<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Package;
use App\Traits\BaseApiResponse;
use Illuminate\Http\Request;

class DriverManagementController extends Controller
{
    use BaseApiResponse;
    //assignDriver
    public function assignDriver(Request $request)
    {
        //validate request
        $request->validate([
            'package_id' => 'required',
            'driver_id' => 'required',
        ]);

        //check if driver is invalid
        $driver = Driver::find($request->driver_id);
        if (!$driver) {
            return $this->failed(null, 'Driver not found', 'Driver not found', 404);
        }

        //validate package id and check if package is invalid
        $package = Package::find($request->package_id);
        if (!$package) {
            return $this->failed(null, 'Package not found', 'Package not found', 404);
        }
        $package->driver_id = $request->driver_id;
        $package->save();

        //return response
        return $this->success($package, 'Driver assigned successfully');
    }
}
