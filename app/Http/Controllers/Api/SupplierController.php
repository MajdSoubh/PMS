<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Supplier\StoreSupplierRequest;
use App\Http\Requests\supplier\UpdateSupplierRequest;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use PDF;

class SupplierController extends Controller
{
    use HttpResponses;

    public function __construct()
    {
        $this->middleware('permission:supplier-list')->only('index', 'show', 'export');
        $this->middleware('permission:supplier-create')->only('store');
        $this->middleware('permission:supplier-edit')->only('update');
        $this->middleware('permission:supplier-delete')->only('destroy');
    }

    /**
     * @OA\Get(
     * path="/api/supplier",
     * operationId="SupplierAll",
     * tags={"Supplier"},
     * summary="Return all Suppliers",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/SupplierSchema")
     *                  )
     *  )
     */
    public function index()
    {
        $suppliers = auth()->user()->pharmacy->suppliers;
        return SupplierResource::collection($suppliers);
    }

    /**
     * @OA\Post(
     * path="/api/Supplier",
     * operationId="createSupplier",
     * tags={"Supplier"},
     * summary="Create new Supplier",
     *     @OA\RequestBody(
     *       @OA\JsonContent(
     *      @OA\Property(
     *           property="name",
     *           type="integer",
     *           description="Supplier name",
     *           nullable=false,
     *       ),
     *       @OA\Property(
     *           property="contact",
     *           type="integer",
     *           description="Supplier phone number",
     *           nullable=false,
     *       ),
     *       @OA\Property(
     *           property="address",
     *           type="integer",
     *           description="Supplier address",
     *           nullable=false,
     *       ),
     *        @OA\Property(
     *            property="email",
     *            description="Supplier email",
     *            type="string",
     *            nullable="false",
     *        ),
     *                  ),
     *                     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/SupplierSchema")
     *                  )
     *  )
     */
    public function store(StoreSupplierRequest $request)
    {
        $validated = $request->validated();
        $supplier = Supplier::create($validated);
        $supplier->pharmacy()->associate(auth()->user()->pharmacy->id);
        $supplier->save();
        return (new SupplierResource($supplier))->additional(['message' => __('messages.model.created', ['name' => __('supplier')]), 'status' => 1])->response(201);
    }

    /**
     * @OA\Get(
     * path="/api/supplier/{id}",
     * operationId="getSupplier",
     * tags={"Supplier"},
     * summary="Find Supplier by id",
     *     @OA\Parameter(
     *         name="id",
     *         description="Supplier ID",
     *         in="path",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/SupplierSchema")
     *                  ),
     *     @OA\Response(
     *         response=404,
     *         description="Supplier not found",
     *                  )
     *  )
     */
    public function show($id)
    {
        $supplier =  auth()->user()->pharmacy->suppliers->find($id);
        if (!$supplier)
        {
            return $this->error(null, __('messages.model.lost', ['name' => __('supplier')]), 422);
        }

        return (new SupplierResource($supplier))->additional(['status' => 1]);
    }

    /**
     * @OA\Put(
     * path="/api/supplier/{id}",
     * operationId="editSupplier",
     * tags={"Supplier"},
     * summary="Edit Supplier by id",
     *     @OA\Parameter(
     *         name="id",
     *         description="Supplier ID",
     *         in="path",
     *         required=true,
     *     ),
     *     @OA\RequestBody(
     *       @OA\JsonContent(
     *      @OA\Property(
     *           property="name",
     *           type="integer",
     *           description="Supplier name",
     *           nullable=false,
     *       ),
     *       @OA\Property(
     *           property="contact",
     *           type="integer",
     *           description="Supplier phone number",
     *           nullable=false,
     *       ),
     *       @OA\Property(
     *           property="address",
     *           type="integer",
     *           description="Supplier address",
     *           nullable=false,
     *       ),
     *        @OA\Property(
     *            property="email",
     *            description="Supplier email",
     *            type="string",
     *            nullable="false",
     *        ),
     *                  ),
     *                     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/SupplierSchema")
     *                  )
     *  )
     */
    public function update(UpdateSupplierRequest $request, $id)
    {
        $supplier =  auth()->user()->pharmacy->suppliers->find($id);
        if (!$supplier)
        {
            return $this->error(null, __('messages.model.lost', ['name' => __('supplier')]), 422);
        }
        $supplier->update($request->safe()->all());
        return (new SupplierResource($supplier))->additional(['message' => __('messages.model.updated', ['name' => __('supplier')]), 'status' => 1]);
    }

    /**
     * @OA\delete(
     * path="/api/supplier/{id}",
     * operationId="deleteSupplier",
     * tags={"Supplier"},
     * summary="Delete Supplier by id",
     *     @OA\Parameter(
     *         name="id",
     *         description="Supplier ID",
     *         in="path",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *      ),
     *     @OA\Response(
     *         response=402,
     *         description="Supplier not found",
     *      ),
     *  )
     */
    public function destroy($id)
    {
        $supplier =  auth()->user()->pharmacy->suppliers->find($id);
        if (!$supplier)
        {
            return $this->error(null, __('messages.model.lost', ['name' => __('supplier')]), 422);
        }
        $supplier->delete();
        return (new SupplierResource($supplier))->additional(['message' => __('messages.model.deleted', ['name' => __('supplier')]), 'status' => 1]);
    }
    public function export()
    {
        $suppliers =  auth()->user()->pharmacy->suppliers->all();
        $pdf = Pdf::loadView('Pdf.suppliers', ["suppliers" => $suppliers]);
        $date = Carbon::now()->toDateString();
        return $pdf->download('suppliers-report_' . $date . '.pdf');
    }
}
