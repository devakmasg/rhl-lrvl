@extends('layouts.app')

@section('canonical', route('mission-vision'))

@section('content')
@include('partials.page-header')

<section class="mv-teaser" style="padding-top:110px;">
  <div class="mv-grid">
    <div class="mv-card reveal-card">
      <span class="intro-tag">{{ $page->get('mission_eyebrow') }}</span>
      <h3>{{ $page->get('mission_heading') }}</h3>
      <p>{{ $page->get('mission') }}</p>
    </div>
    <div class="mv-card reveal-card">
      <span class="intro-tag">{{ $page->get('vision_eyebrow') }}</span>
      <h3>{{ $page->get('vision_heading') }}</h3>
      <p>{{ $page->get('vision') }}</p>
    </div>
  </div>
</section>

<section class="audience" style="padding-top:0;">
  <div class="wrap">
    <div class="audience-head">
      <span class="intro-tag reveal-up">{{ $sections->eyebrow('values') }}</span>
      <h2 class="reveal-up">{{ $sections->heading('values') }}</h2>
    </div>
    <div class="pillars">
      @foreach ($page->list('core_values') as $i => $value)
        <div class="pillar reveal-card">
          <span class="pillar-idx">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
          <h3>{{ $value['title'] }}</h3>
          <p>{{ $value['desc'] }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>

@include('partials.connect')
@endsection
