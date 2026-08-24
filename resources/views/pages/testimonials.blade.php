@extends('layouts.app')

@section('title', 'Testimonials | RHL Properties Ltd')
@section('description', 'Read what homeowners, landowner partners and commercial tenants say about building with RHL Properties Ltd.')
@section('og_image', asset('assets/images/hero-4-waterfront.jpg'))
@section('canonical', route('testimonials'))

@push('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
@endpush

@section('content')
<section class="page-header">
  <div class="page-header-media" data-parallax-header="0.22" style="background-image:url('{{ asset('assets/images/hero-4-waterfront.jpg') }}')"></div>
  <div class="wrap">
    <span class="intro-tag">Client Voices</span>
    <h1 data-reveal="load">Trusted by those who build with us</h1>
    <p>Homeowners, landowners and commercial tenants share their experience partnering with RHL Properties Ltd.</p>
  </div>
</section>

<section class="testimonials" id="testimonials">
  <div class="wrap">
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
    <div class="testi-nav" style="margin-top:44px;">
      <button id="testiPrev" aria-label="Previous">&larr;</button>
      <button id="testiNext" aria-label="Next">&rarr;</button>
    </div>
    <div class="testi-dots" id="testiDots"></div>
  </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/testimonials.js') }}"></script>
@endpush
