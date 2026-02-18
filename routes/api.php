<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\LogoutController;
use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function (): void {
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('login', [LoginController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::get('me', [UserController::class, 'me']);
        Route::post('logout', [LogoutController::class, 'logout']);
    });
});

Route::get('movies', [MovieController::class, 'index']);
Route::get('movies/{id}', [MovieController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('movies', MovieController::class)->except('create', 'edit');
});
