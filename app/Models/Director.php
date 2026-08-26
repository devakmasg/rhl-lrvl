<?php

namespace App\Models;

use App\Models\Concerns\ResolvesImageUrl;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'role', 'is_managing_director', 'photo', 'bio', 'order'])]
class Director extends Model
{
    use ResolvesImageUrl;

    protected function casts(): array
    {
        return [
            'is_managing_director' => 'boolean',
        ];
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->resolveImageUrl($this->photo);
    }

    /**
     * The one director whose portrait and message the site shows — on the
     * homepage teaser and the MD Message page.
     *
     * This is the single source for who the MD is. The About page holds only
     * the writing (md_quote, md_message); name, role and photo come from here,
     * so renaming the person in admin → Directors updates every page at once.
     *
     * Falls back to the old role match, then to the first director, so a row
     * that predates the flag still resolves to somebody rather than blanking
     * two pages.
     */
    public static function managingDirector(): ?self
    {
        return static::where('is_managing_director', true)->first()
            ?? static::where('role', 'Managing Director')->orderBy('order')->first()
            ?? static::orderBy('order')->first();
    }
}
