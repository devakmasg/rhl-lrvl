<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['order' => 1, 'title' => 'Residential Development', 'description' => 'Thoughtfully designed homes and residential communities built for lasting quality of life.'],
            ['order' => 2, 'title' => 'Commercial Real Estate', 'description' => "Grade-A office, retail and mixed-use spaces engineered for tomorrow's businesses."],
            ['order' => 3, 'title' => 'Hospitality & Leisure', 'description' => 'Boutique hotels and lifestyle destinations crafted around guest experience.'],
            ['order' => 4, 'title' => 'Property Management', 'description' => 'End-to-end asset and facility management preserving long-term property value.'],
            ['order' => 5, 'title' => 'Investment & Asset Management', 'description' => 'Strategic capital deployment and portfolio management across real asset classes.'],
        ];

        foreach ($services as $s) {
            Service::create($s);
        }
    }
}
