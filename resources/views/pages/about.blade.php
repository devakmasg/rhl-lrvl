@extends('layouts.app')

@section('canonical', route('about'))

@section('content')
@include('partials.page-header')

@include('partials.intro-section')

<section class="prose">
  <div class="wrap">
    <div class="prose-inner">
      <span class="intro-tag reveal-up">{{ $page->get('overview_eyebrow') }}</span>
      <h2 class="reveal-up">{{ $page->get('headline') }}</h2>
      @foreach ($page->list('overview') as $paragraph)
        <p class="reveal-up">{{ $paragraph }}</p>
      @endforeach
    </div>
  </div>
</section>

<section class="milestones">
  <div class="wrap">
    <div class="milestones-head">
      <span class="intro-tag reveal-up">{{ $page->get('history_eyebrow') }}</span>
      <h2 class="reveal-up">{{ $page->get('history_heading') }}</h2>
    </div>
    <div class="process">
      @foreach ($page->list('milestones') as $milestone)
        <div class="step reveal-card">
          <div class="step-num">{{ $milestone['year'] }}</div>
          <div><p>{{ $milestone['text'] }}</p></div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<section class="pd-facts-band">
  <div class="wrap">
    <span class="intro-tag reveal-up" style="padding-top:44px;display:block;">{{ $page->get('facts_eyebrow') }}</span>
    <dl class="pd-facts" style="padding-top:24px;">
      @foreach ($page->list('facts') as $fact)
        <div class="fact reveal-card"><dt>{{ $fact['k'] }}</dt><dd>{{ $fact['v'] }}</dd></div>
      @endforeach
    </dl>
  </div>
</section>

<section class="quicklinks">
  <div class="wrap">
    <div class="quicklinks-head">
      <span class="intro-tag reveal-up">{{ $page->get('quicklinks_eyebrow') }}</span>
      <h2 class="reveal-up">{{ $page->get('quicklinks_heading') }}</h2>
    </div>
    @php
      // Destination route and link-verb are structural (each card points at a
      // specific page) so they stay fixed in the view; only the title/desc
      // copy is admin-editable, in the same order as the seeded content.
      $quicklinkMeta = [
        ['route' => 'mission-vision', 'link' => 'Read more'],
        ['route' => 'chairman-message', 'link' => 'Read more'],
        ['route' => 'md-message', 'link' => 'Read more'],
        ['route' => 'directors', 'link' => 'Meet the board'],
        ['route' => 'management', 'link' => 'Meet the team'],
        ['route' => 'achievements', 'link' => 'See achievements'],
      ];
    @endphp
    <div class="quicklinks-grid">
      @foreach ($page->get('quicklinks', []) as $i => $quicklink)
        @continue(!isset($quicklinkMeta[$i]))
        {{-- A card an admin emptied drops out rather than rendering a blank box. --}}
        @continue(empty($quicklink['title']) && empty($quicklink['desc']))
        <a class="quicklink-card reveal-card" href="{{ route($quicklinkMeta[$i]['route']) }}">
          <h3>{{ $quicklink['title'] }}</h3>
          <p>{{ $quicklink['desc'] }}</p>
          <span class="link-arrow">{{ $quicklinkMeta[$i]['link'] }} &rarr;</span>
        </a>
      @endforeach
    </div>
  </div>
</section>

@include('partials.stats-section')
@endsection

@push('scripts')
<script src="{{ \App\Support\Asset::v('assets/js/stats.js') }}"></script>
@endpush
