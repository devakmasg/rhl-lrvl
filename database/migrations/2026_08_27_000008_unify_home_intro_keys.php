<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The homepage intro was the odd one out: its heading lived in
     * intro_headline while About and Partners both used intro_heading, and
     * its eyebrow lived in the section-headings registry rather than beside
     * the copy it labels. That split forced a carve-out in HomeSections and
     * a second copy of the same markup in the view.
     *
     * Both keys now match the other pages, so home and About can share one
     * partial while keeping their own separately editable content.
     *
     * intro_image is backfilled too: the view used to paper over an empty
     * value with a hardcoded fallback, and the shared partial does not.
     */
    public function up(): void
    {
        $this->rewriteHomeContent(function (array $content): array {
            // ??= throughout, so a value someone already edited is never lost.
            if (isset($content['intro_headline'])) {
                $content['intro_heading'] ??= $content['intro_headline'];
            }

            unset($content['intro_headline']);

            $storedEyebrow = $content['sections']['story']['eyebrow'] ?? null;
            $content['intro_eyebrow'] ??= $storedEyebrow ?: 'Our Story';
            unset($content['sections']['story']);

            // What the removed Blade fallbacks used to supply.
            $fallbacks = [
                'intro_image' => 'assets/images/hero-1-residential.jpg',
                'intro_since_label' => 'Since 1998',
                'intro_spectrum_label' => 'Full Spectrum',
                'intro_badge_number' => '25+',
                'intro_badge_label' => 'Years of Excellence',
            ];

            foreach ($fallbacks as $key => $fallback) {
                $content[$key] = ($content[$key] ?? '') ?: $fallback;
            }

            return $content;
        });
    }

    public function down(): void
    {
        // Restoring the mismatched key is not useful; the unified one reads
        // correctly on every page that uses it.
    }

    private function rewriteHomeContent(callable $mutate): void
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

        DB::table('pages')->where('slug', 'home')->update([
            'content' => json_encode($mutate($content)),
        ]);
    }
};
