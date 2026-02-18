<?php

use App\Domains\Users\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'role:admin'])->prefix('users')->group(function () {
    Route::get('/', [UserController::class , 'index']);
    Route::post('/{user}/reset-device', [UserController::class , 'resetDevice']);
});
