@extends('layouts.app')

@section('title', 'Thank You | RHL Properties Ltd')
@section('description', "Thank you for contacting RHL Properties Ltd — we've received your enquiry and will reply within two working days.")
@section('og_image', asset('assets/images/hero-1-residential.jpg'))
@section('canonical', route('thank-you'))

@push('head')
<meta name="robots" content="noindex">
@endpush

@section('content')
<section class="page-header" style="min-height:70vh;display:flex;align-items:center;">
  <div class="page-header-media" data-parallax-header="0.22" style="background-image:url('{{ asset('assets/images/hero-1-residential.jpg') }}')"></div>
  <div class="wrap">
    <span class="intro-tag">Message Received</span>
    <h1 data-reveal="load">Thank you{{ session('inquiry_name') ? ', ' . session('inquiry_name') : '' }}.</h1>
    <p id="thankYouMessage">
      @if (session('inquiry_project'))
        Your enquiry about {{ session('inquiry_project') }} has been received. Our team usually replies within two working days.
      @else
        Your enquiry has been received. Our team usually replies within two working days.
      @endif
    </p>
  </div>
</section>

<section class="connect">
  <span class="intro-tag reveal-up">While You Wait</span>
  <h2 class="reveal-up">Keep exploring RHL Properties.</h2>
  <div class="connect-grid">
    <div class="connect-card reveal-card">
      <h3>Browse Developments</h3>
      <p>See our full portfolio of residential, commercial and mixed-use projects across Dhaka.</p>
      <a href="{{ route('projects.index') }}" class="btn">View projects &rarr;</a>
    </div>
    <div class="connect-card reveal-card">
      <h3>Talk to Us Sooner</h3>
      <p>Need a faster answer? Call or WhatsApp our team directly during office hours.</p>
      <a href="tel:+8801711234567" class="btn">Call +880 1711-234567 &rarr;</a>
    </div>
  </div>
</section>
@endsection
