@extends('layouts.app')

@section('canonical', route('news.index'))

@section('content')
@include('partials.page-header')

<section class="news-listing">
  <div class="wrap">
    <div class="news-listing-grid">
      @foreach ($news as $article)
        <a class="news-card reveal-card" href="{{ route('news.show', $article->slug) }}">
          <div class="news-media"><img src="{{ $article->cover_image_url ?? asset('assets/images/hero-2-commercial.jpg') }}" alt="{{ $article->title }}" loading="lazy"></div>
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
