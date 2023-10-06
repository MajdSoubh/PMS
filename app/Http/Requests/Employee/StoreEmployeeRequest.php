<?php

namespace App\Http\Requests\Employee;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
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
            "email" => "required|email|unique:users,email",
            "roles" => "array",
            "roles.*.role" =>  Rule::exists("roles", "id")->where("pharmacy_id", auth()->user()->pharmacy->id),
            "password" => "required|min:8|confirmed",
            "password_confirmation" => "required",
        ];
    }
}
