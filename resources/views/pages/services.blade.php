@extends('layouts.app')

@section('canonical', route('services'))

@section('content')
@include('partials.page-header')

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
