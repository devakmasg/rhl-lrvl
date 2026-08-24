@extends('layouts.admin')

@section('title', 'About Page')

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
<form method="POST" action="{{ route('admin.content.about.update') }}">
  @csrf
  @method('PUT')

  <div class="page-head">
    <div>
      <h1>About Page</h1>
      <p>Feeds about.html, mission-vision.html and md-message.html.</p>
    </div>
    <div class="page-head-actions">
      <button class="btn btn-primary" type="submit">Save Changes</button>
    </div>
  </div>

  <div class="card card-pad" style="margin-bottom:20px;">
    <h2 style="font-size:15.5px;margin-bottom:16px;">Company Overview</h2>
    <div class="field" style="margin-bottom:16px;">
      <label for="aboutHeadline">Page Headline</label>
      <input type="text" id="aboutHeadline" name="headline" value="{{ old('headline', $content['headline'] ?? '') }}" required>
    </div>
    <div class="field">
      <label for="aboutOverview">Overview Paragraphs</label>
      <textarea id="aboutOverview" name="overview" style="min-height:110px;" placeholder="One paragraph per blank-line-separated block.">{{ old('overview', $overviewText) }}</textarea>
      <span class="hint">Separate paragraphs with a blank line.</span>
    </div>
  </div>

  <div class="card card-pad" style="margin-bottom:20px;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
      <h2 style="font-size:15.5px;">Company History (Milestones)</h2>
      <button type="button" class="btn btn-outline btn-sm" id="addMilestone">+ Add Milestone</button>
    </div>
    <div id="milestonesList"></div>
  </div>

  <div class="card card-pad" style="margin-bottom:20px;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
      <h2 style="font-size:15.5px;">At-a-Glance Facts</h2>
      <button type="button" class="btn btn-outline btn-sm" id="addFact">+ Add Fact</button>
    </div>
    <div id="factsList"></div>
  </div>

  <div class="card card-pad" style="margin-bottom:20px;">
    <h2 style="font-size:15.5px;margin-bottom:16px;">Mission &amp; Vision</h2>
    <div class="field-row" style="margin-bottom:16px;">
      <div class="field">
        <label for="missionText">Mission</label>
        <textarea id="missionText" name="mission" style="min-height:90px;">{{ old('mission', $content['mission'] ?? '') }}</textarea>
      </div>
      <div class="field">
        <label for="visionText">Vision</label>
        <textarea id="visionText" name="vision" style="min-height:90px;">{{ old('vision', $content['vision'] ?? '') }}</textarea>
      </div>
    </div>
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
      <label style="font-size:12.5px;font-weight:600;color:var(--charcoal-soft);">Core Values</label>
      <button type="button" class="btn btn-outline btn-sm" id="addValue">+ Add Value</button>
    </div>
    <div id="valuesList"></div>
  </div>

  <div class="card card-pad">
    <h2 style="font-size:15.5px;margin-bottom:16px;">Managing Director's Message</h2>
    <div class="field-row" style="margin-bottom:16px;">
      <div class="field">
        <label for="mdName">Name</label>
        <input type="text" id="mdName" name="md_name" value="{{ old('md_name', $content['md_name'] ?? '') }}">
      </div>
      <div class="field">
        <label for="mdPhoto">Portrait URL</label>
        <input type="url" id="mdPhoto" name="md_photo" value="{{ old('md_photo', $content['md_photo'] ?? '') }}">
      </div>
    </div>
    <div class="field" style="margin-bottom:16px;">
      <label for="mdQuote">Pull Quote</label>
      <textarea id="mdQuote" name="md_quote" style="min-height:60px;">{{ old('md_quote', $content['md_quote'] ?? '') }}</textarea>
    </div>
    <div class="field">
      <label for="mdMessage">Full Message</label>
      <textarea id="mdMessage" name="md_message" style="min-height:130px;" placeholder="One paragraph per blank-line-separated block.">{{ old('md_message', $mdMessageText) }}</textarea>
      <span class="hint">Separate paragraphs with a blank line.</span>
    </div>
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

  makeRepeater(document.getElementById('milestonesList'), document.getElementById('addMilestone'),
    (v) => `<div class="field" style="max-width:110px;"><input type="text" name="milestone_year[]" placeholder="Year" value="${esc(v.year)}"></div>
            <div class="field"><input type="text" name="milestone_text[]" placeholder="Milestone description" value="${esc(v.text)}"></div>`,
    @json(old('milestone_year') ? collect(old('milestone_year'))->map(fn($t, $i) => ['year' => $t, 'text' => old('milestone_text')[$i] ?? ''])->values() : ($content['milestones'] ?? [])));

  makeRepeater(document.getElementById('factsList'), document.getElementById('addFact'),
    (v) => `<div class="field"><input type="text" name="fact_k[]" placeholder="Label" value="${esc(v.k)}"></div>
            <div class="field"><input type="text" name="fact_v[]" placeholder="Value" value="${esc(v.v)}"></div>`,
    @json(old('fact_k') ? collect(old('fact_k'))->map(fn($t, $i) => ['k' => $t, 'v' => old('fact_v')[$i] ?? ''])->values() : ($content['facts'] ?? [])));

  makeRepeater(document.getElementById('valuesList'), document.getElementById('addValue'),
    (v) => `<div class="field"><input type="text" name="value_title[]" placeholder="Value title" value="${esc(v.title)}"></div>
            <div class="field"><input type="text" name="value_desc[]" placeholder="Short description" value="${esc(v.desc)}"></div>`,
    @json(old('value_title') ? collect(old('value_title'))->map(fn($t, $i) => ['title' => $t, 'desc' => old('value_desc')[$i] ?? ''])->values() : ($content['core_values'] ?? [])));
</script>
@endpush
