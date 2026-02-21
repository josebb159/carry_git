<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);

Route::prefix('v1')->group(function () {
    require base_path('routes/api/v1/auth.php');
    require base_path('routes/api/v1/orders.php');
    require base_path('routes/api/v1/events.php');
    require base_path('routes/api/v1/billing.php');
    require base_path('routes/api/v1/dashboard.php');
    require base_path('routes/api/v1/users.php');
    require base_path('routes/api/v1/fleet.php');
});

/*
|--------------------------------------------------------------------------
| v10 Merchant App API
|--------------------------------------------------------------------------
| All routes are protected by the 'api.key' middleware which validates
| the required 'apiKey: 123456rx-ecourier123456' header.
| Authenticated routes inside the file additionally require auth:sanctum.
*/
Route::prefix('v10')->middleware('api.key')->group(function () {
    require base_path('routes/api/v10/merchant.php');
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
