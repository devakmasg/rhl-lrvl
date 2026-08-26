{{-- Frozen header markup (see hsc TASKS.md F0.4) — keep byte-identical to the
     Phase 1 static source other than href → route() conversions. --}}
<header class="site on-dark" id="siteHeader">
  <a href="{{ route('home') }}" class="brand">
    <svg class="mark" viewBox="0 0 40 40" fill="none"><circle cx="20" cy="20" r="19" stroke="currentColor" stroke-width="1"/><path d="M11 26L20 12l9 14" stroke="#b08d57" stroke-width="1.4"/><circle cx="20" cy="20" r="2.4" fill="#b08d57"/></svg>
    <span class="word">{{ \App\Support\Brand::mark() }}<small>{{ \App\Support\Brand::markSub() }}</small></span>
  </a>
  <div class="nav-right">
    <nav class="nav-links">
      <div class="nav-item">
        <a href="{{ route('about') }}" @class(['active' => request()->routeIs(['about', 'mission-vision', 'md-message', 'directors', 'management', 'achievements'])])>About <span class="nav-item-caret" aria-hidden="true"></span></a>
        <div class="nav-dropdown">
          <a href="{{ route('about') }}">Company Overview</a>
          <a href="{{ route('mission-vision') }}">Mission &amp; Vision</a>
          <a href="{{ route('md-message') }}">Managing Director's Message</a>
          <a href="{{ route('directors') }}">Board of Directors</a>
          <a href="{{ route('management') }}">Management Team</a>
          <a href="{{ route('achievements') }}">Achievements</a>
        </div>
      </div>
      <a href="{{ route('projects.index') }}" @class(['active' => request()->routeIs('projects.*')])>Projects</a>
      <a href="{{ route('services') }}" @class(['active' => request()->routeIs('services')])>Services</a>
      <a href="{{ route('partners') }}" @class(['active' => request()->routeIs('partners')])>Partners</a>
      <a href="{{ route('testimonials') }}" @class(['active' => request()->routeIs('testimonials')])>Testimonials</a>
      <a href="{{ route('contact') }}" @class(['active' => request()->routeIs('contact')])>Contact</a>
    </nav>
    <a href="{{ route('contact') }}" class="nav-cta">Enquire</a>
    <button class="nav-toggle" id="navToggle" type="button" aria-label="Open menu" aria-expanded="false" aria-controls="mobileNav">
      <span></span><span></span>
    </button>
  </div>
</header>

<div class="mobile-nav" id="mobileNav">
  <nav aria-label="Primary">
    <div class="mnav-group">
      <button class="mnav-toggle" type="button" aria-expanded="false" aria-controls="mnavAboutSub">About <span class="mnav-caret" aria-hidden="true"></span></button>
      <div class="mnav-sub" id="mnavAboutSub">
        <a href="{{ route('about') }}">Company Overview</a>
        <a href="{{ route('mission-vision') }}">Mission &amp; Vision</a>
        <a href="{{ route('md-message') }}">Managing Director's Message</a>
        <a href="{{ route('directors') }}">Board of Directors</a>
        <a href="{{ route('management') }}">Management Team</a>
        <a href="{{ route('achievements') }}">Achievements</a>
      </div>
    </div>
    <a href="{{ route('projects.index') }}">Projects</a>
    <a href="{{ route('services') }}">Services</a>
    <a href="{{ route('partners') }}">Partners</a>
    <a href="{{ route('testimonials') }}">Testimonials</a>
    <a href="{{ route('contact') }}">Contact</a>
  </nav>
  <div class="mobile-nav-foot">
    <a href="{{ route('contact') }}" class="btn-solid">Enquire</a>
    <p class="contact-line">
      @php($navPhone = $setting->phone ?? '+880 1711-234567')
      <a href="tel:{{ preg_replace('/\s+/', '', $navPhone) }}">{{ $navPhone }}</a><br>
      <a href="mailto:{{ $setting->email ?? 'hello@rhlproperties.com.bd' }}">{{ $setting->email ?? 'hello@rhlproperties.com.bd' }}</a>
    </p>
  </div>
</div>
