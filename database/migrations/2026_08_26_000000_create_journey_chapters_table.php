<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The "Our Journey" chapters on the homepage. Each is either a still or a
     * muted looping video; a video chapter still needs image_path, which the
     * markup renders as the poster before the clip loads.
     */
    public function up(): void
    {
        Schema::create('journey_chapters', function (Blueprint $table) {
            $table->id();
            $table->string('media_type')->default('image');
            $table->string('image_path')->nullable();
            $table->string('video_path')->nullable();
            $table->string('kicker')->nullable();
            $table->string('heading')->nullable();
            $table->text('body')->nullable();
            $table->string('link_label')->nullable();
            $table->string('link_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journey_chapters');
    }
};
