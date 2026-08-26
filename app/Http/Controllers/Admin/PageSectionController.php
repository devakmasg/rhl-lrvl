<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * The headings that sit above each block on the pages which have no content
 * row of their own — Achievements, Contact, the listings, and the project and
 * news detail templates.
 *
 * Grouped by page rather than given a screen each: they are all the same three
 * or four fields, and one accordion is easier to scan than nine menu entries.
 */
class PageSectionController extends Controller
{
    public function index(): View
    {
        return view('admin.page-sections.index', [
            'groups' => PageSection::orderBy('page_key')->orderBy('sort_order')->get()->groupBy('page_key'),
        ]);
    }

    public function update(Request $request, string $pageKey): RedirectResponse
    {
        $data = $request->validate([
            'sections' => ['array'],
            'sections.*.eyebrow' => ['nullable', 'string', 'max:255'],
            'sections.*.heading' => ['nullable', 'string', 'max:255'],
            'sections.*.body' => ['nullable', 'string', 'max:2000'],
            'sections.*.link_label' => ['nullable', 'string', 'max:120'],
        ]);

        // Keyed by id, and scoped to this page, so a submitted id belonging to
        // another page cannot be written through this form.
        $sections = PageSection::where('page_key', $pageKey)->get()->keyBy('id');

        foreach ($data['sections'] ?? [] as $id => $fields) {
            $section = $sections->get((int) $id);

            if (! $section) {
                continue;
            }

            $section->update([
                'eyebrow' => $this->clean($fields['eyebrow'] ?? null),
                'heading' => $this->clean($fields['heading'] ?? null),
                'body' => $this->clean($fields['body'] ?? null),
                'link_label' => $this->clean($fields['link_label'] ?? null),
            ]);
        }

        $label = $sections->first()?->page_label ?? 'Page';

        return redirect()
            ->route('admin.page-sections.index', ['#ps-'.$pageKey])
            ->with('status', 'Saved “'.$label.'”.');
    }

    private function clean(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }
}
