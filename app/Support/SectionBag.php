<?php

namespace App\Support;

use App\Models\PageSection;

/**
 * The page's section headings, addressed by key from a view.
 *
 * Wrapping the rows rather than passing a bare array is what lets a template
 * ask for a section that does not exist — a page added before its row was
 * seeded, or a section an admin cleared — and get an empty string back instead
 * of an "undefined index" fatal. The whole point of this phase is that a view
 * never breaks because content is missing.
 *
 *   {{ $sections->eyebrow('gallery') }}
 *   {{ $sections->heading('gallery') }}
 */
final class SectionBag
{
    /** @param array<string, PageSection> $sections */
    public function __construct(private array $sections = [])
    {
    }

    public function eyebrow(string $key, string $default = ''): string
    {
        return $this->value($key, 'eyebrow', $default);
    }

    public function heading(string $key, string $default = ''): string
    {
        return $this->value($key, 'heading', $default);
    }

    public function body(string $key, string $default = ''): string
    {
        return $this->value($key, 'body', $default);
    }

    public function linkLabel(string $key, string $default = ''): string
    {
        return $this->value($key, 'linkLabel', $default);
    }

    public function get(string $key): ?PageSection
    {
        return $this->sections[$key] ?? null;
    }

    private function value(string $key, string $method, string $default): string
    {
        $resolved = $this->sections[$key]?->{$method}() ?? '';

        return $resolved !== '' ? $resolved : $default;
    }
}
