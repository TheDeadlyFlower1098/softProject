<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')
                  ->constrained()           // references prescriptions.id
                  ->onDelete('cascade');    // delete items if prescription is deleted

            $table->string('name');        // e.g. "Amoxicillin"
            $table->string('dosage')->nullable();      // e.g. "500mg"
            $table->string('frequency')->nullable();   // e.g. "2x per day"
            $table->text('instructions')->nullable();  // e.g. "Take with food"

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescription_items');
    }
};