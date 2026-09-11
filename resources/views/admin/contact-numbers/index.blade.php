@extends('layouts.admin')

@section('title', 'Contact Numbers')

@section('content')
<div class="page-head">
  <div>
    <h1>Contact Numbers</h1>
    <p>Every phone number the website shows. Add as many as you need &mdash; office, sales, a hotline.</p>
  </div>
  <div class="page-head-actions">
    <button class="btn btn-primary btn-sm" type="button" data-modal-open="addNumber">+ Add Number</button>
  </div>
</div>

<div class="card card-pad" style="margin-bottom:20px;">
  <h2 style="font-size:15.5px;margin-bottom:10px;">Where these appear</h2>
  <p class="hint" style="margin:0 0 8px;">
    <strong>The main number</strong> is used wherever the site has room for only one: the floating
    call button on every page, the &ldquo;Call Now&rdquo; buttons, and the contact line in page
    introductions. Exactly one number is the main one.
  </p>
  <p class="hint" style="margin:0 0 8px;">
    <strong>Show in footer</strong> puts a number in the footer&rsquo;s Contact column, with its
    title above it. Two or three fit there comfortably; more than that crowds the column.
  </p>
  <p class="hint" style="margin:0;">
    <strong>Used for</strong> lets one page reach past the main number to the right desk &mdash; the
    Sales Team, Landowners and Partners pages each ask for their own. A page whose desk has no
    number falls back to the main one, so nothing is ever blank.
  </p>
</div>

<div class="card">
  <div class="table-scroll">
    <table class="table">
      <thead>
        <tr>
          <th style="width:190px;">Title</th>
          <th style="width:180px;">Number</th>
          <th>Used for</th>
          <th style="width:90px;">Footer</th>
          <th style="width:90px;">Status</th>
          <th style="width:150px;"></th>
        </tr>
      </thead>
      <tbody>
        @forelse ($numbers as $row)
          <tr>
            <td>
              <span class="cell-main">{{ $row->label }}</span>
              @if ($row->is_primary)
                <span class="badge badge-converted" style="margin-left:6px;">Main</span>
              @endif
            </td>
            <td><span class="cell-sub">{{ $row->number }}</span></td>
            <td><span class="cell-sub">{{ \App\Models\ContactNumber::SLOTS[$row->key] ?? '—' }}</span></td>
            <td><span class="cell-sub">{{ $row->show_in_footer ? 'Yes' : 'No' }}</span></td>
            <td><span class="badge badge-{{ $row->is_active ? 'converted' : 'closed' }}">{{ $row->is_active ? 'Live' : 'Hidden' }}</span></td>
            <td class="cell-actions">
              <button class="btn btn-ghost btn-sm" type="button" data-modal-open="editNumber{{ $row->id }}">Edit</button>
              <form method="POST" action="{{ route('admin.contact-numbers.destroy', $row) }}" style="display:inline;" onsubmit="return confirm('Delete this number?');">
                @csrf
                @method('DELETE')
                <button class="btn btn-ghost btn-sm" type="submit" style="color:var(--danger);">Delete</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="6">No numbers yet. The site falls back to the number on Site Settings until one is added.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- Add --}}
<div class="modal-overlay" id="addNumber">
  <div class="modal">
    <div class="modal-head">
      <h3>Add Number</h3>
      <button class="modal-close" data-modal-close aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
    </div>
    <form method="POST" action="{{ route('admin.contact-numbers.store') }}">
      @csrf
      <div class="modal-body" style="display:flex;flex-direction:column;gap:14px;">
        <div class="field">
          <label>Title</label>
          <input type="text" name="label" required placeholder="Head Office">
          <span class="hint">What this number reaches &mdash; shown above it on the site.</span>
        </div>
        <div class="field">
          <label>Number</label>
          <input type="tel" name="number" required placeholder="+880 1711-234567">
        </div>
        <div class="field">
          <label>Used for</label>
          <select name="key">
            <option value="">No specific desk</option>
            @foreach (\App\Models\ContactNumber::SLOTS as $slot => $slotLabel)
              <option value="{{ $slot }}">{{ $slotLabel }}</option>
            @endforeach
          </select>
          <span class="hint">Optional. Each desk can hold one number.</span>
        </div>
        <div class="field">
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
            <input type="checkbox" name="show_in_footer" value="1" checked style="width:auto;margin:0;">
            Show in the footer
          </label>
        </div>
        <div class="field">
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
            <input type="checkbox" name="is_primary" value="1" style="width:auto;margin:0;">
            Use as the site&rsquo;s main number
          </label>
        </div>
        <div class="field">
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
            <input type="checkbox" name="is_active" value="1" checked style="width:auto;margin:0;">
            Show on the website
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
@foreach ($numbers as $row)
  <div class="modal-overlay" id="editNumber{{ $row->id }}">
    <div class="modal">
      <div class="modal-head">
        <h3>Edit Number</h3>
        <button class="modal-close" data-modal-close aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
      </div>
      <form method="POST" action="{{ route('admin.contact-numbers.update', $row) }}">
        @csrf
        @method('PUT')
        <div class="modal-body" style="display:flex;flex-direction:column;gap:14px;">
          <div class="field">
            <label>Title</label>
            <input type="text" name="label" value="{{ $row->label }}" required>
          </div>
          <div class="field">
            <label>Number</label>
            <input type="tel" name="number" value="{{ $row->number }}" required>
          </div>
          <div class="field">
            <label>Used for</label>
            <select name="key">
              <option value="">No specific desk</option>
              @foreach (\App\Models\ContactNumber::SLOTS as $slot => $slotLabel)
                <option value="{{ $slot }}" {{ $row->key === $slot ? 'selected' : '' }}>{{ $slotLabel }}</option>
              @endforeach
            </select>
          </div>
          <div class="field" style="max-width:160px;">
            <label>Display Order</label>
            <input type="number" name="sort_order" min="1" value="{{ $row->sort_order }}">
          </div>
          <div class="field">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
              <input type="checkbox" name="show_in_footer" value="1" {{ $row->show_in_footer ? 'checked' : '' }} style="width:auto;margin:0;">
              Show in the footer
            </label>
          </div>
          <div class="field">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
              <input type="checkbox" name="is_primary" value="1" {{ $row->is_primary ? 'checked' : '' }} style="width:auto;margin:0;">
              Use as the site&rsquo;s main number
            </label>
            <span class="hint">Ticking this unticks it on whichever number holds it now.</span>
          </div>
          <div class="field">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
              <input type="checkbox" name="is_active" value="1" {{ $row->is_active ? 'checked' : '' }} style="width:auto;margin:0;">
              Show on the website
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
