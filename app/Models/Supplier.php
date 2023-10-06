<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];
    protected $hidden = [
        'pharmacy_id'
    ];
    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}
