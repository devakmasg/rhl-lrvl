<?php

namespace App\Http\Controllers;

use App\Models\Project;
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

        if ($request->filled('location') && $request->query('location') !== 'all') {
            $query->where('location', ucfirst($request->query('location')));
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

        return view('pages.projects', compact('projects'));
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

        $unitsColumns = null;
        $units = $project->units;
        if ($units->isNotEmpty()) {
            $unitsColumns = ! is_null($units->first()->beds)
                ? ['Unit Type', 'Size (sq ft)', 'Beds', 'Baths']
                : ['Floor Range', 'Size (sq ft)', 'Floorplate', 'Use'];
        }

        $mapQuery = "{$project->name}, {$project->location}, Dhaka, Bangladesh";

        return view('pages.project', compact(
            'project', 'prev', 'next', 'related', 'stages', 'thresholds',
            'currentStageIndex', 'unitsColumns', 'mapQuery'
        ));
    }
}
