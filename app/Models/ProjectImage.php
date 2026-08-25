<?php

namespace App\Models;

use App\Models\Concerns\ResolvesImageUrl;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['project_id', 'image_path', 'is_featured', 'sort_order'])]
class ProjectImage extends Model
{
    use ResolvesImageUrl;

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
        ];
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->resolveImageUrl($this->image_path);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
