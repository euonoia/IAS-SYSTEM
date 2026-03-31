<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentMedicalRecordClinic extends Model
{
    protected $table = 'student_medical_record_clinics';

    // Idagdag ang mga columns na ito para payagan ang pag-save
    protected $fillable = [
        'student_id',
        'blood_type',
        'allergies',
        'chronic_illness',
        'medical_history',
        'notes'
    ];
}