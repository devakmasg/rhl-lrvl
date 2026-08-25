<?php

namespace App\Models;

use App\Models\Concerns\ResolvesImageUrl;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'page_key', 'label', 'eyebrow', 'heading', 'intro', 'image_path',
    'seo_title', 'seo_description', 'og_image_path',
])]
class PageBanner extends Model
{
    use ResolvesImageUrl;

    public function getImageUrlAttribute(): ?string
    {
        return $this->resolveImageUrl($this->image_path);
    }

    /**
     * Social preview falls back to the banner image — in practice they are the
     * same picture on almost every page, and making the editor set both would
     * only create a way to get them out of sync.
     */
    public function getOgImageUrlAttribute(): ?string
    {
        return $this->resolveImageUrl($this->og_image_path ?: $this->image_path);
    }

    public static function forKey(string $key): ?self
    {
        return static::where('page_key', $key)->first();
    }
}
