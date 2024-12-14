<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveSupplierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "name"=> "required|max:150",
            "email"=> "email|unique:suppliers",
            "phone"=> "required|max:15|unique:suppliers",
//            "photo"=> "required",
            "address"=> "required|max:500",
            "type"=> "required",
            "city"=> "required|max:50",
            "shop_name"=> "required",
//            "bank_name"=> "required",
//            "bank_number"=> "required",
        ];
    }
}
