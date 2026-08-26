{{--
  The intro block shown as "Our Story" on the homepage and "Who We Are" on the
  About page. Both render the same markup from their own page row, so the copy
  stays independently editable while the layout lives in one place.

  Partners has a visually similar intro but a different grid and content shape
  (.partner-intro, free paragraphs, no badge), so it keeps its own markup.

  Expects:
    $page   the page row to read the intro_* keys from
  Optional:
    $id     anchor id for the section
    $link   ['url' => ..., 'label' => ...] arrow link below the copy
--}}
@php
  $introBadgeNumber = $page->get('intro_badge_number');
  $introBadgeLabel = $page->get('intro_badge_label');
  $introLink = $link ?? null;
@endphp
<section class="intro"@isset($id) id="{{ $id }}"@endisset>
  <div class="wrap intro-grid">
    <div>
      <span class="intro-tag reveal-up">{{ $page->get('intro_eyebrow') }}</span>
      <h2 class="reveal-up">{!! \App\Support\Copy::emphasise($page->get('intro_heading')) !!}</h2>
      <div class="intro-foot">
        <div class="reveal-up"><strong>{{ $page->get('intro_since_label') }}</strong>{{ $page->get('intro_since_text') }}</div>
        <div class="reveal-up"><strong>{{ $page->get('intro_spectrum_label') }}</strong>{{ $page->get('intro_spectrum_text') }}</div>
      </div>
      @if ($introLink)
        <a href="{{ $introLink['url'] }}" class="link-arrow reveal-up">{{ $introLink['label'] }} &rarr;</a>
      @endif
    </div>
    <div class="intro-media reveal-up">
      <img data-parallax="0.15" decoding="async" loading="lazy"
           src="{{ $page->imageUrl('intro_image') }}"
           alt="{{ \App\Support\Brand::name() }} signature development">
      @if ($introBadgeNumber || $introBadgeLabel)
        <div class="badge"><div class="n">{{ $introBadgeNumber }}</div><div class="l">{{ $introBadgeLabel }}</div></div>
      @endif
    </div>
  </div>
</section>
