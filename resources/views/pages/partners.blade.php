@extends('layouts.app')

@section('canonical', route('partners'))

@section('content')
@include('partials.page-header')

<section class="partner-intro">
  <div class="wrap partner-intro-grid">
    <div>
      <span class="intro-tag reveal-up">Why RHL Properties</span>
      <h2 class="reveal-up">Twenty-five years of partnerships that finished on time.</h2>
      <p class="reveal-up">Since 1998 we have completed fifty-two developments without a single project abandoned mid-construction. Landowners keep their share protected by registered agreement from day one, and investors see the same quarterly reporting our own board reads.</p>
      <p class="reveal-up">Every partnership begins the same way — a site visit, an honest feasibility study, and written terms before anything is signed.</p>
    </div>
    <div class="partner-figure reveal-up">
      <img src="{{ asset('assets/images/hero-2-commercial.jpg') }}" alt="A completed RHL Properties commercial development" loading="lazy" decoding="async">
    </div>
  </div>
</section>

<section class="audience" id="partner-options">
  <div class="wrap">
    <div class="audience-head">
      <span class="intro-tag reveal-up">How it works</span>
      <h2 class="reveal-up">Choose the path that fits you.</h2>
    </div>

    <div class="audience-switch seg" id="audienceSwitch" role="tablist" aria-label="Choose partnership type">
      <button type="button" role="tab" id="tab-landowners" aria-controls="panel-landowners" aria-selected="true" tabindex="0">For Landowners</button>
      <button type="button" role="tab" id="tab-investors" aria-controls="panel-investors" aria-selected="false" tabindex="-1">For Investors</button>
    </div>

    <div class="audience-panel" id="panel-landowners" role="tabpanel" aria-labelledby="tab-landowners" tabindex="0">
      <p class="audience-lead">You own the land. We bring design, approvals, financing and construction — and you receive an agreed share of the finished development, secured in writing before work begins.</p>

      <div class="pillars">
        <div class="pillar">
          <span class="pillar-idx">01</span>
          <h3>A fair, written share</h3>
          <p>Your share of the built area is fixed by registered joint-venture deed at the outset — never renegotiated once construction starts.</p>
        </div>
        <div class="pillar">
          <span class="pillar-idx">02</span>
          <h3>Signing money up front</h3>
          <p>A non-refundable advance is paid on signing, with the balance scheduled against verified construction milestones.</p>
        </div>
        <div class="pillar">
          <span class="pillar-idx">03</span>
          <h3>We carry the cost</h3>
          <p>Approvals, design, materials and labour are financed entirely by RHL Properties. You are not asked to fund construction.</p>
        </div>
        <div class="pillar">
          <span class="pillar-idx">04</span>
          <h3>Handover on a date</h3>
          <p>A completion date is written into the agreement, with an agreed penalty payable to you if we miss it.</p>
        </div>
      </div>

      <div class="process">
        <div class="step">
          <span class="step-num">Step 01</span>
          <div><h3>Submit your land</h3><p>Send us the location, plot size and ownership documents using the form below. A first response takes two to three working days.</p></div>
        </div>
        <div class="step">
          <span class="step-num">Step 02</span>
          <div><h3>Site visit and title check</h3><p>Our team visits the plot and our legal counsel verifies title, mutation and any encumbrance. There is no cost to you at this stage.</p></div>
        </div>
        <div class="step">
          <span class="step-num">Step 03</span>
          <div><h3>Feasibility and offer</h3><p>We model what the site can support under current planning rules and return a written offer setting out your share, the advance and the timeline.</p></div>
        </div>
        <div class="step">
          <span class="step-num">Step 04</span>
          <div><h3>Agreement and advance</h3><p>Terms are registered as a joint-venture deed. The signing advance is paid and the power of attorney is limited strictly to obtaining approvals.</p></div>
        </div>
        <div class="step">
          <span class="step-num">Step 05</span>
          <div><h3>Construction and handover</h3><p>You receive quarterly progress reports and open site access throughout. On completion, your share is handed over with individual documentation.</p></div>
        </div>
      </div>
    </div>

    <div class="audience-panel" id="panel-investors" role="tabpanel" aria-labelledby="tab-investors" tabindex="0" hidden>
      <p class="audience-lead">Invest alongside a developer that publishes its numbers. Positions are available in individual developments or across a portfolio, from pre-launch through to completed, income-producing assets.</p>

      <div class="pillars">
        <div class="pillar">
          <span class="pillar-idx">01</span>
          <h3>Enter at any stage</h3>
          <p>Pre-launch pricing on projects still in approval, or completed assets already tenanted and producing rent from day one.</p>
        </div>
        <div class="pillar">
          <span class="pillar-idx">02</span>
          <h3>Reporting you can audit</h3>
          <p>Quarterly statements covering construction progress, cost against budget, sales velocity and occupancy — the same pack our board reads.</p>
        </div>
        <div class="pillar">
          <span class="pillar-idx">03</span>
          <h3>Our capital sits alongside</h3>
          <p>RHL Properties retains a stake in every development it syndicates, so our exposure moves in the same direction as yours.</p>
        </div>
        <div class="pillar">
          <span class="pillar-idx">04</span>
          <h3>A defined exit</h3>
          <p>Resale, buy-back and hold-for-income routes are set out in the subscription documents before you commit, not after.</p>
        </div>
      </div>

      <div class="process">
        <div class="step">
          <span class="step-num">Step 01</span>
          <div><h3>Introductory call</h3><p>A short conversation about your horizon, target return and whether income or capital growth matters more to you.</p></div>
        </div>
        <div class="step">
          <span class="step-num">Step 02</span>
          <div><h3>Opportunity pack</h3><p>You receive the current schedule of developments with costs, projected returns, timelines and the risks attached to each.</p></div>
        </div>
        <div class="step">
          <span class="step-num">Step 03</span>
          <div><h3>Site and books</h3><p>Visit the sites and review the audited accounts and the delivery record on completed projects before committing anything.</p></div>
        </div>
        <div class="step">
          <span class="step-num">Step 04</span>
          <div><h3>Subscription</h3><p>Terms, payment schedule and exit routes are documented and signed. Funds are drawn against construction milestones, not in advance.</p></div>
        </div>
        <div class="step">
          <span class="step-num">Step 05</span>
          <div><h3>Reporting and exit</h3><p>Quarterly reporting through the build, then distribution, resale or transfer to income according to the route you chose.</p></div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="stats">
  <div class="stats-bg" id="statsBg" data-parallax-bg="0.25"></div>
  <div class="stats-inner wrap">
    <span class="intro-tag reveal-up" style="color:var(--gold-light)">Track Record</span>
    <h2 class="reveal-up" style="max-width:640px;margin-bottom:50px;">The numbers behind the partnership.</h2>
    <div class="stats-grid">
      <div class="stat reveal-card"><div class="num" data-target="52" data-suffix="+">0</div><div class="label">Developments Completed</div></div>
      <div class="stat reveal-card"><div class="num" data-target="6.4" data-decimals="1" data-suffix="M+">0</div><div class="label">Sq. Ft. Delivered</div></div>
      <div class="stat reveal-card"><div class="num" data-target="140" data-suffix="+">0</div><div class="label">Landowner Partnerships</div></div>
      <div class="stat reveal-card"><div class="num" data-target="25" data-suffix="">0</div><div class="label">Years of Excellence</div></div>
      <div class="stat reveal-card"><div class="num" data-target="0" data-suffix="">0</div><div class="label">Projects Abandoned</div></div>
      <div class="stat reveal-card"><div class="num" data-target="96" data-suffix="%">0</div><div class="label">Delivered On Schedule</div></div>
    </div>
  </div>
