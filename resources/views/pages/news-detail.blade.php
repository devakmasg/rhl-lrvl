@extends('layouts.app')

@section('title', $news->title.' | '.\App\Support\Brand::name())
@section('description', $news->excerpt)
@section('og_image', $news->cover_image_url ?? asset('assets/images/hero-2-commercial.jpg'))
@section('canonical', route('news.show', $news->slug))

@section('content')
<section class="page-header">
  <div class="page-header-media" id="ndHeroMedia" data-parallax-header="0.22" style="background-image:url('{{ $news->cover_image_url ?? asset('assets/images/hero-2-commercial.jpg') }}')"></div>
  <div class="wrap">
    <span class="intro-tag" id="ndCategory">{{ $news->category }}</span>
    <h1 data-reveal="load" id="ndTitle">{{ $news->title }}</h1>
    <p class="nd-meta" id="ndDate">{{ $news->date->format('d F Y') }}</p>
  </div>
</section>

<section class="prose nd-article">
  <div class="wrap">
    <div class="prose-inner" id="ndBody">
      @foreach (explode("\n\n", $news->body ?? '') as $para)
        <p>{{ $para }}</p>
      @endforeach
    </div>
    <div class="share-row">
      <span>Share</span>
      <a class="share-btn" id="ndShareFacebook" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('news.show', $news->slug)) }}" target="_blank" rel="noopener" aria-label="Share on Facebook">
        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M22 12a10 10 0 1 0-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.4h-1.2c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.4 2.9h-2.4v7A10 10 0 0 0 22 12z"/></svg>
      </a>
      <a class="share-btn" id="ndShareLinkedin" href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('news.show', $news->slug)) }}" target="_blank" rel="noopener" aria-label="Share on LinkedIn">
        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M6.94 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM3.2 8.75h3.5V21H3.2V8.75zm6.2 0h3.35v1.68h.05c.47-.88 1.6-1.8 3.3-1.8 3.53 0 4.18 2.33 4.18 5.35V21h-3.5v-6.1c0-1.46-.03-3.34-2.03-3.34-2.04 0-2.35 1.6-2.35 3.24V21H9.4V8.75z"/></svg>
      </a>
      <a class="share-btn" id="ndShareWhatsapp" href="https://wa.me/?text={{ urlencode($news->title.' '.route('news.show', $news->slug)) }}" target="_blank" rel="noopener" aria-label="Share on WhatsApp">
        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.5 14.4c-.3-.1-1.6-.8-1.9-.9-.2-.1-.4-.1-.6.1-.2.3-.7.9-.8 1-.2.2-.3.2-.5.1-.3-.1-1.1-.4-2.1-1.3-.8-.7-1.3-1.6-1.5-1.8-.1-.2 0-.4.1-.5.1-.1.3-.3.4-.5.1-.1.2-.3.3-.4.1-.2 0-.4 0-.5C11 9.5 10.6 8.5 10.4 8c-.1-.4-.3-.3-.5-.3h-.4c-.2 0-.5.1-.7.3-.2.3-.9.9-.9 2.1s1 2.5 1.1 2.6c.1.2 1.9 3 4.7 4.1.6.3 1.1.4 1.5.6.6.2 1.2.1 1.6.1.5-.1 1.6-.6 1.8-1.3.2-.6.2-1.1.2-1.2-.1-.2-.3-.2-.5-.3z"/><path d="M12 2a10 10 0 0 0-8.5 15.2L2 22l4.9-1.5A10 10 0 1 0 12 2zm0 18.2c-1.6 0-3.1-.4-4.4-1.2l-.3-.2-3 .9.9-2.9-.2-.3A8.2 8.2 0 1 1 20.2 12 8.2 8.2 0 0 1 12 20.2z"/></svg>
      </a>
      <a class="share-btn" id="ndShareEmail" href="mailto:?subject={{ rawurlencode($news->title) }}&body={{ rawurlencode(route('news.show', $news->slug)) }}" aria-label="Share by email">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 6-10 7L2 6"/></svg>
      </a>
    </div>
    <p style="margin-top:30px"><a href="{{ route('news.index') }}" class="link-arrow">← Back to all news</a></p>
  </div>
</section>

<section class="nd-related-section">
  <div class="wrap">
    <span class="intro-tag reveal-up">Related</span>
    <h2 class="reveal-up">More from {{ \App\Support\Brand::shortName() }}.</h2>
    <div class="news-grid" id="ndRelated">
      @foreach ($related as $article)
        <a class="news-card reveal-card" href="{{ route('news.show', $article->slug) }}">
          <div class="news-media"><img src="{{ $article->cover_image_url ?? asset('assets/images/hero-2-commercial.jpg') }}" alt="{{ $article->title }}" loading="lazy" decoding="async"></div>
          <span class="news-date">{{ $article->date->format('d F Y') }}</span>
          <h3>{{ $article->title }}</h3>
        </a>
      @endforeach
    </div>
  </div>
</section>
@endsection
