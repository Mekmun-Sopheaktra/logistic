<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
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

    });
});
