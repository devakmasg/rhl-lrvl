<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Support\MapEmbed;
use Illuminate\Validation\ValidationException;

trait ResolvesMapEmbed
{
    /**
     * Whatever was pasted into a map field, stored as the URL a page can
     * actually frame.
     *
     * Normalising on save rather than at render means the paste is checked
     * while the person who made it is still looking at the form: a link that
     * cannot be framed is rejected with an explanation instead of quietly
     * becoming a grey box on the live site.
     */
    protected function resolveMapEmbed(?string $input, string $field = 'map_embed'): ?string
    {
        $input = trim((string) $input);

        if ($input === '') {
            return null;
        }

        // Short share links have to be followed before they can be read.
        $expanded = MapEmbed::expand($input);
        $url = $expanded ? MapEmbed::url($expanded) : null;

        if (! $url) {
            throw ValidationException::withMessages([
                $field => "That doesn't look like a Google Maps location we can show. In Google Maps, open the place, choose Share → Embed a map, and paste the whole code — or paste the address-bar link with the coordinates in it.",
            ]);
        }

        return $url;
    }
}
