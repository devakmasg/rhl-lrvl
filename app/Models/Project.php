<?php

namespace App\Models;

use App\Models\Concerns\ResolvesImageUrl;
use App\Support\MapEmbed;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'slug', 'name', 'type', 'location', 'map_embed', 'status', 'progress', 'hero_image',
    'summary', 'body', 'facts', 'features', 'published', 'featured', 'brochure_path',
])]
class Project extends Model
{
    use ResolvesImageUrl;

    protected function casts(): array
    {
        return [
            'facts' => 'array',
            'features' => 'array',
            'published' => 'boolean',
            'featured' => 'boolean',
        ];
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
