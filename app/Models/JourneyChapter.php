<?php

namespace App\Models;

use App\Models\Concerns\ResolvesImageUrl;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'media_type', 'image_path', 'video_path', 'kicker', 'heading', 'body',
    'link_label', 'link_url', 'is_active', 'sort_order',
])]
class JourneyChapter extends Model
{
    use ResolvesImageUrl;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->resolveImageUrl($this->image_path);
    }

    public function getVideoUrlAttribute(): ?string
    {
        return $this->resolveImageUrl($this->video_path);
    }

    /**
     * A chapter only behaves as a video once it actually has a clip — the
     * front-end script would otherwise build a <video> with an empty src.
     */
    public function isVideo(): bool
    {
        return $this->media_type === 'video' && filled($this->video_path);
    }
}
