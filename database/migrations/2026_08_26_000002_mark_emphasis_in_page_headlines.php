<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The intro headlines used to be italicised by a str_replace() over the
     * literal words "trust" and "last" in the view. That is now driven by
     * *asterisk* markers in the copy itself, so the stored headlines need the
     * markers added or the emphasis disappears on the next page load.
     *
     * Targeted at the seeded wording only — a headline the admin has already
     * rewritten is left exactly as they typed it.
     */
    private const SEEDED = 'A legacy built on trust, developments built to last.';

    private const MARKED = 'A legacy built on *trust*, developments built to *last*.';

    private const KEYS = [
        'home' => 'intro_headline',
        'about' => 'intro_heading',
    ];

    public function up(): void
    {
        $this->rewrite(self::SEEDED, self::MARKED);
    }

    public function down(): void
    {
        $this->rewrite(self::MARKED, self::SEEDED);
    }

    private function rewrite(string $from, string $to): void
    {
        if (! Schema::hasTable('pages')) {
            return;
        }

        foreach (self::KEYS as $slug => $key) {
            $row = DB::table('pages')->where('slug', $slug)->first();

            if (! $row) {
                continue;
            }

            $content = json_decode($row->content ?? '', true);

            if (! is_array($content) || ($content[$key] ?? null) !== $from) {
                continue;
            }

            $content[$key] = $to;

            DB::table('pages')->where('slug', $slug)->update([
                'content' => json_encode($content),
            ]);
        }
    }
};
