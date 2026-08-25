@extends('layouts.app')

@section('canonical', route('testimonials'))

@push('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
@endpush

@section('content')
@include('partials.page-header')

<section class="testimonials" id="testimonials">
  <div class="wrap">
    <div class="swiper testi-swiper">
      <div class="swiper-wrapper" id="testiTrack">
        @foreach ($testimonials as $testimonial)
          <div class="swiper-slide testi-slide">
            <blockquote>&ldquo;{{ $testimonial->quote }}&rdquo;</blockquote>
            <div class="testi-who"><div class="testi-avatar"><img src="{{ $testimonial->avatar_url }}" alt="{{ $testimonial->name }}" loading="lazy" decoding="async"></div><div><div class="testi-name">{{ $testimonial->name }}</div><div class="testi-role">{{ $testimonial->role }}</div></div></div>
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
