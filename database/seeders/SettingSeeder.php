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
            // Starter handles so the footer's "Follow" column renders on a
            // fresh install — the client replaces these in Site Settings.
            'social_facebook' => 'https://facebook.com/rhlproperties',
            'social_instagram' => 'https://instagram.com/rhlproperties',
            'social_linkedin' => 'https://linkedin.com/company/rhlproperties',
            'social_youtube' => 'https://youtube.com/@rhlproperties',
            'social_twitter' => null,
            'social_tiktok' => null,
        ]);
    }
}
