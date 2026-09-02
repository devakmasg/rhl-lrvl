@extends('layouts.app')

@section('canonical', route('sales-team'))

@section('content')
@include('partials.page-header')

<section class="people">
  <div class="wrap">
    @if ($teamMembers->isEmpty())
      {{-- Nobody assigned to sales yet. The closing CTA below still gives a
           visitor a way to reach the office, so the page is never a dead end. --}}
      <p class="people-empty">Our sales team is being introduced here shortly. In the meantime, call
        <a href="tel:{{ preg_replace('/\s+/', '', $setting?->phone ?? '') }}">{{ $setting?->phone }}</a>
        and we will put you through.</p>
    @else
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
    @endif
  </div>
</section>

@include('partials.connect')
@endsection
