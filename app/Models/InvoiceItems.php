<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItems extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];
    protected $hidden = [
        'invoice_id',
        'drug_id'
    ];
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
    public function drug()
    {

        return $this->belongsTo(Drug::class);
    }
}
