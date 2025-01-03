<?php

use App\Http\Controllers\Vendor\AuthController;
use App\Http\Controllers\Vendor\PackageController;
use Illuminate\Support\Facades\Route;
Route::prefix('vendor')->group(function () {
    // ---------------------------------Public routes---------------------------------

    //auth
    Route::prefix('')->group(function () {
        Route::post('/register', [AuthController::class, 'register'])->name('vendor.register');
        Route::post('/login', [AuthController::class, 'login'])->name('vendor.login');

        Route::post('/request-vendor', [AuthController::class, 'requestVendor'])->name('vendor.login');
    });


    // ---------------------------------Protected routes---------------------------------
    Route::middleware(['auth:api', 'vendor.access'])->group(function () {
        Route::get('/me', [AuthController::class, 'me'])->name('vendor.me');
        Route::post('/logout', [AuthController::class, 'logout'])->name('vendor.logout');

        //package
        Route::prefix('packages')->group(function () {
            Route::get('', [PackageController::class, 'index'])->name('vendor.packages.index');
            //history
            Route::get('history', [PackageController::class, 'history'])->name('vendor.packages.history');
            Route::post('', [PackageController::class, 'store'])->name('vendor.packages.store');
            Route::get('{id}', [PackageController::class, 'show'])->name('vendor.packages.show');
            Route::put('{id}', [PackageController::class, 'update'])->name('vendor.packages.update');
            Route::delete('{id}', [PackageController::class, 'destroy'])->name('vendor.packages.destroy');

            //search package by number
            Route::get('search/{number}', [PackageController::class, 'search'])->name('vendor.packages.search');
        });
    });
});
