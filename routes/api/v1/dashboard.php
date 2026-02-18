<?php

use App\Domains\Dashboard\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')->middleware('auth:sanctum')->group(function () {
    Route::get('/kpis', [DashboardController::class, 'index']);
});
