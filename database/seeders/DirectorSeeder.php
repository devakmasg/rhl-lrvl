<?php

namespace Database\Seeders;

use App\Models\Director;
use Illuminate\Database\Seeder;

class DirectorSeeder extends Seeder
{
    public function run(): void
    {
        $directors = [
            // The MD's photo is seeded at portrait size, not thumbnail size:
            // this one row also fills the large portrait slots on the homepage
            // teaser and the MD Message page.
            ['order' => 1, 'name' => 'Md. Rezaul Haque', 'role' => 'Managing Director', 'is_managing_director' => true, 'photo' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=800&q=80', 'bio' => 'Founded RHL Properties in 1998 and has led every major development since.'],
            ['order' => 2, 'name' => 'Nasrin Akhtar', 'role' => 'Director, Finance', 'photo' => 'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?auto=format&fit=crop&w=200&q=80', 'bio' => 'Oversees capital structure, financing and investor relations across the portfolio.'],
            ['order' => 3, 'name' => 'Kamrul Islam', 'role' => 'Non-Executive Director', 'photo' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=200&q=80', 'bio' => 'Brings three decades of RAJUK and regulatory experience to board oversight.'],
            ['order' => 4, 'name' => 'Sultana Begum', 'role' => 'Director, Legal & Compliance', 'photo' => 'https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?auto=format&fit=crop&w=200&q=80', 'bio' => 'Leads legal due diligence, land title verification and regulatory compliance.'],
        ];

        foreach ($directors as $d) {
            Director::create($d);
        }
    }
}
