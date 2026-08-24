<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->enum('category', [
                'Construction Update',
                'Handover',
                'Sales',
                'Awards',
                'Community',
                'New Launch',
            ]);
            $table->date('date');
            $table->text('excerpt');
            $table->longText('body')->nullable();
            $table->string('cover_image')->nullable();
            $table->boolean('published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
