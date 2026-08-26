<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'site_name', 'site_short_name', 'brand_mark', 'brand_mark_sub',
    'address', 'phone', 'whatsapp', 'email', 'hours_weekday', 'hours_saturday',
    'hours_friday', 'map_query', 'footer_blurb', 'meta_description',
    'social_instagram', 'social_linkedin', 'social_facebook',
])]
class Setting extends Model
{
    /**
     * Digits-only number for wa.me links.
     */
    public function getWhatsappDigitsAttribute(): string
    {
        return preg_replace('/\D/', '', (string) $this->whatsapp) ?: '';
    }

    /**
     * The social links that are actually set, as label => url.
     */
    public function socialLinks(): array
    {
        return array_filter([
            'Instagram' => $this->social_instagram,
            'LinkedIn' => $this->social_linkedin,
            'Facebook' => $this->social_facebook,
        ]);
    }
}
