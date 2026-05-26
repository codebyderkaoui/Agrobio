<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\FarmController;
use App\Http\Controllers\ClientController;

/*
|--------------------------------------------------------------------------
| API Routes  (prefix: /api)
|--------------------------------------------------------------------------
| All routes require an active session (web auth guard).
| For a decoupled SPA, swap 'auth' for 'auth:sanctum'.
*/

Route::middleware('auth')->group(function () {

    // ── Products ──────────────────────────────────
    Route::get   ('/products',          [ProductController::class, 'apiIndex']);
    Route::post  ('/products',          [ProductController::class, 'apiStore']);
    Route::put   ('/products/{product}', [ProductController::class, 'apiUpdate']);

    // ── Orders ────────────────────────────────────
    Route::get   ('/orders',               [OrderController::class, 'apiIndex']);
    Route::post  ('/orders',               [OrderController::class, 'apiStore']);
    Route::patch ('/orders/{order}/advance',[OrderController::class, 'apiAdvance']);

    // ── Tasks ─────────────────────────────────────
    Route::get   ('/tasks',         [TaskController::class, 'apiIndex']);
    Route::post  ('/tasks',         [TaskController::class, 'apiStore']);
    Route::patch ('/tasks/{task}',  [TaskController::class, 'apiUpdate']);

    // ── Projects ──────────────────────────────────
    Route::get   ('/projects',            [ProjectController::class, 'apiIndex']);
    Route::post  ('/projects',            [ProjectController::class, 'apiStore']);
    Route::patch ('/projects/{project}',  [ProjectController::class, 'apiUpdate']);

    // ── Analytics ─────────────────────────────────
    Route::get('/analytics/summary',      [AnalyticsController::class, 'summary']);
    Route::get('/analytics/monthly',      [AnalyticsController::class, 'monthly']);
    Route::get('/analytics/top-products', [AnalyticsController::class, 'topProducts']);
    Route::get('/analytics/top-farms',    [AnalyticsController::class, 'topFarms']);
    Route::get('/analytics/categories',   [AnalyticsController::class, 'categories']);

    // ── farms and clients ─────────────────────────────────
    Route::get('/farms',                       [FarmController::class,  'apiIndex']);
    Route::get('/clients/{name}/orders',       [ClientController::class, 'orders']);

});

Route::middleware(['auth', 'admin'])->group(function () {
        Route::delete('/products/{product}', [ProductController::class, 'apiDestroy']);
        Route::delete('/orders/{order}',     [OrderController::class, 'apiDestroy']);
        Route::delete('/tasks/{task}',       [TaskController::class, 'apiDestroy']);
        Route::delete('/projects/{project}', [ProjectController::class, 'apiDestroy']); 

});
