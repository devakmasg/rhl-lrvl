{{-- Upload field for a column that may still hold a seeded URL.
     Expects: $name, $label, and optionally $currentUrl (resolved via the
     model's *_url accessor), $hint, and $accept (defaults to any image —
     override for a field whose validation allows more, e.g. favicon's .ico). --}}
@php($currentUrl = $currentUrl ?? null)
@php($accept = $accept ?? 'image/*')
<div class="field">
  <label>{{ $label }}</label>
  @if ($currentUrl)
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
      <img src="{{ $currentUrl }}" alt="" style="width:44px;height:44px;border-radius:8px;object-fit:cover;flex:none;border:1px solid var(--line);">
      <label style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--stone);cursor:pointer;">
        <input type="checkbox" name="{{ $name }}_remove" value="1" style="width:auto;margin:0;"> Remove
      </label>
    </div>
  @endif
  <input type="file" name="{{ $name }}" accept="{{ $accept }}">
  <span style="display:block;font-size:11.5px;color:var(--stone);margin-top:5px;">
    {{ $hint ?? ($currentUrl ? 'Upload a new file to replace the current image.' : 'JPG, PNG or WebP — up to 5 MB.') }}
  </span>
  @error($name) <span class="field-error">{{ $message }}</span> @enderror
</div>
