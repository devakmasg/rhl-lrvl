<?php

namespace App\Support;

/**
 * The eyebrow + heading pair that sits above each section of a page.
 *
 * One definition shared by three places: the admin form that edits them, the
 * backfill that seeded them from the original hardcoded markup, and the
 * fallback used when a key is missing from page content (a fresh install, or
 * a section added after the row was last saved).
 *
 * Registries are keyed by page slug, matching the pages table, so a view can
 * ask for its own page's defaults without naming the registry — see
 * Page::section(). Adding a page here is all a new page editor needs.
 *
 * Sections whose heading is already its own content field — the homepage hero,
 * and "Our Story" whose heading is intro_headline — are deliberately absent,
 * as is the closing CTA, which lives in the cta_blocks table.
 */
final class PageSections
{
    public const REGISTRIES = [
        'home' => HomeSections::DEFAULTS,
    ];

    /**
     * Every section defined for a page, in display order.
     *
     * @return array<string, array{label: string, eyebrow: ?string, heading: ?string}>
     */
    public static function all(string $page): array
    {
        return self::REGISTRIES[$page] ?? [];
    }

    /**
     * The original copy for one section field, used when the stored content
     * has nothing for it.
     */
    public static function default(string $page, string $key, string $field): ?string
    {
        return self::REGISTRIES[$page][$key][$field] ?? null;
    }

    /** Whether this page has any admin-editable section headings. */
    public static function has(string $page): bool
    {
        return isset(self::REGISTRIES[$page]);
    }
}
