<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageInvoiceResource extends JsonResource
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
            'customer_phone' => $this->customer?->phone,
            'location' => $this->invoice,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
