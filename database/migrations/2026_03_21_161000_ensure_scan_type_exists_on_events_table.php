<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('events', 'scan_type')) {
            Schema::table('events', function (Blueprint $table) {
                $table->unsignedTinyInteger('scan_type')->default(1);
            });
        }

        DB::table('events')->whereNull('scan_type')->update(['scan_type' => 1]);
    }

    public function down(): void
    {
        if (Schema::hasColumn('events', 'scan_type')) {
            Schema::table('events', function (Blueprint $table) {
                $table->dropColumn('scan_type');
            });
        }
    }
};

