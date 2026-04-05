<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Add medicine relationship and quantity tracking
     */
    public function up(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            // Add foreign key for medicine
            $table->foreignId('medicine_id')->nullable()->after('treatment')->references('id')->on('medicines')->onDelete('set null');
            
            // Add quantity used tracking
            $table->decimal('quantity_used', 8, 2)->nullable()->after('medicine_id');
            
            // Drop old medicines_used column
            $table->dropColumn('medicines_used');
        });
    }

    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            // Restore old medicines_used column
            $table->string('medicines_used')->nullable();
            
            // Drop new columns
            $table->dropForeignKeyConstraints();
            $table->dropColumn('medicine_id');
            $table->dropColumn('quantity_used');
        });
    }
};
