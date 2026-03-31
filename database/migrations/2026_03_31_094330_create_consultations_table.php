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
    Schema::create('consultations', function (Blueprint $table) {
        $table->id();
        // Naka-link ito sa Module 1 (Student Medical Records)
        $table->foreignId('student_medical_record_id')->constrained('student_medical_record_clinics')->onDelete('cascade');
        $table->text('symptoms');
        $table->text('diagnosis');
        $table->text('treatment'); // Record treatment
        $table->string('medicines_used')->nullable(); // Attach medicines
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
