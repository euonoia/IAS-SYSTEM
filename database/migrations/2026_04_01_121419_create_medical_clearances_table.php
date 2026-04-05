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
    Schema::create('medical_clearances', function (Blueprint $table) {
        $table->id();
        // Link sa Module 1 (Patient Record)
        $table->foreignId('student_medical_record_id')->constrained('student_medical_record_clinics')->onDelete('cascade');
        
        $table->string('purpose'); // Enrollment, OJT, PE, etc.
        $table->string('status')->default('Pending'); // Pending, Approved, Released
        $table->text('remarks')->nullable(); // Additional medical notes
        $table->date('issued_date')->nullable();
        $table->string('clearance_number')->unique(); // Unique code para sa printing
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_clearances');
    }
};
