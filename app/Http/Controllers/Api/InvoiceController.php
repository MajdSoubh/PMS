<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\StoreInvoiceRequest;
use App\Http\Requests\Invoice\UpdateInvoiceRequest;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceItems;
use App\Http\Resources\InvoiceResource;
use Illuminate\Support\Carbon;
use App\Models\Drug;
use Pdf;

class InvoiceController extends Controller
{
    use HttpResponses;


    public function __construct()
    {
        $this->middleware('permission:invoice-list')->only('index', 'show', 'export');
        $this->middleware('permission:invoice-create')->only('store');
        $this->middleware('permission:invoice-edit')->only('update');
        $this->middleware('permission:invoice-delete')->only('destroy');
    }

    /**
     * @OA\Get(
     * path="/api/invoice",
     * operationId="InvoiceAll",
     * tags={"Invoice"},
     * summary="Return all Invoices",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/InvoiceSchema")
     *                  )
     *  )
     */
    public function index()
    {
        $invoices = auth()->user()->pharmacy->invoices->load('customer', 'invoiceItems.drug');

        return InvoiceResource::collection($invoices);
    }

    /**
     * @OA\Post(
     * path="/api/invoice",
     * operationId="createInvoice",
     * tags={"Invoice"},
     * summary="Create new Invoice",
     *     @OA\RequestBody(
     *       @OA\JsonContent(
     *        @OA\Property(
     *            property="paid",
     *            description="Paid amount",
     *            type="integer",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="date",
     *            description="Date of invoice created",
     *            type="date",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="customer",
     *            description="Customer related to invoice",
     *            type="int",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="items",
     *            description="Invoice items",
     *            type="array",
     *            nullable="false",
     *            @OA\Items(     *        @OA\Property(
     *            property="drug",
     *            description="Drug related to invoice",
     *            type="int",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="qty",
     *            description="Quantity of drug",
     *            type="int",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="cost",
     *            description="Cost of drug",
     *            type="double",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="price",
     *            description="Price of drug",
     *            type="double",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="discount",
     *            description="Amount of discount for drug",
     *            type="double",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="exp",
     *            description="Expiration date of drug",
     *            type="date",
     *            nullable="false",
     *        ),),
     *                      ),
     *                     ),),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/InvoiceSchema")
     *                  )
     *  )
     */
    public function store(StoreInvoiceRequest $request)
    {
        $invoice = Invoice::create($request->except(['items', 'customer']));
        $invoice->pharmacy()->associate(auth()->user()->pharmacy->id);
        if ($request->filled('customer'))
        {
            $invoice->customer()->associate($request->customer);
        }
        $total = 0;
        $totalNet = 0;
        $totalDiscount = 0;
        $totalProfit = 0;
        foreach ($request->items as $item)
        {
            $item['drug_id'] = $item['drug'];
            unset($item['drug']);
            $invoiceItem = InvoiceItems::create($item);
            $invoiceItem->invoice()->associate($invoice->id)->save();
            $stock = auth()->user()->pharmacy->stocks()->where('drug_id', $item['drug_id'])->where('exp', Carbon::parse($item['exp'])->format('Y-m-d'))->where('cost', $item['cost'])->first();
            $stock->decrement('qty', $item['qty']);
            if ($stock->qty == 0)
            {
                $stock->delete();
            }
            Drug::where('id', $item['drug_id'])->first()->increment('sellingCounter', $item['qty']);
            $total += $item['price'] * $item['qty'];
            $totalNet += ($item['price'] - $item['discount']) * $item['qty'];
            $totalProfit = $totalNet - ($item['cost'] * $item['qty']);
            $totalDiscount += $item['discount'];
        }
        $invoice->totalNet = $totalNet;
        $invoice->total = $total;
        $invoice->totalDiscount = $totalDiscount;
        $invoice->totalProfit = $totalProfit;

        $invoice->save();
        $invoice = $invoice->fresh();

        auth()->user()->pharmacy()->increment('chest', $invoice->totalNet);

        $invoice->load("invoiceItems.drug", "customer");
        return (new InvoiceResource($invoice))->additional(['message' => __('messages.model.created', ['name' => __('invoice')]), 'status' => 1]);
    }

