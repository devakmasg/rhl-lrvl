<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Three more platforms for the footer's "Follow" column, and starter values
 * for all six.
 *
 * The column had never rendered on the live site: the footer hides it when no
 * link is set, and every social column shipped null. Filling them here is what
 * makes it appear — the handles are the client's to correct in Site Settings,
 * they are not verified accounts.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('social_youtube')->nullable()->after('social_facebook');
            $table->string('social_twitter')->nullable()->after('social_youtube');
            $table->string('social_tiktok')->nullable()->after('social_twitter');
        });

        DB::table('settings')->whereNull('social_facebook')->update([
            'social_facebook' => 'https://facebook.com/rhlproperties',
            'social_instagram' => 'https://instagram.com/rhlproperties',
            'social_linkedin' => 'https://linkedin.com/company/rhlproperties',
            'social_youtube' => 'https://youtube.com/@rhlproperties',
        ]);
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['social_youtube', 'social_twitter', 'social_tiktok']);
        });
    }
};
