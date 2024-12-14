<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $fillable = [
        "product_name",
        "product_code",
        "product_garage",
        "product_route",
        "product_image",
        "expire_date",
        "import_price",
        "export_price",
        "description",
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function import()
    {
        return $this->belongsTo(Import::class);
    }

    public function export()
    {
        return $this->belongsTo(Export::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function compound()
    {
        return $this->belongsToMany(Compound::class)->withPivot('product_quantity');
    }
}
