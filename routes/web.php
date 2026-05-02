<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\Auth\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ── Guest only ────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

// ── Authenticated pages ───────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    Route::post('/logout',   [AuthController::class,     'logout'])->name('logout');
    Route::get('/',          [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/products',  [ProductController::class,   'index'])->name('products.index');
    Route::get('/orders',    [OrderController::class,     'index'])->name('orders.index');
    Route::get('/tasks',     [TaskController::class,      'index'])->name('tasks.index');
    Route::get('/projects',  [ProjectController::class,   'index'])->name('projects.index');
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

});
