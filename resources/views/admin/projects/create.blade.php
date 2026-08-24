@extends('layouts.admin')

@section('title', 'New Project')

@section('content')
<div class="page-head">
  <div>
    <h1>New Project</h1>
    <p>All fields feed the public project detail page (name, facts, amenities, unit table, floor plans).</p>
  </div>
  <div class="page-head-actions">
    <a href="{{ route('admin.projects.index') }}" class="btn btn-outline">Cancel</a>
    <button class="btn btn-primary" type="submit" form="projectForm">Publish Project</button>
  </div>
</div>

@include('admin.projects._form')
@endsection
