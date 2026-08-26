<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The last hardcoded strings in the footer: the two column headings that
     * sit above data rather than above a menu, the rights line beside the
     * copyright year, and the credit line in the bottom-right corner.
     *
     * The Explore column's heading is not here — it belongs to that menu, see
     * the menus table.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('footer_contact_heading')->nullable()->after('footer_blurb');
            $table->string('footer_follow_heading')->nullable()->after('footer_contact_heading');
            $table->string('footer_rights')->nullable()->after('footer_follow_heading');
            $table->string('footer_credit')->nullable()->after('footer_rights');
            $table->string('nav_cta_label')->nullable()->after('brand_mark_sub');
        });

        if (Schema::hasTable('settings')) {
            DB::table('settings')->whereNull('footer_contact_heading')->update([
                'footer_contact_heading' => 'Contact',
                'footer_follow_heading' => 'Follow',
                'footer_rights' => 'All Rights Reserved.',
                'footer_credit' => 'Concept design template',
                'nav_cta_label' => 'Enquire',
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'footer_contact_heading', 'footer_follow_heading', 'footer_rights',
                'footer_credit', 'nav_cta_label',
            ]);
        });
    }
};
