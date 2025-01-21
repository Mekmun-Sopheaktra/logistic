<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorInvoice extends Model
{
    use HasFactory;

    protected $table = 'vendor_invoice';

    protected $fillable = [
        'vendor_id',
        'invoice_number',
        'total',
        'status',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    //invoice
    public function invoice()
    {
        return $this->hasMany(Invoice::class);
    }
}
