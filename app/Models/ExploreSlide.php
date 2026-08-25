<?php

namespace App\Models;

use App\Models\Concerns\ResolvesImageUrl;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'project_id', 'media_type', 'image_path', 'video_path', 'category',
    'title', 'location', 'is_active', 'sort_order',
])]
class ExploreSlide extends Model
{
    use ResolvesImageUrl;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->resolveImageUrl($this->image_path);
    }

    public function getVideoUrlAttribute(): ?string
    {
        return $this->resolveImageUrl($this->video_path);
    }

    public function isVideo(): bool
    {
        return $this->media_type === 'video' && filled($this->video_path);
    }

    /* The caption prefers the linked project so it can't drift from it, but
       falls back to the slide's own fields for a slide with no project. */

    public function displayCategory(): ?string
    {
        return $this->project?->type ?: $this->category;
    }

    public function displayTitle(): ?string
    {
        return $this->project?->name ?: $this->title;
    }

    public function displayLocation(): ?string
    {
        return $this->project?->location ?: $this->location;
    }
}
