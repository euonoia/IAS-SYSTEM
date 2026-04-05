<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalClearance extends Model
{
    protected $fillable = [
    'student_medical_record_id', 
    'purpose', 
    'status', 
    'remarks', 
    'issued_date', 
    'clearance_number'
];

public function student_medical_record()
{
    // Link the clearance to the Student Medical Record (Module 1).
    return $this->belongsTo(StudentMedicalRecordClinic::class, 'student_medical_record_id');
}
}
