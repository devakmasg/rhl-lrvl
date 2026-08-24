@extends('layouts.admin')

@section('title', 'News')

@section('content')
<div class="page-head">
  <div>
    <h1>News &amp; Updates</h1>
    <p>Articles shown on news.html and each project's timeline.</p>
  </div>
  <div class="page-head-actions">
    <a href="{{ route('admin.news.create') }}" class="btn btn-primary">+ New Article</a>
  </div>
</div>

<div class="card">
  <div class="table-scroll">
    <table class="table">
      <thead><tr><th>Title</th><th>Category</th><th>Date</th><th>Status</th><th></th></tr></thead>
      <tbody>
        @forelse ($news as $article)
          <tr>
            <td><div class="cell-main">{{ $article->title }}</div></td>
            <td>{{ $article->category }}</td>
            <td>{{ $article->date?->format('d M Y') }}</td>
            <td><span class="badge badge-{{ $article->published ? 'published' : 'draft' }}">{{ $article->published ? 'Published' : 'Draft' }}</span></td>
            <td class="cell-actions">
              <a href="{{ route('admin.news.edit', $article) }}" class="btn btn-ghost btn-sm">Edit</a>
              <button class="btn btn-ghost btn-sm" type="button"
                data-delete="{{ $article->title }}"
                data-delete-url="{{ route('admin.news.destroy', $article) }}"
                data-modal-open="deleteModal">Delete</button>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5">
              <div class="empty-state">
                <h3>No articles yet</h3>
                <p>Create your first article to get started.</p>
              </div>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="pagination">
    <span class="pagination-info">Showing {{ $news->firstItem() ?? 0 }}&ndash;{{ $news->lastItem() ?? 0 }} of {{ $news->total() }} articles</span>
    <div class="pagination-nav">
      {{ $news->onEachSide(1)->links() }}
    </div>
  </div>
</div>

<div class="modal-overlay" id="deleteModal">
  <div class="modal">
    <div class="modal-head"><h3>Delete article?</h3><button class="modal-close" data-modal-close aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button></div>
    <div class="modal-body" id="deleteModalBody">This removes the article permanently. This cannot be undone.</div>
    <div class="modal-foot">
      <button class="btn btn-outline" data-modal-close type="button">Cancel</button>
      <form id="deleteForm" method="POST" action="">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger" type="submit">Delete Article</button>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
  document.querySelectorAll('[data-delete-url]').forEach((btn) => {
    btn.addEventListener('click', () => {
      document.getElementById('deleteModalBody').textContent =
        `Delete "${btn.dataset.delete}"? This removes the article permanently. This cannot be undone.`;
      document.getElementById('deleteForm').setAttribute('action', btn.dataset.deleteUrl);
    });
  });
</script>
@endpush
@endsection
