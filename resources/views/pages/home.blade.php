@extends('layouts.app')

@section('title', "RHL Properties Ltd | Building Tomorrow's Landmarks")
@section('description', 'RHL Properties Ltd — a diversified real estate & investment group across residential, commercial and hospitality developments.')
@section('og_image', asset('assets/images/hero-1-residential.jpg'))
@section('canonical', route('home'))

@push('head')
<link rel="preload" as="image" href="{{ asset('assets/images/hero-1-residential.jpg') }}" fetchpriority="high">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
@endpush

@section('content')
<section class="hero" id="hero">
  <div class="hero-pin">
    <div class="hero-layer" id="heroLayer">
      <div class="swiper hero-swiper" id="heroSwiper">
        <div class="swiper-wrapper" id="heroSlides"></div>
      </div>
      <div class="hero-scrim"></div>
    </div>
    <div class="hero-content" id="heroContent">
      <div class="hero-eyebrow"><span>{{ $page->content['hero_eyebrow'] }}</span></div>
      <div class="hero-label" id="heroLabel">{{ $page->content['hero_label'] }}</div>
      <h1 data-reveal="load" data-split="chars">{{ $page->content['hero_headline'] }}</h1>
      <div class="hero-foot">
        <p class="hero-sub">{{ $page->content['hero_sub'] }}</p>
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

<section class="intro" id="story">
  <div class="wrap intro-grid">
    <div>
      <span class="intro-tag reveal-up">Our Story</span>
      <h2 class="reveal-up">{!! str_replace(['trust', 'last'], ['<em>trust</em>', '<em>last</em>'], e($page->content['intro_headline'])) !!}</h2>
      <div class="intro-foot">
        <div class="reveal-up"><strong>Since 1998</strong>{{ $page->content['intro_since_text'] }}</div>
        <div class="reveal-up"><strong>Full Spectrum</strong>{{ $page->content['intro_spectrum_text'] }}</div>
      </div>
      <a href="{{ route('about') }}" class="link-arrow reveal-up">Read our full story &rarr;</a>
    </div>
    <div class="intro-media reveal-up">
      <img data-parallax="0.15" decoding="async" loading="lazy" src="https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=1200&q=80" alt="RHL Properties Ltd signature development">
      <div class="badge"><div class="n">25+</div><div class="l">Years of Excellence</div></div>
    </div>
  </div>
</section>

<section class="why" id="whyChooseUs">
  <div class="wrap">
    <div class="why-head">
      <span class="intro-tag reveal-up">Why Choose Us</span>
      <h2 class="reveal-up">Built on trust, backed by approvals, delivered on time.</h2>
    </div>
    <div class="why-grid">
      @foreach ($page->content['why_cards'] as $i => $card)
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
      <span class="intro-tag">Our Mission</span>
      <h3>Landmark developments, delivered with integrity.</h3>
      <p>To design and build residential, commercial and hospitality spaces that stand for decades — earning trust through quality, transparency and timely handover.</p>
      <a href="{{ route('mission-vision') }}" class="link-arrow">Read our mission &amp; vision &rarr;</a>
    </div>
    <div class="mv-card reveal-card">
      <span class="intro-tag">Our Vision</span>
      <h3>Shaping Dhaka's skyline for the next generation.</h3>
      <p>To be Bangladesh's most trusted real estate group — recognised for design integrity, RAJUK compliance and long-term value for every stakeholder.</p>
      <a href="{{ route('mission-vision') }}" class="link-arrow">Read our mission &amp; vision &rarr;</a>
    </div>
  </div>
</section>

