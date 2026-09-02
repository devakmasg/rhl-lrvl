@extends('layouts.app')

{{-- Title and description come from the "home" page_banners row (admin →
     Page Headers), the same place every other page's SEO is edited. --}}
@php
  // Both of these have to follow whichever slide the admin put first — a fixed
  // filename would preload an image the hero may no longer show, and share a
  // picture that isn't on the page.
  $firstSlide = $heroSlides->first()?->image_url ?? asset('assets/images/hero-1-residential.jpg');
@endphp
@section('og_image', $firstSlide)
@section('canonical', route('home'))

@push('head')
<link rel="preload" as="image" href="{{ $firstSlide }}" fetchpriority="high">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
@endpush

@section('content')
<section class="hero" id="hero">
  <div class="hero-pin">
    <div class="hero-layer" id="heroLayer">
      <div class="swiper hero-swiper" id="heroSwiper">
        <div class="swiper-wrapper" id="heroSlides" data-slides="{{ $heroSlides->map(fn ($s) => ['img' => $s->image_url, 'label' => $s->label ?: ''])->toJson() }}"></div>
      </div>
      <div class="hero-scrim"></div>
    </div>
    <div class="hero-content" id="heroContent">
      <div class="hero-eyebrow"><span>{{ $page->get('hero_eyebrow') }}</span></div>
      <div class="hero-label" id="heroLabel">{{ $page->get('hero_label') }}</div>
      <h1 data-reveal="load" data-split="chars">{{ $page->get('hero_headline') }}</h1>
      <div class="hero-foot">
        <p class="hero-sub">{{ $page->get('hero_sub') }}</p>
        <div class="hero-nav">
          <div class="hero-dots" id="heroDots"></div>
          <div class="hero-arrows" role="group" aria-label="Hero slides">
            <button id="heroPrev" aria-label="Previous slide">&larr;</button>
            <button id="heroNext" aria-label="Next slide">&rarr;</button>
          </div>
        </div>
      </div>
    </div>
    <div class="hero-scroll-cue"><span>Scroll</span><span class="line"></span></div>
  </div>
</section>

{{-- Reads the about row: this block is the same content as the About page's
     intro, edited once there. Only the arrow link below it is homepage-only. --}}
@include('partials.intro-section', [
  'page' => $aboutPage,
  'id' => 'story',
  'link' => ['url' => route('about'), 'label' => $page->link('story')],
])

<section class="why" id="whyChooseUs">
  <div class="wrap">
    <div class="why-head">
      <span class="intro-tag reveal-up">{{ $page->section('why', 'eyebrow') }}</span>
      <h2 class="reveal-up">{{ $page->section('why') }}</h2>
    </div>
    <div class="why-grid">
      @foreach ($page->list('why_cards') as $i => $card)
        <div class="why-card reveal-card">
          <span class="why-idx">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
          <h3>{{ $card['title'] }}</h3>
          <p>{{ $card['desc'] }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>

<section class="mv-teaser" id="missionVision">
  <div class="mv-grid">
    <div class="mv-card reveal-card">
      <span class="intro-tag">{{ $aboutPage?->get('mission_eyebrow') }}</span>
      <h3>{{ $aboutPage?->get('mission_heading') }}</h3>
      <p>{{ $aboutPage?->get('mission') }}</p>
      <a href="{{ route('mission-vision') }}" class="link-arrow">{{ $page->link('mission_vision') }} &rarr;</a>
    </div>
    <div class="mv-card reveal-card">
      <span class="intro-tag">{{ $aboutPage?->get('vision_eyebrow') }}</span>
      <h3>{{ $aboutPage?->get('vision_heading') }}</h3>
      <p>{{ $aboutPage?->get('vision') }}</p>
      <a href="{{ route('mission-vision') }}" class="link-arrow">{{ $page->link('mission_vision') }} &rarr;</a>
    </div>
  </div>
</section>

{{-- Reads the about row: the same band appears on the About page, edited once there. --}}
@include('partials.stats-section', ['page' => $aboutPage])

<div class="marquee" aria-hidden="true">
  <div class="marquee-track" id="marqueeTrack" data-items="{{ json_encode(array_values($page->get('marquee_items', []) ?: [])) }}"></div>
</div>

<section class="featured" id="featured">
  <div class="wrap">
    <div class="featured-head">
      <div>
        <span class="intro-tag reveal-up">{{ $page->section('featured', 'eyebrow') }}</span>
        <h2 class="reveal-up">{{ $page->section('featured') }}</h2>
      </div>
      <div class="featured-controls">
        <div class="featured-toggle" id="featuredToggle" role="group" aria-label="Slide transition style">
          <button type="button" data-mode="simple" aria-pressed="false">Simple</button>
          <button type="button" data-mode="animated" class="active" aria-pressed="true">Animated</button>
        </div>
        <div class="featured-nav" role="group" aria-label="Featured developments">
          <button id="featPrev" aria-label="Previous project">&larr;</button>
          <button id="featNext" aria-label="Next project">&rarr;</button>
        </div>
      </div>
    </div>
    <div class="swiper featured-swiper">
      <div class="swiper-wrapper" id="featuredTrack">
        @foreach ($featuredProjects as $project)
          <div class="swiper-slide feature-card reveal-card">
            <img src="{{ $project->hero_image_url }}" alt="{{ $project->name }}" loading="lazy" decoding="async">
            <div class="feature-copy">
              <span class="cat">{{ $project->type }}</span>
              <h3>{{ $project->name }}</h3>
              <span class="loc">{{ $project->location }}</span>
              <a href="{{ route('projects.show', $project->slug) }}" class="feature-btn">{{ $page->link('featured_project') }} &rarr;</a>
            </div>
          </div>
        @endforeach
      </div>
    </div>
    <div class="featured-dots" id="featuredDots"></div>
  </div>
</section>

<section class="split" id="projectSplit">
  <div class="wrap">
    <div class="split-head">
      <span class="intro-tag reveal-up">{{ $page->section('portfolio', 'eyebrow') }}</span>
      <h2 class="reveal-up">{{ $page->section('portfolio') }}</h2>
    </div>
    <div class="split-grid">
      <div class="split-col reveal-card">
        <div class="split-col-head">
          <h3>{{ $page->section('portfolio_ongoing') }}</h3>
          <a href="{{ route('projects.index', ['status' => 'ongoing']) }}#portfolio" class="link-arrow">{{ $page->link('portfolio_ongoing') }} &rarr;</a>
        </div>
        <div class="split-list">
          @foreach ($ongoingProjects as $project)
            <a class="split-item" href="{{ route('projects.show', $project->slug) }}">
              <div class="split-thumb"><img src="{{ $project->hero_image_url }}" alt="{{ $project->name }}" loading="lazy"></div>
              <div class="split-copy"><h4>{{ $project->name }}</h4><span>{{ $project->type }} &mdash; {{ $project->location }}</span></div>
            </a>
          @endforeach
        </div>
      </div>
      <div class="split-col reveal-card">
        <div class="split-col-head">
          <h3>{{ $page->section('portfolio_completed') }}</h3>
          <a href="{{ route('projects.index', ['status' => 'completed']) }}#portfolio" class="link-arrow">{{ $page->link('portfolio_completed') }} &rarr;</a>
        </div>
        <div class="split-list">
          @foreach ($completedProjects as $project)
            <a class="split-item" href="{{ route('projects.show', $project->slug) }}">
              <div class="split-thumb"><img src="{{ $project->hero_image_url }}" alt="{{ $project->name }}" loading="lazy"></div>
              <div class="split-copy"><h4>{{ $project->name }}</h4><span>{{ $project->type }} &mdash; {{ $project->location }}</span></div>
            </a>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

<section class="svc-teaser" id="whatWeDo">
  <div class="svc-head">
    <div>
      <span class="intro-tag reveal-up">{{ $page->section('services', 'eyebrow') }}</span>
      <h2 class="reveal-up" style="font-size:clamp(28px,3.8vw,46px);font-weight:400;">{{ $page->section('services') }}</h2>
    </div>
    <a href="{{ route('services') }}" class="link-arrow">{{ $page->link('services') }} &rarr;</a>
  </div>
  <div class="svc-grid">
    @foreach ($services as $i => $service)
      <div class="svc-card reveal-card">
        <span class="idx">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
        <h3>{{ $service->title }}</h3>
        <p>{{ $service->description }}</p>
      </div>
    @endforeach
  </div>
</section>

<section class="journey" id="ourProjects">
  <div class="journey-head">
    <span class="intro-tag reveal-up">{{ $page->section('journey', 'eyebrow') }}</span>
    <h2 class="reveal-up">{{ $page->section('journey') }}</h2>
  </div>

  @foreach ($journeyChapters as $chapter)
    <div class="journey-chapter"
         data-type="{{ $chapter->isVideo() ? 'video' : 'image' }}"
         @if ($chapter->isVideo())
           data-src="{{ $chapter->video_url }}"
           data-poster="{{ $chapter->image_url }}"
         @endif>
      <div class="journey-media"><img src="{{ $chapter->image_url }}" alt="{{ $chapter->heading }}" loading="lazy" decoding="async"></div>
      <div class="journey-scrim"></div>
      <span class="journey-num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
      <div class="journey-content">
        <div class="journey-content-inner">
          @if ($chapter->kicker)
            <span class="journey-kicker"><span class="dot"></span>{{ $chapter->kicker }}</span>
          @endif
          <h3>{{ $chapter->heading }}</h3>
          <p>{{ $chapter->body }}</p>
          @if ($chapter->link_label)
            <a href="{{ $chapter->link_url ?: route('projects.index') }}" class="link-arrow">{{ $chapter->link_label }} &rarr;</a>
          @endif
        </div>
      </div>
    </div>
  @endforeach
</section>

@if ($md)
<section class="md-teaser" id="mdMessage">
  <div class="md-grid">
    <div class="md-portrait reveal-card">
      <img src="{{ $md->photo_url }}" alt="{{ $md->name }}, {{ $md->role }}, {{ \App\Support\Brand::name() }}" loading="lazy" decoding="async">
    </div>
    <div class="reveal-up">
      <span class="intro-tag">{{ $page->section('md_message', 'eyebrow') }}</span>
      <p class="md-quote">{{ $aboutPage?->get('md_quote') }}</p>
      <div class="md-name">{{ $md->name }}</div>
      <div class="md-role">{{ $md->role }}, {{ \App\Support\Brand::name() }}</div>
      <a href="{{ route('md-message') }}" class="link-arrow">{{ $page->link('md_message') }} &rarr;</a>
    </div>
  </div>
</section>
@endif

<section class="mgmt-strip" id="management">
  <div class="mgmt-head">
    <div>
      <span class="intro-tag reveal-up">{{ $page->section('leadership', 'eyebrow') }}</span>
      <h2 class="reveal-up" style="font-size:clamp(28px,3.8vw,46px);font-weight:400;">{{ $page->section('leadership') }}</h2>
    </div>
    <a href="{{ route('directors') }}" class="link-arrow">{{ $page->link('leadership') }} &rarr;</a>
  </div>
  <div class="mgmt-row">
    @foreach ($leadership as $person)
      <a class="mgmt-person reveal-card" href="{{ $person instanceof \App\Models\Director ? route('directors') : route('management') }}">
        <div class="mgmt-photo"><img src="{{ $person->photo_url }}" alt="{{ $person->name }}" loading="lazy"></div>
        <h4>{{ $person->name }}</h4>
        <span>{{ $person->role }}</span>
      </a>
    @endforeach
  </div>
</section>

<section class="explore" id="explore">
  <div class="swiper explore-swiper">
    <div class="swiper-wrapper" id="exploreSlides" data-slides="{{ $exploreSlides->map(fn ($s) => [
      'type' => $s->isVideo() ? 'video' : 'image',
      'src' => $s->isVideo() ? $s->video_url : $s->image_url,
      'poster' => $s->image_url,
      'cat' => $s->displayCategory() ?: '',
      'title' => $s->displayTitle() ?: '',
      'loc' => $s->displayLocation() ?: '',
    ])->toJson() }}"></div>
  </div>
  <div class="explore-scrim"></div>
  <div class="explore-content wrap">
    <span class="intro-tag reveal-up" style="color:var(--gold-light)">{{ $page->section('explore', 'eyebrow') }}</span>
    <h2 class="reveal-up">{{ $page->section('explore') }}</h2>
    {{-- Seeded with slide one so the caption is correct before the script runs. --}}
    <div class="explore-info">
      <span class="cat" id="exploreCat">{{ $exploreSlides->first()?->displayCategory() }}</span>
      <h3 id="exploreTitle">{{ $exploreSlides->first()?->displayTitle() }}</h3>
      <span class="loc" id="exploreLoc">{{ $exploreSlides->first()?->displayLocation() }}</span>
    </div>
    <div class="explore-nav">
      <div class="explore-dots" id="exploreDots"></div>
      <div class="explore-arrows" role="group" aria-label="Development slides">
        <button id="explorePrev" aria-label="Previous project">&larr;</button>
        <button id="exploreNext" aria-label="Next project">&rarr;</button>
      </div>
    </div>
  </div>
