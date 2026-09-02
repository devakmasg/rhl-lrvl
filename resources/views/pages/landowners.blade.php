@extends('layouts.app')

@section('canonical', route('landowners'))

@section('content')
{{-- One block for the whole page. Keep it that way: the inline shorthand form
     of this directive, used anywhere above a block form, silently breaks the
     rest of the compiled file. --}}
@php
  $videoEmbed = \App\Support\VideoEmbed::url($page->get('video_url'));

  // Pillars and process come from the partners row — the same content the
  // Partners page shows under "For Landowners". Edited in admin → Partners
  // Page so the two pages cannot say different things.
  $pillars = $partnersPage?->list('landowner_pillars') ?? [];
  $steps = $partnersPage?->list('landowner_steps') ?? [];
@endphp
@include('partials.page-header')

{{-- Opening argument. Reuses the Partners page's intro grid, so the two pages
     that speak to partners look like the same site. --}}
<section class="partner-intro">
  <div class="wrap partner-intro-grid">
    <div>
      <span class="intro-tag reveal-up">{{ $page->get('intro_eyebrow') }}</span>
      <h2 class="reveal-up">{{ $page->get('intro_heading') }}</h2>
      <p class="reveal-up">{{ $page->get('intro_text_1') }}</p>
      <p class="reveal-up">{{ $page->get('intro_text_2') }}</p>
      <a href="#submit" class="btn-solid lo-cta reveal-up">{{ $page->get('contact_eyebrow') ?: 'Submit your plot' }} &rarr;</a>
    </div>
    <div class="partner-figure reveal-up">
      <img src="{{ $page->imageUrl('intro_image') }}" alt="A completed {{ \App\Support\Brand::shortName() }} joint-venture development" loading="lazy" decoding="async">
    </div>
  </div>
</section>

{{-- Optional explainer video. The whole block disappears when no URL is set,
     rather than leaving an empty frame on the page. --}}
@if ($videoEmbed)
<section class="lo-video">
  <div class="wrap">
    <div class="lo-video-frame reveal-up">
      <iframe src="{{ $videoEmbed }}" title="{{ $page->get('video_caption') ?: 'How a joint venture works' }}"
              loading="lazy" allowfullscreen
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
    </div>
    @if ($page->get('video_caption'))
      <p class="lo-video-caption reveal-up">{{ $page->get('video_caption') }}</p>
    @endif
  </div>
</section>
@endif

{{-- The differentiator block: same grid, image on the other side. --}}
<section class="partner-intro is-reversed">
  <div class="wrap partner-intro-grid">
    <div class="partner-figure reveal-up">
      <img src="{{ $page->imageUrl('diff_image') }}" alt="{{ \App\Support\Brand::shortName() }} construction team on site" loading="lazy" decoding="async">
    </div>
    <div>
      <span class="intro-tag reveal-up">{{ $page->get('diff_eyebrow') }}</span>
      <h2 class="reveal-up">{{ $page->get('diff_heading') }}</h2>
      <p class="reveal-up">{{ $page->get('diff_text_1') }}</p>
      <p class="reveal-up">{{ $page->get('diff_text_2') }}</p>
    </div>
  </div>
</section>

@if ($pillars)
<section class="audience">
  <div class="wrap">
    <div class="audience-head">
      <span class="intro-tag reveal-up">{{ $page->get('pillars_eyebrow') }}</span>
      <h2 class="reveal-up">{{ $page->get('pillars_heading') }}</h2>
    </div>
    <div class="pillars">
      @foreach ($pillars as $i => $pillar)
        <div class="pillar reveal-card">
          <span class="pillar-idx">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
          <h3>{{ $pillar['title'] }}</h3>
          <p>{{ $pillar['desc'] }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

@if ($steps)
<section class="audience" style="padding-top:0;">
  <div class="wrap">
    <div class="audience-head">
      <span class="intro-tag reveal-up">{{ $page->get('process_eyebrow') }}</span>
      <h2 class="reveal-up">{{ $page->get('process_heading') }}</h2>
    </div>
    <div class="process">
      @foreach ($steps as $i => $step)
        <div class="step reveal-up">
          <span class="step-num">Step {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
          <div><h3>{{ $step['title'] }}</h3><p>{{ $step['desc'] }}</p></div>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

@if ($page->list('quotes'))
<section class="lo-quotes">
  <div class="wrap">
    <div class="audience-head">
      <span class="intro-tag reveal-up">{{ $page->get('quotes_eyebrow') }}</span>
      <h2 class="reveal-up">{{ $page->get('quotes_heading') }}</h2>
    </div>
    <div class="lo-quote-grid">
      @foreach ($page->list('quotes') as $quote)
        <figure class="lo-quote reveal-card">
          <blockquote>{{ $quote['quote'] }}</blockquote>
          <figcaption>
            <span class="lo-quote-name">{{ $quote['name'] }}</span>
            @if (! empty($quote['project']))
              <span class="lo-quote-project">{{ $quote['project'] }}</span>
            @endif
          </figcaption>
        </figure>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- FAQ. Native <details> rather than a scripted accordion: it opens with no
     JS, is keyboard-operable for free, and a browser's find-in-page can reach
     the answers. --}}
