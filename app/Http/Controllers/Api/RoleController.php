<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Resources\RoleResource;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    use HttpResponses;

    public function __construct()
    {
        $this->middleware('permission:role-list')->only('index', 'show', 'export');
        /*      $this->middleware('permission:role-create')->only('store');
        $this->middleware('permission:role-edit')->only('update');
        $this->middleware('permission:role-delete')->only('destroy'); */
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();

        return RoleResource::collection($roles);
    }

    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function store(StoreRoleRequest $request)
    // {
    //     $role = Role::create($request->except('permissions'));
    //     $role->pharmacy()->associate(auth()->user()->pharmacy->id);
    //     if ($request->has('permissions'))
    //     {
    //         $role->syncPermissions($request->permissions);
    //     }
    //     $role->save();
    //     $role->load('permissions');
    //     return (new RoleResource($role))->additional(['message' => __('messages.model.created', ['name' => __('role')]), 'status' => 1])->response(201);
    // }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        if (!$role)
        {
            return $this->error(null, __('messages.model.lost', ['name' => __('role')]), 422);
        }

        $role->load('permissions');
        return (new RoleResource($role))->additional(['status' => 1]);
    }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(UpdateRoleRequest $request, $id)
    // {
    //     $role =  auth()->user()->pharmacy->roles->find($id);
    //     if (!$role)
    //     {
    //         return $this->error(null, __('messages.model.lost', ['name' => __('role')]), 422);
    //     }
    //     $role->update($request->except('permissions'));
    //     if ($request->has('permissions'))
    //     {
    //         $role->syncPermissions($request->permissions);
    //     }

    //     $role->loadMissing('permissions');
    //     return (new RoleResource($role))->additional(['message' => __('messages.model.updated', ['name' => __('role')]), 'status' => 1]);
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy($id)
    // {
    //     $role =  auth()->user()->pharmacy->roles->find($id);
    //     if (!$role)
    //     {
    //         return $this->error(null, __('messages.model.lost', ['name' => __('role')]), 422);
    //     }
    //     $role->load('permissions');
    //     $role->delete();
    //     return (new RoleResource($role))->additional(['message' => __('messages.model.deleted', ['name' => __('role')]), 'status' => 1]);
    // }
}
