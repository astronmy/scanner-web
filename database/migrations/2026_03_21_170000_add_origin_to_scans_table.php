<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('scans', 'origin')) {
            Schema::table('scans', function (Blueprint $table) {
                $table->string('origin', 20)->default('AUTOMATIC')->after('event_id');
            });
        }

        DB::table('scans')->whereNull('origin')->update(['origin' => 'AUTOMATIC']);
    }

    public function down(): void
    {
        if (Schema::hasColumn('scans', 'origin')) {
            Schema::table('scans', function (Blueprint $table) {
                $table->dropColumn('origin');
            });
        }
    }
};

