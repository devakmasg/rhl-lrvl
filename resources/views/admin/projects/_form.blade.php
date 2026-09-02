@php
  $isEdit = $project->exists;
  $factsList = old('fact_keys') ? collect(old('fact_keys'))->map(fn ($k, $i) => ['k' => $k, 'v' => old('fact_values')[$i] ?? '']) : collect($project->facts ?? [])->map(fn ($v, $k) => ['k' => $k, 'v' => $v])->values();
  $featuresList = old('features') ?? ($project->features ?? []);
  $amenitiesList = old('amenities') ?? ($project->amenities->pluck('text')->all() ?? []);
  $unitsList = $isEdit && ! old('unit_type') ? $project->units->map(fn ($u) => ['type' => $u->unit_type, 'size' => $u->size_sqft, 'beds' => $u->beds, 'baths' => $u->baths, 'floorplate' => $u->floorplate, 'use' => $u->use]) : collect();
  $bodyText = old('body') ?? ($project->body ? implode("\n", explode("\n\n", $project->body)) : '');
@endphp

<style>
  .form-grid{display:grid;grid-template-columns:1fr 320px;gap:20px;align-items:start;}
  @media (max-width:1080px){.form-grid{grid-template-columns:1fr;}}
  .form-stack{display:flex;flex-direction:column;gap:20px;}
  .repeater-row{display:flex;gap:10px;align-items:center;margin-bottom:10px;}
  .repeater-row .field{flex:1;margin-bottom:0;}
  .repeater-remove{flex:none;width:34px;height:34px;border-radius:8px;border:1px solid var(--line);background:var(--surface);color:var(--danger);display:flex;align-items:center;justify-content:center;}
  .repeater-remove:hover{background:var(--danger-bg);}
  .repeater-remove svg{width:14px;height:14px;}
  .units-table{width:100%;border-collapse:collapse;margin-bottom:10px;}
  .units-table th{text-align:left;font-size:11px;color:var(--stone);text-transform:uppercase;letter-spacing:.05em;padding:0 8px 8px;}
  .units-table td{padding:0 8px 8px;}
  .units-table input{width:100%;}
  .sticky-side{position:sticky;top:calc(var(--topbar-h) + 20px);}
</style>

