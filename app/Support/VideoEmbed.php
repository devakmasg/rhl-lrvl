<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Turns whatever a site owner pastes into a video field into a URL an <iframe>
 * can actually load.
 *
 * The address someone copies out of the browser bar is a watch page, not an
 * embed — dropping it straight into an iframe shows a refusal notice rather
 * than the video. Rather than telling an admin to hand-build an embed URL,
 * accept the four forms they are likely to paste and convert them here.
 *
 * Anything unrecognised returns null so the calling view can drop the whole
 * block, which is better than rendering a frame that will never load.
 */
final class VideoEmbed
{
    public static function url(?string $input): ?string
    {
        $input = trim((string) $input);

        if ($input === '') {
            return null;
        }

        // Already an embed URL — leave it alone.
        if (Str::contains($input, ['youtube.com/embed/', 'player.vimeo.com/video/'])) {
            return $input;
        }

        if ($id = self::youtubeId($input)) {
            return 'https://www.youtube-nocookie.com/embed/'.$id;
        }

        if (preg_match('#vimeo\.com/(?:video/)?(\d+)#i', $input, $m)) {
            return 'https://player.vimeo.com/video/'.$m[1];
        }

        return null;
    }

    /**
     * The eleven-character id out of a watch link, a youtu.be short link, or a
     * bare id typed on its own.
     */
    private static function youtubeId(string $input): ?string
    {
        if (preg_match('#(?:youtube\.com/(?:watch\?(?:.*&)?v=|shorts/|live/)|youtu\.be/)([A-Za-z0-9_-]{11})#i', $input, $m)) {
            return $m[1];
        }

        return preg_match('/^[A-Za-z0-9_-]{11}$/', $input) ? $input : null;
    }
}
