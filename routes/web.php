<?php

use App\Http\Controllers\Admin\MovieController as AdminMovieController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('movies.index'))->name('index');

Route::prefix('admin')->as('admin.')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::resource('movies', AdminMovieController::class);
});

Route::prefix('movies')->as('movies.')->group(function () {
    Route::get('/', [MovieController::class, 'index'])->name('index');

    Route::get('/{movie:slug}', [MovieController::class, 'show'])->name('show');
});

Route::post('/logout', LogoutController::class)->middleware('auth')->name('logout');

Route::middleware('guest')->group(function () {
    Route::get('login', fn () => view('auth.login'))->name('login');
    Route::post('login', LoginController::class)->middleware('throttle:5,1')->name('login.attempt');
    Route::view('register', 'auth.register')->name('register');
    Route::post('register', RegisterController::class)->name('register.store');
});
