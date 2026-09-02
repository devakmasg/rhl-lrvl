<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The header logo was an inline SVG typed into nav.blade.php — the one
     * piece of branding an owner cannot change from admin.
     *
     * Two columns rather than one because the header sits over a photo at the
     * top of every page (white text) and turns ivory once scrolled (charcoal
     * text), and the footer is charcoal throughout. A single flat PNG cannot
     * read on both. The second upload is optional and falls back to the first,
     * so a logo that already works on any background needs only one file.
     *
     * The inline SVG stays as the fallback: nothing uploaded means the site
     * renders exactly as it does today.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('logo_path')->nullable()->after('brand_mark_sub');
            $table->string('logo_dark_path')->nullable()->after('logo_path');
            $table->boolean('show_wordmark')->default(true)->after('logo_dark_path');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['logo_path', 'logo_dark_path', 'show_wordmark']);
        });
    }
};
