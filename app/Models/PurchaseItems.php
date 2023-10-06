<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItems extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];
    protected $hidden = [
        'purchase_id',
        'drug_id'
    ];
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
    public function drug()
    {

        return $this->belongsTo(Drug::class);
    }
}
