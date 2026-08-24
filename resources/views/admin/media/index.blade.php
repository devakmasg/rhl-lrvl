@extends('layouts.admin')

@section('title', 'Media Library')

@push('head')
<style>
  .media-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:14px;}
  .m-item{position:relative;border-radius:10px;overflow:hidden;border:1px solid var(--line);background:var(--surface);aspect-ratio:1/1;}
  .m-item img{width:100%;height:100%;object-fit:cover;}
  .m-item.is-doc{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;color:var(--stone);background:var(--surface-muted);}
  .m-item.is-doc svg{width:34px;height:34px;color:var(--gold);}
  .m-item.is-doc span{font-size:11px;text-align:center;padding:0 8px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:100%;}
  .m-delete{
    position:absolute;top:6px;right:6px;width:28px;height:28px;border-radius:7px;border:none;
    background:rgba(21,20,15,.65);color:#fff;display:flex;align-items:center;justify-content:center;opacity:0;transition:opacity .15s ease;cursor:pointer;
  }
  .m-item:hover .m-delete{opacity:1;}
  .m-delete svg{width:14px;height:14px;}
  .m-name{
    position:absolute;left:0;right:0;bottom:0;padding:6px 8px;background:linear-gradient(transparent,rgba(21,20,15,.75));
    color:#fff;font-size:10.5px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;
  }
  .upload-zone{border:1.5px dashed var(--line);border-radius:10px;padding:26px;text-align:center;color:var(--stone);font-size:12.5px;cursor:pointer;}
  .upload-zone:hover,.upload-zone.is-drag{border-color:var(--gold);background:#faf5ea;}
  .upload-zone svg{width:28px;height:28px;margin:0 auto 8px;opacity:.6;}
</style>
@endpush

@section('content')
<div class="page-head">
  <div>
    <h1>Media Library</h1>
    <p>Every image and document uploaded across projects, news and content pages.</p>
  </div>
</div>

<div class="card card-pad" style="margin-bottom:20px;">
  <form id="uploadForm" method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data">
    @csrf
    <label class="upload-zone" id="mediaZone" for="mediaInput">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
      Click to browse — multiple files supported
      <input type="file" id="mediaInput" name="files[]" accept="image/*,application/pdf" multiple hidden>
    </label>
    <span class="field-error">{{ $errors->first('files') }}{{ $errors->first('files.0') }}</span>
  </form>
</div>

<div class="card card-pad">
  <div class="media-grid">
    @forelse ($files as $file)
      <div class="m-item @if(!$file['is_image']) is-doc @endif">
        @if ($file['is_image'])
          <img src="{{ $file['url'] }}" alt="{{ $file['name'] }}">
          <div class="m-name">{{ $file['name'] }}</div>
        @else
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>
          <span>{{ $file['name'] }}</span>
        @endif
        <button class="m-delete" type="button" title="Delete" data-modal-open="mediaDeleteModal"
          data-delete-name="{{ $file['name'] }}"
          data-delete-url="{{ route('admin.media.destroy', rawurlencode($file['name'])) }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
        </button>
      </div>
    @empty
      <div class="empty-state">
        <h3>No files yet</h3>
        <p>Upload an image or PDF to get started.</p>
      </div>
    @endforelse
  </div>
</div>

<div class="modal-overlay" id="mediaDeleteModal">
  <div class="modal">
    <div class="modal-head"><h3>Delete file?</h3><button class="modal-close" data-modal-close aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button></div>
    <div class="modal-body" id="mediaDeleteBody">This cannot be undone.</div>
    <div class="modal-foot">
      <button class="btn btn-outline" data-modal-close type="button">Cancel</button>
      <form id="mediaDeleteForm" method="POST" action="">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger" type="submit">Delete</button>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
  document.getElementById('mediaInput').addEventListener('change', () => {
    document.getElementById('uploadForm').submit();
  });
  document.querySelectorAll('[data-delete-url]').forEach((btn) => {
    btn.addEventListener('click', () => {
      document.getElementById('mediaDeleteBody').textContent =
        `Delete "${btn.dataset.deleteName}"? This cannot be undone.`;
      document.getElementById('mediaDeleteForm').setAttribute('action', btn.dataset.deleteUrl);
    });
  });
</script>
@endpush
@endsection
