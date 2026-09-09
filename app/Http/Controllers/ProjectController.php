<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectLocation;
use App\Models\ProjectUnit;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::where('published', true);

        if ($request->filled('status') && $request->query('status') !== 'all') {
            $query->where('status', ucfirst($request->query('status')));
        }

        if ($request->filled('type') && $request->query('type') !== 'all') {
            $query->where('type', ucfirst($request->query('type')));
        }

        // Locations are admin-managed and can be several words ("Bashundhara
        // R/A"), which ucfirst() would mangle, so the filter carries the name
        // lowercased — the same spelling the cards use in data-location — and
        // the comparison is made case-insensitively.
        if ($request->filled('location') && $request->query('location') !== 'all') {
            $query->whereRaw('LOWER(location) = ?', [mb_strtolower((string) $request->query('location'))]);
        }

        if ($request->filled('q')) {
            $q = $request->query('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('type', 'like', "%{$q}%")
                    ->orWhere('location', 'like', "%{$q}%")
                    ->orWhere('summary', 'like', "%{$q}%");
            });
        }

        $projects = $query->orderBy('id')->get();

        return view('pages.projects', [
            'projects' => $projects,
            'locations' => ProjectLocation::query()->live()->ordered()->get(),
        ]);
    }

    public function show(Project $project)
    {
        $all = Project::where('published', true)->orderBy('id')->get();
        $index = $all->search(fn ($p) => $p->id === $project->id);

        $prev = $all[($index - 1 + $all->count()) % $all->count()];
        $next = $all[($index + 1) % $all->count()];

        $related = $all
            ->filter(fn ($p) => $p->id !== $project->id)
            ->map(function ($p) use ($project) {
                $score = ($p->location === $project->location ? 2 : 0)
                    + ($p->type === $project->type ? 1 : 0);

                return ['project' => $p, 'score' => $score];
            })
            ->sortByDesc('score')
            ->take(3)
            ->pluck('project');

        $stages = ['Foundation', 'Structure', 'Finishing', 'Handover'];
        $thresholds = [30, 65, 90, 100];
        $currentStageIndex = null;
        if (! is_null($project->progress)) {
            foreach ($thresholds as $i => $threshold) {
                if ($project->progress <= $threshold) {
                    $currentStageIndex = $i;
                    break;
                }
            }
        }

        // Which columns the schedule shows follows what the units actually
        // carry, rather than one fixed set per kind of project.
        $unitsColumns = $project->units->isNotEmpty()
            ? ProjectUnit::columnsFor($project->units)
            : null;

        // The map itself comes off the project (map_embed_url), which falls
        // back to a name-and-area search when nothing has been pasted for it.
        return view('pages.project', compact(
            'project', 'prev', 'next', 'related', 'stages', 'thresholds',
            'currentStageIndex', 'unitsColumns'
        ));
    }
}
