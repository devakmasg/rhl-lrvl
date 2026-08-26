{{-- One editable menu row. Rendered both for existing links and, with
     $link = null, into the <template> the "Add Link" button clones. The input
     index is filled in by renumber() on the client. --}}
@php
  $child = $child ?? false;
  $parentId = $parentId ?? null;
@endphp
<div class="mn-row{{ $child ? ' is-child' : '' }}">
  <span class="mn-grip" title="Order follows this list">{{ $child ? '↳' : '⠿' }}</span>

  <input type="hidden" name="links[][id]" value="{{ $link?->id }}">
  <input type="hidden" name="links[][parent_id]" value="{{ $parentId }}">

  <div class="field">
    <input type="text" name="links[][label]" value="{{ $link?->label }}" placeholder="Link label" aria-label="Link label">
  </div>

  <div class="field narrow">
    <select name="links[][target]" aria-label="Links to">
      <option value="">Choose a page…</option>
      @foreach ($targets as $name => $niceName)
        <option value="{{ $name }}" @selected($link?->target === $name)>{{ $niceName }}</option>
      @endforeach
      @if ($link && ! array_key_exists($link->target, $targets))
        <option value="{{ $link->target }}" selected>{{ $link->target }}</option>
      @endif
    </select>
  </div>

  <div class="mn-tools">
    <label class="mn-live">
      <input type="hidden" name="links[][is_active]" value="0">
      <input type="checkbox" name="links[][is_active]" value="1" {{ $link === null || $link->is_active ? 'checked' : '' }} style="width:auto;margin:0;">
      Show
    </label>
    <button type="button" class="mn-del" data-mn-del aria-label="Remove link">&times;</button>
  </div>
</div>
