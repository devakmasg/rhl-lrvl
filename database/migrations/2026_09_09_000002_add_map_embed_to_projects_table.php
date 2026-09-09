<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A per-project map, pasted from Google, replacing the name+area search the
 * project page guessed with. Nullable on purpose: a project without one keeps
 * falling back to that search, so nothing has to be filled in to stay correct.
 *
 * Text rather than a string column — the pb= embed URLs Google generates run
 * to several hundred characters.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->text('map_embed')->nullable()->after('location');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('map_embed');
        });
    }
};
