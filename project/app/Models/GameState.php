<?php

// app/Models/GameState.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameState extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'company_name', 'money', 'level', 'reputation', 'energy', 'staff_limit',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
