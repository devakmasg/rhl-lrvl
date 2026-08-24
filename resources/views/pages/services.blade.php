@extends('layouts.app')

@section('title', 'Services | RHL Properties Ltd')
@section('description', "From land acquisition and design to construction, leasing and long-term asset management — RHL Properties covers the full spectrum of real estate services.")
@section('og_image', asset('assets/images/hero-3-hospitality.jpg'))
@section('canonical', route('services'))

@section('content')
<section class="page-header">
  <div class="page-header-media" data-parallax-header="0.22" style="background-image:url('{{ asset('assets/images/hero-3-hospitality.jpg') }}')"></div>
  <div class="wrap">
    <span class="intro-tag">What We Do</span>
    <h1 data-reveal="load">Diversified across every stage of the built environment.</h1>
    <p>From land acquisition and design to construction, leasing and long-term asset management — RHL Properties covers the full spectrum of real estate services.</p>
  </div>
</section>

<section class="services" id="services">
  <div class="wrap">
    <div class="service-list">
      @foreach ($services as $i => $service)
        <div class="service-row reveal-card">
          <span class="idx">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span><h3>{{ $service->title }}</h3><span class="arrow">+</span>
          <p>{{ $service->description }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endsection
