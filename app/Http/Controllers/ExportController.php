<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Export;

use App\Models\Import;
use App\Models\Income;
use App\Models\Products;
use App\Models\Revenue;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function saveExport(Request $request, $id)
    {



        $product = Products::findOrFail($id);
        if ($product->product_quantity >= 0 && $product->product_quantity >= $request->product_quantity) {
            $product->product_quantity = $product->product_quantity - $request->product_quantity;
            $product->product_amount = $product->product_quantity * $product->import_price;
            $product->update();

            $export = new Export();
            $export->product_id = $id;
            $export->product_name = $product->product_name;
            $export->product_image = $product->product_image;

            $export->export_quantity = $request->product_quantity;
            $export->total_export_price = $product->export_price * $export->export_quantity;
            $export->save();

            $income = new Income();
            $income->income_amount = $export->total_export_price;
            $income->save();

            $revenue = new Revenue();
            $revenue->revenue = ($product->export_price * $export->export_quantity) - ($product->import_price * $export->export_quantity);
            $revenue->save();

            return response()->json([
                "message" => "Export added successfully!",
                "status" => 200,
            ]);
        } else {
            return response()->json([
                "message" => "Cannot export product since the product has only $product->product_quantity",
                "status" => 404,
            ], 404);
        }




    }

    public function getExports()
    {
        $exports = Export::all();

        return response()->json([
            "exports" => $exports,
            "status" => 200,
        ]);
    }

    public function getExportByProductID($id)
    {
        $export = Export::where('product_id', $id)->get();
        return response()->json([
            "exports" => $export,
            "status" => 200,
        ]);
    }
}