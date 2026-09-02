@extends('layouts.admin')

@section('title', 'Landowners Page')

@push('head')
<style>
  .repeater-row{display:flex;gap:10px;align-items:flex-start;margin-bottom:10px;}
  .repeater-row .field{flex:1;margin-bottom:0;}
  .repeater-remove{flex:none;width:34px;height:34px;border-radius:8px;border:1px solid var(--line);background:var(--surface);color:var(--danger);display:flex;align-items:center;justify-content:center;margin-top:4px;}
  .repeater-remove:hover{background:var(--danger-bg);}
  .repeater-remove svg{width:14px;height:14px;}
</style>
@endpush

@section('content')
<form method="POST" action="{{ route('admin.content.landowners.update') }}" enctype="multipart/form-data">
  @csrf
  @method('PUT')

  <div class="page-head">
    <div>
      <h1>Landowners Page</h1>
      <p>The page a landowner reads before submitting their plot. Its header photo and SEO live in <a href="{{ route('admin.page-banners.index') }}">Page Headers</a>; the closing cards in Page CTAs.</p>
    </div>
    <div class="page-head-actions">
      @include('admin.partials.view-page', ['route' => 'landowners'])
      <button class="btn btn-primary" type="submit">Save Changes</button>
    </div>
  </div>

  <div class="card card-pad" style="margin-bottom:20px;">
    <h2 style="font-size:15.5px;margin-bottom:4px;">Opening Section</h2>
    <div class="card-head-sub" style="margin-bottom:16px;">The first block below the page banner &mdash; the case for developing with you at all.</div>
    <div class="field-row" style="margin-bottom:16px;">
      <div class="field">
        <label for="introEyebrow">Eyebrow</label>
        <input type="text" id="introEyebrow" name="intro_eyebrow" value="{{ old('intro_eyebrow', $content['intro_eyebrow'] ?? '') }}">
      </div>
      <div class="field">
        <label for="introHeading">Heading</label>
        <input type="text" id="introHeading" name="intro_heading" value="{{ old('intro_heading', $content['intro_heading'] ?? '') }}">
      </div>
    </div>
    <div class="field" style="margin-bottom:16px;">
      <label for="introText1">First Paragraph</label>
      <textarea id="introText1" name="intro_text_1" style="min-height:80px;">{{ old('intro_text_1', $content['intro_text_1'] ?? '') }}</textarea>
    </div>
    <div class="field" style="margin-bottom:16px;">
      <label for="introText2">Second Paragraph</label>
      <textarea id="introText2" name="intro_text_2" style="min-height:80px;">{{ old('intro_text_2', $content['intro_text_2'] ?? '') }}</textarea>
    </div>
    <div style="max-width:420px;">
      @include('admin.partials.image-field', [
        'name' => 'intro_image',
        'label' => 'Section Photo',
        'currentUrl' => $page->imageUrl('intro_image'),
      ])
    </div>
  </div>

  <div class="card card-pad" style="margin-bottom:20px;">
    <h2 style="font-size:15.5px;margin-bottom:4px;">Explainer Video (optional)</h2>
    <div class="card-head-sub" style="margin-bottom:16px;">Leave the link empty and no video block appears on the page.</div>
    <div class="field-row">
      <div class="field">
        <label for="videoUrl">YouTube or Vimeo Link</label>
        <input type="text" id="videoUrl" name="video_url" value="{{ old('video_url', $content['video_url'] ?? '') }}" placeholder="https://www.youtube.com/watch?v=...">
        <span class="hint">Paste the ordinary link from the browser bar &mdash; it is converted to an embed automatically.</span>
        <span class="field-error">{{ $errors->first('video_url') }}</span>
      </div>
      <div class="field">
        <label for="videoCaption">Caption</label>
        <input type="text" id="videoCaption" name="video_caption" value="{{ old('video_caption', $content['video_caption'] ?? '') }}">
        <span class="hint">The line printed under the video.</span>
      </div>
    </div>
  </div>

  <div class="card card-pad" style="margin-bottom:20px;">
    <h2 style="font-size:15.5px;margin-bottom:4px;">&ldquo;How We Are Different&rdquo; Section</h2>
    <div class="card-head-sub" style="margin-bottom:16px;">The second block, with its photo on the opposite side.</div>
    <div class="field-row" style="margin-bottom:16px;">
      <div class="field">
        <label for="diffEyebrow">Eyebrow</label>
        <input type="text" id="diffEyebrow" name="diff_eyebrow" value="{{ old('diff_eyebrow', $content['diff_eyebrow'] ?? '') }}">
      </div>
      <div class="field">
        <label for="diffHeading">Heading</label>
        <input type="text" id="diffHeading" name="diff_heading" value="{{ old('diff_heading', $content['diff_heading'] ?? '') }}">
      </div>
    </div>
    <div class="field" style="margin-bottom:16px;">
      <label for="diffText1">First Paragraph</label>
      <textarea id="diffText1" name="diff_text_1" style="min-height:80px;">{{ old('diff_text_1', $content['diff_text_1'] ?? '') }}</textarea>
    </div>
    <div class="field" style="margin-bottom:16px;">
      <label for="diffText2">Second Paragraph</label>
      <textarea id="diffText2" name="diff_text_2" style="min-height:80px;">{{ old('diff_text_2', $content['diff_text_2'] ?? '') }}</textarea>
    </div>
    <div style="max-width:420px;">
      @include('admin.partials.image-field', [
        'name' => 'diff_image',
        'label' => 'Section Photo',
        'currentUrl' => $page->imageUrl('diff_image'),
      ])
    </div>
  </div>

  <div class="card card-pad" style="margin-bottom:20px;">
    <h2 style="font-size:15.5px;margin-bottom:4px;">Terms &amp; Process Headings</h2>
    <div class="card-head-sub" style="margin-bottom:16px;">
      The four terms and the process steps themselves are shared with the &ldquo;For Landowners&rdquo; tab on the
      <a href="{{ route('admin.content.partners') }}">Partners page</a> &mdash; edit them there and both pages update.
      Only the headings above them belong to this page.
    </div>
    <div class="field-row" style="margin-bottom:16px;">
      <div class="field">
        <label for="pillarsEyebrow">Terms Eyebrow</label>
        <input type="text" id="pillarsEyebrow" name="pillars_eyebrow" value="{{ old('pillars_eyebrow', $content['pillars_eyebrow'] ?? '') }}">
      </div>
      <div class="field">
        <label for="pillarsHeading">Terms Heading</label>
        <input type="text" id="pillarsHeading" name="pillars_heading" value="{{ old('pillars_heading', $content['pillars_heading'] ?? '') }}">
      </div>
    </div>
    <div class="field-row">
      <div class="field">
        <label for="processEyebrow">Process Eyebrow</label>
        <input type="text" id="processEyebrow" name="process_eyebrow" value="{{ old('process_eyebrow', $content['process_eyebrow'] ?? '') }}">
      </div>
      <div class="field">
        <label for="processHeading">Process Heading</label>
        <input type="text" id="processHeading" name="process_heading" value="{{ old('process_heading', $content['process_heading'] ?? '') }}">
      </div>
    </div>
  </div>

  <div class="card card-pad" style="margin-bottom:20px;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
      <h2 style="font-size:15.5px;">Landowner Quotes</h2>
      <button type="button" class="btn btn-outline btn-sm" id="addQuote">+ Add Quote</button>
    </div>
    <div class="field-row" style="margin-bottom:16px;">
      <div class="field">
        <label for="quotesEyebrow">Eyebrow</label>
        <input type="text" id="quotesEyebrow" name="quotes_eyebrow" value="{{ old('quotes_eyebrow', $content['quotes_eyebrow'] ?? '') }}">
      </div>
      <div class="field">
        <label for="quotesHeading">Heading</label>
        <input type="text" id="quotesHeading" name="quotes_heading" value="{{ old('quotes_heading', $content['quotes_heading'] ?? '') }}">
      </div>
    </div>
    <p class="hint" style="margin-bottom:14px;">These are landowners specifically, so they are kept here rather than in the site-wide Testimonials list. Clearing a quote removes its card.</p>
    <div id="quotesList"></div>
  </div>

  <div class="card card-pad" style="margin-bottom:20px;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
      <h2 style="font-size:15.5px;">Frequently Asked Questions</h2>
      <button type="button" class="btn btn-outline btn-sm" id="addFaq">+ Add Question</button>
    </div>
    <div class="field-row" style="margin-bottom:16px;">
      <div class="field">
        <label for="faqEyebrow">Eyebrow</label>
        <input type="text" id="faqEyebrow" name="faq_eyebrow" value="{{ old('faq_eyebrow', $content['faq_eyebrow'] ?? '') }}">
      </div>
      <div class="field">
        <label for="faqHeading">Heading</label>
        <input type="text" id="faqHeading" name="faq_heading" value="{{ old('faq_heading', $content['faq_heading'] ?? '') }}">
      </div>
    </div>
    <div id="faqList"></div>
  </div>

  <div class="card card-pad">
    <h2 style="font-size:15.5px;margin-bottom:4px;">Submission Form</h2>
    <div class="card-head-sub" style="margin-bottom:16px;">The wording around the form. Submissions arrive in <a href="{{ route('admin.inquiries.index') }}">Inquiries</a> marked as landowner enquiries.</div>
    <div class="field-row" style="margin-bottom:16px;">
      <div class="field">
        <label for="contactEyebrow">Eyebrow</label>
        <input type="text" id="contactEyebrow" name="contact_eyebrow" value="{{ old('contact_eyebrow', $content['contact_eyebrow'] ?? '') }}">
        <span class="hint">Also used on the button at the top of the page.</span>
      </div>
      <div class="field">
        <label for="contactHeading">Heading</label>
        <input type="text" id="contactHeading" name="contact_heading" value="{{ old('contact_heading', $content['contact_heading'] ?? '') }}">
      </div>
    </div>
    <div class="field" style="margin-bottom:16px;">
      <label for="contactText">Intro Text</label>
      <textarea id="contactText" name="contact_text" style="min-height:70px;">{{ old('contact_text', $content['contact_text'] ?? '') }}</textarea>
    </div>
    <div class="field" style="margin-bottom:16px;">
      <label for="formNote">Note Beside the Submit Button</label>
      <input type="text" id="formNote" name="form_note" value="{{ old('form_note', $content['form_note'] ?? '') }}" maxlength="160">
      <span class="hint">Keep this to one short sentence &mdash; it sits alongside the button, and a long note pushes the button out of line. The fuller confidentiality wording belongs in the sidebar field below.</span>
      <span class="field-error">{{ $errors->first('form_note') }}</span>
    </div>
    <div class="field-row" style="margin-bottom:16px;">
      <div class="field">
        <label for="asideHeading">Sidebar Heading</label>
        <input type="text" id="asideHeading" name="aside_heading" value="{{ old('aside_heading', $content['aside_heading'] ?? '') }}">
        <label for="asideText" style="margin-top:12px;">Sidebar Text</label>
        <textarea id="asideText" name="aside_text" style="min-height:70px;">{{ old('aside_text', $content['aside_text'] ?? '') }}</textarea>
        <span class="hint">The phone number and email beneath it come from Site Settings.</span>
      </div>
      <div class="field">
        <label for="asideConfidenceHeading">Confidentiality Heading</label>
        <input type="text" id="asideConfidenceHeading" name="aside_confidence_heading" value="{{ old('aside_confidence_heading', $content['aside_confidence_heading'] ?? '') }}">
        <label for="asideConfidenceText" style="margin-top:12px;">Confidentiality Text</label>
        <textarea id="asideConfidenceText" name="aside_confidence_text" style="min-height:70px;">{{ old('aside_confidence_text', $content['aside_confidence_text'] ?? '') }}</textarea>
        <span class="hint">Shown in the sidebar beside the form.</span>
      </div>
    </div>
  </div>
