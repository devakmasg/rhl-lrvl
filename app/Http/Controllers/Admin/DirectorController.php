<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Models\Director;
use App\Models\TeamMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DirectorController extends Controller
{
    use HandlesImageUploads;

    /**
     * The directors.html mockup merges Board of Directors and Management
     * Team CRUD onto a single page, so this index also loads team members
     * for TeamMemberController's rows (see routes/admin.php note).
     */
    public function index(): View
    {
        return view('admin.directors.index', [
            'directors' => Director::orderBy('order')->orderBy('name')->get(),
            'teamMembers' => TeamMember::orderBy('order')->orderBy('name')->get(),
        ]);
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('admin.directors.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['photo'] = $this->resolveImageInput($request, 'photo', 'people');

        Director::create($data);

        return redirect()->route('admin.directors.index')->with('status', 'Director added.');
    }

    public function edit(Director $director): RedirectResponse
    {
        return redirect()->route('admin.directors.index');
    }

    public function update(Request $request, Director $director): RedirectResponse
    {
        $data = $this->validated($request);
        $data['photo'] = $this->resolveImageInput($request, 'photo', 'people', $director->photo);

        $director->update($data);

        return redirect()->route('admin.directors.index')->with('status', 'Director updated.');
    }

    public function destroy(Director $director): RedirectResponse
    {
        $this->deleteUploadedFile($director->photo);
        $director->delete();

        return redirect()->route('admin.directors.index')->with('status', 'Director deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'max:5120'],
            'order' => ['nullable', 'integer', 'min:1'],
            'bio' => ['nullable', 'string'],
        ]);

        // 'photo' is set from the upload by the caller, never from the raw input.
        unset($data['photo']);

        return $data;
    }
}
