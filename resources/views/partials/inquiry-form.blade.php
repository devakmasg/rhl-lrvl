{{-- Reusable inquiry / enquiry form (F4.1 "standardise inquiry form fields
     site-wide"). Posts to InquiryController@store.

     Optional variables a caller may pass:
       - $project  : an App\Models\Project instance. When given, the
                     "Interested project" field is locked to that project
                     (shown read-only, submitted via hidden inputs) instead
                     of rendering the open dropdown.
       - $projects : a pre-fetched collection for the dropdown, to avoid a
                     duplicate query when the parent controller already
                     loaded one. Falls back to querying published projects
                     itself when omitted.
       - $formId   : override the form element's id if a page embeds more
                     than one instance (defaults to "inquiryForm"). --}}
@php
  $project = $project ?? null;
  $formProjects = $projects ?? \App\Models\Project::where('published', true)->orderBy('name')->get();
  $formId = $formId ?? 'inquiryForm';
@endphp
<form class="form" id="{{ $formId }}" method="POST" action="{{ route('inquiries.store') }}" novalidate>
  @csrf

  <div class="form-grid">
    <div class="field @error('name') has-error @enderror">
      <label for="{{ $formId }}-name">Full name <span class="req" aria-hidden="true">*</span></label>
      <input type="text" id="{{ $formId }}-name" name="name" data-label="Full name" required autocomplete="name" value="{{ old('name') }}">
      <span class="field-error" aria-live="polite">@error('name'){{ $message }}@enderror</span>
    </div>

    <div class="field @error('email') has-error @enderror">
      <label for="{{ $formId }}-email">Email <span class="req" aria-hidden="true">*</span></label>
      <input type="email" id="{{ $formId }}-email" name="email" data-label="Email" required autocomplete="email" value="{{ old('email') }}">
      <span class="field-error" aria-live="polite">@error('email'){{ $message }}@enderror</span>
    </div>

    <div class="field @error('phone') has-error @enderror">
      <label for="{{ $formId }}-phone">Phone <span class="req" aria-hidden="true">*</span></label>
      <input type="tel" id="{{ $formId }}-phone" name="phone" data-label="Phone" required autocomplete="tel"
             data-bd-phone pattern="[0-9+()\-\s]{6,}" data-error-pattern="Use digits, spaces and + ( ) - only."
             value="{{ old('phone') }}" placeholder="+880 1XXX-XXXXXX">
      <span class="field-error" aria-live="polite">@error('phone'){{ $message }}@enderror</span>
    </div>

    <div class="field field-full @error('project_id') has-error @enderror">
      <label for="{{ $formId }}-project">Interested project</label>
      @if ($project)
        <input type="text" value="{{ $project->name }} ({{ $project->location }})" disabled>
        <input type="hidden" name="project_id" value="{{ $project->id }}">
        <input type="hidden" name="project_name" value="{{ $project->name }}">
      @else
        <select id="{{ $formId }}-project" name="project_id" data-label="Interested project">
          <option value="" {{ old('project_id') ? '' : 'selected' }}>General enquiry — not project-specific</option>
          @foreach ($formProjects as $p)
            <option value="{{ $p->id }}" {{ (string) old('project_id') === (string) $p->id ? 'selected' : '' }}>{{ $p->name }} ({{ $p->location }})</option>
          @endforeach
        </select>
      @endif
      <span class="field-error" aria-live="polite">@error('project_id'){{ $message }}@enderror</span>
    </div>

    <div class="field field-full @error('message') has-error @enderror">
      <label for="{{ $formId }}-message">Message <span class="req" aria-hidden="true">*</span></label>
      <textarea id="{{ $formId }}-message" name="message" data-label="Message" required minlength="20"
                placeholder="A sentence or two about what you need and your timeframe.">{{ old('message') }}</textarea>
      <span class="field-error" aria-live="polite">@error('message'){{ $message }}@enderror</span>
    </div>
  </div>

  <div class="form-foot">
    <button type="submit" class="btn-solid">Send enquiry</button>
    <p class="form-note">We use your details only to answer your enquiry, and never share them with third parties.</p>
  </div>

  @if ($errors->any())
    <p class="form-status is-bad" role="status" aria-live="polite">
      {{ $errors->count() === 1 ? 'One field needs attention before this can be sent.' : $errors->count() . ' fields need attention before this can be sent.' }}
    </p>
  @endif
</form>
