<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\site\HomeController;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\RandQuote;
use App\Http\Middleware\UserLanguage;
use Illuminate\Support\Facades\Route;

// HOME ROUTE
Route::middleware(UserLanguage::class, RandQuote::class)->prefix('/')->group(function () {
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
    Route::middleware(AuthMiddleware::class)->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');

        Route::get('/skills', [AdminController::class, 'skills'])->name('skills');
        Route::get('/skills/new', [AdminController::class, 'skills'])->name('skill.new');
        Route::post('/skills/add', [AdminController::class, 'skillAdd'])->name('skill.add');
        Route::get('/skills/delete/{code}', [AdminController::class, 'skillDelete']);

        // INFOS GROUP ROUTE
        Route::prefix('info')->group(function () {
            Route::get('/', [AdminController::class, 'info'])->name('info');

            Route::post('update/info', [AdminController::class, 'updateInfo'])->name('info.update');
            Route::post('add/info', [AdminController::class, 'addInfo'])->name('info.add');

            Route::post('update/contacts', [AdminController::class, 'updateContacts'])->name('contacts.update');
            Route::post('add/contacts', [AdminController::class, 'addContacts'])->name('contacts.add');

            Route::get('add/en_US', [AdminController::class, 'info'])->name('info.add.en_US');
            Route::get('add/pt_BR', [AdminController::class, 'info'])->name('info.add.pt_BR');
        });

        
        Route::get('customization', [AdminController::class, 'info'])->name('customization');
        Route::get('settings', [AdminController::class, 'info'])->name('info');
    });
});
