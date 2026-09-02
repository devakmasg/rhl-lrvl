<?php

namespace App\Models;

use App\Models\Concerns\ResolvesImageUrl;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * One logo in the "Trusted Partners" strip above the footer.
 *
 * The Partners *page* (landowner/investor leads) is unrelated — that content
 * lives in the pages table under the "partners" slug.
 */
#[Fillable(['name', 'logo_path', 'website', 'is_active', 'sort_order'])]
class Partner extends Model
{
    use ResolvesImageUrl;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->resolveImageUrl($this->logo_path);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * What the strip renders. A row with no logo yet would leave a hole in the
     * marquee, so it waits until one is uploaded.
     */
    public static function forStrip(): Collection
    {
        return static::query()
            ->where('is_active', true)
            ->whereNotNull('logo_path')
            ->ordered()
            ->get();
    }
}
