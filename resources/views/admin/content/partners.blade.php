@extends('layouts.admin')

@section('title', 'Partners Page')

@push('head')
<style>
  .repeater-row{display:flex;gap:10px;align-items:center;margin-bottom:10px;}
  .repeater-row .field{flex:1;margin-bottom:0;}
  .repeater-remove{flex:none;width:34px;height:34px;border-radius:8px;border:1px solid var(--line);background:var(--surface);color:var(--danger);display:flex;align-items:center;justify-content:center;}
  .repeater-remove:hover{background:var(--danger-bg);}
  .repeater-remove svg{width:14px;height:14px;}
  .audience-tabs{display:flex;gap:8px;margin-bottom:16px;}
  .audience-tab-btn{padding:8px 16px;border-radius:8px;border:1px solid var(--line);background:var(--surface);cursor:pointer;font-size:13px;}
  .audience-tab-btn.is-active{background:var(--gold);color:var(--dark);border-color:var(--gold);}
  .audience-tab-panel{display:none;}
  .audience-tab-panel.is-active{display:block;}
</style>
@endpush

@section('content')
<form method="POST" action="{{ route('admin.content.partners.update') }}" enctype="multipart/form-data">
  @csrf
  @method('PUT')

  <div class="page-head">
    <div>
      <h1>Partners Page</h1>
      <p>Feeds the Investors &amp; Landowners page — everything except the header banner (see Page Headers) and the submission form.</p>
    </div>
    <div class="page-head-actions">
      @include('admin.partials.view-page', ['route' => 'partners'])
      <button class="btn btn-primary" type="submit">Save Changes</button>
    </div>
  </div>

  <div class="card card-pad" style="margin-bottom:20px;">
    <h2 style="font-size:15.5px;margin-bottom:16px;">Intro</h2>
    <div class="field-row" style="margin-bottom:16px;">
      <div class="field">
        <label for="introEyebrow">Eyebrow</label>
        <input type="text" id="introEyebrow" name="intro_eyebrow" value="{{ old('intro_eyebrow', $content['intro_eyebrow'] ?? '') }}">
      </div>
      <div class="field">
        <label for="introHeading">Heading</label>
        <input type="text" id="introHeading" name="intro_heading" value="{{ old('intro_heading', $content['intro_heading'] ?? '') }}" required>
      </div>
    </div>
    <div class="field-row" style="margin-bottom:16px;">
      <div class="field">
        <label for="introText1">Paragraph 1</label>
        <textarea id="introText1" name="intro_text_1" style="min-height:80px;">{{ old('intro_text_1', $content['intro_text_1'] ?? '') }}</textarea>
      </div>
      <div class="field">
        <label for="introText2">Paragraph 2</label>
        <textarea id="introText2" name="intro_text_2" style="min-height:80px;">{{ old('intro_text_2', $content['intro_text_2'] ?? '') }}</textarea>
      </div>
    </div>
    @include('admin.partials.image-field', [
      'name' => 'intro_image',
      'label' => 'Photo',
      'currentUrl' => $page->imageUrl('intro_image'),
    ])
  </div>

  <div class="card card-pad" style="margin-bottom:20px;">
    <h2 style="font-size:15.5px;margin-bottom:16px;">"How It Works" Head</h2>
    <div class="field-row">
      <div class="field">
        <label for="howEyebrow">Eyebrow</label>
        <input type="text" id="howEyebrow" name="how_eyebrow" value="{{ old('how_eyebrow', $content['how_eyebrow'] ?? '') }}">
      </div>
      <div class="field">
        <label for="howHeading">Heading</label>
        <input type="text" id="howHeading" name="how_heading" value="{{ old('how_heading', $content['how_heading'] ?? '') }}">
      </div>
    </div>
  </div>

  <div class="card card-pad" style="margin-bottom:20px;">
    <div class="audience-tabs">
      <button type="button" class="audience-tab-btn is-active" data-tab="landowner">For Landowners</button>
      <button type="button" class="audience-tab-btn" data-tab="investor">For Investors</button>
    </div>

    <div class="audience-tab-panel is-active" data-panel="landowner">
      <div class="field" style="margin-bottom:16px;">
        <label for="landownerLead">Lead Paragraph</label>
        <textarea id="landownerLead" name="landowner_lead" style="min-height:70px;">{{ old('landowner_lead', $content['landowner_lead'] ?? '') }}</textarea>
      </div>

      <h3 style="font-size:13px;text-transform:uppercase;letter-spacing:.06em;color:var(--stone);margin-bottom:10px;">Pillars (4)</h3>
      @include('admin.content._fixed-rows', [
        'prefix' => 'landowner_pillar', 'rows' => $content['landowner_pillars'] ?? [],
        'count' => 4, 'itemLabel' => 'Pillar',
      ])

      <h3 style="font-size:13px;text-transform:uppercase;letter-spacing:.06em;color:var(--stone);margin:18px 0 10px;">Process Steps (5)</h3>
      @include('admin.content._fixed-rows', [
        'prefix' => 'landowner_step', 'rows' => $content['landowner_steps'] ?? [],
        'count' => 5, 'itemLabel' => 'Step',
      ])
    </div>

    <div class="audience-tab-panel" data-panel="investor">
      <div class="field" style="margin-bottom:16px;">
        <label for="investorLead">Lead Paragraph</label>
        <textarea id="investorLead" name="investor_lead" style="min-height:70px;">{{ old('investor_lead', $content['investor_lead'] ?? '') }}</textarea>
      </div>

      <h3 style="font-size:13px;text-transform:uppercase;letter-spacing:.06em;color:var(--stone);margin-bottom:10px;">Pillars (4)</h3>
      @include('admin.content._fixed-rows', [
        'prefix' => 'investor_pillar', 'rows' => $content['investor_pillars'] ?? [],
        'count' => 4, 'itemLabel' => 'Pillar',
      ])

      <h3 style="font-size:13px;text-transform:uppercase;letter-spacing:.06em;color:var(--stone);margin:18px 0 10px;">Process Steps (5)</h3>
      @include('admin.content._fixed-rows', [
        'prefix' => 'investor_step', 'rows' => $content['investor_steps'] ?? [],
        'count' => 5, 'itemLabel' => 'Step',
      ])
    </div>
  </div>

  <div class="card card-pad" style="margin-bottom:20px;">
    <h2 style="font-size:15.5px;margin-bottom:16px;">Track Record Stats</h2>
    <div class="field-row" style="margin-bottom:16px;">
      <div class="field">
        <label for="statsEyebrow">Eyebrow</label>
        <input type="text" id="statsEyebrow" name="stats_eyebrow" value="{{ old('stats_eyebrow', $content['stats_eyebrow'] ?? '') }}">
      </div>
      <div class="field">
        <label for="statsHeading">Heading</label>
        <input type="text" id="statsHeading" name="stats_heading" value="{{ old('stats_heading', $content['stats_heading'] ?? '') }}">
      </div>
    </div>
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
      <label style="font-size:12.5px;font-weight:600;color:var(--charcoal-soft);">Stats</label>
      <button type="button" class="btn btn-outline btn-sm" id="addStat">+ Add Stat</button>
    </div>
    <div id="statsList"></div>
  </div>

  <div class="card card-pad" style="margin-bottom:20px;">
    <h2 style="font-size:15.5px;margin-bottom:16px;">Contact Section Head</h2>
    <div class="field-row" style="margin-bottom:16px;">
      <div class="field">
        <label for="contactEyebrow">Eyebrow</label>
        <input type="text" id="contactEyebrow" name="contact_eyebrow" value="{{ old('contact_eyebrow', $content['contact_eyebrow'] ?? '') }}">
      </div>
      <div class="field">
        <label for="contactHeading">Heading</label>
        <input type="text" id="contactHeading" name="contact_heading" value="{{ old('contact_heading', $content['contact_heading'] ?? '') }}">
      </div>
    </div>
    <div class="field">
      <label for="contactText">Lead Paragraph</label>
      <textarea id="contactText" name="contact_text" style="min-height:70px;">{{ old('contact_text', $content['contact_text'] ?? '') }}</textarea>
    </div>
    <p class="hint" style="margin-top:8px;">The "Partnership desk" phone and email in the sidebar come from Settings, not this page.</p>
  </div>

  <div class="card card-pad">
    <h2 style="font-size:15.5px;margin-bottom:16px;">Sidebar Cards</h2>
    <div class="field" style="margin-bottom:16px;">
      <label for="asideReady">"What to have ready"</label>
      <textarea id="asideReady" name="aside_ready_text" style="min-height:60px;">{{ old('aside_ready_text', $content['aside_ready_text'] ?? '') }}</textarea>
    </div>
    <div class="field" style="margin-bottom:16px;">
      <label for="asideTimeline">"Typical timeline"</label>
      <textarea id="asideTimeline" name="aside_timeline_text" style="min-height:60px;">{{ old('aside_timeline_text', $content['aside_timeline_text'] ?? '') }}</textarea>
    </div>
    <div class="field">
      <label for="asideWork">"See the work first"</label>
      <textarea id="asideWork" name="aside_work_text" style="min-height:60px;">{{ old('aside_work_text', $content['aside_work_text'] ?? '') }}</textarea>
    </div>
  </div>
</form>
@endsection

@push('scripts')
<script>
  document.querySelectorAll('.audience-tab-btn').forEach((btn) => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.audience-tab-btn').forEach((b) => b.classList.remove('is-active'));
      document.querySelectorAll('.audience-tab-panel').forEach((p) => p.classList.remove('is-active'));
      btn.classList.add('is-active');
      document.querySelector(`[data-panel="${btn.dataset.tab}"]`).classList.add('is-active');
    });
  });

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

  makeRepeater(document.getElementById('statsList'), document.getElementById('addStat'),
    (v) => `<div class="field" style="max-width:130px;"><input type="text" name="stat_value[]" placeholder="Value, e.g. 52+" value="${esc(v.value)}"></div>
            <div class="field"><input type="text" name="stat_label[]" placeholder="Label" value="${esc(v.label)}"></div>`,
    @json(old('stat_value') ? collect(old('stat_value'))->map(fn($t, $i) => ['value' => $t, 'label' => old('stat_label')[$i] ?? ''])->values() : ($content['stats'] ?? [])));
</script>
@endpush
