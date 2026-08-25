@extends('layouts.admin')

@section('title', $news->exists ? 'Edit Article' : 'New Article')

@push('head')
<style>
  .form-grid{display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;}
  @media (max-width:1080px){.form-grid{grid-template-columns:1fr;}}
  .upload-zone{border:1.5px dashed var(--line);border-radius:10px;padding:22px;text-align:center;color:var(--stone);font-size:12.5px;cursor:pointer;}
  .upload-zone:hover{border-color:var(--gold);background:#faf5ea;}
  .upload-zone svg{width:26px;height:26px;margin:0 auto 8px;opacity:.6;}
</style>
@endpush

@section('content')
<div class="page-head">
  <div>
    <h1>{{ $news->exists ? 'Edit Article' : 'New Article' }}</h1>
    <p>Feeds news.html and each article's news-detail.html page.</p>
  </div>
  <div class="page-head-actions">
    <a href="{{ route('admin.news.index') }}" class="btn btn-outline">Cancel</a>
    <button class="btn btn-primary" type="submit" form="newsForm" id="saveBtn">{{ $news->exists ? 'Save Changes' : 'Publish Article' }}</button>
  </div>
</div>

<form id="newsForm" class="form-grid" method="POST"
  action="{{ $news->exists ? route('admin.news.update', $news) : route('admin.news.store') }}"
  enctype="multipart/form-data">
  @csrf
  @if ($news->exists)
    @method('PUT')
  @endif

  <div class="card card-pad">
    <div class="field" style="margin-bottom:16px;">
      <label for="artTitle">Headline</label>
      <input type="text" id="artTitle" name="title" value="{{ old('title', $news->title) }}" required>
      <span class="field-error">{{ $errors->first('title') }}</span>
    </div>
    <div class="field-row" style="margin-bottom:16px;">
      <div class="field">
        <label for="artCategory">Category</label>
        <select id="artCategory" name="category">
          @foreach (['Construction Update', 'Handover', 'Sales', 'Awards', 'Community', 'New Launch'] as $cat)
            <option value="{{ $cat }}" @selected(old('category', $news->category) === $cat)>{{ $cat }}</option>
          @endforeach
        </select>
      </div>
      <div class="field">
        <label for="artDate">Publish Date</label>
        <input type="date" id="artDate" name="date" value="{{ old('date', optional($news->date)->format('Y-m-d')) }}" required>
      </div>
    </div>
    <div class="field" style="margin-bottom:16px;">
      <label for="artExcerpt">Excerpt</label>
      <textarea id="artExcerpt" name="excerpt" placeholder="One or two sentences shown on the news listing grid." required style="min-height:70px;">{{ old('excerpt', $news->excerpt) }}</textarea>
      <span class="field-error">{{ $errors->first('excerpt') }}</span>
    </div>
    <div class="field">
      <label for="artBody">Full Article</label>
      <textarea id="artBody" name="body" placeholder="One paragraph per line." style="min-height:180px;">{{ old('body', $news->body) }}</textarea>
      <span class="hint">One paragraph per line.</span>
    </div>
  </div>

  <div style="display:flex;flex-direction:column;gap:20px;">
    <div class="card card-pad">
      <h2 style="font-size:14px;margin-bottom:14px;">Publish Settings</h2>
      <div style="display:flex;align-items:center;justify-content:space-between;">
        <div><div style="font-weight:600;font-size:13px;">Published</div><div class="hint">Visible on the live site</div></div>
        <label class="toggle"><input type="checkbox" name="published" value="1" @checked(old('published', $news->published ?? true))><span class="track"></span></label>
      </div>
    </div>
    <div class="card card-pad">
      <h2 style="font-size:14px;margin-bottom:14px;">Cover Image</h2>
      @if ($news->cover_image)
        <img src="{{ $news->cover_image_url }}" alt="" style="width:100%;border-radius:8px;margin-bottom:10px;">
      @endif
      <label class="upload-zone" id="coverZone" for="coverInput">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-5-5L5 21"/></svg>
        Click to upload cover photo
        <input type="file" id="coverInput" name="cover_image" accept="image/*" hidden>
      </label>
      <div id="coverChip" style="margin-top:10px;font-size:12px;color:var(--stone);"></div>
      <span class="field-error">{{ $errors->first('cover_image') }}</span>
    </div>
  </div>
</form>

@push('scripts')
<script>
  document.getElementById('coverInput').addEventListener('change', (e) => {
    const chip = document.getElementById('coverChip');
    chip.textContent = e.target.files[0] ? e.target.files[0].name : '';
  });
</script>
@endpush
@endsection
