<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\CheckStockAvailablity;

class UpdateInvoiceRequest extends FormRequest
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
            "total" => "numeric",
            "totalDiscount" => "numeric",
            "paid" => "numeric",
            "date" => "date",
            "customer" => ["nullable", Rule::exists("customers", "id")->where("pharmacy_id", auth()->user()->pharmacy->id)],
            "items" => ["array", "min:1"],
            "items.*.drug" => [Rule::exists("drugs", "id")->where("pharmacy_id", auth()->user()->pharmacy->id)],
            "items.*.qty" => "numeric|min:1",
            "items.*.exp" => "date",
            "items.*.discount" => "numeric|min:0",
            "items.*" => new CheckStockAvailablity
        ];
    }
}
