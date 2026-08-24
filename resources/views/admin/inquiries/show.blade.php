@extends('layouts.admin')

@section('title', $inquiry->name)

@push('head')
<style>
  .detail-grid{display:grid;grid-template-columns:1fr 320px;gap:20px;align-items:start;}
  @media (max-width:1080px){.detail-grid{grid-template-columns:1fr;}}
  .info-list{display:flex;flex-direction:column;gap:14px;}
  .info-row{display:flex;gap:14px;}
  .info-row svg{width:17px;height:17px;color:var(--gold);flex:none;margin-top:2px;}
  .info-row .k{font-size:11.5px;color:var(--stone);text-transform:uppercase;letter-spacing:.04em;}
  .info-row .v{font-size:13.5px;color:var(--charcoal);font-weight:500;}
  .message-box{background:var(--surface-muted);border-radius:10px;padding:16px;font-size:13.5px;line-height:1.6;color:var(--charcoal-soft);}
  .notes-thread{display:flex;flex-direction:column;gap:14px;margin-bottom:16px;}
  .note{background:var(--surface-muted);border-radius:10px;padding:12px 14px;}
  .note-head{display:flex;justify-content:space-between;font-size:12px;color:var(--stone);margin-bottom:4px;}
  .note-head strong{color:var(--charcoal);}
  .note p{font-size:13.5px;color:var(--charcoal-soft);}
</style>
@endpush

@section('content')
<div class="page-head">
  <div>
    <h1>{{ $inquiry->name }}</h1>
    <p>{{ $inquiry->reference }} &mdash; submitted {{ $inquiry->created_at->format('d M Y') }}</p>
  </div>
  <div class="page-head-actions">
    <a href="{{ route('admin.inquiries.index') }}" class="btn btn-outline">&larr; Back to Inquiries</a>
  </div>
</div>

<div class="detail-grid">
  <div class="form-stack" style="display:flex;flex-direction:column;gap:20px;">
    <div class="card card-pad">
      <h2 style="font-size:15.5px;margin-bottom:16px;">Customer Message</h2>
      <div class="message-box">{{ $inquiry->message }}</div>
    </div>

    <div class="card card-pad">
      <h2 style="font-size:15.5px;margin-bottom:16px;">Internal Notes</h2>
      <div class="notes-thread">
        @forelse ($inquiry->notes as $note)
          <div class="note">
            <div class="note-head"><strong>{{ $note->author }}</strong><span>{{ $note->created_at->format('d M Y, g:i A') }}</span></div>
            <p>{{ $note->text }}</p>
          </div>
        @empty
          <p class="hint">No notes yet &mdash; log the first call or email below.</p>
        @endforelse
      </div>

      <form method="POST" action="{{ route('admin.inquiries.notes.store', $inquiry) }}">
        @csrf
        <div class="field">
          <label for="newNote">Add a note</label>
          <textarea id="newNote" name="text" placeholder="Log a call, an email sent, or the next follow-up date…" style="min-height:70px;">{{ old('text') }}</textarea>
          @error('text')<div class="field-error">{{ $message }}</div>@enderror
        </div>
        <button class="btn btn-primary btn-sm" type="submit" style="margin-top:10px;">Add Note</button>
      </form>
    </div>
  </div>

  <div class="form-stack" style="display:flex;flex-direction:column;gap:20px;">
    <div class="card card-pad">
      <h2 style="font-size:14px;margin-bottom:14px;">Customer Info</h2>
      <div class="info-list">
        <div class="info-row">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          <div><div class="k">Name</div><div class="v">{{ $inquiry->name }}</div></div>
        </div>
        <div class="info-row">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          <div><div class="k">Phone</div><div class="v"><a href="tel:{{ preg_replace('/[^\d+]/', '', $inquiry->phone) }}">{{ $inquiry->phone }}</a></div></div>
        </div>
        <div class="info-row">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/></svg>
          <div><div class="k">Email</div><div class="v"><a href="mailto:{{ $inquiry->email }}">{{ $inquiry->email }}</a></div></div>
        </div>
        <div class="info-row">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/></svg>
          <div><div class="k">Interested Project</div><div class="v">{{ $inquiry->project_name }}</div></div>
        </div>
        <div class="info-row">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          <div><div class="k">Received</div><div class="v">{{ $inquiry->created_at->format('d F Y') }}</div></div>
        </div>
      </div>
    </div>

    <div class="card card-pad">
      <h2 style="font-size:14px;margin-bottom:14px;">Status</h2>
      <form method="POST" action="{{ route('admin.inquiries.status', $inquiry) }}">
        @csrf
        @method('PATCH')
        <div class="field">
          <label for="statusSelect">Update status</label>
          <select id="statusSelect" name="status">
            <option value="new" @selected($inquiry->status === 'new')>New</option>
            <option value="contacted" @selected($inquiry->status === 'contacted')>Contacted</option>
            <option value="follow-up" @selected($inquiry->status === 'follow-up')>Follow-up</option>
            <option value="converted" @selected($inquiry->status === 'converted')>Converted</option>
            <option value="closed" @selected($inquiry->status === 'closed')>Closed</option>
          </select>
        </div>
        <button class="btn btn-primary btn-sm" type="submit" style="width:100%;margin-top:12px;">Save Status</button>
      </form>
    </div>
  </div>
</div>
@endsection
