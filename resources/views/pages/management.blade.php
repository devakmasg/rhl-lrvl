@extends('layouts.app')

@section('title', 'Management Team | RHL Properties Ltd')
@section('description', 'Meet the Management Team of RHL Properties Ltd running construction, sales, finance and after-handover support.')
@section('og_image', asset('assets/images/hero-3-hospitality.jpg'))
@section('canonical', route('management'))

@section('content')
<section class="page-header">
  <div class="page-header-media" data-parallax-header="0.22" style="background-image:url('{{ asset('assets/images/hero-3-hospitality.jpg') }}')"></div>
  <div class="wrap">
    <span class="intro-tag">Leadership</span>
    <h1 data-reveal="load">Management Team.</h1>
    <p>The people running construction, sales, finance and after-handover support on every current project.</p>
  </div>
</section>

<section class="people">
  <div class="wrap">
    <div class="people-grid">
      @foreach ($teamMembers as $member)
        <div class="person-card reveal-card">
          <div class="mgmt-photo"><img src="{{ $member->photo }}" alt="{{ $member->name }}" loading="lazy"></div>
          <h3>{{ $member->name }}</h3>
          <span class="role">{{ $member->role }}</span>
          <p>{{ $member->bio }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>

<section class="connect">
  <span class="intro-tag reveal-up">Continue Exploring</span>
  <h2 class="reveal-up">See the board they report to.</h2>
  <div class="connect-grid">
    <div class="connect-card reveal-card">
      <h3>Board of Directors</h3>
      <p>The board overseeing strategy, governance and capital discipline at RHL Properties.</p>
      <a href="{{ route('directors') }}" class="btn">Meet the board &rarr;</a>
    </div>
    <div class="connect-card reveal-card">
      <h3>Managing Director's Message</h3>
      <p>Read the Managing Director's message on the company's approach to every project.</p>
      <a href="{{ route('md-message') }}" class="btn">Read the message &rarr;</a>
    </div>
  </div>
</section>
@endsection
