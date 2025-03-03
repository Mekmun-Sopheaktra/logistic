<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'driver_type' => $this->driver_type,
            'driver_description' => $this->driver_description,
            'dob' => $this->dob,
            'gender' => $this->gender,
            'zone' => $this->zone,
            'contact_number' => $this->contact_number,
            'telegram_contact' => $this->telegram_contact,
            'image' => $this->image,
            'bank_name' => $this->bank_name,
            'bank_number' => $this->bank_number,
            'status' => $this->user->account_status,
            'email' => $this->user->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
