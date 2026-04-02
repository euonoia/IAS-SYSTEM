<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthIncident extends Model
{
    /**
     * Ang mga fields na pinapayagang ma-save sa database.
     * Naka-base ito sa Module 5 requirements.
     */
    protected $fillable = [
        'student_medical_record_id', // Link sa Module 1
        'incident_type',             // Uri ng aksidente (e.g. Fainting, Sprain)
        'description',               // Detalye ng nangyari
        'location',                  // Saan nangyari (e.g. Gym, Room 101)
        'incident_date',             // Petsa at oras
        'first_aid_given',           // Lunas na ibinigay (Module 5 requirement)
        'action_taken',              // Status (e.g. Treated, Referred, Sent Home)
        'reported_by'                // Teacher o Staff na nakasaksi
    ];

    /**
     * Relationship: I-link ang incident sa Student Medical Record (Module 1).
     * Pinapayagan tayo nito na gamitin ang: $incident->student_medical_record->name
     */
    public function student_medical_record()
    {
        return $this->belongsTo(StudentMedicalRecordClinic::class, 'student_medical_record_id');
    }
}