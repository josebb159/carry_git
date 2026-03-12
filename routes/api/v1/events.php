<?php

use App\Domains\Events\Controllers\EventController;
use Illuminate\Support\Facades\Route;

Route::prefix('orders/{orderId}/events')->middleware('auth:sanctum')->group(function () {
    Route::post('/', [EventController::class, 'store']);
    Route::get('/', [EventController::class, 'index']);
});
