<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_state_id', 'name', 'role', 'level', 'experience', 'salary', 'avatar'
    ];

    public function gameState()
    {
        return $this->belongsTo(GameState::class);
    }
}