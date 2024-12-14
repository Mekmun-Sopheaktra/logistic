<?php

namespace App\Http\Controllers;

use App\Models\Compound;
use App\Models\Products;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class CompoundController extends Controller
{
    public function store(Request $request)
    {
        //            $request->validate([
        //                'name' => 'required|string',
        //                'products' => 'required|array',
        //                'products.*.id' => 'required|exists:products,id',
        //                'products.*.quantity' => 'required|integer|min:1',
        //            ]);
        $compound = Compound::where('name', $request->name)->first();
        if ($compound) {
            return response()->json([
                'data' => 'Compound name already existed'
            ], 400);
        } else {
            if ($request->name == null) {
                return response()->json([
                    'data' => 'Please enter compound name'
                ], 400);
            }

            if ($request->price == null) {
                return response()->json([
                    'data' => 'Please enter compound price'
                ], 400);
            }
            if ($request->description == null) {
                return response()->json([
                    'data' => 'Please enter compound description'
                ], 400);
            }
            if ($request->input('products') == null) {
                return response()->json([
                    'data' => 'Error, Empty product!'
                ], 400);
            }

            $compoundProduct = Compound::create([
                'name' => $request->input('name'),
                'price' => $request->input('price'),
                'description' => $request->input('description'),
            ]);

            foreach ($request->input('products') as $productData) {
                $product = Products::find($productData['id']);
                $product_quantity = $productData['product_quantity'];
                $compoundProduct->products()->attach($product, ['product_quantity' => $product_quantity]);
            }
        }

        return response()->json(['message' => 'Compound product created successfully']);
    }


    public function update(Request $request, $id)
    {
        $compound = Compound::findOrFail($id);
        $data = $request->all();

        $data['name'] = $request->name;
        $data['price'] = $request->price;
        $data['description'] = $request->description;

        $compound->update($data);


        return response()->json([
            "message" => "Compound data updated successfully!",
            "status" => 200,
        ]);
    }




    public function show($id)
    {
        $compoundProduct = Compound::with('products')->find($id);

        if (!$compoundProduct) {
            return response()->json(['message' => 'Compound product not found'], 404);
        }

        return response()->json([
            "compound" => $compoundProduct,
            "status" => 200
        ]);
    }
    public function delete($id)
    {
        $compound = Compound::findOrFail($id);

        $compound->delete();

        return response()->json([
            "message" => "Compound deleted successfully!",
            "status" => 200
        ]);
    }
    public function getCompounds()
    {
        $compound = Compound::all();

        return response()->json([
            "compounds" => $compound,
            "status" => 200
        ]);
    }
    public function getByChar($compound_name)
    {

        $compound = Compound::where('name', 'like', '%' . $compound_name . '%')->get();

        if ($compound) {
            return response()->json([
                "compounds" => $compound,
                "status" => 200,
            ]);
        } else {
            return response()->json(['error' => 'compound not found'], 404);
        }
    }
}
