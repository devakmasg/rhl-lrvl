@extends('layouts.app')

@section('canonical', route('partners'))

@section('content')
@include('partials.page-header')

<section class="partner-intro">
  <div class="wrap partner-intro-grid">
    <div>
      <span class="intro-tag reveal-up">{{ $page->get('intro_eyebrow') }}</span>
      <h2 class="reveal-up">{{ $page->get('intro_heading') }}</h2>
      <p class="reveal-up">{{ $page->get('intro_text_1') }}</p>
      <p class="reveal-up">{{ $page->get('intro_text_2') }}</p>
    </div>
    <div class="partner-figure reveal-up">
      <img src="{{ $page->imageUrl('intro_image') }}" alt="A completed {{ \App\Support\Brand::shortName() }} commercial development" loading="lazy" decoding="async">
    </div>
  </div>
</section>

<section class="audience" id="partner-options">
  <div class="wrap">
    <div class="audience-head">
      <span class="intro-tag reveal-up">{{ $page->get('how_eyebrow') }}</span>
      <h2 class="reveal-up">{{ $page->get('how_heading') }}</h2>
    </div>

    <div class="audience-switch seg" id="audienceSwitch" role="tablist" aria-label="Choose partnership type">
      <button type="button" role="tab" id="tab-landowners" aria-controls="panel-landowners" aria-selected="true" tabindex="0">For Landowners</button>
      <button type="button" role="tab" id="tab-investors" aria-controls="panel-investors" aria-selected="false" tabindex="-1">For Investors</button>
    </div>

    <div class="audience-panel" id="panel-landowners" role="tabpanel" aria-labelledby="tab-landowners" tabindex="0">
      <p class="audience-lead">{{ $page->get('landowner_lead') }}</p>

      <div class="pillars">
        @foreach ($page->get('landowner_pillars', []) as $i => $pillar)
          <div class="pillar">
            <span class="pillar-idx">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
            <h3>{{ $pillar['title'] }}</h3>
            <p>{{ $pillar['desc'] }}</p>
          </div>
        @endforeach
      </div>

      <div class="process">
        @foreach ($page->get('landowner_steps', []) as $i => $step)
          <div class="step">
            <span class="step-num">Step {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
            <div><h3>{{ $step['title'] }}</h3><p>{{ $step['desc'] }}</p></div>
          </div>
        @endforeach
      </div>
    </div>

    <div class="audience-panel" id="panel-investors" role="tabpanel" aria-labelledby="tab-investors" tabindex="0" hidden>
      <p class="audience-lead">{{ $page->get('investor_lead') }}</p>

      <div class="pillars">
        @foreach ($page->get('investor_pillars', []) as $i => $pillar)
          <div class="pillar">
            <span class="pillar-idx">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
            <h3>{{ $pillar['title'] }}</h3>
            <p>{{ $pillar['desc'] }}</p>
          </div>
        @endforeach
      </div>

      <div class="process">
        @foreach ($page->get('investor_steps', []) as $i => $step)
          <div class="step">
            <span class="step-num">Step {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
            <div><h3>{{ $step['title'] }}</h3><p>{{ $step['desc'] }}</p></div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</section>

<section class="stats">
  <div class="stats-bg" id="statsBg" data-parallax-bg="0.25"></div>
  <div class="stats-inner wrap">
    <span class="intro-tag reveal-up" style="color:var(--gold-light)">{{ $page->get('stats_eyebrow') }}</span>
    <h2 class="reveal-up" style="max-width:640px;margin-bottom:50px;">{{ $page->get('stats_heading') }}</h2>
    <div class="stats-grid">
      @foreach ($page->get('stats', []) as $stat)
        @php
          preg_match('/^([\d.]+)(.*)$/', $stat['value'], $m);
          $decimals = str_contains($m[1] ?? '', '.') ? strlen(explode('.', $m[1])[1]) : 0;
        @endphp
        <div class="stat reveal-card"><div class="num" data-target="{{ $m[1] ?? $stat['value'] }}" data-decimals="{{ $decimals }}" data-suffix="{{ $m[2] ?? '' }}">0</div><div class="label">{{ $stat['label'] }}</div></div>
      @endforeach
    </div>
  </div>
</section>

<section class="contact" id="submit">
  <div class="wrap contact-grid">
    <div>
      <div class="contact-form-head">
        <span class="intro-tag reveal-up">{{ $page->get('contact_eyebrow') }}</span>
        <h2 class="reveal-up">{{ $page->get('contact_heading') }}</h2>
        <p class="reveal-up">{{ $page->get('contact_text') }}</p>
      </div>

      <form class="form" id="partnerForm" method="POST" action="{{ route('inquiries.partner.store') }}" novalidate>
        @csrf
        <div class="form-grid">
          <div class="field @error('name') has-error @enderror">
            <label for="p-name">Full name <span class="req" aria-hidden="true">*</span></label>
            <input type="text" id="p-name" name="name" data-label="Full name" required autocomplete="name" value="{{ old('name') }}">
            <span class="field-error" aria-live="polite">@error('name'){{ $message }}@enderror</span>
          </div>

          <div class="field @error('role') has-error @enderror">
            <label for="p-role">I am a <span class="req" aria-hidden="true">*</span></label>
            <select id="p-role" name="role" data-label="Partner type" required>
              <option value="">Please choose&hellip;</option>
              <option value="landowner" {{ old('role') === 'landowner' ? 'selected' : '' }}>Landowner</option>
              <option value="investor" {{ old('role') === 'investor' ? 'selected' : '' }}>Investor</option>
              <option value="both" {{ old('role') === 'both' ? 'selected' : '' }}>Both</option>
            </select>
            <span class="field-error" aria-live="polite">@error('role'){{ $message }}@enderror</span>
          </div>

          <div class="field @error('email') has-error @enderror">
            <label for="p-email">Email <span class="req" aria-hidden="true">*</span></label>
            <input type="email" id="p-email" name="email" data-label="Email" required autocomplete="email" value="{{ old('email') }}">
            <span class="field-error" aria-live="polite">@error('email'){{ $message }}@enderror</span>
          </div>

          <div class="field @error('phone') has-error @enderror">
            <label for="p-phone">Phone <span class="req" aria-hidden="true">*</span></label>
            <input type="tel" id="p-phone" name="phone" data-label="Phone" required autocomplete="tel"
                   data-bd-phone pattern="[0-9+()\-\s]{6,}" data-error-pattern="Use digits, spaces and + ( ) - only."
                   value="{{ old('phone') }}" placeholder="+880 1XXX-XXXXXX">
            <span class="field-error" aria-live="polite">@error('phone'){{ $message }}@enderror</span>
          </div>

          <div class="field @error('area') has-error @enderror">
            <label for="p-area">Area</label>
            <select id="p-area" name="area" data-label="Area">
              <option value="">Not applicable</option>
              @foreach (['banani' => 'Banani', 'gulshan' => 'Gulshan', 'dhanmondi' => 'Dhanmondi', 'tejgaon' => 'Tejgaon', 'other' => 'Elsewhere'] as $value => $label)
                <option value="{{ $value }}" {{ old('area') === $value ? 'selected' : '' }}>{{ $label }}</option>
              @endforeach
            </select>
            <span class="field-error" aria-live="polite">@error('area'){{ $message }}@enderror</span>
          </div>

          <div class="field @error('size') has-error @enderror">
            <label for="p-size">Plot size or budget</label>
            <input type="text" id="p-size" name="size" data-label="Plot size or budget"
                   placeholder="e.g. 10 katha, or 2 crore" value="{{ old('size') }}">
            <span class="field-error" aria-live="polite">@error('size'){{ $message }}@enderror</span>
          </div>

          <div class="field field-full @error('message') has-error @enderror">
            <label for="p-message">Details <span class="req" aria-hidden="true">*</span></label>
            <textarea id="p-message" name="message" data-label="Details" required minlength="20"
                      placeholder="Landowners: plot location, size and ownership status. Investors: your horizon and whether income or growth matters more.">{{ old('message') }}</textarea>
            <span class="field-error" aria-live="polite">@error('message'){{ $message }}@enderror</span>
          </div>
        </div>

        <div class="form-foot">
          <button type="submit" class="btn-solid">Submit</button>
          <p class="form-note">Submissions are reviewed in confidence. We never share plot or ownership details outside our own team.</p>
        </div>

        @if ($errors->any())
          <p class="form-status is-bad" role="status" aria-live="polite">
            {{ $errors->count() === 1 ? 'One field needs attention before this can be sent.' : $errors->count().' fields need attention before this can be sent.' }}
          </p>
        @endif
      </form>
    </div>

    <aside class="contact-aside">
      <div class="detail-block reveal-up">
        <h3>{{ $sections->heading('aside_desk') }}</h3>
        <a href="tel:{{ preg_replace('/\s+/', '', $setting->phone ?? '+8801711234567') }}">{{ $setting->phone ?? '+880 1711-234567' }}</a>
        <a href="mailto:{{ $setting->email ?? 'hello@rhlproperties.com.bd' }}">{{ $setting->email ?? 'hello@rhlproperties.com.bd' }}</a>
      </div>
      <div class="detail-block reveal-up">
        <h3>{{ $sections->heading('aside_ready') }}</h3>
        <p class="muted">{{ $page->get('aside_ready_text') }}</p>
      </div>
      <div class="detail-block reveal-up">
        <h3>{{ $sections->heading('aside_timeline') }}</h3>
        <p class="muted">{{ $page->get('aside_timeline_text') }}</p>
      </div>
      <div class="detail-block reveal-up">
        <h3>{{ $sections->heading('aside_work') }}</h3>
        <p class="muted">{{ $page->get('aside_work_text') }}</p>
        <a href="{{ route('projects.index') }}#portfolio" class="link-arrow">{{ $sections->linkLabel('aside_work') }} &rarr;</a>
      </div>
    </aside>
  </div>
</section>
@endsection

@push('scripts')
<script src="{{ \App\Support\Asset::v('assets/js/stats.js') }}"></script>
<script src="{{ \App\Support\Asset::v('assets/js/partners.js') }}"></script>
<script src="{{ \App\Support\Asset::v('assets/js/forms.js') }}"></script>
@endpush
