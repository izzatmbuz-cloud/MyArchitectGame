<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_state_id', 'title', 'description', 'budget', 'reputation_reward', 'duration_seconds', 'status', 'started_at', 'completed_at'
    ];

    public function gameState()
    {
        return $this->belongsTo(GameState::class);
    }
}