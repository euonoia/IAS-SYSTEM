<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_medical_record_clinics', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->unique();
            $table->string('blood_type')->nullable();
            $table->text('allergies')->nullable();
            $table->text('chronic_illness')->nullable();
            $table->text('medical_history')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_medical_record_clinics');
    }
};