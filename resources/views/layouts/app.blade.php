<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
@php
  // Admin-managed per-page SEO. A page that still sets @section('title')
  // wins, so dynamic pages (a project, a news article) keep their own.
  $banner = $banner ?? null;
  $defaultTitle = $banner?->seo_title ?: \App\Support\Brand::name();
  $defaultDescription = $banner?->seo_description ?: \App\Support\Brand::metaDescription();
  $defaultOgImage = $banner?->og_image_url ?: asset('assets/images/hero-1-residential.jpg');
@endphp
<title>@yield('title', $defaultTitle)</title>
<meta name="description" content="@yield('description', $defaultDescription)">
<meta name="theme-color" content="#111110">
<link rel="icon" href="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%2040%2040'%3E%3Ccircle%20cx='20'%20cy='20'%20r='20'%20fill='%23111110'/%3E%3Cpath%20d='M11%2026L20%2012l9%2014'%20stroke='%23b08d57'%20stroke-width='2.4'%20fill='none'%20stroke-linejoin='round'/%3E%3Ccircle%20cx='20'%20cy='20'%20r='2.8'%20fill='%23b08d57'/%3E%3C/svg%3E">

<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ \App\Support\Brand::name() }}">
<meta property="og:title" content="@yield('title', $defaultTitle)">
<meta property="og:description" content="@yield('description', $defaultDescription)">
<meta property="og:image" content="@yield('og_image', $defaultOgImage)">
<meta name="twitter:card" content="summary_large_image">
<link rel="canonical" href="@yield('canonical', url()->current())">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
<link rel="preconnect" href="https://unpkg.com" crossorigin>
<link rel="dns-prefetch" href="https://images.unsplash.com">
@stack('preload')
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300;9..144,400;9..144,500;9..144,600&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
@stack('head')
<link rel="stylesheet" href="{{ \App\Support\Asset::v('assets/css/style.css') }}">
<script src="https://unpkg.com/gsap@3/dist/gsap.min.js"></script>
<script src="https://unpkg.com/gsap@3/dist/ScrollTrigger.min.js"></script>
<script src="https://unpkg.com/lenis@1/dist/lenis.min.js"></script>
<script src="https://unpkg.com/gsap@3/dist/SplitText.min.js"></script>
<script>if(window.gsap&&window.ScrollTrigger)document.documentElement.classList.add('anim-ready');</script>
</head>
<body>

  <a class="skip-link" href="#main">Skip to content</a>

  @include('partials.nav')

  <main id="main">
    @yield('content')
  </main>

  @include('partials.partners')

  @include('partials.footer')

  <script src="{{ \App\Support\Asset::v('assets/js/main.js') }}"></script>
  <script src="{{ \App\Support\Asset::v('assets/js/scroll-animations.js') }}"></script>
  @stack('scripts')
</body>
</html>
