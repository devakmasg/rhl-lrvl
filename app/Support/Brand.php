<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;

/**
 * The company's own name, in the three forms the site renders it.
 *
 * Views reach for this rather than the $setting model directly, for two
 * reasons: the settings row is not bound on every view (only the footer and
 * nav get a composer), and a null row or an empty column must still render a
 * name rather than a blank. Every fallback below is the string the views
 * hardcoded before this class existed, so an un-migrated or un-seeded install
 * looks exactly as it did.
 *
 * Resolved once per request — the layout alone reads it three times.
 */
final class Brand
{
    private const FALLBACKS = [
        'site_name' => 'RHL Properties Ltd',
        'site_short_name' => 'RHL Properties',
        'brand_mark' => 'RHL',
        'brand_mark_sub' => 'PROPERTIES LTD',
        'meta_description' => 'RHL Properties Ltd — a diversified real estate & investment group across residential, commercial and hospitality developments.',
    ];

    private static ?Setting $setting = null;

    private static bool $resolved = false;

    /** The full legal name: "RHL Properties Ltd". */
    public static function name(): string
    {
        return self::value('site_name');
    }

    /** The shorter form used mid-sentence: "RHL Properties". Falls back to the full name. */
    public static function shortName(): string
    {
        $short = self::setting()?->site_short_name;

        return $short !== null && $short !== '' ? $short : self::name();
    }

    /** The large half of the nav wordmark: "RHL". */
    public static function mark(): string
    {
        return self::value('brand_mark');
    }

    /** The small half of the nav wordmark: "PROPERTIES LTD". */
    public static function markSub(): string
    {
        return self::value('brand_mark_sub');
    }

    /**
     * The uploaded logo, or null when none has been set — in which case the
     * header falls back to the inline SVG mark it has always drawn.
     */
    public static function logo(): ?string
    {
        return self::setting()?->logo_url;
    }

    /** The logo for dark backgrounds: the header over a hero, and the footer. */
    public static function logoOnDark(): ?string
    {
        return self::setting()?->logo_dark_url;
    }

    /**
     * The browser-tab icon, or null when none has been set — in which case
     * every layout falls back to the inline SVG it has always drawn.
     */
    public static function favicon(): ?string
    {
        return self::setting()?->favicon_url;
    }

    /**
     * Whether the "RHL / PROPERTIES LTD" text sits beside the logo.
     *
     * Defaults to true, so a site that has never opened the setting keeps the
     * wordmark it has today. An owner whose uploaded logo already contains the
     * company name turns it off.
     */
    public static function showWordmark(): bool
    {
        return (bool) (self::setting()?->show_wordmark ?? true);
    }

    /** Site-wide meta description, used when a page banner has none of its own. */
    public static function metaDescription(): string
    {
        return self::value('meta_description');
    }

    /**
     * Drop the memoised row. Only needed by tests and by the settings
     * controller after a save within the same request.
     */
    public static function flush(): void
    {
        self::$setting = null;
        self::$resolved = false;
    }

    private static function value(string $column): string
    {
        $stored = self::setting()?->{$column};

        return $stored !== null && $stored !== '' ? $stored : self::FALLBACKS[$column];
    }

    /**
     * Guarded on the table existing so `migrate` on a fresh database can still
     * boot a view, matching how AppServiceProvider resolves the same row.
     */
    private static function setting(): ?Setting
    {
        if (! self::$resolved) {
            self::$resolved = true;
            self::$setting = Schema::hasTable('settings') ? Setting::first() : null;
        }

        return self::$setting;
    }
}
