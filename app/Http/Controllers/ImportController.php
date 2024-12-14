<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Export;
use App\Models\Import;
use App\Models\Products;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function saveImport(Request $request, $id)
    {

        $product = Products::findOrFail($id);


        $import = new Import();
        $import->product_id = $id;
        $import->product_name = $product->product_name;
        $import->product_image = $product->product_image;

        $expense = new Expense();
        if ($product->product_quantity + $request->product_quantity > 0) {
            $product->product_quantity = $product->product_quantity + $request->product_quantity;
            $product->product_amount = $product->product_quantity * $product->import_price;
            $product->update();

            $import->import_quantity = $request->product_quantity;
            $import->total_import_price = $import->import_quantity * $product->import_price;
            $import->save();

            $expense->expense_amount = $import->total_import_price;
            $expense->save();
            return response()->json([
                "message" => "Import added successfully!",
                "status" => 200,
            ]);
        } else {
            return response()->json([
                "message" => "Can't Reduce Product!",
                "status" => 404,
            ], 404);
        }

    }

    public function getImports()
    {
        $imports = Import::all();

        return response()->json([
            "imports" => $imports,
            "status" => 200,
        ]);
    }

    public function getImportByProductID($id)
    {

        $import = Import::where('product_id', $id)->get();
        return response()->json([
            "imports" => $import,
            "status" => 200,
        ]);
    }

}
