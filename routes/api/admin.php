<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeliveryUserController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\TrackingController;
use App\Http\Controllers\Admin\VendorUserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    // ---------------------------------Public routes---------------------------------

    Route::prefix('')->group(function () {
        Route::post('/register', [AuthController::class, 'register'])->name('vendor.register');
        Route::post('/login', [AuthController::class, 'login'])->name('vendor.login');
    });

    // ---------------------------------Protected routes---------------------------------
    Route::middleware(['auth:api', 'admin.access'])->group(function () {
        Route::get('/me', [AuthController::class, 'me'])->name('vendor.me');
        Route::post('/logout', [AuthController::class, 'logout'])->name('vendor.logout');

        //dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        Route::get('/package-invoice', [InvoiceController::class, 'packagesInvoice'])->name('admin.package.invoice');
        Route::get('/vendor-invoice', [InvoiceController::class, 'vendorInvoice'])->name('admin.vendor.invoice');

        //user management
        Route::prefix('vendors')->group(function () {
            Route::get('/', [VendorUserController::class, 'index'])->name('admin.users');
            Route::post('/', [VendorUserController::class, 'store'])->name('admin.users.store');
            Route::get('/{id}', [VendorUserController::class, 'show'])->name('admin.users.show');
            Route::post('/{id}', [VendorUserController::class, 'update'])->name('admin.users.update');
            Route::delete('/{id}', [VendorUserController::class, 'destroy'])->name('admin.users.delete');
        });

        Route::prefix('drivers')->group(function () {
            Route::get('/', [DeliveryUserController::class, 'index'])->name('drivers.users');
            Route::post('/', [DeliveryUserController::class, 'store'])->name('drivers.users.store');
            Route::get('/{id}', [DeliveryUserController::class, 'show'])->name('drivers.users.show');
            Route::post('/{id}', [DeliveryUserController::class, 'update'])->name('drivers.users.update');
            Route::delete('/{id}', [DeliveryUserController::class, 'destroy'])->name('drivers.users.delete');
        });

        //tracking
        Route::prefix('tracking')->group(function () {
            Route::get('/', [TrackingController::class, 'index'])->name('drivers.tracking');
            Route::get('/{id}', [TrackingController::class, 'show'])->name('drivers.tracking.show');
        });
    });
});
