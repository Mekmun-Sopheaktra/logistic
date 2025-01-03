<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class DriverManagementController extends Controller
{
    //assignDriver
    public function assignDriver(Request $request)
    {
        //validate request
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'driver_id' => 'required|exists:drivers,id',
        ]);

        //assign driver to package
        $package = Package::find($request->package_id);
        $package->driver_id = $request->driver_id;
        $package->save();

        //return response
        return response()->json([
            'message' => 'Driver assigned to package successfully',
            'package' => $package
        ]);
    }
}
