<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * The strip renders in the shared layout rather than on one page, so its
 * eyebrow, heading and on/off switch belong with the other site-wide copy in
 * settings — same reasoning as the footer headings.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('partners_eyebrow')->nullable()->after('footer_credit');
            $table->string('partners_heading')->nullable()->after('partners_eyebrow');
            $table->boolean('show_partners')->default(true)->after('partners_heading');
        });

        DB::table('settings')->whereNull('partners_eyebrow')->update([
            'partners_eyebrow' => 'Trusted Partners',
            'partners_heading' => 'The names we build alongside',
        ]);
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['partners_eyebrow', 'partners_heading', 'show_partners']);
        });
    }
};
