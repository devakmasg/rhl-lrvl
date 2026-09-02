{{-- The "Trusted Partners" logo strip, shown above the footer on every page.

     Logos come from the partners table (admin → Trusted Partners); the eyebrow,
     heading and the on/off switch are site-wide copy on the settings row. Both
     are bound by the view composer in AppServiceProvider, so the layout can
     include this without every controller having to load it.

     The strip is a continuous marquee rather than a paged slider: the list is
     rendered twice and the track travels exactly half its own width, so the
     second copy lands where the first started and the loop is seamless. That
     needs no JS — which is why it can sit in the layout without every page
     pulling in a slider script. --}}
@if ($partners->isNotEmpty())
  <section class="partners-strip">
    @if ($partnersEyebrow || $partnersHeading)
      <div class="wrap partners-strip-head">
        @if ($partnersEyebrow)
          <span class="intro-tag">{{ $partnersEyebrow }}</span>
        @endif
        @if ($partnersHeading)
          <h2>{{ $partnersHeading }}</h2>
        @endif
      </div>
    @endif

    <div class="partners-marquee">
      <div class="partners-track">
        {{-- The second pass is the same list again, so it is hidden from
             assistive tech and taken out of the tab order. --}}
        @foreach ([false, true] as $isDuplicate)
          <ul class="partners-row" @if ($isDuplicate) aria-hidden="true" @endif>
            @foreach ($partners as $partner)
              <li class="partner-item">
                @if ($partner->website)
                  <a class="partner-card" href="{{ $partner->website }}" target="_blank" rel="noopener" @if ($isDuplicate) tabindex="-1" @endif>
                    <img class="partner-logo" src="{{ $partner->logo_url }}" alt="" loading="lazy" width="150" height="40">
                    <span class="partner-name">{{ $partner->name }}</span>
                  </a>
                @else
                  <div class="partner-card">
                    <img class="partner-logo" src="{{ $partner->logo_url }}" alt="" loading="lazy" width="150" height="40">
                    <span class="partner-name">{{ $partner->name }}</span>
                  </div>
                @endif
              </li>
            @endforeach
          </ul>
        @endforeach
      </div>
    </div>
  </section>
@endif
