<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'separator')) {
                $table->string('separator')->nullable()->after('autostart');
            }

            if (!Schema::hasColumn('events', 'check_duplicity')) {
                $table->boolean('check_duplicity')->default(0)->after('separator');
            }
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'check_duplicity')) {
                $table->dropColumn('check_duplicity');
            }

            if (Schema::hasColumn('events', 'separator')) {
                $table->dropColumn('separator');
            }
        });
    }
};

