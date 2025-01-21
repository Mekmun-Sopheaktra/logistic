<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Resources\Vendor\InvoiceCollection;
use App\Http\Resources\Vendor\InvoiceResource;
use App\Models\Package;
use App\Models\VendorInvoice;
use App\Traits\BaseApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class InvoiceController extends Controller
{
    use BaseApiResponse;



    public function index(Request $request)
    {
        $user = auth()->user();
        $perPage = $request->query('per_page', config('pagination.per_page', 10));
        $dateFilter = $request->query('date');

        // Reformat date to match Laravel's 'created_at' format (YYYY-MM-DD)
        if ($dateFilter) {
            try {
                $dateFilter = Carbon::parse($dateFilter)->format('Y-m-d');
            } catch (\Exception $e) {
                return $this->error('Invalid date format.', 422);
            }
        }

        $invoices = $user->vendor->invoices()
            ->with([
                'packages.vendor',
                'packages.customer',
                'packages.location',
                'packages.shipment',
                'driver',
                'packages',
                'employee'
            ])
            ->when($request->query('search'), fn($query, $search) => $query->where('number', 'like', "%$search%"))
            ->when($dateFilter, fn($query, $date) => $query->whereDate('created_at', $date))
            ->paginate($perPage);

        // Define all possible statuses
        $allStatuses = ['completed', 'pending', 'in_transit', 'cancelled'];

        // Convert paginator collection and modify invoices
        $invoices->getCollection()->transform(function ($invoice) use ($allStatuses, $dateFilter) {
            // Ensure packages is a collection
            $packages = $invoice->packages ?? collect();

            $invoice->total_package_price = $packages->sum('price');
            $invoice->delivery_fee = $packages->sum(fn($package) => optional($package->shipment)->delivery_fee ?? 0);

            // Get the package status counts
            $statusCounts = Package::query()
                ->selectRaw('status, count(*) as count')
                ->where('vendor_id', $invoice->vendor->id)
                ->when($dateFilter, fn($query, $date) => $query->whereDate('created_at', $date))
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status')
                ->toArray();

            // Ensure all statuses exist with default 0
            $invoice->package_status_counts = collect($allStatuses)
                ->mapWithKeys(fn($status) => [$status => $statusCounts[$status] ?? 0]);

            return $invoice;
        });

        return $this->success(new InvoiceCollection($invoices), 'List of vendor invoices.');
    }

    //show
    public function show($id)
    {
        $invoice = auth()->user()->vendor->invoices()->with([
            'packages.vendor',
            'packages.customer',
            'packages.location',
            'packages.shipment',
            'driver',
            'packages',
            'employee'
        ])->findOrFail($id);

        return $this->success(new InvoiceResource($invoice), 'Vendor invoice details.');
    }

    //vendorInvoice
    public function vendorInvoice()
    {
        $user = auth()->user();
        $perPage = request()->query('per_page', config('pagination.per_page', 10));
        $dateFilter = request()->query('date');
        $vendorId = $user->vendor->id;

        //Vendor invoices
        $vendorInvoices = VendorInvoice::query()
            ->where('vendor_id', $vendorId)
            ->with([
                'vendor',
                'invoice',
            ])
            ->when($dateFilter, fn($query, $date) => $query->whereDate('created_at', $date))
            ->paginate($perPage);

        return $this->success($vendorInvoices, 'List of vendor invoices.');
    }

    //vendorInvoiceShow
    public function vendorInvoiceShow($id)
    {
        $user = auth()->user();
        $vendorId = $user->vendor->id;

        $vendorInvoice = VendorInvoice::query()
            ->where('vendor_id', $vendorId)
            ->with([
                'vendor',
                'invoice',

            ])
            ->findOrFail($id);

        return $this->success($vendorInvoice, 'Vendor invoice details.');
    }
}