<section class="stats">
  <div class="stats-bg" id="statsBg" data-parallax-bg="0.25"></div>
  <div class="stats-inner wrap">
    <span class="intro-tag reveal-up" style="color:var(--gold-light)">Key Statistics</span>
    <h2 class="reveal-up" style="max-width:640px;margin-bottom:50px;">Our impact, in numbers.</h2>
    <div class="stats-grid">
      @foreach ($page->content['stats'] as $stat)
        @php
          preg_match('/^([\d.]+)(.*)$/', $stat['value'], $m);
          $decimals = str_contains($m[1] ?? '', '.') ? strlen(explode('.', $m[1])[1]) : 0;
        @endphp
        <div class="stat reveal-card"><div class="num" data-target="{{ $m[1] ?? $stat['value'] }}" data-decimals="{{ $decimals }}" data-suffix="{{ $m[2] ?? '' }}">0</div><div class="label">{{ $stat['label'] }}</div></div>
      @endforeach
    </div>
  </div>
</section>

<div class="marquee" aria-hidden="true">
  <div class="marquee-track" id="marqueeTrack"></div>
</div>

<section class="featured" id="featured">
  <div class="wrap">
    <div class="featured-head">
      <div>
        <span class="intro-tag reveal-up">Featured Developments</span>
        <h2 class="reveal-up">Landmarks in the making</h2>
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
            <img src="{{ $project->hero_image }}" alt="{{ $project->name }}" loading="lazy" decoding="async">
            <div class="feature-copy">
              <span class="cat">{{ $project->type }}</span>
              <h3>{{ $project->name }}</h3>
              <span class="loc">{{ $project->location }}</span>
              <a href="{{ route('projects.show', $project->slug) }}" class="feature-btn">View Project &rarr;</a>
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
      <span class="intro-tag reveal-up">Our Portfolio</span>
      <h2 class="reveal-up">Ongoing and completed, at a glance.</h2>
    </div>
    <div class="split-grid">
      <div class="split-col reveal-card">
        <div class="split-col-head">
          <h3>Ongoing</h3>
          <a href="{{ route('projects.index', ['status' => 'ongoing']) }}#portfolio" class="link-arrow">View all &rarr;</a>
        </div>
        <div class="split-list">
          @foreach ($ongoingProjects as $project)
            <a class="split-item" href="{{ route('projects.show', $project->slug) }}">
              <div class="split-thumb"><img src="{{ $project->hero_image }}" alt="{{ $project->name }}" loading="lazy"></div>
              <div class="split-copy"><h4>{{ $project->name }}</h4><span>{{ $project->type }} &mdash; {{ $project->location }}</span></div>
            </a>
          @endforeach
        </div>
      </div>
      <div class="split-col reveal-card">
        <div class="split-col-head">
          <h3>Completed</h3>
          <a href="{{ route('projects.index', ['status' => 'completed']) }}#portfolio" class="link-arrow">View all &rarr;</a>
        </div>
        <div class="split-list">
          @foreach ($completedProjects as $project)
            <a class="split-item" href="{{ route('projects.show', $project->slug) }}">
              <div class="split-thumb"><img src="{{ $project->hero_image }}" alt="{{ $project->name }}" loading="lazy"></div>
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
      <span class="intro-tag reveal-up">What We Do</span>
      <h2 class="reveal-up" style="font-size:clamp(28px,3.8vw,46px);font-weight:400;">Diversified across the built environment.</h2>
    </div>
    <a href="{{ route('services') }}" class="link-arrow">All services &rarr;</a>
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
    <span class="intro-tag reveal-up">Our Journey</span>
    <h2 class="reveal-up">A story we're building, one milestone at a time.</h2>
  </div>

  <div class="journey-chapter" data-type="video" data-src="{{ asset('assets/videos/skyline-commerce-tower.mp4') }}" data-poster="https://images.unsplash.com/photo-1470723710355-95304d8aece4?auto=format&fit=crop&w=1800&q=80">
    <div class="journey-media"><img src="https://images.unsplash.com/photo-1470723710355-95304d8aece4?auto=format&fit=crop&w=1800&q=80" alt="Skyline Commerce Tower" loading="lazy" decoding="async"></div>
    <div class="journey-scrim"></div>
    <span class="journey-num">01</span>
    <div class="journey-content">
      <div class="journey-content-inner">
        <span class="journey-kicker"><span class="dot"></span>Ongoing — Tejgaon</span>
        <h3>Rising above the skyline.</h3>
        <p>Twenty-two floors of Grade-A office space are taking shape in the heart of Tejgaon. Structural work is complete; interior fit-out begins this quarter.</p>
        <a href="{{ route('projects.index') }}" class="link-arrow">Follow the build &rarr;</a>
      </div>
    </div>
  </div>

  <div class="journey-chapter" data-type="image">
    <div class="journey-media"><img src="https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=1800&q=80" alt="The RHL Residences" loading="lazy" decoding="async"></div>
    <div class="journey-scrim"></div>
    <span class="journey-num">02</span>
    <div class="journey-content">
      <div class="journey-content-inner">
        <span class="journey-kicker"><span class="dot"></span>Completed — Gulshan</span>
        <h3>Where lakeside living began.</h3>
        <p>Delivered in 2024, our flagship residences set the benchmark for design-led living on Gulshan Lake — every unit found a home within its first year.</p>
        <a href="{{ route('projects.index') }}" class="link-arrow">See the finished residences &rarr;</a>
      </div>
    </div>
  </div>

  <div class="journey-chapter" data-type="video" data-src="{{ asset('assets/videos/grand-exchange.mp4') }}" data-poster="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1800&q=80">
    <div class="journey-media"><img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1800&q=80" alt="The Grand Exchange" loading="lazy" decoding="async"></div>
    <div class="journey-scrim"></div>
    <span class="journey-num">03</span>
    <div class="journey-content">
      <div class="journey-content-inner">
        <span class="journey-kicker"><span class="dot"></span>Ongoing — Dhanmondi</span>
        <h3>A new exchange, taking shape.</h3>
        <p>Foundations are laid for a mixed-use landmark blending retail, office and public space along Dhanmondi Lake. Topping out is expected within the year.</p>
        <a href="{{ route('projects.index') }}" class="link-arrow">Follow the build &rarr;</a>
      </div>
    </div>
  </div>

  <div class="journey-chapter" data-type="image">
    <div class="journey-media"><img src="https://images.unsplash.com/photo-1502005229762-cf1b2da7c5d6?auto=format&fit=crop&w=1800&q=80" alt="Aurora Waterfront Villas" loading="lazy" decoding="async"></div>
    <div class="journey-scrim"></div>
    <span class="journey-num">04</span>
    <div class="journey-content">
      <div class="journey-content-inner">
        <span class="journey-kicker"><span class="dot"></span>Upcoming — Banani</span>
        <h3>The next chapter, breaking ground soon.</h3>
        <p>Twelve private villas designed around Banani Lake's natural shoreline. Groundbreaking is scheduled for early 2027.</p>
        <a href="{{ route('projects.index') }}" class="link-arrow">Register your interest &rarr;</a>
      </div>
    </div>
  </div>
