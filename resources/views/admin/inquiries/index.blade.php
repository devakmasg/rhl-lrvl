@extends('layouts.admin')

@section('title', 'Inquiries')

@section('content')
<div class="page-head">
  <div>
    <h1>Inquiries</h1>
    <p>All leads submitted through the public site's enquiry forms.</p>
  </div>
</div>

<div class="card">
  <form method="GET" action="{{ route('admin.inquiries.index') }}" class="card-pad" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;border-bottom:1px solid var(--line);">
    <div class="field" style="flex:1;min-width:220px;">
      <label for="iSearch">Search</label>
      <input type="search" id="iSearch" name="search" value="{{ request('search') }}" placeholder="Search by name, phone or email…">
    </div>
    <div class="field" style="width:180px;">
      <label for="iStatus">Status</label>
      <select id="iStatus" name="status">
        <option value="">All statuses</option>
        <option value="new" @selected(request('status') === 'new')>New</option>
        <option value="contacted" @selected(request('status') === 'contacted')>Contacted</option>
        <option value="follow-up" @selected(request('status') === 'follow-up')>Follow-up</option>
        <option value="converted" @selected(request('status') === 'converted')>Converted</option>
        <option value="closed" @selected(request('status') === 'closed')>Closed</option>
      </select>
    </div>
    <div class="field" style="width:170px;">
      <label for="iDate">Received</label>
      <select id="iDate" name="date">
        <option value="">All time</option>
        <option value="7" @selected(request('date') === '7')>Last 7 days</option>
        <option value="30" @selected(request('date') === '30')>Last 30 days</option>
      </select>
    </div>
    <button class="btn btn-primary" type="submit">Filter</button>
    <a class="btn btn-outline" href="{{ route('admin.inquiries.index') }}">Clear filters</a>
  </form>

  <div class="table-scroll">
    <table class="table" id="inquiriesTable">
      <thead>
        <tr>
          <th>Name</th>
          <th>Contact</th>
          <th>Interested Project</th>
          <th>Status</th>
          <th>Received</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse ($inquiries as $inquiry)
          <tr>
            <td>
              <div class="cell-main">{{ $inquiry->name }}</div>
              <div class="cell-sub">{{ $inquiry->reference }}</div>
            </td>
            <td>
              <div>{{ $inquiry->phone }}</div>
              <div class="cell-sub">{{ $inquiry->email }}</div>
            </td>
            <td>{{ $inquiry->project_name }}</td>
            <td><span class="badge badge-{{ $inquiry->status }}">{{ ucfirst(str_replace('-', ' ', $inquiry->status)) }}</span></td>
            <td>{{ $inquiry->created_at->format('d M Y') }}</td>
            <td class="cell-actions">
              <a href="{{ route('admin.inquiries.show', $inquiry) }}" class="btn btn-ghost btn-sm">View</a>
              <button class="btn btn-ghost btn-sm" type="button"
                data-delete="{{ $inquiry->name }}"
                data-delete-url="{{ route('admin.inquiries.destroy', $inquiry) }}"
                data-modal-open="deleteModal">Delete</button>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6">
              <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                <h3>No inquiries match</h3>
                <p>Try clearing the search or filters.</p>
              </div>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="pagination">
    <span class="pagination-info">Showing {{ $inquiries->firstItem() ?? 0 }}–{{ $inquiries->lastItem() ?? 0 }} of {{ $inquiries->total() }} inquiries</span>
    <div class="pagination-nav">
      {{ $inquiries->onEachSide(1)->links() }}
    </div>
  </div>
</div>

<div class="modal-overlay" id="deleteModal">
  <div class="modal">
    <div class="modal-head">
      <h3>Delete inquiry?</h3>
      <button class="modal-close" data-modal-close aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
    </div>
    <div class="modal-body" id="deleteModalBody">This removes the inquiry and its notes permanently. This cannot be undone.</div>
    <div class="modal-foot">
      <button class="btn btn-outline" data-modal-close type="button">Cancel</button>
      <form id="deleteForm" method="POST" action="">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger" type="submit">Delete Inquiry</button>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
  document.querySelectorAll('[data-delete-url]').forEach((btn) => {
    btn.addEventListener('click', () => {
      document.getElementById('deleteModalBody').textContent =
        `Delete the inquiry from "${btn.dataset.delete}"? This removes it and its notes permanently. This cannot be undone.`;
      document.getElementById('deleteForm').setAttribute('action', btn.dataset.deleteUrl);
    });
  });
</script>
@endpush
@endsection
