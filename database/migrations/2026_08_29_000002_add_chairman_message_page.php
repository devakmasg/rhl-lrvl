<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The Chairman's Message page, wired the same way the MD's is: a banner
     * row keyed by route name, a closing CTA row, a link in the About
     * dropdown, and its writing on the About page content row.
     *
     * Everything here is inserted only if it is missing, so re-running against
     * a database that already has the page is a no-op rather than a duplicate.
     */
    public function up(): void
    {
        $now = now();

        DB::table('page_banners')->updateOrInsert(
            ['page_key' => 'chairman-message'],
            [
                'label' => "Chairman's Message",
                'eyebrow' => 'Leadership',
                'heading' => 'A message from our Chairman.',
                'intro' => null,
                'image_path' => 'assets/images/hero-5-business.jpg',
                'seo_title' => "Chairman's Message | RHL Properties Ltd",
                'seo_description' => "A message from the Chairman of RHL Properties Ltd, on the values behind every development the company puts its name to.",
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        DB::table('cta_blocks')->updateOrInsert(
            ['page_key' => 'chairman-message'],
            [
                'label' => "Chairman's Message",
                'eyebrow' => 'Continue Exploring',
                'heading' => 'See the team carrying this forward.',
                'section_id' => null,
                'cards' => json_encode([
                    ['title' => "Managing Director's Message", 'text' => "Read {md_name}'s message on how {company} approaches every project.", 'btn_label' => 'Read the message', 'btn_url' => 'md-message'],
                    ['title' => 'Board of Directors', 'text' => 'The board overseeing strategy, governance and capital discipline at {company}.', 'btn_label' => 'Meet the board', 'btn_url' => 'directors'],
                ]),
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        $this->addMenuLink($now);
        $this->addAboutContent();
    }

    public function down(): void
    {
        DB::table('page_banners')->where('page_key', 'chairman-message')->delete();
        DB::table('cta_blocks')->where('page_key', 'chairman-message')->delete();
        DB::table('menu_links')->where('target', 'chairman-message')->delete();

        $this->removeAboutContent();
    }

    /**
     * Sits directly above the MD's message in the About dropdown, so the two
     * leadership messages read in the order a visitor expects. Siblings below
     * it shuffle down by one.
     */
    private function addMenuLink($now): void
    {
        $primaryId = DB::table('menus')->where('key', 'primary')->value('id');

        if (! $primaryId || DB::table('menu_links')->where('target', 'chairman-message')->exists()) {
            return;
        }

        $md = DB::table('menu_links')
            ->where('menu_id', $primaryId)
            ->where('target', 'md-message')
            ->first();

        if (! $md) {
            return;
        }

        DB::table('menu_links')
            ->where('menu_id', $primaryId)
            ->where('parent_id', $md->parent_id)
            ->where('sort_order', '>=', $md->sort_order)
            ->increment('sort_order');

        DB::table('menu_links')->insert([
            'menu_id' => $primaryId,
            'parent_id' => $md->parent_id,
            'label' => "Chairman's Message",
            'target' => 'chairman-message',
            'sort_order' => $md->sort_order,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    /**
     * The chairman's writing lives on the About row beside the MD's, because
     * one admin screen edits both. Existing copy is left alone — only the keys
     * that were never there are added.
     */
    private function addAboutContent(): void
    {
        $page = DB::table('pages')->where('slug', 'about')->first();

        if (! $page) {
            return;
        }

        $content = json_decode($page->content ?? '[]', true) ?: [];

        $content['chairman_quote'] ??= 'A company is judged by what it hands over, not by what it announces.';
        $content['chairman_message'] ??= [
            "Real estate in this country has never been short of ambition. What it has been short of is patience — the willingness to secure the land properly, price the work honestly, and let a building take the time a building takes.",
            "That patience is what the board asks of every team at RHL Properties. We would rather turn down a plot with a clouded title than explain the consequences to a family five years later. We would rather hold a launch back a quarter than move a handover date once it has been promised.",
            "To our residents, our landowning partners and our investors: thank you for the trust. It is the only asset in this business that cannot be bought, and we intend to keep earning it.",
        ];

        // The About page's "Explore Further" cards are a fixed list in a fixed
        // order — the new card slots in beside the other leadership pages
        // rather than at the end.
        $quicklinks = $content['quicklinks'] ?? [];

        if (! collect($quicklinks)->contains(fn ($card) => ($card['title'] ?? '') === "Chairman's Message")) {
            array_splice($quicklinks, 1, 0, [[
                'title' => "Chairman's Message",
                'desc' => 'A word from our Chairman on the values behind every RHL development.',
            ]]);

            $content['quicklinks'] = $quicklinks;
        }

        DB::table('pages')->where('id', $page->id)->update(['content' => json_encode($content)]);
    }

    private function removeAboutContent(): void
    {
        $page = DB::table('pages')->where('slug', 'about')->first();

        if (! $page) {
            return;
        }

        $content = json_decode($page->content ?? '[]', true) ?: [];

        unset($content['chairman_quote'], $content['chairman_message']);

        $content['quicklinks'] = array_values(array_filter(
            $content['quicklinks'] ?? [],
            fn ($card) => ($card['title'] ?? '') !== "Chairman's Message"
        ));

        DB::table('pages')->where('id', $page->id)->update(['content' => json_encode($content)]);
    }
};
