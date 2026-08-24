<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Director;
use App\Models\TeamMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DirectorController extends Controller
{
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

        $director->update($data);

        return redirect()->route('admin.directors.index')->with('status', 'Director updated.');
    }

    public function destroy(Director $director): RedirectResponse
    {
        $director->delete();

        return redirect()->route('admin.directors.index')->with('status', 'Director deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'photo' => ['nullable', 'string', 'max:2048'],
            'order' => ['nullable', 'integer', 'min:1'],
            'bio' => ['nullable', 'string'],
        ]);
    }
}
