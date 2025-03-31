<?php

use App\Http\Controllers\Employee\AuthController;
use App\Http\Controllers\Employee\DriverManagementController;
use Illuminate\Support\Facades\Route;

Route::prefix('employee')->group(function () {
    // ---------------------------------Public routes---------------------------------
    Route::prefix('')->group(function () {
        Route::post('/register', [AuthController::class, 'register'])->name('employee.register');
        Route::post('/login', [AuthController::class, 'login'])->name('employee.login');
    });

    // ---------------------------------Protected routes---------------------------------
    Route::middleware(['auth:api', 'employee.access'])->group(function () {
        //assign driver to package
        Route::post('/assign-driver', [DriverManagementController::class, 'assignDriver'])->name('employee.assign-driver');
        Route::post('/create-invoice', [DriverManagementController::class, 'createVendorInvoice'])->name('employee.create-invoice');
    });

    //create vendor invoice

});
