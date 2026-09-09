<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * One area a project can sit in — the options behind the Location dropdown on
 * the project form and the Location filter on the public Projects page.
 *
 * Projects store the location *name*, not an id (see the create migration), so
 * a rename here has to carry the projects with it; ProjectLocationController
 * does that in one transaction.
 */
#[Fillable(['name', 'is_active', 'sort_order'])]
class ProjectLocation extends Model
{
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function scopeLive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * The names offered anywhere a location is chosen or filtered on.
     */
    public static function options(): Collection
    {
        return static::query()->live()->ordered()->pluck('name');
    }

    /**
     * How the public filter and every project card spell this location in
     * markup — lowercase, so `data-location` and the option value line up and
     * the client-side filter keeps matching (see public/assets/js/projects.js).
     */
    public function getFilterValueAttribute(): string
    {
        return mb_strtolower($this->name);
    }
}
