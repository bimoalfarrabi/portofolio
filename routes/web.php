<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Share\ShareProjectController;
use App\Http\Controllers\Admin\Projects\ProjectController;
use App\Http\Controllers\Admin\Skills\SkillController;
use App\Http\Controllers\Admin\Logs\LogController;
use App\Http\Controllers\Admin\Stats\StatController;
use App\Http\Controllers\Admin\Users\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Collab\CollabController;
use App\Http\Controllers\Collab\CollabMessageController;
use App\Http\Controllers\Admin\Messages\MessageController;
use App\Models\PortfolioCollab;
use App\Models\PortfolioLog;
use App\Models\PortfolioProject;
use App\Models\PortfolioSkill;
use App\Models\PortfolioStat;
use Illuminate\Support\Facades\Route;

// Public routes — available as both / (ID) and /en/ (EN)
// The SetLocale middleware reads the first URL segment to set the locale.
$publicRoutes = function () {
    Route::get('/', function () {
        return view('welcome', [
            'locale' => app()->getLocale(),
            'portfolioData' => [
                'projects' => PortfolioProject::query()->where('is_published', true)->orderBy('sort_order')->get(),
                'skills' => PortfolioSkill::query()->where('is_active', true)->orderBy('sort_order')->get(),
                'logs' => PortfolioLog::query()->where('is_published', true)->orderBy('sort_order')->get(),
                'stats' => PortfolioStat::query()->where('is_active', true)->orderBy('sort_order')->get(),
                'collab' => PortfolioCollab::current(),
            ],
        ]);
    })->name('home');

    Route::get('/p/{project}', [ShareProjectController::class, 'show'])
        ->name('share.project');
    Route::get('/p/{project}/og.png', [ShareProjectController::class, 'image'])
        ->name('share.project.image');

    Route::post('/collab/messages', [CollabMessageController::class, 'store'])
        ->name('collab.messages.store');
};

// ID (default) — no prefix
Route::middleware('set.locale')->group($publicRoutes);

// EN — /en prefix
Route::prefix('en')->name('en.')->middleware('set.locale')->group($publicRoutes);

Route::get('/login', fn () => redirect()->route('admin.login'))->name('login');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'create'])->name('login');
        Route::post('/login', [AuthController::class, 'store'])->name('login.store');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

        Route::resource('projects', ProjectController::class)->except(['show']);
        Route::patch('/skills/reorder', [SkillController::class, 'reorder'])->name('skills.reorder');
        Route::resource('skills', SkillController::class)->except(['show']);
        Route::resource('logs', LogController::class)->except(['show']);
        Route::resource('stats', StatController::class)->except(['show']);
        Route::resource('users', UserController::class)->except(['show']);
        Route::patch('/users/{user}/toggle-admin', [UserController::class, 'toggleAdmin'])->name('users.toggle-admin');
        Route::get('/collab', [CollabController::class, 'edit'])->name('collab.edit');
        Route::put('/collab', [CollabController::class, 'update'])->name('collab.update');
        Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
        Route::get('/messages/unread-count', [MessageController::class, 'unreadCount'])->name('messages.unread-count');
        Route::patch('/messages/{message}/read', [MessageController::class, 'toggleRead'])->name('messages.toggle-read');
        Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
    });
});
