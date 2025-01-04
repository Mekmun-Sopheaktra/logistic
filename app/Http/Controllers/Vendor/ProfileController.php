<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Resources\Vendor\ProfileResource;
use App\Models\Vendor;
use App\Traits\BaseApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    use BaseApiResponse;
    //index
    public function index()
    {
        $user = auth()->user();
        $vendor = Vendor::query()->where('user_id', $user->id)->first();
        if (!$vendor) {
            return $this->error('Vendor not found', 404);
        }

        // Create an object for the resource
        $data = new ProfileResource([
            'vendor' => $vendor,
            'user' => $user
        ]);

        return $this->success($data, 'Vendor profile');
    }

    //update
    public function update(Request $request)
    {
        $user = auth()->user();
        $vendor = Vendor::query()->where('user_id', $user->id)->first();
        if (!$vendor) {
            return $this->error('Vendor not found', 404);
        }

        $validated = $request->validate([
            'first_name' => 'string',
            'last_name' => 'string',
            'business_name' => 'string',
            'dob' => 'date',
            'gender' => 'string',
            'address' => 'string',
            'contact_number' => 'string',
        ]);

        $vendor->update($validated);

        // Create an object for the resource
        $data = new ProfileResource([
            'vendor' => $vendor,
            'user' => $user
        ]);

        return $this->success($data, 'Vendor profile updated successfully');
    }

    //resetPassword
    public function resetPassword(Request $request)
    {
        $user = auth()->user();
        $vendor = Vendor::query()->where('user_id', $user->id)->first();
        if (!$vendor) {
            return $this->error('Vendor not found', 404);
        }

        $validated = $request->validate([
            'old_password' => 'required|string',
            'password' => 'required|string|confirmed',
        ]);

        if (!Hash::check($validated['old_password'], $user->password)) {
            return $this->error('Old password is incorrect', 400);
        }

        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        return $this->success(null, 'Password updated successfully');
    }

    //logout
    public function logout()
    {
        // Revoke the token passport token
        auth()->user()->token()->revoke();

        return $this->success(null, 'Vendor logged out successfully');
    }
}
