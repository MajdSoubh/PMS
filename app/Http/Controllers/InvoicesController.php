<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use App\Models\InvoiceItems;
use App\Models\Medicine;
use App\Http\Requests\Invoice\StoreInvoiceRequest;
use App\Models\Chest;
use App\Models\Payment;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Interfaces\ViewMethods;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoicesController extends Controller implements ViewMethods
{

    public function search(Request $request)
    {
        $query = Invoice::where("user_id", Auth::user()->id)->with("customer", "payment");
        if ($request->filled("id"))
        {
            $query = $query->where("id", $request->id);
        }
        if ($request->filled("from"))
        {
            $query = $query->where("created_at", ">=", $request->from);
        }
        if ($request->filled("to"))
        {
            $query = $query->where("date", "<=", $request->to);
        }
        if ($request->filled("customer"))
        {
            $query = $query->whereRelation("customer", "name", "like", "%" . $request->customer . "%");
        }
        $query->orderBy("date", "desc")->get();
        $invoices = $query->simplePaginate(8);;
        return view("sub.invoice_table", compact("invoices"))->render();
    }
    public function generatePDF(Request $request)
    {
        $invoices = Invoice::where("user_id", Auth::user()->id)->with("customer", "payment");
        if ($request->filled("id"))
        {
            $invoices = $invoices->where("id", $request->id);
        }
        if ($request->filled("from"))
        {
            $invoices = $invoices->where("created_at", ">=", $request->from);
        }
        if ($request->filled("to"))
        {
            $invoices = $invoices->where("date", "<=", $request->to);
        }
        if ($request->filled("customer"))
        {
            $invoices = $invoices->whereRelation("customer", "name", "like", "%" . $request->customer . "%");
        }
        $invoices = $invoices->orderBy("date", "desc")->get();
        $pdf = PDF::loadView("pdf.invoices", compact("invoices"));
        return $pdf->download('invoices.pdf');
    }
    public function manage()
    {

        return view("invoices/invoices_manage");
    }
    public function add()
    {
        $invoice_number = Invoice::max('id') + 1;
        return view("invoices/invoice_add", compact("invoice_number"));
    }
    /**
     * Store new Invoice
     */
    public function store(StoreInvoiceRequest $request)
    { //take all validated elements
        $validated = $request->validated();
        $customerId = null;
        //get customer if it filled in request
        if ($request->filled("customer"))
            $customerId = Customer::where("user_id", Auth::user()->id)->where("name", $request->customer)->first()->id;
        //create new payment
        $payment = Payment::create($validated);
        //create new invoice and bound it with customer and payment
        $invoice = Invoice::create($validated + [
            'user_id' => Auth::user()->id,
            'payment_id' => $payment->id,
            'customer_id' => $customerId
        ]);
        //for each item in invoice
        foreach ($request->items as $item)
        {
            //find the related medicine
            $medicine = Medicine::where("user_id", Auth::user()->id)->where("name", $item['medicine'])->first();
            //create new invoice items
            $invoiceItem = InvoiceItems::create($item + [
                "medicine_id" => $medicine->id,
                "invoice_id" => $invoice->id,
            ]);
            $query = Stock::where("user_id", Auth::user()->id)->where("medicine_id", $medicine->id)->where("exp", $item['exp'])->first();

            //decrease the amount of this item from the stock
            $query->decrement('qty', $item['qty']);
        }
        //increase the chest value by the amount of paid
        Chest::where("user_id", Auth::user()->id)->increment("total", (int)$validated["paid"]);
        //return response as json have invoice and payment with status 201
        return response()->json(["invoice" => $invoice, "payment" => $payment], 201);
    }
    public function destroy(Request $request)
    {
        if ($request->filled("id"))
        {

            Invoice::destroy($request->id);
        }
        return response()->json(["success" => 1], 200);
    }

    public function getInvoiceItems($id)
    {

        $invoice =  Invoice::find($id);
        $items =  Invoice::find($id)->invoiceItems;
        $payment =  Invoice::find($id)->payment;
        $customer =  Invoice::find($id)->customer;
        return view("sub/invoice_items", compact("invoice", "payment", "customer", "items"))->render();
    }
    public function returnedMedicines()
    {
        return view("invoices/returned_medicines");
    }
}
