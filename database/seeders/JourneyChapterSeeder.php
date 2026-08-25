<?php

namespace Database\Seeders;

use App\Models\JourneyChapter;
use Illuminate\Database\Seeder;

/**
 * The four chapters exactly as they were hardcoded in home.blade.php, so
 * moving them into the database changes nothing on the page.
 */
class JourneyChapterSeeder extends Seeder
{
    public function run(): void
    {
        $chapters = [
            [
                'media_type' => 'video',
                'image_path' => 'https://images.unsplash.com/photo-1470723710355-95304d8aece4?auto=format&fit=crop&w=1800&q=80',
                'video_path' => 'assets/videos/skyline-commerce-tower.mp4',
                'kicker' => 'Ongoing — Tejgaon',
                'heading' => 'Rising above the skyline.',
                'body' => 'Twenty-two floors of Grade-A office space are taking shape in the heart of Tejgaon. Structural work is complete; interior fit-out begins this quarter.',
                'link_label' => 'Follow the build',
                'link_url' => '/projects',
                'sort_order' => 1,
            ],
            [
                'media_type' => 'image',
                'image_path' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=1800&q=80',
                'video_path' => null,
                'kicker' => 'Completed — Gulshan',
                'heading' => 'Where lakeside living began.',
                'body' => 'Delivered in 2024, our flagship residences set the benchmark for design-led living on Gulshan Lake — every unit found a home within its first year.',
                'link_label' => 'See the finished residences',
                'link_url' => '/projects',
                'sort_order' => 2,
            ],
            [
                'media_type' => 'video',
                'image_path' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1800&q=80',
                'video_path' => 'assets/videos/grand-exchange.mp4',
                'kicker' => 'Ongoing — Dhanmondi',
                'heading' => 'A new exchange, taking shape.',
                'body' => 'Foundations are laid for a mixed-use landmark blending retail, office and public space along Dhanmondi Lake. Topping out is expected within the year.',
                'link_label' => 'Follow the build',
                'link_url' => '/projects',
                'sort_order' => 3,
            ],
            [
                'media_type' => 'image',
                'image_path' => 'https://images.unsplash.com/photo-1502005229762-cf1b2da7c5d6?auto=format&fit=crop&w=1800&q=80',
                'video_path' => null,
                'kicker' => 'Upcoming — Banani',
                'heading' => 'The next chapter, breaking ground soon.',
                'body' => "Twelve private villas designed around Banani Lake's natural shoreline. Groundbreaking is scheduled for early 2027.",
                'link_label' => 'Register your interest',
                'link_url' => '/projects',
                'sort_order' => 4,
            ],
        ];

        foreach ($chapters as $chapter) {
            JourneyChapter::updateOrCreate(
                ['sort_order' => $chapter['sort_order']],
                $chapter + ['is_active' => true]
            );
        }
    }
}
