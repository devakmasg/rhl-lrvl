<?php

namespace App\Models;

use App\Models\Concerns\ResolvesImageUrl;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'role', 'avatar', 'quote'])]
class Testimonial extends Model
{
    use ResolvesImageUrl;

    public function getAvatarUrlAttribute(): ?string
    {
        return $this->resolveImageUrl($this->avatar);
    }
}
