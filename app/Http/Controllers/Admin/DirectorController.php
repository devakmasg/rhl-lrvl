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

        $director = Director::create($data);
        $this->enforceSingleManagingDirector($director);

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
        $this->enforceSingleManagingDirector($director);

        return redirect()->route('admin.directors.index')->with('status', 'Director updated.');
    }

    public function destroy(Director $director): RedirectResponse
    {
        $this->deleteUploadedFile($director->photo);
        $director->delete();

        return redirect()->route('admin.directors.index')->with('status', 'Director deleted.');
    }

    /**
     * The MD flag identifies exactly one person — the homepage teaser and the
     * MD Message page both read whoever carries it. Ticking a new director
     * clears the previous one rather than leaving two rows fighting over which
     * first() wins.
     */
    private function enforceSingleManagingDirector(Director $director): void
    {
        if (! $director->is_managing_director) {
            return;
        }

        Director::where('id', '!=', $director->id)
            ->where('is_managing_director', true)
            ->update(['is_managing_director' => false]);
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'is_managing_director' => ['nullable', 'boolean'],
            'photo' => ['nullable', 'image', 'max:5120'],
            'order' => ['nullable', 'integer', 'min:1'],
            'bio' => ['nullable', 'string'],
        ]);

        // 'photo' is set from the upload by the caller, never from the raw input.
        unset($data['photo']);

        // An unchecked checkbox sends nothing, so absence has to mean false —
        // otherwise unticking the flag would silently leave it set.
        $data['is_managing_director'] = $request->boolean('is_managing_director');

        return $data;
    }
}
