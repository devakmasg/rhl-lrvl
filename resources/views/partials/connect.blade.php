{{-- The closing call-to-action band, shared by every page that ends with one.

     $cta is bound by the CtaBlock view composer in AppServiceProvider, keyed
     off the current route name — the same way $banner is. A page whose row is
     missing or whose cards are all empty renders nothing at all, rather than
     an empty section with padding. --}}
@php
  $cta = $cta ?? null;
  $ctaCards = $cta?->resolvedCards() ?? [];
  $ctaEyebrow = $cta?->resolvedEyebrow();
  $ctaHeading = $cta?->resolvedHeading();
@endphp
@if ($ctaCards)
  <section class="connect" @if ($cta->section_id) id="{{ $cta->section_id }}" @endif>
    @if ($ctaEyebrow)
      <span class="intro-tag reveal-up">{{ $ctaEyebrow }}</span>
    @endif
    @if ($ctaHeading)
      <h2 class="reveal-up">{{ $ctaHeading }}</h2>
    @endif
    <div class="connect-grid">
      @foreach ($ctaCards as $card)
        <div class="connect-card reveal-card">
          <h3>{{ $card['title'] }}</h3>
          <p>{{ $card['text'] }}</p>
          @if ($card['btn_label'] && $card['btn_url'])
            <a href="{{ $card['btn_url'] }}" class="btn">{{ $card['btn_label'] }} &rarr;</a>
          @endif
        </div>
      @endforeach
    </div>
  </section>
@endif
