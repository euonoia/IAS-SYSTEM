<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('health_incidents', function (Blueprint $table) {
        $table->id();
        // Link sa Module 1 (Sino ang nasaktan/involved)
        $table->foreignId('student_medical_record_id')->constrained('student_medical_record_clinics')->onDelete('cascade');
        
        $table->string('incident_type'); // e.g., Injury, Fainting, Seizure, Sports Accident
        $table->text('description'); // Detalye ng nangyari
        $table->string('location'); // Saan nangyari (e.g., Gym, Classroom)
        $table->dateTime('incident_date'); // Kailan nangyari
        
        $table->text('first_aid_given'); // Anong ginawang lunas (First Aid)
        $table->string('action_taken')->default('Treated'); // Treated, Referred to Hospital, Sent Home
        $table->string('reported_by'); // Pangalan ng teacher o witness na nagreport
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_incidents');
    }
};
