<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;
    use HasUuids;
    protected $table = 'medical_records';
    protected $primarykey = 'id';
    
    public $incrementing = false;
    protected $fillable = [
        'appointment_id',
        'title',
        'description',
        
    ];

    public function myappointment(){
        return $this->belongsTo(Appointment::class,'appointment_id');
    }
    public function myfiles(){
        return $this->hasMany(MedicalRecordFile::class);
    }
}
