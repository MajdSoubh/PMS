<?php

namespace App\Http\Requests\Purchase;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePurchaseRequest extends FormRequest
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
            /*       "total" => "numeric", */
            "paid" => "numeric",
            "supplier" => [Rule::exists("suppliers", "id")->where("pharmacy_id", auth()->user()->pharmacy->id)],
            "date" => "date",
            "items.*.drug" => ['required', Rule::exists("drugs", "id")->where("pharmacy_id", auth()->user()->pharmacy->id)],
            "items.*.mfd" => 'date',
            "items.*.exp" => 'date',
            "items.*.qty" => 'numeric',
            /*     "items.*.cost" => 'numeric', */
            "items" => ["array", "min:1"],

        ];
    }
}
