{{-- Shared page header. $banner is bound by the PageBanner view composer in
     AppServiceProvider, keyed off the current route name.

     Callers may override any part for a page whose header is partly dynamic:
       $heading, $intro  — e.g. thank-you personalising the name
       $headerStyle      — extra inline style on the <section>
       $introHtml        — pre-escaped markup when the lead contains links --}}
@php
  $banner = $banner ?? null;
  $bannerHeading = $heading ?? $banner?->heading;
  $bannerIntro = $intro ?? $banner?->intro;
  $bannerImage = $banner?->image_url;
@endphp
<section class="page-header" @if (!empty($headerStyle)) style="{{ $headerStyle }}" @endif>
  @if ($bannerImage)
    <div class="page-header-media" data-parallax-header="0.22" style="background-image:url('{{ $bannerImage }}')"></div>
  @endif
  <div class="wrap">
    @if ($banner?->eyebrow)
      <span class="intro-tag">{{ $banner->eyebrow }}</span>
    @endif
    @if ($bannerHeading)
      <h1 data-reveal="load">{{ $bannerHeading }}</h1>
    @endif
    @if (!empty($introHtml))
      <p>{!! $introHtml !!}</p>
    @elseif ($bannerIntro)
      <p>{{ $bannerIntro }}</p>
    @endif
  </div>
</section>
