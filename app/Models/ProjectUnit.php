<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

#[Fillable([
    'project_id', 'unit_type', 'size_sqft', 'beds', 'baths',
    'drawing_rooms', 'dining_rooms', 'balconies', 'floorplate', 'use', 'sort_order',
])]
class ProjectUnit extends Model
{
    /**
     * Every column the units table can show, in the order it shows them.
     *
     * One definition drives three places: the header on the project page, the
     * cells under it, and the repeater on the project form — a new line on a
     * unit needs its column here and nowhere else.
     */
    public const COLUMNS = [
        'unit_type' => 'Unit Type',
        'size_sqft' => 'Size (sq ft)',
        'beds' => 'Beds',
        'baths' => 'Baths',
        'drawing_rooms' => 'Drawing',
        'dining_rooms' => 'Dining',
        'balconies' => 'Balcony',
        'floorplate' => 'Floorplate',
        'use' => 'Use',
    ];

    /**
     * The columns worth printing for one project — the ones at least one of
     * its units actually fills, keyed by column name.
     *
     * A project fills a handful of these at most: a flat lists beds and baths,
     * a floor lists its floorplate and use. Printing the full set would leave
     * a table mostly of empty cells, and hardcoding one set per kind of
     * project is what stopped drawing rooms and balconies from being sayable
     * at all.
     */
    public static function columnsFor(Collection $units): array
    {
        $columns = [];

        foreach (self::COLUMNS as $key => $label) {
            // Unit type is the row's own name — always shown, and required.
            if ($key === 'unit_type' || $units->contains(fn (self $unit) => filled($unit->{$key}))) {
                $columns[$key] = $label;
            }
        }

        // A commercial schedule lists floors rather than flats, and its first
        // column names a floor range ("Levels 3–8") rather than a unit type.
        if (! $units->contains(fn (self $unit) => filled($unit->beds))
            && (isset($columns['floorplate']) || isset($columns['use']))) {
            $columns['unit_type'] = 'Floor Range';
        }

        return $columns;
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
