<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Boarding extends Model
{
    use HasFactory;
    use HasUuids;
    protected $table = 'boarding_bookings';
    protected $primarykey = 'id';
    public $incrementing = false;


    protected $fillable  = [
        'reservation',
        'pet_id',
        'start',
        'end',
        'room_id',
        'owner_id',
        'room'

    ];
public function room(){
    return $this->hasOne(Booking_room::class);
}
}
