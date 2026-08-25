<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Models\PageBanner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageBannerController extends Controller
{
    use HandlesImageUploads;

    public function index(): View
    {
        return view('admin.page-banners.index', [
            'banners' => PageBanner::orderBy('label')->get(),
        ]);
    }

    public function update(Request $request, PageBanner $pageBanner): RedirectResponse
    {
        $data = $request->validate([
            'eyebrow' => ['nullable', 'string', 'max:255'],
            'heading' => ['nullable', 'string', 'max:255'],
            'intro' => ['nullable', 'string', 'max:1000'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'image_path' => ['nullable', 'image', 'max:5120'],
            'og_image_path' => ['nullable', 'image', 'max:5120'],
        ]);

        $data['image_path'] = $this->resolveImageInput($request, 'image_path', 'page-banners', $pageBanner->image_path);
        $data['og_image_path'] = $this->resolveImageInput($request, 'og_image_path', 'page-banners', $pageBanner->og_image_path);

        $pageBanner->update($data);

        return back()->with('status', "\"{$pageBanner->label}\" header updated.");
    }
}
