@extends('layouts.app')

@section('canonical', route('achievements'))

@section('content')
@include('partials.page-header')

<section class="audience" style="padding-top:110px;">
  <div class="wrap">
    <div class="audience-head">
      <span class="intro-tag reveal-up">{{ $sections->eyebrow('awards') }}</span>
      <h2 class="reveal-up">{{ $sections->heading('awards') }}</h2>
    </div>
    <div class="pillars">
      @foreach ($awards as $award)
        <div class="pillar reveal-card">
          <span class="pillar-idx">{{ $award->year }}</span>
          <h3>{{ $award->title }}</h3>
          <p>{{ $award->description }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>

<section class="milestones">
  <div class="wrap">
    <div class="milestones-head">
      <span class="intro-tag reveal-up">{{ $sections->eyebrow('certifications') }}</span>
      <h2 class="reveal-up">{{ $sections->heading('certifications') }}</h2>
    </div>
    <div class="process">
      @foreach ($certifications as $certification)
        <div class="step reveal-card">
          <div class="step-num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</div>
          <div><h3>{{ $certification->title }}</h3><p>{{ $certification->description }}</p></div>
        </div>
      @endforeach
    </div>
  </div>
</section>

@include('partials.connect')
@endsection
