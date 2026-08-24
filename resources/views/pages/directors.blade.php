@extends('layouts.app')

@section('title', "Board of Directors | RHL Properties Ltd")
@section('description', 'Meet the Board of Directors of RHL Properties Ltd, overseeing strategy, governance and capital discipline.')
@section('og_image', asset('assets/images/hero-5-business.jpg'))
@section('canonical', route('directors'))

@section('content')
<section class="page-header">
  <div class="page-header-media" data-parallax-header="0.22" style="background-image:url('{{ asset('assets/images/hero-5-business.jpg') }}')"></div>
  <div class="wrap">
    <span class="intro-tag">Leadership</span>
    <h1 data-reveal="load">Board of Directors.</h1>
    <p>The board overseeing strategy, governance and capital discipline across every RHL development.</p>
  </div>
</section>

<section class="people">
  <div class="wrap">
    <div class="people-grid">
      @foreach ($directors as $director)
        <div class="person-card reveal-card">
          <div class="mgmt-photo"><img src="{{ $director->photo }}" alt="{{ $director->name }}" loading="lazy"></div>
          <h3>{{ $director->name }}</h3>
          <span class="role">{{ $director->role }}</span>
          <p>{{ $director->bio }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>

<section class="connect">
  <span class="intro-tag reveal-up">Continue Exploring</span>
  <h2 class="reveal-up">Meet the team running delivery day to day.</h2>
  <div class="connect-grid">
    <div class="connect-card reveal-card">
      <h3>Management Team</h3>
      <p>Construction, sales and asset management leads across every current development.</p>
      <a href="{{ route('management') }}" class="btn">Meet the team &rarr;</a>
    </div>
    <div class="connect-card reveal-card">
      <h3>Managing Director's Message</h3>
      <p>Read {{ optional($directors->firstWhere('role', 'Managing Director'))->name ?? "the Managing Director's" }} message on the company's approach to every handover.</p>
      <a href="{{ route('md-message') }}" class="btn">Read the message &rarr;</a>
    </div>
  </div>
</section>
@endsection
