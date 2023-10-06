<?php

namespace App\Http\Requests\Invoice;

use App\Rules\CheckStockAvailablity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInvoiceRequest extends FormRequest
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
            "date" => "required|date",
            "customer" => ["nullable", Rule::exists("customers", "id")->where("pharmacy_id", auth()->user()->pharmacy->id)],
            "items" => ["required", "array", "min:1"],
            "items.*.drug" => ['required', Rule::exists("stocks", "drug_id")->where("pharmacy_id", auth()->user()->pharmacy->id)],
            "items.*.qty" =>  "required|numeric|min:1|bail",
            "items.*.exp" => "required|date",
            "items.*.cost" => "required|numeric",
            "items.*.price" => "required|numeric",
            "items.*.discount" => "required|numeric|min:0",
            "items.*" => new CheckStockAvailablity
        ];
    }
}
