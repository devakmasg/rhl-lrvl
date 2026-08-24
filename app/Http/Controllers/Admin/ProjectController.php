<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectAmenity;
use App\Models\ProjectFloorPlan;
use App\Models\ProjectImage;
use App\Models\ProjectUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    protected array $types = ['Residential', 'Commercial', 'Mixed-Use'];
    protected array $locations = ['Gulshan', 'Banani', 'Dhanmondi', 'Uttara', 'Bashundhara', 'Tejgaon'];
    protected array $statuses = ['Upcoming', 'Ongoing', 'Completed'];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Project::query();

        if ($search = trim((string) $request->get('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        $projects = $query->orderBy('name')->paginate(12)->withQueryString();

        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $project = new Project([
            'status' => 'Ongoing',
            'published' => true,
            'featured' => false,
        ]);

        return view('admin.projects.create', [
            'project' => $project,
            'types' => $this->types,
            'locations' => $this->locations,
            'statuses' => $this->statuses,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->validateProject($request);

        $project = DB::transaction(function () use ($request, $data) {
            $project = Project::create($data);
            $this->syncChildren($request, $project);

            return $project;
        });

        return redirect()->route('admin.projects.edit', $project)
            ->with('status', 'Project created. You can now manage its gallery and floor plans below.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('admin.projects.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $project = Project::with(['images', 'floorPlans', 'units', 'amenities'])->findOrFail($id);

        return view('admin.projects.edit', [
            'project' => $project,
            'types' => $this->types,
            'locations' => $this->locations,
            'statuses' => $this->statuses,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $project = Project::findOrFail($id);

        // Lightweight toggle path used by the index list's Published/Featured
        // switches — a plain form post, no need for a full form re-validation.
        if ($request->filled('toggle') && in_array($request->input('toggle'), ['published', 'featured'], true)) {
            $field = $request->input('toggle');
            $project->update([$field => ! $project->{$field}]);

            return back()->with('status', ucfirst($field)." updated for \"{$project->name}\".");
        }

        $data = $this->validateProject($request, $project);

        DB::transaction(function () use ($request, $project, $data) {
            $project->update($data);
            $this->syncChildren($request, $project);
        });

        return redirect()->route('admin.projects.edit', $project)
            ->with('status', 'Project updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $project = Project::with(['images', 'floorPlans'])->findOrFail($id);

        foreach ($project->images as $image) {
            $this->deleteStoredFile($image->image_path);
        }
        foreach ($project->floorPlans as $plan) {
            $this->deleteStoredFile($plan->image_path);
        }
        $this->deleteStoredFile($project->hero_image);
        $this->deleteStoredFile($project->brochure_path);

        $name = $project->name;
        $project->delete(); // cascades to images/floorPlans/units/amenities via FK

        return redirect()->route('admin.projects.index')->with('status', "\"{$name}\" deleted.");
    }

    /* ================= Gallery management (A2.3) ================= */

    public function storeImage(Request $request, Project $project)
    {
        $request->validate([
            'images' => ['required', 'array'],
            'images.*' => ['image', 'max:5120'],
        ]);

        $hasFeatured = $project->images()->where('is_featured', true)->exists();
        $nextOrder = (int) $project->images()->max('sort_order') + 1;

        foreach ($request->file('images', []) as $file) {
            $path = $file->store('projects', 'public');
            $project->images()->create([
                'image_path' => $path,
                'is_featured' => ! $hasFeatured,
                'sort_order' => $nextOrder++,
            ]);
            $hasFeatured = true;
        }

        return back()->with('status', 'Gallery images uploaded.');
    }

    public function destroyImage(Project $project, ProjectImage $image)
    {
        abort_unless($image->project_id === $project->id, 404);

        $this->deleteStoredFile($image->image_path);
        $wasFeatured = $image->is_featured;
        $image->delete();

        if ($wasFeatured) {
            $next = $project->images()->orderBy('sort_order')->first();
            $next?->update(['is_featured' => true]);
        }

        return back()->with('status', 'Image removed.');
    }

    public function featureImage(Project $project, ProjectImage $image)
    {
        abort_unless($image->project_id === $project->id, 404);

        $project->images()->update(['is_featured' => false]);
        $image->update(['is_featured' => true]);

        return back()->with('status', 'Featured image updated.');
    }

    public function reorderImages(Request $request, Project $project)
    {
        $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer'],
        ]);

        foreach ($request->input('order') as $index => $imageId) {
            ProjectImage::where('id', $imageId)->where('project_id', $project->id)
                ->update(['sort_order' => $index]);
        }

        if ($request->wantsJson()) {
            return response()->json(['status' => 'ok']);
        }

        return back()->with('status', 'Gallery order updated.');
    }

    /* ================= Floor plans (A2.4) ================= */

    public function storeFloorPlan(Request $request, Project $project)
    {
        $request->validate([
            'floor_plan' => ['required', 'image', 'max:5120'],
            'label' => ['nullable', 'string', 'max:255'],
        ]);

        $path = $request->file('floor_plan')->store('projects/floor-plans', 'public');
        $nextOrder = (int) $project->floorPlans()->max('sort_order') + 1;

        $project->floorPlans()->create([
            'image_path' => $path,
            'label' => $request->input('label'),
            'sort_order' => $nextOrder,
        ]);

        return back()->with('status', 'Floor plan uploaded.');
    }

    public function destroyFloorPlan(Project $project, ProjectFloorPlan $floorPlan)
    {
        abort_unless($floorPlan->project_id === $project->id, 404);

        $this->deleteStoredFile($floorPlan->image_path);
        $floorPlan->delete();

        return back()->with('status', 'Floor plan removed.');
    }

    /* ================= Helpers ================= */

    protected function validateProject(Request $request, ?Project $project = null): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable', 'string', 'max:255', 'regex:/^[a-z0-9-]+$/',
                Rule::unique('projects', 'slug')->ignore($project?->id),
            ],
            'type' => ['required', Rule::in($this->types)],
            'location' => ['required', Rule::in($this->locations)],
            'status' => ['required', Rule::in($this->statuses)],
            'progress' => ['nullable', 'integer', 'min:0', 'max:100'],
            'summary' => ['required', 'string'],
            'body' => ['nullable', 'string'],
            'hero_image' => ['nullable', 'image', 'max:5120'],
            'brochure' => ['nullable', 'mimes:pdf', 'max:10240'],
            'fact_keys' => ['nullable', 'array'],
            'fact_keys.*' => ['nullable', 'string', 'max:255'],
            'fact_values' => ['nullable', 'array'],
            'fact_values.*' => ['nullable', 'string', 'max:255'],
            'features' => ['nullable', 'array'],
            'features.*' => ['nullable', 'string', 'max:255'],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['nullable', 'string', 'max:255'],
            'unit_type' => ['nullable', 'array'],
            'unit_type.*' => ['nullable', 'string', 'max:255'],
            'size_sqft' => ['nullable', 'array'],
            'beds' => ['nullable', 'array'],
            'baths' => ['nullable', 'array'],
            'floorplate' => ['nullable', 'array'],
            'unit_use' => ['nullable', 'array'],
        ];

        $validated = $request->validate($rules);

        $slug = $validated['slug'] ?? null;
        if (! $slug) {
            $slug = Str::slug($validated['name']);
        }
        // Guarantee uniqueness even if the auto-generated slug collides.
        $base = $slug;
        $i = 1;
        while (Project::where('slug', $slug)->when($project, fn ($q) => $q->where('id', '!=', $project->id))->exists()) {
            $slug = $base.'-'.(++$i);
        }

        $paragraphs = collect(preg_split('/\r\n|\r|\n/', (string) $request->input('body')))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values();

        $facts = [];
        $keys = $request->input('fact_keys', []);
        $values = $request->input('fact_values', []);
        foreach ($keys as $i => $key) {
            $key = trim((string) $key);
            $value = trim((string) ($values[$i] ?? ''));
            if ($key !== '' && $value !== '') {
                $facts[$key] = $value;
            }
        }

        $features = collect($request->input('features', []))
            ->map(fn ($v) => trim((string) $v))
            ->filter()
            ->values()
            ->all();

        $data = [
            'name' => $validated['name'],
            'slug' => $slug,
            'type' => $validated['type'],
            'location' => $validated['location'],
            'status' => $validated['status'],
            'progress' => $validated['progress'] ?? null,
            'summary' => $validated['summary'],
            'body' => $paragraphs->implode("\n\n"),
            'facts' => $facts,
            'features' => $features,
            'published' => $request->boolean('published'),
            'featured' => $request->boolean('featured'),
        ];

        if ($request->hasFile('hero_image')) {
            if ($project?->hero_image) {
                $this->deleteStoredFile($project->hero_image);
            }
            $data['hero_image'] = $request->file('hero_image')->store('projects/hero', 'public');
        }

        if ($request->hasFile('brochure')) {
            if ($project?->brochure_path) {
                $this->deleteStoredFile($project->brochure_path);
            }
            $data['brochure_path'] = $request->file('brochure')->store('brochures', 'public');
        }

        return $data;
    }

    protected function syncChildren(Request $request, Project $project): void
    {
        // Amenities — simplest correct approach is replace-all-on-save.
        $project->amenities()->delete();
        $amenities = collect($request->input('amenities', []))
            ->map(fn ($v) => trim((string) $v))
            ->filter()
            ->values();
        foreach ($amenities as $i => $text) {
            $project->amenities()->create(['text' => $text, 'sort_order' => $i]);
        }

        // Units — replace-all-on-save, keep whichever residential/commercial
        // columns were filled for that row.
        $project->units()->delete();
        $types = $request->input('unit_type', []);
        $sizes = $request->input('size_sqft', []);
        $beds = $request->input('beds', []);
        $baths = $request->input('baths', []);
        $floorplates = $request->input('floorplate', []);
        $uses = $request->input('unit_use', []);

        $order = 0;
        foreach ($types as $i => $unitType) {
            $unitType = trim((string) $unitType);
            if ($unitType === '') {
                continue;
            }
            ProjectUnit::create([
                'project_id' => $project->id,
                'unit_type' => $unitType,
                'size_sqft' => trim((string) ($sizes[$i] ?? '')) ?: null,
                'beds' => is_numeric($beds[$i] ?? null) ? (int) $beds[$i] : null,
                'baths' => is_numeric($baths[$i] ?? null) ? (int) $baths[$i] : null,
                'floorplate' => trim((string) ($floorplates[$i] ?? '')) ?: null,
                'use' => trim((string) ($uses[$i] ?? '')) ?: null,
                'sort_order' => $order++,
            ]);
        }
    }

    protected function deleteStoredFile(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
