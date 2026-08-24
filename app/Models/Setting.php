<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'address', 'phone', 'whatsapp', 'email', 'hours_weekday', 'hours_saturday',
    'hours_friday', 'map_query', 'social_instagram', 'social_linkedin', 'social_facebook',
])]
class Setting extends Model
{
    //
}
