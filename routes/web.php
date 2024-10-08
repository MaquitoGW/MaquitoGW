<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\site\HomeController;
use Illuminate\Support\Facades\Route;

// HOME ROUTE
Route::prefix('/')->group(function () {
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/details/{id}', [HomeController::class, 'index']);
    Route::get('/demo/{id}', [HomeController::class, 'demo']);
});


// ADMIN ROUTE
Route::prefix('admin')->group(function () {
    // AUTH ROUTE
    Route::prefix('auth')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/auth/update', [AuthController::class, 'update'])->name('update.auth');

        Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [AuthController::class, 'register']);

        // Deletar usuario
        Route::get('/delete', function () {
            abort(404);
        })->name('delete');
        Route::get('/delete/{id}', [AuthController::class, 'delete']);
    });

    // ADMIN ROUTES 
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
});
