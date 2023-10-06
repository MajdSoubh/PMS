<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Purchase\StorePurchaseRequest;
use App\Http\Requests\Purchase\UpdatePurchaseRequest;
use Illuminate\Http\Request;
use App\Http\Resources\PurchaseResource;
use App\Models\Purchase;
use App\Traits\HttpResponses;
use Illuminate\Support\Carbon;
use App\Models\PurchaseItems;
use App\Models\Stock;
use PDF;


class PurchaseController extends Controller
{
    use HttpResponses;

    public function __construct()
    {
        $this->middleware('permission:purchase-list')->only('index', 'show', 'export');
        $this->middleware('permission:purchase-create')->only('store');
        $this->middleware('permission:purchase-edit')->only('update');
        $this->middleware('permission:purchase-delete')->only('destroy');
    }

    /**
     * @OA\Get(
     * path="/api/purchase",
     * operationId="PurchaseAll",
     * tags={"Purchase"},
     * summary="Return all Purchases",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/PurchaseSchema")
     *                  )
     *  )
     */
    public function index()
    {
        $purchases = auth()->user()->pharmacy->purchases->load('supplier', 'purchaseItems.drug');

        return PurchaseResource::collection($purchases);
    }

    /**
     * @OA\Post(
     * path="/api/Purchase",
     * operationId="createPurchase",
     * tags={"Purchase"},
     * summary="Create new Purchase",
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
     *            description="Date of Purchase created",
     *            type="date",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="supplier",
     *            description="Supplier related to Purchase",
     *            type="int",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="items",
     *            description="Purchase items",
     *            type="array",
     *            nullable="false",
     *            @OA\Items(     *        @OA\Property(
     *            property="drug",
     *            description="Drug related to Purchase",
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
     *            property="exp",
     *            description="Expiration date of drug",
     *            type="date",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="mfd",
     *            description="Maunfactoring date of drug",
     *            type="date",
     *            nullable="false",
     *        ),
     * ),
     *                      ),
     *                     ),),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/PurchaseSchema")
     *                  )
     *  )
     */
    public function store(StorePurchaseRequest $request)
    {
        $purchase = Purchase::create($request->except(['items', 'supplier']));
        $purchase->pharmacy()->associate(auth()->user()->pharmacy->id);
        if ($request->filled('supplier'))
        {
            $purchase->supplier()->associate($request->supplier);
        }
        $total = 0;
        foreach ($request->items as $item)
        {
            $total += $item['cost'] * $item['qty'];
            $item['drug_id'] = $item['drug'];
            unset($item['drug']);
            $purchaseItem = PurchaseItems::create($item + ['purchase_id' => $purchase->id]);
            $stock = auth()->user()->pharmacy->stocks()->where('drug_id', $item['drug_id'])->where('exp', Carbon::parse($item['exp'])->format('Y-m-d'))->where('mfd', Carbon::parse($item['mfd'])->format('Y-m-d'))->where('cost', $item['cost'])->first();
            if ($stock)
            {
                $stock->increment('qty', $item['qty']);
            }
            else
            {
                Stock::create($item + [
                    'pharmacy_id' => auth()->user()->pharmacy->id
                ]);
            }
        }

        $purchase->total = $total;
        $purchase->save();

        auth()->user()->pharmacy()->decrement('chest', $total);

        $purchase = $purchase->fresh();
        $purchase->load("purchaseItems.drug", "supplier");
        return (new PurchaseResource($purchase))->additional(['message' => __('messages.model.created', ['name' => __('purchase')]), 'status' => 1]);
    }

