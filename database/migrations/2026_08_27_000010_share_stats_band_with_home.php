<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The statistics band was the same six figures stored on two rows — the
     * homepage's and the About page's were identical value for value, so the
     * only thing the second copy could do was drift.
     *
     * Both pages now render it from the about row, the way the intro block
     * already does. The band's framing moves across with the numbers: the
     * homepage's eyebrow and heading came from the section registry and its
     * background photo from the home row, and all three are now content keys
     * on about so one editor owns the whole band.
     */
    public function up(): void
    {
        if (! Schema::hasTable('pages')) {
            return;
        }

        $home = $this->content('home');
        $about = $this->content('about');

        if ($home === null || $about === null) {
            return;
        }

        // Whatever the homepage was showing wins, so an edited label survives.
        $about['stats_eyebrow'] = ($home['sections']['stats']['eyebrow'] ?? '') ?: 'Key Statistics';
        $about['stats_heading'] = ($home['sections']['stats']['heading'] ?? '') ?: 'Our impact, in numbers.';
        $about['stats_background'] = ($about['stats_background'] ?? null) ?: ($home['stats_background'] ?? null);

        // The two arrays are identical; keep the homepage's, which is the one
        // the section registry framed.
        if (! empty($home['stats'])) {
            $about['stats'] = $home['stats'];
        }

        $this->put('about', $about);

        // Nothing reads these on the home row any more.
        unset($home['stats'], $home['stats_background'], $home['sections']['stats']);

        $this->put('home', $home);
    }

    public function down(): void
    {
        // The about row still holds the band and both pages read it from
        // there — re-seeding a second copy would only recreate the drift.
    }

    private function content(string $slug): ?array
    {
        $row = DB::table('pages')->where('slug', $slug)->first();

        if (! $row) {
            return null;
        }

        $content = json_decode($row->content ?? '', true);

        return is_array($content) ? $content : null;
    }

    private function put(string $slug, array $content): void
    {
        DB::table('pages')->where('slug', $slug)->update([
            'content' => json_encode($content),
        ]);
    }
};
