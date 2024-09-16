<?php

use App\Http\Controllers\site\HomeController;
use Illuminate\Support\Facades\Route;

Route::prefix('/')->group(function () {
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/details/{id}', [HomeController::class, 'index']);
    Route::get('/demo/{id}', [HomeController::class, 'demo']);
});
