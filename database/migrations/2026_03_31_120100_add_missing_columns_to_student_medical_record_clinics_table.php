<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('student_medical_record_clinics')) {
            return;
        }

        Schema::table('student_medical_record_clinics', function (Blueprint $table) {
            if (! Schema::hasColumn('student_medical_record_clinics', 'student_id')) {
                $table->string('student_id')->nullable()->unique();
            }

            if (! Schema::hasColumn('student_medical_record_clinics', 'blood_type')) {
                $table->string('blood_type')->nullable();
            }

            if (! Schema::hasColumn('student_medical_record_clinics', 'allergies')) {
                $table->text('allergies')->nullable();
            }

            if (! Schema::hasColumn('student_medical_record_clinics', 'chronic_illness')) {
                $table->text('chronic_illness')->nullable();
            }

            if (! Schema::hasColumn('student_medical_record_clinics', 'medical_history')) {
                $table->text('medical_history')->nullable();
            }

            if (! Schema::hasColumn('student_medical_record_clinics', 'notes')) {
                $table->text('notes')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('student_medical_record_clinics')) {
            return;
        }

        Schema::table('student_medical_record_clinics', function (Blueprint $table) {
            if (Schema::hasColumn('student_medical_record_clinics', 'student_id')) {
                $table->dropUnique(['student_id']);
            }

            $columns = array_filter([
                Schema::hasColumn('student_medical_record_clinics', 'student_id') ? 'student_id' : null,
                Schema::hasColumn('student_medical_record_clinics', 'blood_type') ? 'blood_type' : null,
                Schema::hasColumn('student_medical_record_clinics', 'allergies') ? 'allergies' : null,
                Schema::hasColumn('student_medical_record_clinics', 'chronic_illness') ? 'chronic_illness' : null,
                Schema::hasColumn('student_medical_record_clinics', 'medical_history') ? 'medical_history' : null,
                Schema::hasColumn('student_medical_record_clinics', 'notes') ? 'notes' : null,
            ]);

            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
