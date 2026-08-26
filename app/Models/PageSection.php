<?php

namespace App\Models;

use App\Support\SectionBag;
use App\Support\Tokens;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

/**
 * One editable section heading on a page that has no content row of its own.
 *
 * Every text field runs through Tokens, so copy like "More from {company}."
 * stays correct when the company name changes in Settings.
 */
#[Fillable([
    'page_key', 'section_key', 'page_label', 'label',
    'eyebrow', 'heading', 'body', 'link_label', 'sort_order',
])]
class PageSection extends Model
{
    public function eyebrow(): string
    {
        return Tokens::expand($this->eyebrow);
    }

    public function heading(): string
    {
        return Tokens::expand($this->heading);
    }

    public function body(): string
    {
        return Tokens::expand($this->body);
    }

    public function linkLabel(): string
    {
        return Tokens::expand($this->link_label);
    }

    /**
     * Every section for a page, wrapped so a view can read a missing key
     * without guarding — see SectionBag.
     */
    public static function bagFor(string $pageKey): SectionBag
    {
        return new SectionBag(
            static::where('page_key', $pageKey)
                ->orderBy('sort_order')
                ->get()
                ->keyBy('section_key')
                ->all()
        );
    }
}
