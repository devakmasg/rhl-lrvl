<?php

namespace App\Models;

use App\Models\Concerns\ResolvesImageUrl;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'role', 'department', 'photo', 'bio', 'order'])]
class TeamMember extends Model
{
    use ResolvesImageUrl;

    /**
     * Which page a person appears on. One table rather than two models: a
     * sales person and a management person carry exactly the same fields, and
     * only the page listing them differs.
     */
    public const MANAGEMENT = 'management';

    public const SALES = 'sales';

    public const DEPARTMENTS = [
        self::MANAGEMENT => 'Our Team',
        self::SALES => 'Sales Team',
    ];

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->resolveImageUrl($this->photo);
    }

    public function getDepartmentLabelAttribute(): string
    {
        return self::DEPARTMENTS[$this->department] ?? self::DEPARTMENTS[self::MANAGEMENT];
    }

    /**
     * Members of one department, in display order.
     *
     * Defaults to management so a row written before this column existed —
     * or by anything that does not set it — still lands on Our Team.
     */
    public function scopeDepartment($query, string $department)
    {
        return $department === self::MANAGEMENT
            ? $query->where(fn ($q) => $q->where('department', self::MANAGEMENT)->orWhereNull('department'))
            : $query->where('department', $department);
    }
}
