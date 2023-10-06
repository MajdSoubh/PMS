<?php

namespace App\Http\Requests\supplier;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSupplierRequest extends FormRequest
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
            'name' => [Rule::unique("suppliers", "name")->where("pharmacy_id", auth()->user()->pharmacy->id)->ignore($this->supplier)],
            'email' => ['nullable', 'email', Rule::unique("suppliers", "contact")->where("pharmacy_id", auth()->user()->pharmacy->id)->ignore($this->supplier)],
            'contact' => ['string', 'min:10', 'max:17', Rule::unique("suppliers", "contact")->where("pharmacy_id", auth()->user()->pharmacy->id)->ignore($this->supplier)],
            'address' => 'nullable|string',
        ];
    }
}
