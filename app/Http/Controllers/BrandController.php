<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveBrandRequest;
use App\Http\Requests\UpdateBrandRequest;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BrandController extends Controller
{
    public function saveBrand(Request $request)
    {
        $brand = new Brand();

        $brand->name = $request->name;

        $brand->save();

        return response()->json([
            "message" => "Brand added successfully!",
            "status" => 200
        ]);
    }

    public function getBrands()
    {
        $brands = Brand::all();

        return response()->json([
            "brands" => $brands,
            "status" => 200
        ]);

    }


    public function getBrand($id)
    {
        $brand = Brand::findOrFail($id);
        return response()->json([
            "brand" => $brand,
            "status" => 200,
        ]);
    }

    public function updateBrand(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);
        $brand->name = $request->name;

        $brand->update();

        return response()->json([
            "message" => "Brand data updated successfully!",
            "status" => 200
        ]);
    }


    // if client want to add delete related products
    public function deleteBrand($id)
    {
        $brand = Brand::findOrFail($id);

        if ($brand->products->isEmpty()) {
            // do something if there are no products
            $brand->delete();
        } else {
            // do something if there are products
            return response()->json([
                "message" => "Products exist!",
            ], 404);
        }

        return response()->json([
            "message" => "Brand deleted successfully!",
            "status" => 200
        ]);

    }

    public function getByName($Brand)
    {
        $brand = Brand::where('name', '=', $Brand)->first();

        if ($brand) {
            return response()->json([
                "brand" => $brand,
                "status" => 200,
            ]);
        } else {
            return response()->json(['error' => 'Brand not found'], 404);
        }
    }

    public function getByChar($brand_name)
    {

        $brand = Brand::where('name', 'like', '%' . $brand_name . '%')->get();

        if ($brand) {
            return response()->json([
                "brands" => $brand,
                "status" => 200,
            ]);
        } else {
            return response()->json(['error' => 'brand not found'], 404);
        }
    }

}