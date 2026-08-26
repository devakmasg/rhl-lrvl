@extends('layouts.admin')

@section('title', 'Directors & Team')

@section('content')
<div class="page-head">
  <div>
    <h1>Board of Directors</h1>
    <p>Directors shown on directors.html and the homepage leadership strip.</p>
  </div>
  <div class="page-head-actions">
    <button class="btn btn-primary" type="button" data-modal-open="addDirectorModal">+ Add Director</button>
  </div>
</div>

<div class="card" style="margin-bottom:24px;">
  <div class="table-scroll">
    <table class="table">
      <thead><tr><th style="width:60px;">Order</th><th></th><th>Name</th><th>Role</th><th>Bio</th><th></th></tr></thead>
      <tbody>
        @forelse ($directors as $d)
          <tr>
            <td>{{ $d->order ?? '—' }}</td>
            <td><img src="{{ $d->photo_url ?: 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=100&q=80' }}" alt="" style="width:36px;height:36px;border-radius:50%;object-fit:cover;"></td>
            <td><span class="cell-main">{{ $d->name }}</span></td>
            <td>{{ $d->role }}</td>
            <td><span class="cell-sub">{{ \Illuminate\Support\Str::limit($d->bio, 60) }}</span></td>
            <td class="cell-actions">
              <button class="btn btn-ghost btn-sm" type="button" data-modal-open="editDirectorModal{{ $d->id }}">Edit</button>
              <form method="POST" action="{{ route('admin.directors.destroy', $d) }}" style="display:inline;" onsubmit="return confirm('Delete this director?');">
                @csrf
                @method('DELETE')
                <button class="btn btn-ghost btn-sm" type="submit" style="color:var(--danger);">Delete</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="6">No directors yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="page-head">
  <div>
    <h2 style="font-family:var(--serif);font-size:19px;">Management Team</h2>
    <p>Team members shown on management.html.</p>
  </div>
  <div class="page-head-actions">
    <button class="btn btn-primary" type="button" data-modal-open="addTeamModal">+ Add Team Member</button>
  </div>
</div>

<div class="card">
  <div class="table-scroll">
    <table class="table">
      <thead><tr><th style="width:60px;">Order</th><th></th><th>Name</th><th>Role</th><th>Bio</th><th></th></tr></thead>
      <tbody>
        @forelse ($teamMembers as $t)
          <tr>
            <td>{{ $t->order ?? '—' }}</td>
            <td><img src="{{ $t->photo_url ?: 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=100&q=80' }}" alt="" style="width:36px;height:36px;border-radius:50%;object-fit:cover;"></td>
            <td><span class="cell-main">{{ $t->name }}</span></td>
            <td>{{ $t->role }}</td>
            <td><span class="cell-sub">{{ \Illuminate\Support\Str::limit($t->bio, 60) }}</span></td>
            <td class="cell-actions">
              <button class="btn btn-ghost btn-sm" type="button" data-modal-open="editTeamModal{{ $t->id }}">Edit</button>
              <form method="POST" action="{{ route('admin.team.destroy', $t) }}" style="display:inline;" onsubmit="return confirm('Delete this team member?');">
                @csrf
                @method('DELETE')
                <button class="btn btn-ghost btn-sm" type="submit" style="color:var(--danger);">Delete</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="6">No team members yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- Add Director modal --}}
<div class="modal-overlay" id="addDirectorModal">
  <div class="modal">
    <div class="modal-head"><h3>Add Director</h3><button class="modal-close" data-modal-close aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button></div>
    <form method="POST" action="{{ route('admin.directors.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="modal-body" style="display:flex;flex-direction:column;gap:14px;">
        <div class="field"><label>Name</label><input type="text" name="name" required></div>
        <div class="field"><label>Role</label><input type="text" name="role" required></div>
        @include('admin.partials.image-field', ['name' => 'photo', 'label' => 'Photo'])
        <div class="field">
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
            <input type="checkbox" name="is_managing_director" value="1" style="width:auto;margin:0;">
            This is the Managing Director
          </label>
          <span class="hint">Their name, role and photo appear on the homepage teaser and the Managing Director's Message page. Only one director can be marked.</span>
        </div>
        <div class="field"><label>Display Order</label><input type="number" name="order" min="1"></div>
        <div class="field"><label>Bio</label><textarea name="bio" style="min-height:80px;"></textarea></div>
      </div>
      <div class="modal-foot"><button class="btn btn-outline" type="button" data-modal-close>Cancel</button><button class="btn btn-primary" type="submit">Save Director</button></div>
    </form>
  </div>
