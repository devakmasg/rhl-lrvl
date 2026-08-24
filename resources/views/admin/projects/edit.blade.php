@extends('layouts.admin')

@section('title', 'Edit Project')

@section('content')
<div class="page-head">
  <div>
    <h1>Edit Project</h1>
    <p>All fields feed the public project detail page (name, facts, amenities, unit table, floor plans).</p>
  </div>
  <div class="page-head-actions">
    <a href="{{ route('admin.projects.index') }}" class="btn btn-outline">Cancel</a>
    <button class="btn btn-primary" type="submit" form="projectForm">Save Changes</button>
  </div>
</div>

@include('admin.projects._form')

<div class="card" id="galleryPanel" style="margin-top:24px;scroll-margin-top:90px;">
  <div class="card-pad" style="border-bottom:1px solid var(--line);">
    <h2 style="font-size:15.5px;margin-bottom:4px;">Project Gallery</h2>
    <div class="card-head-sub" style="margin-bottom:16px;">Drag to reorder. The star sets the featured shot.</div>

    <form method="POST" action="{{ route('admin.projects.images.store', $project) }}" enctype="multipart/form-data">
      @csrf
      <div class="upload-zone" id="galleryZone">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" width="26" height="26" style="margin:0 auto 8px;opacity:.6;"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-5-5L5 21"/></svg>
        Drag images here, or click to browse — multiple files supported
        <input type="file" id="galleryInput" name="images[]" accept="image/*" multiple hidden>
      </div>
    </form>
  </div>

  <div class="card-pad">
    <style>
      .gallery-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:14px;}
      .g-item{position:relative;border-radius:10px;overflow:hidden;border:1px solid var(--line);background:var(--surface);cursor:grab;aspect-ratio:4/3;}
      .g-item:active{cursor:grabbing;}
      .g-item.dragging{opacity:.4;}
      .g-item.drag-over{outline:2px dashed var(--gold);outline-offset:-2px;}
      .g-item img{width:100%;height:100%;object-fit:cover;}
      .g-order{position:absolute;top:8px;left:8px;width:24px;height:24px;border-radius:50%;background:rgba(21,20,15,.65);color:#fff;font-size:11.5px;font-weight:700;display:flex;align-items:center;justify-content:center;}
      .g-actions{position:absolute;top:8px;right:8px;display:flex;gap:6px;}
      .g-btn{width:28px;height:28px;border-radius:7px;border:none;background:rgba(21,20,15,.65);color:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;}
      .g-btn svg{width:14px;height:14px;}
      .g-btn:hover{background:rgba(21,20,15,.85);}
      .g-btn.is-featured{background:var(--gold);color:var(--dark);}
      .g-caption{position:absolute;left:0;right:0;bottom:0;padding:6px 8px;background:linear-gradient(transparent,rgba(21,20,15,.75));color:#fff;font-size:11px;}
    </style>
    <div class="gallery-grid" id="galleryGrid">
      @forelse ($project->images as $image)
        <div class="g-item" draggable="true" data-id="{{ $image->id }}">
          <span class="g-order">{{ $loop->iteration }}</span>
          <img src="{{ Str::startsWith($image->image_path, ['http://', 'https://']) ? $image->image_path : \Illuminate\Support\Facades\Storage::url($image->image_path) }}" alt="">
          <div class="g-actions">
            <form method="POST" action="{{ route('admin.projects.images.feature', [$project, $image]) }}">
              @csrf
              <button type="submit" class="g-btn{{ $image->is_featured ? ' is-featured' : '' }}" aria-label="Set as featured">
                <svg viewBox="0 0 24 24" fill="{{ $image->is_featured ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
              </button>
            </form>
            <form method="POST" action="{{ route('admin.projects.images.destroy', [$project, $image]) }}" onsubmit="return confirm('Remove this image?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="g-btn" aria-label="Delete image">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
              </button>
            </form>
          </div>
          @if ($image->is_featured)
            <div class="g-caption">Featured shot</div>
          @endif
        </div>
      @empty
        <div class="empty-state" style="grid-column:1/-1;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-5-5L5 21"/></svg>
          <h3>No images yet</h3><p>Upload photos above to build this project's gallery.</p>
        </div>
      @endforelse
    </div>
  </div>
</div>

<div class="card card-pad" style="margin-top:24px;">
  <h2 style="font-size:15.5px;margin-bottom:4px;">Floor Plans</h2>
  <div class="card-head-sub" style="margin-bottom:16px;">Upload individual floor plan images with an optional label.</div>

  <form method="POST" action="{{ route('admin.projects.floor-plans.store', $project) }}" enctype="multipart/form-data" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;margin-bottom:20px;">
    @csrf
    <div class="field" style="flex:1;min-width:220px;margin-bottom:0;">
      <label for="fpFile">Floor plan image</label>
      <input type="file" id="fpFile" name="floor_plan" accept="image/*" required>
    </div>
    <div class="field" style="flex:1;min-width:220px;margin-bottom:0;">
      <label for="fpLabel">Label</label>
      <input type="text" id="fpLabel" name="label" placeholder="e.g. Typical Floor Plan">
    </div>
    <button type="submit" class="btn btn-outline">Upload Floor Plan</button>
  </form>

  <div class="gallery-grid">
    @forelse ($project->floorPlans as $plan)
      <div class="g-item" style="cursor:default;">
        <img src="{{ Str::startsWith($plan->image_path, ['http://', 'https://']) ? $plan->image_path : \Illuminate\Support\Facades\Storage::url($plan->image_path) }}" alt="{{ $plan->label }}">
        <div class="g-actions">
          <form method="POST" action="{{ route('admin.projects.floor-plans.destroy', [$project, $plan]) }}" onsubmit="return confirm('Remove this floor plan?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="g-btn" aria-label="Delete floor plan">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
          </form>
        </div>
        @if ($plan->label)
          <div class="g-caption">{{ $plan->label }}</div>
        @endif
      </div>
    @empty
      <div class="empty-state" style="grid-column:1/-1;">
        <h3>No floor plans yet</h3><p>Upload floor plan images above.</p>
      </div>
    @endforelse
  </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
  const zone = document.getElementById('galleryZone');
  const input = document.getElementById('galleryInput');
  if (zone) {
    zone.addEventListener('click', () => input.click());
    zone.addEventListener('dragover', (e) => { e.preventDefault(); zone.classList.add('is-drag'); });
    zone.addEventListener('dragleave', () => zone.classList.remove('is-drag'));
    zone.addEventListener('drop', (e) => {
      e.preventDefault(); zone.classList.remove('is-drag');
      input.files = e.dataTransfer.files;
      input.closest('form').submit();
    });
    input.addEventListener('change', () => { if (input.files.length) input.closest('form').submit(); });
  }

  /* ---------- drag reorder ---------- */
  const grid = document.getElementById('galleryGrid');
  if (grid) {
    let dragEl = null;
    grid.addEventListener('dragstart', (e) => {
      const item = e.target.closest('.g-item');
      if (!item) return;
      dragEl = item;
      item.classList.add('dragging');
    });
    grid.addEventListener('dragend', (e) => {
      const item = e.target.closest('.g-item');
      if (item) item.classList.remove('dragging');
      grid.querySelectorAll('.drag-over').forEach((el) => el.classList.remove('drag-over'));
    });
    grid.addEventListener('dragover', (e) => {
      e.preventDefault();
      const item = e.target.closest('.g-item');
      if (!item || item === dragEl) return;
      grid.querySelectorAll('.drag-over').forEach((el) => el.classList.remove('drag-over'));
      item.classList.add('drag-over');
    });
    grid.addEventListener('drop', (e) => {
      e.preventDefault();
      const item = e.target.closest('.g-item');
      if (!item || !dragEl || item === dragEl) return;
      const items = Array.from(grid.children);
      const dragIdx = items.indexOf(dragEl);
      const dropIdx = items.indexOf(item);
      if (dragIdx < dropIdx) item.after(dragEl); else item.before(dragEl);
      grid.querySelectorAll('.g-order').forEach((el, i) => el.textContent = i + 1);
      grid.querySelectorAll('.drag-over').forEach((el) => el.classList.remove('drag-over'));

      const order = Array.from(grid.querySelectorAll('.g-item')).map((el) => el.dataset.id);
      fetch('{{ route('admin.projects.images.reorder', $project) }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
          'Accept': 'application/json',
        },
        body: JSON.stringify({ order }),
      });
    });
  }
})();
</script>
@endpush
