@extends('layouts.app')

@section('canonical', route('achievements'))

@section('content')
@include('partials.page-header')

<section class="audience" style="padding-top:110px;">
  <div class="wrap">
    <div class="audience-head">
      <span class="intro-tag reveal-up">Awards &amp; Recognition</span>
      <h2 class="reveal-up">Industry recognition, earned project by project.</h2>
    </div>
    <div class="pillars">
      <div class="pillar reveal-card">
        <span class="pillar-idx">2023</span>
        <h3>Best Residential Developer</h3>
        <p>Bangladesh Real Estate &amp; Housing Awards, recognising Gulshan Heights' design and on-time handover.</p>
      </div>
      <div class="pillar reveal-card">
        <span class="pillar-idx">2022</span>
        <h3>RAJUK Compliance Excellence</h3>
        <p>Recognised for a fully clean approval record across every active development at the time.</p>
      </div>
      <div class="pillar reveal-card">
        <span class="pillar-idx">2021</span>
        <h3>Best Commercial Project</h3>
        <p>RHL Logistics Hub in Tejgaon, awarded for design efficiency in light-industrial development.</p>
      </div>
      <div class="pillar reveal-card">
        <span class="pillar-idx">2019</span>
        <h3>Customer Trust Award</h3>
        <p>REHAB Bangladesh recognition for handover satisfaction across residential deliveries.</p>
      </div>
    </div>
  </div>
</section>

<section class="milestones">
  <div class="wrap">
    <div class="milestones-head">
      <span class="intro-tag reveal-up">Certifications &amp; Memberships</span>
      <h2 class="reveal-up">Standing behind our approvals.</h2>
    </div>
    <div class="process">
      <div class="step reveal-card">
        <div class="step-num">01</div>
        <div><h3>REHAB Bangladesh Member</h3><p>Registered member of the Real Estate &amp; Housing Association of Bangladesh since 2001.</p></div>
      </div>
      <div class="step reveal-card">
        <div class="step-num">02</div>
        <div><h3>RAJUK-Registered Developer</h3><p>Every current and completed development carries a verifiable RAJUK approval on file.</p></div>
      </div>
      <div class="step reveal-card">
        <div class="step-num">03</div>
        <div><h3>ISO 9001:2015 Certified</h3><p>Quality management certification covering our construction and project-delivery processes.</p></div>
      </div>
      <div class="step reveal-card">
        <div class="step-num">04</div>
        <div><h3>Fire Service &amp; Civil Defence Clearance</h3><p>All occupied developments hold current fire-safety clearance certificates.</p></div>
      </div>
    </div>
  </div>
</section>

@include('partials.connect')
@endsection