</form>
@endsection

@push('scripts')
{{-- Seed rows are built here rather than inline in @json(...): a long ternary
     with array subscripts inside it trips Blade's argument parser and silently
     truncates the compiled call. --}}
@php
  $quoteRows = old('quote_text')
    ? collect(old('quote_text'))->map(function ($text, $i) {
        return [
          'quote' => $text,
          'name' => old('quote_name')[$i] ?? '',
          'project' => old('quote_project')[$i] ?? '',
        ];
      })->values()->all()
    : ($content['quotes'] ?? []);

  $faqRows = old('faq_q')
    ? collect(old('faq_q'))->map(function ($question, $i) {
        return ['q' => $question, 'a' => old('faq_a')[$i] ?? ''];
      })->values()->all()
    : ($content['faqs'] ?? []);
@endphp
<script>
  function makeRepeater(listEl, addBtn, buildRow, seed){
    function addRow(values){
      const row = document.createElement('div');
      row.className = 'repeater-row';
      row.innerHTML = buildRow(values || {});
      const remove = document.createElement('button');
      remove.type = 'button'; remove.className = 'repeater-remove'; remove.setAttribute('aria-label', 'Remove');
      remove.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';
      remove.addEventListener('click', () => row.remove());
      row.appendChild(remove);
      listEl.appendChild(row);
    }
    addBtn.addEventListener('click', () => addRow());
    (seed || []).forEach(addRow);
  }

  function esc(v){
    return String(v ?? '').replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
  }

  makeRepeater(document.getElementById('quotesList'), document.getElementById('addQuote'),
    (v) => `<div class="field"><textarea name="quote_text[]" placeholder="What the landowner said" style="min-height:70px;">${esc(v.quote)}</textarea></div>
            <div class="field" style="max-width:220px;">
              <input type="text" name="quote_name[]" placeholder="Name" value="${esc(v.name)}" style="margin-bottom:8px;">
              <input type="text" name="quote_project[]" placeholder="Project — plot size, area" value="${esc(v.project)}">
            </div>`,
    @json($quoteRows));

  makeRepeater(document.getElementById('faqList'), document.getElementById('addFaq'),
    (v) => `<div class="field" style="max-width:300px;"><input type="text" name="faq_q[]" placeholder="Question" value="${esc(v.q)}"></div>
            <div class="field"><textarea name="faq_a[]" placeholder="Answer" style="min-height:70px;">${esc(v.a)}</textarea></div>`,
    @json($faqRows));
</script>
@endpush
