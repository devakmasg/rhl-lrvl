@extends('layouts.admin')

@section('title', 'Homepage')

@push('head')
<style>
  .repeater-row{display:flex;gap:10px;align-items:center;margin-bottom:10px;}
  .repeater-row .field{flex:1;margin-bottom:0;}
  .repeater-remove{flex:none;width:34px;height:34px;border-radius:8px;border:1px solid var(--line);background:var(--surface);color:var(--danger);display:flex;align-items:center;justify-content:center;}
  .repeater-remove:hover{background:var(--danger-bg);}
  .repeater-remove svg{width:14px;height:14px;}
</style>
@endpush

@section('content')
<form method="POST" action="{{ route('admin.content.home.update') }}">
  @csrf
  @method('PUT')

  <div class="page-head">
    <div>
      <h1>Homepage</h1>
      <p>Hero, intro, why-choose-us and key statistics on index.html.</p>
    </div>
    <div class="page-head-actions">
      <button class="btn btn-primary" type="submit">Save Changes</button>
    </div>
  </div>

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
        <label for="heroLabel">Slide Label</label>
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
        <label for="introSince">"Since 1998" Blurb</label>
        <textarea id="introSince" name="intro_since_text" style="min-height:70px;">{{ old('intro_since_text', $content['intro_since_text'] ?? '') }}</textarea>
      </div>
      <div class="field">
        <label for="introSpectrum">"Full Spectrum" Blurb</label>
        <textarea id="introSpectrum" name="intro_spectrum_text" style="min-height:70px;">{{ old('intro_spectrum_text', $content['intro_spectrum_text'] ?? '') }}</textarea>
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

  <div class="card card-pad">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
      <h2 style="font-size:15.5px;">Key Statistics</h2>
      <button type="button" class="btn btn-outline btn-sm" id="addStat">+ Add Stat</button>
    </div>
    <div id="statsList"></div>
  </div>
</form>
@endsection

@push('scripts')
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
</script>
@endpush
