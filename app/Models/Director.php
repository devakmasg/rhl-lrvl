<?php

namespace App\Models;

use App\Models\Concerns\ResolvesImageUrl;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'role', 'is_managing_director', 'is_chairman', 'photo', 'bio', 'order'])]
class Director extends Model
{
    use ResolvesImageUrl;

    protected function casts(): array
    {
        return [
            'is_managing_director' => 'boolean',
            'is_chairman' => 'boolean',
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

    /**
     * The one director whose portrait and message the Chairman's Message page
     * shows — the same arrangement as managingDirector(): name, role and photo
     * come from here, the writing lives on the About page row.
     *
     * Unlike the MD there is no final fallback to the first director. A site
     * with nobody marked as Chairman has no Chairman, and showing some other
     * board member under that title would be worse than showing none.
     */
    public static function chairman(): ?self
    {
        return static::where('is_chairman', true)->first()
            ?? static::where('role', 'like', 'Chairman%')->orderBy('order')->first();
    }
}
