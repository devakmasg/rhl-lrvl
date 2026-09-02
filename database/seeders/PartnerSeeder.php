<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

/**
 * Placeholder logos so the strip has something to show before the client
 * supplies real ones. The names are invented and the marks are plain SVGs
 * shipped in public/assets/images/partners — replace both from
 * admin → Trusted Partners.
 */
class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        $partners = [
            ['name' => 'Meridian Group', 'logo_path' => 'assets/images/partners/meridian.svg'],
            ['name' => 'Northbridge Capital', 'logo_path' => 'assets/images/partners/northbridge.svg'],
            ['name' => 'Everline Ceramics', 'logo_path' => 'assets/images/partners/everline.svg'],
            ['name' => 'Aurum Bank', 'logo_path' => 'assets/images/partners/aurum.svg'],
            ['name' => 'Castella Interiors', 'logo_path' => 'assets/images/partners/castella.svg'],
            ['name' => 'Sunlit Steel', 'logo_path' => 'assets/images/partners/sunlit.svg'],
            ['name' => 'Bluecrest Consulting', 'logo_path' => 'assets/images/partners/bluecrest.svg'],
            ['name' => 'Vertex Engineering', 'logo_path' => 'assets/images/partners/vertex.svg'],
        ];

        foreach ($partners as $position => $partner) {
            Partner::updateOrCreate(
                ['name' => $partner['name']],
                $partner + ['is_active' => true, 'sort_order' => $position + 1],
            );
        }
    }
}
