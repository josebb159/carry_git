<?php

use App\Http\Controllers\Api\V10\MerchantAuthController;
use App\Http\Controllers\Api\V10\MerchantDashboardController;
use App\Http\Controllers\Api\V10\MerchantFraudController;
use App\Http\Controllers\Api\V10\MerchantHubController;
use App\Http\Controllers\Api\V10\MerchantInvoiceController;
use App\Http\Controllers\Api\V10\MerchantNewsController;
use App\Http\Controllers\Api\V10\MerchantParcelController;
use App\Http\Controllers\Api\V10\MerchantPaymentController;
use App\Http\Controllers\Api\V10\MerchantProfileController;
use App\Http\Controllers\Api\V10\MerchantSettingsController;
use App\Http\Controllers\Api\V10\MerchantShopController;
use App\Http\Controllers\Api\V10\MerchantSupportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| v10 Merchant API Routes
|--------------------------------------------------------------------------
| All routes here are already wrapped inside:
|   Route::prefix('v10')->middleware('api.key')->group(...)
| in routes/api.php
*/

// ── PUBLIC (apiKey only, no auth token required) ──────────────────────────
Route::get('general-settings', [MerchantSettingsController::class, 'generalSettings']);
Route::get('hub', [MerchantHubController::class, 'index']);
Route::post('hub', [MerchantHubController::class, 'index']);

Route::post('signin', [MerchantAuthController::class, 'signin']);
Route::post('register', [MerchantAuthController::class, 'register']);
Route::post('otp-login', [MerchantAuthController::class, 'otpLogin']);
Route::post('resend-otp', [MerchantAuthController::class, 'resendOtp']);
Route::post('otp-verification', [MerchantAuthController::class, 'otpVerification']);

// ── PROTECTED (apiKey + auth:sanctum) ─────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('sign-out', [MerchantAuthController::class, 'signOut']);
    Route::post('refresh', [MerchantAuthController::class, 'refresh']);

    // Profile & Account
    Route::get('profile', [MerchantProfileController::class, 'show']);
    Route::post('profile/update', [MerchantProfileController::class, 'update']);
    Route::post('update-password', [MerchantProfileController::class, 'updatePassword']);
    Route::post('device', [MerchantProfileController::class, 'registerDevice']);
    Route::post('fcm-subscribe', [MerchantProfileController::class, 'fcmSubscribe']);
    Route::post('fcm-unsubscribe', [MerchantProfileController::class, 'fcmUnsubscribe']);

    // Settings (protected ones)
    Route::get('settings/delivery-charges', [MerchantSettingsController::class, 'deliveryCharges']);
    Route::get('settings/cod-charges', [MerchantSettingsController::class, 'codCharges']);

    // Dashboard
    Route::get('dashboard', [MerchantDashboardController::class, 'index']);
    Route::get('dashboard/balance-details', [MerchantDashboardController::class, 'balanceDetails']);
    Route::get('dashboard/available-parcels', [MerchantDashboardController::class, 'availableParcels']);
    Route::get('analytics', [MerchantDashboardController::class, 'analytics']);

    // Parcels
    Route::get('parcel/index', [MerchantParcelController::class, 'index']);
    Route::get('parcel/filter', [MerchantParcelController::class, 'filter']);
    Route::get('parcel/create', [MerchantParcelController::class, 'create']);
    Route::post('parcel/store', [MerchantParcelController::class, 'store']);
    Route::get('parcel/details/{id}', [MerchantParcelController::class, 'show']);
    Route::get('parcel/logs/{id}', [MerchantParcelController::class, 'logs']);
    Route::get('parcel/edit/{id}', [MerchantParcelController::class, 'edit']);
    Route::put('parcel/update/{id}', [MerchantParcelController::class, 'update']);
    Route::delete('parcel/delete/{id}', [MerchantParcelController::class, 'destroy']);
    Route::get('parcel/all/status', [MerchantParcelController::class, 'allStatuses']);
    Route::get('status-wise/parcel/list/{status}', [MerchantParcelController::class, 'byStatus']);

    // Shops
    Route::get('shops/index', [MerchantShopController::class, 'index']);
    Route::post('shops/store', [MerchantShopController::class, 'store']);
    Route::put('shops/update/{id}', [MerchantShopController::class, 'update']);
    Route::delete('shops/delete/{id}', [MerchantShopController::class, 'destroy']);

    // Payment Accounts
    Route::get('payment-accounts/index', [MerchantPaymentController::class, 'accountIndex']);
    Route::post('payment-account/store', [MerchantPaymentController::class, 'accountStore']);

    // Payment Requests
    Route::get('payment-request/index', [MerchantPaymentController::class, 'requestIndex']);
    Route::post('payment-request/store', [MerchantPaymentController::class, 'requestStore']);

    // Statements
    Route::get('statements/index', [MerchantPaymentController::class, 'statements']);
    Route::get('statement-reports', [MerchantPaymentController::class, 'statementReports']);

    // Transactions
    Route::get('account-transaction/index', [MerchantPaymentController::class, 'transactionIndex']);
    Route::get('account-transaction/filter', [MerchantPaymentController::class, 'transactionFilter']);

    // Wallet
    Route::get('merchant/wallet', [MerchantPaymentController::class, 'wallet']);
    Route::post('merchant/wallet/recharge-add', [MerchantPaymentController::class, 'walletRecharge']);

    // Fraud Reports
    Route::get('fraud/index', [MerchantFraudController::class, 'index']);
    Route::post('fraud/store', [MerchantFraudController::class, 'store']);
    Route::get('fraud/edit/{id}', [MerchantFraudController::class, 'edit']);
    Route::put('fraud/update/{id}', [MerchantFraudController::class, 'update']);
    Route::delete('fraud/delete/{id}', [MerchantFraudController::class, 'destroy']);

    // Support
    Route::get('support/index', [MerchantSupportController::class, 'index']);
    Route::post('support/store', [MerchantSupportController::class, 'store']);
    Route::delete('support/delete/{id}', [MerchantSupportController::class, 'destroy']);

    // News & Offers
    Route::get('news-offer/index', [MerchantNewsController::class, 'index']);

    // Invoices
    Route::get('invoice-list/index', [MerchantInvoiceController::class, 'index']);
    Route::get('invoice-details/{id}', [MerchantInvoiceController::class, 'show']);
});
