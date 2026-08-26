<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Which director's message the site shows was decided by matching the role
     * column against the literal string 'Managing Director'. That tied a piece
     * of site wiring to editable copy: renaming the role to "Managing Director
     * & CEO" silently emptied the homepage teaser and the MD Message page.
     *
     * An explicit flag says who it is, leaving the role free text.
     */
    public function up(): void
    {
        Schema::table('directors', function (Blueprint $table) {
            $table->boolean('is_managing_director')->default(false)->after('role');
        });

        if (! Schema::hasTable('directors')) {
            return;
        }

        // Carry over whoever the old role match would have found. first() is
        // deliberate — the flag identifies exactly one person.
        $current = DB::table('directors')
            ->where('role', 'Managing Director')
            ->orderBy('order')
            ->first();

        if (! $current) {
            return;
        }

        $update = ['is_managing_director' => true];

        // This row now also fills the large portrait slots on the homepage
        // teaser and the MD Message page, which previously read a separate
        // 800px image off the About row. The seeded director photo is the same
        // picture at thumbnail width, so ask Unsplash for the bigger crop.
        if ($current->photo === self::SEEDED_THUMBNAIL) {
            $update['photo'] = self::SEEDED_PORTRAIT;
        }

        DB::table('directors')->where('id', $current->id)->update($update);
    }

    private const SEEDED_THUMBNAIL = 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=200&q=80';

    private const SEEDED_PORTRAIT = 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=800&q=80';

    public function down(): void
    {
        Schema::table('directors', function (Blueprint $table) {
            $table->dropColumn('is_managing_director');
        });
    }
};
