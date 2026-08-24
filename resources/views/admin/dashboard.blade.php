@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="page-head">
  <div>
    <h1>Welcome back, {{ explode(' ', auth()->user()->name)[0] }}</h1>
    <p>Here's what's happening across RHL Properties today.</p>
  </div>
  <div class="page-head-actions">
    <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">+ New Project</a>
  </div>
</div>

<div class="stat-grid">
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/></svg></div>
    </div>
    <div class="stat-num">{{ $stats['total_projects'] }}</div>
    <div class="stat-label">Total Projects</div>
  </div>
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
    </div>
    <div class="stat-num">{{ $stats['ongoing_projects'] }}</div>
    <div class="stat-label">Ongoing Projects</div>
  </div>
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div>
    </div>
    <div class="stat-num">{{ $stats['completed_projects'] }}</div>
    <div class="stat-label">Completed Projects</div>
  </div>
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg></div>
    </div>
    <div class="stat-num">{{ $stats['total_inquiries'] }}</div>
    <div class="stat-label">Total Inquiries</div>
  </div>
</div>

<div class="card">
  <div class="card-head">
    <div>
      <h2>Recent Inquiries</h2>
      <div class="card-head-sub">Latest leads across all projects</div>
    </div>
    <a href="{{ route('admin.inquiries.index') }}" class="btn btn-outline btn-sm">View all &rarr;</a>
  </div>
  <div class="table-scroll">
    <table class="table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Interested Project</th>
          <th>Phone</th>
          <th>Status</th>
          <th>Received</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse ($recentInquiries as $inquiry)
          <tr>
            <td><div class="cell-main">{{ $inquiry->name }}</div><div class="cell-sub">{{ $inquiry->email }}</div></td>
            <td>{{ $inquiry->project_name ?? '—' }}</td>
            <td>{{ $inquiry->phone }}</td>
            <td><span class="badge badge-{{ $inquiry->status }}">{{ ucfirst($inquiry->status) }}</span></td>
            <td>{{ $inquiry->created_at->format('d M Y') }}</td>
            <td class="cell-actions"><a href="{{ route('admin.inquiries.show', $inquiry) }}" class="btn btn-ghost btn-sm">View</a></td>
          </tr>
        @empty
          <tr><td colspan="6">No inquiries yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
