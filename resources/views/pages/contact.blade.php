@extends('layouts.app')

@section('canonical', route('contact'))

@section('content')
@include('partials.page-header', [
  'introHtml' => 'Reach us at <a href="tel:'.e(preg_replace('/\s+/', '', $setting->phone ?? '+8801711234567')).'" style="color:var(--gold-light)">'.e($setting->phone ?? '+880 1711-234567').'</a>'
    .' or <a href="mailto:'.e($setting->email ?? 'hello@rhlproperties.com.bd').'" style="color:var(--gold-light)">'.e($setting->email ?? 'hello@rhlproperties.com.bd').'</a>.',
])

<section class="contact" id="enquire">
  <div class="wrap contact-grid">
    <div>
      <div class="contact-form-head">
        <span class="intro-tag reveal-up">Send an enquiry</span>
        <h2 class="reveal-up">Tell us what you're looking for.</h2>
        <p class="reveal-up">The more you can tell us up front, the more useful our first reply will be. Everything marked with an asterisk is required.</p>
      </div>

      @include('partials.inquiry-form', ['formId' => 'contactForm', 'projects' => $projects])
    </div>

    <aside class="contact-aside">
      <div class="detail-block reveal-up">
        <h3>Talk to us</h3>
        <a href="tel:{{ $setting->phone ?? '+8801711234567' }}">{{ $setting->phone ?? '+880 1711-234567' }}</a>
        <a href="mailto:{{ $setting->email ?? 'hello@rhlproperties.com.bd' }}">{{ $setting->email ?? 'hello@rhlproperties.com.bd' }}</a>
        <div class="quick-contact">
          <a href="tel:{{ $setting->phone ?? '+8801711234567' }}" class="btn-solid quick-contact-call">Call Now</a>
          <a href="https://wa.me/{{ preg_replace('/\D/', '', $setting->whatsapp ?? '8801711234567') }}" target="_blank" rel="noopener" class="btn-solid quick-contact-whatsapp">WhatsApp Us</a>
        </div>
      </div>
      <div class="detail-block reveal-up">
        <h3>Head office</h3>
        <p>{{ $setting->address ?? 'House 24, Road 11, Gulshan-1, Dhaka 1212, Bangladesh' }}</p>
      </div>
      <div class="detail-block reveal-up">
        <h3>Office hours</h3>
        <p class="muted">
          {{ $setting->hours_weekday ?? 'Sunday – Thursday, 9:00 – 18:00' }}<br>
          {{ $setting->hours_saturday ?? 'Saturday, 10:00 – 16:00' }}<br>
          {{ $setting->hours_friday ?? 'Friday closed' }}
        </p>
      </div>
      <div class="detail-block reveal-up">
        <h3>Land &amp; investment</h3>
        <p class="muted">Proposing a site or looking at returns? The partnership terms and process are set out in full.</p>
        <a href="{{ route('partners') }}" class="link-arrow">See how partnering works &rarr;</a>
      </div>
    </aside>
  </div>
</section>

@if ($setting && $setting->map_query)
<section class="pd-map-section">
  <div class="wrap">
    <span class="intro-tag reveal-up">Find Us</span>
    <h2 class="reveal-up">Our head office in Gulshan.</h2>
    <div class="pd-map-embed">
      <iframe src="https://www.google.com/maps?q={{ urlencode($setting->map_query) }}&output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="{{ \App\Support\Brand::name() }} head office location"></iframe>
    </div>
  </div>
</section>
@endif

@include('partials.connect')
@endsection

@push('scripts')
<script src="{{ asset('assets/js/forms.js') }}"></script>
<script>
  // contact?project=<id> pre-selects that project, so any external
  // "Ask about this project" link lands with the right context already set.
  (function(){
    const id = new URLSearchParams(location.search).get('project');
    if(!id) return;
    const select = document.getElementById('contactForm-project');
    if(select && select.querySelector(`option[value="${id}"]`)) select.value = id;
  })();
</script>
@endpush
