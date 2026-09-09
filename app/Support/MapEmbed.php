<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * Turns whatever someone pastes into a project's map field into a URL an
 * <iframe> can actually load — the map counterpart of {@see VideoEmbed}.
 *
 * Google hands out several different things depending on where you click, and
 * only one of them frames: the "Embed a map" code. A plain map link refuses to
 * be framed outright, so pasting the address bar would leave a grey box. The
 * forms accepted here are the ones a person actually ends up with:
 *
 *   - the whole <iframe …> block from Share → Embed a map
 *   - just its src, a .../maps/embed?pb=… URL
 *   - an ordinary map link with coordinates in it (/@23.79,90.41,17z/…)
 *   - a share short link (maps.app.goo.gl/…), expanded once when saved
 *   - a bare "23.7925, 90.4078" pair typed by hand
 *
 * Nothing outside Google is allowed through: the value ends up as the src of a
 * frame on a public page, so an arbitrary URL pasted here — by accident or not
 * — would be someone else's content served under this site's name.
 */
final class MapEmbed
{
    /**
     * The embeddable URL for a pasted value, or null if it is not one.
     *
     * Pure and offline: this runs on every project page render, so it never
     * reaches out to the network (see expand() for the part that does).
     */
    public static function url(?string $input): ?string
    {
        $input = trim((string) $input);

        if ($input === '') {
            return null;
        }

        // The full embed block from Google's dialog — keep only its src.
        if (Str::contains($input, '<iframe') && preg_match('/src\s*=\s*["\']([^"\']+)["\']/i', $input, $m)) {
            $input = html_entity_decode($m[1]);
        }

        // Already an embed URL. Both shapes frame: the pb= one from the dialog,
        // and the older ?q=…&output=embed one this site builds for the fallback.
        if (self::isGoogle($input) && (Str::contains($input, '/maps/embed') || Str::contains($input, 'output=embed'))) {
            return $input;
        }

        if ($point = self::coordinates($input)) {
            return self::forPoint(...$point);
        }

        // A place link with no coordinates in it still names the place, which
        // is enough for the same search embed the fallback uses.
        if (self::isGoogle($input) && preg_match('#/maps/place/([^/@?]+)#', $input, $m)) {
            return self::forQuery(str_replace('+', ' ', rawurldecode($m[1])));
        }

        return null;
    }

    /**
     * The map shown when a project has nothing pasted — the development's own
     * name and area, which is what the page did before the field existed.
     */
    public static function forQuery(string $query): string
    {
        return 'https://www.google.com/maps?q='.urlencode($query).'&output=embed';
    }

    /**
     * Expands a share short link to the full URL behind it, so it can be read
     * for coordinates like any other. Costs one HTTP round trip, so it belongs
     * in the admin save path only — never in a page render.
     *
     * Returns the input untouched if it is not a short link, and null if the
     * link could not be followed, so the caller can say so rather than store
     * something that will never frame.
     */
    public static function expand(string $input): ?string
    {
        $input = trim($input);

        if (! preg_match('#^https?://(maps\.app\.goo\.gl|goo\.gl/maps)/#i', $input)) {
            return $input;
        }

        try {
            $response = Http::timeout(8)->get($input);
        } catch (\Throwable) {
            return null;
        }

        $final = (string) $response->effectiveUri();

        // A short link normally redirects straight to the place URL; when
        // Google answers with a consent page instead, the real one is still in
        // the body, so it is worth one look before giving up.
        if (! self::coordinates($final) && preg_match('#https://www\.google\.com/maps[^"\'\\ ]+#', $response->body(), $m)) {
            $final = str_replace('\u0026', '&', $m[0]);
        }

        return $final !== '' ? $final : null;
    }

    /**
     * Latitude and longitude out of a map link, preferring the pin's own
     * coordinates (!3d/!4d) over the viewport centre (@lat,lng) — on a place
     * link the two differ, and the pin is the one worth showing.
     */
    private static function coordinates(string $input): ?array
    {
        if (preg_match('/!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/', $input, $m)) {
            return [$m[1], $m[2], self::zoom($input)];
        }

        if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)(?:,(\d+(?:\.\d+)?)z)?/', $input, $m)) {
            return [$m[1], $m[2], $m[3] ?? null];
        }

        // A bare pair, typed or copied from the "what's here?" card.
        if (preg_match('/^\s*(-?\d+\.\d+)\s*,\s*(-?\d+\.\d+)\s*$/', $input, $m)) {
            return [$m[1], $m[2], null];
        }

        return null;
    }

    private static function zoom(string $input): ?string
    {
        return preg_match('/@-?\d+\.\d+,-?\d+\.\d+,(\d+(?:\.\d+)?)z/', $input, $m) ? $m[1] : null;
    }

    private static function forPoint(string $lat, string $lng, ?string $zoom = null): string
    {
        return 'https://www.google.com/maps?q='.$lat.','.$lng
            .'&z='.($zoom ? (int) round((float) $zoom) : 16)
            .'&output=embed';
    }

    /**
     * Google's own hosts only, including the country domains (google.com.bd).
     */
    private static function isGoogle(string $url): bool
    {
        $host = parse_url($url, PHP_URL_HOST);

        return is_string($host) && preg_match('/(^|\.)google(\.[a-z]{2,3}){1,2}$/i', $host) === 1;
    }
}
