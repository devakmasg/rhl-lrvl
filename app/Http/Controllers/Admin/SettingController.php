<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\Brand;
use Illuminate\Http\Request;

class SettingController extends Controller
{
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
            'social_instagram' => ['nullable', 'url', 'max:255'],
            'social_linkedin' => ['nullable', 'url', 'max:255'],
            'social_facebook' => ['nullable', 'url', 'max:255'],
        ]);

        $setting = Setting::first();
        $setting->update($data);

        // Brand memoises the row for the request; drop it so the redirect
        // and any view rendered after this point read the saved values.
        Brand::flush();

        return redirect()->route('admin.settings.edit')->with('status', 'Settings saved.');
    }
}
