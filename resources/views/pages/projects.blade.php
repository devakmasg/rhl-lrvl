@extends('layouts.app')

@section('canonical', route('projects.index'))

@section('content')
@include('partials.page-header')

<section class="portfolio" id="portfolio">
  <div class="wrap">
    <div class="portfolio-head">
      <span class="intro-tag reveal-up">{{ $sections->eyebrow('portfolio') }}</span>
      <h2 class="reveal-up">{{ $sections->heading('portfolio') }}</h2>
    </div>

    <form class="filters" id="projectFilters" role="search" aria-label="Filter developments" method="GET" action="{{ route('projects.index') }}#portfolio">
      <div class="filter-row">
        <fieldset class="filter-field">
          <legend>Status</legend>
          <div class="seg">
            <input type="radio" name="status" value="all" id="f-st-all" {{ !request()->filled('status') || request('status') === 'all' ? 'checked' : '' }} onchange="this.form.submit()">
            <label for="f-st-all">All</label>
            {{-- Values are the slugged status, matching each card's data-status
                 so the client-side filter in projects.js keeps working without
                 a second list to maintain. --}}
            @foreach (\App\Models\Project::statusFilterOptions() as $slug => $label)
              <input type="radio" name="status" value="{{ $slug }}" id="f-st-{{ $slug }}" {{ request('status') === $slug ? 'checked' : '' }} onchange="this.form.submit()">
              <label for="f-st-{{ $slug }}">{{ $label }}</label>
            @endforeach
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
            {{-- Values are the lowercased name, matching each card's
                 data-location so the client-side filter in projects.js keeps
                 working without a second list to maintain. --}}
            @foreach ($locations as $location)
              <option value="{{ $location->filter_value }}" {{ strtolower((string) request('location')) === $location->filter_value ? 'selected' : '' }}>{{ $location->name }}</option>
            @endforeach
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
      {{ $sections->body('empty') }}
      <a class="link-arrow" id="emptyClear" href="{{ route('projects.index') }}#portfolio">{{ $sections->linkLabel('empty') }} →</a>
    </p>
  </div>
</section>
@endsection