</section>

<section class="testimonials" id="testimonials">
  <div class="wrap">
    <div class="testi-head">
      <div>
        <span class="intro-tag reveal-up">{{ $page->section('testimonials', 'eyebrow') }}</span>
        <h2 class="reveal-up">{{ $page->section('testimonials') }}</h2>
      </div>
      <div class="testi-nav" role="group" aria-label="Testimonials">
        <button id="testiPrev" aria-label="Previous">&larr;</button>
        <button id="testiNext" aria-label="Next">&rarr;</button>
      </div>
    </div>
    <div class="swiper testi-swiper">
      <div class="swiper-wrapper" id="testiTrack">
        @foreach ($testimonials as $testimonial)
          <div class="swiper-slide testi-slide">
            <blockquote>&ldquo;{{ $testimonial->quote }}&rdquo;</blockquote>
            <div class="testi-who"><div class="testi-avatar"><img src="{{ $testimonial->avatar_url }}" alt="{{ $testimonial->name }}" loading="lazy" decoding="async"></div><div><div class="testi-name">{{ $testimonial->name }}</div><div class="testi-role">{{ $testimonial->role }}</div></div></div>
          </div>
        @endforeach
      </div>
    </div>
    <div class="testi-dots" id="testiDots"></div>
  </div>
</section>

