<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\PackageInvoiceResource;
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

    public function packagesInvoice()
    {
        $perPage = request()->query('per_page', config('pagination.per_page', 10));
        $search = request()->query('search'); // Search by customer phone
        $date = request()->query('date');

        $invoiceQuery = Package::with(['invoice', 'customer', 'location']);

        if ($date) {
            $invoiceQuery->whereHas('invoice', function ($query) use ($date) {
                $query->whereDate('created_at', $date);
            });
        }

        if ($search) {
            $invoiceQuery->whereHas('customer', function ($query) use ($search) {
                $query->where('phone', 'like', "%$search%");
            });
        }

        $invoices = [
            'data' => PackageInvoiceResource::collection($invoiceQuery->paginate($perPage)),
            'pagination' => [
                'total' => $invoiceQuery->count(),
                'per_page' => $perPage,
                'current_page' => $invoiceQuery->currentPage(),
                'last_page' => $invoiceQuery->lastPage(),
                'from' => $invoiceQuery->firstItem(),
                'to' => $invoiceQuery->lastItem()
            ]
        ];

        return $this->success($invoices, 'Packages Invoice', 'Packages invoice data fetched successfully');
    }


    //show packages invoice
    public function showPackagesInvoice($id)
    {
        // Get invoice data from packages relationship with invoice
        $invoice = Package::with(['invoice', 'customer'])->find($id);

        if (!$invoice) {
            return $this->error('Invoice not found', 404);
        }

        return $this->success($invoice, 'Packages Invoice', 'Packages invoice data fetched successfully');
    }

    //vendorInvoice
    public function vendorInvoice()
    {
        // Get invoice data from vendor relationship with invoice
        $perPage = request()->query('per_page', config('pagination.per_page', 10));
        $search = request()->query('search'); // Search by invoice number
        $date = request()->query('date');

        $invoiceQuery = VendorInvoice::with(['vendor', 'location']);

        if ($date) {
            $invoiceQuery->whereDate('created_at', $date);
        }

        if ($search) {
            $invoiceQuery->where('invoice_number', 'like', "%$search%");
        }

        $invoices = $invoiceQuery->paginate($perPage);

        return $this->success($invoices, 'Vendor Invoice', 'Vendor invoice data fetched successfully');
    }

    //show vendor invoice
    public function showVendorInvoice($id)
    {
        // Get invoice data from vendor relationship with invoice
        $invoice = VendorInvoice::with(['vendor'])->find($id);

        if (!$invoice) {
            return $this->error('Invoice not found', 404);
        }

        return $this->success($invoice, 'Vendor Invoice', 'Vendor invoice data fetched successfully');
    }
}
