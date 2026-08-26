@extends('layouts.admin')

@section('title', 'Page Sections')

@push('head')
<style>
  .ps-item{border:1px solid var(--line);border-radius:10px;margin-bottom:14px;background:var(--surface);overflow:hidden;}
  .ps-head{display:flex;align-items:center;gap:14px;padding:14px 16px;cursor:pointer;}
  .ps-head:hover{background:var(--surface-2, rgba(0,0,0,.02));}
  .ps-meta{flex:1;min-width:0;}
  .ps-meta .k{font-size:14px;font-weight:600;}
  .ps-meta .v{font-size:12px;color:var(--stone);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
  .ps-count{flex:none;font-size:11.5px;color:var(--stone);border:1px solid var(--line);border-radius:20px;padding:2px 10px;}
  .ps-caret{transition:transform .15s;flex:none;width:16px;height:16px;color:var(--stone);}
  .ps-item.is-open .ps-caret{transform:rotate(90deg);}
  .ps-body{display:none;padding:0 16px 18px;border-top:1px solid var(--line);}
  .ps-item.is-open .ps-body{display:block;}
  .ps-sec{border:1px solid var(--line);border-radius:8px;padding:14px;margin-top:14px;}
  .ps-sec h4{font-size:11.5px;text-transform:uppercase;letter-spacing:.08em;color:var(--stone);margin-bottom:12px;font-weight:600;}
  .ps-cols{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
  @media (max-width:900px){.ps-cols{grid-template-columns:1fr;}}
  .ps-tokens{font-size:12px;color:var(--stone);margin-top:16px;line-height:1.7;}
  .ps-tokens code{font-size:11.5px;background:var(--line);border-radius:4px;padding:1px 5px;}
</style>
@endpush

@section('content')
<div class="page-head">
  <div>
    <h1>Page Sections</h1>
    <p>The small label and heading above each block on these pages. Leave a field blank to hide it.</p>
  </div>
</div>

@forelse ($groups as $pageKey => $sections)
  <div class="ps-item" id="ps-{{ $pageKey }}">
    <div class="ps-head" role="button" tabindex="0" data-ps-toggle aria-expanded="false">
      <svg class="ps-caret" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
      <div class="ps-meta">
        <div class="k">{{ $sections->first()->page_label }}</div>
        <div class="v">{{ $sections->pluck('label')->implode(' · ') }}</div>
      </div>
      <span class="ps-count">{{ $sections->count() }} sections</span>
    </div>

    <div class="ps-body">
      <form method="POST" action="{{ route('admin.page-sections.update', $pageKey) }}">
        @csrf
        @method('PUT')

        @if (in_array($pageKey, ['project_detail', 'news_detail'], true))
          <p class="card-head-sub" style="margin-top:14px;">One template serves every {{ $pageKey === 'project_detail' ? 'project' : 'article' }}, so these headings appear on all of them.</p>
        @endif

        @foreach ($sections as $section)
          <div class="ps-sec">
            <h4>{{ $section->label }}</h4>
            <div class="ps-cols">
              @if ($section->eyebrow !== null || $section->heading === null)
                <div class="field">
                  <label>Eyebrow</label>
                  <input type="text" name="sections[{{ $section->id }}][eyebrow]" value="{{ old("sections.{$section->id}.eyebrow", $section->eyebrow) }}">
                  <span class="hint">Small label above the heading.</span>
                </div>
              @endif
              @if ($section->heading !== null || $section->eyebrow === null)
                <div class="field">
                  <label>Heading</label>
                  <input type="text" name="sections[{{ $section->id }}][heading]" value="{{ old("sections.{$section->id}.heading", $section->heading) }}">
                </div>
              @endif
            </div>
            @if ($section->body !== null)
              <div class="field" style="margin-top:14px;">
                <label>Text</label>
                <textarea name="sections[{{ $section->id }}][body]" style="min-height:70px;">{{ old("sections.{$section->id}.body", $section->body) }}</textarea>
              </div>
            @endif
            @if ($section->link_label !== null)
              <div class="field" style="margin-top:14px;max-width:320px;">
                <label>Link Label</label>
                <input type="text" name="sections[{{ $section->id }}][link_label]" value="{{ old("sections.{$section->id}.link_label", $section->link_label) }}">
                <span class="hint">Where it goes is fixed — only the words change.</span>
              </div>
            @endif
          </div>
        @endforeach

        <p class="ps-tokens">
          You can use these anywhere above &mdash; they fill in automatically:
          @foreach (\App\Support\Tokens::AVAILABLE as $token => $desc)
            <code>{{ $token }}</code> {{ $desc }}@if (! $loop->last) &middot; @endif
          @endforeach
        </p>

        <div style="margin-top:16px;display:flex;justify-content:flex-end;">
          <button class="btn btn-primary" type="submit">Save &ldquo;{{ $sections->first()->page_label }}&rdquo;</button>
        </div>
      </form>
    </div>
  </div>
@empty
  <div class="empty-state">
    <h3>No page sections yet</h3>
    <p>Run <code>php artisan migrate</code> to create them.</p>
  </div>
@endforelse
@endsection

@push('scripts')
<script>
  document.querySelectorAll('[data-ps-toggle]').forEach((head) => {
    const item = head.closest('.ps-item');
    const toggle = () => {
      const open = item.classList.toggle('is-open');
      head.setAttribute('aria-expanded', open ? 'true' : 'false');
    };
    head.addEventListener('click', toggle);
    head.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggle(); }
    });
  });

  if (location.hash.startsWith('#ps-')) {
    const target = document.querySelector(location.hash);
    if (target) target.classList.add('is-open');
  }
</script>
@endpush
