<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Every static page repeated the same block — a background image, an
     * eyebrow, an H1, a lead paragraph — plus its own hardcoded SEO title,
     * meta description and og:image. One row per page replaces all of it.
     *
     * Keyed by page_key (the route name) rather than a foreign key, because
     * these pages are routes rather than rows in `pages`.
     */
    public function up(): void
    {
        Schema::create('page_banners', function (Blueprint $table) {
            $table->id();
            $table->string('page_key')->unique();
            $table->string('label');
            $table->string('eyebrow')->nullable();
            $table->string('heading')->nullable();
            $table->text('intro')->nullable();
            $table->string('image_path')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('og_image_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_banners');
    }
};
