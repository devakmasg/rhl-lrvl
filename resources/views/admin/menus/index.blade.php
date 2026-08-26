@extends('layouts.admin')

@section('title', 'Menus')

@push('head')
<style>
  .mn-item{border:1px solid var(--line);border-radius:10px;margin-bottom:14px;background:var(--surface);overflow:hidden;}
  .mn-head{display:flex;align-items:center;gap:14px;padding:14px 16px;cursor:pointer;}
  .mn-head:hover{background:var(--surface-2, rgba(0,0,0,.02));}
  .mn-meta{flex:1;min-width:0;}
  .mn-meta .k{font-size:14px;font-weight:600;}
  .mn-meta .v{font-size:12px;color:var(--stone);}
  .mn-count{flex:none;font-size:11.5px;color:var(--stone);border:1px solid var(--line);border-radius:20px;padding:2px 10px;}
  .mn-caret{transition:transform .15s;flex:none;width:16px;height:16px;color:var(--stone);}
  .mn-item.is-open .mn-caret{transform:rotate(90deg);}
  .mn-body{display:none;padding:0 16px 18px;border-top:1px solid var(--line);}
  .mn-item.is-open .mn-body{display:block;}
  .mn-row{display:flex;gap:10px;align-items:flex-start;padding:10px;border:1px solid var(--line);border-radius:8px;margin-bottom:8px;background:var(--bg, transparent);}
  .mn-row.is-child{margin-left:34px;border-style:dashed;}
  .mn-grip{flex:none;width:22px;color:var(--stone);font-size:11px;padding-top:9px;text-align:center;cursor:grab;user-select:none;}
  .mn-row .field{flex:1;margin-bottom:0;min-width:0;}
  .mn-row .field.narrow{max-width:210px;}
  .mn-tools{flex:none;display:flex;flex-direction:column;gap:6px;align-items:center;padding-top:4px;}
  .mn-live{font-size:11px;color:var(--stone);display:flex;align-items:center;gap:5px;white-space:nowrap;}
  .mn-del{width:30px;height:30px;border-radius:7px;border:1px solid var(--line);background:var(--surface);color:var(--danger);cursor:pointer;line-height:1;}
  .mn-del:hover{background:var(--danger-bg);}
  .mn-add{display:flex;gap:8px;margin-top:4px;}
</style>
@endpush

@section('content')
<div class="page-head">
  <div>
    <h1>Menus</h1>
    <p>The header navigation and the footer's link column. Both the desktop and mobile menus read from the header menu below, so a label only has to be changed once.</p>
  </div>
</div>

@forelse ($menus as $menu)
  @php($rows = $menu->links->whereNull('parent_id')->sortBy('sort_order'))
  <div class="mn-item" id="menu-{{ $menu->id }}">
    <div class="mn-head" role="button" tabindex="0" data-mn-toggle aria-expanded="false">
      <svg class="mn-caret" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
      <div class="mn-meta">
        <div class="k">{{ $menu->label }}</div>
        <div class="v">{{ $menu->links->pluck('label')->take(5)->implode(' · ') }}{{ $menu->links->count() > 5 ? ' …' : '' }}</div>
      </div>
      <span class="mn-count">{{ $menu->links->count() }} links</span>
    </div>

    <div class="mn-body">
      <form method="POST" action="{{ route('admin.menus.update', $menu) }}" data-mn-form>
        @csrf
        @method('PUT')

        @if ($menu->heading !== null || $menu->key !== 'primary')
          <div class="field" style="max-width:320px;margin:16px 0;">
            <label>Column Heading</label>
            <input type="text" name="heading" value="{{ old('heading', $menu->heading) }}">
            <span class="hint">Shown above this list in the footer.</span>
          </div>
        @endif

        <div data-mn-list style="margin-top:16px;">
          @foreach ($rows as $link)
            @include('admin.menus._row', ['link' => $link, 'menu' => $menu, 'child' => false])
            @foreach ($link->children->sortBy('sort_order') as $childLink)
              @include('admin.menus._row', ['link' => $childLink, 'menu' => $menu, 'child' => true, 'parentId' => $link->id])
            @endforeach
          @endforeach
        </div>

        <div class="mn-add">
          <button type="button" class="btn btn-outline btn-sm" data-mn-add>+ Add Link</button>
          @if ($menu->key === 'primary')
            <span class="hint" style="align-self:center;">Indented rows are the dropdown under the item above them. New links are added at the top level.</span>
          @endif
        </div>

        <div style="margin-top:16px;display:flex;justify-content:flex-end;">
          <button class="btn btn-primary" type="submit">Save &ldquo;{{ $menu->label }}&rdquo;</button>
        </div>
      </form>
    </div>
  </div>
@empty
  <div class="empty-state">
    <h3>No menus yet</h3>
    <p>Run <code>php artisan migrate</code> to create them.</p>
  </div>
@endforelse

<template id="mnRowTemplate">
  @include('admin.menus._row', ['link' => null, 'menu' => null, 'child' => false])
</template>
@endsection

@push('scripts')
<script>
  document.querySelectorAll('[data-mn-toggle]').forEach((head) => {
    const item = head.closest('.mn-item');
    const toggle = () => {
      const open = item.classList.toggle('is-open');
      head.setAttribute('aria-expanded', open ? 'true' : 'false');
    };
    head.addEventListener('click', toggle);
    head.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggle(); }
    });
  });

  /* Input names carry a row index, so they are renumbered whenever a row is
     added or removed — otherwise a deleted row would leave a gap and the
     controller would read the rows in the wrong order. */
  function renumber(list) {
    list.querySelectorAll('.mn-row').forEach((row, i) => {
      row.querySelectorAll('[name]').forEach((input) => {
        input.name = input.name.replace(/links\[\d*\]/, 'links[' + i + ']');
      });
    });
  }

  document.querySelectorAll('[data-mn-form]').forEach((form) => {
    const list = form.querySelector('[data-mn-list]');

    form.querySelector('[data-mn-add]').addEventListener('click', () => {
      const frag = document.getElementById('mnRowTemplate').content.cloneNode(true);
      list.appendChild(frag);
      renumber(list);
    });

    list.addEventListener('click', (e) => {
      const del = e.target.closest('[data-mn-del]');
      if (!del) return;
      del.closest('.mn-row').remove();
      renumber(list);
    });

    renumber(list);
  });

  if (location.hash.startsWith('#menu-')) {
    const target = document.querySelector(location.hash);
    if (target) target.classList.add('is-open');
  }
</script>
@endpush
