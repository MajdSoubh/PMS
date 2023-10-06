<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];
    protected $casts = [
        'totalProfit' => 'decimal:2',
        'total' => 'decimal:2',
    ];
    protected $hidden = [
        'pharmacy_id',
        'customer_id'
    ];
    protected $attributes = [
        'total' => '0',
        'totalDiscount' => '0',
        'totalProfit' => '0'
    ];


    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItems::class);
    }
}
