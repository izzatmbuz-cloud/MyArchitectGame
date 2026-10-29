<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_states', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('company_name')->default('Mening Arxitektura Byurom');
            $table->bigInteger('money')->default(25000); // Boshlang'ich pul
            $table->integer('level')->default(1);        // Boshlang'ich daraja
            $table->integer('reputation')->default(0);  // Obro' (tajriba ochkosi)
            $table->integer('energy')->default(100);    // Maksimal 100 energiya
            $table->integer('staff_limit')->default(12); // Maksimal xodimlar soni
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_states');
    }
};
