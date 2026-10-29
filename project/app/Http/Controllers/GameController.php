<?php

// app/Http/Controllers/API/GameController.php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\GameState;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    // O'yinning hozirgi holatini o'qish (Vue.js uchun)
    public function getStatus()
    {
        $user = Auth::user();
        $gameState = GameState::with(['employees', 'projects'])->where('user_id', $user->id)->first();

        if (! $gameState) {
            // Agar foydalanuvchida o'yin holati yo'q bo'lsa, yangi yaratish
            $gameState = GameState::create([
                'user_id' => $user->id,
                'company_name' => "{$user->name} Byurosi",
            ]);

            // Boshlang'ich loyihalarni ham generatsiya qilish mumkin
            $this->generateInitialProjects($gameState);
            $gameState->load(['employees', 'projects']);
        }

        return response()->json($gameState);
    }

    // Loyihani boshlash
    public function startProject(Request $request, $projectId)
    {
        $user = Auth::user();
        $gameState = GameState::where('user_id', $user->id)->firstOrFail();
        $project = Project::where('id', $projectId)->where('game_state_id', $gameState->id)->firstOrFail();

        if ($project->status !== 'pending') {
            return response()->json(['message' => 'Loyiha allaqachon boshlangan yoki tugatilgan!'], 400);
        }

        // Loyihani faollashtirish va boshlanish vaqtini yozish
        $project->update([
            'status' => 'active',
            'started_at' => Carbon::now(),
        ]);

        return response()->json([
            'message' => 'Loyiha muvaffaqiyatli boshlandi!',
            'project' => $project,
        ]);
    }

    // Loyihani yakunlab mukofotlarni yig'ib olish
    public function claimProjectRewards($projectId)
    {
        $user = Auth::user();
        $gameState = GameState::where('user_id', $user->id)->firstOrFail();
        $project = Project::where('id', $projectId)->where('game_state_id', $gameState->id)->firstOrFail();

        if ($project->status !== 'active') {
            return response()->json(['message' => 'Bu loyiha hali faol emas!'], 400);
        }

        // Vaqtni tekshirish
        $finishTime = Carbon::parse($project->started_at)->addSeconds($project->duration_seconds);
        if (Carbon::now()->lt($finishTime)) {
            return response()->json(['message' => 'Loyiha hali yakunlanmagan!'], 400);
        }

        // Loyihani yopish va o'yinchiga pul/obro' berish
        $project->update(['status' => 'completed', 'completed_at' => Carbon::now()]);

        $gameState->increment('money', $project->budget);
        $gameState->increment('reputation', $project->reputation_reward);

        // Darajani (level) ko'tarish mantiqi (masalan: har 1000 reputation 1 level)
        $newLevel = floor($gameState->reputation / 1000) + 1;
        if ($newLevel > $gameState->level) {
            $gameState->update(['level' => $newLevel]);
        }

        return response()->json([
            'message' => 'Loyiha muvaffaqiyatli yakunlandi, mukofotlar olindi!',
            'game_state' => $gameState->fresh(['employees', 'projects']),
        ]);
    }

    private function generateInitialProjects(GameState $gameState)
    {
        Project::create([
            'game_state_id' => $gameState->id,
            'title' => 'Turar-joy binosi',
            'description' => 'Kichik 2 qavatli turar-joy binoning loyihasi.',
            'budget' => 1000,
            'reputation_reward' => 150,
            'duration_seconds' => 30, // Test uchun 30 sekund
            'status' => 'pending',
        ]);

        Project::create([
            'game_state_id' => $gameState->id,
            'title' => 'Biznes markazi',
            'description' => 'Shahar markazidagi zamonaviy ofis binosi.',
            'budget' => 30000,
            'reputation_reward' => 1200,
            'duration_seconds' => 3600, // 1 soat
            'status' => 'pending',
        ]);
    }
}
