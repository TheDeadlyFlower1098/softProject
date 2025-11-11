<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicineChecksTable extends Migration
{
    public function up()
    {
        Schema::create('medicine_checks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('caregiver_id');
            $table->unsignedBigInteger('patient_id');
            $table->date('date');
            $table->boolean('morning')->default(false);
            $table->boolean('afternoon')->default(false);
            $table->boolean('night')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('medicine_checks');
    }
}
