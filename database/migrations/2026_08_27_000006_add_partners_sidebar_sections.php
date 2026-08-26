<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Four sidebar headings on the Partners page. The paragraphs under them
     * were already editable through the Partners content editor, but the
     * headings themselves were not — an easy set to miss, since the page
     * otherwise looks fully wired up.
     */
    public function up(): void
    {
        if (! Schema::hasTable('page_sections')) {
            return;
        }

        $now = now();
        $rows = [
            ['section_key' => 'aside_desk', 'label' => 'Sidebar — Phone & Email', 'heading' => 'Partnership desk', 'link_label' => null],
            ['section_key' => 'aside_ready', 'label' => 'Sidebar — What To Have Ready', 'heading' => 'What to have ready', 'link_label' => null],
            ['section_key' => 'aside_timeline', 'label' => 'Sidebar — Typical Timeline', 'heading' => 'Typical timeline', 'link_label' => null],
            ['section_key' => 'aside_work', 'label' => 'Sidebar — See The Work', 'heading' => 'See the work first', 'link_label' => 'Browse the portfolio'],
        ];

        foreach ($rows as $i => $row) {
            DB::table('page_sections')->updateOrInsert(
                ['page_key' => 'partners', 'section_key' => $row['section_key']],
                [
                    'page_label' => 'Partners',
                    'label' => $row['label'],
                    'heading' => $row['heading'],
                    'link_label' => $row['link_label'],
                    'sort_order' => $i + 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('page_sections')) {
            DB::table('page_sections')->where('page_key', 'partners')->delete();
        }
    }
};
