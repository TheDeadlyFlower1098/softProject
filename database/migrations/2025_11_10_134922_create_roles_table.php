<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Admin, Supervisor, Doctor, Caregiver, Patient, Family
            $table->integer('access_level')->default(1); // numeric access level if needed
        });
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
