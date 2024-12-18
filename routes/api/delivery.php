<?php

use App\Http\Controllers\Delivery\AuthController;
use Illuminate\Support\Facades\Route;


Route::prefix('delivery')->group(function () {
    // ---------------------------------Public routes---------------------------------

    //auth
    Route::prefix('')->group(function () {
        Route::post('/register', [AuthController::class, 'register'])->name('vendor.register');
        Route::post('/login', [AuthController::class, 'login'])->name('vendor.login');

    });


    // ---------------------------------Protected routes---------------------------------
    Route::middleware(['auth:api', 'delivery.access'])->group(function () {
        Route::get('/me', [AuthController::class, 'me'])->name('vendor.me');
        Route::post('/logout', [AuthController::class, 'logout'])->name('vendor.logout');


        //package
    });
});
