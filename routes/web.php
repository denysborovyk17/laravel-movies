<?php declare(strict_types=1);

use App\Http\Controllers\Admin\MovieController as AdminMovieController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\UsersExportController;
use App\Http\Controllers\MoviesExportController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('movies.index'))->name('index');

Route::prefix('admin')->as('admin.')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::resource('movies', AdminMovieController::class);
});

Route::prefix('movies')->as('movies.')->group(function () {
    Route::get('/', [MovieController::class, 'index'])->name('index');

    Route::get('/{movie:slug}', [MovieController::class, 'show'])->name('show');
});

Route::get('/me', [AuthController::class, 'me'])->middleware('auth')->name('me');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('guest')->group(function () {
    Route::view('login', 'auth.login')->name('login');
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.attempt');
    Route::view('register', 'auth.register')->name('register');
    Route::post('register', [AuthController::class, 'register'])->name('register.store');
});

Route::prefix('export')->as('export.')->group(function () {
    Route::get('users', [UsersExportController::class, 'export']);
    Route::get('movies', [MoviesExportController::class, 'export']);
});
