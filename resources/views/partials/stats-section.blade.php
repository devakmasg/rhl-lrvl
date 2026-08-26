{{--
  The dark statistics band, shown on both the homepage and the About page.
  Both read the about row, so the numbers and the framing around them are
  edited once — see PageController::about() and HomeController::index().

  The background is optional: stats.js falls back to its own image when
  data-bg is empty, which is what both pages showed before this was editable.

  Expects:
    $page   the row holding stats, stats_eyebrow, stats_heading, stats_background
--}}
<section class="stats">
  <div class="stats-bg" id="statsBg" data-parallax-bg="0.25" data-bg="{{ $page->imageUrl('stats_background') }}"></div>
  <div class="stats-inner wrap">
    <span class="intro-tag reveal-up" style="color:var(--gold-light)">{{ $page->get('stats_eyebrow') }}</span>
    @if ($page->get('stats_heading'))
      <h2 class="reveal-up" style="max-width:640px;margin-bottom:50px;">{{ $page->get('stats_heading') }}</h2>
    @endif
    <div class="stats-grid">
      @foreach ($page->list('stats') as $stat)
        @php
          preg_match('/^([\d.]+)(.*)$/', $stat['value'], $m);
          $decimals = str_contains($m[1] ?? '', '.') ? strlen(explode('.', $m[1])[1]) : 0;
        @endphp
        <div class="stat reveal-card"><div class="num" data-target="{{ $m[1] ?? $stat['value'] }}" data-decimals="{{ $decimals }}" data-suffix="{{ $m[2] ?? '' }}">0</div><div class="label">{{ $stat['label'] }}</div></div>
      @endforeach
    </div>
  </div>
</section>
