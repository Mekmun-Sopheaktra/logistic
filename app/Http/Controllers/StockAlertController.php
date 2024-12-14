<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\StockAlert;
use Illuminate\Http\Request;

class StockAlertController extends Controller
{
    public function checkStockAlert()
    {
        // Get products with stock quantity less than 5
        $lowStockProducts = Products::where('product_quantity', '<=', 5)->get();

        // Generate alerts for each low stock product
//        foreach ($lowStockProducts as $product) {
//            echo "Product: {$product->product_name} is low in stock. Current stock: {$product->product_quantity}\n";
//
//        }
        return response()->json([
            "stock_alert" => $lowStockProducts,
            "status" => 200,
        ]);
    }

}
