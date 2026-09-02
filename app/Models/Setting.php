<?php

namespace App\Models;

use App\Models\Concerns\ResolvesImageUrl;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'site_name', 'site_short_name', 'brand_mark', 'brand_mark_sub',
    'logo_path', 'logo_dark_path', 'show_wordmark',
    'address', 'phone', 'whatsapp', 'email', 'hours_weekday', 'hours_saturday',
    'hours_friday', 'map_query', 'footer_blurb', 'meta_description',
    'footer_contact_heading', 'footer_follow_heading', 'footer_rights', 'footer_credit',
    'partners_eyebrow', 'partners_heading', 'show_partners',
    'nav_cta_label',
    'social_facebook', 'social_instagram', 'social_linkedin',
    'social_youtube', 'social_twitter', 'social_tiktok',
])]
class Setting extends Model
{
    use ResolvesImageUrl;

    /**
     * Every social platform the footer can link to, in the order the "Follow"
     * column lists them. One definition drives three places: this model's
     * socialLinks(), the validation in Admin\SettingController, and the fields
     * on the Site Settings form — adding a platform here is all a new one
     * needs, on top of its column.
     */
    public const SOCIALS = [
        'social_facebook' => ['label' => 'Facebook', 'placeholder' => 'https://facebook.com/rhlproperties'],
        'social_instagram' => ['label' => 'Instagram', 'placeholder' => 'https://instagram.com/rhlproperties'],
        'social_linkedin' => ['label' => 'LinkedIn', 'placeholder' => 'https://linkedin.com/company/rhlproperties'],
        'social_youtube' => ['label' => 'YouTube', 'placeholder' => 'https://youtube.com/@rhlproperties'],
        'social_twitter' => ['label' => 'X', 'placeholder' => 'https://x.com/rhlproperties'],
        'social_tiktok' => ['label' => 'TikTok', 'placeholder' => 'https://tiktok.com/@rhlproperties'],
    ];

    protected function casts(): array
    {
        return [
            'show_wordmark' => 'boolean',
            'show_partners' => 'boolean',
        ];
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->resolveImageUrl($this->logo_path);
    }

    /**
     * The version for dark backgrounds — the header over a hero photo, and the
     * footer. Falls back to the main logo, which is right for a logo that
     * already reads on any background.
     */
    public function getLogoDarkUrlAttribute(): ?string
    {
        return $this->resolveImageUrl($this->logo_dark_path) ?? $this->logo_url;
    }

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
        $links = [];

        foreach (self::SOCIALS as $column => $platform) {
            if (filled($this->{$column})) {
                $links[$platform['label']] = $this->{$column};
            }
        }

        return $links;
    }
}
