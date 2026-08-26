@extends('layouts.app')

@section('canonical', route('directors'))

@section('content')
@include('partials.page-header')

<section class="people">
  <div class="wrap">
    <div class="people-grid">
      @foreach ($directors as $director)
        <div class="person-card reveal-card">
          <div class="mgmt-photo"><img src="{{ $director->photo_url }}" alt="{{ $director->name }}" loading="lazy"></div>
          <h3>{{ $director->name }}</h3>
          <span class="role">{{ $director->role }}</span>
          <p>{{ $director->bio }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>

@include('partials.connect')
@endsection
