<?php

namespace App\Support;

/**
 * Small text conveniences for admin-entered copy.
 */
final class Copy
{
    /**
     * Render a headline where the admin marked emphasis with *asterisks*.
     *
     * Replaces a str_replace() over two literal words ("trust", "last") that
     * the views used to hardcode. That approach broke in two ways once the
     * headline became editable: rewriting the headline silently lost the
     * italics, and because it matched substrings, "trusted" rendered as
     * "<em>trust</em>ed".
     *
     * The text is escaped first, so only the markers this method adds can
     * produce markup.
     */
    public static function emphasise(?string $text): string
    {
        $escaped = e((string) $text);

        return preg_replace('/\*([^*]+)\*/', '<em>$1</em>', $escaped);
    }
}
