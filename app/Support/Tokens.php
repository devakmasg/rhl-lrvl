<?php

namespace App\Support;

use App\Models\Director;
use App\Models\Setting;

/**
 * Placeholders an admin may use inside editable copy.
 *
 * These exist because some site copy was never really static: it named the
 * managing director or the office phone number. Freezing today's value into
 * the text would mean it silently goes stale when that value changes
 * elsewhere — exactly the drift this work removes.
 */
final class Tokens
{
    public const AVAILABLE = [
        '{company}' => 'Short company name, e.g. RHL Properties',
        '{company_full}' => 'Full legal name, e.g. RHL Properties Ltd',
        '{md_name}' => "The Managing Director's name",
        '{chairman_name}' => "The Chairman's name",
        '{phone}' => 'Office phone number from Settings',
    ];

    /** Replace every {token} with its current value. */
    public static function expand(?string $text): string
    {
        $text = (string) $text;

        if (! str_contains($text, '{')) {
            return $text;
        }

        return strtr($text, [
            '{company}' => Brand::shortName(),
            '{company_full}' => Brand::name(),
            '{md_name}' => Director::managingDirector()?->name ?: 'the Managing Director',
            '{chairman_name}' => Director::chairman()?->name ?: 'our Chairman',
            '{phone}' => Setting::first()?->phone ?: '',
        ]);
    }
}
