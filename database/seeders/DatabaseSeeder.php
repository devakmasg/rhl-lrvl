<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            ProjectSeeder::class,
            DirectorSeeder::class,
            TeamMemberSeeder::class,
            ServiceSeeder::class,
            TestimonialSeeder::class,
            NewsSeeder::class,
            InquirySeeder::class,
            SettingSeeder::class,
            PageSeeder::class,
        ]);
    }
}