@if ($page->list('faqs'))
<section class="lo-faq">
  <div class="wrap">
    <div class="audience-head">
      <span class="intro-tag reveal-up">{{ $page->get('faq_eyebrow') }}</span>
      <h2 class="reveal-up">{{ $page->get('faq_heading') }}</h2>
    </div>
    <div class="lo-faq-list">
      @foreach ($page->list('faqs') as $faq)
        <details class="lo-faq-item reveal-up">
          <summary>{{ $faq['q'] }}<span class="lo-faq-icon" aria-hidden="true"></span></summary>
          <div class="lo-faq-answer"><p>{{ $faq['a'] }}</p></div>
        </details>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- Submission form. Posts to the same handler as the Partners page form, so
     these land in the one inbox with the same reference numbering — but the
     role is fixed to "landowner" rather than asked, because on this page the
     answer is already known. --}}
<section class="contact" id="submit">
  <div class="wrap contact-grid">
    <div>
      <div class="contact-form-head">
        <span class="intro-tag reveal-up">{{ $page->get('contact_eyebrow') }}</span>
        <h2 class="reveal-up">{{ $page->get('contact_heading') }}</h2>
        <p class="reveal-up">{{ $page->get('contact_text') }}</p>
      </div>

      <form class="form" id="landownerForm" method="POST" action="{{ route('inquiries.partner.store') }}" novalidate>
        @csrf
        <input type="hidden" name="role" value="landowner">
        <div class="form-grid">
          <div class="field @error('name') has-error @enderror">
            <label for="lo-name">Full name <span class="req" aria-hidden="true">*</span></label>
            <input type="text" id="lo-name" name="name" data-label="Full name" required autocomplete="name" value="{{ old('name') }}">
            <span class="field-error" aria-live="polite">@error('name'){{ $message }}@enderror</span>
          </div>

          <div class="field @error('phone') has-error @enderror">
            <label for="lo-phone">Phone <span class="req" aria-hidden="true">*</span></label>
            <input type="tel" id="lo-phone" name="phone" data-label="Phone" required autocomplete="tel"
                   data-bd-phone pattern="[0-9+()\-\s]{6,}" data-error-pattern="Use digits, spaces and + ( ) - only."
                   value="{{ old('phone') }}" placeholder="+880 1XXX-XXXXXX">
            <span class="field-error" aria-live="polite">@error('phone'){{ $message }}@enderror</span>
          </div>

          <div class="field @error('email') has-error @enderror">
            <label for="lo-email">Email <span class="req" aria-hidden="true">*</span></label>
            <input type="email" id="lo-email" name="email" data-label="Email" required autocomplete="email" value="{{ old('email') }}">
            <span class="field-error" aria-live="polite">@error('email'){{ $message }}@enderror</span>
          </div>

          <div class="field @error('area') has-error @enderror">
            <label for="lo-area">Where is the plot?</label>
            <select id="lo-area" name="area" data-label="Area">
              <option value="">Please choose&hellip;</option>
              @foreach (['banani' => 'Banani', 'gulshan' => 'Gulshan', 'dhanmondi' => 'Dhanmondi', 'tejgaon' => 'Tejgaon', 'other' => 'Elsewhere'] as $value => $label)
                <option value="{{ $value }}" {{ old('area') === $value ? 'selected' : '' }}>{{ $label }}</option>
              @endforeach
            </select>
            <span class="field-error" aria-live="polite">@error('area'){{ $message }}@enderror</span>
          </div>

          <div class="field field-full @error('size') has-error @enderror">
            <label for="lo-size">Plot size</label>
            <input type="text" id="lo-size" name="size" data-label="Plot size"
                   placeholder="e.g. 10 katha, corner plot facing south" value="{{ old('size') }}">
            <span class="field-error" aria-live="polite">@error('size'){{ $message }}@enderror</span>
          </div>

          <div class="field field-full @error('message') has-error @enderror">
            <label for="lo-message">About the land <span class="req" aria-hidden="true">*</span></label>
            <textarea id="lo-message" name="message" data-label="About the land" required minlength="20"
                      placeholder="Road and holding number, ownership status (single owner, joint, inherited), and whether the plot is currently vacant or built on.">{{ old('message') }}</textarea>
            <span class="field-error" aria-live="polite">@error('message'){{ $message }}@enderror</span>
          </div>
        </div>

        <div class="form-foot">
          <button type="submit" class="btn-solid">Submit your plot</button>
          {{-- Deliberately short: .form-foot lays the note out beside the
               button, so a long paragraph here strands the button against a
               tall block. The full confidentiality wording is in the sidebar. --}}
          <p class="form-note">{{ $page->get('form_note') }}</p>
        </div>

        @if ($errors->any())
          <p class="form-status is-bad" role="status" aria-live="polite">
            {{ $errors->count() === 1 ? 'One field needs attention before this can be sent.' : $errors->count().' fields need attention before this can be sent.' }}
          </p>
        @endif
      </form>
    </div>

    <aside class="contact-aside">
      <div class="detail-block reveal-up">
        <h3>{{ $page->get('aside_heading') }}</h3>
        <p class="muted">{{ $page->get('aside_text') }}</p>
        <a href="tel:{{ preg_replace('/\s+/', '', $setting->phone ?? '+8801711234567') }}">{{ $setting->phone ?? '+880 1711-234567' }}</a>
        <a href="mailto:{{ $setting->email ?? 'hello@rhlproperties.com.bd' }}">{{ $setting->email ?? 'hello@rhlproperties.com.bd' }}</a>
      </div>
      <div class="detail-block reveal-up">
        <h3>{{ $page->get('aside_confidence_heading') }}</h3>
        <p class="muted">{{ $page->get('aside_confidence_text') }}</p>
      </div>
    </aside>
  </div>
</section>

@include('partials.connect')
@endsection