<section class="news" id="latestNews">
  <div class="news-head">
    <div>
      <span class="intro-tag reveal-up">{{ $page->section('news', 'eyebrow') }}</span>
      <h2 class="reveal-up" style="font-size:clamp(28px,3.8vw,46px);font-weight:400;">{{ $page->section('news') }}</h2>
    </div>
    <a href="{{ route('news.index') }}" class="link-arrow">{{ $page->link('news') }} &rarr;</a>
  </div>
  <div class="news-grid">
    @foreach ($latestNews as $article)
      <a class="news-card reveal-card" href="{{ route('news.show', $article->slug) }}">
        <div class="news-media"><img src="{{ $article->cover_image_url ?? asset('assets/images/hero-2-commercial.jpg') }}" alt="{{ $article->title }}" loading="lazy" decoding="async"></div>
        <span class="news-date">{{ $article->date->format('d F Y') }}</span>
        <h3>{{ $article->title }}</h3>
      </a>
    @endforeach
  </div>
</section>

@if ($setting)
<section class="map-band" id="locateUs">
  <div class="map-grid">
    <div class="map-embed reveal-card">
      <iframe src="https://www.google.com/maps?q={{ urlencode($setting->map_query) }}&output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="{{ \App\Support\Brand::name() }} head office location"></iframe>
    </div>
    <div class="map-info reveal-up">
      <span class="intro-tag">{{ $page->section('map', 'eyebrow') }}</span>
      <h3>{{ $page->section('map') }}</h3>
      <p>{{ $setting->address }}</p>
      <p><a href="tel:{{ $setting->phone }}">{{ $setting->phone }}</a></p>
      <p><a href="mailto:{{ $setting->email }}">{{ $setting->email }}</a></p>
      <a href="{{ route('contact') }}" class="link-arrow">{{ $page->link('map') }} &rarr;</a>
    </div>
  </div>
</section>
@endif

@include('partials.connect')
@endsection

@push('scripts')
<script src="{{ \App\Support\Asset::v('assets/js/stats.js') }}"></script>
<script src="{{ \App\Support\Asset::v('assets/js/testimonials.js') }}"></script>
<script src="{{ \App\Support\Asset::v('assets/js/home.js') }}"></script>
@endpush
