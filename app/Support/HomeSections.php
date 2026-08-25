<?php

namespace App\Support;

/**
 * The eyebrow + heading pair that sits above each homepage section.
 *
 * One definition shared by three places: the admin form that edits them, the
 * backfill that seeded them from the original hardcoded markup, and the
 * fallback used when a key is missing from page content (a fresh install, or
 * a section added after the row was last saved).
 *
 * Sections whose heading is already its own content field — the hero, and
 * "Our Story" whose heading is intro_headline — are deliberately absent.
 */
final class HomeSections
{
    public const DEFAULTS = [
        'story' => [
            'label' => 'Our Story',
            'eyebrow' => 'Our Story',
            'heading' => null, // heading comes from intro_headline
        ],
        'why' => [
            'label' => 'Why Choose Us',
            'eyebrow' => 'Why Choose Us',
            'heading' => 'Built on trust, backed by approvals, delivered on time.',
        ],
        'md_message' => [
            'label' => 'MD Message Teaser',
            'eyebrow' => 'A Message From Our Managing Director',
            'heading' => null, // this teaser has no heading, only the eyebrow tag and the quote
        ],
        'stats' => [
            'label' => 'Key Statistics',
            'eyebrow' => 'Key Statistics',
            'heading' => 'Our impact, in numbers.',
        ],
        'featured' => [
            'label' => 'Featured Developments',
            'eyebrow' => 'Featured Developments',
            'heading' => 'Landmarks in the making',
        ],
        'portfolio' => [
            'label' => 'Portfolio Split',
            'eyebrow' => 'Our Portfolio',
            'heading' => 'Ongoing and completed, at a glance.',
        ],
        'services' => [
            'label' => 'What We Do',
            'eyebrow' => 'What We Do',
            'heading' => 'Diversified across the built environment.',
        ],
        'journey' => [
            'label' => 'Our Journey',
            'eyebrow' => 'Our Journey',
            'heading' => "A story we're building, one milestone at a time.",
        ],
        'leadership' => [
            'label' => 'Leadership Strip',
            'eyebrow' => 'Leadership',
            'heading' => 'The team behind every handover.',
        ],
        'explore' => [
            'label' => 'Explore Slider',
            'eyebrow' => 'Explore',
            'heading' => 'Step inside our developments.',
        ],
        'testimonials' => [
            'label' => 'Testimonials',
            'eyebrow' => 'Client Voices',
            'heading' => 'Trusted by those who build with us',
        ],
        'news' => [
            'label' => 'Latest News',
            'eyebrow' => 'Latest Updates',
            'heading' => 'News from RHL Properties.',
        ],
        'map' => [
            'label' => 'Map / Head Office',
            'eyebrow' => 'Visit Our Head Office',
            'heading' => "We're based in the heart of Gulshan.",
        ],
        'connect' => [
            'label' => 'Closing CTA',
            'eyebrow' => 'Explore RHL Properties',
            'heading' => "Two decades of landmark developments — see where we've been, and where you fit in.",
        ],
    ];

    /**
     * @return array<string, array{label: string, eyebrow: ?string, heading: ?string}>
     */
    public static function all(): array
    {
        return self::DEFAULTS;
    }

    public static function default(string $key, string $field): ?string
    {
        return self::DEFAULTS[$key][$field] ?? null;
    }
}
