<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Invoice extends Model
{
    use HasFactory;
    protected $hidden = [];
    protected $casts = [
        'total' => 'double',
        'total_discount' => 'double',
        'total_net' => 'double',
        'paid' => 'double',
        'customer_id' => 'integer',
        'user_id' => 'integer',
        'payment_id' => 'integer',
        'rest' => 'double',
    ];
    protected $fillable = [
        'total',
        'total_discount',
        'total_net',
        'rest',
        'paid',
        'customer_id',
        'user_id',
        'payment_id',
        'date',
    ];

    public function user()
    {
        return  $this->belongsTo(User::class);
    }
    public function customer()
    {
        return  $this->belongsTo(Customer::class);
    }
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
    public function invoiceItems()
    {
        return  $this->hasMany(InvoiceItems::class);
    }
}
