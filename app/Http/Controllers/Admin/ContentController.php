<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Models\ExploreSlide;
use App\Models\HeroSlide;
use App\Models\JourneyChapter;
use App\Models\Page;
use App\Models\Project;
use App\Support\HomeSections;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ContentController extends Controller
{
    use HandlesImageUploads;

    public function editHome(): View
    {
        $page = Page::where('slug', 'home')->firstOrFail();

        return view('admin.content.home', [
            'page' => $page,
            'content' => $page->content ?? [],
            'heroSlides' => HeroSlide::orderBy('sort_order')->get(),
            'journeyChapters' => JourneyChapter::orderBy('sort_order')->get(),
            'exploreSlides' => ExploreSlide::with('project')->orderBy('sort_order')->get(),
            'projects' => Project::orderBy('name')->get(['id', 'name', 'location']),
            'sectionDefs' => HomeSections::all(),
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
            'intro_since_label' => ['nullable', 'string', 'max:100'],
            'intro_since_text' => ['nullable', 'string'],
            'intro_spectrum_label' => ['nullable', 'string', 'max:100'],
            'intro_spectrum_text' => ['nullable', 'string'],
            'why_title' => ['array'],
            'why_title.*' => ['nullable', 'string', 'max:255'],
            'why_desc' => ['array'],
            'why_desc.*' => ['nullable', 'string'],
            'stat_value' => ['array'],
            'stat_value.*' => ['nullable', 'string', 'max:50'],
            'stat_label' => ['array'],
            'stat_label.*' => ['nullable', 'string', 'max:255'],
            'section_eyebrow' => ['array'],
            'section_eyebrow.*' => ['nullable', 'string', 'max:255'],
            'section_heading' => ['array'],
            'section_heading.*' => ['nullable', 'string', 'max:255'],
            'marquee_items' => ['nullable', 'string', 'max:2000'],
            'intro_image' => ['nullable', 'image', 'max:5120'],
            'intro_badge_number' => ['nullable', 'string', 'max:20'],
            'intro_badge_label' => ['nullable', 'string', 'max:100'],
            'stats_background' => ['nullable', 'image', 'max:5120'],
            'connect_title' => ['array'],
            'connect_title.*' => ['nullable', 'string', 'max:255'],
            'connect_text' => ['array'],
            'connect_text.*' => ['nullable', 'string', 'max:1000'],
            'connect_btn_label' => ['array'],
            'connect_btn_label.*' => ['nullable', 'string', 'max:255'],
            'connect_btn_url' => ['array'],
            'connect_btn_url.*' => ['nullable', 'string', 'max:2048'],
        ]);

        $page = Page::where('slug', 'home')->firstOrFail();

        $whyCards = $this->zipRepeater($data['why_title'] ?? [], $data['why_desc'] ?? [], 'title', 'desc');
        $stats = $this->zipRepeater($data['stat_value'] ?? [], $data['stat_label'] ?? [], 'value', 'label');

        // Section headings arrive keyed by section slug, so only keys we know
        // about are kept — a stray field can't write into page content.
        $sections = [];
        foreach (HomeSections::all() as $key => $definition) {
            $sections[$key] = [
                'eyebrow' => trim((string) ($data['section_eyebrow'][$key] ?? '')),
                'heading' => trim((string) ($data['section_heading'][$key] ?? '')),
            ];
        }

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
            'sections' => $sections,
            'marquee_items' => $this->splitLines($data['marquee_items'] ?? ''),
            'intro_image' => $this->resolveImageInput($request, 'intro_image', 'home', $page->get('intro_image', null)),
            'intro_badge_number' => $data['intro_badge_number'] ?? '',
            'intro_badge_label' => $data['intro_badge_label'] ?? '',
            'stats_background' => $this->resolveImageInput($request, 'stats_background', 'home', $page->get('stats_background', null)),
            'connect_cards' => $this->zipConnectCards($data),
        ];

        // Query-builder update() bypasses Eloquent's array cast, so update
        // via a loaded model instance instead of Page::where(...)->update().
        $page->update(['content' => $content]);

        return redirect()->route('admin.content.home')->with('status', 'Homepage content saved.');
    }

    /* ================= Hero slider slides ================= */

    public function storeHeroSlide(Request $request): RedirectResponse
    {
        $request->validate([
            'images' => ['required', 'array'],
            'images.*' => ['image', 'max:5120'],
        ]);

        $nextOrder = (int) HeroSlide::max('sort_order') + 1;

        foreach ($request->file('images', []) as $file) {
            $path = $file->store('hero-slides', 'public');
            HeroSlide::create([
                'image_path' => $path,
                'is_active' => true,
                'sort_order' => $nextOrder++,
            ]);
        }

        return back()->with('status', 'Hero slide(s) uploaded.');
    }

    public function updateHeroSlide(Request $request, HeroSlide $heroSlide): RedirectResponse
    {
        $data = $request->validate([
            'label' => ['nullable', 'string', 'max:255'],
        ]);

        $heroSlide->update(['label' => $data['label'] ?? '']);

        return back()->with('status', 'Slide label updated.');
    }

    public function toggleHeroSlide(HeroSlide $heroSlide): RedirectResponse
    {
        $heroSlide->update(['is_active' => ! $heroSlide->is_active]);

        return back()->with('status', $heroSlide->is_active ? 'Slide activated.' : 'Slide deactivated.');
    }

    public function destroyHeroSlide(HeroSlide $heroSlide): RedirectResponse
    {
        if ($heroSlide->image_path && Storage::disk('public')->exists($heroSlide->image_path)) {
            Storage::disk('public')->delete($heroSlide->image_path);
        }
        $heroSlide->delete();

        return back()->with('status', 'Hero slide removed.');
    }

    public function reorderHeroSlides(Request $request)
    {
        $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer'],
        ]);

        foreach ($request->input('order') as $index => $slideId) {
            HeroSlide::where('id', $slideId)->update(['sort_order' => $index]);
        }

        if ($request->wantsJson()) {
            return response()->json(['status' => 'ok']);
        }

        return back()->with('status', 'Slide order updated.');
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
            'mission_heading' => ['nullable', 'string', 'max:255'],
            'mission' => ['nullable', 'string'],
            'vision_heading' => ['nullable', 'string', 'max:255'],
            'vision' => ['nullable', 'string'],
            'value_title' => ['array'],
            'value_title.*' => ['nullable', 'string', 'max:255'],
            'value_desc' => ['array'],
            'value_desc.*' => ['nullable', 'string'],
            'md_name' => ['nullable', 'string', 'max:255'],
            'md_photo' => ['nullable', 'image', 'max:5120'],
            'md_quote' => ['nullable', 'string'],
            'md_message' => ['nullable', 'string'],
        ]);

        $page = Page::where('slug', 'about')->firstOrFail();
        $mdPhoto = $this->resolveImageInput($request, 'md_photo', 'people', $page->get('md_photo', null));

        $milestones = $this->zipRepeater($data['milestone_year'] ?? [], $data['milestone_text'] ?? [], 'year', 'text');
        $facts = $this->zipRepeater($data['fact_k'] ?? [], $data['fact_v'] ?? [], 'k', 'v');
        $coreValues = $this->zipRepeater($data['value_title'] ?? [], $data['value_desc'] ?? [], 'title', 'desc');

        $content = [
            'headline' => $data['headline'],
            'overview' => $this->splitParagraphs($data['overview'] ?? ''),
            'milestones' => $milestones,
            'facts' => $facts,
            'mission_heading' => $data['mission_heading'] ?? '',
            'mission' => $data['mission'] ?? '',
            'vision_heading' => $data['vision_heading'] ?? '',
            'vision' => $data['vision'] ?? '',
            'core_values' => $coreValues,
            'md_name' => $data['md_name'] ?? '',
            'md_photo' => $mdPhoto ?? '',
            'md_quote' => $data['md_quote'] ?? '',
            'md_message' => $this->splitParagraphs($data['md_message'] ?? ''),
        ];

        $page->update(['content' => $content]);

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
     * The closing CTA cards, which carry four parallel inputs rather than the
     * two zipRepeater handles. A row with no title is dropped.
     *
     * @return list<array{title: string, text: string, btn_label: string, btn_url: string}>
     */
    private function zipConnectCards(array $data): array
    {
        $titles = $data['connect_title'] ?? [];
        $cards = [];

        foreach ($titles as $i => $title) {
            $title = trim((string) $title);

            if ($title === '') {
                continue;
            }

            $cards[] = [
                'title' => $title,
                'text' => trim((string) ($data['connect_text'][$i] ?? '')),
                'btn_label' => trim((string) ($data['connect_btn_label'][$i] ?? '')),
                'btn_url' => trim((string) ($data['connect_btn_url'][$i] ?? '')),
            ];
        }

        return $cards;
    }

    /**
     * One value per line, blanks dropped — for simple lists like the
     * marquee phrases.
     */
    private function splitLines(string $text): array
    {
        $lines = preg_split('/\r\n|\r|\n/', trim($text)) ?: [];

        return array_values(array_filter(array_map('trim', $lines), fn ($l) => $l !== ''));
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
