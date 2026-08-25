@extends('layouts.admin')

@section('title', 'Page Headers')

@push('head')
<style>
  .pb-item{border:1px solid var(--line);border-radius:10px;margin-bottom:14px;overflow:hidden;background:var(--surface);}
  .pb-head{display:flex;align-items:center;gap:14px;padding:14px 16px;cursor:pointer;}
  .pb-head:hover{background:var(--surface-2, rgba(0,0,0,.02));}
  .pb-thumb{width:64px;height:40px;border-radius:6px;object-fit:cover;flex:none;border:1px solid var(--line);background:var(--line);}
  .pb-meta{flex:1;min-width:0;}
  .pb-meta .k{font-size:14px;font-weight:600;}
  .pb-meta .v{font-size:12px;color:var(--stone);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
  .pb-body{display:none;padding:0 16px 18px;border-top:1px solid var(--line);}
  .pb-item.is-open .pb-body{display:block;}
  .pb-item.is-open .pb-head{background:var(--surface-2, rgba(0,0,0,.02));}
  .pb-caret{transition:transform .15s;flex:none;width:16px;height:16px;color:var(--stone);}
  .pb-item.is-open .pb-caret{transform:rotate(90deg);}
  .pb-cols{display:grid;grid-template-columns:1fr 1fr;gap:16px;padding-top:16px;}
  @media (max-width:900px){.pb-cols{grid-template-columns:1fr;}}
</style>
@endpush

@section('content')
<div class="page-head">
  <div>
    <h1>Page Headers</h1>
    <p>The banner image, heading and search-engine listing for each page on the public site.</p>
  </div>
</div>

@forelse ($banners as $banner)
  <div class="pb-item" id="pb-{{ $banner->id }}">
    <div class="pb-head" role="button" tabindex="0" data-pb-toggle aria-expanded="false" aria-controls="pb-body-{{ $banner->id }}">
      <svg class="pb-caret" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
      @if ($banner->image_url)
        <img class="pb-thumb" src="{{ $banner->image_url }}" alt="">
      @else
        <span class="pb-thumb"></span>
      @endif
      <div class="pb-meta">
        <div class="k">{{ $banner->label }}</div>
        <div class="v">{{ $banner->heading ?: 'No heading set' }}</div>
      </div>
    </div>

    <div class="pb-body" id="pb-body-{{ $banner->id }}">
      <form method="POST" action="{{ route('admin.page-banners.update', $banner) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @php($hasBanner = $banner->page_key !== 'home')
        <div class="pb-cols" @if (! $hasBanner) style="grid-template-columns:1fr;max-width:560px;" @endif>
          @if ($hasBanner)
          <div>
            <h3 style="font-size:13px;text-transform:uppercase;letter-spacing:.06em;color:var(--stone);margin-bottom:12px;">On the page</h3>
            <div class="field" style="margin-bottom:14px;">
              <label>Eyebrow</label>
              <input type="text" name="eyebrow" value="{{ $banner->eyebrow }}">
              <span class="hint">Small label above the heading.</span>
            </div>
            <div class="field" style="margin-bottom:14px;">
              <label>Heading</label>
              <input type="text" name="heading" value="{{ $banner->heading }}">
            </div>
            <div class="field" style="margin-bottom:14px;">
              <label>Intro Paragraph</label>
              <textarea name="intro" style="min-height:80px;">{{ $banner->intro }}</textarea>
            </div>
            @include('admin.partials.image-field', [
              'name' => 'image_path',
              'label' => 'Banner Image',
              'currentUrl' => $banner->image_url,
            ])
          </div>
          @endif

          <div>
            @unless ($hasBanner)
              <p class="hint" style="margin-bottom:14px;">The homepage opens with the hero slider rather than a banner, so only its search listing is set here. The share image follows the first hero slide.</p>
            @endunless
            <h3 style="font-size:13px;text-transform:uppercase;letter-spacing:.06em;color:var(--stone);margin-bottom:12px;">Search &amp; sharing</h3>
            <div class="field" style="margin-bottom:14px;">
              <label>SEO Title</label>
              <input type="text" name="seo_title" value="{{ $banner->seo_title }}">
              <span class="hint">Shown in the browser tab and Google results.</span>
            </div>
            <div class="field" style="margin-bottom:14px;">
              <label>Meta Description</label>
              <textarea name="seo_description" style="min-height:80px;">{{ $banner->seo_description }}</textarea>
              <span class="hint">The summary under the title in search results. Around 155 characters works best.</span>
            </div>
            @if ($hasBanner)
              @include('admin.partials.image-field', [
                'name' => 'og_image_path',
                'label' => 'Social Share Image',
                'currentUrl' => $banner->og_image_path ? $banner->og_image_url : null,
                'hint' => 'Optional — the banner image is used when this is empty.',
              ])
            @endif
          </div>
        </div>

        <div style="margin-top:16px;display:flex;justify-content:flex-end;">
          <button class="btn btn-primary" type="submit">Save “{{ $banner->label }}”</button>
        </div>
      </form>
    </div>
  </div>
@empty
  <div class="empty-state">
    <h3>No page headers yet</h3>
    <p>Run <code>php artisan db:seed --class=PageBannerSeeder</code> to create them.</p>
  </div>
@endforelse
@endsection

@push('scripts')
<script>
  document.querySelectorAll('[data-pb-toggle]').forEach((head) => {
    const item = head.closest('.pb-item');
    const toggle = () => {
      const open = item.classList.toggle('is-open');
      head.setAttribute('aria-expanded', open ? 'true' : 'false');
    };
    head.addEventListener('click', toggle);
    head.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggle(); }
    });
  });

  // Deep-link back to the row that was just saved.
  if (location.hash.startsWith('#pb-')) {
    const target = document.querySelector(location.hash);
    if (target) target.classList.add('is-open');
  }
</script>
@endpush
