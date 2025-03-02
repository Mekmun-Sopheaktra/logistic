<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ConstUserRole;
use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Revenue;
use App\Models\User;
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

        $vendors = User::query()->where('role', ConstUserRole::VENDOR)->get();
        $vendorsData = [];
        foreach ($vendors as $vendor) {
            $vendorData = [
                'vendor_id' => $vendor->id,
                'vendor_name' => $vendor->name,
                'vendor_email' => $vendor->email,
                'total_packages' => Package::query()->where('vendor_id', $vendor->id)->count(),
                'total_completed_packages' => Package::query()->where('vendor_id', $vendor->id)->where('status', 'completed')->count(),
                'total_pending_packages' => Package::query()->where('vendor_id', $vendor->id)->where('status', 'pending')->count(),
            ];
            $vendorsData[] = $vendorData;
        }

        //revenue graph logic from Revenue Model group by month
         $revenueData = Revenue::query()
            ->selectRaw('sum(amount) as total_amount, MONTH(created_at) as month')
            ->groupBy('month')
            ->get();

        return $this->success([
            'total_vendors' => $totalVendors,
            'total_packages' => $totalPackages,
            'total_completed_packages' => $totalCompletedPackages,
            'total_pending_packages' => $totalPendingPackages,
            'revenue_data' => $revenueData,
            'recent_vendors' => $vendorsData,
        ], 'Dashboard', 'Dashboard data fetched successfully');
    }
}
