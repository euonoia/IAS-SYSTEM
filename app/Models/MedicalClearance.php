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
    // Siguraduhin na 'student_medical_record_id' ang foreign key sa table mo
    return $this->belongsTo(StudentMedicalRecordClinic::class, 'student_medical_record_id');
}
}
