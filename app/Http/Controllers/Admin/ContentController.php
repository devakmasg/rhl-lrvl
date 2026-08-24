<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContentController extends Controller
{
    public function editHome(): View
    {
        $page = Page::where('slug', 'home')->firstOrFail();

        return view('admin.content.home', [
            'page' => $page,
            'content' => $page->content ?? [],
        ]);
    }

    public function updateHome(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'hero_headline' => ['required', 'string', 'max:255'],
            'hero_eyebrow' => ['nullable', 'string', 'max:255'],
            'hero_label' => ['nullable', 'string', 'max:255'],
            'hero_sub' => ['nullable', 'string'],
            'intro_headline' => ['required', 'string', 'max:255'],
            'intro_since_text' => ['nullable', 'string'],
            'intro_spectrum_text' => ['nullable', 'string'],
            'why_title' => ['array'],
            'why_title.*' => ['nullable', 'string', 'max:255'],
            'why_desc' => ['array'],
            'why_desc.*' => ['nullable', 'string'],
            'stat_value' => ['array'],
            'stat_value.*' => ['nullable', 'string', 'max:50'],
            'stat_label' => ['array'],
            'stat_label.*' => ['nullable', 'string', 'max:255'],
        ]);

        $whyCards = $this->zipRepeater($data['why_title'] ?? [], $data['why_desc'] ?? [], 'title', 'desc');
        $stats = $this->zipRepeater($data['stat_value'] ?? [], $data['stat_label'] ?? [], 'value', 'label');

        $content = [
            'hero_headline' => $data['hero_headline'],
            'hero_eyebrow' => $data['hero_eyebrow'] ?? '',
            'hero_label' => $data['hero_label'] ?? '',
            'hero_sub' => $data['hero_sub'] ?? '',
            'intro_headline' => $data['intro_headline'],
            'intro_since_text' => $data['intro_since_text'] ?? '',
            'intro_spectrum_text' => $data['intro_spectrum_text'] ?? '',
            'why_cards' => $whyCards,
            'stats' => $stats,
        ];

        // Query-builder update() bypasses Eloquent's array cast, so update
        // via a loaded model instance instead of Page::where(...)->update().
        Page::where('slug', 'home')->firstOrFail()->update(['content' => $content]);

        return redirect()->route('admin.content.home')->with('status', 'Homepage content saved.');
    }

    public function editAbout(): View
    {
        $page = Page::where('slug', 'about')->firstOrFail();
        $content = $page->content ?? [];

        return view('admin.content.about', [
            'page' => $page,
            'content' => $content,
            'overviewText' => implode("\n\n", $content['overview'] ?? []),
            'mdMessageText' => implode("\n\n", $content['md_message'] ?? []),
        ]);
    }

    public function updateAbout(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'headline' => ['required', 'string', 'max:255'],
            'overview' => ['nullable', 'string'],
            'milestone_year' => ['array'],
            'milestone_year.*' => ['nullable', 'string', 'max:20'],
            'milestone_text' => ['array'],
            'milestone_text.*' => ['nullable', 'string'],
            'fact_k' => ['array'],
            'fact_k.*' => ['nullable', 'string', 'max:255'],
            'fact_v' => ['array'],
            'fact_v.*' => ['nullable', 'string', 'max:255'],
            'mission' => ['nullable', 'string'],
            'vision' => ['nullable', 'string'],
            'value_title' => ['array'],
            'value_title.*' => ['nullable', 'string', 'max:255'],
            'value_desc' => ['array'],
            'value_desc.*' => ['nullable', 'string'],
            'md_name' => ['nullable', 'string', 'max:255'],
            'md_photo' => ['nullable', 'string', 'max:2048'],
            'md_quote' => ['nullable', 'string'],
            'md_message' => ['nullable', 'string'],
        ]);

        $milestones = $this->zipRepeater($data['milestone_year'] ?? [], $data['milestone_text'] ?? [], 'year', 'text');
        $facts = $this->zipRepeater($data['fact_k'] ?? [], $data['fact_v'] ?? [], 'k', 'v');
        $coreValues = $this->zipRepeater($data['value_title'] ?? [], $data['value_desc'] ?? [], 'title', 'desc');

        $content = [
            'headline' => $data['headline'],
            'overview' => $this->splitParagraphs($data['overview'] ?? ''),
            'milestones' => $milestones,
            'facts' => $facts,
            'mission' => $data['mission'] ?? '',
            'vision' => $data['vision'] ?? '',
            'core_values' => $coreValues,
            'md_name' => $data['md_name'] ?? '',
            'md_photo' => $data['md_photo'] ?? '',
            'md_quote' => $data['md_quote'] ?? '',
            'md_message' => $this->splitParagraphs($data['md_message'] ?? ''),
        ];

        Page::where('slug', 'about')->firstOrFail()->update(['content' => $content]);

        return redirect()->route('admin.content.about')->with('status', 'About page content saved.');
    }

    /**
     * Zip two parallel arrays of repeater inputs into a list of
     * associative rows, dropping rows that are entirely empty.
     */
    private function zipRepeater(array $a, array $b, string $keyA, string $keyB): array
    {
        $count = max(count($a), count($b));
        $rows = [];

        for ($i = 0; $i < $count; $i++) {
            $valA = trim((string) ($a[$i] ?? ''));
            $valB = trim((string) ($b[$i] ?? ''));

            if ($valA === '' && $valB === '') {
                continue;
            }

            $rows[] = [$keyA => $valA, $keyB => $valB];
        }

        return $rows;
    }

    /**
     * Split a textarea's contents into paragraphs on blank lines, matching
     * the convention used elsewhere (e.g. Project::body) for JSON-stored
     * paragraph arrays.
     */
    private function splitParagraphs(string $text): array
    {
        $text = str_replace("\r\n", "\n", $text);
        $parts = preg_split('/\n\s*\n/', trim($text));

        return array_values(array_filter(array_map('trim', $parts ?: []), fn ($p) => $p !== ''));
    }
}
