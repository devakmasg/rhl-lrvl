@extends('layouts.app')

@section('title', "Mission & Vision | RHL Properties Ltd")
@section('description', 'RHL Properties Ltd — our mission, vision and the core values behind every development in Dhaka.')
@section('og_image', asset('assets/images/hero-2-commercial.jpg'))
@section('canonical', route('mission-vision'))

@section('content')
<section class="page-header">
  <div class="page-header-media" data-parallax-header="0.22" style="background-image:url('{{ asset('assets/images/hero-2-commercial.jpg') }}')"></div>
  <div class="wrap">
    <span class="intro-tag">Mission &amp; Vision</span>
    <h1 data-reveal="load">What we're building toward.</h1>
    <p>The principles that decide which land we buy, which contractors we sign, and which dates we promise.</p>
  </div>
</section>

<section class="mv-teaser" style="padding-top:110px;">
  <div class="mv-grid">
    <div class="mv-card reveal-card">
      <span class="intro-tag">Our Mission</span>
      <h3>Landmark developments, delivered with integrity.</h3>
      <p>{{ $page->content['mission'] }}</p>
    </div>
    <div class="mv-card reveal-card">
      <span class="intro-tag">Our Vision</span>
      <h3>Shaping Dhaka's skyline for the next generation.</h3>
      <p>{{ $page->content['vision'] }}</p>
    </div>
  </div>
</section>

<section class="audience" style="padding-top:0;">
  <div class="wrap">
    <div class="audience-head">
      <span class="intro-tag reveal-up">Core Values</span>
      <h2 class="reveal-up">What every handover is measured against.</h2>
    </div>
    <div class="pillars">
      @foreach ($page->content['core_values'] as $i => $value)
        <div class="pillar reveal-card">
          <span class="pillar-idx">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
          <h3>{{ $value['title'] }}</h3>
          <p>{{ $value['desc'] }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>

<section class="connect">
  <span class="intro-tag reveal-up">Continue Exploring</span>
  <h2 class="reveal-up">See who's behind these commitments.</h2>
  <div class="connect-grid">
    <div class="connect-card reveal-card">
      <h3>Managing Director's Message</h3>
      <p>Read {{ $page->content['md_name'] }}'s message on how RHL Properties approaches every project.</p>
      <a href="{{ route('md-message') }}" class="btn">Read the message &rarr;</a>
    </div>
    <div class="connect-card reveal-card">
      <h3>Board &amp; Management</h3>
      <p>Meet the board of directors and the management team delivering these values daily.</p>
      <a href="{{ route('directors') }}" class="btn">Meet the board &rarr;</a>
    </div>
  </div>
</section>
@endsection
