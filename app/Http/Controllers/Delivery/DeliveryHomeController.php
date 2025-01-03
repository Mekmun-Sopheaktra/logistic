<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Driver;
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
        //get driver by user id
        $driver = Driver::query()->where('user_id', $user->id)->first();

        //get packages by driver id
//        $packages = $driver->packages()->paginate($perPage);
        logger('Driver', [$driver]);
        if (!$driver) {
            return $this->error('Driver not found', 404);
        }

        return $this->success('Welcome to delivery home page');
    }
}
