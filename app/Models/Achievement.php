<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * An award or a certification shown on the Achievements page.
 *
 * Both kinds live in one table: they render as different components but carry
 * the same fields, so splitting them would duplicate a CRUD screen to no end.
 */
#[Fillable(['kind', 'year', 'title', 'description', 'sort_order', 'is_active'])]
class Achievement extends Model
{
    public const AWARD = 'award';

    public const CERTIFICATION = 'certification';

    public const KINDS = [
        self::AWARD => 'Award',
        self::CERTIFICATION => 'Certification',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function scopeKind(Builder $query, string $kind): Builder
    {
        return $query->where('kind', $kind)->orderBy('sort_order')->orderBy('id');
    }

    public function scopeLive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