    /**
     * @OA\Get(
     * path="/api/purchase/{id}",
     * operationId="getPurchase",
     * tags={"Purchase"},
     * summary="Find Purchase by id",
     *     @OA\Parameter(
     *         name="id",
     *         description="Purchase ID",
     *         in="path",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/PurchaseSchema")
     *                  ),
     *     @OA\Response(
     *         response=404,
     *         description="Purchase not found",
     *                  )
     *  )
     */
    public function show($id)
    {
        $purchase = auth()->user()->pharmacy->purchases->find($id)->load('supplier', 'purchaseItems.drug');
        if (!$purchase)
        {
            return $this->error(null, __('messages.model.lost', ['name' => __('purchase')]), 422);
        }

        return (new PurchaseResource($purchase))->additional(['status' => 'successfull']);;
    }

    /**
     * @OA\Put(
     * path="/api/purchase/{id}",
     * operationId="editPurchase",
     * tags={"Purchase"},
     * summary="Edit Purchase by id",
     *     @OA\Parameter(
     *         name="id",
     *         description="Purchase ID",
     *         in="path",
     *         required=true,
     *     ),
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
     *            description="Date of Purchase created",
     *            type="date",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="supplier",
     *            description="Supplier related to Purchase",
     *            type="int",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="items",
     *            description="Purchase items",
     *            type="array",
     *            nullable="false",
     *            @OA\Items(     *        @OA\Property(
     *            property="drug",
     *            description="Drug related to Purchase",
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
     *            property="exp",
     *            description="Expiration date of drug",
     *            type="date",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="mfd",
     *            description="Maunfactoring date of drug",
     *            type="date",
     *            nullable="false",
     *        ),
     * ),
     *                      ),
     *                     ),),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/PurchaseSchema")
     *                  )
     *  )
     */
    public function update(UpdatePurchaseRequest $request, $id)
    {
        $purchase =  auth()->user()->pharmacy->purchases->find($id);

        if (!$purchase)
        {
            return $this->error(null, __('messages.model.lost', ['name' => __('purchase')]), 422);
        }
        auth()->user()->pharmacy()->increment('safe', $purchase->total);
        $purchase->update($request->except(['items', 'supplier']));
        auth()->user()->pharmacy()->decrement('safe', $purchase->total);
        if ($request->has('supplier'))
        {
            $purchase->supplier()->associate($request->supplier);
        }
        /*    if ($request->has('items'))
        {
            $purchase->purchaseItems()->delete();

            foreach ($request->items as $item)
            {
                $item['drug_id'] = $item['drug'];
                unset($item['drug']);
                $purchaseItem = purchaseItems::create($item);
                $purchaseItem->purchase()->associate($purchase->id)->save();
            }
        } */
        $purchase = $purchase->fresh();
        $purchase->load("purchaseItems.drug", "supplier");
        return (new purchaseResource($purchase))->additional(['message' => __('messages.model.updated', ['name' => __('purchase')]), 'status' => 1]);
    }

    /**
     * @OA\delete(
     * path="/api/purchase/{id}",
     * operationId="deletePurchase",
     * tags={"Purchase"},
     * summary="Delete Purchase by id",
     *     @OA\Parameter(
     *         name="id",
     *         description="Purchase ID",
     *         in="path",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *      ),
     *     @OA\Response(
     *         response=402,
     *         description="Purchase not found",
     *      ),
     *  )
     */
    public function destroy($id)
    {
        $purchase =  auth()->user()->pharmacy->purchases->find($id);
        if (!$purchase)
        {
            return $this->error(null, __('messages.model.lost', ['name' => __('purchase')]), 422);
        }
        auth()->user()->pharmacy()->increment('safe', $purchase->total);
        $purchase->purchaseItems()->delete();
        $purchase->delete();
        return (new PurchaseResource($purchase))->additional(['message' => __('messages.model.deleted', ['name' => __('purchase')]), 'status' => 1]);
    }
    public function export()
    {
        $purchases =  auth()->user()->pharmacy->purchases->all();
        $pdf = Pdf::loadView('Pdf.purchases', ["purchases" => $purchases]);
        $date = Carbon::now()->toDateString();
        return $pdf->download('purchases-report_' . $date . '.pdf');
    }
}
