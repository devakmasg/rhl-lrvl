@extends('layouts.app')

@section('canonical', route('thank-you'))

@push('head')
<meta name="robots" content="noindex">
@endpush

@section('content')
@include('partials.page-header', [
  'headerStyle' => 'min-height:70vh;display:flex;align-items:center;',
  'heading' => ($banner?->heading ?: 'Thank you').(session('inquiry_name') ? ', '.session('inquiry_name') : '').'.',
  'intro' => session('inquiry_project')
    ? 'Your enquiry about '.session('inquiry_project').' has been received. Our team usually replies within two working days.'
    : 'Your enquiry has been received. Our team usually replies within two working days.',
])

<section class="connect">
  <span class="intro-tag reveal-up">While You Wait</span>
  <h2 class="reveal-up">Keep exploring RHL Properties.</h2>
  <div class="connect-grid">
    <div class="connect-card reveal-card">
      <h3>Browse Developments</h3>
      <p>See our full portfolio of residential, commercial and mixed-use projects across Dhaka.</p>
      <a href="{{ route('projects.index') }}" class="btn">View projects &rarr;</a>
    </div>
    <div class="connect-card reveal-card">
      <h3>Talk to Us Sooner</h3>
      <p>Need a faster answer? Call or WhatsApp our team directly during office hours.</p>
      @php($tyPhone = \App\Models\Setting::first()?->phone ?? '+880 1711-234567')
      <a href="tel:{{ preg_replace('/\s+/', '', $tyPhone) }}" class="btn">Call {{ $tyPhone }} &rarr;</a>
    </div>
  </div>
</section>
@endsection
