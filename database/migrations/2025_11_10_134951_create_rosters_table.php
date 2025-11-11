<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRostersTable extends Migration
{
    public function up()
    {
        Schema::create('rosters', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedBigInteger('supervisor_id')->nullable();
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->unsignedBigInteger('caregiver_1')->nullable();
            $table->unsignedBigInteger('caregiver_2')->nullable();
            $table->unsignedBigInteger('caregiver_3')->nullable();
            $table->unsignedBigInteger('caregiver_4')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rosters');
    }
}
