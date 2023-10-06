<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

/**
 * @OA\Schema(
 *    schema="PharmacySchema",
 *        @OA\Property(
 *            property="id",
 *            description="Pharmacy identifier",
 *            type="integer",
 *            nullable="false",
 *        ),
 *        @OA\Property(
 *            property="name",
 *            description="Pharmacy name",
 *            type="string",
 *            nullable="false",
 *        ),
 *        @OA\Property(
 *            property="licence",
 *            description="Pharmacy licence id",
 *            type="string",
 *            nullable="false",
 *        ),
 *        @OA\Property(
 *            property="safe",
 *            description="Safe amount",
 *            type="double",
 *            nullable="false",
 *        ),
 *        @OA\Property(
 *            property="chest",
 *            description="Chest amount",
 *            type="double",
 *            nullable="false",
 *        ),

 *    )
 * )
 */
class Pharmacy extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];
    protected $hidden = [];
    protected $casts = [
        'chest' => 'decimal:2',
        'safe' => 'decimal:2',
    ];
    public function owner()
    {
        return $this->hasMany(User::class);
    }
    public function employees()
    {

        return $this->hasMany(User::class)->where('isOwner', false);
    }
    public function drugs()
    {
        return $this->hasMany(Drug::class);
    }
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }
    public function vouchers()
    {
        return $this->hasMany(Voucher::class);
    }
    public function roles()
    {
        return $this->hasMany(Role::class);
    }
}
