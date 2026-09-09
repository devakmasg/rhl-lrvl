<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Drawing room, dining room and balcony on a unit — the three lines a local
 * floor-plan listing carries beyond beds and baths.
 *
 * Strings rather than integers, unlike beds/baths: these are quoted either as
 * a count ("1") or as a dimension ("14' x 16'") depending on the project, and
 * a string holds both. They are display-only values in the units table, so
 * nothing counts or sorts on them.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_units', function (Blueprint $table) {
            $table->string('drawing_rooms', 60)->nullable()->after('baths');
            $table->string('dining_rooms', 60)->nullable()->after('drawing_rooms');
            $table->string('balconies', 60)->nullable()->after('dining_rooms');
        });
    }

    public function down(): void
    {
        Schema::table('project_units', function (Blueprint $table) {
            $table->dropColumn(['drawing_rooms', 'dining_rooms', 'balconies']);
        });
    }
};
