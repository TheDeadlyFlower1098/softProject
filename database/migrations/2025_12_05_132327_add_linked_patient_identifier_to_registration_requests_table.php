<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('registration_requests', function (Blueprint $table) {
            // this will store whatever the family typed (patient ID / code)
            $table->string('linked_patient_identifier')->nullable()->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('registration_requests', function (Blueprint $table) {
            $table->dropColumn('linked_patient_identifier');
        });
    }
};

