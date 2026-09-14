<?php

namespace App\Models;

use App\Models\Concerns\ResolvesImageUrl;
use App\Support\MapEmbed;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable([
    'slug', 'name', 'type', 'location', 'map_embed', 'status', 'progress', 'hero_image',
    'summary', 'body', 'facts', 'features', 'published', 'featured', 'brochure_path',
])]
class Project extends Model
{
    use ResolvesImageUrl;

    /**
     * Every status a project can hold, in lifecycle order — the order both the
     * admin dropdown and the public filter present them in.
     *
     * This is the single list. The column is an enum, so adding to it takes a
     * migration as well, and both controllers read from here rather than
     * keeping a copy of their own.
     */
    public const STATUSES = ['Upcoming', 'Ongoing', 'Under Construction', 'Handover', 'Completed'];

    protected function casts(): array
    {
        return [
            'facts' => 'array',
            'features' => 'array',
            'published' => 'boolean',
            'featured' => 'boolean',
        ];
    }

    /**
     * The spelling a status carries in URLs, in a card's data-status and in the
     * badge's CSS modifier: "Under Construction" becomes "under-construction".
     * For the single-word statuses this is the plain lowercase name, which is
     * exactly what those places already held.
     */
    public function getStatusSlugAttribute(): string
    {
        return Str::slug((string) $this->status);
    }

    /**
     * The public filter's options, as filter value => label, so the view never
     * has to slug anything itself.
     */
    public static function statusFilterOptions(): array
    {
        return collect(self::STATUSES)
            ->mapWithKeys(fn ($status) => [Str::slug($status) => $status])
            ->all();
    }

    /**
     * Turn a filter value back into the stored status, so a query string can
     * stay readable without the controller guessing at capitalisation. Returns
     * null for anything that is not a real status.
     */
    public static function statusFromSlug(?string $slug): ?string
    {
        foreach (self::STATUSES as $status) {
            if (Str::slug($status) === Str::slug((string) $slug)) {
                return $status;
            }
        }

        return null;
    }

    public function getHeroImageUrlAttribute(): ?string
    {
        return $this->resolveImageUrl($this->hero_image);
    }

    public function getBrochureUrlAttribute(): ?string
    {
        return $this->resolveImageUrl($this->brochure_path);
    }

    /**
     * What the project page's map frame loads: the map pasted in the admin, or
     * — while that is empty — the name-and-area search the page used before the
     * field existed, so every project still shows something.
     */
    public function getMapEmbedUrlAttribute(): string
    {
        return MapEmbed::url($this->map_embed)
            ?? MapEmbed::forQuery("{$this->name}, {$this->location}, Dhaka, Bangladesh");
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->orderBy('sort_order');
    }

    public function floorPlans(): HasMany
    {
        return $this->hasMany(ProjectFloorPlan::class)->orderBy('sort_order');
    }

    public function units(): HasMany
    {
        return $this->hasMany(ProjectUnit::class)->orderBy('sort_order');
    }

    public function amenities(): HasMany
    {
        return $this->hasMany(ProjectAmenity::class)->orderBy('sort_order');
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }
}
