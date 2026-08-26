@extends('layouts.admin')

@section('title', 'Page CTAs')

@push('head')
<style>
  .cta-item{border:1px solid var(--line);border-radius:10px;margin-bottom:14px;overflow:hidden;background:var(--surface);}
  .cta-head{display:flex;align-items:center;gap:14px;padding:14px 16px;cursor:pointer;}
  .cta-head:hover{background:var(--surface-2, rgba(0,0,0,.02));}
  .cta-meta{flex:1;min-width:0;}
  .cta-meta .k{font-size:14px;font-weight:600;}
  .cta-meta .v{font-size:12px;color:var(--stone);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
  .cta-body{display:none;padding:0 16px 18px;border-top:1px solid var(--line);}
  .cta-item.is-open .cta-body{display:block;}
  .cta-item.is-open .cta-head{background:var(--surface-2, rgba(0,0,0,.02));}
  .cta-caret{transition:transform .15s;flex:none;width:16px;height:16px;color:var(--stone);}
  .cta-item.is-open .cta-caret{transform:rotate(90deg);}
  .cta-count{flex:none;font-size:11.5px;color:var(--stone);border:1px solid var(--line);border-radius:20px;padding:2px 10px;}
  .cta-cards{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:16px;}
  @media (max-width:900px){.cta-cards{grid-template-columns:1fr;}}
  .cta-card{border:1px solid var(--line);border-radius:8px;padding:14px;background:var(--bg, transparent);}
  .cta-card h4{font-size:11.5px;text-transform:uppercase;letter-spacing:.08em;color:var(--stone);margin-bottom:12px;font-weight:600;}
  .cta-tokens{font-size:12px;color:var(--stone);margin-top:14px;line-height:1.7;}
  .cta-tokens code{font-size:11.5px;background:var(--line);border-radius:4px;padding:1px 5px;}
</style>
@endpush

@section('content')
<div class="page-head">
  <div>
    <h1>Page CTAs</h1>
    <p>The two call-to-action cards that close each page. Edited here for every page at once.</p>
  </div>
</div>

@forelse ($blocks as $block)
  <div class="cta-item" id="cta-{{ $block->id }}">
    <div class="cta-head" role="button" tabindex="0" data-cta-toggle aria-expanded="false" aria-controls="cta-body-{{ $block->id }}">
      <svg class="cta-caret" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
      <div class="cta-meta">
        <div class="k">{{ $block->label }}</div>
        <div class="v">{{ $block->heading ?: ($block->eyebrow ?: 'Cards only, no heading') }}</div>
      </div>
      <span class="cta-count">{{ count($block->cards ?? []) }} cards</span>
    </div>

    <div class="cta-body" id="cta-body-{{ $block->id }}">
      <form method="POST" action="{{ route('admin.cta-blocks.update', $block) }}">
        @csrf
        @method('PUT')

        <div class="cta-cards" style="grid-template-columns:1fr 1fr;">
          <div class="field">
            <label>Eyebrow</label>
            <input type="text" name="eyebrow" value="{{ old('eyebrow', $block->eyebrow) }}" placeholder="Continue Exploring">
            <span class="hint">Small label above the heading. Leave blank to hide it.</span>
          </div>
          <div class="field">
            <label>Heading</label>
            <input type="text" name="heading" value="{{ old('heading', $block->heading) }}">
            <span class="hint">Leave blank to show the cards with no heading.</span>
          </div>
        </div>

        <div class="cta-cards">
          @foreach (range(0, 1) as $i)
            @php($card = ($block->cards ?? [])[$i] ?? [])
            <div class="cta-card">
              <h4>Card {{ $i + 1 }}</h4>
              <div class="field" style="margin-bottom:12px;">
                <label>Title</label>
                <input type="text" name="cards[{{ $i }}][title]" value="{{ old("cards.$i.title", $card['title'] ?? '') }}">
              </div>
              <div class="field" style="margin-bottom:12px;">
                <label>Text</label>
                <textarea name="cards[{{ $i }}][text]" style="min-height:74px;">{{ old("cards.$i.text", $card['text'] ?? '') }}</textarea>
              </div>
              <div class="field" style="margin-bottom:12px;">
                <label>Button Label</label>
                <input type="text" name="cards[{{ $i }}][btn_label]" value="{{ old("cards.$i.btn_label", $card['btn_label'] ?? '') }}">
                <span class="hint">Leave blank to show the card with no button.</span>
              </div>
              <div class="field">
                <label>Button Links To</label>
                <input type="text" name="cards[{{ $i }}][btn_url]" value="{{ old("cards.$i.btn_url", $card['btn_url'] ?? '') }}" placeholder="projects.index">
                <span class="hint">A page name like <code>projects.index</code> or <code>partners#investors</code>, or a full address starting with <code>/</code> or <code>https://</code>.</span>
                <span class="field-error">{{ $errors->first("cards.$i.btn_url") }}</span>
              </div>
            </div>
          @endforeach
        </div>

        <p class="cta-tokens">
          Leave a card's title and text both blank to remove it from the page.<br>
          You can use these anywhere above &mdash; they fill in automatically, so the copy never goes stale:
          @foreach (\App\Models\CtaBlock::TOKENS as $token => $desc)
            <code>{{ $token }}</code> {{ $desc }}@if (! $loop->last) &middot; @endif
          @endforeach
        </p>

        <div style="margin-top:16px;display:flex;justify-content:flex-end;">
          <button class="btn btn-primary" type="submit">Save &ldquo;{{ $block->label }}&rdquo;</button>
        </div>
      </form>
    </div>
  </div>
@empty
  <div class="empty-state">
    <h3>No page CTAs yet</h3>
    <p>Run <code>php artisan migrate</code> to create them.</p>
  </div>
@endforelse
@endsection

@push('scripts')
<script>
  document.querySelectorAll('[data-cta-toggle]').forEach((head) => {
    const item = head.closest('.cta-item');
    const toggle = () => {
      const open = item.classList.toggle('is-open');
      head.setAttribute('aria-expanded', open ? 'true' : 'false');
    };
    head.addEventListener('click', toggle);
    head.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggle(); }
    });
  });

  // Deep-link back to the row that was just saved.
  if (location.hash.startsWith('#cta-')) {
    const target = document.querySelector(location.hash);
    if (target) target.classList.add('is-open');
  }
</script>
@endpush
