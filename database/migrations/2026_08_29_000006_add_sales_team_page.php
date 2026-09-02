<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A page for the sales team.
     *
     * Sales staff are people with a name, a role, a photo and a bio — exactly
     * what team_members already holds — so this adds a department to that
     * table rather than a second near-identical model. Our Team lists the
     * management department, the new page lists sales, and one admin screen
     * still edits both.
     *
     * Existing rows default to management, which is what they all are today.
     */
    public function up(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->string('department')->default('management')->after('role');
        });

        $now = now();

        DB::table('page_banners')->updateOrInsert(
            ['page_key' => 'sales-team'],
            [
                'label' => 'Sales Team',
                'eyebrow' => 'Talk To Us',
                'heading' => 'Our Sales Team.',
                'intro' => 'The people who will show you the units, walk you through the payment schedule, and stay with you from first viewing to handover.',
                'image_path' => 'assets/images/hero-3-hospitality.jpg',
                'seo_title' => 'Sales Team | RHL Properties Ltd',
                'seo_description' => 'Meet the sales team at RHL Properties Ltd — the people handling viewings, payment schedules and bookings across every current development.',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        DB::table('cta_blocks')->updateOrInsert(
            ['page_key' => 'sales-team'],
            [
                'label' => 'Sales Team',
                'eyebrow' => 'Continue Exploring',
                'heading' => 'Ready when you are.',
                'section_id' => null,
                'cards' => json_encode([
                    ['title' => 'Browse Developments', 'text' => 'See what is available right now across every ongoing and completed {company} project.', 'btn_label' => 'View projects', 'btn_url' => 'projects.index'],
                    ['title' => 'Arrange a Viewing', 'text' => 'Call {phone} or send us your details and the team will arrange a time to walk you round.', 'btn_label' => 'Contact us', 'btn_url' => 'contact'],
                ]),
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        $this->addMenuLink($now);
    }

    public function down(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->dropColumn('department');
        });

        DB::table('page_banners')->where('page_key', 'sales-team')->delete();
        DB::table('cta_blocks')->where('page_key', 'sales-team')->delete();
        DB::table('menu_links')->where('target', 'sales-team')->delete();
    }

    /** Directly below Our Team in the About dropdown. */
    private function addMenuLink($now): void
    {
        $primaryId = DB::table('menus')->where('key', 'primary')->value('id');

        if (! $primaryId || DB::table('menu_links')->where('target', 'sales-team')->exists()) {
            return;
        }

        $team = DB::table('menu_links')
            ->where('menu_id', $primaryId)
            ->where('target', 'management')
            ->first();

        if (! $team) {
            return;
        }

        DB::table('menu_links')
            ->where('menu_id', $primaryId)
            ->where('parent_id', $team->parent_id)
            ->where('sort_order', '>', $team->sort_order)
            ->increment('sort_order');

        DB::table('menu_links')->insert([
            'menu_id' => $primaryId,
            'parent_id' => $team->parent_id,
            'label' => 'Sales Team',
            'target' => 'sales-team',
            'sort_order' => $team->sort_order + 1,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
};
