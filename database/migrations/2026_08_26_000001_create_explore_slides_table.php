<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The "Step inside our developments" slider. Like the journey chapters,
     * each slide is a still or a muted looping clip — but these carry the
     * category / title / location caption instead of a story block.
     *
     * project_id is optional: when set, the caption follows that project so
     * renaming it in one place updates the slider too. Left null, the three
     * caption fields are typed in directly.
     */
    public function up(): void
    {
        Schema::create('explore_slides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->string('media_type')->default('image');
            $table->string('image_path')->nullable();
            $table->string('video_path')->nullable();
            $table->string('category')->nullable();
            $table->string('title')->nullable();
            $table->string('location')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('explore_slides');
    }
};
