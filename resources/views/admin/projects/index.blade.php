@extends('layouts.admin')

@section('title', 'Projects')

@section('content')
<div class="page-head">
  <div>
    <h1>Projects</h1>
    <p>{{ $projects->total() }} development{{ $projects->total() === 1 ? '' : 's' }}. Featured ones lead the homepage slider; the newest three of each status fill its portfolio columns.</p>
  </div>
  <div class="page-head-actions">
    @include('admin.partials.view-page', ['route' => 'projects.index'])
    <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">+ New Project</a>
  </div>
</div>

<div class="card">
  <form method="GET" action="{{ route('admin.projects.index') }}" class="card-pad" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;border-bottom:1px solid var(--line);">
    <div class="field" style="flex:1;min-width:220px;">
      <label for="pSearch">Search</label>
      <input type="search" id="pSearch" name="search" value="{{ request('search') }}" placeholder="Search by name or location…">
    </div>
    <div class="field" style="width:170px;">
      <label for="pStatus">Status</label>
      <select id="pStatus" name="status">
        <option value="">All statuses</option>
        <option value="Ongoing" @selected(request('status') === 'Ongoing')>Ongoing</option>
        <option value="Completed" @selected(request('status') === 'Completed')>Completed</option>
        <option value="Upcoming" @selected(request('status') === 'Upcoming')>Upcoming</option>
      </select>
    </div>
    <div class="field" style="width:170px;">
      <label for="pType">Type</label>
      <select id="pType" name="type">
        <option value="">All types</option>
        <option value="Residential" @selected(request('type') === 'Residential')>Residential</option>
        <option value="Commercial" @selected(request('type') === 'Commercial')>Commercial</option>
        <option value="Mixed-Use" @selected(request('type') === 'Mixed-Use')>Mixed-Use</option>
      </select>
    </div>
    <button class="btn btn-outline" type="submit">Apply</button>
    @if (request('search') || request('status') || request('type'))
      <a class="btn btn-outline" href="{{ route('admin.projects.index') }}">Clear filters</a>
    @endif
  </form>

  <div class="table-scroll">
    <table class="table" id="projectsTable">
      <thead>
        <tr>
          <th>Project</th>
          <th>Type</th>
          <th>Status</th>
          <th>Location</th>
          <th>Published</th>
          <th>Featured</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse ($projects as $project)
          <tr>
            <td>
              <div style="display:flex;align-items:center;gap:12px;">
                @if ($project->hero_image)
                  <img src="{{ $project->hero_image_url }}" alt="" style="width:44px;height:44px;border-radius:8px;object-fit:cover;flex:none;">
                @else
                  <div style="width:44px;height:44px;border-radius:8px;background:var(--surface-muted);flex:none;"></div>
                @endif
                <div>
                  <div class="cell-main">{{ $project->name }}</div>
                  <div class="cell-sub">{{ $project->location }}</div>
                </div>
              </div>
            </td>
            <td>{{ $project->type }}</td>
            <td><span class="badge badge-{{ strtolower($project->status) }}">{{ $project->status }}</span></td>
            <td>{{ $project->location }}</td>
            <td>
              <form method="POST" action="{{ route('admin.projects.update', $project) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="toggle" value="published">
                <label class="toggle">
                  <input type="checkbox" onchange="this.form.submit()" @checked($project->published) aria-label="Published">
                  <span class="track"></span>
                </label>
              </form>
            </td>
            <td>
              <form method="POST" action="{{ route('admin.projects.update', $project) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="toggle" value="featured">
                <label class="toggle">
                  <input type="checkbox" onchange="this.form.submit()" @checked($project->featured) aria-label="Featured">
                  <span class="track"></span>
                </label>
              </form>
            </td>
            <td class="cell-actions">
              <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-ghost btn-sm">Edit</a>
              <button class="btn btn-ghost btn-sm" type="button" data-delete="{{ $project->name }}" data-target="{{ route('admin.projects.destroy', $project) }}" data-modal-open="deleteModal">Delete</button>
            </td>
          </tr>
        @empty
          <tr><td colspan="7"><div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <h3>No projects match</h3><p>Try clearing the search or filters.</p>
          </div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="pagination">
    <span class="pagination-info">
      @if ($projects->total())
        Showing {{ $projects->firstItem() }}&ndash;{{ $projects->lastItem() }} of {{ $projects->total() }} projects
      @else
        No projects found
      @endif
    </span>
    <div class="pagination-nav">
      <a class="pg-btn" href="{{ $projects->previousPageUrl() ?? '#' }}" @if(!$projects->previousPageUrl()) aria-disabled="true" style="pointer-events:none;opacity:.4;" @endif aria-label="Previous page">&larr;</a>
      @for ($p = 1; $p <= $projects->lastPage(); $p++)
        <a class="pg-btn @if($p === $projects->currentPage()) active @endif" href="{{ $projects->url($p) }}">{{ $p }}</a>
      @endfor
      <a class="pg-btn" href="{{ $projects->nextPageUrl() ?? '#' }}" @if(!$projects->nextPageUrl()) aria-disabled="true" style="pointer-events:none;opacity:.4;" @endif aria-label="Next page">&rarr;</a>
    </div>
  </div>
</div>

<!-- Delete confirmation modal — generic, reused by every list screen -->
<div class="modal-overlay" id="deleteModal">
  <div class="modal">
    <div class="modal-head">
      <h3>Delete project?</h3>
      <button class="modal-close" data-modal-close aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
    </div>
    <div class="modal-body" id="deleteModalBody">
      This removes the project and its gallery, floor plans and brochure permanently. This cannot be undone.
    </div>
    <div class="modal-foot">
      <button class="btn btn-outline" data-modal-close type="button">Cancel</button>
      <form method="POST" id="deleteForm">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger" type="submit">Delete Project</button>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.querySelectorAll('[data-delete]').forEach((btn) => {
    btn.addEventListener('click', () => {
      document.getElementById('deleteModalBody').textContent =
        `Delete "${btn.dataset.delete}"? This removes its gallery, floor plans and brochure permanently. This cannot be undone.`;
      document.getElementById('deleteForm').action = btn.dataset.target;
    });
  });
</script>
@endpush
