<?php

namespace App\Http\Resources\Vendor;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoicePackageResource extends JsonResource
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
            'shipment_number' => $this->shipment->number,
            'customer_phone' => $this->customer->phone,
            'customer_name' => $this->customer->first_name . ' ' . $this->customer->last_name,
            'location' => $this->location->location,
            'date' => Carbon::parse($this->created_at)->format('d/m/Y H:i') ?? null,
            'package_price' => $this->price ?? null,
            'delivery_fee' => $this->shipment->delivery_fee,
            'status' => $this->status,
        ];
    }
}