<form id="projectForm" class="form-grid" method="POST" action="{{ $isEdit ? route('admin.projects.update', $project) : route('admin.projects.store') }}" enctype="multipart/form-data">
  @csrf
  @if ($isEdit) @method('PUT') @endif

  <div class="form-stack">

    <div class="card card-pad">
      <h2 style="font-size:15.5px;margin-bottom:16px;">Basic Information</h2>
      <div class="field" style="margin-bottom:16px;">
        <label for="pName">Project Name</label>
        <input type="text" id="pName" name="name" placeholder="e.g. Gulshan Heights" value="{{ old('name', $project->name) }}" required>
        @error('name') <span class="field-error">{{ $message }}</span> @enderror
      </div>
      <div class="field" style="margin-bottom:16px;">
        <label for="pSlug">URL Slug</label>
        <input type="text" id="pSlug" name="slug" placeholder="auto-generated from name" pattern="[a-z0-9-]+" value="{{ old('slug', $project->slug) }}">
        <span class="hint">Used in the public project URL — auto-filled from the name if left blank.</span>
        @error('slug') <span class="field-error">{{ $message }}</span> @enderror
      </div>
      <div class="field-row" style="margin-bottom:16px;">
        <div class="field">
          <label for="pType">Type</label>
          <select id="pType" name="type" required>
            @foreach ($types as $t)
              <option value="{{ $t }}" @selected(old('type', $project->type) === $t)>{{ $t }}</option>
            @endforeach
          </select>
        </div>
        <div class="field">
          <label for="pLocation">Location</label>
          <select id="pLocation" name="location" required>
            @foreach ($locations as $l)
              <option value="{{ $l }}" @selected(old('location', $project->location) === $l)>{{ $l }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="field-row">
        <div class="field">
          <label for="pStatus">Project Status</label>
          <select id="pStatus" name="status">
            @foreach ($statuses as $s)
              <option value="{{ $s }}" @selected(old('status', $project->status) === $s)>{{ $s }}</option>
            @endforeach
          </select>
        </div>
        <div class="field" id="progressField">
          <label for="pProgress">Construction Progress (%)</label>
          <input type="number" id="pProgress" name="progress" min="0" max="100" value="{{ old('progress', $project->progress) }}">
          <span class="hint">Drives the Foundation &rarr; Structure &rarr; Finishing &rarr; Handover timeline.</span>
        </div>
      </div>
    </div>

    <div class="card card-pad">
      <h2 style="font-size:15.5px;margin-bottom:16px;">Summary &amp; Description</h2>
      <div class="field" style="margin-bottom:16px;">
        <label for="pSummary">Short Summary</label>
        <textarea id="pSummary" name="summary" placeholder="One or two sentences shown on the projects grid and cards." required style="min-height:70px;">{{ old('summary', $project->summary) }}</textarea>
        @error('summary') <span class="field-error">{{ $message }}</span> @enderror
      </div>
      <div class="field">
        <label for="pBody">Full Description</label>
        <textarea id="pBody" name="body" placeholder="One paragraph per line — each becomes a paragraph on the project page." style="min-height:130px;">{{ $bodyText }}</textarea>
        <span class="hint">One paragraph per line.</span>
      </div>
    </div>

    <div class="card card-pad">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
        <h2 style="font-size:15.5px;">Key Facts</h2>
        <button type="button" class="btn btn-outline btn-sm" id="addFact">+ Add Fact</button>
      </div>
      <div id="factsList">
        @foreach ($factsList as $fact)
          <div class="repeater-row">
            <div class="field"><input type="text" name="fact_keys[]" placeholder="Label, e.g. Completion" value="{{ $fact['k'] }}"></div>
            <div class="field"><input type="text" name="fact_values[]" placeholder="Value, e.g. Q4 2027" value="{{ $fact['v'] }}"></div>
            <button type="button" class="repeater-remove" aria-label="Remove"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
          </div>
        @endforeach
      </div>
    </div>

    <div class="card card-pad">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
        <h2 style="font-size:15.5px;">Features</h2>
        <button type="button" class="btn btn-outline btn-sm" id="addFeature">+ Add Feature</button>
      </div>
      <div id="featuresList">
        @foreach ($featuresList as $feature)
          <div class="repeater-row">
            <div class="field"><input type="text" name="features[]" placeholder="e.g. Rooftop lap pool" value="{{ $feature }}"></div>
            <button type="button" class="repeater-remove" aria-label="Remove"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
          </div>
        @endforeach
      </div>
    </div>

    <div class="card card-pad">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
        <h2 style="font-size:15.5px;">Amenities</h2>
        <button type="button" class="btn btn-outline btn-sm" id="addAmenity">+ Add Amenity</button>
      </div>
      <div id="amenitiesList">
        @foreach ($amenitiesList as $amenity)
          <div class="repeater-row">
            <div class="field"><input type="text" name="amenities[]" placeholder="e.g. Rooftop swimming pool" value="{{ $amenity }}"></div>
            <button type="button" class="repeater-remove" aria-label="Remove"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
          </div>
        @endforeach
      </div>
    </div>

    <div class="card card-pad">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
        <h2 style="font-size:15.5px;">Unit Information</h2>
        <button type="button" class="btn btn-outline btn-sm" id="addUnitRow">+ Add Row</button>
      </div>
      <span class="hint" style="display:block;margin-bottom:10px;">Fill Beds/Baths for residential rows, or Floorplate/Use for commercial rows — both column sets are stored per row.</span>
      <table class="units-table">
        <thead><tr><th>Unit Type</th><th>Size (sq ft)</th><th>Beds</th><th>Baths</th><th>Floorplate</th><th>Use</th><th></th></tr></thead>
        <tbody id="unitsBody">
          @forelse ($unitsList as $u)
            <tr>
              <td><input type="text" name="unit_type[]" value="{{ $u['type'] }}" placeholder="3 Bed"></td>
              <td><input type="text" name="size_sqft[]" value="{{ $u['size'] }}" placeholder="1,980"></td>
              <td><input type="text" name="beds[]" value="{{ $u['beds'] }}" placeholder="3"></td>
              <td><input type="text" name="baths[]" value="{{ $u['baths'] }}" placeholder="3"></td>
              <td><input type="text" name="floorplate[]" value="{{ $u['floorplate'] }}" placeholder="11,000 sq ft"></td>
              <td><input type="text" name="unit_use[]" value="{{ $u['use'] }}" placeholder="Office"></td>
              <td><button type="button" class="repeater-remove" aria-label="Remove row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button></td>
            </tr>
          @empty
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="card card-pad">
      <h2 style="font-size:15.5px;margin-bottom:16px;">Brochure</h2>
      <div class="field">
        <label>Brochure (PDF)</label>
        <div class="upload-zone" id="brochureZone">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
          Drag a brochure PDF here, or click to browse
          <input type="file" id="brochureInput" name="brochure" accept="application/pdf" hidden>
        </div>
        <div class="file-chip-list" id="brochureChips">
          @if ($project->brochure_path)
            <div class="file-chip"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg><span>{{ basename($project->brochure_path) }} (current)</span></div>
          @endif
        </div>
        @error('brochure') <span class="field-error">{{ $message }}</span> @enderror
      </div>
    </div>

  </div>

  <div class="form-stack sticky-side">
    <div class="card card-pad">
      <h2 style="font-size:14px;margin-bottom:14px;">Publish Settings</h2>
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
        <div>
          <div style="font-weight:600;font-size:13px;">Published</div>
          <div class="hint">Visible on the live site</div>
        </div>
        <label class="toggle"><input type="checkbox" name="published" value="1" @checked(old('published', $project->exists ? $project->published : true))><span class="track"></span></label>
      </div>
      <div style="display:flex;align-items:center;justify-content:space-between;">
        <div>
          <div style="font-weight:600;font-size:13px;">Featured</div>
          <div class="hint">Shown in homepage carousel</div>
        </div>
        <label class="toggle"><input type="checkbox" name="featured" value="1" @checked(old('featured', $project->featured))><span class="track"></span></label>
      </div>
    </div>

    <div class="card card-pad">
      <h2 style="font-size:14px;margin-bottom:14px;">Hero Image</h2>
      <div class="upload-zone" id="heroZone">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-5-5L5 21"/></svg>
        Click to upload hero photo
        <input type="file" id="heroInput" name="hero_image" accept="image/*" hidden>
      </div>
      <div class="file-chip-list" id="heroChips">
        @if ($project->hero_image)
          <div class="file-chip"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-5-5L5 21"/></svg><span>Current hero image set</span></div>
        @endif
      </div>
      @error('hero_image') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    @if ($isEdit)
      <div class="card card-pad">
        <h2 style="font-size:14px;margin-bottom:10px;">Gallery &amp; Floor Plans</h2>
        <p class="hint" style="margin-bottom:12px;">Manage the full photo gallery and floor plan images below, after saving.</p>
        <a href="#galleryPanel" class="btn btn-outline btn-sm" style="width:100%;">Manage Gallery &amp; Floor Plans &darr;</a>
      </div>
    @else
      <div class="card card-pad">
        <h2 style="font-size:14px;margin-bottom:10px;">Gallery &amp; Floor Plans</h2>
        <p class="hint">Save this project first, then a gallery and floor plan manager will appear on the edit screen.</p>
      </div>
    @endif

    <div class="card card-pad">
      <h2 style="font-size:14px;margin-bottom:10px;">Location Map</h2>
      <p class="hint">Map embed query is built automatically from Name + Location, matching the public project page.</p>
    </div>
  </div>
</form>

@push('scripts')
<script>
(function(){
  const nameField = document.getElementById('pName');
  const slugField = document.getElementById('pSlug');
  let slugTouched = {{ $isEdit ? 'true' : 'false' }};
  slugField.addEventListener('input', () => { slugTouched = true; });
  nameField.addEventListener('input', () => {
    if (slugTouched) return;
    slugField.value = nameField.value.trim().toLowerCase()
      .replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');
  });

  function makeRepeater(listEl, addBtn, buildRow){
    function addRow(){
      const row = document.createElement('div');
      row.className = 'repeater-row';
      row.innerHTML = buildRow();
      const remove = document.createElement('button');
      remove.type = 'button';
      remove.className = 'repeater-remove';
      remove.setAttribute('aria-label', 'Remove');
      remove.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';
      remove.addEventListener('click', () => row.remove());
      row.appendChild(remove);
      listEl.appendChild(row);
    }
    addBtn.addEventListener('click', addRow);
    return addRow;
  }

  makeRepeater(document.getElementById('factsList'), document.getElementById('addFact'),
    () => `<div class="field"><input type="text" name="fact_keys[]" placeholder="Label, e.g. Completion"></div>
           <div class="field"><input type="text" name="fact_values[]" placeholder="Value, e.g. Q4 2027"></div>`);

  makeRepeater(document.getElementById('featuresList'), document.getElementById('addFeature'),
    () => `<div class="field"><input type="text" name="features[]" placeholder="e.g. Rooftop lap pool"></div>`);

  makeRepeater(document.getElementById('amenitiesList'), document.getElementById('addAmenity'),
    () => `<div class="field"><input type="text" name="amenities[]" placeholder="e.g. Rooftop swimming pool"></div>`);

  document.querySelectorAll('#factsList .repeater-remove, #featuresList .repeater-remove, #amenitiesList .repeater-remove').forEach((btn) => {
    btn.addEventListener('click', () => btn.closest('.repeater-row').remove());
  });

  const unitsBody = document.getElementById('unitsBody');
  document.getElementById('addUnitRow').addEventListener('click', () => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td><input type="text" name="unit_type[]" placeholder="3 Bed"></td>
      <td><input type="text" name="size_sqft[]" placeholder="1,980"></td>
      <td><input type="text" name="beds[]" placeholder="3"></td>
      <td><input type="text" name="baths[]" placeholder="3"></td>
      <td><input type="text" name="floorplate[]" placeholder="11,000 sq ft"></td>
      <td><input type="text" name="unit_use[]" placeholder="Office"></td>
      <td><button type="button" class="repeater-remove" aria-label="Remove row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button></td>`;
    tr.querySelector('.repeater-remove').addEventListener('click', () => tr.remove());
    unitsBody.appendChild(tr);
  });
  document.querySelectorAll('#unitsBody .repeater-remove').forEach((btn) => {
    btn.addEventListener('click', () => btn.closest('tr').remove());
  });

  function wireUploadZone(zoneId, inputId, chipsId){
    const zone = document.getElementById(zoneId);
    const input = document.getElementById(inputId);
    const chips = document.getElementById(chipsId);
    if (!zone) return;
    zone.addEventListener('click', () => input.click());
    zone.addEventListener('dragover', (e) => { e.preventDefault(); zone.classList.add('is-drag'); });
    zone.addEventListener('dragleave', () => zone.classList.remove('is-drag'));
    zone.addEventListener('drop', (e) => {
      e.preventDefault(); zone.classList.remove('is-drag');
      input.files = e.dataTransfer.files;
      showChip();
    });
    input.addEventListener('change', showChip);
    function showChip(){
      if (!input.files.length) return;
      chips.innerHTML = '';
      const chip = document.createElement('div');
      chip.className = 'file-chip';
      chip.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg><span></span>`;
      chip.querySelector('span').textContent = input.files[0].name;
      chips.appendChild(chip);
    }
  }
  wireUploadZone('heroZone', 'heroInput', 'heroChips');
  wireUploadZone('brochureZone', 'brochureInput', 'brochureChips');
})();
</script>
@endpush
