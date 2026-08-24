@extends('layouts.app')

@section('title', 'About | RHL Properties Ltd')
@section('description', 'RHL Properties Ltd — a Bangladeshi real estate developer since 1998, building residential, commercial and hospitality projects across Gulshan, Banani, Dhanmondi and Tejgaon.')
@section('og_image', asset('assets/images/hero-1-residential.jpg'))
@section('canonical', route('about'))

@section('content')
<section class="page-header">
  <div class="page-header-media" data-parallax-header="0.22" style="background-image:url('{{ asset('assets/images/hero-1-residential.jpg') }}')"></div>
  <div class="wrap">
    <span class="intro-tag">About RHL Properties</span>
    <h1 data-reveal="load">A legacy built on trust, developments built to last.</h1>
    <p>RHL Properties Ltd shapes skylines and communities across residential, commercial and hospitality real estate — guided by design integrity and long-term value.</p>
  </div>
</section>

<section class="intro">
  <div class="wrap intro-grid">
    <div>
      <span class="intro-tag reveal-up">Who We Are</span>
      <h2 class="reveal-up">A legacy built on <em>trust</em>, developments built to <em>last</em>.</h2>
      <div class="intro-foot">
        <div class="reveal-up"><strong>Since 1998</strong>Over two decades delivering landmark residential and commercial developments across Dhaka's most sought-after neighbourhoods.</div>
        <div class="reveal-up"><strong>Full Spectrum</strong>From land acquisition and RAJUK-approved design to construction, handover and long-term asset management.</div>
      </div>
    </div>
    <div class="intro-media reveal-up">
      <img data-parallax="0.15" decoding="async" loading="lazy" src="https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=1200&q=80" alt="RHL Properties Ltd signature development">
      <div class="badge"><div class="n">25+</div><div class="l">Years of Excellence</div></div>
    </div>
  </div>
</section>

<section class="prose">
  <div class="wrap">
    <div class="prose-inner">
      <span class="intro-tag reveal-up">Company Overview</span>
      <h2 class="reveal-up">{{ $page->content['headline'] }}</h2>
      @foreach ($page->content['overview'] as $paragraph)
        <p class="reveal-up">{{ $paragraph }}</p>
      @endforeach
    </div>
  </div>
</section>

<section class="milestones">
  <div class="wrap">
    <div class="milestones-head">
      <span class="intro-tag reveal-up">Our History</span>
      <h2 class="reveal-up">Milestones along the way.</h2>
    </div>
    <div class="process">
      @foreach ($page->content['milestones'] as $milestone)
        <div class="step reveal-card">
          <div class="step-num">{{ $milestone['year'] }}</div>
          <div><p>{{ $milestone['text'] }}</p></div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<section class="pd-facts-band">
  <div class="wrap">
    <span class="intro-tag reveal-up" style="padding-top:44px;display:block;">At A Glance</span>
    <dl class="pd-facts" style="padding-top:24px;">
      @foreach ($page->content['facts'] as $fact)
        <div class="fact reveal-card"><dt>{{ $fact['k'] }}</dt><dd>{{ $fact['v'] }}</dd></div>
      @endforeach
    </dl>
  </div>
</section>

<section class="quicklinks">
  <div class="wrap">
    <div class="quicklinks-head">
      <span class="intro-tag reveal-up">Explore Further</span>
      <h2 class="reveal-up">Get to know RHL Properties.</h2>
    </div>
    <div class="quicklinks-grid">
      <a class="quicklink-card reveal-card" href="{{ route('mission-vision') }}">
        <h3>Mission &amp; Vision</h3>
        <p>What we're building toward, and the values that guide every development.</p>
        <span class="link-arrow">Read more &rarr;</span>
      </a>
      <a class="quicklink-card reveal-card" href="{{ route('md-message') }}">
        <h3>Managing Director's Message</h3>
        <p>A word from {{ $page->content['md_name'] }} on the company's approach to every handover.</p>
        <span class="link-arrow">Read more &rarr;</span>
      </a>
      <a class="quicklink-card reveal-card" href="{{ route('directors') }}">
        <h3>Board of Directors</h3>
        <p>The board overseeing strategy, governance and capital discipline.</p>
        <span class="link-arrow">Meet the board &rarr;</span>
      </a>
      <a class="quicklink-card reveal-card" href="{{ route('management') }}">
        <h3>Management Team</h3>
        <p>The people running construction, sales, finance and delivery day to day.</p>
        <span class="link-arrow">Meet the team &rarr;</span>
      </a>
      <a class="quicklink-card reveal-card" href="{{ route('achievements') }}">
        <h3>Achievements</h3>
        <p>Milestones, industry recognition and certifications earned over 25+ years.</p>
        <span class="link-arrow">See achievements &rarr;</span>
      </a>
    </div>
  </div>
</section>

<section class="stats">
  <div class="stats-bg" id="statsBg" data-parallax-bg="0.25"></div>
  <div class="stats-inner wrap">
    <span class="intro-tag reveal-up" style="color:var(--gold-light)">By The Numbers</span>
    <div class="stats-grid">
      <div class="stat reveal-card"><div class="num" data-target="6.4" data-decimals="1" data-suffix="M+">0</div><div class="label">Sq. Ft. Developed</div></div>
      <div class="stat reveal-card"><div class="num" data-target="52" data-suffix="+">0</div><div class="label">Landmark Projects</div></div>
      <div class="stat reveal-card"><div class="num" data-target="25" data-suffix="">0</div><div class="label">Years of Excellence</div></div>
      <div class="stat reveal-card"><div class="num" data-target="8200" data-suffix="+">0</div><div class="label">Satisfied Clients</div></div>
      <div class="stat reveal-card"><div class="num" data-target="12" data-suffix="">0</div><div class="label">Cities Present</div></div>
      <div class="stat reveal-card"><div class="num" data-target="30" data-suffix="+">0</div><div class="label">Industry Awards</div></div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/stats.js') }}"></script>
@endpush
