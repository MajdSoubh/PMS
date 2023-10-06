<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
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
            'name' => [Rule::unique("customers", "name")->where("pharmacy_id", auth()->user()->pharmacy->id)->ignore($this->customer)],
            'contact' => 'nullable|min:10|unique:customers,contact,' . $this->customer,
            'address' => 'nullable|string',
        ];
    }
}
