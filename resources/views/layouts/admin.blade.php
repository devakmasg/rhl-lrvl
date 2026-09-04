<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title') | RHL Admin</title>
<meta name="robots" content="noindex, nofollow">
@php($favicon = \App\Support\Brand::favicon())
@if ($favicon)
<link rel="icon" href="{{ $favicon }}">
@else
<link rel="icon" href="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%2040%2040'%3E%3Ccircle%20cx='20'%20cy='20'%20r='20'%20fill='%23111110'/%3E%3Cpath%20d='M11%2026L20%2012l9%2014'%20stroke='%23b08d57'%20stroke-width='2.4'%20fill='none'%20stroke-linejoin='round'/%3E%3Ccircle%20cx='20'%20cy='20'%20r='2.8'%20fill='%23b08d57'/%3E%3C/svg%3E">
@endif
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ \App\Support\Asset::v('assets/admin/css/admin.css') }}">
@stack('head')
</head>
<body class="admin">

  <a class="skip-link" href="#adminMain">Skip to content</a>

  <div class="admin-shell">
    <div class="sidebar-scrim" id="sidebarScrim"></div>

    @include('admin.partials.sidebar')

    <div class="admin-main">
      @include('admin.partials.topbar')

      <main class="admin-content" id="adminMain">
        @if (session('status'))
          <div class="form-status is-good" style="margin-bottom:20px;">{{ session('status') }}</div>
        @endif

        @yield('content')
      </main>
    </div>
  </div>

  <script src="{{ \App\Support\Asset::v('assets/admin/js/admin.js') }}"></script>
  @stack('scripts')
</body>
</html>
