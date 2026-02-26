<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MovieController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function (): void {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/movies/trashed', [MovieController::class, 'trashed']);
    Route::apiResource('movies', MovieController::class)->except('create', 'edit');
    Route::post('/movies/{id}/restore', [MovieController::class, 'restore']);
    Route::delete('/movies/{id}/force', [MovieController::class, 'forceDelete']);
});
