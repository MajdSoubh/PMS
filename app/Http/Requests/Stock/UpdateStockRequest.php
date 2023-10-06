<?php

namespace App\Http\Requests\Stock;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStockRequest extends FormRequest
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
            "drug" => ["nullable", Rule::exists("drugs", "id")->where("pharmacy_id", auth()->user()->pharmacy->id)],
            "mfd" => "before:" . now() . "|before:exp",
            "exp" => "present",
            "qty" => "numeric|min:1",
            "cost" => "numeric",
        ];
    }
}
