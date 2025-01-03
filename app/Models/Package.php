<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    // Define the table name if it's not the plural form of the model name
    protected $table = 'packages';

    // Define the fillable fields to allow mass assignment
    protected $fillable = [
        'number',
        'name',
        'slug',
        'price',
        'description',
        'image',
        'zone',
        'vendor_id',
        'customer_id',
        'location_id',
        'driver_id',
        'shipment_id',
        'invoice_id',
        'status',
    ];

    // Optionally, you can define relationships with other models

    // Example relationship with Vendor
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    // Example relationship with Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Example relationship with Location
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    // Example relationship with Shipment
    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    //driver relationship
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    // Example relationship with Invoice
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
