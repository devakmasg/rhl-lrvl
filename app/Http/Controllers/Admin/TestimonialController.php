<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    use HandlesImageUploads;

    public function index(): View
    {
        return view('admin.testimonials.index', [
            'testimonials' => Testimonial::orderBy('id')->get(),
        ]);
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('admin.testimonials.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['avatar'] = $this->resolveImageInput($request, 'avatar', 'testimonials');

        Testimonial::create($data);

        return redirect()->route('admin.testimonials.index')->with('status', 'Testimonial added.');
    }

    public function edit(Testimonial $testimonial): RedirectResponse
    {
        return redirect()->route('admin.testimonials.index');
    }

    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $data = $this->validated($request);
        $data['avatar'] = $this->resolveImageInput($request, 'avatar', 'testimonials', $testimonial->avatar);

        $testimonial->update($data);

        return redirect()->route('admin.testimonials.index')->with('status', 'Testimonial updated.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $this->deleteUploadedFile($testimonial->avatar);
        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')->with('status', 'Testimonial deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'max:5120'],
            'quote' => ['required', 'string'],
        ]);

        // 'avatar' is set from the upload by the caller, never from the raw input.
        unset($data['avatar']);

        return $data;
    }
}
