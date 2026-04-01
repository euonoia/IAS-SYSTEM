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
    Schema::create('medicines', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Add medicine
        $table->string('batch_number')->nullable();
        $table->integer('stock_quantity')->default(0); // Update stock
        $table->integer('low_stock_threshold')->default(10); // Low-stock alert
        $table->date('expiration_date'); // Expiration tracking
        $table->text('usage_history')->nullable(); // Medicine usage history
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
