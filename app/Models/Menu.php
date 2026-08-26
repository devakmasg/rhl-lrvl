<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * A named list of links — the header menu, or the footer's Explore column.
 */
#[Fillable(['key', 'label', 'heading'])]
class Menu extends Model
{
    public function links(): HasMany
    {
        return $this->hasMany(MenuLink::class)->orderBy('sort_order')->orderBy('id');
    }

    public static function forKey(string $key): ?self
    {
        return static::where('key', $key)->first();
    }

    /**
     * Top-level active links, each with its active children already loaded.
     *
     * One query for the menu and one for its links — the children are grouped
     * in memory rather than fetched per parent.
     *
     * @return \Illuminate\Support\Collection<int, MenuLink>
     */
    public static function tree(string $key): \Illuminate\Support\Collection
    {
        $menu = static::forKey($key);

        if (! $menu) {
            return collect();
        }

        $links = $menu->links()->where('is_active', true)->get();
        $byParent = $links->groupBy('parent_id');

        return $byParent->get(null, collect())->each(
            fn (MenuLink $link) => $link->setRelation('children', $byParent->get($link->id, collect()))
        );
    }
}
