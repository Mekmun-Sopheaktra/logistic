<?php

namespace App\Http\Controllers\Employee;

use App\Constants\ConstShipmentStatus;
use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Package;
use App\Models\Shipment;
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
            'shipment_number' => 'required',
            'shipment_type' => 'required',
            'shipment_description' => 'required',
            'shipment_date' => 'required',
            'shipment_delivery_fee' => 'required',
        ]);

        //check if driver is invalid
        $driver = Driver::find($request->driver_id);
        if (!$driver) {
            return $this->failed(null, 'Driver not found', 'Driver not found', 404);
        }
        $shipment = new Shipment();
        $shipment->package_id = $request->package_id;
        $shipment->number = $request->shipment_number;
        $shipment->type = $request->shipment_type;
        $shipment->description = $request->shipment_description;
        $shipment->date = $request->shipment_date;
        $shipment->delivery_fee = $request->shipment_delivery_fee;
        $shipment->status = ConstShipmentStatus::PENDING;
        $shipment->save();

        //validate package id and check if package is invalid
        $package = Package::find($request->package_id);
        if (!$package) {
            return $this->failed(null, 'Package not found', 'Package not found', 404);
        }
        $package->driver_id = $request->driver_id;
        $package->shipment_id = $shipment->id;
        $package->save();

        //return response
        return $this->success($package, 'Driver assigned successfully');
    }
}
