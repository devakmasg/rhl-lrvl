@extends('layouts.admin')

@section('title', 'Project Locations')

@section('content')
<div class="page-head">
  <div>
    <h1>Project Locations</h1>
    <p>The areas offered in the Location dropdown on a project, and in the Location filter on the public Projects page.</p>
  </div>
  <div class="page-head-actions">
    <button class="btn btn-primary" type="button" data-modal-open="addLocation">+ Add Location</button>
  </div>
</div>

@if ($errors->any())
  {{-- The forms live in modals that are closed again by the redirect, so an
       error has to be reported up here or it would vanish silently. --}}
  <div class="form-status is-bad" style="margin-bottom:20px;">{{ $errors->first() }}</div>
@endif

<div class="card">
  <div class="card-pad" style="padding-bottom:0;">
    <h2 style="font-size:15.5px;">Areas</h2>
    <div class="card-head-sub">Order below is the order they appear in both dropdowns. Hiding an area keeps every project already in it — it just stops being offered for new ones.</div>
  </div>
  <div class="table-scroll" style="margin-top:14px;">
    <table class="table">
      <thead>
        <tr>
          <th style="width:70px;">#</th>
          <th>Name</th>
          <th style="width:120px;">Projects</th>
          <th style="width:100px;">Status</th>
          <th style="width:150px;"></th>
        </tr>
      </thead>
      <tbody>
        @forelse ($locations as $location)
          @php($used = $counts[$location->name] ?? 0)
          <tr>
            <td>{{ $location->sort_order }}</td>
            <td><span class="cell-main">{{ $location->name }}</span></td>
            <td><span class="cell-sub">{{ $used ?: '—' }}</span></td>
            <td><span class="badge badge-{{ $location->is_active ? 'converted' : 'closed' }}">{{ $location->is_active ? 'Live' : 'Hidden' }}</span></td>
            <td class="cell-actions">
              <button class="btn btn-ghost btn-sm" type="button" data-modal-open="editLocation{{ $location->id }}">Edit</button>
              @if ($used)
                {{-- Deleting would orphan those projects, so the only way out is
                     to move them first; the controller refuses it either way. --}}
                <span class="cell-sub" title="In use by {{ $used }} {{ Str::plural('project', $used) }}">In use</span>
              @else
                <form method="POST" action="{{ route('admin.project-locations.destroy', $location) }}" style="display:inline;" onsubmit="return confirm('Delete “{{ $location->name }}”?');">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-ghost btn-sm" type="submit" style="color:var(--danger);">Delete</button>
                </form>
              @endif
            </td>
          </tr>
        @empty
          <tr><td colspan="5">No locations yet — add one before creating a project.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- Add --}}
<div class="modal-overlay" id="addLocation">
  <div class="modal">
    <div class="modal-head">
      <h3>Add Location</h3>
      <button class="modal-close" data-modal-close aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
    </div>
    <form method="POST" action="{{ route('admin.project-locations.store') }}">
      @csrf
      <div class="modal-body" style="display:flex;flex-direction:column;gap:14px;">
        <div class="field">
          <label>Name</label>
          <input type="text" name="name" placeholder="e.g. Bashundhara" required>
          <span class="hint">Shown exactly as typed, on the project page and in both dropdowns.</span>
        </div>
        <div class="field" style="max-width:160px;"><label>Display Order</label><input type="number" name="sort_order" min="1" placeholder="last"></div>
        <div class="field">
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
            <input type="checkbox" name="is_active" value="1" checked style="width:auto;margin:0;">
            Offer this location
          </label>
        </div>
      </div>
      <div class="modal-foot">
        <button class="btn btn-outline" type="button" data-modal-close>Cancel</button>
        <button class="btn btn-primary" type="submit">Save</button>
      </div>
    </form>
  </div>
</div>

{{-- Edit --}}
@foreach ($locations as $location)
  <div class="modal-overlay" id="editLocation{{ $location->id }}">
    <div class="modal">
      <div class="modal-head">
        <h3>Edit Location</h3>
        <button class="modal-close" data-modal-close aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
      </div>
      <form method="POST" action="{{ route('admin.project-locations.update', $location) }}">
        @csrf
        @method('PUT')
        <div class="modal-body" style="display:flex;flex-direction:column;gap:14px;">
          <div class="field">
            <label>Name</label>
            <input type="text" name="name" value="{{ $location->name }}" required>
            @if ($counts[$location->name] ?? 0)
              <span class="hint">Renaming this also moves the {{ $counts[$location->name] }} {{ Str::plural('project', $counts[$location->name]) }} already in it.</span>
            @endif
          </div>
          <div class="field" style="max-width:160px;"><label>Display Order</label><input type="number" name="sort_order" min="1" value="{{ $location->sort_order }}"></div>
          <div class="field">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
              <input type="checkbox" name="is_active" value="1" {{ $location->is_active ? 'checked' : '' }} style="width:auto;margin:0;">
              Offer this location
            </label>
          </div>
        </div>
        <div class="modal-foot">
          <button class="btn btn-outline" type="button" data-modal-close>Cancel</button>
          <button class="btn btn-primary" type="submit">Save</button>
        </div>
      </form>
    </div>
  </div>
@endforeach
@endsection
