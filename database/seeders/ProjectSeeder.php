<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $g = 'https://images.unsplash.com/';
        $q = '?auto=format&fit=crop&w=1600&q=80';
        $img = [
            'residential' => 'assets/images/hero-1-residential.jpg',
            'commercial' => 'assets/images/hero-2-commercial.jpg',
            'interior' => 'assets/images/hero-3-hospitality.jpg',
            'villa' => 'assets/images/hero-4-waterfront.jpg',
            'street' => 'assets/images/hero-5-business.jpg',
            'apartments' => $g.'photo-1545324418-cc1a3fa10c00'.$q,
            'towers' => $g.'photo-1470723710355-95304d8aece4'.$q,
            'lounge' => $g.'photo-1502005229762-cf1b2da7c5d6'.$q,
            'dusk' => $g.'photo-1512917774080-9991f1c4c750'.$q,
            'park' => $g.'photo-1449824913935-59a10b8d2000'.$q,
            'office' => $g.'photo-1497366754035-f200968a6e72'.$q,
            'skyline' => $g.'photo-1477959858617-67f85cf4f1df'.$q,
        ];

        $projects = [
            [
                'slug' => 'gulshan-heights', 'name' => 'Gulshan Heights',
                'type' => 'Residential', 'status' => 'Completed', 'location' => 'Gulshan',
                'hero' => $img['residential'], 'gallery' => [$img['interior'], $img['apartments'], $img['lounge']],
                'summary' => 'Forty-two lakeside apartments finished in 2024, each with a double-height terrace over Gulshan Lake.',
                'body' => [
                    'Gulshan Heights occupies a corner plot with an uninterrupted western aspect over the lake. The massing steps back above the sixth floor so every apartment receives direct afternoon light, and the double-height terraces are cut into that setback rather than cantilevered from it.',
                    'Handed over in March 2024, three months ahead of the contracted date. All forty-two units were sold before completion.',
                ],
                'facts' => ['Completion' => '2024', 'Built area' => '186,000 sq ft', 'Units' => '42 apartments', 'Floors' => 'B2 + 14', 'Plot' => '18 katha'],
                'features' => ['Double-height private terraces to every unit', 'Two levels of basement parking', "Rooftop lap pool and residents' lounge", 'Full backup generator and lift redundancy', 'Rainwater harvesting to landscape irrigation'],
                'amenities' => ['Rooftop swimming pool', "Residents' lounge", '100% backup generator', 'Two lift lobbies', '24-hour security', 'Landscaped gardens'],
                'units' => [
                    'columns' => ['Unit Type', 'Size (sq ft)', 'Beds', 'Baths'],
                    'rows' => [['2 Bed', '1,450', '2', '2'], ['3 Bed', '1,980', '3', '3'], ['3 Bed + Study', '2,250', '3', '4']],
                ],
                'floorPlans' => [
                    ['img' => $img['residential'], 'label' => 'Typical Floor Plan'],
                    ['img' => $img['interior'], 'label' => '2 Bed Unit Layout'],
                    ['img' => $img['apartments'], 'label' => '3 Bed Unit Layout'],
                ],
            ],
            [
                'slug' => 'rhl-trade-centre', 'name' => 'RHL Trade Centre',
                'type' => 'Commercial', 'status' => 'Ongoing', 'location' => 'Gulshan', 'progress' => 68,
                'hero' => $img['commercial'], 'gallery' => [$img['towers'], $img['office'], $img['dusk']],
                'summary' => 'Eighteen floors of Grade-A office space on Gulshan Avenue. Structure topped out; fit-out begins this quarter.',
                'body' => [
                    'A column-free floorplate of 11,000 sq ft gives tenants a single uninterrupted span, with the core pushed to the northern edge to keep the avenue frontage entirely glazed.',
                    'Structural work topped out in the second quarter. Facade installation is underway from the podium up, and interior fit-out for the first anchor tenant begins this quarter.',
                ],
                'facts' => ['Completion' => 'Q4 2027', 'Built area' => '212,000 sq ft', 'Floorplate' => '11,000 sq ft', 'Floors' => 'B3 + 18', 'Plot' => '24 katha'],
                'features' => ['Column-free 11,000 sq ft floorplates', 'Double-glazed unitised curtain wall', 'Six passenger lifts plus dedicated service core', 'Three basement levels, 180 car spaces', 'Targeting LEED Gold certification'],
                'amenities' => ['Column-free floorplates', 'Six passenger lifts', 'Central air conditioning', '180 basement car spaces', '24-hour security', 'Standby generator'],
                'units' => [
                    'columns' => ['Floor Range', 'Size (sq ft)', 'Floorplate', 'Use'],
                    'rows' => [['3rd–8th', '66,000', '11,000 sq ft', 'Office'], ['9th–14th', '66,000', '11,000 sq ft', 'Office'], ['15th–18th', '44,000', '11,000 sq ft', 'Office / fit-out ready']],
                ],
                'floorPlans' => [
                    ['img' => $img['commercial'], 'label' => 'Typical Office Floor Plan'],
                    ['img' => $img['office'], 'label' => 'Ground Floor Lobby Plan'],
                    ['img' => $img['towers'], 'label' => 'Basement Parking Plan'],
                ],
            ],
            [
                'slug' => 'banani-lake-residences', 'name' => 'Banani Lake Residences',
                'type' => 'Residential', 'status' => 'Ongoing', 'location' => 'Banani', 'progress' => 41,
                'hero' => $img['interior'], 'gallery' => [$img['residential'], $img['lounge'], $img['apartments']],
                'summary' => 'Twenty-six duplexes stepping down toward the lake edge, arranged around a shared courtyard garden.',
                'body' => [
                    'The site falls almost four metres from road to lake, and the plan uses that rather than levelling it: three terraced blocks step down the slope so no duplex looks into another, and the shared courtyard sits at the middle level where the whole community passes through it.',
                    'Superstructure is complete to the fourth floor across all three blocks. Landscaping of the courtyard begins once the final block tops out.',
                ],
                'facts' => ['Completion' => 'Q2 2027', 'Built area' => '148,000 sq ft', 'Units' => '26 duplexes', 'Floors' => 'B1 + 8', 'Plot' => '22 katha'],
                'features' => ['Terraced blocks following the natural fall of the site', 'Shared courtyard garden at mid-level', 'Every duplex dual-aspect', 'Direct lake frontage with private walkway', 'Underground parking beneath the courtyard'],
                'amenities' => ['Shared courtyard garden', 'Private lake-facing walkway', 'Underground parking', 'Community hall', '24-hour security', 'Solar water heating'],
                'units' => [
                    'columns' => ['Unit Type', 'Size (sq ft)', 'Beds', 'Baths'],
                    'rows' => [['Duplex A', '2,100', '3', '3'], ['Duplex B', '2,450', '4', '4']],
                ],
                'floorPlans' => [
                    ['img' => $img['interior'], 'label' => 'Ground Floor Plan'],
                    ['img' => $img['residential'], 'label' => 'Upper Floor Plan'],
                    ['img' => $img['lounge'], 'label' => 'Duplex B Layout'],
                ],
            ],
            [
                'slug' => 'banani-exchange', 'name' => 'The Banani Exchange',
                'type' => 'Commercial', 'status' => 'Upcoming', 'location' => 'Banani',
                'hero' => $img['street'], 'gallery' => [$img['towers'], $img['park'], $img['office']],
                'summary' => 'A mixed retail and workspace address on Kemal Ataturk Avenue. Groundbreaking scheduled for early 2027.',
                'body' => [
                    'Three retail levels wrap a public arcade cut diagonally through the block, connecting Kemal Ataturk Avenue to the quieter service road behind. Eight floors of flexible workspace sit above, set back from the arcade so daylight reaches ground level.',
                    'Planning approval is secured and enabling works are tendered. Groundbreaking is scheduled for the first quarter of 2027.',
                ],
                'facts' => ['Completion' => 'Q3 2029', 'Built area' => '164,000 sq ft', 'Retail' => '38,000 sq ft', 'Floors' => 'B2 + 11', 'Plot' => '19 katha'],
                'features' => ['Public arcade cutting through the block', 'Three retail levels with avenue frontage', 'Flexible workspace floors above', 'Set-back massing for ground-level daylight', 'Servicing entirely from the rear road'],
                'amenities' => ['Public arcade frontage', 'Flexible workspace floors', 'Rear-road servicing', 'Passenger and service lifts', 'Basement parking', 'Retail signage zones'],
                'units' => [
                    'columns' => ['Floor Range', 'Size (sq ft)', 'Floorplate', 'Use'],
                    'rows' => [['Ground–2nd', '48,000', '16,000 sq ft', 'Retail'], ['3rd–10th', '88,000', '11,000 sq ft', 'Workspace']],
                ],
                'floorPlans' => [
                    ['img' => $img['street'], 'label' => 'Ground Floor Retail Plan'],
                    ['img' => $img['office'], 'label' => 'Typical Workspace Floor'],
                    ['img' => $img['towers'], 'label' => 'Arcade Level Plan'],
                ],
            ],
            [
                'slug' => 'dhanmondi-garden-villas', 'name' => 'Dhanmondi Garden Villas',
                'type' => 'Residential', 'status' => 'Completed', 'location' => 'Dhanmondi',
                'hero' => $img['villa'], 'gallery' => [$img['residential'], $img['interior'], $img['lounge']],
                'summary' => 'Twelve private villas delivered in 2023, every unit sold within its first year on the market.',
                'body' => [
                    'Twelve villas on a single gated plot, each with its own walled garden and a shared central green that all twelve front onto. The walls are low enough to keep sightlines across the development and high enough that no garden is overlooked.',
                    'Completed and handed over in late 2023. Every villa was sold within twelve months of launch.',
                ],
                'facts' => ['Completion' => '2023', 'Built area' => '96,000 sq ft', 'Units' => '12 villas', 'Floors' => 'G + 2 each', 'Plot' => '32 katha'],
                'features' => ['Private walled garden to every villa', 'Shared central green', 'Two covered car spaces per unit', 'Solar hot water across all twelve roofs', 'Gated single-entry site with 24-hour security'],
                'amenities' => ['Private walled garden', 'Shared central green', 'Two covered car spaces', 'Solar hot water', 'Gated single-entry security', '24-hour security'],
                'units' => [
                    'columns' => ['Unit Type', 'Size (sq ft)', 'Beds', 'Baths'],
                    'rows' => [['Villa', '8,000', '5', '5']],
                ],
                'floorPlans' => [
                    ['img' => $img['villa'], 'label' => 'Ground Floor Plan'],
                    ['img' => $img['residential'], 'label' => 'First Floor Plan'],
                    ['img' => $img['interior'], 'label' => 'Garden Level Plan'],
                ],
            ],
            [
                'slug' => 'lakeview-court', 'name' => 'Lakeview Court',
                'type' => 'Residential', 'status' => 'Upcoming', 'location' => 'Dhanmondi',
                'hero' => $img['apartments'], 'gallery' => [$img['interior'], $img['residential'], $img['dusk']],
                'summary' => 'Thirty apartments facing Dhanmondi Lake, designed around cross-ventilation and deep shaded balconies.',
                'body' => [
                    'A single slab block turned slightly off the street grid so every apartment faces the lake rather than the road. The depth of the balconies is set by the summer sun angle — deep enough to shade the glazing through the hottest part of the day, shallow enough not to darken the rooms behind.',
                    'Design is complete and approvals are in final review. Construction is expected to begin in the second half of 2027.',
                ],
                'facts' => ['Completion' => 'Q1 2030', 'Built area' => '124,000 sq ft', 'Units' => '30 apartments', 'Floors' => 'B2 + 12', 'Plot' => '16 katha'],
                'features' => ['Every apartment lake-facing and cross-ventilated', 'Balcony depth set by summer sun angle', 'Communal roof terrace over the lake', 'Two basement parking levels', 'Low-e glazing throughout'],
                'amenities' => ['Lake-facing balconies', 'Communal roof terrace', 'Two basement parking levels', 'Cross-ventilated units', '24-hour security', 'Backup generator'],
                'units' => [
                    'columns' => ['Unit Type', 'Size (sq ft)', 'Beds', 'Baths'],
                    'rows' => [['2 Bed', '1,250', '2', '2'], ['3 Bed', '1,720', '3', '3']],
                ],
                'floorPlans' => [
                    ['img' => $img['apartments'], 'label' => 'Typical Floor Plan'],
                    ['img' => $img['interior'], 'label' => '2 Bed Unit Layout'],
                    ['img' => $img['dusk'], 'label' => '3 Bed Unit Layout'],
                ],
            ],
            [
                'slug' => 'tejgaon-industrial-park', 'name' => 'Tejgaon Industrial Park',
                'type' => 'Commercial', 'status' => 'Ongoing', 'location' => 'Tejgaon', 'progress' => 55,
                'hero' => $img['towers'], 'gallery' => [$img['skyline'], $img['park'], $img['street']],
                'summary' => 'Six light-industrial blocks with shared loading yards, replacing a disused warehouse site.',
                'body' => [
                    'Six independent blocks share three loading yards, so a tenant taking one block or all six gets the same vehicle access. The existing warehouse slab was retained and re-used as the yard surface rather than broken out and carted away.',
                    'Blocks one to four are structurally complete. Yard surfacing and the shared substation follow once the final two are up.',
                ],
                'facts' => ['Completion' => 'Q3 2027', 'Built area' => '240,000 sq ft', 'Blocks' => '6 units', 'Clear height' => '9.5 m', 'Plot' => '2.1 acres'],
                'features' => ['Six independently lettable blocks', 'Three shared loading yards', '9.5 m clear internal height', 'Existing slab retained as yard surface', 'Dedicated substation and standby power'],
                'amenities' => ['Shared loading yards', 'Dedicated substation', 'Standby power', '9.5 m clear height', 'Perimeter security fencing', 'Truck turning bays'],
                'units' => [
                    'columns' => ['Block', 'Size (sq ft)', 'Clear Height', 'Use'],
                    'rows' => [['Block 1–2', '80,000', '9.5 m', 'Light industrial'], ['Block 3–4', '80,000', '9.5 m', 'Light industrial'], ['Block 5–6', '80,000', '9.5 m', 'Light industrial']],
                ],
                'floorPlans' => [
                    ['img' => $img['towers'], 'label' => 'Site Layout Plan'],
                    ['img' => $img['park'], 'label' => 'Typical Block Plan'],
                    ['img' => $img['street'], 'label' => 'Loading Yard Plan'],
                ],
            ],
            [
                'slug' => 'rhl-logistics-hub', 'name' => 'RHL Logistics Hub',
                'type' => 'Commercial', 'status' => 'Completed', 'location' => 'Tejgaon',
                'hero' => $img['lounge'], 'gallery' => [$img['towers'], $img['skyline'], $img['office']],
                'summary' => 'A 210,000 sq ft distribution facility handed over in 2022 and fully leased on completion.',
                'body' => [
                    "A single-span distribution shed with twenty-two dock doors along the northern elevation and a two-storey office pod at the western corner. The roof carries a 400 kW solar array covering most of the building's daytime draw.",
                    'Handed over in 2022 and fully leased on the day of completion to a single logistics operator.',
                ],
                'facts' => ['Completion' => '2022', 'Built area' => '210,000 sq ft', 'Dock doors' => '22', 'Clear height' => '12 m', 'Plot' => '3.4 acres'],
                'features' => ['Single-span column-free warehouse floor', 'Twenty-two dock-level doors', '400 kW rooftop solar array', 'Two-storey integrated office pod', 'Full sprinkler and hydrant coverage'],
                'amenities' => ['22 dock-level doors', '400 kW rooftop solar array', 'Two-storey office pod', 'Full sprinkler system', '24-hour security', 'Truck marshalling yard'],
                'units' => [
                    'columns' => ['Unit', 'Size (sq ft)', 'Dock Doors', 'Use'],
                    'rows' => [['Warehouse', '210,000', '22', 'Distribution']],
                ],
                'floorPlans' => [
                    ['img' => $img['lounge'], 'label' => 'Warehouse Floor Plan'],
                    ['img' => $img['towers'], 'label' => 'Office Pod Plan'],
                    ['img' => $img['skyline'], 'label' => 'Yard & Dock Layout'],
                ],
            ],
            [
                'slug' => 'gulshan-park-avenue', 'name' => 'Gulshan Park Avenue',
                'type' => 'Residential', 'status' => 'Upcoming', 'location' => 'Gulshan',
                'hero' => $img['dusk'], 'gallery' => [$img['apartments'], $img['interior'], $img['lounge']],
                'summary' => "Eighteen large-format residences overlooking the park, with a private residents' club at podium level.",
                'body' => [
                    "Eighteen apartments only, at an average of 4,200 sq ft — one or two to a floor, each with a private lift lobby. The podium carries a residents' club that faces the park rather than the street.",
                    'Land is assembled and the scheme is in detailed design. Approvals are expected within the year.',
                ],
                'facts' => ['Completion' => 'Q4 2029', 'Built area' => '132,000 sq ft', 'Units' => '18 residences', 'Average unit' => '4,200 sq ft', 'Plot' => '20 katha'],
                'features' => ['One or two apartments per floor', 'Private lift lobby to every residence', "Residents' club facing the park", 'Staff quarters within each unit', 'Three levels of basement parking'],
                'amenities' => ["Residents' club at podium", 'Private lift lobby per unit', 'Staff quarters included', 'Three basement parking levels', '24-hour security', 'Landscaped park frontage'],
                'units' => [
                    'columns' => ['Unit Type', 'Size (sq ft)', 'Beds', 'Baths'],
                    'rows' => [['4 Bed', '4,200', '4', '5']],
                ],
                'floorPlans' => [
                    ['img' => $img['dusk'], 'label' => 'Typical Floor Plan'],
                    ['img' => $img['apartments'], 'label' => 'Unit Layout'],
                    ['img' => $img['interior'], 'label' => 'Podium Level Plan'],
                ],
            ],
            [
                'slug' => 'banani-corporate-tower', 'name' => 'Banani Corporate Tower',
                'type' => 'Commercial', 'status' => 'Completed', 'location' => 'Banani',
                'hero' => $img['park'], 'gallery' => [$img['towers'], $img['office'], $img['commercial']],
                'summary' => 'Twenty-two floors of headquarters space delivered in 2021, anchored by two multinational tenants.',
                'body' => [
                    'A headquarters building rather than a multi-let one: the floorplate is sized so a single occupier can take a whole floor without subdivision, and the ground-floor lobby is scaled for that kind of arrival.',
                    'Delivered in 2021 and anchored on completion by two multinational tenants across seventeen of the twenty-two floors.',
                ],
                'facts' => ['Completion' => '2021', 'Built area' => '268,000 sq ft', 'Floorplate' => '12,500 sq ft', 'Floors' => 'B3 + 22', 'Plot' => '26 katha'],
                'features' => ['Whole-floor occupancy without subdivision', 'Double-height arrival lobby', 'Eight passenger lifts, two service', 'Three basement levels, 220 car spaces', 'Central chilled-water plant'],
                'amenities' => ['Double-height arrival lobby', 'Eight passenger + two service lifts', '220 basement car spaces', 'Central chilled-water plant', '24-hour security', 'Standby generator'],
                'units' => [
                    'columns' => ['Floor Range', 'Size (sq ft)', 'Floorplate', 'Use'],
                    'rows' => [['3rd–12th', '125,000', '12,500 sq ft', 'Office'], ['13th–22nd', '125,000', '12,500 sq ft', 'Office']],
                ],
                'floorPlans' => [
                    ['img' => $img['park'], 'label' => 'Typical Office Floor'],
                    ['img' => $img['towers'], 'label' => 'Lobby Level Plan'],
                    ['img' => $img['office'], 'label' => 'Basement Parking Plan'],
                ],
            ],
            [
                'slug' => 'dhanmondi-central', 'name' => 'Dhanmondi Central',
                'type' => 'Commercial', 'status' => 'Upcoming', 'location' => 'Dhanmondi',
                'hero' => $img['office'], 'gallery' => [$img['street'], $img['park'], $img['towers']],
                'summary' => 'A neighbourhood retail and office address on Satmasjid Road, with public realm at street level.',
                'body' => [
                    'The ground floor gives roughly a third of its area back to the street as covered public space — seating, shade and a through-route rather than more lettable frontage. Retail occupies the two levels above, offices the six above that.',
                    'Site acquisition is complete. The scheme is in planning review, with construction targeted for 2028.',
                ],
                'facts' => ['Completion' => 'Q2 2030', 'Built area' => '142,000 sq ft', 'Retail' => '31,000 sq ft', 'Floors' => 'B2 + 9', 'Plot' => '17 katha'],
                'features' => ['A third of the ground floor given to public realm', 'Covered seating and through-route', 'Two retail levels above street', 'Six office floors with independent access', 'Cycle parking and end-of-trip facilities'],
                'amenities' => ['Covered public seating', 'Through-route arcade', 'Cycle parking + end-of-trip facilities', 'Independent office access', 'Retail signage zones', '24-hour security'],
                'units' => [
                    'columns' => ['Floor Range', 'Size (sq ft)', 'Floorplate', 'Use'],
                    'rows' => [['Ground–2nd', '62,000', '31,000 sq ft', 'Retail'], ['3rd–9th', '80,000', '11,400 sq ft', 'Office']],
                ],
                'floorPlans' => [
                    ['img' => $img['office'], 'label' => 'Ground Floor Retail Plan'],
                    ['img' => $img['street'], 'label' => 'Typical Office Floor'],
                    ['img' => $img['park'], 'label' => 'Public Realm Plan'],
                ],
            ],
            [
                'slug' => 'tejgaon-riverside-homes', 'name' => 'Tejgaon Riverside Homes',
                'type' => 'Residential', 'status' => 'Ongoing', 'location' => 'Tejgaon', 'progress' => 33,
                'hero' => $img['skyline'], 'gallery' => [$img['residential'], $img['apartments'], $img['interior']],
                'summary' => 'Ninety affordable family apartments beside the canal, the first phase of a longer regeneration plan.',
                'body' => [
                    'Ninety apartments in three blocks, built to a deliberately tight cost per square foot without reducing the things that matter daily — ceiling height, cross-ventilation and a real balcony on every unit. The canal edge is rebuilt as a public walkway as part of the works.',
                    'Foundations and ground floor are complete across all three blocks. This is phase one of a four-phase regeneration of the canal frontage.',
                ],
                'facts' => ['Completion' => 'Q4 2028', 'Built area' => '118,000 sq ft', 'Units' => '90 apartments', 'Floors' => 'G + 9', 'Plot' => '28 katha'],
                'features' => ['Every apartment cross-ventilated with a balcony', 'Canal edge rebuilt as public walkway', 'Community room and shared roof terrace', 'Phase one of four', 'Ground-level cycle and rickshaw parking'],
                'amenities' => ['Canal-edge public walkway', 'Community room', 'Shared roof terrace', 'Cross-ventilated units', 'Ground-level cycle/rickshaw parking', '24-hour security'],
                'units' => [
                    'columns' => ['Unit Type', 'Size (sq ft)', 'Beds', 'Baths'],
                    'rows' => [['2 Bed', '950', '2', '2'], ['3 Bed', '1,180', '3', '2']],
                ],
                'floorPlans' => [
                    ['img' => $img['skyline'], 'label' => 'Typical Floor Plan'],
                    ['img' => $img['residential'], 'label' => '2 Bed Unit Layout'],
                    ['img' => $img['apartments'], 'label' => '3 Bed Unit Layout'],
                ],
            ],
        ];

        foreach ($projects as $data) {
            $project = Project::create([
                'slug' => $data['slug'],
                'name' => $data['name'],
                'type' => $data['type'],
                'location' => $data['location'],
                'status' => $data['status'],
                'progress' => $data['progress'] ?? null,
                'hero_image' => $data['hero'],
                'summary' => $data['summary'],
                'body' => implode("\n\n", $data['body']),
                'facts' => $data['facts'],
                'features' => $data['features'],
                'published' => true,
                'featured' => in_array($data['slug'], ['gulshan-heights', 'rhl-trade-centre', 'banani-lake-residences'], true),
                'brochure_path' => 'brochures/RHL-'.$data['slug'].'-brochure.pdf',
            ]);

            foreach ($data['gallery'] as $i => $path) {
                $project->images()->create([
                    'image_path' => $path,
                    'is_featured' => $i === 0,
                    'sort_order' => $i,
                ]);
            }

            foreach ($data['floorPlans'] as $i => $plan) {
                $project->floorPlans()->create([
                    'image_path' => $plan['img'],
                    'label' => $plan['label'],
                    'sort_order' => $i,
                ]);
            }

            foreach ($data['amenities'] as $i => $text) {
                $project->amenities()->create(['text' => $text, 'sort_order' => $i]);
            }

            $isResidential = $data['units']['columns'][2] === 'Beds';
            foreach ($data['units']['rows'] as $i => $row) {
                if ($isResidential) {
                    $project->units()->create([
                        'unit_type' => $row[0],
                        'size_sqft' => $row[1],
                        'beds' => (int) $row[2],
                        'baths' => (int) $row[3],
                        'sort_order' => $i,
                    ]);
                } else {
                    $project->units()->create([
                        'unit_type' => $row[0],
                        'size_sqft' => $row[1],
                        'floorplate' => $row[2],
                        'use' => $row[3],
                        'sort_order' => $i,
                    ]);
                }
            }
        }
    }
}
