<?php

use App\Domains\Fleet\Controllers\TrackingController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('fleet')->group(function () {
    Route::post('/tracking', [TrackingController::class , 'store']);
});
