<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

/**
 * A phone number the site shows, with the name of the desk it reaches.
 *
 * The site prints a number in eleven places, and only two of them — the footer
 * column and the Contact page — have room for a list. Everywhere else is a
 * single slot: a call button, a {phone} token, a tel: CTA. So the lookups here
 * are built so a template never has to handle "no number":
 *
 *   primary()        the one number every single-slot place uses
 *   forSlot('sales') that desk's number, or the primary when it has none
 *   footerList()     the numbers ticked for the footer column
 *
 * primary() and forSlot() always return a row — a flagged one, else the first
 * live one, else an unsaved row built from settings.phone. That last fallback
 * is what lets this ship without a content edit: the column is still there and
 * still populated.
 */
#[Fillable([
    'label', 'number', 'key', 'is_primary', 'show_in_footer', 'is_active', 'sort_order',
])]
class ContactNumber extends Model
{
    /**
     * The desks a template can ask for by name, as key => label.
     *
     * A slot exists because some piece of Blade wants *that* number rather
     * than the office one — adding a slot here means the dropdown on the admin
     * screen offers it, but something has to call forSlot() for it to matter.
     */
    public const SLOTS = [
        'sales' => 'Sales enquiries',
        'landowners' => 'Landowner enquiries',
        'partnerships' => 'Partnership desk',
    ];

    /**
     * The live list, memoised per process. The footer asks for it on every
     * page, and every single-number lookup below reads from it rather than
     * issuing a query of its own.
     */
    private static ?Collection $cache = null;

    /** The unsaved settings.phone row, built at most once. */
    private static ?self $fallback = null;

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'show_in_footer' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Every live number in display order.
     *
     * Guarded on the table existing so `migrate` on a fresh database can still
     * boot the view composers.
     */
    public static function live(): Collection
    {
        if (self::$cache === null) {
            self::$cache = Schema::hasTable('contact_numbers')
                ? static::query()->where('is_active', true)->orderBy('sort_order')->orderBy('id')->get()
                : collect();
        }

        return self::$cache;
    }

    /**
     * Drop the memoised list.
     *
     * Saving or deleting a number does this for you (see booted()) — the cache
     * would otherwise outlive the change in anything that handles more than one
     * request per process, tests included.
     */
    public static function flush(): void
    {
        self::$cache = null;
        self::$fallback = null;
    }

    protected static function booted(): void
    {
        static::saved(static fn () => static::flush());
        static::deleted(static fn () => static::flush());
    }

    /**
     * The numbers ticked for the footer's contact column.
     */
    public static function footerList(): Collection
    {
        return static::live()->where('show_in_footer', true)->values();
    }

    /**
     * The site's main number — never null, so every call button and token can
     * print it without a guard.
     */
    public static function primary(): self
    {
        return static::live()->firstWhere('is_primary', true)
            ?? static::live()->first()
            ?? static::fromSettings();
    }

    /**
     * One desk's number, falling back to the primary when that desk has no
     * number of its own. A page asking for 'sales' therefore still shows
     * something on a site that has only ever had one number.
     */
    public static function forSlot(string $key): self
    {
        return static::live()->firstWhere('key', $key) ?? static::primary();
    }

    /**
     * The number as a tel: href — digits and a leading +, nothing else.
     *
     * Six templates used to each carry their own regex for this, and two of
     * them stripped spaces but kept the dashes.
     */
    public function getTelAttribute(): string
    {
        return preg_replace('/[^\d+]/', '', (string) $this->number) ?: '';
    }

    /**
     * The pre-table number, as an unsaved row. Only reached when no live row
     * exists at all.
     */
    private static function fromSettings(): self
    {
        if (self::$fallback === null) {
            self::$fallback = new static([
                'label' => 'Phone',
                'number' => (Schema::hasTable('settings') ? Setting::first()?->phone : null) ?: '',
            ]);
        }

        return self::$fallback;
    }
}
