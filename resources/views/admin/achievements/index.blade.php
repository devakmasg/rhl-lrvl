@extends('layouts.admin')

@section('title', 'Achievements')

@section('content')
<div class="page-head">
  <div>
    <h1>Achievements</h1>
    <p>The awards and certifications listed on the Achievements page.</p>
  </div>
  <div class="page-head-actions">
    @include('admin.partials.view-page', ['route' => 'achievements'])
  </div>
</div>

@foreach ([
  ['kind' => \App\Models\Achievement::AWARD, 'rows' => $awards, 'title' => 'Awards & Recognition', 'sub' => 'Shown as a grid, each with its year.', 'add' => 'Add Award'],
  ['kind' => \App\Models\Achievement::CERTIFICATION, 'rows' => $certifications, 'title' => 'Certifications & Memberships', 'sub' => 'Shown as a numbered list — the number follows the order below.', 'add' => 'Add Certification'],
] as $group)
  @php($isAward = $group['kind'] === \App\Models\Achievement::AWARD)
  <div class="card" style="margin-bottom:20px;">
    <div class="card-pad" style="padding-bottom:0;display:flex;align-items:center;justify-content:space-between;">
      <div>
        <h2 style="font-size:15.5px;">{{ $group['title'] }}</h2>
        <div class="card-head-sub">{{ $group['sub'] }}</div>
      </div>
      <button class="btn btn-outline btn-sm" type="button" data-modal-open="addAch{{ $group['kind'] }}">+ {{ $group['add'] }}</button>
    </div>
    <div class="table-scroll" style="margin-top:14px;">
      <table class="table">
        <thead>
          <tr>
            <th style="width:70px;">{{ $isAward ? 'Year' : '#' }}</th>
            <th style="width:240px;">Title</th>
            <th>Description</th>
            <th style="width:80px;">Status</th>
            <th style="width:150px;"></th>
          </tr>
        </thead>
        <tbody>
          @forelse ($group['rows'] as $row)
            <tr>
              <td>{{ $isAward ? ($row->year ?: '—') : str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
              <td><span class="cell-main">{{ $row->title }}</span></td>
              <td><span class="cell-sub">{{ $row->description }}</span></td>
              <td><span class="badge badge-{{ $row->is_active ? 'converted' : 'closed' }}">{{ $row->is_active ? 'Live' : 'Hidden' }}</span></td>
              <td class="cell-actions">
                <button class="btn btn-ghost btn-sm" type="button" data-modal-open="editAch{{ $row->id }}">Edit</button>
                <form method="POST" action="{{ route('admin.achievements.destroy', $row) }}" style="display:inline;" onsubmit="return confirm('Delete “{{ $row->title }}”?');">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-ghost btn-sm" type="submit" style="color:var(--danger);">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="5">Nothing here yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Add --}}
  <div class="modal-overlay" id="addAch{{ $group['kind'] }}">
    <div class="modal">
      <div class="modal-head">
        <h3>{{ $group['add'] }}</h3>
        <button class="modal-close" data-modal-close aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
      </div>
      <form method="POST" action="{{ route('admin.achievements.store') }}">
        @csrf
        <input type="hidden" name="kind" value="{{ $group['kind'] }}">
        <div class="modal-body" style="display:flex;flex-direction:column;gap:14px;">
          @if ($isAward)
            <div class="field"><label>Year</label><input type="text" name="year" placeholder="2024" style="max-width:140px;"></div>
          @endif
          <div class="field"><label>Title</label><input type="text" name="title" required></div>
          <div class="field"><label>Description</label><textarea name="description" style="min-height:80px;"></textarea></div>
          <div class="field">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
              <input type="checkbox" name="is_active" value="1" checked style="width:auto;margin:0;">
              Show on the Achievements page
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

{{-- Edit modals --}}
@foreach ($awards->concat($certifications) as $row)
  @php($isAward = $row->kind === \App\Models\Achievement::AWARD)
  <div class="modal-overlay" id="editAch{{ $row->id }}">
    <div class="modal">
      <div class="modal-head">
        <h3>Edit {{ \App\Models\Achievement::KINDS[$row->kind] }}</h3>
        <button class="modal-close" data-modal-close aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
      </div>
      <form method="POST" action="{{ route('admin.achievements.update', $row) }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="kind" value="{{ $row->kind }}">
        <div class="modal-body" style="display:flex;flex-direction:column;gap:14px;">
          @if ($isAward)
            <div class="field"><label>Year</label><input type="text" name="year" value="{{ $row->year }}" style="max-width:140px;"></div>
          @endif
          <div class="field"><label>Title</label><input type="text" name="title" value="{{ $row->title }}" required></div>
          <div class="field"><label>Description</label><textarea name="description" style="min-height:80px;">{{ $row->description }}</textarea></div>
          <div class="field" style="max-width:160px;"><label>Display Order</label><input type="number" name="sort_order" min="1" value="{{ $row->sort_order }}"></div>
          <div class="field">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
              <input type="checkbox" name="is_active" value="1" {{ $row->is_active ? 'checked' : '' }} style="width:auto;margin:0;">
              Show on the Achievements page
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
