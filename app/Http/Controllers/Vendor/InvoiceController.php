<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Resources\Vendor\InvoiceCollection;
use App\Http\Resources\Vendor\InvoiceResource;
use App\Traits\BaseApiResponse;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    use BaseApiResponse;

    public function index(Request $request)
    {
        $user = auth()->user();
        $perPage = $request->query('per_page', config('pagination.per_page', 10));

        $invoices = $user->vendor->invoices()
            ->with([
                'package.vendor',
                'package.customer',
                'package.location',
                'package.shipment',
                'driver',
                'employee'
            ])
            ->when($request->query('search'), fn($query, $search) => $query->where('number', 'like', "%$search%"))
            ->when($request->query('date'), fn($query, $date) => $query->whereDate('created_at', $date))
            ->paginate($perPage);

        //total_package_price
        $invoices->map(function ($invoice) {
            $invoice->total_package_price = $invoice->package->sum('price');
            return $invoice;
        });

        //delivery_fee
        $invoices->map(function ($invoice) {
            $invoice->delivery_fee = $invoice->package->shipment->delivery_fee;
            return $invoice;
        });

        return $this->success(new InvoiceCollection($invoices), 'List of vendor invoices.');
    }
}
