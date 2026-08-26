<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CtaBlock;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * The closing call-to-action band on every page that has one — one screen for
 * all of them, since the cards share a single layout and differ only in copy.
 */
class CtaBlockController extends Controller
{
    public function index(): View
    {
        return view('admin.cta-blocks.index', [
            'blocks' => CtaBlock::orderBy('id')->get(),
        ]);
    }

    public function update(Request $request, CtaBlock $ctaBlock): RedirectResponse
    {
        $data = $request->validate([
            'eyebrow' => ['nullable', 'string', 'max:255'],
            'heading' => ['nullable', 'string', 'max:255'],
            'cards' => ['array'],
            'cards.*.title' => ['nullable', 'string', 'max:255'],
            'cards.*.text' => ['nullable', 'string', 'max:1000'],
            'cards.*.btn_label' => ['nullable', 'string', 'max:120'],
            'cards.*.btn_url' => ['nullable', 'string', 'max:2048', $this->linkTargetRule()],
        ]);

        $ctaBlock->update([
            'eyebrow' => $this->clean($data['eyebrow'] ?? null),
            'heading' => $this->clean($data['heading'] ?? null),
            'cards' => $this->cleanCards($data['cards'] ?? []),
        ]);

        return redirect()
            ->route('admin.cta-blocks.index', ['#cta-'.$ctaBlock->id])
            ->with('status', 'Saved “'.$ctaBlock->label.'”.');
    }

    /**
     * A button target is either a real route name or something already a URL.
     * Validating it here means a typo is a form error the admin can see and
     * fix, rather than a button that silently stops rendering on the page.
     */
    private function linkTargetRule(): callable
    {
        return function (string $attribute, mixed $value, callable $fail) {
            $value = trim((string) $value);

            if ($value === '') {
                return;
            }

            if (Str::startsWith($value, ['http://', 'https://', '//', '/', '#', 'tel:', 'mailto:'])) {
                return;
            }

            // Tokens are expanded at render time, so a target containing one
            // cannot be checked against the route list here.
            if (str_contains($value, '{')) {
                return;
            }

            $name = Str::before($value, '#');

            if (! Route::has($name)) {
                $fail("“{$value}” is not a page on this site. Use a page name like projects.index, or a full address starting with / or https://.");
            }
        };
    }

    /**
     * Keep both card slots positional so card 1 stays card 1, but drop a card
     * the admin emptied — the page skips those, and storing the blank keys
     * would only make the next edit look like it has content.
     *
     * @return list<array<string, string>>
     */
    private function cleanCards(array $cards): array
    {
        $clean = [];

        foreach ($cards as $card) {
            $card = array_map(fn ($v) => trim((string) $v), $card);

            if (($card['title'] ?? '') === '' && ($card['text'] ?? '') === '') {
                continue;
            }

            $clean[] = [
                'title' => $card['title'] ?? '',
                'text' => $card['text'] ?? '',
                'btn_label' => $card['btn_label'] ?? '',
                'btn_url' => $card['btn_url'] ?? '',
            ];
        }

        return $clean;
    }

    private function clean(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }
}
