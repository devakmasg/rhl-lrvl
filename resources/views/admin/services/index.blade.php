@extends('layouts.admin')

@section('title', 'Services')

@section('content')
<div class="page-head">
  <div>
    <h1>Services</h1>
    <p>The five service lines shown on the homepage and services page.</p>
  </div>
  <div class="page-head-actions">
    <button class="btn btn-primary" type="button" data-modal-open="addServiceModal">+ Add Service</button>
  </div>
</div>

<div class="card">
  <div class="table-scroll">
    <table class="table">
      <thead><tr><th style="width:60px;">Order</th><th>Title</th><th>Description</th><th></th></tr></thead>
      <tbody>
        @forelse ($services as $s)
          <tr>
            <td>{{ $s->order ?? '—' }}</td>
            <td><span class="cell-main">{{ $s->title }}</span></td>
            <td><span class="cell-sub">{{ $s->description }}</span></td>
            <td class="cell-actions">
              <button class="btn btn-ghost btn-sm" type="button" data-modal-open="editServiceModal{{ $s->id }}">Edit</button>
              <form method="POST" action="{{ route('admin.services.destroy', $s) }}" style="display:inline;" onsubmit="return confirm('Delete this service?');">
                @csrf
                @method('DELETE')
                <button class="btn btn-ghost btn-sm" type="submit" style="color:var(--danger);">Delete</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="4">No services yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="modal-overlay" id="addServiceModal">
  <div class="modal">
    <div class="modal-head">
      <h3>Add Service</h3>
      <button class="modal-close" data-modal-close aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
    </div>
    <form method="POST" action="{{ route('admin.services.store') }}">
      @csrf
      <div class="modal-body" style="display:flex;flex-direction:column;gap:14px;">
        <div class="field"><label>Title</label><input type="text" name="title" required></div>
        <div class="field"><label>Display Order</label><input type="number" name="order" min="1"></div>
        <div class="field"><label>Description</label><textarea name="description" style="min-height:80px;" required></textarea></div>
      </div>
      <div class="modal-foot">
        <button class="btn btn-outline" type="button" data-modal-close>Cancel</button>
        <button class="btn btn-primary" type="submit">Save Service</button>
      </div>
    </form>
  </div>
</div>

@foreach ($services as $s)
<div class="modal-overlay" id="editServiceModal{{ $s->id }}">
  <div class="modal">
    <div class="modal-head">
      <h3>Edit Service</h3>
      <button class="modal-close" data-modal-close aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
    </div>
    <form method="POST" action="{{ route('admin.services.update', $s) }}">
      @csrf
      @method('PUT')
      <div class="modal-body" style="display:flex;flex-direction:column;gap:14px;">
        <div class="field"><label>Title</label><input type="text" name="title" value="{{ $s->title }}" required></div>
        <div class="field"><label>Display Order</label><input type="number" name="order" min="1" value="{{ $s->order }}"></div>
        <div class="field"><label>Description</label><textarea name="description" style="min-height:80px;" required>{{ $s->description }}</textarea></div>
      </div>
      <div class="modal-foot">
        <button class="btn btn-outline" type="button" data-modal-close>Cancel</button>
        <button class="btn btn-primary" type="submit">Save Service</button>
      </div>
    </form>
  </div>
</div>
@endforeach
@endsection
