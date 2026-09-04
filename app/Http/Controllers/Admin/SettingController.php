<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\Brand;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    use HandlesImageUploads;

    /**
     * Show the settings form.
     */
    public function edit()
    {
        $setting = Setting::first();

        return view('admin.settings.edit', compact('setting'));
    }

    /**
     * Update the singleton settings row.
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'site_name' => ['required', 'string', 'max:120'],
            'site_short_name' => ['nullable', 'string', 'max:120'],
            'brand_mark' => ['required', 'string', 'max:40'],
            'brand_mark_sub' => ['nullable', 'string', 'max:60'],
            // No SVG: these files are served straight off the public disk, and
            // an SVG can carry script. PNG/WebP cover a logo either way.
            'logo_path' => ['nullable', 'image', 'max:2048'],
            'logo_dark_path' => ['nullable', 'image', 'max:2048'],
            // .ico is a real favicon format but not covered by the 'image'
            // rule, hence the explicit mime list instead. Small cap — a
            // favicon is never legitimately more than a few KB.
            'favicon_path' => ['nullable', 'mimes:ico,png,jpg,jpeg,webp', 'max:512'],
            'show_wordmark' => ['nullable', 'boolean'],
            'meta_description' => ['nullable', 'string', 'max:300'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'whatsapp' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'],
            'hours_weekday' => ['required', 'string', 'max:100'],
            'hours_saturday' => ['required', 'string', 'max:100'],
            'hours_friday' => ['required', 'string', 'max:100'],
            'map_query' => ['required', 'string', 'max:255'],
            'footer_blurb' => ['nullable', 'string', 'max:1000'],
            'footer_contact_heading' => ['nullable', 'string', 'max:60'],
            'footer_follow_heading' => ['nullable', 'string', 'max:60'],
            'footer_rights' => ['nullable', 'string', 'max:120'],
            'footer_credit' => ['nullable', 'string', 'max:120'],
            'nav_cta_label' => ['nullable', 'string', 'max:40'],
            'partners_eyebrow' => ['nullable', 'string', 'max:60'],
            'partners_heading' => ['nullable', 'string', 'max:120'],
            'show_partners' => ['nullable', 'boolean'],
        ] + $this->socialRules(), [], $this->socialAttributes());

        $setting = Setting::first();

        // The two logo columns hold a path, never the raw upload — and an
        // unticked checkbox sends nothing, so absence has to mean false.
        $data['logo_path'] = $this->resolveImageInput($request, 'logo_path', 'brand', $setting->logo_path);
        $data['logo_dark_path'] = $this->resolveImageInput($request, 'logo_dark_path', 'brand', $setting->logo_dark_path);
        $data['favicon_path'] = $this->resolveImageInput($request, 'favicon_path', 'brand', $setting->favicon_path);
        $data['show_wordmark'] = $request->boolean('show_wordmark');
        $data['show_partners'] = $request->boolean('show_partners');

        $setting->update($data);

        // Brand memoises the row for the request; drop it so the redirect
        // and any view rendered after this point read the saved values.
        Brand::flush();

        return redirect()->route('admin.settings.edit')->with('status', 'Settings saved.');
    }

    /**
     * One rule per platform in Setting::SOCIALS, so adding a platform there
     * needs no matching edit here.
     */
    /**
     * Platform names for the error messages — without these an editor sees
     * "the social tiktok field must be a valid URL".
     */
    private function socialAttributes(): array
    {
        return array_map(fn (array $platform) => $platform['label'], Setting::SOCIALS);
    }

    private function socialRules(): array
    {
        return array_fill_keys(
            array_keys(Setting::SOCIALS),
            ['nullable', 'url', 'max:255'],
        );
    }
}
