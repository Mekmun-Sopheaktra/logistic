<?php

use App\Http\Controllers\Employee\DriverManagementController;
use Illuminate\Support\Facades\Route;

Route::prefix('employee')->group(function () {
    // ---------------------------------Public routes---------------------------------
    Route::post('/create-invoice', [DriverManagementController::class, 'createVendorInvoice'])->name('employee.create-invoice');

    //auth
    Route::prefix('')->group(function () {
        //assign driver to package
        Route::post('/assign-driver', [DriverManagementController::class, 'assignDriver'])->name('employee.assign-driver');
    });

    //create vendor invoice

});
