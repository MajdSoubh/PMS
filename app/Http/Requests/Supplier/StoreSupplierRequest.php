<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSupplierRequest extends FormRequest
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
            'name' => ['required', Rule::unique("suppliers", "name")->where("pharmacy_id", auth()->user()->pharmacy->id)],
            'email' => 'nullable|email|unique:suppliers,email',
            'contact' => 'required|string|min:10|max:17|unique:suppliers,contact',
            'address' => 'nullable|string',
        ];
    }
}
