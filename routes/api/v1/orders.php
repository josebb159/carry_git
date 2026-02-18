<?php

use App\Domains\Orders\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('orders')->middleware('auth:sanctum')->group(function () {
    Route::post('/', [OrderController::class, 'store']);
    Route::get('/', [OrderController::class, 'index']);
    Route::get('/{id}', [OrderController::class, 'show']);
});
