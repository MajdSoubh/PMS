<?php

use App\Http\Controllers\Api\AnalyticsController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DrugsController;
use App\Http\Controllers\Api\StockController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\PurchaseController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\VoucherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth')->get('/user', function (Request $request)
{

    return $request->user()->load('pharmacy');
});


// Auth Routes
Route::group(["middleware" => "auth:sanctum"], function ()
{   // Employees
    Route::post("employee", [AuthController::class, "registerEmployee"])->name('employee.store');
    Route::get("employee", [AuthController::class, "getAllEmployees"])->name('employee.index');
    Route::get("employee/{id?}", [AuthController::class, "showEmployee"])->name('employee.show');
    Route::put("employee/{id?}", [AuthController::class, "updateEmployee"])->name('employee.update');
    Route::delete("employee/{id?}", [AuthController::class, "deleteEmployee"])->name('employee.destroy');

    // Owners & Employees Logout
    Route::delete("logout", [AuthController::class, "logout"])->name('logout');
});
Route::post("owner", [AuthController::class, "registerOwner"])->name('owner.store');
Route::post("login", [AuthController::class, "login"])->name('login');
Route::post("forget-password", [AuthController::class, "forgetPassword"])->name('password.forget');
Route::post("reset-password/{token?}", [AuthController::class, "resetPassword"])->name('password.reset');



Route::middleware('auth')->group(function ()
{

    // Drugs CRUD routes
    Route::get('drug/export', [DrugsController::class, 'export'])->name('drug.export');
    Route::apiResource('drug', DrugsController::class);

    // Stock CRUD routes
    Route::get('stock/export', [StockController::class, 'export'])->name('stock.export');
    Route::apiResource('stock', StockController::class);
    Route::put('stock', [StockController::class, 'showBy'])->name('stock.show.by');

    // Suppliers CRUD routes
    Route::get('supplier/export', [SupplierController::class, 'export'])->name('supplier.export');
    Route::apiResource('supplier', SupplierController::class);

    // Customers CRUD routes
    Route::get('customer/export', [CustomerController::class, 'export'])->name('customer.export');
    Route::apiResource('customer', CustomerController::class);

    // Invoices CRUD routes
    Route::get('invoice/export', [InvoiceController::class, 'export'])->name('invoice.export');
    Route::apiResource('invoice', InvoiceController::class);

    // Purchase CRUD routes
    Route::get('purchase/export', [PurchaseController::class, 'export'])->name('purchase.export');
    Route::apiResource('purchase', PurchaseController::class);

    // Voucher CRUD routes
    Route::get('voucher/export', [VoucherController::class, 'export'])->name('voucher.export');
    Route::apiResource('voucher', VoucherController::class);

    // Role CRUD routes
    Route::apiResource('role', RoleController::class)->except("store", "update", "destory");

    // Analytics routes
    Route::get('analytical/dashboard', [AnalyticsController::class, 'dashboard']);
});
