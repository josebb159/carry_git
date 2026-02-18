<?php

use App\Domains\Dashboard\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')->middleware('auth:sanctum')->group(function () {
    Route::get('/kpis', [DashboardController::class , 'index']);
    Route::get('/fleet/locations', [\App\Domains\Dashboard\Controllers\FleetMapController::class , 'getLocations']);
});
