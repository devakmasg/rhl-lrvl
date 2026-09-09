<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectLocation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * The list of areas a project can be placed in, editable by the client.
 *
 * Projects reference a location by name, so this screen owns two things the
 * project form cannot: a rename has to update every project that used the old
 * name, and a location still in use cannot simply be deleted.
 */
class ProjectLocationController extends Controller
{
    public function index(): View
    {
        return view('admin.project-locations.index', [
            'locations' => ProjectLocation::ordered()->get(),
            'counts' => $this->projectCounts(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        // New areas go to the end of the list unless a position was typed in.
        $data['sort_order'] = $data['sort_order'] ?? (int) ProjectLocation::max('sort_order') + 1;

        ProjectLocation::create($data);

        return redirect()->route('admin.project-locations.index')
            ->with('status', "“{$data['name']}” added.");
    }

    public function update(Request $request, ProjectLocation $projectLocation): RedirectResponse
    {
        $data = $this->validated($request, $projectLocation);
        $oldName = $projectLocation->name;

        DB::transaction(function () use ($projectLocation, $data, $oldName) {
            $projectLocation->update($data);

            // Projects carry the name, so a rename has to reach them or they
            // would keep pointing at an area that no longer exists.
            if ($data['name'] !== $oldName) {
                Project::where('location', $oldName)->update(['location' => $data['name']]);
            }
        });

        return redirect()->route('admin.project-locations.index')->with('status', 'Saved.');
    }

    public function destroy(ProjectLocation $projectLocation): RedirectResponse
    {
        $used = Project::where('location', $projectLocation->name)->count();

        if ($used > 0) {
            return redirect()->route('admin.project-locations.index')->with(
                'error',
                "“{$projectLocation->name}” is used by {$used} ".str('project')->plural($used).
                '. Move those projects to another location first, or hide this one instead of deleting it.'
            );
        }

        $projectLocation->delete();

        return redirect()->route('admin.project-locations.index')
            ->with('status', "“{$projectLocation->name}” deleted.");
    }

    /**
     * How many projects sit in each area — read once per page rather than a
     * count query per row.
     */
    private function projectCounts(): array
    {
        return Project::query()
            ->selectRaw('location, COUNT(*) as total')
            ->groupBy('location')
            ->pluck('total', 'location')
            ->all();
    }

    private function validated(Request $request, ?ProjectLocation $location = null): array
    {
        $data = $request->validate([
            'name' => [
                'required', 'string', 'max:120',
                Rule::unique('project_locations', 'name')->ignore($location?->id),
            ],
            'sort_order' => ['nullable', 'integer', 'min:1'],
        ]);

        // An unchecked box sends nothing, so absence has to mean false.
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
