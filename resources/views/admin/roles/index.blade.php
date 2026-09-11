@extends('layouts.admin')

@section('title', 'Roles & Permissions')

@push('head')
<style>
  /* The shared .modal caps at 460px, which is too narrow for the permission
     grid. Widened here rather than in admin.css so no other modal shifts.
     min() keeps it inside the viewport on phones — a plain 720px would push
     the modal off a narrow screen, since this rule sits after admin.css and
     would otherwise beat its responsive cap. */
  .modal.modal-wide{max-width:min(720px, 100%);}
  .perm-group{border:1px solid var(--line);border-radius:8px;padding:12px 14px;margin-bottom:10px;}
  .perm-group-head{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:10px;}
  .perm-group-head h4{font-size:12px;letter-spacing:.08em;text-transform:uppercase;color:var(--stone);margin:0;}
  .perm-list{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:10px 16px;}
  .perm-item{display:flex;gap:8px;align-items:flex-start;cursor:pointer;font-size:13px;line-height:1.35;}
  .perm-item input{width:auto;margin:2px 0 0;flex:none;}
  .perm-item .perm-note{display:block;color:var(--stone);font-size:11.5px;margin-top:2px;}
  .role-perm-summary{display:flex;flex-wrap:wrap;gap:4px;}
  .role-perm-summary .badge{background:var(--surface-muted);color:var(--stone);}
</style>
@endpush

@section('content')
<div class="page-head">
  <div>
    <h1>Roles &amp; Permissions</h1>
    <p>A role is a named set of admin sections. Tick what the role may open; anything unticked is hidden from its menu and refused if the URL is typed directly.</p>
  </div>
  <div class="page-head-actions">
    <button class="btn btn-primary" type="button" data-modal-open="addRoleModal">+ Add Role</button>
  </div>
</div>

<div class="card">
  <div class="table-scroll">
    <table class="table">
      <thead><tr><th>Role</th><th>Users</th><th>Can open</th><th></th></tr></thead>
      <tbody>
        @foreach ($roles as $role)
          <tr>
            <td>
              <span class="cell-main">{{ $role->name }}</span>
              @if ($role->description)
                <span class="cell-sub">{{ $role->description }}</span>
              @endif
            </td>
            <td>{{ $role->users_count }}</td>
            <td>
              @if ($role->is_admin)
                <span class="badge badge-converted">Everything, plus Users &amp; Roles</span>
              @else
                @php($granted = $role->grantedSections())
                @if (count($granted) === 0)
                  <span class="cell-sub">Nothing yet</span>
                @elseif (count($granted) === count($sectionKeys = \App\Support\AdminSections::keys()))
                  <span class="badge badge-converted">Everything except Users &amp; Roles</span>
                @else
                  <div class="role-perm-summary">
                    @foreach ($granted as $key)
                      <span class="badge">{{ \App\Support\AdminSections::all()[$key]['label'] }}</span>
                    @endforeach
                  </div>
                @endif
              @endif
            </td>
            <td class="cell-actions">
              @if ($role->isEditable())
                <button class="btn btn-ghost btn-sm" type="button" data-modal-open="editRoleModal{{ $role->id }}">Edit</button>
                <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" style="display:inline;" onsubmit="return confirm(@js('Delete the '.$role->name.' role?'));">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-ghost btn-sm" type="submit" style="color:var(--danger);">Delete</button>
                </form>
              @else
                <span class="cell-sub">Fixed &mdash; always full access</span>
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

{{-- Add Role modal --}}
<div class="modal-overlay" id="addRoleModal">
  <div class="modal modal-wide">
    <div class="modal-head"><h3>Add Role</h3><button class="modal-close" data-modal-close aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button></div>
    <form method="POST" action="{{ route('admin.roles.store') }}">
      @csrf
      <div class="modal-body" style="display:flex;flex-direction:column;gap:14px;">
        <div class="field"><label>Role Name</label><input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Sales"></div>
        <div class="field"><label>Description</label><input type="text" name="description" value="{{ old('description') }}" placeholder="What this role is for"></div>
        @include('admin.roles.permission-fields', ['grouped' => $grouped, 'selected' => old('permissions', [])])
      </div>
      <div class="modal-foot"><button class="btn btn-outline" type="button" data-modal-close>Cancel</button><button class="btn btn-primary" type="submit">Create Role</button></div>
    </form>
  </div>
</div>

{{-- Edit Role modals --}}
@foreach ($roles as $role)
  @if ($role->isEditable())
  <div class="modal-overlay" id="editRoleModal{{ $role->id }}">
    <div class="modal modal-wide">
      <div class="modal-head"><h3>Edit Role</h3><button class="modal-close" data-modal-close aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button></div>
      <form method="POST" action="{{ route('admin.roles.update', $role) }}">
        @csrf
        @method('PUT')
        <div class="modal-body" style="display:flex;flex-direction:column;gap:14px;">
          <div class="field"><label>Role Name</label><input type="text" name="name" value="{{ $role->name }}" required></div>
          <div class="field"><label>Description</label><input type="text" name="description" value="{{ $role->description }}"></div>
          @if ($role->users_count > 0)
            <p class="cell-sub" style="margin:0;">{{ $role->users_count }} user(s) have this role. Changes take effect on their next page load.</p>
          @endif
          @include('admin.roles.permission-fields', ['grouped' => $grouped, 'selected' => $role->permissions ?? []])
        </div>
        <div class="modal-foot"><button class="btn btn-outline" type="button" data-modal-close>Cancel</button><button class="btn btn-primary" type="submit">Save Role</button></div>
      </form>
    </div>
  </div>
  @endif
@endforeach
@endsection

@push('scripts')
<script>
  // "All" ticks or clears every box in its group.
  document.querySelectorAll('[data-perm-all]').forEach((toggle) => {
    const group = toggle.closest('.perm-group');
    const boxes = group.querySelectorAll('.perm-list input[type="checkbox"]');

    const sync = () => {
      const checked = [...boxes].filter((b) => b.checked).length;
      toggle.checked = checked === boxes.length;
      toggle.indeterminate = checked > 0 && checked < boxes.length;
    };

    toggle.addEventListener('change', () => {
      boxes.forEach((b) => { b.checked = toggle.checked; });
      toggle.indeterminate = false;
    });
    boxes.forEach((b) => b.addEventListener('change', sync));
    sync();
  });
</script>
@endpush
