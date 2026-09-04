<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The browser-tab icon was an inline SVG typed into every layout's <head>
     * — the same "cannot change from admin" gap the logo columns closed. One
     * column is enough here: unlike the header logo, a favicon never sits over
     * a photo or swaps with scroll state, so there is no dark/light variant to
     * manage.
     *
     * The inline SVG stays as the fallback in both layouts: nothing uploaded
     * means the tab icon renders exactly as it does today.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('favicon_path')->nullable()->after('logo_dark_path');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('favicon_path');
        });
    }
};