</div>

{{-- Edit Director modals --}}
@foreach ($directors as $d)
<div class="modal-overlay" id="editDirectorModal{{ $d->id }}">
  <div class="modal">
    <div class="modal-head"><h3>Edit Director</h3><button class="modal-close" data-modal-close aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button></div>
    <form method="POST" action="{{ route('admin.directors.update', $d) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="modal-body" style="display:flex;flex-direction:column;gap:14px;">
        <div class="field"><label>Name</label><input type="text" name="name" value="{{ $d->name }}" required></div>
        <div class="field"><label>Role</label><input type="text" name="role" value="{{ $d->role }}" required></div>
        @include('admin.partials.image-field', ['name' => 'photo', 'label' => 'Photo', 'currentUrl' => $d->photo_url])
        <div class="field">
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
            <input type="checkbox" name="is_managing_director" value="1" {{ $d->is_managing_director ? 'checked' : '' }} style="width:auto;margin:0;">
            This is the Managing Director
          </label>
          <span class="hint">Their name, role and photo appear on the homepage teaser and the Managing Director's Message page. Marking this director unmarks any other.</span>
        </div>
        <div class="field"><label>Display Order</label><input type="number" name="order" min="1" value="{{ $d->order }}"></div>
        <div class="field"><label>Bio</label><textarea name="bio" style="min-height:80px;">{{ $d->bio }}</textarea></div>
      </div>
      <div class="modal-foot"><button class="btn btn-outline" type="button" data-modal-close>Cancel</button><button class="btn btn-primary" type="submit">Save Director</button></div>
    </form>
  </div>
</div>
@endforeach

{{-- Add Team Member modal --}}
<div class="modal-overlay" id="addTeamModal">
  <div class="modal">
    <div class="modal-head"><h3>Add Team Member</h3><button class="modal-close" data-modal-close aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button></div>
    <form method="POST" action="{{ route('admin.team.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="modal-body" style="display:flex;flex-direction:column;gap:14px;">
        <div class="field"><label>Name</label><input type="text" name="name" required></div>
        <div class="field"><label>Role</label><input type="text" name="role" required></div>
        @include('admin.partials.image-field', ['name' => 'photo', 'label' => 'Photo'])
        <div class="field"><label>Display Order</label><input type="number" name="order" min="1"></div>
        <div class="field"><label>Bio</label><textarea name="bio" style="min-height:80px;"></textarea></div>
      </div>
      <div class="modal-foot"><button class="btn btn-outline" type="button" data-modal-close>Cancel</button><button class="btn btn-primary" type="submit">Save Team Member</button></div>
    </form>
  </div>
</div>

{{-- Edit Team Member modals --}}
@foreach ($teamMembers as $t)
<div class="modal-overlay" id="editTeamModal{{ $t->id }}">
  <div class="modal">
    <div class="modal-head"><h3>Edit Team Member</h3><button class="modal-close" data-modal-close aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button></div>
    <form method="POST" action="{{ route('admin.team.update', $t) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="modal-body" style="display:flex;flex-direction:column;gap:14px;">
        <div class="field"><label>Name</label><input type="text" name="name" value="{{ $t->name }}" required></div>
        <div class="field"><label>Role</label><input type="text" name="role" value="{{ $t->role }}" required></div>
        @include('admin.partials.image-field', ['name' => 'photo', 'label' => 'Photo', 'currentUrl' => $t->photo_url])
        <div class="field"><label>Display Order</label><input type="number" name="order" min="1" value="{{ $t->order }}"></div>
        <div class="field"><label>Bio</label><textarea name="bio" style="min-height:80px;">{{ $t->bio }}</textarea></div>
      </div>
      <div class="modal-foot"><button class="btn btn-outline" type="button" data-modal-close>Cancel</button><button class="btn btn-primary" type="submit">Save Team Member</button></div>
    </form>
  </div>
</div>
@endforeach
@endsection
