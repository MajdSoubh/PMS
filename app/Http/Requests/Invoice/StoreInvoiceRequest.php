<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Rules\checkMedicineQuantity;

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
     * @return array
     */
    public function rules()
    {
        return [
            "total_discount" => "numeric",
            "total_net" => "required|numeric",
            "total" => "required|numeric",
            "paid" => "required|numeric",
            "rest" => "required|numeric",
            "date" => "required|date",
            "customer" => ["nullable", Rule::exists("customers", "name")->where("user_id", Auth::user()->id)],
            "items.*.medicine" => ['required', Rule::exists("medicines", "name")->where("user_id", Auth::user()->id)],
            "items.*.qty" => ["required", "numeric", "min:1"],
            "items.*.exp" => ["required", "date"],
            "items.*.discount" => "required|numeric|min:0",
            "items" => ["required", "array", new checkMedicineQuantity()],
            "provider" => Rule::in(["Cash", "Net Banking"]),
            "status" => Rule::in(["Paid", "Partially Paid"]),

        ];
    }
}
