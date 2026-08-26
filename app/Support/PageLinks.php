<?php

namespace App\Support;

/**
 * The wording on the arrow links that carry a visitor from one section to the
 * page behind it — "Read our full story", "All news", "Meet the board".
 *
 * Where each link *goes* stays in the view: a section's link points at one
 * specific page and moving it would break the section's meaning. Only the
 * words are editable, which is what the site owner actually asks to change.
 *
 * Registries are keyed by page slug, matching PageSections, so a view asks for
 * its own page's defaults through Page::link().
 */
final class PageLinks
{
    public const REGISTRIES = [
        'home' => [
            'story' => [
                'label' => 'Our Story → About page',
                'text' => 'Read our full story',
            ],
            'mission_vision' => [
                'label' => 'Mission & Vision cards',
                'text' => 'Read our mission & vision',
                'note' => 'Shown on both cards.',
            ],
            'featured_project' => [
                'label' => 'Featured slider → each project',
                'text' => 'View Project',
            ],
            'portfolio_ongoing' => [
                'label' => 'Portfolio → ongoing list',
                'text' => 'View all',
            ],
            'portfolio_completed' => [
                'label' => 'Portfolio → completed list',
                'text' => 'View all',
            ],
            'services' => [
                'label' => 'What We Do → Services page',
                'text' => 'All services',
            ],
            'md_message' => [
                'label' => "MD teaser → Managing Director's Message",
                'text' => 'Read the full message',
            ],
            'leadership' => [
                'label' => 'Leadership strip → Directors',
                'text' => 'Meet the board',
            ],
            'news' => [
                'label' => 'Latest News → News page',
                'text' => 'All news',
            ],
            'map' => [
                'label' => 'Head office → Contact page',
                'text' => 'Get directions & contact us',
            ],
        ],
    ];

    /**
     * Every link defined for a page.
     *
     * @return array<string, array{label: string, text: string, note?: string}>
     */
    public static function all(string $page): array
    {
        return self::REGISTRIES[$page] ?? [];
    }

    /** The original wording, used when nothing has been saved for this link. */
    public static function default(string $page, string $key): ?string
    {
        return self::REGISTRIES[$page][$key]['text'] ?? null;
    }
}
