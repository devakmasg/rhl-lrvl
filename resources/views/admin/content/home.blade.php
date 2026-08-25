@extends('layouts.admin')

@section('title', 'Homepage')

@push('head')
<style>
  .repeater-row{display:flex;gap:10px;align-items:center;margin-bottom:10px;}
  .repeater-row .field{flex:1;margin-bottom:0;}
  .repeater-remove{flex:none;width:34px;height:34px;border-radius:8px;border:1px solid var(--line);background:var(--surface);color:var(--danger);display:flex;align-items:center;justify-content:center;}
  .repeater-remove:hover{background:var(--danger-bg);}
  .repeater-remove svg{width:14px;height:14px;}
  .gallery-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:14px;}
  .g-item{position:relative;border-radius:10px;overflow:hidden;border:1px solid var(--line);background:var(--surface);cursor:grab;aspect-ratio:16/9;}
  .g-item:active{cursor:grabbing;}
  .g-item.dragging{opacity:.4;}
  .g-item.drag-over{outline:2px dashed var(--gold);outline-offset:-2px;}
  .g-item.is-inactive{opacity:.45;}
  .g-item img{width:100%;height:100%;object-fit:cover;}
  .g-order{position:absolute;top:8px;left:8px;width:24px;height:24px;border-radius:50%;background:rgba(21,20,15,.65);color:#fff;font-size:11.5px;font-weight:700;display:flex;align-items:center;justify-content:center;}
  .g-actions{position:absolute;top:8px;right:8px;display:flex;gap:6px;}
  .g-btn{width:28px;height:28px;border-radius:7px;border:none;background:rgba(21,20,15,.65);color:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;}
  .g-btn svg{width:14px;height:14px;}
  .g-btn:hover{background:rgba(21,20,15,.85);}
  .g-btn.is-active{background:var(--gold);color:var(--dark);}
  .g-caption{position:absolute;left:0;right:0;bottom:0;padding:6px 8px;background:linear-gradient(transparent,rgba(21,20,15,.75));display:flex;gap:6px;}
  .g-caption input{flex:1;background:transparent;border:none;color:#fff;font-size:12px;padding:2px 0;}
  .g-caption input::placeholder{color:rgba(255,255,255,.6);}
</style>
@endpush

@section('content')
<div class="page-head">
  <div>
    <h1>Homepage</h1>
    <p>Hero, intro, why-choose-us and key statistics on index.html.</p>
  </div>
  <div class="page-head-actions">
    <button class="btn btn-primary" type="submit" form="homeContentForm">Save Changes</button>
  </div>
</div>

<div class="card card-pad" style="margin-bottom:20px;">
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;">
    <h2 style="font-size:15.5px;">Hero Slider Images</h2>
  </div>
  <div class="card-head-sub" style="margin-bottom:16px;">Drag to reorder. The star toggles a slide active/inactive; the label shows over that slide.</div>

  <form method="POST" action="{{ route('admin.content.home.hero-slides.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="upload-zone" id="heroZone">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" width="26" height="26" style="margin:0 auto 8px;opacity:.6;"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-5-5L5 21"/></svg>
      Drag images here, or click to browse — multiple files supported
      <input type="file" id="heroInput" name="images[]" accept="image/*" multiple hidden>
    </div>
  </form>

  <div class="gallery-grid" id="heroGrid" style="margin-top:16px;">
    @forelse ($heroSlides as $slide)
      <div class="g-item{{ $slide->is_active ? '' : ' is-inactive' }}" draggable="true" data-id="{{ $slide->id }}">
        <span class="g-order">{{ $loop->iteration }}</span>
        <img src="{{ $slide->image_url }}" alt="">
        <div class="g-actions">
          <form method="POST" action="{{ route('admin.content.home.hero-slides.toggle', $slide) }}">
            @csrf
            <button type="submit" class="g-btn{{ $slide->is_active ? ' is-active' : '' }}" aria-label="Toggle active">
              <svg viewBox="0 0 24 24" fill="{{ $slide->is_active ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            </button>
          </form>
          <form method="POST" action="{{ route('admin.content.home.hero-slides.destroy', $slide) }}" onsubmit="return confirm('Remove this slide?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="g-btn" aria-label="Delete slide">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
          </form>
        </div>
        <form method="POST" action="{{ route('admin.content.home.hero-slides.update', $slide) }}" class="g-caption">
          @csrf
          @method('PUT')
          <input type="text" name="label" value="{{ $slide->label }}" placeholder="Slide label" onblur="if(this.value !== this.defaultValue) this.form.submit();">
        </form>
      </div>
    @empty
      <div class="empty-state" style="grid-column:1/-1;">
        <h3>No hero slides yet</h3><p>Upload images above to populate the homepage hero slider.</p>
      </div>
    @endforelse
  </div>
</div>

<div class="card card-pad" style="margin-bottom:20px;">
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;">
    <h2 style="font-size:15.5px;">Our Journey</h2>
    <button type="button" class="btn btn-outline btn-sm" data-modal-open="addJourneyModal">+ Add Chapter</button>
  </div>
  <div class="card-head-sub" style="margin-bottom:16px;">Full-width story chapters between the services and leadership sections. Each is a photo, or a muted looping video with that photo as its poster.</div>

  <div class="table-scroll">
    <table class="table" style="width:100%;">
      <thead><tr><th style="width:50px;">#</th><th style="width:110px;"></th><th>Chapter</th><th style="width:90px;">Media</th><th style="width:80px;">Status</th><th style="width:150px;"></th></tr></thead>
      <tbody>
        @forelse ($journeyChapters as $chapter)
          <tr>
            <td>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
            <td>
              @if ($chapter->image_url)
                <img src="{{ $chapter->image_url }}" alt="" style="width:96px;height:56px;border-radius:6px;object-fit:cover;border:1px solid var(--line);">
              @endif
            </td>
            <td>
              <span class="cell-main">{{ $chapter->heading }}</span>
              <span class="cell-sub">{{ $chapter->kicker }}</span>
            </td>
            <td>
              <span class="badge">{{ $chapter->isVideo() ? 'Video' : 'Photo' }}</span>
            </td>
            <td>
              <span class="badge badge-{{ $chapter->is_active ? 'converted' : 'closed' }}">{{ $chapter->is_active ? 'Live' : 'Hidden' }}</span>
            </td>
            <td class="cell-actions">
              <button class="btn btn-ghost btn-sm" type="button" data-modal-open="editJourneyModal{{ $chapter->id }}">Edit</button>
              <form method="POST" action="{{ route('admin.content.home.journey.destroy', $chapter) }}" style="display:inline;" onsubmit="return confirm('Remove this chapter?');">
                @csrf
                @method('DELETE')
                <button class="btn btn-ghost btn-sm" type="submit" style="color:var(--danger);">Delete</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="6">No journey chapters yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@include('admin.content._journey-modal', ['modalId' => 'addJourneyModal', 'chapter' => null])
@foreach ($journeyChapters as $chapter)
  @include('admin.content._journey-modal', ['modalId' => 'editJourneyModal'.$chapter->id, 'chapter' => $chapter])
@endforeach

<div class="card card-pad" style="margin-bottom:20px;">
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;">
    <h2 style="font-size:15.5px;">Explore Slider</h2>
    <button type="button" class="btn btn-outline btn-sm" data-modal-open="addExploreModal">+ Add Slide</button>
  </div>
  <div class="card-head-sub" style="margin-bottom:16px;">The full-bleed “Step inside our developments” slider near the foot of the homepage.</div>

  <div class="table-scroll">
    <table class="table" style="width:100%;">
      <thead><tr><th style="width:50px;">#</th><th style="width:110px;"></th><th>Slide</th><th style="width:90px;">Media</th><th style="width:80px;">Status</th><th style="width:150px;"></th></tr></thead>
      <tbody>
        @forelse ($exploreSlides as $slide)
          <tr>
            <td>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
            <td>
              @if ($slide->image_url)
                <img src="{{ $slide->image_url }}" alt="" style="width:96px;height:56px;border-radius:6px;object-fit:cover;border:1px solid var(--line);">
              @endif
            </td>
            <td>
              <span class="cell-main">{{ $slide->displayTitle() }}</span>
              <span class="cell-sub">
                {{ collect([$slide->displayCategory(), $slide->displayLocation()])->filter()->implode(' — ') }}
                @if ($slide->project_id) &middot; linked to project @endif
              </span>
            </td>
            <td><span class="badge">{{ $slide->isVideo() ? 'Video' : 'Photo' }}</span></td>
            <td><span class="badge badge-{{ $slide->is_active ? 'converted' : 'closed' }}">{{ $slide->is_active ? 'Live' : 'Hidden' }}</span></td>
            <td class="cell-actions">
              <button class="btn btn-ghost btn-sm" type="button" data-modal-open="editExploreModal{{ $slide->id }}">Edit</button>
              <form method="POST" action="{{ route('admin.content.home.explore.destroy', $slide) }}" style="display:inline;" onsubmit="return confirm('Remove this slide?');">
                @csrf
                @method('DELETE')
                <button class="btn btn-ghost btn-sm" type="submit" style="color:var(--danger);">Delete</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="6">No explore slides yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@include('admin.content._explore-modal', ['modalId' => 'addExploreModal', 'slide' => null, 'projects' => $projects])
@foreach ($exploreSlides as $slide)
  @include('admin.content._explore-modal', ['modalId' => 'editExploreModal'.$slide->id, 'slide' => $slide, 'projects' => $projects])
@endforeach

<form method="POST" action="{{ route('admin.content.home.update') }}" id="homeContentForm" enctype="multipart/form-data">
  @csrf
  @method('PUT')

  <div class="card card-pad" style="margin-bottom:20px;">
    <h2 style="font-size:15.5px;margin-bottom:16px;">Hero</h2>
    <div class="field" style="margin-bottom:16px;">
      <label for="heroHeadline">Headline</label>
      <input type="text" id="heroHeadline" name="hero_headline" value="{{ old('hero_headline', $content['hero_headline'] ?? '') }}" required>
    </div>
    <div class="field-row">
      <div class="field">
        <label for="heroEyebrow">Eyebrow Text</label>
        <input type="text" id="heroEyebrow" name="hero_eyebrow" value="{{ old('hero_eyebrow', $content['hero_eyebrow'] ?? '') }}">
      </div>
      <div class="field">
        <label for="heroLabel">Slide Label (fallback)</label>
        <input type="text" id="heroLabel" name="hero_label" value="{{ old('hero_label', $content['hero_label'] ?? '') }}">
      </div>
    </div>
    <div class="field" style="margin-top:16px;">
      <label for="heroSub">Subheading</label>
      <textarea id="heroSub" name="hero_sub" style="min-height:70px;">{{ old('hero_sub', $content['hero_sub'] ?? '') }}</textarea>
    </div>
  </div>

  <div class="card card-pad" style="margin-bottom:20px;">
    <h2 style="font-size:15.5px;margin-bottom:16px;">Our Story (Intro Section)</h2>
    <div class="field" style="margin-bottom:16px;">
      <label for="introHeadline">Headline</label>
      <input type="text" id="introHeadline" name="intro_headline" value="{{ old('intro_headline', $content['intro_headline'] ?? '') }}" required>
    </div>
    <div class="field-row">
      <div class="field">
        <label for="introSinceLabel">First Label</label>
        <input type="text" id="introSinceLabel" name="intro_since_label" value="{{ old('intro_since_label', $content['intro_since_label'] ?? 'Since 1998') }}" style="margin-bottom:10px;">
        <label for="introSince">First Blurb</label>
        <textarea id="introSince" name="intro_since_text" style="min-height:70px;">{{ old('intro_since_text', $content['intro_since_text'] ?? '') }}</textarea>
      </div>
      <div class="field">
        <label for="introSpectrumLabel">Second Label</label>
        <input type="text" id="introSpectrumLabel" name="intro_spectrum_label" value="{{ old('intro_spectrum_label', $content['intro_spectrum_label'] ?? 'Full Spectrum') }}" style="margin-bottom:10px;">
        <label for="introSpectrum">Second Blurb</label>
        <textarea id="introSpectrum" name="intro_spectrum_text" style="min-height:70px;">{{ old('intro_spectrum_text', $content['intro_spectrum_text'] ?? '') }}</textarea>
      </div>
    </div>
    <div class="field-row" style="margin-top:16px;">
      @include('admin.partials.image-field', [
        'name' => 'intro_image',
        'label' => 'Section Photo',
        'currentUrl' => $page->imageUrl('intro_image'),
      ])
      <div class="field">
        <label for="introBadgeNumber">Badge Number</label>
        <input type="text" id="introBadgeNumber" name="intro_badge_number" value="{{ old('intro_badge_number', $content['intro_badge_number'] ?? '25+') }}">
        <label for="introBadgeLabel" style="margin-top:12px;">Badge Label</label>
        <input type="text" id="introBadgeLabel" name="intro_badge_label" value="{{ old('intro_badge_label', $content['intro_badge_label'] ?? 'Years of Excellence') }}">
        <span class="hint">The gold badge overlapping the photo.</span>
      </div>
    </div>
  </div>

  <div class="card card-pad" style="margin-bottom:20px;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
      <h2 style="font-size:15.5px;">Why Choose Us (Cards)</h2>
      <button type="button" class="btn btn-outline btn-sm" id="addWhy">+ Add Card</button>
    </div>
    <div id="whyList"></div>
  </div>

  <div class="card card-pad" style="margin-bottom:20px;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
      <h2 style="font-size:15.5px;">Key Statistics</h2>
      <button type="button" class="btn btn-outline btn-sm" id="addStat">+ Add Stat</button>
    </div>
    <div id="statsList"></div>
    <div style="margin-top:18px;max-width:420px;">
      @include('admin.partials.image-field', [
        'name' => 'stats_background',
        'label' => 'Band Background Photo',
        'currentUrl' => $page->imageUrl('stats_background'),
        'hint' => 'Sits behind the statistics, darkened automatically.',
      ])
    </div>
  </div>

  <div class="card card-pad" style="margin-bottom:20px;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;">
      <h2 style="font-size:15.5px;">Closing Cards</h2>
      <button type="button" class="btn btn-outline btn-sm" id="addConnect">+ Add Card</button>
    </div>
    <div class="card-head-sub" style="margin-bottom:14px;">The two call-to-action cards at the very bottom of the homepage.</div>
    <div id="connectList"></div>
  </div>

  <div class="card card-pad" style="margin-bottom:20px;">
    <h2 style="font-size:15.5px;margin-bottom:4px;">Scrolling Marquee</h2>
    <div class="card-head-sub" style="margin-bottom:16px;">The gold ticker of short phrases between the statistics and featured developments.</div>
    <div class="field">
      <label for="marqueeItems">Phrases</label>
      <textarea id="marqueeItems" name="marquee_items" style="min-height:110px;">{{ old('marquee_items', implode("\n", $content['marquee_items'] ?? [])) }}</textarea>
      <span class="hint">One phrase per line. Leave empty to use the built-in defaults.</span>
    </div>
  </div>

  <div class="card card-pad">
    <h2 style="font-size:15.5px;margin-bottom:4px;">Section Headings</h2>
    <div class="card-head-sub" style="margin-bottom:16px;">The small label and heading above each block on the homepage. Leave a field blank to use the built-in default.</div>
    <table class="table" style="width:100%;">
      <thead>
        <tr>
          <th style="width:170px;">Section</th>
          <th style="width:230px;">Eyebrow</th>
          <th>Heading</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($sectionDefs as $key => $def)
          <tr>
            <td><span class="cell-main">{{ $def['label'] }}</span></td>
            <td>
              <input type="text" name="section_eyebrow[{{ $key }}]"
                     value="{{ old('section_eyebrow.'.$key, $content['sections'][$key]['eyebrow'] ?? $def['eyebrow']) }}"
                     placeholder="{{ $def['eyebrow'] }}">
            </td>
            <td>
              @if ($def['heading'] === null)
                <span class="cell-sub">{{ $key === 'story' ? 'Uses the "Our Story" headline above.' : 'This section has no separate heading.' }}</span>
              @else
                <input type="text" name="section_heading[{{ $key }}]"
                       value="{{ old('section_heading.'.$key, $content['sections'][$key]['heading'] ?? $def['heading']) }}"
                       placeholder="{{ $def['heading'] }}">
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</form>
@endsection

@push('scripts')
@php
  // Built here rather than inline: Blade's @json() argument parser cannot cope
  // with a nested array literal spanning several lines.
  $connectSeed = old('connect_title')
    ? collect(old('connect_title'))->map(fn ($t, $i) => [
        'title' => $t,
        'text' => old('connect_text')[$i] ?? '',
        'btn_label' => old('connect_btn_label')[$i] ?? '',
        'btn_url' => old('connect_btn_url')[$i] ?? '',
      ])->values()->all()
    : ($content['connect_cards'] ?? [
        ['title' => 'Get in Touch', 'text' => 'Speak with our team about current availability, partnership opportunities or a project you have in mind.', 'btn_label' => 'Contact us', 'btn_url' => '/contact'],
        ['title' => 'Featured Developments', 'text' => 'Browse our residential, commercial and mixed-use projects across the region.', 'btn_label' => 'View projects', 'btn_url' => '/projects'],
      ]);
@endphp
<script>
  function makeRepeater(listEl, addBtn, buildRow, seed){
    function addRow(values){
      const row = document.createElement('div');
      row.className = 'repeater-row';
      row.innerHTML = buildRow(values || {});
      const remove = document.createElement('button');
      remove.type = 'button'; remove.className = 'repeater-remove'; remove.setAttribute('aria-label', 'Remove');
      remove.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';
      remove.addEventListener('click', () => row.remove());
      row.appendChild(remove);
      listEl.appendChild(row);
    }
    addBtn.addEventListener('click', () => addRow());
    (seed || []).forEach(addRow);
  }

  function esc(v){
    return String(v ?? '').replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
  }

  makeRepeater(document.getElementById('whyList'), document.getElementById('addWhy'),
    (v) => `<div class="field" style="max-width:220px;"><input type="text" name="why_title[]" placeholder="Card title" value="${esc(v.title)}"></div>
            <div class="field"><input type="text" name="why_desc[]" placeholder="Card description" value="${esc(v.desc)}"></div>`,
    @json(old('why_title') ? collect(old('why_title'))->map(fn($t, $i) => ['title' => $t, 'desc' => old('why_desc')[$i] ?? ''])->values() : ($content['why_cards'] ?? [])));

  makeRepeater(document.getElementById('statsList'), document.getElementById('addStat'),
    (v) => `<div class="field" style="max-width:130px;"><input type="text" name="stat_value[]" placeholder="Value, e.g. 6.4M+" value="${esc(v.value)}"></div>
            <div class="field"><input type="text" name="stat_label[]" placeholder="Label" value="${esc(v.label)}"></div>`,
    @json(old('stat_value') ? collect(old('stat_value'))->map(fn($t, $i) => ['value' => $t, 'label' => old('stat_label')[$i] ?? ''])->values() : ($content['stats'] ?? [])));

  makeRepeater(document.getElementById('connectList'), document.getElementById('addConnect'),
    (v) => `<div class="field" style="max-width:190px;"><input type="text" name="connect_title[]" placeholder="Card title" value="${esc(v.title)}"></div>
            <div class="field"><input type="text" name="connect_text[]" placeholder="Card text" value="${esc(v.text)}"></div>
            <div class="field" style="max-width:150px;"><input type="text" name="connect_btn_label[]" placeholder="Button label" value="${esc(v.btn_label)}"></div>
            <div class="field" style="max-width:150px;"><input type="text" name="connect_btn_url[]" placeholder="/contact" value="${esc(v.btn_url)}"></div>`,
    @json($connectSeed));

  (function(){
    const zone = document.getElementById('heroZone');
    const input = document.getElementById('heroInput');
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

    const grid = document.getElementById('heroGrid');
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
        fetch('{{ route('admin.content.home.hero-slides.reorder') }}', {
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
