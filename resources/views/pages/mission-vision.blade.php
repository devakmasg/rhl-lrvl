@extends('layouts.app')

@section('canonical', route('mission-vision'))

@section('content')
@include('partials.page-header')

<section class="mv-teaser" style="padding-top:110px;">
  <div class="mv-grid">
    <div class="mv-card reveal-card">
      <span class="intro-tag">Our Mission</span>
      <h3>{{ $page->get('mission_heading') }}</h3>
      <p>{{ $page->get('mission') }}</p>
    </div>
    <div class="mv-card reveal-card">
      <span class="intro-tag">Our Vision</span>
      <h3>{{ $page->get('vision_heading') }}</h3>
      <p>{{ $page->get('vision') }}</p>
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
