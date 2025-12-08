<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medicine_checks', function (Blueprint $table) {
            // adjust the "after" column names as needed to match your table
            $table->enum('breakfast', ['taken', 'missed'])
                  ->nullable()
                  ->after('night');

            $table->enum('lunch', ['taken', 'missed'])
                  ->nullable()
                  ->after('breakfast');

            $table->enum('dinner', ['taken', 'missed'])
                  ->nullable()
                  ->after('lunch');
        });
    }

    public function down(): void
    {
        Schema::table('medicine_checks', function (Blueprint $table) {
            $table->dropColumn(['breakfast', 'lunch', 'dinner']);
        });
    }
};
