<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Support\AdminSections;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Administrator-only management of roles and what each one may open.
 *
 * The Administrator role itself is not editable or deletable: it is the thing
 * guaranteeing somebody can always reach Users, so there is no screen here that
 * can quietly strip the site of its way back in.
 */
class RoleController extends Controller
{
    public function index(): View
    {
        return view('admin.roles.index', [
            'roles' => Role::withCount('users')->orderByDesc('is_admin')->orderBy('name')->get(),
            'grouped' => AdminSections::grouped(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        Role::create([
            'name' => $data['name'],
            'slug' => $this->uniqueSlug($data['name']),
            'description' => $data['description'] ?? null,
            'is_admin' => false,
            'is_system' => false,
            'permissions' => $this->permissions($request),
        ]);

        return redirect()->route('admin.roles.index')->with('status', $data['name'].' role created.');
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        if (! $role->isEditable()) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'The Administrator role always has full access and cannot be changed.');
        }

        $data = $this->validated($request, $role);

        $role->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'permissions' => $this->permissions($request),
        ]);

        return redirect()->route('admin.roles.index')->with('status', $role->name.' updated.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        if (! $role->isEditable() || $role->is_system) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'The Administrator role cannot be deleted.');
        }

        if ($role->users()->exists()) {
            return redirect()->route('admin.roles.index')->with(
                'error',
                'The '.$role->name.' role still has '.$role->users()->count().' user(s). Move them to another role first.'
            );
        }

        $name = $role->name;
        $role->delete();

        return redirect()->route('admin.roles.index')->with('status', $name.' role deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Role $role = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->ignore($role?->id)],
            'description' => ['nullable', 'string', 'max:255'],
            'permissions' => ['array'],
            'permissions.*' => [Rule::in(AdminSections::keys())],
        ]);
    }

    /**
     * The ticked sections, ordered as the registry lists them and stripped of
     * anything unrecognised.
     *
     * @return array<int, string>
     */
    private function permissions(Request $request): array
    {
        return array_values(array_intersect(
            AdminSections::keys(),
            (array) $request->input('permissions', [])
        ));
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'role';
        $slug = $base;
        $suffix = 2;

        while (Role::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
}
