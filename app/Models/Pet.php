<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;
    use HasUuids;
    protected $table = 'pets';
    protected $primarykey = 'id';
    public $incrementing = false;


    protected $fillable  = [
        'pet_name',
        'owner_id',
        'doc_id',
        'owner_id',
        'species_id',
        'breed_id',
        'dob'

    ];

    public function uniqueIds(): array
    {
        return ['id'];
    }

    public function owner(){
        return $this->belongsTo(User::class,'owner_id');
    }

    public function appointments() {
        return $this->hasMany(Appointment::class);
    }
}
