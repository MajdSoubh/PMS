<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreOwnerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "lastName" => "required|string",
            "firstName" => "required|string",
            "pharmacyName" => "required|string",
            "licence" => "nullable|string",
            "email" => "required|email|unique:users,email",
            "password" => "required|min:8|confirmed",
            "password_confirmation" => "required",
        ];
    }
}
