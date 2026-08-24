{{-- Expects optional $breadcrumb string (defaults to the yielded title) --}}
<header class="admin-topbar">
  <button class="sb-toggle" id="sbToggle" type="button" aria-label="Toggle menu" aria-expanded="false" aria-controls="adminSidebar">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
  </button>
  <div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Admin</a><span class="sep">/</span><span class="current">{{ $breadcrumb ?? $__env->yieldContent('title') }}</span>
  </div>
  <div class="topbar-actions">
    <a class="topbar-icon-btn" href="{{ route('home') }}" target="_blank" rel="noopener" aria-label="View live site">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><path d="M15 3h6v6"/><path d="M10 14 21 3"/></svg>
    </a>
  </div>
</header>
