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

@include('partials.connect')
@endsection
