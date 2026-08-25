{{-- Add/edit modal for a homepage journey chapter.
     Expects: $modalId, and $chapter (null when adding). --}}
<div class="modal-overlay" id="{{ $modalId }}">
  <div class="modal">
    <div class="modal-head">
      <h3>{{ $chapter ? 'Edit Chapter' : 'Add Chapter' }}</h3>
      <button class="modal-close" data-modal-close aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
    </div>
    <form method="POST"
          action="{{ $chapter ? route('admin.content.home.journey.update', $chapter) : route('admin.content.home.journey.store') }}"
          enctype="multipart/form-data">
      @csrf
      @if ($chapter) @method('PUT') @endif

      <div class="modal-body" style="display:flex;flex-direction:column;gap:14px;">
        <div class="field">
          <label>Kicker</label>
          <input type="text" name="kicker" value="{{ $chapter->kicker ?? '' }}" placeholder="e.g. Ongoing — Tejgaon">
          <span class="hint">The small dotted label above the heading.</span>
        </div>

        <div class="field">
          <label>Heading</label>
          <input type="text" name="heading" value="{{ $chapter->heading ?? '' }}" required>
        </div>

        <div class="field">
          <label>Body</label>
          <textarea name="body" style="min-height:90px;">{{ $chapter->body ?? '' }}</textarea>
        </div>

        <div class="field-row">
          <div class="field">
            <label>Link Label</label>
            <input type="text" name="link_label" value="{{ $chapter->link_label ?? '' }}" placeholder="e.g. Follow the build">
            <span class="hint">Leave blank to hide the link.</span>
          </div>
          <div class="field">
            <label>Link URL</label>
            <input type="text" name="link_url" value="{{ $chapter->link_url ?? '' }}" placeholder="/projects">
          </div>
        </div>

        <div class="field">
          <label>Media Type</label>
          <select name="media_type">
            <option value="image" {{ ($chapter->media_type ?? 'image') === 'image' ? 'selected' : '' }}>Photo only</option>
            <option value="video" {{ ($chapter->media_type ?? '') === 'video' ? 'selected' : '' }}>Video (photo becomes the poster)</option>
          </select>
        </div>

        @include('admin.partials.image-field', [
          'name' => 'image_path',
          'label' => 'Photo',
          'currentUrl' => $chapter?->image_url,
          'hint' => 'Shown full-width. Also used as the poster frame for a video chapter.',
        ])

        <div class="field">
          <label>Video Clip</label>
          @if ($chapter?->video_path)
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
              <span class="cell-sub" style="flex:1;">{{ basename($chapter->video_path) }}</span>
              <label style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--stone);cursor:pointer;">
                <input type="checkbox" name="video_path_remove" value="1" style="width:auto;margin:0;"> Remove
              </label>
            </div>
          @endif
          <input type="file" name="video_path" accept="video/mp4,video/webm">
          <span class="hint">MP4 or WebM, up to 50 MB. Plays muted and looping while the chapter is on screen.</span>
        </div>

        <div class="field">
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
            <input type="checkbox" name="is_active" value="1" {{ ($chapter->is_active ?? true) ? 'checked' : '' }} style="width:auto;margin:0;">
            Show this chapter on the homepage
          </label>
        </div>
      </div>

      <div class="modal-foot">
        <button class="btn btn-outline" type="button" data-modal-close>Cancel</button>
        <button class="btn btn-primary" type="submit">{{ $chapter ? 'Save Chapter' : 'Add Chapter' }}</button>
      </div>
    </form>
  </div>
</div>
