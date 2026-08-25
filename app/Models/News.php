<?php

namespace App\Models;

use App\Models\Concerns\ResolvesImageUrl;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['slug', 'title', 'category', 'date', 'excerpt', 'body', 'cover_image', 'published'])]
class News extends Model
{
    use ResolvesImageUrl;

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'published' => 'boolean',
        ];
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        return $this->resolveImageUrl($this->cover_image);
    }
}
