<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            ['name' => 'R. Alam', 'role' => 'Homeowner, The RHL Residences', 'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=facearea&facepad=2.5&w=100&h=100&q=80', 'quote' => 'RHL Properties delivered our residence months ahead of schedule, without compromising a single design detail. Exceptional craftsmanship.'],
            ['name' => 'S. Karim', 'role' => 'Landowner Partner', 'avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=facearea&facepad=2.5&w=100&h=100&q=80', 'quote' => 'As a landowner, their transparency through the entire partnership process gave us complete confidence from day one.'],
            ['name' => 'T. Huda', 'role' => 'Commercial Tenant, Skyline Commerce Tower', 'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=facearea&facepad=2.5&w=100&h=100&q=80', 'quote' => 'Our headquarters at Skyline Commerce Tower has genuinely elevated how our teams and clients experience the brand.'],
        ];

        foreach ($testimonials as $t) {
            Testimonial::create($t);
        }
    }
}
