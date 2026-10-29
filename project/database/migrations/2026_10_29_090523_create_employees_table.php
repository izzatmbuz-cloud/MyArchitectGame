<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_state_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('role', ['muhandis', 'dizayner', 'loyiha_rahbari']);
            $table->integer('level')->default(1);
            $table->integer('experience')->default(0);
            $table->integer('salary')->default(500); // Oylik maoshi
            $table->string('avatar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
