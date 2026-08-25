<?php

namespace App\Models;

use App\Models\Concerns\ResolvesImageUrl;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'role', 'photo', 'bio', 'order'])]
class Director extends Model
{
    use ResolvesImageUrl;

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->resolveImageUrl($this->photo);
    }
}
