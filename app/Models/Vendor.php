<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        "first_name",
        "last_name",
        "business_name",
        "dob",
        "gender",
        "address",
        "contact_number",
        "image",
        "type",
        "bank_name",
        "bank_number",
        "user_id"
    ];

    public function products()
    {
        return $this->hasMany(Products::class);
    }
}