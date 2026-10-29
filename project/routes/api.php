<?php


use App\Http\Controllers\API\GameController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/game/status', [GameController::class, 'getStatus']);
    Route::post('/game/projects/{id}/start', [GameController::class, 'startProject']);
    Route::post('/game/projects/{id}/claim', [GameController::class, 'claimProjectRewards']);
});