<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [App\Http\Controllers\Api\AuthController::class , 'login']);

Route::prefix('v1')->group(function () {
    require base_path('routes/api/v1/auth.php');
    require base_path('routes/api/v1/orders.php');
    require base_path('routes/api/v1/events.php');
    require base_path('routes/api/v1/billing.php');
    require base_path('routes/api/v1/dashboard.php');
    require base_path('routes/api/v1/users.php');
    require base_path('routes/api/v1/fleet.php');
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
