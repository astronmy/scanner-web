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
        Schema::table('table_assignments', function (Blueprint $table) {
            $table->dropUnique(['table_number', 'guest_name']);
        });

        Schema::table('table_assignments', function (Blueprint $table) {
            $table->unique(['event_id', 'table_number', 'guest_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('table_assignments', function (Blueprint $table) {
            $table->dropUnique(['event_id', 'table_number', 'guest_name']);
        });

        Schema::table('table_assignments', function (Blueprint $table) {
            $table->unique(['table_number', 'guest_name']);
        });
    }
};