</section>

@if ($md)
<section class="md-teaser" id="mdMessage">
  <div class="md-grid">
    <div class="md-portrait reveal-card">
      <img src="{{ $md->photo }}" alt="Managing Director, RHL Properties Ltd" loading="lazy" decoding="async">
    </div>
    <div class="reveal-up">
      <span class="intro-tag">A Message From Our Managing Director</span>
      <p class="md-quote">Every RHL address is a promise we intend to keep — approved land, honest schedules and a handover date we don't move.</p>
      <div class="md-name">{{ $md->name }}</div>
      <div class="md-role">{{ $md->role }}, RHL Properties Ltd</div>
      <a href="{{ route('md-message') }}" class="link-arrow">Read the full message &rarr;</a>
    </div>
  </div>
</section>
@endif

<section class="mgmt-strip" id="management">
  <div class="mgmt-head">
    <div>
      <span class="intro-tag reveal-up">Leadership</span>
      <h2 class="reveal-up" style="font-size:clamp(28px,3.8vw,46px);font-weight:400;">The team behind every handover.</h2>
    </div>
    <a href="{{ route('directors') }}" class="link-arrow">Meet the board &rarr;</a>
  </div>
  <div class="mgmt-row">
    @foreach ($leadership as $person)
      <a class="mgmt-person reveal-card" href="{{ $person instanceof \App\Models\Director ? route('directors') : route('management') }}">
        <div class="mgmt-photo"><img src="{{ $person->photo }}" alt="{{ $person->name }}" loading="lazy"></div>
        <h4>{{ $person->name }}</h4>
        <span>{{ $person->role }}</span>
      </a>
    @endforeach
  </div>
