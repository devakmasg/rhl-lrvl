<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    public function run(): void
    {
        $team = [
            ['order' => 1, 'name' => 'Tanvir Huda', 'role' => 'Head of Construction', 'photo' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=200&q=80', 'bio' => 'Runs site delivery across every ongoing development, from foundation to handover.'],
            ['order' => 2, 'name' => 'Farhana Islam', 'role' => 'Head of Sales', 'photo' => 'https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?auto=format&fit=crop&w=200&q=80', 'bio' => 'Leads the sales and customer relationship team across all active projects.'],
        ];

        foreach ($team as $t) {
            TeamMember::create($t);
        }
    }
}
