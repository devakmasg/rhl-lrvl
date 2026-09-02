@extends('layouts.app')

@section('canonical', route('chairman-message'))

@section('content')
{{-- Name, role and portrait come from the Director marked as Chairman (admin →
     Directors & Team); only the writing below lives on the "about" page row. --}}
@php
  $chairmanRole = trim(($chairman?->role ?: 'Chairman').', '.\App\Support\Brand::name());
@endphp
@include('partials.page-header', [
  'intro' => $chairman?->name
    ? $chairman->name.' on the standards behind every development that carries our name.'
    : null,
])

@if ($chairman)
<section class="md-teaser" style="padding-top:110px;">
  <div class="md-grid">
    <div class="md-portrait reveal-card">
      <img src="{{ $chairman->photo_url }}" alt="{{ $chairman->name }}, {{ $chairmanRole }}" loading="lazy" decoding="async">
    </div>
    <div class="reveal-up">
      <div class="md-name">{{ $chairman->name }}</div>
      <div class="md-role">{{ $chairmanRole }}</div>
    </div>
  </div>
</section>
@endif

<section class="prose">
  <div class="wrap">
    <div class="prose-inner">
      @foreach ($page->list('chairman_message') as $paragraph)
        <p>{{ $paragraph }}</p>
      @endforeach
    </div>
    @if ($chairman)
      <div class="reveal-up" style="margin-top:10px;">
        <p style="font-family:var(--serif);font-size:20px;color:var(--charcoal);margin-bottom:4px;">{{ $chairman->name }}</p>
        <p style="font-size:13px;color:var(--stone);">{{ $chairmanRole }}</p>
      </div>
    @endif
  </div>
</section>

@include('partials.connect')
@endsection
