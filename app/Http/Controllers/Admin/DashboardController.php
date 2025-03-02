<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ConstUserRole;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Revenue;
use App\Models\User;
use App\Models\Vendor;
use App\Traits\BaseApiResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use BaseApiResponse;

    //index
    public function index(Request $request)
    {
        //total vendors count
        $totalVendors = User::query()->where('role', ConstUserRole::VENDOR)->count();
        $totalPackages = Package::query()->count();
        $totalCompletedPackages = Package::query()->where('status', 'completed')->count();
        $totalPendingPackages = Package::query()->where('status', 'pending')->count();

        //total customers count
        $totalCustomers = Customer::query()->count();

        $vendors = User::query()->where('role', ConstUserRole::VENDOR)->get();

        $vendorsData = [];
        foreach ($vendors as $vendor) {
            $vendorData = [
                'vendor_id' => $vendor->id,
                'vendor_name' => $vendor->name,
                'vendor_address' => Vendor::query()->where('user_id', $vendor->id)->first()->address,
                'total_delivery' => Package::query()->where('vendor_id', $vendor->id)->where('status', 'completed')->count(),
                $amount = Package::query()
                    ->where('vendor_id', $vendor->id)
                    ->where('status', 'completed')
                    ->with('shipment')
                    ->get()
                    ->sum(fn($package) => $package->shipment->delivery_fee ?? 0),
            ];
            $vendorsData[] = $vendorData;
        }

        //package graph logic from Package model count by month for last 12 months
        $package_per_month = Package::query()
            ->selectRaw('count(id) as total, MONTH(created_at) as month')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->get();

        return $this->success([
            'total_customers' => $totalCustomers,
            'total_packages' => $totalPackages,
            'total_vendors' => $totalVendors,
            'total_sales' => $totalCompletedPackages,
            'package_per_month' => $package_per_month,
            'recent_vendors' => $vendorsData,
        ], 'Dashboard', 'Dashboard data fetched successfully');
    }
}
