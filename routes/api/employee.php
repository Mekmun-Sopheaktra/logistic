<?php

use App\Http\Controllers\Employee\DriverManagementController;
use Illuminate\Support\Facades\Route;

Route::prefix('employee')->group(function () {
    // ---------------------------------Public routes---------------------------------

    //auth
    Route::prefix('')->group(function () {
        //assign driver to package
        Route::post('/assign-driver', [DriverManagementController::class, 'assignDriver'])->name('employee.assign-driver');
    });

});
