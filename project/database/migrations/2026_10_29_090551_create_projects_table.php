<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_state_id')->constrained()->onDelete('cascade');
            $table->string('title'); // Masalan: Turar-joy binosi
            $table->text('description')->nullable();
            $table->bigInteger('budget'); // O'yinchi oladigan pul
            $table->integer('reputation_reward'); // Obro' mukofoti
            $table->integer('duration_seconds'); // Bajarish muddati (sekundda)
            $table->enum('status', ['pending', 'active', 'completed'])->default('pending');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable(); // Bajarib bo'lingan vaqt
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
