<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
    protected $with = ['products'];

    public function unit()
    {
        return $this->hasMany(Products::class);
    }
}
