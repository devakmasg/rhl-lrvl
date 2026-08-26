{{-- "View page" link for an editor's page-head.

     Opens the live page in a new tab so the editor can check a change without
     hunting for the URL or losing the form they are working in.

     Usage: @include('admin.partials.view-page', ['route' => 'projects.index'])
     Pass 'params' for a route that needs them. A route that no longer exists
     renders nothing rather than throwing. --}}
@php
  $viewRoute = $route ?? null;
  $viewParams = $params ?? [];
  $viewUrl = $viewRoute && \Illuminate\Support\Facades\Route::has($viewRoute)
    ? route($viewRoute, $viewParams)
    : null;
@endphp
@if ($viewUrl)
  <a class="btn btn-outline btn-sm" href="{{ $viewUrl }}" target="_blank" rel="noopener">
    View page
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="13" height="13" style="margin-left:5px;vertical-align:-1px;"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
  </a>
@endif
