@extends('layouts.app')

@section('title', 'Projects | RHL Properties Ltd')
@section('description', "Browse RHL Properties Ltd's portfolio of ongoing, upcoming and completed residential and commercial developments across Gulshan, Banani, Dhanmondi and Tejgaon, Dhaka.")
@section('og_image', asset('assets/images/hero-2-commercial.jpg'))
@section('canonical', route('projects.index'))

@section('content')
<section class="page-header">
  <div class="page-header-media" data-parallax-header="0.22" style="background-image:url('{{ asset('assets/images/hero-2-commercial.jpg') }}')"></div>
  <div class="wrap">
    <span class="intro-tag">Featured Developments</span>
    <h1 data-reveal="load">Landmarks in the making</h1>
    <p>Our portfolio spans residential, commercial and mixed-use developments across the region's most sought-after districts.</p>
  </div>
</section>

<section class="portfolio" id="portfolio">
  <div class="wrap">
    <div class="portfolio-head">
      <span class="intro-tag reveal-up">The Portfolio</span>
      <h2 class="reveal-up">Every development, filtered your way.</h2>
    </div>

    <form class="filters" id="projectFilters" role="search" aria-label="Filter developments" method="GET" action="{{ route('projects.index') }}#portfolio">
      <div class="filter-row">
        <fieldset class="filter-field">
          <legend>Status</legend>
          <div class="seg">
            <input type="radio" name="status" value="all" id="f-st-all" {{ !request()->filled('status') || request('status') === 'all' ? 'checked' : '' }} onchange="this.form.submit()">
            <label for="f-st-all">All</label>
            <input type="radio" name="status" value="ongoing" id="f-st-ongoing" {{ request('status') === 'ongoing' ? 'checked' : '' }} onchange="this.form.submit()">
            <label for="f-st-ongoing">Ongoing</label>
            <input type="radio" name="status" value="upcoming" id="f-st-upcoming" {{ request('status') === 'upcoming' ? 'checked' : '' }} onchange="this.form.submit()">
            <label for="f-st-upcoming">Upcoming</label>
            <input type="radio" name="status" value="completed" id="f-st-completed" {{ request('status') === 'completed' ? 'checked' : '' }} onchange="this.form.submit()">
            <label for="f-st-completed">Completed</label>
          </div>
        </fieldset>

        <fieldset class="filter-field">
          <legend>Property type</legend>
          <div class="seg">
            <input type="radio" name="type" value="all" id="f-ty-all" {{ !request()->filled('type') || request('type') === 'all' ? 'checked' : '' }} onchange="this.form.submit()">
            <label for="f-ty-all">All</label>
            <input type="radio" name="type" value="residential" id="f-ty-residential" {{ request('type') === 'residential' ? 'checked' : '' }} onchange="this.form.submit()">
            <label for="f-ty-residential">Residential</label>
            <input type="radio" name="type" value="commercial" id="f-ty-commercial" {{ request('type') === 'commercial' ? 'checked' : '' }} onchange="this.form.submit()">
            <label for="f-ty-commercial">Commercial</label>
          </div>
        </fieldset>
      </div>

      <div class="filter-row filter-row-lower">
        <div class="filter-field">
          <label class="filter-label" for="f-location">Location</label>
          <select id="f-location" name="location" class="filter-select" onchange="this.form.submit()">
            <option value="all" {{ !request()->filled('location') || request('location') === 'all' ? 'selected' : '' }}>All locations</option>
            <option value="banani" {{ request('location') === 'banani' ? 'selected' : '' }}>Banani</option>
            <option value="gulshan" {{ request('location') === 'gulshan' ? 'selected' : '' }}>Gulshan</option>
            <option value="dhanmondi" {{ request('location') === 'dhanmondi' ? 'selected' : '' }}>Dhanmondi</option>
            <option value="tejgaon" {{ request('location') === 'tejgaon' ? 'selected' : '' }}>Tejgaon</option>
          </select>
        </div>

        <div class="filter-field filter-field-grow">
          <label class="filter-label" for="f-search">Search</label>
          <input type="search" id="f-search" name="q" class="filter-search"
                 placeholder="Search by name, type or area…" autocomplete="off" value="{{ request('q') }}">
        </div>

        <button type="submit" class="filter-clear">Apply filters</button>
        <a href="{{ route('projects.index') }}#portfolio" class="filter-clear" id="filterClear">Clear filters</a>
      </div>
    </form>

    <p class="filter-count" id="filterCount" role="status" aria-live="polite">
      {{ $projects->count() }} {{ Str::plural('development', $projects->count()) }} found
    </p>

    <div class="portfolio-grid" id="portfolioGrid">
      @foreach ($projects as $project)
        @include('partials.project-card', ['project' => $project])
      @endforeach
    </div>

    <p class="portfolio-empty" id="portfolioEmpty" @if($projects->isNotEmpty()) hidden @endif>
      No developments match these filters.
      <a class="link-arrow" id="emptyClear" href="{{ route('projects.index') }}#portfolio">Clear filters →</a>
    </p>
  </div>
</section>
@endsection
