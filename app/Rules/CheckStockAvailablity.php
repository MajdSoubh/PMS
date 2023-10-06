<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Support\Carbon;

class CheckStockAvailablity implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {


        $ExpDate = Carbon::parse($value['exp'])->format('Y-m-d');
        //$ExpDate = Carbon::now()->subYear(2)->format('Y-m-d');
        $stock =  auth()->user()->pharmacy->stocks->where('drug_id', $value['drug'])->where('exp', $ExpDate);
        if (!$stock->count())
        {
            return $fail('validation.stock_exp')->translate(['value' => $ExpDate]);
        }
        $stock = $stock->where('cost', $value['cost']);
        if (!$stock->count())
        {
            return $fail('validation.stock_cost')->translate(['value' => $value['cost']]);
        }
        $stock = $stock->where('qty', '>=', $value['qty']);
        if (!$stock->count())
        {
            return $fail('validation.stock_qty')->translate(['value' => $value['qty']]);
        }
    }
}
