<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->text('footer_blurb')->nullable()->after('map_query');
        });

        // Seed with the copy the footer previously hardcoded, so the live
        // site reads identically before anyone opens the settings screen.
        Schema::hasTable('settings') && \Illuminate\Support\Facades\DB::table('settings')
            ->whereNull('footer_blurb')
            ->update([
                'footer_blurb' => 'A diversified real estate and investment group building landmark residential, commercial and hospitality developments since 1998.',
            ]);
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('footer_blurb');
        });
    }
};
