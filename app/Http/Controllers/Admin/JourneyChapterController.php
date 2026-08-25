<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Models\JourneyChapter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class JourneyChapterController extends Controller
{
    use HandlesImageUploads;

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $data['image_path'] = $this->resolveImageInput($request, 'image_path', 'journey');
        $data['video_path'] = $this->resolveVideoInput($request);
        $data['sort_order'] = (int) JourneyChapter::max('sort_order') + 1;
        $data['is_active'] = $request->boolean('is_active', true);

        JourneyChapter::create($data);

        return back()->with('status', 'Journey chapter added.');
    }

    public function update(Request $request, JourneyChapter $journeyChapter): RedirectResponse
    {
        $data = $this->validated($request);

        $data['image_path'] = $this->resolveImageInput($request, 'image_path', 'journey', $journeyChapter->image_path);
        $data['video_path'] = $this->resolveVideoInput($request, $journeyChapter->video_path);
        $data['is_active'] = $request->boolean('is_active');

        $journeyChapter->update($data);

        return back()->with('status', 'Journey chapter updated.');
    }

    public function destroy(JourneyChapter $journeyChapter): RedirectResponse
    {
        $this->deleteUploadedFile($journeyChapter->image_path);
        $this->deleteUploadedFile($journeyChapter->video_path);
        $journeyChapter->delete();

        return back()->with('status', 'Journey chapter removed.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer'],
        ]);

        foreach ($request->input('order') as $index => $id) {
            JourneyChapter::where('id', $id)->update(['sort_order' => $index]);
        }

        if ($request->wantsJson()) {
            return response()->json(['status' => 'ok']);
        }

        return back()->with('status', 'Chapter order updated.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'media_type' => ['required', Rule::in(['image', 'video'])],
            'kicker' => ['nullable', 'string', 'max:255'],
            'heading' => ['required', 'string', 'max:255'],
            'body' => ['nullable', 'string', 'max:2000'],
            'link_label' => ['nullable', 'string', 'max:255'],
            'link_url' => ['nullable', 'string', 'max:2048'],
            'image_path' => ['nullable', 'image', 'max:5120'],
            'video_path' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm', 'max:51200'],
        ]);

        // Both media fields are set from the uploads by the caller.
        unset($data['image_path'], $data['video_path']);

        return $data;
    }

    /**
     * Same contract as resolveImageInput, but the storage folder and the
     * allowed types differ, and clips are large enough that replacing one
     * should always drop the old file.
     */
    private function resolveVideoInput(Request $request, ?string $current = null): ?string
    {
        if ($request->hasFile('video_path')) {
            $this->deleteUploadedFile($current);

            return $request->file('video_path')->store('journey/videos', 'public');
        }

        if ($request->boolean('video_path_remove')) {
            $this->deleteUploadedFile($current);

            return null;
        }

        return $current;
    }
}
