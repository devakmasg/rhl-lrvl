@extends('layouts.app')

@section('title', 'News & Updates | RHL Properties Ltd')
@section('description', 'Construction updates, handovers, awards and announcements from RHL Properties Ltd.')
@section('og_image', asset('assets/images/hero-2-commercial.jpg'))
@section('canonical', route('news.index'))

@section('content')
<section class="page-header">
  <div class="page-header-media" data-parallax-header="0.22" style="background-image:url('{{ asset('assets/images/hero-2-commercial.jpg') }}')"></div>
  <div class="wrap">
    <span class="intro-tag">News &amp; Updates</span>
    <h1 data-reveal="load">Construction updates, handovers and announcements.</h1>
    <p>Everything newsworthy from our residential, commercial and hospitality developments across Dhaka.</p>
  </div>
</section>

<section class="news-listing">
  <div class="wrap">
    <div class="news-listing-grid">
      @foreach ($news as $article)
        <a class="news-card reveal-card" href="{{ route('news.show', $article->slug) }}">
          <div class="news-media"><img src="{{ $article->cover_image ?? asset('assets/images/hero-2-commercial.jpg') }}" alt="{{ $article->title }}" loading="lazy"></div>
          <span class="news-date">{{ $article->date->format('d F Y') }} &middot; {{ $article->category }}</span>
          <h3>{{ $article->title }}</h3>
          <p>{{ $article->excerpt }}</p>
        </a>
      @endforeach
    </div>
  </div>
</section>

@if ($news->hasPages())
  <nav class="pagination" aria-label="News pages">
    @if ($news->onFirstPage())
      <span class="is-disabled" aria-hidden="true">← Previous</span>
    @else
      <a href="{{ $news->previousPageUrl() }}">← Previous</a>
    @endif

    @for ($page = 1; $page <= $news->lastPage(); $page++)
      @if ($page === $news->currentPage())
        <span class="is-current" aria-current="page">{{ $page }}</span>
      @else
        <a href="{{ $news->url($page) }}">{{ $page }}</a>
      @endif
    @endfor

    @if ($news->hasMorePages())
      <a href="{{ $news->nextPageUrl() }}">Next →</a>
    @else
      <span class="is-disabled" aria-hidden="true">Next →</span>
    @endif
  </nav>
@endif
@endsection
