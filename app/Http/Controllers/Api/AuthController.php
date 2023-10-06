<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ForgetPasswordRequest;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\PasswordResetRequest;
use App\Http\Requests\User\StoreOwnerRequest;
use App\Http\Requests\Employee\StoreEmployeeRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;
use App\Models\Pharmacy;
use App\Traits\HttpResponses;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Http\Resources\EmployeeResource;

class AuthController extends Controller
{
    use HttpResponses;
    public function __construct()
    {
        $this->middleware('permission:employee-list')->only('getAllEmployees', 'showEmployee');
        $this->middleware('permission:employee-create')->only('registerEmployee');
        $this->middleware('permission:employee-edit')->only('updateEmployee');
        $this->middleware('permission:employee-delete')->only('deleteEmployee');
    }
    /**
     * @OA\Get(
     * path="/api/employee",
     * operationId="employeeAll",
     * tags={"Authentication"},
     * summary="Return all employees",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",)
     *      )
     */
    public function getAllEmployees()
    {
        $employees = auth()->user()->pharmacy->employees->load('roles');
        return EmployeeResource::collection($employees);
    }
    /**
     * @OA\get(
     * path="/api/employee/{id}",
     * operationId="showEmployee",
     * tags={"Authentication"},
     * summary="Find employee by id",
     *     @OA\Parameter(
     *         name="id",
     *         description="Employee ID",
     *         in="path",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",)
     *      )
     *     @OA\Response(
     *         response=404,
     *         description="Employee not found",)
     *      )
     */
    public function showEmployee($id)
    {
        $employee =  auth()->user()->pharmacy->employees->find($id);
        if (!$employee)
        {
            return $this->error(null, __('messages.model.lost', ['name' => __('employee')]), 422);
        }

        return (new EmployeeResource($employee))->additional(['status' => 1]);
    }
    /**
     * @OA\Put(
     * path="/api/employee/{id}",
     * operationId="EmployeeUpdate",
     * tags={"Authentication"},
     * summary="Edit employee by id",
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *                           @OA\Property(
     *                  property="firstName",
     *                  description="User's first name",
     *                  type="string",
     *                  nullable="false",
     *                                      ),
     *                          @OA\Property(
     *                  property="lastName",
     *                  description="User's last name",
     *                  type="string",
     *                  nullable="false",
     *                                      ),
     *
     *                          @OA\Property(
     *                  property="pharmacyName",
     *                  description="Pharmacy name",
     *                  type="string",
     *                  nullable="false",
     *                                      ),
     *                          @OA\Property(
     *                  property="roles",
     *                  description="Pharmacy name",
     *                  type="array",
     *                  nullable="false",
     *                  @OA\Items(
     *                       @OA\Property(
     *                          property="role",
     *                          type="string",
     *                          description="Role name",
     *                                    )
     *                            ),
     *                                      ),
     *                         @OA\Property(
     *                  property="email",
     *                  description="User's email",
     *                  type="string",
     *                  nullable="false",
     *                                      ),
     *                          @OA\Property(
     *                  property="password",
     *                  description="User's password",
     *                  type="string",
     *                  nullable="false",
     *                                      ),
     *                          @OA\Property(
     *                  property="password_confirmation",
     *                  description="Password confirmation",
     *                  type="string",
     *                  nullable="false",
     *                                      ),
     *                          ),
     *
     *                      ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",),
     *     @OA\Response(
     *         response=422,
     *         description="Un proccessiable content",)
     *          )
     *      )
     */
    public function updateEmployee(UpdateEmployeeRequest $request, $id)
    {

        $employee =  auth()->user()->pharmacy->employees->find($id);
        if (!$employee)
        {
            return $this->error(null, __('messages.model.lost', ['name' => __('employee')]), 422);
        }
        $employee->update($request->except('roles', 'password_confirmation'));
        $employee->roles()->detach();
        // Assign roles to employee
        if ($request->has('roles'))
            foreach ($request->roles as $role)
            {

                $employee->assignRole($role);
            }
        return (new EmployeeResource($employee))->additional(['message' => __('messages.model.updated', ['name' => __('employee')]), 'status' => 1]);
    }
    /**
     * @OA\Post(
     * path="/api/owner",
     * operationId="OwnerRegister",
     * tags={"Authentication"},
     * summary="Owner Register",
     * description="Owner Register here",
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *                           @OA\Property(
     *                  property="firstName",
     *                  description="User's first name",
     *                  type="string",
     *                  nullable="false",
     *                                      ),
     *                          @OA\Property(
     *                  property="lastName",
     *                  description="User's last name",
     *                  type="string",
     *                  nullable="false",
     *                                      ),
     *
     *                          @OA\Property(
     *                  property="pharmacyName",
     *                  description="Pharmacy name",
     *                  type="string",
     *                  nullable="false",
     *                                      ),
     *                          @OA\Property(
     *                  property="licence",
     *                  description="Pharmacy licence",
     *                  type="string",
     *                  nullable="false",
     *                                      ),
     *      *                   @OA\Property(
     *                  property="email",
     *                  description="User's email",
     *                  type="string",
     *                  nullable="false",
     *                                      ),
     *                          @OA\Property(
     *                  property="password",
     *                  description="User's password",
     *                  type="string",
     *                  nullable="false",
     *                                      ),
     *                          @OA\Property(
     *                  property="password_confirmation",
     *                  description="Password confirmation",
     *                  type="string",
     *                  nullable="false",
     *                                      ),
     *                          ),
     *
     *                      ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",),
     *     @OA\Response(
     *         response=422,
     *         description="Un proccessiable content",)
     *          )
     *      )
     */
    public function registerOwner(StoreOwnerRequest $request)
    {
        $user = User::create($request->safe()->except('password', 'password_confirmation', 'pharmacyName') +
            [
                "password" => Hash::make($request->password),
                "isOwner" => true
            ]);
        $pharmacy = Pharmacy::create([
            'name' => $request->input('pharmacyName'),
            'licence' => $request->input('licence')
        ]);
        $user->pharmacy()->associate($pharmacy->id);
        $user->save();

        $user->permissions = $user->getAllPermissions();
        return $this->success(["user" => $user->load('pharmacy'), "token" => $user->createToken("Api token of " . $user->email)->plainTextToken], __('auth.register'));
    }
    /**
     * @OA\Post(
     * path="/api/employee",
     * operationId="EmployeeRegister",
     * tags={"Authentication"},
     * summary="Employee register",
     * description="Employee register here",
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *                           @OA\Property(
     *                  property="firstName",
     *                  description="User's first name",
     *                  type="string",
     *                  nullable="false",
     *                                      ),
     *                          @OA\Property(
     *                  property="lastName",
     *                  description="User's last name",
     *                  type="string",
     *                  nullable="false",
     *                                      ),
     *
     *                          @OA\Property(
     *                  property="roles",
     *                  description="Pharmacy name",
     *                  type="array",
     *                  nullable="false",
     *                  @OA\Items(
     *                       @OA\Property(
     *                          property="role",
     *                          type="string",
     *                          description="Role name",
     *                                    )
     *                            ),
     *                                      ),
     *      *                   @OA\Property(
     *                  property="email",
     *                  description="User's email",
     *                  type="string",
     *                  nullable="false",
     *                                      ),
     *                          @OA\Property(
     *                  property="password",
     *                  description="User's password",
     *                  type="string",
     *                  nullable="false",
     *                                      ),
     *                          @OA\Property(
     *                  property="password_confirmation",
     *                  description="Password confirmation",
     *                  type="string",
     *                  nullable="false",
     *                                      ),
     *                          ),
     *
     *                      ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",),
     *     @OA\Response(
     *         response=422,
     *         description="Un proccessiable content",)
     *          )
     *      )
     */
    public function registerEmployee(StoreEmployeeRequest $request)
    {


        $user = User::create($request->except('password', 'password_confirmation', 'roles') +
            [
                "isOwner" => false,
                "password" => Hash::make($request->password)
            ]);
        $user->pharmacy()->associate(auth()->user()->pharmacy->first());
        $user->save();
        // Assign roles to user
        if ($request->has('roles'))
            foreach ($request->roles as $role)
            {

                $user->assignRole($role);
            }


        $user->permissions = $user->getAllPermissions();

        return $this->success(["user" => $user->load('pharmacy'), "token" => $user->createToken("Api token of " . $user->email)->plainTextToken], __('auth.register'));
    }
    /**
     * @OA\delete(
     * path="/api/employee/{id}",
     * operationId="deleteEmployee",
     * tags={"Authentication"},
     * summary="Delete employee by id",
     *     @OA\Parameter(
     *         name="id",
     *         description="Employee ID",
     *         in="path",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *      ),
     *     @OA\Response(
     *         response=402,
     *         description="Employee not found",
     *      ),
     *  )
     */
    public function deleteEmployee($id)
    {
        $employee = auth()->user()->pharmacy->employees->find($id);
        if (!$employee)
        {
            return $this->error(null, __('messages.model.lost', ['name' => __('employee')]), 422);
        }
        $employee->delete();
        return (new EmployeeResource($employee))->additional(['message' => __('messages.model.deleted', ['name' => __('employee')]), 'status' => 1]);
    }
    /**
     * @OA\Post(
     * path="/api/login",
     * operationId="UserLogin",
     * tags={"Authentication"},
     * summary="Users login",
     * description="Users login here",
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *
     *                          @OA\Property(
     *                  property="email",
     *                  description="User's email",
     *                  type="string",
     *                  nullable="false",
     *                                      ),
     *                          @OA\Property(
     *                  property="password",
     *                  description="User's password",
     *                  type="string",
     *                  nullable="false",
     *                                      ),
     *                          ),
     *
     *                      ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",),
     *     @OA\Response(
     *         response=422,
     *         description="Un proccessiable content",)
     *          )
     *      )
     */
    public function login(LoginRequest $request)
    {

        $user = User::where("email", $request->email)->first();
        if (!$user)
        {
            return $this->error(null, __('auth.failed'), 422);
        }
        $user->permissions = $user->getAllPermissions();
        return $this->success(["user" => $user->load('pharmacy'), "token" => $user->createToken("Api token of " . $user->email)->plainTextToken], __('auth.login'));
    }
    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();
        return $this->success(null, __('auth.logout'));
    }

    public function forgetPassword(ForgetPasswordRequest $request)
    {
        $status = Password::sendResetLink($request->only("email"));

        return $status === Password::RESET_LINK_SENT ? $this->success(null, __($status)) : $this->error(null, __($status), 400);
    }
    public function resetPassword(PasswordResetRequest $request, $token = null)
    {
        $_token = $token ? $token : $request->token;
        $credentials = $request->only([
            "email",
            "password",
            "password_confirmation"
        ]) + ["token" => $_token];
        $status = Password::reset($credentials, function (User $user, $password)
        {
            $user->password = Hash::make($password);
            $user->save();
            /*  if ($request->has("logoutAllDevices") && $request->logoutAllDevices)
            {
            } */
        });
        return $status === Password::PASSWORD_RESET ? $this->success(null, __($status)) : $this->error(null, __($status), 400);
    }
}
