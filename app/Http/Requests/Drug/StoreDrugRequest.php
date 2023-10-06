<?php

namespace App\Http\Requests\Drug;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class StoreDrugRequest extends FormRequest
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
            'name' => ['required', Rule::unique("drugs", "name")->where("pharmacy_id", auth()->user()->pharmacy->id)],
            'generic' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'image' => 'image',
        ];
    }
}
