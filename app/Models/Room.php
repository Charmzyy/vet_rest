<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $table = 'rooms';
    protected $primarykey = 'id';


    protected $fillable = [
        'number',
        'is_available'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_number');
    }
}
