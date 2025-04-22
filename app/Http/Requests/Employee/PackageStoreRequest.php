<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class PackageStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'package_number' => 'required|string|max:255',
            'package_name' => 'required|string|max:255',
            'package_price' => 'required|string',
            'package_description' => 'nullable|string|max:255',
            'package_image' => 'nullable',
            'package_zone' => 'required|string|max:255',
            'package_note' => 'nullable|string|max:255',

            'customer_first_name' => 'required|string|max:255',
            'customer_last_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:255',

            'customer_location' => 'required',
            'customer_lat' => 'required',
            'customer_lng' => 'required',
        ];
    }
}
