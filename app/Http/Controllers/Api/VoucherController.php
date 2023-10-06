<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Voucher\StoreVoucherRequest;
use App\Http\Requests\Voucher\UpdateVoucherRequest;
use App\Http\Resources\VoucherResource;
use App\Models\Voucher;
use App\Traits\HttpResponses;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;

class VoucherController extends Controller
{
    use HttpResponses;

    public function __construct()
    {
        $this->middleware('permission:voucher-list')->only('index', 'show', 'export');
        $this->middleware('permission:voucher-create')->only('store');
        $this->middleware('permission:voucher-edit')->only('update');
        $this->middleware('permission:voucher-delete')->only('destroy');
    }

    /**
     * @OA\Get(
     * path="/api/voucher",
     * operationId="VoucherAll",
     * tags={"Voucher"},
     * summary="Return all Vouchers",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/VoucherSchema")
     *                  )
     *  )
     */
    public function index()
    {
        $vouchers = auth()->user()->pharmacy->vouchers;
        return VoucherResource::collection($vouchers);
    }

    /**
     * @OA\Post(
     * path="/api/voucher",
     * operationId="createVoucher",
     * tags={"Voucher"},
     * summary="Create new Voucher",
     *     @OA\RequestBody(
     *       @OA\JsonContent(
     *        @OA\Property(
     *            property="type",
     *            description="Voucher type",
     *            type="string",
     *            nullable="false",
     *          enum={
     *             "cash",
     *             "payment",
     *             "recipt"
     *          }
     *        ),
     *        @OA\Property(
     *            property="amount",
     *            description="Voucher amount",
     *            type="double",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="description",
     *            description="Voucher description",
     *            type="string",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="date",
     *            description="Voucher date",
     *            type="date",
     *            nullable="false",
     *        ),
     *                  ),
     *                     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/VoucherSchema")
     *                  )
     *  )
     */
    public function store(StoreVoucherRequest $request)
    {
        $this->handleVoucher($request->input('type'), $request->input('amount'));
        $voucher =  Voucher::create($request->safe()->all() + ["pharmacy_id" => auth()->user()->pharmacy->id]);

        return (new VoucherResource($voucher))->additional(['message' => __('messages.model.created', ['name' => __('voucher')]), 'status' => 1])->response(201);
    }

    /**
     * @OA\Get(
     * path="/api/voucher/{id}",
     * operationId="getVoucher",
     * tags={"Voucher"},
     * summary="Find Voucher by id",
     *     @OA\Parameter(
     *         name="id",
     *         description="Voucher ID",
     *         in="path",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/VoucherSchema")
     *                  ),
     *     @OA\Response(
     *         response=404,
     *         description="Voucher not found",
     *                  )
     *  )
     */
    public function show($id)
    {
        $voucher =  auth()->user()->pharmacy->vouchers->find($id);
        if (!$voucher)
        {
            return $this->error(null, __('messages.model.lost', ['name' => __('voucher')]), 422);
        }

        return (new VoucherResource($voucher))->additional(['status' => 1]);
    }

    /**
     * @OA\Put(
     * path="/api/voucher/{id}",
     * operationId="editVoucher",
     * tags={"Voucher"},
     * summary="Edit Voucher by id",
     *     @OA\Parameter(
     *         name="id",
     *         description="Voucher ID",
     *         in="path",
     *         required=true,
     *     ),
     *     @OA\RequestBody(
     *       @OA\JsonContent(
     *        @OA\Property(
     *            property="type",
     *            description="Voucher type",
     *            type="string",
     *            nullable="false",
     *          enum={
     *             "cash",
     *             "payment",
     *             "recipt"
     *          }
     *        ),
     *        @OA\Property(
     *            property="amount",
     *            description="Voucher amount",
     *            type="double",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="description",
     *            description="Voucher description",
     *            type="string",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="date",
     *            description="Voucher date",
     *            type="date",
     *            nullable="false",
     *        ),
     *                  ),
     *                     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/VoucherSchema")
     *                  )
     *  )
     */
    public function update(UpdateVoucherRequest $request, $id)
    {
        $voucher =  auth()->user()->pharmacy->vouchers->find($id);
        if (!$voucher)
        {
            return $this->error(null, __('messages.model.lost', ['name' => __('voucher')]), 422);
        }
        $this->handleVoucher($voucher->type, $voucher->amount, true);
        $voucher->update($request->safe()->all());
        $this->handleVoucher($request->type, $request->amount);
        return (new VoucherResource($voucher))->additional(['message' => __('messages.model.updated', ['name' => __('voucher')]), 'status' => 1]);
    }

    /**
     * @OA\delete(
     * path="/api/voucher/{id}",
     * operationId="deleteVoucher",
     * tags={"Voucher"},
     * summary="Delete Voucher by id",
     *     @OA\Parameter(
     *         name="id",
     *         description="Voucher ID",
     *         in="path",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *      ),
     *     @OA\Response(
     *         response=402,
     *         description="Voucher not found",
     *      ),
     *  )
     */
    public function destroy($id)
    {
        $voucher =  auth()->user()->pharmacy->vouchers->find($id);
        if (!$voucher)
        {
            return $this->error(null, __('messages.model.lost', ['name' => __('voucher')]), 422);
        }
        $this->handleVoucher($voucher->type, $voucher->amount, true);
        $voucher->delete();
        return (new VoucherResource($voucher))->additional(['message' => __('messages.model.deleted', ['name' => __('voucher')]), 'status' => 1]);
    }
    public function export()
    {
        $vouchers =  auth()->user()->pharmacy->vouchers->all();
        $pdf = Pdf::loadView('Pdf.vouchers', ["vouchers" => $vouchers]);
        $date = Carbon::now()->toDateString();
        return $pdf->download('vouchers-report_' . $date . '.pdf');
    }



    protected function handleVoucher($type, $amount, $restore = false)
    {
        if ($restore)
        {
            $amount = -$amount;
        }
        switch ($type)
        {
            case 'Payment':
                auth()->user()->pharmacy()->increment("safe", $amount);
                auth()->user()->pharmacy()->decrement("chest", $amount);
                break;
            case 'Receipt':
                auth()->user()->pharmacy()->decrement("safe", $amount);
                auth()->user()->pharmacy()->increment("chest", $amount);
                break;
            default:
                auth()->user()->pharmacy()->increment("safe", $amount);
        }
    }
}
