<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Awards and certifications for the Achievements page — one screen for both,
 * since they share every field and only differ in how they render.
 */
class AchievementController extends Controller
{
    public function index(): View
    {
        return view('admin.achievements.index', [
            'awards' => Achievement::kind(Achievement::AWARD)->get(),
            'certifications' => Achievement::kind(Achievement::CERTIFICATION)->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        // New entries go to the end of their own list.
        $data['sort_order'] = (int) Achievement::where('kind', $data['kind'])->max('sort_order') + 1;

        Achievement::create($data);

        return redirect()->route('admin.achievements.index')
            ->with('status', Achievement::KINDS[$data['kind']].' added.');
    }

    public function update(Request $request, Achievement $achievement): RedirectResponse
    {
        $achievement->update($this->validated($request));

        return redirect()->route('admin.achievements.index')->with('status', 'Saved.');
    }

    public function destroy(Achievement $achievement): RedirectResponse
    {
        $achievement->delete();

        return redirect()->route('admin.achievements.index')->with('status', 'Deleted.');
    }

    public function reorder(Request $request): RedirectResponse
    {
        $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer'],
        ]);

        foreach ($request->input('order') as $position => $id) {
            Achievement::where('id', $id)->update(['sort_order' => $position + 1]);
        }

        return back()->with('status', 'Order updated.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'kind' => ['required', Rule::in(array_keys(Achievement::KINDS))],
            'year' => ['nullable', 'string', 'max:20'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        // Certifications are numbered by position on the page, so a year on
        // one would be stored and never shown.
        if ($data['kind'] === Achievement::CERTIFICATION) {
            $data['year'] = null;
        }

        // An unchecked box sends nothing, so absence has to mean false.
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
