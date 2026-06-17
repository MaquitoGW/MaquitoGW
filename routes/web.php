<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\ContactController;
use App\Http\Controllers\admin\CustomizationController;
use App\Http\Controllers\admin\ExperienceController;
use App\Http\Controllers\admin\InfoController;
use App\Http\Controllers\admin\LinkController;
use App\Http\Controllers\admin\ProjectController;
use App\Http\Controllers\admin\SettingsController;
use App\Http\Controllers\admin\SkillController;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\site\ContactController as SiteContactController;
use App\Http\Controllers\site\HomeController;
use App\Http\Controllers\site\LinksController;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\RandQuote;
use App\Http\Middleware\UserLanguage;
use Illuminate\Support\Facades\Route;

// HOME ROUTE
Route::middleware(UserLanguage::class, RandQuote::class)->prefix('/')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('index');
    Route::get('/details/{id}', [HomeController::class, 'details']);
    Route::post('/contact', [SiteContactController::class, 'store'])->name('contact.store');
});

// PUBLIC LINKS ROUTE
Route::get('/{slug}', [LinksController::class, 'show'])->name('public.links')->where('slug', '[a-zA-Z0-9_-]+');

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

        // Skills routes
        Route::prefix('skills')->group(function () {
            Route::get('/', [SkillController::class, 'index'])->name('skills');
            Route::get('/new', [SkillController::class, 'index'])->name('skill.new');
            Route::post('/add', [SkillController::class, 'add'])->name('skill.add');
            Route::get('/delete/{code}', [SkillController::class, 'delete'])->name('skills.delete');
        });

        // Info routes
        Route::prefix('info')->group(function () {
            Route::get('/', [InfoController::class, 'index'])->name('info');
            Route::post('update/info', [InfoController::class, 'updateInfo'])->name('info.update');
            Route::post('add/info', [InfoController::class, 'addInfo'])->name('info.add');
            Route::post('update/contacts', [InfoController::class, 'updateContacts'])->name('contacts.update');
            Route::post('add/contacts', [InfoController::class, 'addContacts'])->name('contacts.add');
            Route::get('add/en_US', [InfoController::class, 'index'])->name('info.add.en_US');
            Route::get('add/pt_BR', [InfoController::class, 'index'])->name('info.add.pt_BR');
        });

        // Projects routes
        Route::prefix('projects')->group(function () {
            Route::get('/', [ProjectController::class, 'index'])->name('projects');
            Route::get('new', [ProjectController::class, 'create'])->name('projects.new');
            Route::post('add', [ProjectController::class, 'store'])->name('projects.add');
            Route::get('edit/{uuid}', [ProjectController::class, 'edit']);
            Route::post('update/{uuid}', [ProjectController::class, 'update']);
            Route::get('delete/{uuid}', [ProjectController::class, 'destroy']);
            Route::get('files/{uuid}', [ProjectController::class, 'files'])->name('projects.files');
            Route::post('files/{uuid}/upload', [ProjectController::class, 'uploadFile'])->name('projects.files.upload');
            Route::post('files/{uuid}/folder', [ProjectController::class, 'createFolder'])->name('projects.files.folder');
            Route::post('files/{uuid}/delete', [ProjectController::class, 'deleteFile'])->name('projects.files.delete');
            Route::post('files/{uuid}/save', [ProjectController::class, 'saveFile'])->name('projects.files.save');
        });

        // Customization routes
        Route::prefix('customization')->group(function () {
            Route::get('/', [CustomizationController::class, 'index'])->name('customization');
            Route::post('update', [CustomizationController::class, 'update'])->name('customization.update');
            Route::post('update/images', [CustomizationController::class, 'updateImages'])->name('customization.update.images');
        });

        // Settings routes
        Route::prefix('settings')->group(function () {
            Route::get('/', [SettingsController::class, 'index'])->name('settings');
            Route::post('update', [SettingsController::class, 'update'])->name('settings.update');
        });

        // Links routes
        Route::prefix('links')->group(function () {
            Route::get('/', [LinkController::class, 'index'])->name('links');
            Route::get('new', [LinkController::class, 'create'])->name('links.new');
            Route::post('add', [LinkController::class, 'store'])->name('links.add');
            Route::get('edit/{link}', [LinkController::class, 'edit'])->name('links.edit');
            Route::post('update/{link}', [LinkController::class, 'update'])->name('links.update');
            Route::get('delete/{link}', [LinkController::class, 'destroy'])->name('links.delete');
            Route::post('toggle/{link}', [LinkController::class, 'toggleActive'])->name('links.toggle');
            Route::post('order', [LinkController::class, 'updateOrder'])->name('links.order');
            Route::post('slug', [LinkController::class, 'updateSlug'])->name('links.slug');
            Route::post('profile', [LinkController::class, 'updateProfile'])->name('links.profile');
        });

        // Experiences routes
        Route::prefix('experiences')->group(function () {
            Route::get('/', [ExperienceController::class, 'index'])->name('experiences');
            Route::get('new', [ExperienceController::class, 'create'])->name('experiences.new');
            Route::post('add', [ExperienceController::class, 'store'])->name('experiences.add');
            Route::get('edit/{experience}', [ExperienceController::class, 'edit'])->name('experiences.edit');
            Route::post('update/{experience}', [ExperienceController::class, 'update'])->name('experiences.update');
            Route::get('delete/{experience}', [ExperienceController::class, 'destroy'])->name('experiences.delete');
        });

        // Contact Messages routes
        Route::prefix('contacts')->group(function () {
            Route::get('/', [ContactController::class, 'index'])->name('contacts');
            Route::get('show/{contactMessage}', [ContactController::class, 'show'])->name('contacts.show');
            Route::post('mark-read/{contactMessage}', [ContactController::class, 'markAsRead'])->name('contacts.mark-read');
            Route::post('mark-responded/{contactMessage}', [ContactController::class, 'markAsResponded'])->name('contacts.mark-responded');
            Route::get('delete/{contactMessage}', [ContactController::class, 'destroy'])->name('contacts.delete');
        });
    });
});