</section>

<section class="contact" id="submit">
  <div class="wrap contact-grid">
    <div>
      <div class="contact-form-head">
        <span class="intro-tag reveal-up">Start a conversation</span>
        <h2 class="reveal-up">Submit your land or your interest.</h2>
        <p class="reveal-up">Tell us which side of the partnership you're on and we'll send the relevant pack. Nothing is committed at this stage.</p>
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
        <h3>Partnership desk</h3>
        <a href="tel:+8801711234567">+880 1711-234567</a>
        <a href="mailto:hello@rhlproperties.com.bd">hello@rhlproperties.com.bd</a>
      </div>
      <div class="detail-block reveal-up">
        <h3>What to have ready</h3>
        <p class="muted">Landowners: title deed, mutation certificate, latest rent receipt and a recent survey plan. Investors: nothing — the first conversation needs no paperwork.</p>
      </div>
      <div class="detail-block reveal-up">
        <h3>Typical timeline</h3>
        <p class="muted">First response in 2–3 working days. Site visit and title check within two weeks. Written offer within a month of the visit.</p>
      </div>
      <div class="detail-block reveal-up">
        <h3>See the work first</h3>
        <p class="muted">Every completed and ongoing development is listed with its status and location.</p>
        <a href="{{ route('projects.index') }}#portfolio" class="link-arrow">Browse the portfolio &rarr;</a>
      </div>
    </aside>
  </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/stats.js') }}"></script>
<script src="{{ asset('assets/js/partners.js') }}"></script>
<script src="{{ asset('assets/js/forms.js') }}"></script>
@endpush
