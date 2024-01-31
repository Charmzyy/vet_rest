<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalRecordFile extends Model
{
    use HasFactory;
    use HasUuids;
    protected $table = 'medical_records_files';
    protected $primarykey = 'id';
    
    public $incrementing = false;
    protected $fillable = [
        'medical_record_id',
        'file_path',
        
    ];

    public function myrecord(){
        return $this->belongsTo(MedicalRecord::class,'medical_record_id');
    }
}
