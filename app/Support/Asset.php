<?php

namespace App\Support;

class Asset
{
    /**
     * A cache-busted URL for a file under the public root.
     *
     * Hostinger serves static files with `Cache-Control: public, max-age=604800`
     * and the paths never change, so a deployed CSS or JS edit stayed invisible
     * for a week to anyone who had already loaded the old file — including the
     * client. Appending the file's mtime changes the URL exactly when the file
     * changes and at no other time, so caching still works as hard as it did.
     *
     * public_path() resolves to public_html on the server (index.php calls
     * usePublicPath), so the stat hits the file actually being served.
     */
    public static function v(string $path): string
    {
        $stamp = @filemtime(public_path($path));

        return $stamp ? asset($path).'?v='.$stamp : asset($path);
    }
}
