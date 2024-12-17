<?php

namespace App\Http\Resources\Vendor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
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
            'package_number' => $this->number,
            'customer_name' => $this->customer->first_name . ' ' . $this->customer->last_name,
            'customer_phone' => $this->customer->phone,
            'location' => $this->location->location,
            'shipment_date' => $this->shipment->date ?? null,
            'package_status' => $this->status,
        ];
    }
}
