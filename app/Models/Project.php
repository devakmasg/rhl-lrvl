<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'slug', 'name', 'type', 'location', 'status', 'progress', 'hero_image',
    'summary', 'body', 'facts', 'features', 'published', 'featured', 'brochure_path',
])]
class Project extends Model
{
    protected function casts(): array
    {
        return [
            'facts' => 'array',
            'features' => 'array',
            'published' => 'boolean',
            'featured' => 'boolean',
        ];
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