</section>

<section class="explore" id="explore">
  <div class="swiper explore-swiper">
    <div class="swiper-wrapper" id="exploreSlides"></div>
  </div>
  <div class="explore-scrim"></div>
  <div class="explore-content wrap">
    <span class="intro-tag reveal-up" style="color:var(--gold-light)">Explore</span>
    <h2 class="reveal-up">Step inside our developments.</h2>
    <div class="explore-info">
      <span class="cat" id="exploreCat">Residential</span>
      <h3 id="exploreTitle">The RHL Residences</h3>
      <span class="loc" id="exploreLoc">Gulshan</span>
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
        <span class="intro-tag reveal-up">Client Voices</span>
        <h2 class="reveal-up">Trusted by those who build with us</h2>
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
            <div class="testi-who"><div class="testi-avatar"><img src="{{ $testimonial->avatar }}" alt="{{ $testimonial->name }}" loading="lazy" decoding="async"></div><div><div class="testi-name">{{ $testimonial->name }}</div><div class="testi-role">{{ $testimonial->role }}</div></div></div>
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
      <span class="intro-tag reveal-up">Latest Updates</span>
      <h2 class="reveal-up" style="font-size:clamp(28px,3.8vw,46px);font-weight:400;">News from RHL Properties.</h2>
    </div>
    <a href="{{ route('news.index') }}" class="link-arrow">All news &rarr;</a>
  </div>
  <div class="news-grid">
    @foreach ($latestNews as $article)
      <a class="news-card reveal-card" href="{{ route('news.show', $article->slug) }}">
        <div class="news-media"><img src="{{ $article->cover_image ?? 'https://images.unsplash.com/photo-1470723710355-95304d8aece4?auto=format&fit=crop&w=800&q=80' }}" alt="{{ $article->title }}" loading="lazy" decoding="async"></div>
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
      <iframe src="https://www.google.com/maps?q={{ urlencode($setting->map_query) }}&output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="RHL Properties Ltd head office location"></iframe>
    </div>
    <div class="map-info reveal-up">
      <span class="intro-tag">Visit Our Head Office</span>
      <h3>We're based in the heart of Gulshan.</h3>
      <p>{{ $setting->address }}</p>
      <p><a href="tel:{{ $setting->phone }}">{{ $setting->phone }}</a></p>
      <p><a href="mailto:{{ $setting->email }}">{{ $setting->email }}</a></p>
      <a href="{{ route('contact') }}" class="link-arrow">Get directions &amp; contact us &rarr;</a>
    </div>
  </div>
</section>
@endif

<section class="connect">
  <span class="intro-tag reveal-up">Explore RHL Properties</span>
  <h2 class="reveal-up">Two decades of landmark developments — see where we've been, and where you fit in.</h2>
  <div class="connect-grid">
    <div class="connect-card reveal-card">
      <h3>Get in Touch</h3>
      <p>Speak with our team about current availability, partnership opportunities or a project you have in mind.</p>
      <a href="{{ route('contact') }}" class="btn">Contact us &rarr;</a>
    </div>
    <div class="connect-card reveal-card">
      <h3>Featured Developments</h3>
      <p>Browse our residential, commercial and mixed-use projects across the region.</p>
      <a href="{{ route('projects.index') }}" class="btn">View projects &rarr;</a>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/stats.js') }}"></script>
<script src="{{ asset('assets/js/testimonials.js') }}"></script>
<script src="{{ asset('assets/js/home.js') }}"></script>
@endpush
