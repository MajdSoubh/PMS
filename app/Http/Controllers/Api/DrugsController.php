<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Drug\UpdateDrugRequest;
use App\Http\Requests\Drug\StoreDrugRequest;
use App\Http\Resources\DrugResource;
use Illuminate\Support\Facades\Storage;
use App\Models\Drug;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use PDF;

class DrugsController extends Controller
{
    use HttpResponses;

    public function __construct()
    {
        $this->middleware('permission:drug-list')->only('index', 'show', 'export');
        $this->middleware('permission:drug-create')->only('store');
        $this->middleware('permission:drug-edit')->only('update');
        $this->middleware('permission:drug-delete')->only('destroy');
    }

    /**
     * @OA\Get(
     * path="/api/drug",
     * operationId="drugAll",
     * tags={"Drug"},
     * summary="Return all drugs",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/DrugSchema")
     *                  )
     *  )
     */
    public function index()
    {

        $drugs = auth()->user()->pharmacy->drugs;

        return DrugResource::collection($drugs);
    }

    /**
     * @OA\Post(
     * path="/api/Drug",
     * operationId="createDrug",
     * tags={"Drug"},
     * summary="Create new Drug",
     *     @OA\RequestBody(
     *       @OA\JsonContent( @OA\Property(
     *            property="name",
     *            description="Drug name",
     *            type="string",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="generic",
     *            description="Drug generic name",
     *            type="string",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="description",
     *            description="Drug description",
     *            type="string",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="price",
     *            description="Drug price",
     *            type="int",
     *            nullable="false",
     *        ),
     *                      ),
     *                     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/DrugSchema")
     *                  )
     *  )
     */
    public function store(StoreDrugRequest $request)
    {
        $validated = $request->validated();
        $drug = Drug::create($validated)->pharmacy()->associate(auth()->user()->pharmacy->id);
        if ($request->file('image'))
        {
            $path = $request->file('image')->store('images');
            $drug->image = $path;
        }
        $drug->save();
        return (new DrugResource($drug))->additional(['message' => __('messages.model.created', ['name' => __('drug')]), 'status' => 1])->response(201);
    }

    /**
     * @OA\Get(
     * path="/api/drug/{id}",
     * operationId="getDrug",
     * tags={"Drug"},
     * summary="Find Drug by id",
     *     @OA\Parameter(
     *         name="id",
     *         description="Drug ID",
     *         in="path",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/DrugSchema")
     *                  ),
     *     @OA\Response(
     *         response=404,
     *         description="Drug not found",
     *                  )
     *  )
     */
    public function show($id)
    {
        $drug =  auth()->user()->pharmacy->drugs->find($id);
        if (!$drug)
        {
            return $this->error(null, __('messages.model.lost', ['name' => __('drug')]), 422);
        }

        return (new DrugResource($drug))->additional(['status' => 1]);
    }

    /**
     * @OA\Put(
     * path="/api/drug/{id}",
     * operationId="editDrug",
     * tags={"Drug"},
     * summary="Edit Drug by id",
     *     @OA\Parameter(
     *         name="id",
     *         description="Drug ID",
     *         in="path",
     *         required=true,
     *     ),
     *     @OA\RequestBody(
     *       @OA\JsonContent( @OA\Property(
     *            property="name",
     *            description="Drug name",
     *            type="string",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="generic",
     *            description="Drug generic name",
     *            type="string",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="description",
     *            description="Drug description",
     *            type="string",
     *            nullable="false",
     *        ),
     *        @OA\Property(
     *            property="price",
     *            description="Drug price",
     *            type="int",
     *            nullable="false",
     *        ),
     *                      ),
     *                     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/DrugSchema")
     *                  )
     *  )
     */
    public function update(UpdateDrugRequest $request, $id)
    {

        $drug = auth()->user()->pharmacy->drugs->find($id);
        if (!$drug)
        {
            return $this->error(null, __('messages.model.lost', ['name' => __('drug')]), 422);
        }
        $drug->update($request->safe()->all());
        if ($request->file('image'))
        {
            if (Storage::exists($drug->image))
            {
                Storage::delete($drug->image);
            }
            $path = $request->file('image')->store('images');
            $drug->image = $path;
            $drug->save();
        }
        return (new DrugResource($drug))->additional(['message' => __('messages.model.updated', ['name' => __('drug')]), 'status' => 1]);
    }

    /**
     * @OA\delete(
     * path="/api/drug/{id}",
     * operationId="deleteDrug",
     * tags={"Drug"},
     * summary="Delete Drug by id",
     *     @OA\Parameter(
     *         name="id",
     *         description="Drug ID",
     *         in="path",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *      ),
     *     @OA\Response(
     *         response=402,
     *         description="Drug not found",
     *      ),
     *  )
     */
    public function destroy($id)
    {
        $drug =  auth()->user()->pharmacy->drugs->find($id);
        if (!$drug)
        {
            return $this->error(null, __('messages.model.lost', ['name' => __('drug')]), 422);
        }
        if (!is_null($drug->image) && Storage::exists($drug->image))
        {

            Storage::delete($drug->image);
        }
        $drug->delete();
        return (new DrugResource($drug))->additional(['message' => __('messages.model.deleted', ['name' => __('drug')]), 'status' => 1]);
    }
    public function export()
    {
        $drugs =  auth()->user()->drugs->all();
        $pdf = Pdf::loadView('Pdf.drugs', ["drugs" => $drugs]);
        $date = Carbon::now()->toDateString();
        return $pdf->download('drugs-report_' . $date . '.pdf');
    }
}
