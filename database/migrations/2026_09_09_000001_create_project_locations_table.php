<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Makes a project's location an admin-managed list instead of a fixed enum.
 *
 * The column keeps holding the location *name* rather than a foreign key: every
 * screen, filter and card already reads `projects.location` as text, and a name
 * the client can add is the whole point. The enum has to go first — otherwise
 * MySQL would reject the first location added on the new screen.
 */
return new class extends Migration
{
    /** The enum's members, which seed the new list so nothing changes on day one. */
    private const SEED = ['Gulshan', 'Banani', 'Dhanmondi', 'Uttara', 'Bashundhara', 'Tejgaon'];

    public function up(): void
    {
        Schema::create('project_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120)->unique();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->string('location', 120)->change();
        });

        // Anything already saved on a project has to survive, even if it is not
        // one of the six — the live database is the only copy of that content.
        $names = collect(self::SEED)
            ->merge(DB::table('projects')->distinct()->pluck('location'))
            ->filter()
            ->unique(fn ($name) => mb_strtolower($name))
            ->values();

        $now = now();

        DB::table('project_locations')->insert(
            $names->map(fn ($name, $i) => [
                'name' => $name,
                'is_active' => true,
                'sort_order' => $i + 1,
                'created_at' => $now,
                'updated_at' => $now,
            ])->all()
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('project_locations');

        // The column stays a varchar deliberately: narrowing it back to the enum
        // would throw away every location added since, which is real content.
    }
};
