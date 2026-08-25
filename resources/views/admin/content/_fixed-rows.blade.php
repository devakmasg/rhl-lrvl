{{-- A fixed-count set of title/description rows (partner pillars and steps
     are always exactly 4 or 5 by design — the CSS grid assumes that count —
     so this is deliberately not an add/remove repeater).
     Expects: $prefix (e.g. "landowner_pillar"), $rows (existing array),
     $count, $itemLabel (e.g. "Pillar", "Step"). --}}
@for ($i = 0; $i < $count; $i++)
  @php($row = $rows[$i] ?? ['title' => '', 'desc' => ''])
  <div class="field-row" style="margin-bottom:10px;">
    <div class="field" style="max-width:220px;margin-bottom:0;">
      <label>{{ $itemLabel }} {{ $i + 1 }} Title</label>
      <input type="text" name="{{ $prefix }}_title[]" value="{{ old($prefix.'_title.'.$i, $row['title'] ?? '') }}">
    </div>
    <div class="field" style="margin-bottom:0;">
      <label>{{ $itemLabel }} {{ $i + 1 }} Description</label>
      <textarea name="{{ $prefix }}_desc[]" style="min-height:56px;">{{ old($prefix.'_desc.'.$i, $row['desc'] ?? '') }}</textarea>
    </div>
  </div>
@endfor
