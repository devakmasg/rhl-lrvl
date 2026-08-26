<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The "Our Mission" / "Our Vision" labels lived in two places: the home
     * page row (for the teaser) and page_sections (for the Mission & Vision
     * page). The statements themselves already lived once, on the about row —
     * so the labels were the only part of that section that could drift.
     *
     * Both now sit beside the copy they label, on the about row, and both
     * pages read them from there.
     */
    public function up(): void
    {
        $this->setAboutEyebrows(
            $this->existingEyebrow('mission') ?: 'Our Mission',
            $this->existingEyebrow('vision') ?: 'Our Vision'
        );

        // Drop the two now-unread copies.
        if (Schema::hasTable('page_sections')) {
            DB::table('page_sections')
                ->where('page_key', 'mission-vision')
                ->whereIn('section_key', ['mission', 'vision'])
                ->delete();
        }

        $this->forgetHomeSections(['mission_teaser', 'vision_teaser']);
    }

    public function down(): void
    {
        // Restoring the duplicates is not useful; leave the single copy in
        // place and let the page_sections seed re-add rows if ever needed.
    }

    /** Whatever the Mission & Vision page was showing, so an edited label survives. */
    private function existingEyebrow(string $sectionKey): ?string
    {
        if (! Schema::hasTable('page_sections')) {
            return null;
        }

        return DB::table('page_sections')
            ->where('page_key', 'mission-vision')
            ->where('section_key', $sectionKey)
            ->value('eyebrow');
    }

    private function setAboutEyebrows(string $mission, string $vision): void
    {
        if (! Schema::hasTable('pages')) {
            return;
        }

        $row = DB::table('pages')->where('slug', 'about')->first();

        if (! $row) {
            return;
        }

        $content = json_decode($row->content ?? '', true);

        if (! is_array($content)) {
            return;
        }

        $content['mission_eyebrow'] ??= $mission;
        $content['vision_eyebrow'] ??= $vision;

        DB::table('pages')->where('slug', 'about')->update([
            'content' => json_encode($content),
        ]);
    }

    /** @param list<string> $keys */
    private function forgetHomeSections(array $keys): void
    {
        if (! Schema::hasTable('pages')) {
            return;
        }

        $row = DB::table('pages')->where('slug', 'home')->first();

        if (! $row) {
            return;
        }

        $content = json_decode($row->content ?? '', true);

        if (! is_array($content) || ! isset($content['sections'])) {
            return;
        }

        foreach ($keys as $key) {
            unset($content['sections'][$key]);
        }

        DB::table('pages')->where('slug', 'home')->update([
            'content' => json_encode($content),
        ]);
    }
};
