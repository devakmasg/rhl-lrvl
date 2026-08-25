@extends('layouts.app')

@section('canonical', route('management'))

@section('content')
@include('partials.page-header')

<section class="people">
  <div class="wrap">
    <div class="people-grid">
      @foreach ($teamMembers as $member)
        <div class="person-card reveal-card">
          <div class="mgmt-photo"><img src="{{ $member->photo_url }}" alt="{{ $member->name }}" loading="lazy"></div>
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
