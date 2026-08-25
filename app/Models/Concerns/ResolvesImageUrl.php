<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Turns whatever is in an image column into a URL that works from any route.
 *
 * Three kinds of value live in these columns and all three have to keep
 * working:
 *
 *  - an absolute URL (seeded Unsplash photos, or a pasted link) — passed
 *    through untouched;
 *  - a legacy relative path into public/ ("assets/images/hero-1.jpg"), which
 *    is what the seeders wrote. Rendered raw these resolve against the
 *    CURRENT path, so /projects/gulshan-heights asked for
 *    /projects/assets/images/... and 404'd — asset() makes them absolute;
 *  - an uploaded path on the public disk ("projects/hero/x.jpg"), which needs
 *    Storage::url() to become /storage/projects/hero/x.jpg.
 */
trait ResolvesImageUrl
{
    protected function resolveImageUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '//', 'data:'])) {
            return $path;
        }

        // Files shipped in public/ rather than uploaded to the storage disk.
        if (Str::startsWith($path, ['assets/', '/assets/', 'storage/', '/storage/'])) {
            return asset(ltrim($path, '/'));
        }

        return Storage::url($path);
    }
}
