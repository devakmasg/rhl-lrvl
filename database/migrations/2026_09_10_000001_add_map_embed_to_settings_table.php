<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The office map on the homepage and the Contact page, pasted from Google
 * rather than searched for — the same field a project now has.
 *
 * map_query stays exactly as it is and keeps working: it is the fallback when
 * nothing is pasted here, so an install that never touches this screen looks
 * unchanged.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->text('map_embed')->nullable()->after('map_query');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('map_embed');
        });
    }
};
