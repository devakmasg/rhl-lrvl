@extends('layouts.admin')

@section('title', 'Trusted Partners')

@section('content')
<div class="page-head">
  <div>
    <h1>Trusted Partners</h1>
    <p>The logo strip that runs above the footer on every page.</p>
  </div>
  <div class="page-head-actions">
    @include('admin.partials.view-page', ['route' => 'home'])
    <button class="btn btn-primary btn-sm" type="button" data-modal-open="addPartner">+ Add Partner</button>
  </div>
</div>

<div class="card card-pad" style="margin-bottom:20px;">
  <h2 style="font-size:15.5px;margin-bottom:4px;">Section Heading</h2>
  <div class="card-head-sub" style="margin-bottom:16px;">The label and headline shown above the logos.</div>
  <form method="POST" action="{{ route('admin.partners.strip.update') }}">
    @csrf
    @method('PUT')
    <div class="field-row">
      <div class="field">
        <label for="pxEyebrow">Eyebrow</label>
        <input type="text" id="pxEyebrow" name="partners_eyebrow" value="{{ old('partners_eyebrow', $setting->partners_eyebrow) }}" placeholder="Trusted Partners">
      </div>
      <div class="field">
        <label for="pxHeading">Heading</label>
        <input type="text" id="pxHeading" name="partners_heading" value="{{ old('partners_heading', $setting->partners_heading) }}" placeholder="The names we build alongside">
        <span class="hint">Leave blank to show the eyebrow on its own.</span>
      </div>
    </div>
    <div class="field" style="margin-top:16px;">
      <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
        <input type="checkbox" name="show_partners" value="1" {{ old('show_partners', $setting->show_partners) ? 'checked' : '' }} style="width:auto;margin:0;">
        Show the partner strip on the site
      </label>
      <span class="hint">Unticking hides the whole section without deleting any logos.</span>
    </div>
    <div style="margin-top:18px;">
      <button class="btn btn-primary" type="submit">Save Section</button>
    </div>
  </form>
</div>

<div class="card">
  <div class="card-pad" style="padding-bottom:0;">
    <h2 style="font-size:15.5px;">Logos</h2>
    <div class="card-head-sub">Shown left to right in the order below, then looped. A partner with no logo is skipped.</div>
  </div>
  <div class="table-scroll" style="margin-top:14px;">
    <table class="table">
      <thead>
        <tr>
          <th style="width:60px;">#</th>
          <th style="width:150px;">Logo</th>
          <th style="width:220px;">Name</th>
          <th>Website</th>
          <th style="width:80px;">Status</th>
          <th style="width:150px;"></th>
        </tr>
      </thead>
      <tbody>
        @forelse ($partners as $partner)
          <tr>
            <td>{{ $partner->sort_order }}</td>
            <td>
              @if ($partner->logo_url)
                <img src="{{ $partner->logo_url }}" alt="" style="height:30px;width:auto;max-width:120px;object-fit:contain;">
              @else
                <span class="cell-sub">No logo</span>
              @endif
            </td>
            <td><span class="cell-main">{{ $partner->name }}</span></td>
            <td><span class="cell-sub">{{ $partner->website ?: '—' }}</span></td>
            <td><span class="badge badge-{{ $partner->is_active ? 'converted' : 'closed' }}">{{ $partner->is_active ? 'Live' : 'Hidden' }}</span></td>
            <td class="cell-actions">
              <button class="btn btn-ghost btn-sm" type="button" data-modal-open="editPartner{{ $partner->id }}">Edit</button>
              <form method="POST" action="{{ route('admin.partners.destroy', $partner) }}" style="display:inline;" onsubmit="return confirm('Delete this partner?');">
                @csrf
                @method('DELETE')
                <button class="btn btn-ghost btn-sm" type="submit" style="color:var(--danger);">Delete</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="6">No partners yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- Add --}}
<div class="modal-overlay" id="addPartner">
  <div class="modal">
    <div class="modal-head">
      <h3>Add Partner</h3>
      <button class="modal-close" data-modal-close aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
    </div>
    <form method="POST" action="{{ route('admin.partners.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="modal-body" style="display:flex;flex-direction:column;gap:14px;">
        <div class="field"><label>Partner Name</label><input type="text" name="name" required></div>
        @include('admin.partials.image-field', [
          'name' => 'logo',
          'label' => 'Logo',
          'hint' => 'PNG or WebP with a transparent background reads best — up to 2 MB. Logos show in grey and lift to full colour on hover.',
        ])
        <div class="field">
          <label>Website</label>
          <input type="url" name="website" placeholder="https://example.com">
          <span class="hint">Optional. With a link, the logo opens it in a new tab.</span>
        </div>
        <div class="field">
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
            <input type="checkbox" name="is_active" value="1" checked style="width:auto;margin:0;">
            Show in the strip
          </label>
        </div>
      </div>
      <div class="modal-foot">
        <button class="btn btn-outline" type="button" data-modal-close>Cancel</button>
        <button class="btn btn-primary" type="submit">Save</button>
      </div>
    </form>
  </div>
</div>

{{-- Edit --}}
@foreach ($partners as $partner)
  <div class="modal-overlay" id="editPartner{{ $partner->id }}">
    <div class="modal">
      <div class="modal-head">
        <h3>Edit Partner</h3>
        <button class="modal-close" data-modal-close aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
      </div>
      <form method="POST" action="{{ route('admin.partners.update', $partner) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-body" style="display:flex;flex-direction:column;gap:14px;">
          <div class="field"><label>Partner Name</label><input type="text" name="name" value="{{ $partner->name }}" required></div>
          @include('admin.partials.image-field', [
            'name' => 'logo',
            'label' => 'Logo',
            'currentUrl' => $partner->logo_url,
          ])
          <div class="field"><label>Website</label><input type="url" name="website" value="{{ $partner->website }}" placeholder="https://example.com"></div>
          <div class="field" style="max-width:160px;"><label>Display Order</label><input type="number" name="sort_order" min="1" value="{{ $partner->sort_order }}"></div>
          <div class="field">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
              <input type="checkbox" name="is_active" value="1" {{ $partner->is_active ? 'checked' : '' }} style="width:auto;margin:0;">
              Show in the strip
            </label>
          </div>
        </div>
        <div class="modal-foot">
          <button class="btn btn-outline" type="button" data-modal-close>Cancel</button>
          <button class="btn btn-primary" type="submit">Save</button>
        </div>
      </form>
    </div>
  </div>
@endforeach
@endsection
