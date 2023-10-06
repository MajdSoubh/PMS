<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
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
    public function drug()
    {

        return $this->belongsTo(Drug::class);
    }
    public function supplier()
    {

        return $this->belongsTo(Supplier::class);
    }
}
