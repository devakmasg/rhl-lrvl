<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The header menu was written twice in nav.blade.php — once for the
     * desktop bar and once for the mobile drawer — so every label existed in
     * duplicate and renaming one meant remembering the other. The footer's
     * "Explore" column was a third hardcoded list.
     *
     * One row per link, rendered by both. Targets are stored as route names
     * rather than URLs (see MenuLink::url) so nothing breaks when the domain
     * changes, and the admin cannot point a menu item at a page that does not
     * exist — the controller validates the name on save.
     */
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('label');
            $table->string('heading')->nullable();
            $table->timestamps();
        });

        Schema::create('menu_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('menu_links')->cascadeOnDelete();
            $table->string('label');
            $table->string('target');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $this->seed();
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_links');
        Schema::dropIfExists('menus');
    }

    /** Exactly the links the two views hardcoded, in the same order. */
    private function seed(): void
    {
        $now = now();

        $menus = [
            ['key' => 'primary', 'label' => 'Header Menu', 'heading' => null],
            ['key' => 'footer_explore', 'label' => 'Footer — Explore Column', 'heading' => 'Explore'],
        ];

        foreach ($menus as $menu) {
            DB::table('menus')->insert($menu + ['created_at' => $now, 'updated_at' => $now]);
        }

        $primaryId = DB::table('menus')->where('key', 'primary')->value('id');
        $footerId = DB::table('menus')->where('key', 'footer_explore')->value('id');

        // Top level of the header menu. "About" carries the dropdown.
        $primary = [
            ['label' => 'About', 'target' => 'about'],
            ['label' => 'Projects', 'target' => 'projects.index'],
            ['label' => 'Services', 'target' => 'services'],
            ['label' => 'Partners', 'target' => 'partners'],
            ['label' => 'Testimonials', 'target' => 'testimonials'],
            ['label' => 'Contact', 'target' => 'contact'],
        ];

        foreach ($primary as $i => $link) {
            DB::table('menu_links')->insert($link + [
                'menu_id' => $primaryId,
                'parent_id' => null,
                'sort_order' => $i + 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $aboutId = DB::table('menu_links')
            ->where('menu_id', $primaryId)
            ->where('label', 'About')
            ->value('id');

        $aboutChildren = [
            ['label' => 'Company Overview', 'target' => 'about'],
            ['label' => 'Mission & Vision', 'target' => 'mission-vision'],
            ['label' => "Managing Director's Message", 'target' => 'md-message'],
            ['label' => 'Board of Directors', 'target' => 'directors'],
            ['label' => 'Management Team', 'target' => 'management'],
            ['label' => 'Achievements', 'target' => 'achievements'],
        ];

        foreach ($aboutChildren as $i => $link) {
            DB::table('menu_links')->insert($link + [
                'menu_id' => $primaryId,
                'parent_id' => $aboutId,
                'sort_order' => $i + 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $footer = [
            ['label' => 'About', 'target' => 'about'],
            ['label' => 'Projects', 'target' => 'projects.index'],
            ['label' => 'Services', 'target' => 'services'],
            ['label' => 'Partners', 'target' => 'partners'],
            ['label' => 'Investors & Landowners', 'target' => 'partners'],
            ['label' => 'Testimonials', 'target' => 'testimonials'],
            ['label' => 'Contact', 'target' => 'contact'],
        ];

        foreach ($footer as $i => $link) {
            DB::table('menu_links')->insert($link + [
                'menu_id' => $footerId,
                'parent_id' => null,
                'sort_order' => $i + 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
};
