<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The homepage intro and the About page intro were the same block stored
     * twice — the heading, both label/blurb pairs and the badge were identical
     * character for character, so the only thing the second copy could do was
     * drift.
     *
     * The homepage now reads the about row for this block, the way the
     * mission/vision teaser and the MD pull-quote already did. These keys on
     * the home row are no longer read by anything, so they are dropped rather
     * than left behind to confuse the next person editing this content.
     *
     * The arrow link under the block stays on the home row, in content.links.
     */
    public function up(): void
    {
        if (! Schema::hasTable('pages')) {
            return;
        }

        $row = DB::table('pages')->where('slug', 'home')->first();

        if (! $row) {
            return;
        }

        $content = json_decode($row->content ?? '', true);

        if (! is_array($content)) {
            return;
        }

        foreach ([
            'intro_eyebrow',
            'intro_heading',
            'intro_since_label',
            'intro_since_text',
            'intro_spectrum_label',
            'intro_spectrum_text',
            'intro_image',
            'intro_badge_number',
            'intro_badge_label',
        ] as $key) {
            unset($content[$key]);
        }

        DB::table('pages')->where('slug', 'home')->update([
            'content' => json_encode($content),
        ]);
    }

    public function down(): void
    {
        // The about row still holds this copy, and the homepage reads it from
        // there — re-seeding a second copy would only recreate the drift.
    }
};
