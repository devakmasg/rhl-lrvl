{{-- Permission checkboxes, built from App\Support\AdminSections so the list can
     never fall out of step with the sidebar. Shared by the add and edit modals.
     Expects: $grouped (sections by sidebar group), $selected (section keys). --}}
<div class="field">
  <label>Sections this role can open</label>
  <span class="hint" style="margin-bottom:10px;">Unticked sections disappear from their sidebar and are refused if the URL is typed directly. Users and Roles are not listed &mdash; only administrators ever reach those.</span>

  @foreach ($grouped as $group => $sections)
    @continue(count($sections) === 0)
    <div class="perm-group">
      <div class="perm-group-head">
        <h4>{{ $group }}</h4>
        <label class="perm-item" style="margin:0;">
          <input type="checkbox" data-perm-all>
          <span>All</span>
        </label>
      </div>
      <div class="perm-list">
        @foreach ($sections as $key => $section)
          <label class="perm-item">
            <input type="checkbox" name="permissions[]" value="{{ $key }}" @checked(in_array($key, $selected, true))>
            <span>
              {!! $section['label'] !!}
              @isset ($section['note'])
                <span class="perm-note">{!! $section['note'] !!}</span>
              @endisset
            </span>
          </label>
        @endforeach
      </div>
    </div>
  @endforeach
</div>
