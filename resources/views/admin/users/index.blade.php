@extends('layouts.admin')

@section('title', 'Users')

@section('content')
<div class="page-head">
  <div>
    <h1>Users</h1>
    <p>Who can sign in to this admin panel, and which role each of them holds.</p>
  </div>
  <div class="page-head-actions">
    <button class="btn btn-primary" type="button" data-modal-open="addUserModal">+ Add User</button>
  </div>
</div>

<div class="card">
  <div class="table-scroll">
    <table class="table">
      <thead><tr><th></th><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th></th></tr></thead>
      <tbody>
        @foreach ($users as $u)
          <tr>
            <td><img src="{{ $u->avatar ? asset('storage/'.$u->avatar) : 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=100&q=80' }}" alt="" style="width:36px;height:36px;border-radius:50%;object-fit:cover;"></td>
            <td>
              <span class="cell-main">{{ $u->name }}</span>
              @if ($u->id === auth()->id())
                <span class="cell-sub">That&rsquo;s you</span>
              @elseif ($u->must_change_password)
                <span class="cell-sub">Temporary password &mdash; not yet changed</span>
              @endif
            </td>
            <td><span class="cell-sub">{{ $u->email }}</span></td>
            <td>{{ $u->role_label }}</td>
            <td><span class="badge badge-{{ $u->is_active ? 'converted' : 'closed' }}">{{ $u->is_active ? 'Active' : 'Inactive' }}</span></td>
            <td class="cell-actions">
              <button class="btn btn-ghost btn-sm" type="button" data-modal-open="editUserModal{{ $u->id }}">Edit</button>
              <button class="btn btn-ghost btn-sm" type="button" data-modal-open="passwordUserModal{{ $u->id }}">Reset Password</button>
              @if ($u->id !== auth()->id())
                <form method="POST" action="{{ route('admin.users.toggle', $u) }}" style="display:inline;" onsubmit="return confirm(@js($u->is_active ? 'Deactivate '.$u->name.'? They will be signed out immediately.' : 'Let '.$u->name.' sign in again?'));">
                  @csrf
                  <button class="btn btn-ghost btn-sm" type="submit">{{ $u->is_active ? 'Deactivate' : 'Activate' }}</button>
                </form>
                <form method="POST" action="{{ route('admin.users.destroy', $u) }}" style="display:inline;" onsubmit="return confirm(@js('Delete '.$u->name.'? This cannot be undone — deactivate them instead if they might come back.'));">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-ghost btn-sm" type="submit" style="color:var(--danger);">Delete</button>
                </form>
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

{{-- Add User modal --}}
<div class="modal-overlay" id="addUserModal">
  <div class="modal">
    <div class="modal-head"><h3>Add User</h3><button class="modal-close" data-modal-close aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button></div>
    <form method="POST" action="{{ route('admin.users.store') }}">
      @csrf
      <div class="modal-body" style="display:flex;flex-direction:column;gap:14px;">
        <div class="field"><label>Full Name</label><input type="text" name="name" value="{{ old('name') }}" required></div>
        <div class="field"><label>Email</label><input type="email" name="email" value="{{ old('email') }}" required autocomplete="off"><span class="hint">This is what they sign in with.</span></div>
        <div class="field"><label>Phone</label><input type="text" name="phone" value="{{ old('phone') }}"></div>
        <div class="field">
          <label>Role</label>
          <select name="role_id">
            @foreach ($roles as $role)
              <option value="{{ $role->id }}" @selected((int) old('role_id', $defaultRoleId) === $role->id)>{{ $role->name }}</option>
            @endforeach
          </select>
          <span class="hint">What each role can open is set on <a href="{{ route('admin.roles.index') }}">Roles &amp; Permissions</a>.</span>
        </div>
        <div class="field"><label>Temporary Password</label><input type="password" name="password" required minlength="8" autocomplete="new-password"><span class="hint">At least 8 characters. Tell them this password &mdash; they will be asked to choose their own when they first sign in.</span></div>
        <div class="field"><label>Confirm Password</label><input type="password" name="password_confirmation" required minlength="8" autocomplete="new-password"></div>
        <div class="field">
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
            <input type="checkbox" name="is_active" value="1" checked style="width:auto;margin:0;">
            Active &mdash; can sign in
          </label>
        </div>
      </div>
      <div class="modal-foot"><button class="btn btn-outline" type="button" data-modal-close>Cancel</button><button class="btn btn-primary" type="submit">Create User</button></div>
    </form>
  </div>
</div>

{{-- Edit User + Reset Password modals --}}
@foreach ($users as $u)
<div class="modal-overlay" id="editUserModal{{ $u->id }}">
  <div class="modal">
    <div class="modal-head"><h3>Edit User</h3><button class="modal-close" data-modal-close aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button></div>
    <form method="POST" action="{{ route('admin.users.update', $u) }}">
      @csrf
      @method('PUT')
      <div class="modal-body" style="display:flex;flex-direction:column;gap:14px;">
        <div class="field"><label>Full Name</label><input type="text" name="name" value="{{ $u->name }}" required></div>
        <div class="field"><label>Email</label><input type="email" name="email" value="{{ $u->email }}" required></div>
        <div class="field"><label>Phone</label><input type="text" name="phone" value="{{ $u->phone }}"></div>
        <div class="field">
          <label>Role</label>
          @if ($u->id === auth()->id())
            {{-- A disabled select is not submitted, so the real value rides along
                 in the hidden input; the controller refuses the change anyway. --}}
            <select disabled>
              <option>{{ $u->role_label }}</option>
            </select>
            <input type="hidden" name="role_id" value="{{ $u->role_id }}">
            <span class="hint">You cannot change your own role. Another administrator can.</span>
          @else
            <select name="role_id">
              @foreach ($roles as $role)
                <option value="{{ $role->id }}" @selected($u->role_id === $role->id)>{{ $role->name }}</option>
              @endforeach
            </select>
          @endif
        </div>
      </div>
      <div class="modal-foot"><button class="btn btn-outline" type="button" data-modal-close>Cancel</button><button class="btn btn-primary" type="submit">Save Changes</button></div>
    </form>
  </div>
</div>

<div class="modal-overlay" id="passwordUserModal{{ $u->id }}">
  <div class="modal">
    <div class="modal-head"><h3>Reset Password</h3><button class="modal-close" data-modal-close aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button></div>
    <form method="POST" action="{{ route('admin.users.password', $u) }}">
      @csrf
      @method('PUT')
      <div class="modal-body" style="display:flex;flex-direction:column;gap:14px;">
        <p class="cell-sub" style="margin:0;">Set a new password for <strong>{{ $u->name }}</strong> and pass it on to them. They will be asked to choose their own the next time they sign in.</p>
        <div class="field"><label>New Password</label><input type="password" name="password" required minlength="8" autocomplete="new-password"><span class="hint">At least 8 characters.</span></div>
        <div class="field"><label>Confirm Password</label><input type="password" name="password_confirmation" required minlength="8" autocomplete="new-password"></div>
      </div>
      <div class="modal-foot"><button class="btn btn-outline" type="button" data-modal-close>Cancel</button><button class="btn btn-primary" type="submit">Reset Password</button></div>
    </form>
  </div>
</div>
@endforeach
@endsection
