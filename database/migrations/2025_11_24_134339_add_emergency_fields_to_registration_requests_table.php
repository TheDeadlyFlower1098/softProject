<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
        public function up()
    {
        Schema::table('registration_requests', function (Blueprint $table) {
            $table->string('emergency_contact')->nullable()->after('role');
            $table->string('relation_to_contact')->nullable()->after('emergency_contact');
        });
    }

    public function down()
    {
        Schema::table('registration_requests', function (Blueprint $table) {
            $table->dropColumn(['emergency_contact', 'relation_to_contact']);
        });
    }

};
