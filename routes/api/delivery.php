<?php

use App\Http\Controllers\Delivery\AuthController;
use App\Http\Controllers\Delivery\DeliveryHomeController;
use Illuminate\Support\Facades\Route;


Route::prefix('delivery')->group(function () {
    // ---------------------------------Public routes---------------------------------

    //auth
    Route::prefix('')->group(function () {
        Route::post('/register', [AuthController::class, 'register'])->name('vendor.register');
        Route::post('/login', [AuthController::class, 'login'])->name('vendor.login');

        Route::get('email/verify/{id}', [AuthController::class, 'verify'])->name('vendor.verification.verify');
    });


    // ---------------------------------Protected routes---------------------------------
    Route::middleware(['auth:api', 'delivery.access'])->group(function () {
        Route::get('/me', [AuthController::class, 'me'])->name('vendor.me');
        Route::post('/logout', [AuthController::class, 'logout'])->name('vendor.logout');


        //home
        Route::get('/home', [DeliveryHomeController::class, 'index'])->name('vendor.home');
    });
});
