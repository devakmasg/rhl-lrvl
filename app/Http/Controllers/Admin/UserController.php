<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Administrator-only management of the panel's user accounts. Reachable only
 * behind the admin.administrator middleware (see routes/admin.php).
 *
 * No sequence of clicks here can leave the site without an administrator. The
 * work is done by refuseSelfChange(): since nobody can deactivate, demote or
 * delete their own row, whoever is doing the damage is always an active
 * administrator who survives it. refuseLosingLastAdmin() is a backstop for that
 * reasoning rather than a check that fires today — it would start mattering the
 * moment self-changes were allowed, or a bulk action were added.
 */
class UserController extends Controller
{
    public function index(): View
    {
        $roles = Role::orderByDesc('is_admin')->orderBy('name')->get();

        return view('admin.users.index', [
            'users' => User::with('role')->orderBy('name')->get(),
            'roles' => $roles,
            // New users default to the least privileged role on offer, so that
            // creating one without touching the dropdown never hands out
            // administrator access by accident.
            'defaultRoleId' => ($roles->firstWhere('is_admin', false) ?? $roles->first())?->id,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'phone' => ['nullable', 'string', 'max:50'],
            'role_id' => ['required', Rule::exists('roles', 'id')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        // The administrator typed this password, so it is temporary by
        // definition — the new user replaces it before they can do anything.
        $data['must_change_password'] = true;

        User::create($data);

        return redirect()->route('admin.users.index')
            ->with('status', $data['name'].' can now sign in. Give them the password you set — they will be asked to choose their own.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:50'],
            'role_id' => ['required', Rule::exists('roles', 'id')],
        ]);

        if ((int) $data['role_id'] !== (int) $user->role_id && $message = $this->refuseSelfChange($user, 'change your own role')) {
            return $message;
        }

        $newRole = Role::find($data['role_id']);

        if (! $newRole->is_admin && ($message = $this->refuseLosingLastAdmin($user, 'demote'))) {
            return $message;
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('status', $user->name.' updated.');
    }

    /**
     * Set someone else's password without knowing their old one — the admin
     * counterpart to ProfileController::updatePassword, which requires it.
     */
    public function updatePassword(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => $request->input('password'),
            'must_change_password' => true,
        ]);

        return redirect()->route('admin.users.index')
            ->with('status', 'Password reset for '.$user->name.'. They will be asked to choose their own on their next sign-in.');
    }

    public function toggleActive(User $user): RedirectResponse
    {
        if ($message = $this->refuseSelfChange($user, 'deactivate your own account')) {
            return $message;
        }

        if ($user->is_active && ($message = $this->refuseLosingLastAdmin($user, 'deactivate'))) {
            return $message;
        }

        $user->update(['is_active' => ! $user->is_active]);

        return redirect()->route('admin.users.index')->with(
            'status',
            $user->name.($user->is_active ? ' can sign in again.' : ' has been deactivated and signed out.')
        );
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($message = $this->refuseSelfChange($user, 'delete your own account')) {
            return $message;
        }

        if ($message = $this->refuseLosingLastAdmin($user, 'delete')) {
            return $message;
        }

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')->with('status', $name.' removed.');
    }

    /**
     * Blocks acting on your own row. Returns a redirect to bail out with, or
     * null when the target is somebody else.
     */
    private function refuseSelfChange(User $user, string $action): ?RedirectResponse
    {
        if ($user->id !== auth()->id()) {
            return null;
        }

        return redirect()->route('admin.users.index')
            ->with('error', 'You cannot '.$action.'. Ask another administrator to do it.');
    }

    /**
     * Blocks anything that would leave the site with no active administrator.
     */
    private function refuseLosingLastAdmin(User $user, string $action): ?RedirectResponse
    {
        if (! $user->isAdmin() || ! $user->is_active) {
            return null;
        }

        $otherActiveAdmins = User::whereHas('role', fn ($query) => $query->where('is_admin', true))
            ->where('is_active', true)
            ->where('id', '!=', $user->id)
            ->count();

        if ($otherActiveAdmins > 0) {
            return null;
        }

        return redirect()->route('admin.users.index')
            ->with('error', 'You cannot '.$action.' the only active administrator. Make someone else an administrator first.');
    }
}
