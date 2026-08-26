{{-- Contact details and social links come from Setting (admin → Settings).
     $setting is bound by the view composer in AppServiceProvider, so this
     renders correctly on every page regardless of the controller. The
     fallbacks keep the layout intact if the settings row is missing. --}}
@php
  $phone = $setting->phone ?? '+880 1711-234567';
  $email = $setting->email ?? 'hello@rhlproperties.com.bd';
  $address = $setting->address ?? 'House 24, Road 11, Gulshan-1, Dhaka 1212, Bangladesh';
  $whatsapp = $setting?->whatsapp_digits ?: '8801711234567';
  $socials = $setting?->socialLinks() ?: [];
@endphp
<footer>
  <div class="footer-top">
    <div class="footer-brand">
      <span class="word">{{ \App\Support\Brand::name() }}</span>
      <p>{{ $setting->footer_blurb ?? 'A diversified real estate and investment group building landmark residential, commercial and hospitality developments since 1998.' }}</p>
    </div>
    <div>
      <h4>Contact</h4>
      <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}">{{ $phone }}</a>
      <a href="mailto:{{ $email }}">{{ $email }}</a>
      <p>{!! nl2br(e($address)) !!}</p>
    </div>
    <div>
      <h4>Explore</h4>
      <a href="{{ route('about') }}">About</a>
      <a href="{{ route('projects.index') }}">Projects</a>
      <a href="{{ route('services') }}">Services</a>
      <a href="{{ route('partners') }}">Partners</a>
      <a href="{{ route('partners') }}">Investors &amp; Landowners</a>
      <a href="{{ route('testimonials') }}">Testimonials</a>
      <a href="{{ route('contact') }}">Contact</a>
    </div>
    @if ($socials)
      <div>
        <h4>Follow</h4>
        @foreach ($socials as $label => $url)
          <a href="{{ $url }}" target="_blank" rel="noopener">{{ $label }}</a>
        @endforeach
      </div>
    @endif
  </div>
  <div class="footer-bottom">
    <span>&copy; {{ date('Y') }} {{ \App\Support\Brand::name() }}. All Rights Reserved.</span>
    <span>Concept design template</span>
  </div>
</footer>

<div class="float-actions">
  <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" class="float-btn float-call" aria-label="Call {{ \App\Support\Brand::shortName() }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
  </a>
  <a href="https://wa.me/{{ $whatsapp }}" class="float-btn float-whatsapp" aria-label="Chat with us on WhatsApp" target="_blank" rel="noopener">
    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.5 14.4c-.3-.1-1.6-.8-1.9-.9-.2-.1-.4-.1-.6.1-.2.3-.7.9-.8 1-.2.2-.3.2-.5.1-.3-.1-1.1-.4-2.1-1.3-.8-.7-1.3-1.6-1.5-1.8-.1-.2 0-.4.1-.5.1-.1.3-.3.4-.5.1-.1.2-.3.3-.4.1-.2 0-.4 0-.5C11 9.5 10.6 8.5 10.4 8c-.1-.4-.3-.3-.5-.3h-.4c-.2 0-.5.1-.7.3-.2.3-.9.9-.9 2.1s1 2.5 1.1 2.6c.1.2 1.9 3 4.7 4.1.6.3 1.1.4 1.5.6.6.2 1.2.1 1.6.1.5-.1 1.6-.6 1.8-1.3.2-.6.2-1.1.2-1.2-.1-.2-.3-.2-.5-.3z"/><path d="M12 2a10 10 0 0 0-8.5 15.2L2 22l4.9-1.5A10 10 0 1 0 12 2zm0 18.2c-1.6 0-3.1-.4-4.4-1.2l-.3-.2-3 .9.9-2.9-.2-.3A8.2 8.2 0 1 1 20.2 12 8.2 8.2 0 0 1 12 20.2z"/></svg>
  </a>
</div>

<button class="totop" id="toTop" aria-label="Back to top">&uarr;</button>