    /**
     * @OA\Get(
     * path="/api/invoice/{id}",
     * operationId="getInvoice",
     * tags={"Invoice"},
     * summary="Find Invoice by id",
     *     @OA\Parameter(
     *         name="id",
     *         description="Invoice ID",
     *         in="path",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/InvoiceSchema")
     *                  ),
     *     @OA\Response(
     *         response=404,
     *         description="Invoice not found",
     *                  )
     *  )
     */
    public function show($id)
    {
        $invoice = auth()->user()->pharmacy->invoices->find($id)->load('customer', 'invoiceItems.drug');
        if (!$invoice)
        {
            return $this->error(null, __('messages.model.lost', ['name' => __('invoice')]), 422);
        }

        return (new InvoiceResource($invoice))->additional(['status' => 'successfull']);;
    }

    /**
     * @OA\Put(
     * path="/api/invoice/{id}",
     * operationId="editInvoice",
     * tags={"Invoice"},
     * summary="Edit Invoice by id",
     *     @OA\Parameter(
     *         name="id",
     *         description="Invoice ID",
     *         in="path",
     *         required=true,
     *     ),
     *     @OA\RequestBody(
     *       @OA\JsonContent(
     *       @OA\Property(
     *            property="id",
     *            description="Invoice identifier",
     *            type="integer",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="paid",
     *            description="Paid amount",
     *            type="integer",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="date",
     *            description="Date of invoice created",
     *            type="date",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="customer",
     *            description="Customer related to invoice",
     *            type="int",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="items",
     *            description="Invoice items",
     *            type="array",
     *            nullable="false",
     *            @OA\Items(     *        @OA\Property(
     *            property="drug",
     *            description="Drug related to invoice",
     *            type="int",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="qty",
     *            description="Quantity of drug",
     *            type="int",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="cost",
     *            description="Cost of drug",
     *            type="double",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="price",
     *            description="Price of drug",
     *            type="double",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="discount",
     *            description="Amount of discount for drug",
     *            type="double",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="exp",
     *            description="Expiration date of drug",
     *            type="date",
     *            nullable="false",
     *        ),),
     *                      ),
     *                     ),),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/InvoiceSchema")
     *                  )
     *  )
     */
    public function update(UpdateInvoiceRequest $request, $id)
    {
        $invoice = auth()->user()->pharmacy->invoices->find($id);

        if (!$invoice)
        {
            return $this->error(null, __('messages.model.lost', ['name' => __('invoice')]), 422);
        }
        auth()->user()->pharmacy()->decrement('chest', $invoice->totalNet);
        $invoice->update($request->except(['items', 'customer']));
        auth()->user()->pharmacy()->increment('chest', $invoice->totalNet);
        if ($request->has('customer'))
        {
            $invoice->customer()->associate($request->customer);
        }
        /*    if ($request->has('items'))
        {
            $invoice->invoiceItems()->delete();

            foreach ($request->items as $item)
            {
                $item['drug_id'] = $item['drug'];
                unset($item['drug']);
                $invoiceItem = InvoiceItems::create($item);
                $invoiceItem->invoice()->associate($invoice->id)->save();
            }
        } */
        $invoice->save();
        $invoice = $invoice->fresh();
        $invoice->load("invoiceItems.drug", "customer");
        return (new InvoiceResource($invoice))->additional(['message' => __('messages.model.updated', ['name' => __('invoice')]), 'status' => 1]);
    }

    /**
     * @OA\delete(
     * path="/api/invoice/{id}",
     * operationId="deleteInvoice",
     * tags={"Invoice"},
     * summary="Delete Invoice by id",
     *     @OA\Parameter(
     *         name="id",
     *         description="Invoice ID",
     *         in="path",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *      ),
     *     @OA\Response(
     *         response=402,
     *         description="Invoice not found",
     *      ),
     *  )
     */
    public function destroy($id)
    {

        $invoice = auth()->user()->pharmacy->invoices->find($id);

        if (!$invoice)
        {
            return $this->error(null, __('messages.model.lost', ['name' => __('invoice')]), 422);
        }
        auth()->user()->pharmacy()->decrement('safe', $invoice->totalNet);
        $invoice->invoiceItems()->delete();
        $invoice->delete();
        return (new InvoiceResource($invoice))->additional(['message' => __('messages.model.deleted', ['name' => __('invoice')]), 'status' => 1]);
    }
    public function export()
    {
        $invoices = auth()->user()->pharmacy->invoices->all();
        $pdf = Pdf::loadView('Pdf.invoices', ["invoices" => $invoices]);
        $date = Carbon::now()->toDateString();
        return $pdf->download('invoices-report_' . $date . '.pdf');
    }
}
