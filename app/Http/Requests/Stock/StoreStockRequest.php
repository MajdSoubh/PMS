<?php

namespace App\Http\Requests\Stock;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreStockRequest extends FormRequest
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
            "drug" => ["required", Rule::exists("drugs", "id")->where("pharmacy_id", auth()->user()->pharmacy->id)],
            "mfd" => "required|date|before:" . now() . "|before:exp",
            "exp" => "required|date",
            "qty" => "required|numeric|min:1",
            "cost" => "numeric",
        ];
    }
}
