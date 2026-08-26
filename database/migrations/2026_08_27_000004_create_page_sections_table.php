<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Section headings for the pages that have no content row of their own.
     *
     * The homepage and About keep theirs inside pages.content, because those
     * two also carry a lot of other copy that is edited alongside them. Every
     * other page had its headings typed into Blade, and several of them — the
     * project and news detail pages — have no single content row they could
     * belong to, since one template serves every record.
     *
     * Keyed page_key + section_key, like page_banners and cta_blocks, so one
     * admin screen edits all of them and a view composer binds them by route.
     */
    public function up(): void
    {
        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            $table->string('page_key');
            $table->string('section_key');
            $table->string('page_label');
            $table->string('label');
            $table->string('eyebrow')->nullable();
            $table->string('heading')->nullable();
            $table->text('body')->nullable();
            $table->string('link_label')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['page_key', 'section_key']);
        });

        $now = now();
        $order = [];

        foreach ($this->seedRows() as $row) {
            $order[$row['page_key']] = ($order[$row['page_key']] ?? 0) + 1;

            DB::table('page_sections')->insert($row + [
                'sort_order' => $order[$row['page_key']],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('page_sections');
    }

    /** Exactly the copy the views hardcoded. */
    private function seedRows(): array
    {
        $rows = [];

        $add = function (string $page, string $pageLabel, string $key, string $label, array $fields) use (&$rows) {
            $rows[] = [
                'page_key' => $page,
                'page_label' => $pageLabel,
                'section_key' => $key,
                'label' => $label,
                'eyebrow' => $fields['eyebrow'] ?? null,
                'heading' => $fields['heading'] ?? null,
                'body' => $fields['body'] ?? null,
                'link_label' => $fields['link_label'] ?? null,
            ];
        };

        $add('achievements', 'Achievements', 'awards', 'Awards Section', [
            'eyebrow' => 'Awards & Recognition',
            'heading' => 'Industry recognition, earned project by project.',
        ]);
        $add('achievements', 'Achievements', 'certifications', 'Certifications Section', [
            'eyebrow' => 'Certifications & Memberships',
            'heading' => 'Standing behind our approvals.',
        ]);

        $add('contact', 'Contact', 'form', 'Enquiry Form', [
            'eyebrow' => 'Send an enquiry',
            'heading' => "Tell us what you're looking for.",
            'body' => 'The more you can tell us up front, the more useful our first reply will be. Everything marked with an asterisk is required.',
        ]);
        $add('contact', 'Contact', 'talk', 'Sidebar — Phone & Email', ['heading' => 'Talk to us']);
        $add('contact', 'Contact', 'office', 'Sidebar — Address', ['heading' => 'Head office']);
        $add('contact', 'Contact', 'hours', 'Sidebar — Opening Hours', ['heading' => 'Office hours']);
        $add('contact', 'Contact', 'land', 'Sidebar — Partnering', [
            'heading' => 'Land & investment',
            'body' => 'Proposing a site or looking at returns? The partnership terms and process are set out in full.',
            'link_label' => 'See how partnering works',
        ]);
        $add('contact', 'Contact', 'map', 'Map Section', [
            'eyebrow' => 'Find Us',
            'heading' => 'Our head office in Gulshan.',
        ]);

        $add('projects', 'Projects Listing', 'portfolio', 'Filter Bar', [
            'eyebrow' => 'The Portfolio',
            'heading' => 'Every development, filtered your way.',
        ]);
        $add('projects', 'Projects Listing', 'empty', 'No-Results Message', [
            'body' => 'No developments match these filters.',
            'link_label' => 'Clear filters',
        ]);

        $add('project_detail', 'Project Page', 'progress', 'Construction Progress', ['heading' => 'Construction progress']);
        $add('project_detail', 'Project Page', 'amenities', 'Amenities List', ['heading' => 'Amenities']);
        $add('project_detail', 'Project Page', 'features', 'Features List', ['heading' => 'Features']);
        $add('project_detail', 'Project Page', 'units', 'Unit Information', [
            'eyebrow' => 'Unit Information',
            'heading' => "What's on offer.",
        ]);
        $add('project_detail', 'Project Page', 'gallery', 'Gallery', [
            'eyebrow' => 'Gallery',
            'heading' => 'A closer look.',
        ]);
        $add('project_detail', 'Project Page', 'floorplans', 'Floor Plans', [
            'eyebrow' => 'Floor Plans',
            'heading' => 'Layouts at a glance.',
        ]);
        $add('project_detail', 'Project Page', 'location', 'Location Map', [
            'eyebrow' => 'Location',
            'heading' => 'Find us on the map.',
        ]);
        $add('project_detail', 'Project Page', 'enquire', 'Enquiry Form', [
            'eyebrow' => 'Enquire',
            'heading' => 'Ask us about this development.',
        ]);
        $add('project_detail', 'Project Page', 'related', 'Related Projects', [
            'eyebrow' => 'Related',
            'heading' => 'Others you may want to see.',
        ]);

        $add('news_detail', 'News Article', 'back', 'Back Link', ['link_label' => 'Back to all news']);
        $add('news_detail', 'News Article', 'related', 'Related Articles', [
            'eyebrow' => 'Related',
            'heading' => 'More from {company}.',
        ]);

        $add('mission-vision', 'Mission & Vision', 'mission', 'Mission Card', ['eyebrow' => 'Our Mission']);
        $add('mission-vision', 'Mission & Vision', 'vision', 'Vision Card', ['eyebrow' => 'Our Vision']);
        $add('mission-vision', 'Mission & Vision', 'values', 'Core Values', [
            'eyebrow' => 'Core Values',
            'heading' => 'What every handover is measured against.',
        ]);

        return $rows;
    }
};
