<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $news = [
            ['title' => 'RHL Trade Centre tops out on schedule in Gulshan', 'category' => 'Construction Update', 'date' => '2026-08-12', 'excerpt' => 'Structural work on the eighteen-storey office tower has topped out, keeping the project on track for its Q4 2027 completion.', 'published' => true],
            ['title' => 'Gulshan Heights: all 42 units handed over ahead of date', 'category' => 'Handover', 'date' => '2026-07-28', 'excerpt' => 'Every apartment at Gulshan Heights has now been handed over, three months ahead of the original contracted date.', 'published' => true],
            ['title' => 'Banani Lake Residences crosses 40% construction milestone', 'category' => 'Construction Update', 'date' => '2026-07-05', 'excerpt' => 'The terraced duplex development beside Banani Lake has passed the 40% construction mark across all three blocks.', 'published' => true],
            ['title' => 'Dhanmondi Garden Villas sells out within twelve months of launch', 'category' => 'Sales', 'date' => '2026-05-14', 'excerpt' => 'All twelve villas at Dhanmondi Garden Villas have now been sold, twelve months after the development launched.', 'published' => true],
            ['title' => 'RHL Properties named Best Residential Developer at the 2026 REHAB Awards', 'category' => 'Awards', 'date' => '2026-03-02', 'excerpt' => 'RHL Properties has been recognised by REHAB for its residential portfolio and commitment to on-time handover.', 'published' => true],
            ['title' => 'Tejgaon Industrial Park: first four blocks structurally complete', 'category' => 'Construction Update', 'date' => '2026-02-18', 'excerpt' => 'Four of the six light-industrial blocks at Tejgaon Industrial Park have reached structural completion.', 'published' => true],
            ['title' => 'RHL Properties launches a scholarship fund for site-worker families', 'category' => 'Community', 'date' => '2026-01-20', 'excerpt' => 'A new scholarship fund will support the children of site workers across all active RHL developments.', 'published' => false],
        ];

        foreach ($news as $n) {
            News::create($n + ['slug' => Str::slug($n['title'])]);
        }
    }
}
