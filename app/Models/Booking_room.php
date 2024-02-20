<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking_room extends Model
{
    use HasFactory;
    protected $table = 'booking_rooms';
    protected $primarykey = 'id';


    protected $fillable = [
        'name',
        'status'
    ];

public function booking(){
    return $this->belongsTo(Boarding::class);
}
}
