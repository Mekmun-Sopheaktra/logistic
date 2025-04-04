<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Currency;
use App\Models\DeliveryFee;
use App\Traits\BaseApiResponse;
use App\Traits\UploadImage;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    use BaseApiResponse, UploadImage;
    //index
    public function index()
    {
        //get all settings
        $user = auth()->user();
        if (!$user) {
            return $this->failed(null, 'Unauthorized', 'Unauthorized');
        }
        //get admin data
        $admin = Admin::where('id', $user->id)->first();
        if (!$admin) {
            return $this->failed(null, 'Unauthorized', 'Unauthorized');
        }

        $admin['email'] = $user->email;
        $currecy = Currency::query()->first();
        $admin['exchange_rate'] = (int) $currecy->exchange_rate;

        $delivery_fee = DeliveryFee::query()->first();
        $admin['delivery_fee'] = $delivery_fee->fee;

        return $this->success($admin, 'Settings retrieved successfully');
    }

    //update
    public function update(Request $request)
    {
        ///update
        $user = auth()->user();
        if (!$user) {
            return $this->failed(null, 'Unauthorized', 'Unauthorized');
        }
        //get admin data
        $admin = Admin::where('id', $user->id)->first();
        if (!$admin) {
            return $this->failed(null, 'Unauthorized', 'Unauthorized');
        }

        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'username' => 'required',
            'image' => 'nullable',
        ]);

        //upload image
        $image = $request->image ?? null;
        if ($request->hasFile('image')) {
            $image = $this->updateImage($request, $admin);
        }

        $data['image'] = $image;

        $admin->update($data);

        return $this->success($admin, 'Settings updated successfully');
    }
}
