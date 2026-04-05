<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthIncident extends Model
{
    /**
     * The fields that are allowed to be saved in the database.
     * Based on Module 5 requirements.
     */
    protected $fillable = [
        'student_medical_record_id', // Link to Module 1
        'incident_type',             // Type of incident (e.g. Fainting, Sprain)
        'description',               // Details of what happened
        'location',                  // Where it happened (e.g. Gym, Room 101)
        'incident_date',             // Date and time
        'first_aid_given',           // First aid given (Module 5 requirement)
        'action_taken',              // Status (e.g. Treated, Referred, Sent Home)
        'reported_by'                // Teacher or Staff who witnessed
    ];

    /**
     * Relationship: Link the incident to the Student Medical Record (Module 1).
     * This allows us to use: $incident->student_medical_record->name
     */
    public function student_medical_record()
    {
        return $this->belongsTo(StudentMedicalRecordClinic::class, 'student_medical_record_id');
    }
}