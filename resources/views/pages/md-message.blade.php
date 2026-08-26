@extends('layouts.app')

@section('canonical', route('md-message'))

@section('content')
{{-- Name, role and portrait come from the Director record (admin → Directors
     & Team); only the writing below lives on the "about" page row. --}}
@php
  $mdRole = trim(($md?->role ?: 'Managing Director').', '.\App\Support\Brand::name());
@endphp
@include('partials.page-header', [
  'intro' => $md?->name
    ? $md->name." on approvals, honest schedules, and why the handover date doesn't move."
    : null,
])

<section class="md-teaser" style="padding-top:110px;">
  <div class="md-grid">
    <div class="md-portrait reveal-card">
      <img src="{{ $md?->photo_url }}" alt="{{ $md?->name }}, {{ $mdRole }}" loading="lazy" decoding="async">
    </div>
    <div class="reveal-up">
      <div class="md-name">{{ $md?->name }}</div>
      <div class="md-role">{{ $mdRole }}</div>
    </div>
  </div>
</section>

<section class="prose">
  <div class="wrap">
    <div class="prose-inner">
      @foreach ($page->list('md_message') as $paragraph)
        <p>{{ $paragraph }}</p>
      @endforeach
    </div>
    <div class="reveal-up" style="margin-top:10px;">
      <p style="font-family:var(--serif);font-size:20px;color:var(--charcoal);margin-bottom:4px;">{{ $md?->name }}</p>
      <p style="font-size:13px;color:var(--stone);">{{ $mdRole }}</p>
    </div>
  </div>
</section>

@include('partials.connect')
@endsection
