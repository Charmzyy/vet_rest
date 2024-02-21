<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;
    use HasUuids;
    protected $table = 'payments';
    protected $primarykey = 'id';
    public $incrementing = false;


    protected $fillable  = [
        
        'user_id',
        'invoice_id',
        'amount',
        'merchant_request_id',
        'checkout_request_id',

    ];

public function invoice(){
    return $this->belongsTo(Invoice::class,'invoice_id');
}
}
