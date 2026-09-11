{{-- Byte-identical shell across every admin page (see hsc TASKS.md
     rhl-html-partials-discipline). Active state driven by route name.

     The nav is rendered from App\Support\AdminSections rather than written out
     link by link, so the menu and the permission checkboxes on Roles are always
     the same list, and a section the current role lacks simply is not drawn. --}}
<aside class="admin-sidebar" id="adminSidebar">
  @php
    // Icon/name follow Settings → Company Logo/Name (same source as the public
    // header, see resources/views/partials/nav.blade.php). "ADMIN PANEL" names
    // the software, not the company, so it stays fixed regardless of that setting.
    $sbLogo = \App\Support\Brand::logoOnDark() ?: \App\Support\Brand::logo();
    $sbUser = auth()->user();
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
    @foreach (\App\Support\AdminSections::grouped() as $sbGroup => $sbSections)
      @php
        $sbVisible = array_filter(
          $sbSections,
          fn ($key) => $sbUser->canAccessSection($key),
          ARRAY_FILTER_USE_KEY
        );
      @endphp
      @continue(count($sbVisible) === 0)

      <div class="sb-group-label">{{ $sbGroup }}</div>
      @foreach ($sbVisible as $sbSection)
        <a class="sb-link @if(request()->routeIs($sbSection['patterns'])) active @endif" href="{{ route($sbSection['route']) }}">
          {!! $sbSection['icon'] !!}
          {{ $sbSection['label'] }}
        </a>
      @endforeach
    @endforeach

    @if ($sbUser->isAdmin())
      <div class="sb-group-label">Access</div>
      <a class="sb-link @if(request()->routeIs('admin.users.*')) active @endif" href="{{ route('admin.users.index') }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        Users
      </a>
      <a class="sb-link @if(request()->routeIs('admin.roles.*')) active @endif" href="{{ route('admin.roles.index') }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3 4 6v6c0 4.5 3.4 8.3 8 9 4.6-.7 8-4.5 8-9V6Z"/><path d="m9 12 2 2 4-4"/></svg>
        Roles &amp; Permissions
      </a>
    @endif
  </nav>

  <div class="sb-foot">
    <div class="sb-user">
      <img src="{{ $sbUser->avatar ? asset('storage/'.$sbUser->avatar) : 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=100&q=80' }}" alt="">
      <div class="sb-user-info">
        <div class="sb-user-name">{{ $sbUser->name }}</div>
        <div class="sb-user-role">{{ $sbUser->role_label }}</div>
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
