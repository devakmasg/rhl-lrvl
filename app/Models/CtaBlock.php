<?php

namespace App\Models;

use App\Support\Tokens;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/**
 * The closing call-to-action band at the foot of a page.
 *
 * One row per page, keyed like page_banners, so all of them are edited from a
 * single admin screen and rendered by partials/connect.blade.php.
 */
#[Fillable(['page_key', 'label', 'eyebrow', 'heading', 'section_id', 'cards'])]
class CtaBlock extends Model
{
    /** Placeholders usable in card copy, a button label, or a button URL. */
    public const TOKENS = Tokens::AVAILABLE;

    protected function casts(): array
    {
        return [
            'cards' => 'array',
        ];
    }

    public static function forKey(string $key): ?self
    {
        return static::where('page_key', $key)->first();
    }

    /**
     * Cards with tokens expanded and button URLs resolved, ready to render.
     *
     * A card with no title and no text is dropped, so an admin can empty a
     * card to remove it without the grid rendering a blank box.
     *
     * @return array<int, array{title: string, text: string, btn_label: string, btn_url: ?string}>
     */
    public function resolvedCards(): array
    {
        $cards = [];

        foreach ($this->cards ?? [] as $card) {
            $title = self::expand($card['title'] ?? '');
            $text = self::expand($card['text'] ?? '');

            if ($title === '' && $text === '') {
                continue;
            }

            $cards[] = [
                'title' => $title,
                'text' => $text,
                'btn_label' => self::expand($card['btn_label'] ?? ''),
                'btn_url' => self::resolveUrl($card['btn_url'] ?? ''),
            ];
        }

        return $cards;
    }

    public function resolvedHeading(): string
    {
        return self::expand($this->heading ?? '');
    }

    public function resolvedEyebrow(): string
    {
        return self::expand($this->eyebrow ?? '');
    }

    /** Replace every {token} with its current value. */
    public static function expand(?string $text): string
    {
        return Tokens::expand($text);
    }

    /**
     * Turn a stored button target into an href.
     *
     * Three forms are accepted so the admin never has to paste an absolute
     * URL that would break when the domain changes:
     *
     *   "projects.index"        a route name
     *   "partners#investors"    a route name with an anchor
     *   "tel:{phone}", "/x",    anything already a URL or scheme, used as-is
     *   "https://..."
     *
     * An unknown route name returns null rather than throwing, so a typo in
     * the admin hides one button instead of returning a 500 for the page.
     */
    public static function resolveUrl(?string $target): ?string
    {
        $target = trim((string) $target);

        if ($target === '') {
            return null;
        }

        $target = self::expand($target);

        if (Str::startsWith($target, ['http://', 'https://', '//', '/', '#', 'tel:', 'mailto:'])) {
            return Str::startsWith($target, 'tel:')
                ? 'tel:'.preg_replace('/[^\d+]/', '', Str::after($target, 'tel:'))
                : $target;
        }

        [$name, $fragment] = array_pad(explode('#', $target, 2), 2, null);

        if (! Route::has($name)) {
            return null;
        }

        return route($name).($fragment ? '#'.$fragment : '');
    }
}
