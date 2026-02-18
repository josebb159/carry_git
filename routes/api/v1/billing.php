<?php

use App\Domains\Billing\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::prefix('billing')->middleware('auth:sanctum')->group(function () {
    Route::post('/invoices', [InvoiceController::class, 'store']);
    Route::get('/invoices', [InvoiceController::class, 'index']);
});
