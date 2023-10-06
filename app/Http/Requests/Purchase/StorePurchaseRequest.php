<?php

namespace App\Http\Requests\Purchase;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePurchaseRequest extends FormRequest
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
            "paid" => "required|numeric",
            "supplier" => [Rule::exists("suppliers", "id")->where("pharmacy_id", auth()->user()->pharmacy->id)],
            "date" => "required|date",
            "items.*.drug" => ['required', Rule::exists("drugs", "id")->where("pharmacy_id", auth()->user()->pharmacy->id)],
            "items.*.mfd" => 'required|date|before:' . now() . '|before:items.*.exp',
            "items.*.exp" => 'required|date',
            "items.*.qty" => 'required|numeric',
            "items.*.cost" => 'required|numeric',
            "items" => ["required", "array", "min:1"],

        ];
    }
}
