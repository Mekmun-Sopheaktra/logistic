<?php

namespace App\Http\Controllers\Delivery;

use App\Constants\ConstPackageStatus;
use App\Constants\ConstShipmentStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Delivery\HomeResource;
use App\Models\Driver;
use App\Models\Package;
use App\Models\Shipment;
use App\Traits\BaseApiResponse;
use Illuminate\Http\Request;

class DeliveryHomeController extends Controller
{
    use BaseApiResponse;
    public function index(Request $request)
    {
        $user = auth()->user();
        $perPage = $request->query('per_page', env('PAGINATION_PER_PAGE', 10));
        $search = $request->query('search');
        $driver = Driver::query()->where('user_id', $user->id)->first();

        if (!$driver) {
            return $this->error('Driver not found', 404);
        }

        //packages belong to driver
        $packages = Package::query()
            ->when($search, function ($query, $search) {
                return $query->where('number', 'like', '%' . $search . '%');
            })
            ->where('driver_id', $driver->id)
            ->paginate($perPage);

        $count_all = Package::query()->where('driver_id', $driver->id)->count();
        $count_pending = Package::query()->where('driver_id', $driver->id)->where('status', ConstPackageStatus::PENDING)->count();
        $count_in_transit = Package::query()->where('driver_id', $driver->id)->where('status', ConstPackageStatus::IN_TRANSIT)->count();
        $count_completed = Package::query()->where('driver_id', $driver->id)->where('status', ConstPackageStatus::COMPLETED)->count();
        $count_cancelled = Package::query()->where('driver_id', $driver->id)->where('status', ConstPackageStatus::CANCELLED)->count();

        $data = new HomeResource([
            'driver' => $driver,
            'user' => $user,
            'count_all' => $count_all,
            'count_pending' => $count_pending,
            'count_in_transit' => $count_in_transit,
            'count_completed' => $count_completed,
            'count_cancelled' => $count_cancelled,
            'packages' => $packages
        ]);

        return $this->success($data,'Welcome to delivery home page');
    }

    //pickupPackage
    public function pickupPackage(Request $request)
    {
        $user = auth()->user();
        $driver = Driver::query()->where('user_id', $user->id)->first();
        $package_id = $request->id;

        if (!$driver) {
            return $this->error('Driver not found', 404);
        }
        //update package status to picked up
        //Assign Driver Id
        Package::query()->where('id', $package_id)->update(['status' => ConstPackageStatus::IN_TRANSIT]);
        //update shipment status to in transit
        Shipment::query()->where('package_id', $package_id)->update(['status' => ConstShipmentStatus::IN_TRANSIT]);

        return $this->success(null,'Package picked up successfully');
    }

    //deliveredPackage
    public function deliveredPackage(Request $request)
    {
        $user = auth()->user();
        $driver = Driver::query()->where('user_id', $user->id)->first();
        $package_id = $request->id;

        if (!$driver) {
            return $this->error('Driver not found', 404);
        }
        //update package status to delivered
        Package::query()->where('id', $package_id)->update(['status' => ConstPackageStatus::COMPLETED]);
        //update shipment status to delivered
        Shipment::query()->where('package_id', $package_id)->update(['status' => ConstShipmentStatus::COMPLETED]);
        return $this->success(null,'Package delivered successfully');
    }
}