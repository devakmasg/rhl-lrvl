{{-- Add/edit modal for an "Explore" slider slide.
     Expects: $modalId, $slide (null when adding), $projects. --}}
<div class="modal-overlay" id="{{ $modalId }}">
  <div class="modal">
    <div class="modal-head">
      <h3>{{ $slide ? 'Edit Slide' : 'Add Slide' }}</h3>
      <button class="modal-close" data-modal-close aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
    </div>
    <form method="POST"
          action="{{ $slide ? route('admin.content.home.explore.update', $slide) : route('admin.content.home.explore.store') }}"
          enctype="multipart/form-data">
      @csrf
      @if ($slide) @method('PUT') @endif

      <div class="modal-body" style="display:flex;flex-direction:column;gap:14px;">
        <div class="field">
          <label>Linked Project</label>
          <select name="project_id">
            <option value="">Not linked — type the caption below</option>
            @foreach ($projects as $p)
              <option value="{{ $p->id }}" {{ (int) ($slide->project_id ?? 0) === $p->id ? 'selected' : '' }}>{{ $p->name }} ({{ $p->location }})</option>
            @endforeach
          </select>
          <span class="hint">Link a project and the caption follows it automatically, so renaming the project updates this slide too.</span>
        </div>

        <div class="field-row">
          <div class="field">
            <label>Category</label>
            <input type="text" name="category" value="{{ $slide->category ?? '' }}" placeholder="Residential">
          </div>
          <div class="field">
            <label>Location</label>
            <input type="text" name="location" value="{{ $slide->location ?? '' }}" placeholder="Gulshan">
          </div>
        </div>

        <div class="field">
          <label>Title</label>
          <input type="text" name="title" value="{{ $slide->title ?? '' }}" placeholder="The RHL Residences">
          <span class="hint">Used only when no project is linked.</span>
        </div>

        <div class="field">
          <label>Media Type</label>
          <select name="media_type">
            <option value="image" {{ ($slide->media_type ?? 'image') === 'image' ? 'selected' : '' }}>Photo only</option>
            <option value="video" {{ ($slide->media_type ?? '') === 'video' ? 'selected' : '' }}>Video (photo becomes the poster)</option>
          </select>
        </div>

        @include('admin.partials.image-field', [
          'name' => 'image_path',
          'label' => 'Photo',
          'currentUrl' => $slide?->image_url,
          'hint' => 'Fills the slide. Also the poster frame for a video slide.',
        ])

        <div class="field">
          <label>Video Clip</label>
          @if ($slide?->video_path)
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
              <span class="cell-sub" style="flex:1;">{{ basename($slide->video_path) }}</span>
              <label style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--stone);cursor:pointer;">
                <input type="checkbox" name="video_path_remove" value="1" style="width:auto;margin:0;"> Remove
              </label>
            </div>
          @endif
          <input type="file" name="video_path" accept="video/mp4,video/webm">
          <span class="hint">MP4 or WebM, up to 50 MB. Only the active slide's clip plays.</span>
        </div>

        <div class="field">
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
            <input type="checkbox" name="is_active" value="1" {{ ($slide->is_active ?? true) ? 'checked' : '' }} style="width:auto;margin:0;">
            Show this slide on the homepage
          </label>
        </div>
      </div>

      <div class="modal-foot">
        <button class="btn btn-outline" type="button" data-modal-close>Cancel</button>
        <button class="btn btn-primary" type="submit">{{ $slide ? 'Save Slide' : 'Add Slide' }}</button>
      </div>
    </form>
  </div>
</div>
