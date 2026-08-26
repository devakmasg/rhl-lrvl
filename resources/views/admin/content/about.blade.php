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
<form method="POST" action="{{ route('admin.content.about.update') }}" enctype="multipart/form-data">
  @csrf
  @method('PUT')

  <div class="page-head">
    <div>
      <h1>About Page</h1>
      <p>Feeds the About page, and also the Mission &amp; Vision page, the Managing Director&rsquo;s Message, and the intro block and teasers on the homepage.</p>
    </div>
    <div class="page-head-actions">
      @include('admin.partials.view-page', ['route' => 'about'])
      <button class="btn btn-primary" type="submit">Save Changes</button>
    </div>
  </div>

  <div class="card card-pad" style="margin-bottom:20px;">
    <h2 style="font-size:15.5px;margin-bottom:4px;">Intro Section</h2>
    <div class="card-head-sub" style="margin-bottom:16px;">The first block below the page banner. This same block also opens the homepage, so what you set here shows on both.</div>
    <div class="field-row" style="margin-bottom:16px;">
      <div class="field">
        <label for="introEyebrow">Eyebrow</label>
        <input type="text" id="introEyebrow" name="intro_eyebrow" value="{{ old('intro_eyebrow', $content['intro_eyebrow'] ?? '') }}">
      </div>
      <div class="field">
        <label for="introHeading">Heading</label>
        <input type="text" id="introHeading" name="intro_heading" value="{{ old('intro_heading', $content['intro_heading'] ?? '') }}">
        <span class="hint">Wrap any word in *asterisks* to italicise it &mdash; e.g. built on *trust*.</span>
      </div>
    </div>
    <div class="field-row" style="margin-bottom:16px;">
      <div class="field">
        <label for="introSinceLabel">First Label</label>
        <input type="text" id="introSinceLabel" name="intro_since_label" value="{{ old('intro_since_label', $content['intro_since_label'] ?? '') }}" style="margin-bottom:10px;">
        <label for="introSinceText">First Blurb</label>
        <textarea id="introSinceText" name="intro_since_text" style="min-height:70px;">{{ old('intro_since_text', $content['intro_since_text'] ?? '') }}</textarea>
      </div>
      <div class="field">
        <label for="introSpectrumLabel">Second Label</label>
        <input type="text" id="introSpectrumLabel" name="intro_spectrum_label" value="{{ old('intro_spectrum_label', $content['intro_spectrum_label'] ?? '') }}" style="margin-bottom:10px;">
        <label for="introSpectrumText">Second Blurb</label>
        <textarea id="introSpectrumText" name="intro_spectrum_text" style="min-height:70px;">{{ old('intro_spectrum_text', $content['intro_spectrum_text'] ?? '') }}</textarea>
      </div>
    </div>
    <div class="field-row">
      @include('admin.partials.image-field', [
        'name' => 'intro_image',
        'label' => 'Section Photo',
        'currentUrl' => $page->imageUrl('intro_image'),
      ])
      <div class="field">
        <label for="introBadgeNumber">Badge Number</label>
        <input type="text" id="introBadgeNumber" name="intro_badge_number" value="{{ old('intro_badge_number', $content['intro_badge_number'] ?? '') }}">
        <label for="introBadgeLabel" style="margin-top:12px;">Badge Label</label>
        <input type="text" id="introBadgeLabel" name="intro_badge_label" value="{{ old('intro_badge_label', $content['intro_badge_label'] ?? '') }}">
      </div>
    </div>
  </div>

  <div class="card card-pad" style="margin-bottom:20px;">
    <h2 style="font-size:15.5px;margin-bottom:16px;">Company Overview</h2>
    <div class="field" style="margin-bottom:16px;">
      <label for="overviewEyebrow">Eyebrow</label>
      <input type="text" id="overviewEyebrow" name="overview_eyebrow" value="{{ old('overview_eyebrow', $content['overview_eyebrow'] ?? '') }}" style="max-width:280px;">
    </div>
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
    <div class="field-row" style="margin-bottom:16px;">
      <div class="field">
        <label for="historyEyebrow">Eyebrow</label>
        <input type="text" id="historyEyebrow" name="history_eyebrow" value="{{ old('history_eyebrow', $content['history_eyebrow'] ?? '') }}">
      </div>
      <div class="field">
        <label for="historyHeading">Heading</label>
        <input type="text" id="historyHeading" name="history_heading" value="{{ old('history_heading', $content['history_heading'] ?? '') }}">
      </div>
    </div>
    <div id="milestonesList"></div>
  </div>

  <div class="card card-pad" style="margin-bottom:20px;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
      <h2 style="font-size:15.5px;">At-a-Glance Facts</h2>
      <button type="button" class="btn btn-outline btn-sm" id="addFact">+ Add Fact</button>
    </div>
    <div class="field" style="margin-bottom:16px;max-width:280px;">
      <label for="factsEyebrow">Eyebrow</label>
      <input type="text" id="factsEyebrow" name="facts_eyebrow" value="{{ old('facts_eyebrow', $content['facts_eyebrow'] ?? '') }}">
    </div>
    <div id="factsList"></div>
  </div>

  <div class="card card-pad" style="margin-bottom:20px;">
    <h2 style="font-size:15.5px;margin-bottom:16px;">Mission &amp; Vision</h2>
    <div class="field-row" style="margin-bottom:16px;">
      <div class="field">
        <label for="missionEyebrow">Mission Label</label>
        <input type="text" id="missionEyebrow" name="mission_eyebrow" value="{{ old('mission_eyebrow', $content['mission_eyebrow'] ?? '') }}" placeholder="Our Mission">
        <span class="hint">The small label above the mission card.</span>
      </div>
      <div class="field">
        <label for="visionEyebrow">Vision Label</label>
        <input type="text" id="visionEyebrow" name="vision_eyebrow" value="{{ old('vision_eyebrow', $content['vision_eyebrow'] ?? '') }}" placeholder="Our Vision">
        <span class="hint">The small label above the vision card.</span>
      </div>
    </div>
    <div class="field-row" style="margin-bottom:16px;">
      <div class="field">
        <label for="missionHeading">Mission Heading</label>
        <input type="text" id="missionHeading" name="mission_heading" value="{{ old('mission_heading', $content['mission_heading'] ?? '') }}">
      </div>
      <div class="field">
        <label for="visionHeading">Vision Heading</label>
        <input type="text" id="visionHeading" name="vision_heading" value="{{ old('vision_heading', $content['vision_heading'] ?? '') }}">
      </div>
    </div>
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
    <p class="hint" style="margin-bottom:16px;">These six fields drive both the Mission &amp; Vision page and the teaser on the homepage &mdash; edit them once here.</p>
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
      <label style="font-size:12.5px;font-weight:600;color:var(--charcoal-soft);">Core Values</label>
      <button type="button" class="btn btn-outline btn-sm" id="addValue">+ Add Value</button>
    </div>
    <div id="valuesList"></div>
  </div>

  <div class="card card-pad">
    <h2 style="font-size:15.5px;margin-bottom:16px;">Managing Director's Message</h2>
    <p class="hint" style="margin-bottom:16px;">
      The words below appear on the homepage teaser and the Managing Director's Message page.
      The MD's name, role and portrait are not edited here &mdash; they come from their entry in
      <a href="{{ route('admin.directors.index') }}">Directors &amp; Team</a>, so the same person shows everywhere.
    </p>
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

  <div class="card card-pad" style="margin-top:20px;">
    <h2 style="font-size:15.5px;margin-bottom:16px;">"Explore Further" Cards</h2>
    <div class="field-row" style="margin-bottom:16px;">
      <div class="field">
        <label for="quicklinksEyebrow">Eyebrow</label>
        <input type="text" id="quicklinksEyebrow" name="quicklinks_eyebrow" value="{{ old('quicklinks_eyebrow', $content['quicklinks_eyebrow'] ?? '') }}">
      </div>
      <div class="field">
        <label for="quicklinksHeading">Heading</label>
        <input type="text" id="quicklinksHeading" name="quicklinks_heading" value="{{ old('quicklinks_heading', $content['quicklinks_heading'] ?? '') }}">
      </div>
    </div>
    <p class="hint" style="margin-bottom:14px;">These 5 cards always link to Mission &amp; Vision, MD Message, Directors, Management and Achievements in that order — only the title and description below are editable.</p>
    @include('admin.content._fixed-rows', [
      'prefix' => 'quicklink', 'rows' => $content['quicklinks'] ?? [],
      'count' => 5, 'itemLabel' => 'Card',
    ])
  </div>

  <div class="card card-pad" style="margin-top:20px;">
    <h2 style="font-size:15.5px;margin-bottom:4px;">Statistics Band</h2>
    <div class="card-head-sub" style="margin-bottom:16px;">The dark band of counting numbers. It also appears on the homepage, so what you set here shows on both.</div>
    <div class="field-row" style="margin-bottom:16px;">
      <div class="field">
        <label for="statsEyebrow">Eyebrow</label>
        <input type="text" id="statsEyebrow" name="stats_eyebrow" value="{{ old('stats_eyebrow', $content['stats_eyebrow'] ?? '') }}">
      </div>
      <div class="field">
        <label for="statsHeading">Heading</label>
        <input type="text" id="statsHeading" name="stats_heading" value="{{ old('stats_heading', $content['stats_heading'] ?? '') }}">
        <span class="hint">Leave blank to show the numbers with no heading.</span>
      </div>
    </div>
    <div style="margin-bottom:18px;max-width:420px;">
      @include('admin.partials.image-field', [
        'name' => 'stats_background',
        'label' => 'Band Background Photo',
        'currentUrl' => $page->imageUrl('stats_background'),
        'hint' => 'Sits behind the statistics, darkened automatically.',
      ])
    </div>
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
      <label style="font-size:12.5px;font-weight:600;color:var(--charcoal-soft);">Stats</label>
      <button type="button" class="btn btn-outline btn-sm" id="addAboutStat">+ Add Stat</button>
    </div>
    <div id="aboutStatsList"></div>
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

  makeRepeater(document.getElementById('aboutStatsList'), document.getElementById('addAboutStat'),
    (v) => `<div class="field" style="max-width:130px;"><input type="text" name="stat_value[]" placeholder="Value, e.g. 6.4M+" value="${esc(v.value)}"></div>
            <div class="field"><input type="text" name="stat_label[]" placeholder="Label" value="${esc(v.label)}"></div>`,
    @json(old('stat_value') ? collect(old('stat_value'))->map(fn($t, $i) => ['value' => $t, 'label' => old('stat_label')[$i] ?? ''])->values() : ($content['stats'] ?? [])));
</script>
@endpush
