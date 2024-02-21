<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    use HasUuids;
    protected $table = 'invoices';
    protected $primarykey ='id';
    public $incrementing = 'false';

    protected $fillable = [
        'appointment_id',
        'amount',
        'due_date',
        'status'

    ];
   public function appointment(){

    return $this->belongsTo(Appointment::class,'apppointment_id');

   }

   public function payment(){
    return $this->hasMany(Payment::class);
   }

}
