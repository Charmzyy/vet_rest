<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Breed extends Model
{
    use HasFactory;
    protected $table = 'breeds';
    protected $primarykey = 'id';


    protected $fillable = [
        'breed_name',
        'species_id',
    ];

    public function breed(){
        return $this->belongsTo(Specie::class, 'species_id');
    }
}
