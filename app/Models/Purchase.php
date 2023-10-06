<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];
    protected $hidden = [
        'pharmacy_id',
        'supplier_id'
    ];

    protected $attributes = [
        'total' => '0'
    ];


    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItems::class);
    }
}
