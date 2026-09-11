<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'phone', 'avatar', 'role_id', 'is_active', 'must_change_password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function inquiryNotes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InquiryNote::class);
    }

    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Whether this user may reach the admin-only areas (Users and Roles).
     */
    public function isAdmin(): bool
    {
        return (bool) $this->role?->is_admin;
    }

    /**
     * Whether this user's role grants the given admin section.
     */
    public function canAccessSection(string $section): bool
    {
        return (bool) $this->role?->allows($section);
    }

    /**
     * The role name, used wherever the role is displayed.
     */
    public function getRoleLabelAttribute(): string
    {
        return $this->role?->name ?? 'No role';
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'must_change_password' => 'boolean',
        ];
    }
}
