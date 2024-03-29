<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specie extends Model
{
    use HasFactory;
    protected $table = 'species';
    protected $primarykey = 'id';


    protected $fillable = [
        'name',
    
    ];

    public function species() {
        return $this->hasMany(Breed::class);
    }
}
