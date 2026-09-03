{{-- Byte-identical shell across every admin page (see hsc TASKS.md
     rhl-html-partials-discipline). Active state driven by route name. --}}
<aside class="admin-sidebar" id="adminSidebar">
  @php
    // Icon/name follow Settings → Company Logo/Name (same source as the public
    // header, see resources/views/partials/nav.blade.php). "ADMIN PANEL" names
    // the software, not the company, so it stays fixed regardless of that setting.
    $sbLogo = \App\Support\Brand::logoOnDark() ?: \App\Support\Brand::logo();
  @endphp
  <div class="sb-brand">
    @if ($sbLogo)
      <img class="brand-logo" src="{{ $sbLogo }}" alt="{{ \App\Support\Brand::name() }}">
    @else
      <svg class="mark" viewBox="0 0 40 40" fill="none"><circle cx="20" cy="20" r="19" stroke="currentColor" stroke-width="1"/><path d="M11 26L20 12l9 14" stroke="#b08d57" stroke-width="1.4"/><circle cx="20" cy="20" r="2.4" fill="#b08d57"/></svg>
    @endif
    <span class="word">{{ \App\Support\Brand::mark() }}<small>ADMIN PANEL</small></span>
  </div>

  <nav class="sb-nav" aria-label="Admin">
    <div class="sb-group-label">Overview</div>
    <a class="sb-link @if(request()->routeIs('admin.dashboard')) active @endif" href="{{ route('admin.dashboard') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>
      Dashboard
    </a>
    <a class="sb-link @if(request()->routeIs('admin.inquiries.*')) active @endif" href="{{ route('admin.inquiries.index') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
      Inquiries
    </a>

    <div class="sb-group-label">Pages</div>
    <a class="sb-link @if(request()->routeIs('admin.content.home')) active @endif" href="{{ route('admin.content.home') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Z"/></svg>
      Homepage
    </a>
    <a class="sb-link @if(request()->routeIs('admin.content.about')) active @endif" href="{{ route('admin.content.about') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
      About &amp; Mission
    </a>
    <a class="sb-link @if(request()->routeIs('admin.content.landowners')) active @endif" href="{{ route('admin.content.landowners') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 20h18M5 20V9l7-5 7 5v11"/><path d="M10 20v-6h4v6"/></svg>
      Landowners Page
    </a>
    <a class="sb-link @if(request()->routeIs('admin.content.partners')) active @endif" href="{{ route('admin.content.partners') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      Partners Page
    </a>
    <a class="sb-link @if(request()->routeIs('admin.page-banners.*')) active @endif" href="{{ route('admin.page-banners.index') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/></svg>
      Page Headers
    </a>
    <a class="sb-link @if(request()->routeIs('admin.page-sections.*')) active @endif" href="{{ route('admin.page-sections.index') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 9v12"/></svg>
      Page Sections
    </a>
    <a class="sb-link @if(request()->routeIs('admin.cta-blocks.*')) active @endif" href="{{ route('admin.cta-blocks.index') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="7" rx="1.5"/><rect x="3" y="15" width="8" height="5" rx="1.5"/><rect x="13" y="15" width="8" height="5" rx="1.5"/></svg>
      Page CTAs
    </a>

    <div class="sb-group-label">Collections</div>
    <a class="sb-link @if(request()->routeIs('admin.projects.*')) active @endif" href="{{ route('admin.projects.index') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/><path d="M9 9h.01M9 13h.01M9 17h.01M15 9h.01M15 13h.01M15 17h.01"/></svg>
      Projects
    </a>
    <a class="sb-link @if(request()->routeIs('admin.news.*')) active @endif" href="{{ route('admin.news.index') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h13a2 2 0 0 1 2 2v13a1 1 0 0 1-1.7.7L15 17H6a2 2 0 0 1-2-2V4Z"/><path d="M8 9h8M8 13h5"/></svg>
      News
    </a>
    <a class="sb-link @if(request()->routeIs('admin.services.*')) active @endif" href="{{ route('admin.services.index') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94Z"/></svg>
      Services
    </a>
    <a class="sb-link @if(request()->routeIs('admin.achievements.*')) active @endif" href="{{ route('admin.achievements.index') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>
      Achievements
    </a>
    <a class="sb-link @if(request()->routeIs('admin.testimonials.*')) active @endif" href="{{ route('admin.testimonials.index') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      Testimonials
    </a>
    <a class="sb-link @if(request()->routeIs(['admin.directors.*', 'admin.team.*'])) active @endif" href="{{ route('admin.directors.index') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      Leaders &amp; Team
    </a>

    <div class="sb-group-label">Site-wide</div>
    <a class="sb-link @if(request()->routeIs('admin.partners.*')) active @endif" href="{{ route('admin.partners.index') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M3.5 9h17M3.5 15h17"/><path d="M12 3c2.5 3 2.5 15 0 18-2.5-3-2.5-15 0-18Z"/></svg>
      Trusted Partners
    </a>
    <a class="sb-link @if(request()->routeIs('admin.menus.*')) active @endif" href="{{ route('admin.menus.index') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
      Menus
    </a>
    <a class="sb-link @if(request()->routeIs('admin.settings.*')) active @endif" href="{{ route('admin.settings.edit') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
      Site Settings
    </a>
    <a class="sb-link @if(request()->routeIs('admin.media.*')) active @endif" href="{{ route('admin.media.index') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-5-5L5 21"/></svg>
      Media Library
    </a>
  </nav>

  <div class="sb-foot">
    <div class="sb-user">
      <img src="{{ auth()->user()->avatar ? asset('storage/'.auth()->user()->avatar) : 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=100&q=80' }}" alt="">
      <div class="sb-user-info">
        <div class="sb-user-name">{{ auth()->user()->name }}</div>
        <div class="sb-user-role">{{ auth()->user()->role }}</div>
      </div>
    </div>
    <a class="sb-link @if(request()->routeIs('admin.profile.*')) active @endif" href="{{ route('admin.profile.edit') }}" style="margin-top:4px;">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      Profile Settings
    </a>
    <form method="POST" action="{{ route('admin.logout') }}">
      @csrf
      <button class="sb-logout" type="submit">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        Log Out
      </button>
    </form>
  </div>
</aside>
