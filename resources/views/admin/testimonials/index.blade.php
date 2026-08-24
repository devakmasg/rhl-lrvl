@extends('layouts.admin')

@section('title', 'Testimonials')

@section('content')
<div class="page-head">
  <div>
    <h1>Testimonials</h1>
    <p>Client quotes rotated on the homepage and testimonials.html.</p>
  </div>
  <div class="page-head-actions">
    <button class="btn btn-primary" type="button" data-modal-open="addTestiModal">+ Add Testimonial</button>
  </div>
</div>

<div class="card">
  <div class="table-scroll">
    <table class="table">
      <thead><tr><th></th><th>Name</th><th>Role</th><th>Quote</th><th></th></tr></thead>
      <tbody>
        @forelse ($testimonials as $t)
          <tr>
            <td><img src="{{ $t->avatar ?: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=facearea&facepad=2.5&w=100&h=100&q=80' }}" alt="" style="width:36px;height:36px;border-radius:50%;object-fit:cover;"></td>
            <td><span class="cell-main">{{ $t->name }}</span></td>
            <td>{{ $t->role }}</td>
            <td><span class="cell-sub">&quot;{{ \Illuminate\Support\Str::limit($t->quote, 70) }}&quot;</span></td>
            <td class="cell-actions">
              <button class="btn btn-ghost btn-sm" type="button" data-modal-open="editTestiModal{{ $t->id }}">Edit</button>
              <form method="POST" action="{{ route('admin.testimonials.destroy', $t) }}" style="display:inline;" onsubmit="return confirm('Delete this testimonial?');">
                @csrf
                @method('DELETE')
                <button class="btn btn-ghost btn-sm" type="submit" style="color:var(--danger);">Delete</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="5">No testimonials yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="modal-overlay" id="addTestiModal">
  <div class="modal">
    <div class="modal-head"><h3>Add Testimonial</h3><button class="modal-close" data-modal-close aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button></div>
    <form method="POST" action="{{ route('admin.testimonials.store') }}">
      @csrf
      <div class="modal-body" style="display:flex;flex-direction:column;gap:14px;">
        <div class="field"><label>Name</label><input type="text" name="name" required></div>
        <div class="field"><label>Role</label><input type="text" name="role" placeholder="e.g. Homeowner, The RHL Residences" required></div>
        <div class="field"><label>Avatar URL</label><input type="url" name="avatar" placeholder="https://…"></div>
        <div class="field"><label>Quote</label><textarea name="quote" style="min-height:90px;" required></textarea></div>
      </div>
      <div class="modal-foot"><button class="btn btn-outline" type="button" data-modal-close>Cancel</button><button class="btn btn-primary" type="submit">Save Testimonial</button></div>
    </form>
  </div>
</div>

@foreach ($testimonials as $t)
<div class="modal-overlay" id="editTestiModal{{ $t->id }}">
  <div class="modal">
    <div class="modal-head"><h3>Edit Testimonial</h3><button class="modal-close" data-modal-close aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button></div>
    <form method="POST" action="{{ route('admin.testimonials.update', $t) }}">
      @csrf
      @method('PUT')
      <div class="modal-body" style="display:flex;flex-direction:column;gap:14px;">
        <div class="field"><label>Name</label><input type="text" name="name" value="{{ $t->name }}" required></div>
        <div class="field"><label>Role</label><input type="text" name="role" value="{{ $t->role }}" required></div>
        <div class="field"><label>Avatar URL</label><input type="url" name="avatar" value="{{ $t->avatar }}" placeholder="https://…"></div>
        <div class="field"><label>Quote</label><textarea name="quote" style="min-height:90px;" required>{{ $t->quote }}</textarea></div>
      </div>
      <div class="modal-foot"><button class="btn btn-outline" type="button" data-modal-close>Cancel</button><button class="btn btn-primary" type="submit">Save Testimonial</button></div>
    </form>
  </div>
</div>
@endforeach
@endsection
