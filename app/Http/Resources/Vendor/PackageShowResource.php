<?php

namespace App\Http\Resources\Vendor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'image' => $this->image,
            'package' => [
                'package_number' => $this->number,
                'customer_name' => $this->customer->first_name . ' ' . $this->customer->last_name,
                'customer_phone' => $this->customer->phone,
                'location' => $this->location->location,
                'total_price' => $this->price,
            ],
            'delivery' => [
                'shipment_date' => $this->shipment->date,
                'package_status' => $this->status,
                'driver_name' => $this->driver->first_name . ' ' . $this->driver->last_name,
                'driver_phone' => $this->driver->contact_number,
                'delivery_fee' => $this->shipment->delivery_fee,
            ],
            'vendor' => [
                'vendor_name' => $this->vendor->first_name . ' ' . $this->vendor->last_name,
                'pickup_date' => $this->shipment->date,
                'vendor_phone' => $this->vendor->contact_number,
                'vendor_address' => $this->vendor->address
            ],
        ];
    }
}
