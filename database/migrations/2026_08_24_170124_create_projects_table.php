<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->enum('type', ['Residential', 'Commercial', 'Mixed-Use']);
            $table->enum('location', ['Gulshan', 'Banani', 'Dhanmondi', 'Uttara', 'Bashundhara', 'Tejgaon']);
            $table->enum('status', ['Upcoming', 'Ongoing', 'Completed'])->default('Ongoing');
            $table->unsignedTinyInteger('progress')->nullable();
            $table->string('hero_image')->nullable();
            $table->text('summary');
            $table->longText('body')->nullable();
            $table->json('facts')->nullable();
            $table->json('features')->nullable();
            $table->boolean('published')->default(true);
            $table->boolean('featured')->default(false);
            $table->string('brochure_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
