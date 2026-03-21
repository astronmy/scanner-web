<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('table_assignments', function (Blueprint $table) {
            $table->text('observations')->nullable()->after('guest_name');
        });
    }

    public function down(): void
    {
        Schema::table('table_assignments', function (Blueprint $table) {
            $table->dropColumn('observations');
        });
    }
};

