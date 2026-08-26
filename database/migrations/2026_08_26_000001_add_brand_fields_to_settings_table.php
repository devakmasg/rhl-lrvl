<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The company name was typed into 25 places across 13 Blade files, in three
     * different forms: the full legal name, a shorter prose form, and the split
     * wordmark the nav renders. All three become editable here so a rename is a
     * settings change instead of a find-and-replace.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('site_name')->nullable()->after('id');
            $table->string('site_short_name')->nullable()->after('site_name');
            $table->string('brand_mark')->nullable()->after('site_short_name');
            $table->string('brand_mark_sub')->nullable()->after('brand_mark');
            $table->text('meta_description')->nullable()->after('footer_blurb');
        });

        // Backfill with exactly what the views hardcoded, so the live site
        // reads identically before anyone opens the settings screen.
        if (Schema::hasTable('settings')) {
            DB::table('settings')->whereNull('site_name')->update([
                'site_name' => 'RHL Properties Ltd',
                'site_short_name' => 'RHL Properties',
                'brand_mark' => 'RHL',
                'brand_mark_sub' => 'PROPERTIES LTD',
                'meta_description' => 'RHL Properties Ltd — a diversified real estate & investment group across residential, commercial and hospitality developments.',
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'site_name', 'site_short_name', 'brand_mark', 'brand_mark_sub', 'meta_description',
            ]);
        });
    }
};
