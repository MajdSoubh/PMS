<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CustomerResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Traits\HttpResponses;
use Pdf;
use Illuminate\Support\Carbon;

class CustomerController extends Controller
{
    use HttpResponses;


    public function __construct()
    {
        $this->middleware('permission:customer-list')->only('index', 'show', 'export');
        $this->middleware('permission:customer-create')->only('store');
        $this->middleware('permission:customer-edit')->only('update');
        $this->middleware('permission:customer-delete')->only('destroy');
    }

    /**
     * @OA\Get(
     * path="/api/customer",
     * operationId="customerAll",
     * tags={"Customer"},
     * summary="Return all customers",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/CustomerSchema")
     *                  )
     *  )
     */
    public function index()
    {
        $customers = auth()->user()->pharmacy->customers;


        return CustomerResource::collection($customers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Post(
     * path="/api/customer",
     * operationId="createCustomer",
     * tags={"Customer"},
     * summary="Create new Customer",
     *     @OA\RequestBody(
     *       @OA\JsonContent(
     *      @OA\Property(
     *           property="name",
     *           type="integer",
     *           description="Customer name",
     *           nullable=false,
     *       ),
     *       @OA\Property(
     *           property="contact",
     *           type="integer",
     *           description="Customer phone number",
     *           nullable=false,
     *       ),
     *       @OA\Property(
     *           property="address",
     *           type="integer",
     *           description="Customer address",
     *           nullable=false,
     *       ),
     *                  ),
     *                     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/CustomerSchema")
     *                  )
     *  )
     */
    public function store(StoreCustomerRequest $request)
    {
        $validated = $request->validated();
        $customer = Customer::create($validated)->pharmacy()->associate(auth()->user()->pharmacy->id);
        $customer->save();
        return (new CustomerResource($customer))->additional(['message' => __('messages.model.created', ['name' => __('customer')]), 'status' => 1])->response(201);
    }

    /**
     * @OA\Get(
     * path="/api/customer/{id}",
     * operationId="getCustomer",
     * tags={"Customer"},
     * summary="Find customer by id",
     *     @OA\Parameter(
     *         name="id",
     *         description="Customer ID",
     *         in="path",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/CustomerSchema")
     *                  ),
     *     @OA\Response(
     *         response=404,
     *         description="Customer not found",
     *                  )
     *  )
     */
    public function show($id)
    {
        $customer =  auth()->user()->pharmacy->customers->find($id);
        if (!$customer)
        {
            return $this->error(null, __('messages.model.lost', ['name' => __('customer')]), 422);
        }

        return (new CustomerResource($customer))->additional(['status' => 'successfull']);;
    }

    /**
     * @OA\Put(
     * path="/api/customer/{id}",
     * operationId="editCustomer",
     * tags={"Customer"},
     * summary="Edit customer by id",
     *     @OA\Parameter(
     *         name="id",
     *         description="Customer ID",
     *         in="path",
     *         required=true,
     *     ),
     *     @OA\RequestBody(
     *       @OA\JsonContent(
     *      @OA\Property(
     *           property="name",
     *           type="integer",
     *           description="Customer name",
     *           nullable=false,
     *       ),
     *       @OA\Property(
     *           property="contact",
     *           type="integer",
     *           description="Customer phone number",
     *           nullable=false,
     *       ),
     *       @OA\Property(
     *           property="address",
     *           type="integer",
     *           description="Customer address",
     *           nullable=false,
     *       ),
     *                  ),
     *                     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/CustomerSchema")
     *                  )
     *  )
     */
    public function update(UpdateCustomerRequest $request, $id)
    {
        $customer =  auth()->user()->pharmacy->customers->find($id);
        if (!$customer)
        {
            return $this->error(null, __('messages.model.lost', ['name' => __('customer')]), 422);
        }
        $customer->update($request->safe()->all());
        return (new CustomerResource($customer))->additional(['message' => __('messages.model.updated', ['name' => __('customer')]), 'status' => 1]);
    }

    /**
     * @OA\delete(
     * path="/api/customer/{id}",
     * operationId="deleteCustomer",
     * tags={"Customer"},
     * summary="Delete customer by id",
     *     @OA\Parameter(
     *         name="id",
     *         description="Customer ID",
     *         in="path",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *      ),
     *     @OA\Response(
     *         response=402,
     *         description="Customer not found",
     *      ),
     *  )
     */
    public function destroy($id)
    {
        $customer =  auth()->user()->pharmacy->customers->find($id);
        if (!$customer)
        {
            return $this->error(null, __('messages.model.lost', ['name' => __('customer')]), 422);
        }
        $customer->delete();
        return (new CustomerResource($customer))->additional(['message' => __('messages.model.deleted', ['name' => __('customer')]), 'status' => 1]);
    }
    public function export()
    {
        $customers =  auth()->user()->pharmacy->customers->all();
        $pdf = Pdf::loadView('Pdf.customers', ["customers" => $customers]);
        $date = Carbon::now()->toDateString();
        return $pdf->download('customers-report_' . $date . '.pdf');
    }
}
