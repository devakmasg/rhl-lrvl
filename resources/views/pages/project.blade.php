@extends('layouts.app')

@section('title', $project->name.' | '.\App\Support\Brand::name())
@section('description', $project->summary)
@section('og_image', $project->hero_image_url)
@section('canonical', route('projects.show', $project->slug))

@section('content')
<div id="projectDetail">
  <section class="pd-hero">
    <div class="page-header-media" id="pdHeroMedia" data-parallax-header="0.22" style="background-image:url('{{ $project->hero_image_url }}')"></div>
    <div class="wrap pd-hero-inner">
      <a href="{{ route('projects.index') }}#portfolio" class="pd-back">← All developments</a>
      <span class="pcard-status is-{{ strtolower($project->status) }}" id="pdStatus">{{ $project->status }}</span>
      <h1 data-reveal="load" id="pdName">{{ $project->name }}</h1>
      <p class="pd-meta" id="pdMeta">{{ $project->type }} &middot; {{ $project->location }}</p>
      <p class="pd-summary" id="pdSummary">{{ $project->summary }}</p>
    </div>
  </section>

  <section class="pd-facts-band">
    <dl class="wrap pd-facts" id="pdFacts">
      @foreach (($project->facts ?? []) as $key => $value)
        <div class="fact"><dt>{{ $key }}</dt><dd>{{ $value }}</dd></div>
      @endforeach
    </dl>
  </section>

  <section class="pd-main">
    <div class="wrap pd-main-grid">
      <div class="pd-body" id="pdBody">
        @foreach (explode("\n\n", $project->body ?? '') as $para)
          <p>{{ $para }}</p>
        @endforeach
      </div>

      <aside class="pd-side">
        <div class="pd-progress" id="pdProgress" @if(is_null($project->progress)) hidden @endif>
          <h3>Construction progress</h3>
          <div class="pd-progress-track" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $project->progress }}" aria-label="Construction progress">
            <i id="pdProgressBar" style="width:{{ $project->progress }}%"></i>
          </div>
          <span class="pd-progress-value" id="pdProgressValue">{{ $project->progress }}% complete</span>
          <div class="pd-stage-track" id="pdStageTrack">
            @if (!is_null($currentStageIndex))
              @foreach ($stages as $i => $label)
                <div class="pd-stage {{ $i < $currentStageIndex ? 'is-done' : ($i === $currentStageIndex ? 'is-current' : '') }}">
                  <div class="pd-stage-bar"></div><span class="pd-stage-label">{{ $label }}</span>
                </div>
              @endforeach
            @endif
          </div>
        </div>

        <h3>Amenities</h3>
        <ul class="pd-amenities" id="pdAmenities">
          @foreach ($project->amenities as $amenity)
            <li>{{ $amenity->text }}</li>
          @endforeach
        </ul>

        <h3>Features</h3>
        <ul class="pd-features" id="pdFeatures">
          @foreach (($project->features ?? []) as $feature)
            <li>{{ $feature }}</li>
          @endforeach
        </ul>

        @if ($project->brochure_path)
          <a class="pd-brochure" id="pdBrochure" href="{{ $project->brochure_url }}" download="RHL-{{ $project->slug }}-brochure.pdf">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Download Brochure
          </a>
        @endif
        <a class="btn-solid pd-enquire" id="pdEnquire" href="#pdEnquireForm">Enquire about this development</a>
      </aside>
    </div>
  </section>

  @if ($project->units->isNotEmpty())
    <section class="pd-units-section" id="pdUnitsSection">
      <div class="wrap">
        <span class="intro-tag reveal-up">Unit Information</span>
        <h2 class="reveal-up">What's on offer.</h2>
        <div class="pd-units-wrap">
          <table class="pd-units-table" id="pdUnitsTable">
            <thead>
              <tr id="pdUnitsHead">
                @foreach ($unitsColumns as $col)
                  <th>{{ $col }}</th>
                @endforeach
              </tr>
            </thead>
            <tbody id="pdUnitsBody">
              @foreach ($project->units as $unit)
                <tr>
                  @if (!is_null($unit->beds))
                    <td>{{ $unit->unit_type }}</td>
                    <td>{{ $unit->size_sqft }}</td>
                    <td>{{ $unit->beds }}</td>
                    <td>{{ $unit->baths }}</td>
                  @else
                    <td>{{ $unit->unit_type }}</td>
                    <td>{{ $unit->size_sqft }}</td>
                    <td>{{ $unit->floorplate }}</td>
                    <td>{{ $unit->use }}</td>
                  @endif
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </section>
  @endif

  <section class="pd-gallery-section">
    <div class="wrap">
      <span class="intro-tag reveal-up">Gallery</span>
      <h2 class="reveal-up">A closer look.</h2>
      <div class="pd-gallery" id="pdGallery">
        @foreach ($project->images as $i => $image)
          <figure class="pd-shot">
            <img src="{{ $image->image_url }}" alt="{{ $project->name }} — view {{ $i + 1 }}" loading="lazy">
          </figure>
        @endforeach
      </div>
    </div>
  </section>

  <section class="pd-floorplans-section">
    <div class="wrap">
      <span class="intro-tag reveal-up">Floor Plans</span>
      <h2 class="reveal-up">Layouts at a glance.</h2>
      <div class="pd-floorplans" id="pdFloorplans">
        @foreach ($project->floorPlans as $plan)
          <button type="button" class="pd-floorplan" data-lightbox="{{ $plan->image_url }}" data-caption="{{ $project->name }} — {{ $plan->label }}">
            <img src="{{ $plan->image_url }}" alt="{{ $project->name }} — {{ $plan->label }}" loading="lazy">
            <span>{{ $plan->label }}</span>
          </button>
        @endforeach
      </div>
    </div>
  </section>

  <section class="pd-map-section">
    <div class="wrap">
      <span class="intro-tag reveal-up">Location</span>
      <h2 class="reveal-up">Find us on the map.</h2>
      <div class="pd-map-embed">
        <iframe id="pdMapEmbed" src="https://www.google.com/maps?q={{ urlencode($mapQuery) }}&output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Development location"></iframe>
      </div>
    </div>
  </section>

  <section class="pd-enquire-section" id="pdEnquireForm">
    <div class="wrap pd-enquire-inner">
      <div class="pd-enquire-head">
        <span class="intro-tag reveal-up">Enquire</span>
        <h2 class="reveal-up">Ask us about this development.</h2>
        <p class="reveal-up">Tell us a little about what you're looking for and our sales team will follow up within two working days.</p>
      </div>
      <form class="form" id="pdEnquiryForm" action="{{ route('inquiries.store') }}" method="POST">
        @csrf
        <input type="hidden" name="project_id" value="{{ $project->id }}">
        <div class="form-grid">
          <div class="field">
            <label for="pd-f-name">Full name <span class="req" aria-hidden="true">*</span></label>
            <input type="text" id="pd-f-name" name="name" data-label="Full name" required autocomplete="name">
            <span class="field-error" aria-live="polite"></span>
          </div>
          <div class="field">
            <label for="pd-f-phone">Phone <span class="req" aria-hidden="true">*</span></label>
            <input type="tel" id="pd-f-phone" name="phone" data-label="Phone" required autocomplete="tel"
                   pattern="[0-9+()\-\s]{6,}" data-error-pattern="Use digits, spaces and + ( ) - only.">
            <span class="field-error" aria-live="polite"></span>
          </div>
          <div class="field">
            <label for="pd-f-email">Email <span class="req" aria-hidden="true">*</span></label>
            <input type="email" id="pd-f-email" name="email" data-label="Email" required autocomplete="email">
            <span class="field-error" aria-live="polite"></span>
          </div>
          <div class="field">
            <label for="pd-f-project">Interested project</label>
            <input type="text" id="pd-f-project" name="project" data-label="Interested project" value="{{ $project->name }}" readonly>
            <span class="field-error" aria-live="polite"></span>
          </div>
          <div class="field field-full">
            <label for="pd-f-message">Message <span class="req" aria-hidden="true">*</span></label>
            <textarea id="pd-f-message" name="message" data-label="Message" required minlength="20"
                      placeholder="A sentence or two about what you need and your timeframe."></textarea>
            <span class="field-error" aria-live="polite"></span>
          </div>
        </div>
        <div class="form-foot">
          <button type="submit" class="btn-solid">Send enquiry</button>
          <p class="form-note">We use your details only to answer your enquiry, and never share them with third parties.</p>
        </div>
        <p class="form-status" role="status" aria-live="polite"></p>
      </form>
    </div>
  </section>

  <nav class="pd-nav" aria-label="Development navigation">
    <div class="wrap pd-nav-grid">
      <a class="pd-nav-link pd-nav-prev" id="pdPrev" href="{{ route('projects.show', $prev->slug) }}" aria-label="Previous development: {{ $prev->name }}">
        <span class="pd-nav-label">← Previous</span>
        <span class="pd-nav-name">{{ $prev->name }}</span>
      </a>
      <a class="pd-nav-link pd-nav-next" id="pdNext" href="{{ route('projects.show', $next->slug) }}" aria-label="Next development: {{ $next->name }}">
        <span class="pd-nav-label">Next →</span>
        <span class="pd-nav-name">{{ $next->name }}</span>
      </a>
    </div>
  </nav>

  <section class="portfolio pd-related-section">
    <div class="wrap">
      <div class="portfolio-head">
        <span class="intro-tag reveal-up">Related</span>
        <h2 class="reveal-up">Others you may want to see.</h2>
      </div>
      <div class="portfolio-grid" id="pdRelated">
        @foreach ($related as $relatedProject)
          @include('partials.project-card', ['project' => $relatedProject])
        @endforeach
      </div>
    </div>
  </section>
</div>

<div class="lightbox" id="lightbox" role="dialog" aria-modal="true" aria-label="Floor plan preview">
  <button class="lightbox-close" id="lightboxClose" aria-label="Close preview">&times;</button>
  <img id="lightboxImg" src="" alt="">
  <span class="lightbox-caption" id="lightboxCaption"></span>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/lightbox.js') }}"></script>
@endpush
