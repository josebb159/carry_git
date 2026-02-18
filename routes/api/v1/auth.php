<?php

use App\Domains\Auth\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        Route::put('/profile', [\App\Http\Controllers\Api\V1\ProfileController::class, 'update']);
        Route::put('/profile/password', [\App\Http\Controllers\Api\V1\ProfileController::class, 'password']);
    });
});
