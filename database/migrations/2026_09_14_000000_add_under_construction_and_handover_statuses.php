<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Two more points in a project's life than the original three allowed for.
     *
     * Written through the Blueprint rather than a raw MySQL MODIFY so the test
     * suite, which runs on SQLite, can migrate it too. The default stays
     * 'Ongoing' — still what a newly created project starts on.
     *
     * The list is spelled out rather than read from Project::STATUSES so this
     * migration keeps describing the schema as of today, even if that constant
     * grows again later.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->enum('status', ['Upcoming', 'Ongoing', 'Under Construction', 'Handover', 'Completed'])
                ->default('Ongoing')
                ->change();
        });
    }

    public function down(): void
    {
        // Anything sitting on one of the new values has to be moved onto a
        // surviving one first, or narrowing the column would reject it.
        DB::table('projects')
            ->whereIn('status', ['Under Construction', 'Handover'])
            ->update(['status' => 'Ongoing']);

        Schema::table('projects', function (Blueprint $table) {
            $table->enum('status', ['Upcoming', 'Ongoing', 'Completed'])
                ->default('Ongoing')
                ->change();
        });
    }
};
