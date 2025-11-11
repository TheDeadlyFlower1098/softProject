<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrationRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('registration_requests', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->date('dob')->nullable();
            $table->string('role');
            $table->json('meta')->nullable(); // patient-specific extra fields
            $table->boolean('approved')->default(false);
            $table->unsignedBigInteger('processed_by')->nullable(); // admin id
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('registration_requests');
    }
}
