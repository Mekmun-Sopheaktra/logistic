<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Resources\Vendor\PackageResource;
use App\Http\Resources\Vendor\PackageShowResource;
use App\Models\Driver;
use App\Models\Vendor;
use App\Traits\BaseApiResponse;
use Illuminate\Http\Request;
use App\Models\Package;
use Illuminate\Routing\Controller;

class PackageController extends Controller
{
    use BaseApiResponse;
    /**
     * Display a listing of the packages with pagination.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $limit = $request->query('limit', 15); // Default items per page is 15
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        // Get the current user's vendor_id from the Vendor table
        $user = auth()->user();
        $vendor = Vendor::where('user_id', $user->id)->first();

        if (!$vendor) {
            return $this->error('Vendor not found for the current user.', 404);
        }

        $packagesQuery = Package::query()
            ->with(['vendor', 'shipment', 'invoice', 'location', 'customer'])
            ->where('vendor_id', $vendor->id);

        if ($startDate && $endDate) {
            $packagesQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $packages = $packagesQuery->paginate($limit);

        $data = [
            'packages' => PackageResource::collection($packages),
            'total' => $packages->total(),
            'per_page' => $packages->perPage(),
            'current_page' => $packages->currentPage(),
            'last_page' => $packages->lastPage(),
            'from' => $packages->firstItem(),
            'to' => $packages->lastItem(),
            'next_page_url' => $packages->nextPageUrl(),
            'prev_page_url' => $packages->previousPageUrl(),
            'path' => $packages->path(),
        ];

        return $this->success(
            $data,
            'Packages Retrieved',
            'Packages fetched successfully with pagination.'
        );
    }

    /**
     * Store a newly created package in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'number' => 'required|string',
            'name' => 'required|string|max:255',
            'slug' => 'required|string',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'zone' => 'nullable|string|max:255',
            'vendor_id' => 'required|exists:vendors,id',
            'status' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $validatedData['image'] = $request->file('image')->store('packages', 'public');
        }

        $package = Package::create($validatedData);

        return $this->success(
            $package,
            'Package Created',
            'The package has been created successfully.',
            201
        );
    }

    /**
     * Display the specified package.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = auth()->user();
        $vendor = Vendor::where('user_id', $user->id)->first();

        if (!$vendor) {
            return $this->failed(
                null,
                'Vendor Not Found',
                'No vendor associated with the current user.',
                404
            );
        }

        $package = Package::query()
            ->with(['vendor', 'shipment', 'invoice', 'location', 'customer'])
            ->where('vendor_id', $vendor->id) // Ensure the package belongs to this vendor
            ->find($id);

        if (!$package) {
            return $this->failed(
                null,
                'Package Not Found',
                'The requested package does not exist or does not belong to your vendor.',
                404
            );
        }

        if ($package->invoice) {
            $driver = Driver::find($package->invoice->driver_id);
            if ($driver) {
                $package->driver = $driver;
            }
        }

        return $this->success(
            PackageShowResource::make($package),
            'Package Retrieved',
            'The package details have been retrieved successfully.'
        );
    }

    /**
     * Update the specified package in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $package = Package::find($id);

        if (!$package) {
            return $this->failed(
                null,
                'Package Not Found',
                'The package you are trying to update does not exist.',
                404
            );
        }

        $validatedData = $request->validate([
            'number' => 'sometimes|string|unique:packages,number,' . $package->id,
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|unique:packages,slug,' . $package->id,
            'price' => 'sometimes|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'zone' => 'nullable|string|max:255',
            'vendor_id' => 'sometimes|exists:vendors,id',
            'customer_id' => 'nullable|exists:customers,id',
            'location_id' => 'nullable|exists:locations,id',
            'shipment_id' => 'nullable|exists:shipments,id',
            'invoice_id' => 'nullable|exists:invoices,id',
            'status' => 'nullable|string|in:pending,approved,shipped,delivered,canceled',
        ]);

        if ($request->hasFile('image')) {
            $validatedData['image'] = $request->file('image')->store('packages', 'public');
        }

        $package->update($validatedData);

        return $this->success(
            $package,
            'Package Updated',
            'The package has been updated successfully.'
        );
    }

    /**
     * Remove the specified package from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $package = Package::find($id);

        if (!$package) {
            return $this->failed(
                null,
                'Package Not Found',
                'The package you are trying to delete does not exist.',
                404
            );
        }

        $package->delete();

        return $this->success(
            null,
            'Package Deleted',
            'The package has been deleted successfully.'
        );
    }
}
