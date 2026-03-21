<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('message_not_found')
                ->nullable()
                ->after('new_button_enabled');
            $table->unsignedTinyInteger('scan_type')
                ->default(1)
                ->after('message_not_found');
        });

        \Illuminate\Support\Facades\DB::table('events')->update(['scan_type' => 1]);
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('scan_type');
            $table->dropColumn('message_not_found');
        });
    }
};

