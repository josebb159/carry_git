<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\ClientController;
use App\Http\Controllers\Web\CarrierController;
use App\Http\Controllers\Web\BillingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class , 'index'])->name('dashboard');

    // Orders
    Route::get('/orders', [OrderController::class , 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class , 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class , 'store'])->name('orders.store');
    Route::get('/orders/{uuid}', [OrderController::class , 'show'])->name('orders.show');

    // Clients
    Route::get('/clients', [ClientController::class , 'index'])->name('clients.index');
    Route::get('/clients/create', [ClientController::class , 'create'])->name('clients.create');
    Route::post('/clients', [ClientController::class , 'store'])->name('clients.store');

    // Carriers
    Route::get('/carriers', [CarrierController::class , 'index'])->name('carriers.index');
    Route::get('/carriers/create', [CarrierController::class , 'create'])->name('carriers.create');
    Route::post('/carriers', [CarrierController::class , 'store'])->name('carriers.store');

    // Billing (Admin Only)
    Route::get('/billing', [BillingController::class , 'index'])
        ->name('billing.index')
        ->middleware('role:admin');

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [App\Http\Controllers\Web\NotificationController::class , 'index'])->name('index');
            Route::post('/{id}/read', [App\Http\Controllers\Web\NotificationController::class , 'markAsRead'])->name('markAsRead');
            Route::post('/read-all', [App\Http\Controllers\Web\NotificationController::class , 'markAllRead'])->name('markAllRead');
        }
        );

        // Users
        Route::resource('users', \App\Http\Controllers\Web\UserController::class)->middleware('role:admin');

        // Fleet (Admin/Agent)
        Route::middleware('role:admin|agent')->group(function () {
            Route::get('/fleet', [\App\Http\Controllers\Web\FleetController::class , 'index'])->name('fleet.index');
            Route::get('/fleet/create', [\App\Http\Controllers\Web\FleetController::class , 'create'])->name('fleet.create');
            Route::post('/fleet', [\App\Http\Controllers\Web\FleetController::class , 'store'])->name('fleet.store');
        }
        );

        // Fleet Tracking Map (Admin)
        Route::get('/admin/fleet/map', [\App\Domains\Dashboard\Controllers\FleetMapController::class , 'index'])
            ->name('fleet.map')
            ->middleware('role:admin');    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class , 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class , 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class , 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
