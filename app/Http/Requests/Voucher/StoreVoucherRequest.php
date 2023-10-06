<?php

namespace App\Http\Requests\Voucher;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVoucherRequest extends FormRequest
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

            'type' => Rule::in(['Payment', 'Receipt', 'Cash']),
            'amount' => ['required', 'numeric', 'not_in:0'],
            'description' => ['nullable', 'string'],
            'date' => ['required', 'date'],
        ];
    }
}
