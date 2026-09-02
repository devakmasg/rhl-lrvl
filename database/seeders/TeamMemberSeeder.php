<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    public function run(): void
    {
        // 'department' decides which page a person lands on — Our Team or the
        // Sales Team page. See TeamMember::DEPARTMENTS.
        $team = [
            ['order' => 1, 'name' => 'Tanvir Huda', 'role' => 'Head of Construction', 'department' => TeamMember::MANAGEMENT, 'photo' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=200&q=80', 'bio' => 'Runs site delivery across every ongoing development, from foundation to handover.'],
            ['order' => 2, 'name' => 'Farhana Islam', 'role' => 'Head of Sales', 'department' => TeamMember::MANAGEMENT, 'photo' => 'https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?auto=format&fit=crop&w=200&q=80', 'bio' => 'Leads the sales and customer relationship team across all active projects.'],
            ['order' => 1, 'name' => 'Nusrat Jahan', 'role' => 'Senior Sales Manager', 'department' => TeamMember::SALES, 'photo' => 'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?auto=format&fit=crop&w=200&q=80', 'bio' => 'Handles residential enquiries in Gulshan and Banani, from first viewing to booking.'],
            ['order' => 2, 'name' => 'Imran Chowdhury', 'role' => 'Sales Manager, Commercial', 'department' => TeamMember::SALES, 'photo' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=200&q=80', 'bio' => 'Looks after office and retail space enquiries across the commercial portfolio.'],
            ['order' => 3, 'name' => 'Sadia Rahman', 'role' => 'Customer Relationship Executive', 'department' => TeamMember::SALES, 'photo' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=200&q=80', 'bio' => 'Your point of contact after booking — payment schedules, paperwork and handover.'],
        ];

        foreach ($team as $t) {
            TeamMember::create($t);
        }
    }
}
