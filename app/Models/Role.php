<?php

namespace App\Models;

use App\Support\AdminSections;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug', 'description', 'is_admin', 'is_system', 'permissions'])]
class Role extends Model
{
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Whether this role may open the given admin section. Administrators are
     * allowed everything without consulting the stored list, so a section added
     * later never has to be back-filled onto them.
     */
    public function allows(string $section): bool
    {
        if ($this->is_admin) {
            return true;
        }

        return in_array($section, $this->permissions ?? [], true);
    }

    /**
     * The sections this role actually grants, in registry order so the sidebar
     * and the Roles page always list them the same way.
     *
     * @return array<int, string>
     */
    public function grantedSections(): array
    {
        if ($this->is_admin) {
            return AdminSections::keys();
        }

        return array_values(array_intersect(AdminSections::keys(), $this->permissions ?? []));
    }

    /**
     * Roles the client is allowed to reshape. The Administrator role is fixed:
     * it is what guarantees somebody can always reach Users.
     */
    public function isEditable(): bool
    {
        return ! $this->is_admin;
    }

    protected function casts(): array
    {
        return [
            'is_admin' => 'boolean',
            'is_system' => 'boolean',
            'permissions' => 'array',
        ];
    }
}
