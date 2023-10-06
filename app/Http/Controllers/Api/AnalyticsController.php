<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use App\Models\Customer;
use App\Models\Drug;
use App\Models\Pharmacy;
use App\Models\Purchase;
use App\Models\Stock;
use App\Models\Supplier;

class AnalyticsController extends Controller
{
    use HttpResponses;
    public function dashboard()
    {

        $phId = auth()->user()->pharmacy_id;
        $customersCount = Customer::where('pharmacy_id', $phId)->count();
        $suppliersCount = Supplier::where('pharmacy_id', $phId)->count();
        $drugsCount = Drug::where('pharmacy_id', $phId)->count();
        $chest = Pharmacy::find($phId)->chest;
        $safe = Pharmacy::find($phId)->safe;
        $expiredDrugs = Stock::where('pharmacy_id', $phId)->where('exp', '<=', Carbon::now())->sum('qty');
        $totalEarning = Invoice::where('pharmacy_id', $phId)->sum('totalProfit');
        $excludedDrugs = DB::table('stocks')->where('pharmacy_id', $phId)->select('drug_id')->pluck('drug_id')->all();
        $outOfStock = DB::table('drugs')->where('pharmacy_id', $phId)->whereNotIn('id', $excludedDrugs)->count();

        $bestCustomer = Invoice::where('pharmacy_id', $phId)->whereHas('customer')->orderBy('total', 'desc')->first();
        if ($bestCustomer)  $bestCustomer = $bestCustomer->customer->name;
        $bestSupplier = Purchase::where('pharmacy_id', $phId)->whereHas('supplier')->orderBy('total', 'desc')->first();
        if ($bestSupplier)  $bestSupplier = $bestSupplier->supplier->name;


        $bestSellingDrugs = Drug::where('pharmacy_id', $phId)->where('sellingCounter', '!=', 0)->orderBy('sellingCounter', 'desc')->take(7)->get(['name', 'sellingCounter']);
        $sellingDrugsCounter = $bestSellingDrugs->sum('sellingCounter');

        $invoicesYearsFinancial = Invoice::where('pharmacy_id', $phId)->select(DB::raw("(sum(totalNet)) as total"), DB::raw("(DATE_FORMAT(date, '%Y')) as year"))->groupBy(DB::raw("DATE_FORMAT(date, '%Y')"))->orderBy('year', 'desc')->get();
        $invoicesYearsFinancialTotal = $invoicesYearsFinancial->sum('total');

        $invoicesYearsProfit = Invoice::where('pharmacy_id', $phId)->select(DB::raw("(sum(totalProfit)) as total"), DB::raw("(DATE_FORMAT(date, '%Y')) as year"))->groupBy(DB::raw("DATE_FORMAT(date, '%Y')"))->orderBy('year', 'desc')->take(5)->get();
        $invoicesYearsProfitTotal = $invoicesYearsProfit->sum('total');

        $purchasesYearsFinancial = Purchase::where('pharmacy_id', $phId)->select(DB::raw("(sum(total)) as total"), DB::raw("(DATE_FORMAT(date, '%Y')) as year"))->groupBy(DB::raw("DATE_FORMAT(date, '%Y')"))->orderBy('year', 'desc')->get();
        $purchasesYearsFinancialTotal = $purchasesYearsFinancial->sum('total');

        return $this->success([
            'customers' => $customersCount,
            'suppliers' => $suppliersCount,
            'drugs' => $drugsCount,
            'chest' => $chest,
            'safe' => $safe,
            'expiredDrugs' => $expiredDrugs,
            'outOfStock' => $outOfStock,
            'bestCustomer' => $bestCustomer,
            'bestSupplier' => $bestSupplier,
            'earnings' => $totalEarning,

            'bestSellingDrugs' => $bestSellingDrugs,
            'sellingDrugsCounter' => $sellingDrugsCounter,

            'invoicesYearsFinancial' => $invoicesYearsFinancial,
            'invoicesYearsFinancialTotal' => $invoicesYearsFinancialTotal,

            'purchasesYearsFinancial' => $purchasesYearsFinancial,
            'purchasesYearsFinancialTotal' => $purchasesYearsFinancialTotal,

            'invoicesYearsProfit' => $invoicesYearsProfit,
            'invoicesYearsProfitTotal' => $invoicesYearsProfitTotal,



        ]);
    }
}
