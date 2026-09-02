{{-- Header markup follows the Phase 1 static source (see hsc TASKS.md F0.4).
     The menu itself is no longer written into it: both the desktop bar and the
     mobile drawer render the same "primary" menu from the database (admin →
     Menus), so a label exists in exactly one place. --}}
@php
  $navLinks = \App\Models\Menu::tree('primary');
  $navCta = $setting?->nav_cta_label ?: 'Enquire';

  // Logo uploaded in admin → Settings. With none set the inline SVG below is
  // drawn instead, so an install that never touches the setting is unchanged.
  // Both variants are rendered and swapped in CSS rather than picked here,
  // because which one is right depends on the header's scroll state.
  $brandLogo = \App\Support\Brand::logo();
  $brandLogoDark = \App\Support\Brand::logoOnDark();
@endphp
<header class="site on-dark" id="siteHeader">
  <a href="{{ route('home') }}" class="brand">
    @if ($brandLogo)
      <img class="brand-logo" src="{{ $brandLogo }}" alt="{{ \App\Support\Brand::name() }}" width="160" height="34">
      <img class="brand-logo brand-logo-dark" src="{{ $brandLogoDark }}" alt="" aria-hidden="true" width="160" height="34">
    @else
      <svg class="mark" viewBox="0 0 40 40" fill="none"><circle cx="20" cy="20" r="19" stroke="currentColor" stroke-width="1"/><path d="M11 26L20 12l9 14" stroke="#b08d57" stroke-width="1.4"/><circle cx="20" cy="20" r="2.4" fill="#b08d57"/></svg>
    @endif
    @if (\App\Support\Brand::showWordmark())
      <span class="word">{{ \App\Support\Brand::mark() }}<small>{{ \App\Support\Brand::markSub() }}</small></span>
    @endif
  </a>
  <div class="nav-right">
    <nav class="nav-links">
      @foreach ($navLinks as $link)
        @continue (! $link->url())
        @if ($link->children->isNotEmpty())
          <div class="nav-item">
            <a href="{{ $link->url() }}" @class(['active' => $link->isActive()])>{{ $link->label }} <span class="nav-item-caret" aria-hidden="true"></span></a>
            <div class="nav-dropdown">
              @foreach ($link->children as $child)
                @if ($child->url())
                  <a href="{{ $child->url() }}">{{ $child->label }}</a>
                @endif
              @endforeach
            </div>
          </div>
        @else
          <a href="{{ $link->url() }}" @class(['active' => $link->isActive()])>{{ $link->label }}</a>
        @endif
      @endforeach
    </nav>
    <a href="{{ route('contact') }}" class="nav-cta">{{ $navCta }}</a>
    <button class="nav-toggle" id="navToggle" type="button" aria-label="Open menu" aria-expanded="false" aria-controls="mobileNav">
      <span></span><span></span>
    </button>
  </div>
</header>

{{-- data-lenis-prevent: main.js calls lenis.stop() while the panel is open so
     the page behind cannot move, and a stopped Lenis swallows wheel and touch
     everywhere. This attribute exempts the panel, so its own overflow-y can
     scroll natively when the links plus an expanded submenu run past the
     bottom of a short phone screen. --}}
<div class="mobile-nav" id="mobileNav" data-lenis-prevent>
  <nav aria-label="Primary">
    @foreach ($navLinks as $link)
      @continue (! $link->url())
      @if ($link->children->isNotEmpty())
        <div class="mnav-group">
          <button class="mnav-toggle" type="button" aria-expanded="false" aria-controls="mnavSub{{ $link->id }}">{{ $link->label }} <span class="mnav-caret" aria-hidden="true"></span></button>
          <div class="mnav-sub" id="mnavSub{{ $link->id }}">
            @foreach ($link->children as $child)
              @if ($child->url())
                <a href="{{ $child->url() }}">{{ $child->label }}</a>
              @endif
            @endforeach
          </div>
        </div>
      @else
        <a href="{{ $link->url() }}">{{ $link->label }}</a>
      @endif
    @endforeach
  </nav>
  <div class="mobile-nav-foot">
    <a href="{{ route('contact') }}" class="btn-solid">{{ $navCta }}</a>
    <p class="contact-line">
      @php($navPhone = $setting->phone ?? '+880 1711-234567')
      <a href="tel:{{ preg_replace('/\s+/', '', $navPhone) }}">{{ $navPhone }}</a><br>
      <a href="mailto:{{ $setting->email ?? 'hello@rhlproperties.com.bd' }}">{{ $setting->email ?? 'hello@rhlproperties.com.bd' }}</a>
    </p>
  </div>
</div>
