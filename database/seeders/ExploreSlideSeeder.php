<?php

namespace Database\Seeders;

use App\Models\ExploreSlide;
use Illuminate\Database\Seeder;

/**
 * The five slides exactly as they were hardcoded in public/assets/js/home.js.
 */
class ExploreSlideSeeder extends Seeder
{
    public function run(): void
    {
        $slides = [
            [
                'media_type' => 'image',
                'image_path' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=1800&q=80',
                'video_path' => null,
                'category' => 'Residential',
                'title' => 'The RHL Residences',
                'location' => 'Gulshan',
                'sort_order' => 1,
            ],
            [
                'media_type' => 'video',
                'image_path' => 'https://images.unsplash.com/photo-1470723710355-95304d8aece4?auto=format&fit=crop&w=1800&q=80',
                'video_path' => 'assets/videos/skyline-commerce-tower.mp4',
                'category' => 'Commercial',
                'title' => 'Skyline Commerce Tower',
                'location' => 'Tejgaon',
                'sort_order' => 2,
            ],
            [
                'media_type' => 'image',
                'image_path' => 'https://images.unsplash.com/photo-1502005229762-cf1b2da7c5d6?auto=format&fit=crop&w=1800&q=80',
                'video_path' => null,
                'category' => 'Residential',
                'title' => 'Aurora Waterfront Villas',
                'location' => 'Banani',
                'sort_order' => 3,
            ],
            [
                'media_type' => 'video',
                'image_path' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1800&q=80',
                'video_path' => 'assets/videos/grand-exchange.mp4',
                'category' => 'Mixed-Use',
                'title' => 'The Grand Exchange',
                'location' => 'Dhanmondi',
                'sort_order' => 4,
            ],
            [
                'media_type' => 'image',
                'image_path' => 'https://images.unsplash.com/photo-1449824913935-59a10b8d2000?auto=format&fit=crop&w=1800&q=80',
                'video_path' => null,
                'category' => 'Commercial',
                'title' => 'Horizon Business Park',
                'location' => 'Tejgaon',
                'sort_order' => 5,
            ],
        ];

        foreach ($slides as $slide) {
            ExploreSlide::updateOrCreate(
                ['sort_order' => $slide['sort_order']],
                $slide + ['is_active' => true]
            );
        }
    }
}
