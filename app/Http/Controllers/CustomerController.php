<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Category;
use App\Models\Customer;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Faker\Provider\Image;
use Illuminate\Http\Request;

class CustomerController extends Controller
{


    //    protected function uploadCustomerImage($request)
//    {
//        $customerImage = $request->file('photo');
//        $imageType = $customerImage->getClientOriginalExtension();
//        $imageName = rand(100, 100000) . $request->name . '.' . $imageType;
//        $directory = 'inventory/customer-images/';
//        $imageUrl = $directory . $imageName;
//        Image::make($customerImage)->save($imageUrl);
//        return $imageUrl;
//    }

    public function saveCustomer(Request $request)
    {
        $customer = new Customer();

        // upload image to storage
//        if ($request->hasFile('photo')) {
//            $image = $request->file('photo');
//            $image_name = time() . '.' . $image->getClientOriginalExtension();
//            $image->move(public_path('images/customers/'), $image_name);
//            $customer->photo = $image_name;
//        } else if ($request->photo) {
//            $base64_string = $request->photo;
//            $image = base64_decode($base64_string);
//
//            $file_name = time() . '.' . 'png';
//            $file_path = public_path('images/customers/' . $file_name);
//            file_put_contents($file_path, $image);
//            $customer->photo = $file_name;
//        }
//        $cloudinary = new Cloudinary();
//        $image = $request->file('photo');
//        $result = $cloudinary->uploadApi()->upload($image->getRealPath());

        // upload image to storage for Cloudinary
        if ($request->hasFile('photo')) {
            $imagePath = $request->file('photo')->getRealPath();
            $uploadedImage = Cloudinary::upload($imagePath)->getSecurePath();
            $customer->photo = $uploadedImage;
        } else if ($request->photo) {
            $imageBase64 = $request->photo;
            $uploadedImage = Cloudinary::upload("data:image/png;base64," . $imageBase64)->getSecurePath();
            $customer->photo = $uploadedImage;
        }


        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->city = $request->city;
        $customer->shop_name = $request->shop_name;
        $customer->bank_name = $request->bank_name;
        $customer->bank_number = $request->bank_number;
        $customer->save();
        return response()->json([
            "message" => "Customer added successfully!",
            "status" => 200,
        ]);

    }
    public function getCustomers()
    {
        $customers = Customer::all();
        return response()->json([
            "customers" => $customers,
            "status" => 200,
        ]);
    }
    public function getCustomer($id)
    {
        $customer = Customer::findOrFail($id);
        return response()->json([
            "customer" => $customer,
            "status" => 200,
        ]);
    }
    public function updatedCustomer(Request $request, $id)
    {


        $customer = Customer::findOrFail($id);
        $data = $request->all();

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['phone'] = $request->phone;
        $data['address'] = $request->address;
        $data['city'] = $request->city;
        $data['shop_name'] = $request->shop_name;
        $data['bank_name'] = $request->bank_name;
        $data['bank_number'] = $request->bank_number;


        // if ($request->hasFile('photo')) {
        //     $image = $request->file('photo');
        //     $image_name = time() . '.' . $image->getClientOriginalExtension();
        //     $image->move(public_path('images/customers/'), $image_name);
        //     $data['photo'] = $image_name; // remove old image
        //     $old_image = public_path('images/customers/' . $customer->photo);
        //     if (file_exists($old_image)) {
        //         @unlink($old_image);
        //     }
        // } else if ($request->photo) {
        //     $base64_string = $request->photo;
        //     $image = base64_decode($base64_string);

        //     $file_name = time() . '.' . 'png';
        //     $file_path = public_path('images/customers/' . $file_name);
        //     file_put_contents($file_path, $image);
        //     $data['photo'] = $file_name; // remove old image
        //     $old_image = public_path('images/customers/' . $customer->photo);
        //     if (file_exists($old_image)) {
        //         @unlink($old_image);
        //     }
        // }

        // upload image to storage for Cloudinary
        if ($request->hasFile('photo')) {

            //check if old image exist
            $old_image = Cloudinary::getImage($customer->photo);
            if ($old_image->getPublicId() != null) {
                Cloudinary::destroy($old_image->getPublicId());
            }

            //set image to response json
            $imagePath = $request->file('photo')->getRealPath();
            $uploadedImage = Cloudinary::upload($imagePath)->getSecurePath();
            $data['photo'] = $uploadedImage;


        } else if ($request->photo) {
            //check if old image exist
            $old_image = Cloudinary::getImage($customer->photo);
            if ($old_image->getPublicId() != null) {
                Cloudinary::destroy($old_image->getPublicId());
            }
            //set image to response json
            $imageBase64 = $request->photo;
            $uploadedImage = Cloudinary::upload("data:image/png;base64," . $imageBase64)->getSecurePath();
            $data['photo'] = $uploadedImage;
        }
        $customer->update($data);
        return response()->json([
            "message" => "Customer data updated successfully!",
            "status" => 200,
        ]);
    }


    public function deleteCustomer($id)
    {
        $customer = Customer::findOrFail($id);
        //        if($customer->hasFile('photo'))
//        {
//            unlink($customer->photo);
//        }
        $customer->delete();
        return response()->json([
            "message" => "Customer removed successfully!",
            "status" => 200,
        ]);

    }


    public function getByName($Customer)
    {
        $customer = Customer::where('name', '=', $Customer)->first();

        if ($customer) {
            return response()->json([
                "customer" => $customer,
                "status" => 200,
            ]);
        } else {
            return response()->json(['error' => 'Customer not found'], 404);
        }
    }


    public function getByChar($customer_name)
    {

        $customer = Customer::where('name', 'like', '%' . $customer_name . '%')->get();

        if ($customer) {
            return response()->json([
                "customers" => $customer,
                "status" => 200,
            ]);
        } else {
            return response()->json(['error' => 'customer not found'], 404);
        }
    }

}