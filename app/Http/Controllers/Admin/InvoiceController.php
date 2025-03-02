<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\VendorInvoice;
use App\Traits\BaseApiResponse;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    use BaseApiResponse;
    //packagesInvoice

    //get packages invoice or packages invoice data
    public function index()
    {
        //tab = packages or vendors
        $tab = request()->query('tab', 'packages');
        if ($tab === 'packages') {
            return $this->packagesInvoice();
        } elseif ($tab === 'vendors') {
            return $this->vendorInvoice();
        }

        return $this->failed(null, 'Invalid Tab', 'Invalid tab provided');
    }

    private function packagesInvoice()
    {
        //get invoice data from packages relationship with invoice
        $per_page = request()->query('per_page', config('pagination.per_page', 10));
        $search = request()->query('search'); // search by customer phone
        $date = request()->query('date');
        $invoice = Package::query()
            ->with(['invoice', 'customer'])
            ->when($date, fn($query, $date) => $query->whereHas('invoice', fn($query) => $query->whereDate('date', $date)))
            ->when($search, fn($query, $search) => $query->whereHas('customer', fn($query) => $query->where('phone', 'like', "%$search%")))
            ->paginate($per_page);

        return $this->success($invoice, 'Packages Invoice', 'Packages invoice data fetched successfully');
    }

    //vendorInvoice
    private function vendorInvoice()
    {
        //get invoice data from vendor relationship with invoice
        $per_page = request()->query('per_page', config('pagination.per_page', 10));
        $search = request()->query('search'); // search by vendor name
        $date = request()->query('date');
        $invoice = VendorInvoice::query()
            ->with(['vendor'])
            ->when($date, fn($query, $date) => $query->whereDate('created_at', $date))
            ->when($search, fn($query, $search) => $query->where('invoice_number', 'like', "%$search%"))
            ->paginate($per_page);

        return $this->success($invoice, 'Vendor Invoice', 'Vendor invoice data fetched successfully');
    }
}
