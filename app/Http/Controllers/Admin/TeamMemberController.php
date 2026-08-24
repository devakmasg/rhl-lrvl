<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    /**
     * There is no dedicated admin.team.index page — team management happens
     * entirely from within admin/directors/index.blade.php (Phase 2 merge
     * decision, see directors.html). All actions redirect back there.
     */
    public function index(): RedirectResponse
    {
        return redirect()->route('admin.directors.index');
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('admin.directors.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        TeamMember::create($data);

        return redirect()->route('admin.directors.index')->with('status', 'Team member added.');
    }

    public function edit(TeamMember $team): RedirectResponse
    {
        return redirect()->route('admin.directors.index');
    }

    public function update(Request $request, TeamMember $team): RedirectResponse
    {
        $data = $this->validated($request);

        $team->update($data);

        return redirect()->route('admin.directors.index')->with('status', 'Team member updated.');
    }

    public function destroy(TeamMember $team): RedirectResponse
    {
        $team->delete();

        return redirect()->route('admin.directors.index')->with('status', 'Team member deleted.');
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
