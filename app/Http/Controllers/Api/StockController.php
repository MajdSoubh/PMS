<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Stock\StoreStockRequest;
use App\Http\Requests\Stock\UpdateStockRequest;
use App\Http\Resources\StockResource;
use Illuminate\Http\Request;
use App\Models\Stock;
use App\Traits\HttpResponses;
use Pdf;
use Illuminate\Support\Carbon;

class StockController extends Controller
{
    use HttpResponses;


    public function __construct()
    {
        $this->middleware('permission:stock-list')->only('index', 'show', 'export');
        $this->middleware('permission:stock-create')->only('store');
        $this->middleware('permission:stock-edit')->only('update');
        $this->middleware('permission:stock-delete')->only('destroy');
    }

    /**
     * @OA\Get(
     * path="/api/stock",
     * operationId="StockAll",
     * tags={"Stock"},
     * summary="Return all Stocks",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/StockSchema")
     *                  )
     *  )
     */
    public function index()
    {
        $stocks = auth()->user()->pharmacy->stocks;
        $stocks->load('drug');
        return  StockResource::collection($stocks);
    }

    /**
     * @OA\Post(
     * path="/api/stock",
     * operationId="createStock",
     * tags={"Stock"},
     * summary="Create new Stock",
     *     @OA\RequestBody(
     *       @OA\JsonContent(
     *        @OA\Property(
     *            property="mfd",
     *            description="Stock manufactoring date",
     *            type="date",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="exp",
     *            description="Stock expiration date",
     *            type="date",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="Drug",
     *            description="Related Drug",
     *            type="int",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="qty",
     *            description="Stock quantity",
     *            type="int",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="cost",
     *            description="Stock cost",
     *            type="double",
     *            nullable="false",
     *        ),
     *                      ),
     *                     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/StockSchema")
     *                  )
     *  )
     */
    public function store(StoreStockRequest $request)
    {

        $stock = auth()->user()->pharmacy->stocks()->updateOrCreate(
            [
                'drug_id' => $request->input('drug'),
                'mfd' => $request->input('mfd'),
                'exp' => $request->input('exp'),
                'cost' => $request->input('cost')
            ]
        );
        $stock->increment('qty', $request->input('qty'));
        $stock->load('drug');
        return (new StockResource($stock))->additional(['message' => __('messages.model.created', ['name' => __('stock')]), 'status' => 1])->response(201);
    }

    /**
     * @OA\Get(
     * path="/api/stock/{id}",
     * operationId="getStock",
     * tags={"Stock"},
     * summary="Find Stock by id",
     *     @OA\Parameter(
     *         name="id",
     *         description="Stock ID",
     *         in="path",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/StockSchema")
     *                  ),
     *     @OA\Response(
     *         response=404,
     *         description="Stock not found",
     *                  )
     *  )
     */
    public function show($id)
    {
        $stock =  auth()->user()->pharmacy->stocks->find($id);
        if (!$stock)
        {
            return $this->error(null, __('messages.model.lost', ['name' => __('stock')]), 422);
        }
        $stock->load('drug');

        return (new StockResource($stock))->additional(['status' => 'successfull']);
    }


    public function showBy(Request $request)
    {
        $stock =  auth()->user()->pharmacy->stocks();
        if ($request->filled('drug'))
        {
            $stock = $stock->where('drug_id', $request->drug);
        }
        if ($request->filled('exp'))
        {
            $stock = $stock->where('exp', $request->exp);
        }
        if ($request->filled('cost'))
        {
            $stock = $stock->where('cost', $request->cost);
        }
        $stock = $stock->orderBy("exp", "asc")->with('drug')->get();

        return StockResource::collection($stock)->additional(['status' => 'successfull']);
    }
    /**
     * @OA\Put(
     * path="/api/stock/{id}",
     * operationId="editStock",
     * tags={"Stock"},
     * summary="Edit Stock by id",
     *     @OA\Parameter(
     *         name="id",
     *         description="Stock ID",
     *         in="path",
     *         required=true,
     *     ),
     *     @OA\RequestBody(
     *       @OA\JsonContent(
     *        @OA\Property(
     *            property="mfd",
     *            description="Stock manufactoring date",
     *            type="date",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="exp",
     *            description="Stock expiration date",
     *            type="date",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="Drug",
     *            description="Related Drug",
     *            type="int",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="qty",
     *            description="Stock quantity",
     *            type="int",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="cost",
     *            description="Stock cost",
     *            type="double",
     *            nullable="false",
     *        ),
     *                      ),
     *                     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/StockSchema")
     *                  )
     *  )
     */
    public function update(UpdateStockRequest $request, $id)
    {
        $stock =  auth()->user()->pharmacy->stocks->find($id);
        if (!$stock)
        {
            return $this->error(null, __('messages.model.lost', ['name' => __('stock')]), 422);
        }
        $stock->update($request->except(['drug']));
        if ($request->has('drug'))
        {

            $stock->drug()->associate($request->drug);
        }

        $stock->save();
        $stock->load('drug');
        return (new StockResource($stock))->additional(['message' => __('messages.model.updated', ['name' => __('stock')]), 'status' => 1]);
    }


    /**
     * @OA\delete(
     * path="/api/stock/{id}",
     * operationId="deleteStock",
     * tags={"Stock"},
     * summary="Delete Stock by id",
     *     @OA\Parameter(
     *         name="id",
     *         description="Stock ID",
     *         in="path",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *      ),
     *     @OA\Response(
     *         response=402,
     *         description="Stock not found",
     *      ),
     *  )
     */
    public function destroy($id)
    {
        $stock =  auth()->user()->pharmacy->stocks->find($id)->load('supplier', 'drug');
        if (!$stock)
        {
            return $this->error(null, __('messages.model.lost', ['name' => __('stock')]), 422);
        }
        $stock->delete();
        return (new StockResource($stock))->additional(['message' => __('messages.model.deleted', ['name' => __('stock')]), 'status' => 1]);
    }
    public function export()
    {
        $stocks =  auth()->user()->pharmacy->stocks->all();
        $pdf = Pdf::loadView('Pdf.stocks', ["stocks" => $stocks]);
        $date = Carbon::now()->toDateString();
        return $pdf->download('stocks-report_' . $date . '.pdf');
    }
}
