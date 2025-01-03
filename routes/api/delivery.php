<?php

use App\Http\Controllers\Delivery\AuthController;
use App\Http\Controllers\Delivery\DeliveryHomeController;
use Illuminate\Support\Facades\Route;


Route::prefix('delivery')->group(function () {
    // ---------------------------------Public routes---------------------------------

    //auth
    Route::prefix('')->group(function () {
        Route::post('/register', [AuthController::class, 'register'])->name('delivery.register');
        Route::post('/login', [AuthController::class, 'login'])->name('delivery.login');

        Route::get('email/verify/{id}', [AuthController::class, 'verify'])->name('delivery.verification.verify');
    });


    // ---------------------------------Protected routes---------------------------------
    Route::middleware(['auth:api', 'delivery.access'])->group(function () {

        Route::get('/me', [AuthController::class, 'me'])->name('delivery.me');
        Route::post('/logout', [AuthController::class, 'logout'])->name('delivery.logout');
        Route::get('/home', [DeliveryHomeController::class, 'index'])->name('delivery.home');
        Route::post('/pickup/{id}', [DeliveryHomeController::class, 'pickupPackage'])->name('delivery.pickup-package');
        Route::post('/delivered/{id}', [DeliveryHomeController::class, 'deliveredPackage'])->name('delivery.delivered-package');

        Route::prefix('profile')->group(function () {
        });
    });
});
