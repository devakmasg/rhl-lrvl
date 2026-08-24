<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['slug', 'title', 'content'])]
class Page extends Model
{
    protected function casts(): array
    {
        return [
            'content' => 'array',
        ];
    }
}
