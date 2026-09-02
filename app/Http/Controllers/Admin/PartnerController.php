<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * The "Trusted Partners" logo strip shown above the footer on every page.
 *
 * The heading that sits over the strip is site-wide copy, so it lives on the
 * settings row and is edited from this screen rather than from Site Settings —
 * an editor changing the logos is the one who wants to change the label.
 */
class PartnerController extends Controller
{
    use HandlesImageUploads;

    public function index(): View
    {
        return view('admin.partners.index', [
            'partners' => Partner::ordered()->get(),
            'setting' => Setting::first(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        // New logos go to the end of the strip.
        $data['sort_order'] = (int) Partner::max('sort_order') + 1;
        $data['logo_path'] = $this->resolveImageInput($request, 'logo', 'partners');

        Partner::create($data);

        return redirect()->route('admin.partners.index')->with('status', 'Partner added.');
    }

    public function update(Request $request, Partner $partner): RedirectResponse
    {
        $data = $this->validated($request);
        $data['logo_path'] = $this->resolveImageInput($request, 'logo', 'partners', $partner->logo_path);

        $partner->update($data);

        return redirect()->route('admin.partners.index')->with('status', 'Saved.');
    }

    public function destroy(Partner $partner): RedirectResponse
    {
        $this->deleteUploadedFile($partner->logo_path);
        $partner->delete();

        return redirect()->route('admin.partners.index')->with('status', 'Partner deleted.');
    }

    /**
     * The strip's heading and its on/off switch — stored on settings, edited
     * from the same screen as the logos.
     */
    public function updateStrip(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'partners_eyebrow' => ['nullable', 'string', 'max:60'],
            'partners_heading' => ['nullable', 'string', 'max:120'],
        ]);

        $data['show_partners'] = $request->boolean('show_partners');

        Setting::first()->update($data);

        return redirect()->route('admin.partners.index')->with('status', 'Section saved.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // No SVG, for the same reason the brand logo refuses one: these
            // files are served straight off the public disk and an SVG can
            // carry script. The seeded sample marks are repo assets, not
            // uploads, so they are not affected.
            'logo' => ['nullable', 'image', 'max:2048'],
            'website' => ['nullable', 'url', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        // 'logo' is the file input; the column is set by the caller.
        unset($data['logo']);

        // An unchecked box sends nothing, so absence has to mean false.
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
