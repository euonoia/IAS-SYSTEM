<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $fillable = [
        'student_medical_record_id', 
        'symptoms', 
        'diagnosis', 
        'treatment', 
        'medicine_id',
        'quantity_used'
    ];

    public function student_medical_record()
    {
        return $this->belongsTo(StudentMedicalRecordClinic::class, 'student_medical_record_id');
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'medicine_id');
    }
}