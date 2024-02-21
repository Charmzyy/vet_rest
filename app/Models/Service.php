<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $table= 'services';
    protected $primarykey = 'id';


    protected $fillable = [
        'name',
        'price',
        'description'
    ];
    public function appointments(){
        return $this->belongsTo('appointment_service','service_id','appointment_id');
    }

}
