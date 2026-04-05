<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentMedicalRecordClinic extends Model
{
    protected $table = 'student_medical_record_clinics';

    // Fields that can be mass assigned to the database
    protected $fillable = [
        'student_id',
        'name',
        'blood_type',
        'allergies',
        'chronic_illness',
        'medical_history',
        'notes'
    ];
}