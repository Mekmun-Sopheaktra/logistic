<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Package;
use App\Traits\BaseApiResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ExpressController extends Controller
{
    use BaseApiResponse;
    //index
    public function index(Request $request)
    {
        $per_page = $request->query('per_page', env('PAGINATION_PER_PAGE', 10));
        $search = $request->query('search');
        $user = auth()->user();
        $driver = Driver::query()->where('user_id', $user->id)->first();

        // Ensure the driver exists
        if (!$driver) {
            return $this->error('Driver not found', 404);
        }

        // Query packages grouped by updated_at
        $packages = Package::query()
            ->when($search, function ($query, $search) {
                return $query->where('number', 'like', '%' . $search . '%');
            })
            ->with([
                'vendor',
                'customer',
                'location',
                'driver',
                'shipment',
                'invoice',
            ])
            ->where('driver_id', $driver->id)
            ->orderBy('updated_at', 'desc') // Order by updated_at first
            ->get()
            ->groupBy(function ($item) {
                return $item->updated_at->format('M d, Y'); // Group by date of updated_at
            });

        // Paginate the grouped results manually
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $collection = collect($packages);
        $paginatedData = new LengthAwarePaginator(
            $collection->forPage($currentPage, $per_page),
            $collection->count(),
            $per_page,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        return $this->success($paginatedData, 'Welcome to express delivery');
    }

}
