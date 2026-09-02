<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * "Board of Directors" becomes "Our Leaders", "Management Team" becomes
     * "Our Team".
     *
     * These two names are written into four different tables — the menu, the
     * page banners, the closing CTA cards and the About page's quick links —
     * because each is independently editable. Renaming by hand in admin means
     * four screens and a good chance of missing one, so it is done here.
     *
     * The URLs (/directors, /management) deliberately do not change: they are
     * already linked and indexed, and a rename would cost that for nothing a
     * visitor sees.
     */
    private const RENAMES = [
        'Board of Directors' => 'Our Leaders',
        'Management Team' => 'Our Team',
    ];

    public function up(): void
    {
        $this->applyRenames(self::RENAMES);

        DB::table('page_banners')->where('page_key', 'directors')->update([
            'heading' => 'Our Leaders.',
            'seo_title' => 'Our Leaders | RHL Properties Ltd',
            'seo_description' => 'Meet the leaders of RHL Properties Ltd, overseeing strategy, governance and capital discipline.',
        ]);

        DB::table('page_banners')->where('page_key', 'management')->update([
            'heading' => 'Our Team.',
            'seo_title' => 'Our Team | RHL Properties Ltd',
            'seo_description' => 'Meet the team at RHL Properties Ltd running construction, sales, finance and after-handover support.',
        ]);
    }

    public function down(): void
    {
        $this->applyRenames(array_flip(self::RENAMES));

        DB::table('page_banners')->where('page_key', 'directors')->update([
            'heading' => 'Board of Directors.',
            'seo_title' => 'Board of Directors | RHL Properties Ltd',
            'seo_description' => 'Meet the Board of Directors of RHL Properties Ltd, overseeing strategy, governance and capital discipline.',
        ]);

        DB::table('page_banners')->where('page_key', 'management')->update([
            'heading' => 'Management Team.',
            'seo_title' => 'Management Team | RHL Properties Ltd',
            'seo_description' => 'Meet the Management Team of RHL Properties Ltd running construction, sales, finance and after-handover support.',
        ]);
    }

    /**
     * @param  array<string, string>  $renames  old name => new name
     */
    private function applyRenames(array $renames): void
    {
        foreach ($renames as $from => $to) {
            DB::table('menu_links')->where('label', $from)->update(['label' => $to]);
            DB::table('page_banners')->where('label', $from)->update(['label' => $to]);
            DB::table('cta_blocks')->where('label', $from)->update(['label' => $to]);
        }

        $this->renameInCtaCards($renames);
        $this->renameInAboutQuicklinks($renames);
    }

    /**
     * Card titles live inside a JSON column, so they are rewritten row by row
     * rather than with a SQL replace — which keeps this working the same on
     * MySQL and on the SQLite used by the tests.
     */
    private function renameInCtaCards(array $renames): void
    {
        foreach (DB::table('cta_blocks')->get() as $block) {
            $cards = json_decode($block->cards ?? '[]', true) ?: [];
            $changed = false;

            foreach ($cards as $i => $card) {
                $title = $card['title'] ?? '';

                if (isset($renames[$title])) {
                    $cards[$i]['title'] = $renames[$title];
                    $changed = true;
                }
            }

            if ($changed) {
                DB::table('cta_blocks')->where('id', $block->id)->update(['cards' => json_encode($cards)]);
            }
        }
    }

    private function renameInAboutQuicklinks(array $renames): void
    {
        $page = DB::table('pages')->where('slug', 'about')->first();

        if (! $page) {
            return;
        }

        $content = json_decode($page->content ?? '[]', true) ?: [];
        $changed = false;

        foreach ($content['quicklinks'] ?? [] as $i => $card) {
            $title = $card['title'] ?? '';

            if (isset($renames[$title])) {
                $content['quicklinks'][$i]['title'] = $renames[$title];
                $changed = true;
            }
        }

        if ($changed) {
            DB::table('pages')->where('id', $page->id)->update(['content' => json_encode($content)]);
        }
    }
};
