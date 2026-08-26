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

@include('partials.connect')
@endsection
