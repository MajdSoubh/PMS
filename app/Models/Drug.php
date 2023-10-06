<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drug extends Model
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
    public function stock()
    {
        return $this->hasMany(Stock::class);
    }
}
