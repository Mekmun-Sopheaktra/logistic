<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
            "email"=> "email|unique:customers",
            "phone"=> "required|max:15|unique:customers",
//            "photo"=> "required",
            "address"=> "required|max:500",
            "city"=> "required|max:100",
//            "shop_name"=> "required",
//            "bank_name"=> "required",
//            "bank_number"=> "required",
        ];
    }
}
