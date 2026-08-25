<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Models\ExploreSlide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExploreSlideController extends Controller
{
    use HandlesImageUploads;

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $data['image_path'] = $this->resolveImageInput($request, 'image_path', 'explore');
        $data['video_path'] = $this->resolveVideoInput($request);
        $data['sort_order'] = (int) ExploreSlide::max('sort_order') + 1;
        $data['is_active'] = $request->boolean('is_active', true);

        ExploreSlide::create($data);

        return back()->with('status', 'Explore slide added.');
    }

    public function update(Request $request, ExploreSlide $exploreSlide): RedirectResponse
    {
        $data = $this->validated($request);

        $data['image_path'] = $this->resolveImageInput($request, 'image_path', 'explore', $exploreSlide->image_path);
        $data['video_path'] = $this->resolveVideoInput($request, $exploreSlide->video_path);
        $data['is_active'] = $request->boolean('is_active');

        $exploreSlide->update($data);

        return back()->with('status', 'Explore slide updated.');
    }

    public function destroy(ExploreSlide $exploreSlide): RedirectResponse
    {
        $this->deleteUploadedFile($exploreSlide->image_path);
        $this->deleteUploadedFile($exploreSlide->video_path);
        $exploreSlide->delete();

        return back()->with('status', 'Explore slide removed.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer'],
        ]);

        foreach ($request->input('order') as $index => $id) {
            ExploreSlide::where('id', $id)->update(['sort_order' => $index]);
        }

        if ($request->wantsJson()) {
            return response()->json(['status' => 'ok']);
        }

        return back()->with('status', 'Slide order updated.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'project_id' => ['nullable', 'integer', 'exists:projects,id'],
            'media_type' => ['required', Rule::in(['image', 'video'])],
            'category' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'image_path' => ['nullable', 'image', 'max:5120'],
            'video_path' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm', 'max:51200'],
        ]);

        unset($data['image_path'], $data['video_path']);

        $data['project_id'] = $data['project_id'] ?? null;

        return $data;
    }

    private function resolveVideoInput(Request $request, ?string $current = null): ?string
    {
        if ($request->hasFile('video_path')) {
            $this->deleteUploadedFile($current);

            return $request->file('video_path')->store('explore/videos', 'public');
        }

        if ($request->boolean('video_path_remove')) {
            $this->deleteUploadedFile($current);

            return null;
        }

        return $current;
    }
}
