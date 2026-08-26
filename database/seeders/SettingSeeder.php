<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::create([
            'site_name' => 'RHL Properties Ltd',
            'site_short_name' => 'RHL Properties',
            'brand_mark' => 'RHL',
            'brand_mark_sub' => 'PROPERTIES LTD',
            'meta_description' => 'RHL Properties Ltd — a diversified real estate & investment group across residential, commercial and hospitality developments.',
            'address' => 'House 24, Road 11, Gulshan-1, Dhaka 1212, Bangladesh',
            'phone' => '+880 1812-345678',
            'whatsapp' => '+880 1812-345678',
            'email' => 'info@rhlproperties.com.bd',
            'hours_weekday' => '9:00 – 18:00',
            'hours_saturday' => '10:00 – 16:00',
            'hours_friday' => 'Closed',
            'map_query' => 'Gulshan-1, Dhaka, Bangladesh',
            'social_instagram' => null,
            'social_linkedin' => null,
            'social_facebook' => null,
        ]);
    }
}
