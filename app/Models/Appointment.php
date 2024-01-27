<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    use HasUuids;
    protected $table = 'appointments';
    protected $primarykey = 'id';
    public $incrementing = false;


    protected $fillable  = [
        'description',
        'pet_id',
        'doc_id',
        'owner_id',
        'book_date',
        'book_time'

    ];

    public function uniqueIds(): array
    {
        return ['id'];
    }
   
    public function user(){
        return $this->belongsTo(User::class);
    }
}
