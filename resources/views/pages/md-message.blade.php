@extends('layouts.app')

@section('title', "Managing Director's Message | RHL Properties Ltd")
@section('description', "A message from Md. Rezaul Haque, Managing Director of RHL Properties Ltd, on the company's approach to every development.")
@section('og_image', asset('assets/images/hero-1-residential.jpg'))
@section('canonical', route('md-message'))

@section('content')
<section class="page-header">
  <div class="page-header-media" data-parallax-header="0.22" style="background-image:url('{{ asset('assets/images/hero-1-residential.jpg') }}')"></div>
  <div class="wrap">
    <span class="intro-tag">Leadership</span>
    <h1 data-reveal="load">A message from our Managing Director.</h1>
    <p>{{ $page->content['md_name'] }} on approvals, honest schedules, and why the handover date doesn't move.</p>
  </div>
</section>

<section class="md-teaser" style="padding-top:110px;">
  <div class="md-grid">
    <div class="md-portrait reveal-card">
      <img src="{{ $page->content['md_photo'] }}" alt="{{ $page->content['md_name'] }}, Managing Director, RHL Properties Ltd" loading="lazy" decoding="async">
    </div>
    <div class="reveal-up">
      <div class="md-name">{{ $page->content['md_name'] }}</div>
      <div class="md-role">Managing Director, RHL Properties Ltd</div>
    </div>
  </div>
</section>

<section class="prose">
  <div class="wrap">
    <div class="prose-inner">
      @foreach ($page->content['md_message'] as $paragraph)
        <p>{{ $paragraph }}</p>
      @endforeach
    </div>
    <div class="reveal-up" style="margin-top:10px;">
      <p style="font-family:var(--serif);font-size:20px;color:var(--charcoal);margin-bottom:4px;">{{ $page->content['md_name'] }}</p>
      <p style="font-size:13px;color:var(--stone);">Managing Director, RHL Properties Ltd</p>
    </div>
  </div>
</section>

<section class="connect">
  <span class="intro-tag reveal-up">Continue Exploring</span>
  <h2 class="reveal-up">See the team carrying this forward.</h2>
  <div class="connect-grid">
    <div class="connect-card reveal-card">
      <h3>Board of Directors</h3>
      <p>The board overseeing strategy, governance and capital discipline at RHL Properties.</p>
      <a href="{{ route('directors') }}" class="btn">Meet the board &rarr;</a>
    </div>
    <div class="connect-card reveal-card">
      <h3>Management Team</h3>
      <p>The people running construction, sales and delivery on every current project.</p>
      <a href="{{ route('management') }}" class="btn">Meet the team &rarr;</a>
    </div>
  </div>
</section>
@endsection
