<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/**
 * One entry in a menu.
 *
 * `target` holds a route name ("projects.index"), optionally with an anchor
 * ("partners#investors"), or anything already a URL. Storing the route name
 * rather than the built URL is what keeps the menu correct if the site moves
 * domain, and lets isActive() highlight the current page without a second
 * column describing which routes count as "inside" this item.
 */
#[Fillable(['menu_id', 'parent_id', 'label', 'target', 'sort_order', 'is_active'])]
class MenuLink extends Model
{
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order')->orderBy('id');
    }

    /** The route name, with any anchor stripped. */
    public function routeName(): string
    {
        return Str::before($this->target, '#');
    }

    /**
     * The href to render, or null when the target names a route that no longer
     * exists — the view drops the link rather than throwing for the whole page.
     */
    public function url(): ?string
    {
        $target = trim((string) $this->target);

        if ($target === '') {
            return null;
        }

        if (Str::startsWith($target, ['http://', 'https://', '//', '/', '#', 'tel:', 'mailto:'])) {
            return $target;
        }

        [$name, $fragment] = array_pad(explode('#', $target, 2), 2, null);

        if (! Route::has($name)) {
            return null;
        }

        return route($name).($fragment ? '#'.$fragment : '');
    }

    /**
     * Whether this item should read as the current page.
     *
     * A parent is active when any of its children is — that is what keeps
     * "About" highlighted while the visitor is on Mission & Vision.
     */
    public function isActive(): bool
    {
        if (Route::currentRouteName() === null) {
            return false;
        }

        $names = [$this->routeName(), ...$this->childRouteNames()];

        // A resource listing should stay active on its detail pages too.
        $patterns = [];
        foreach (array_filter($names) as $name) {
            $patterns[] = $name;
            if (str_ends_with($name, '.index')) {
                $patterns[] = Str::beforeLast($name, '.index').'.*';
            }
        }

        return $patterns !== [] && request()->routeIs($patterns);
    }

    /** @return list<string> */
    private function childRouteNames(): array
    {
        $children = $this->relationLoaded('children') ? $this->children : collect();

        return $children->map(fn (self $c) => $c->routeName())->all();
    }
}
