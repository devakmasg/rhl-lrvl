/* ================= ADMIN — Project create/edit form (A2.2, A2.4) =================
   No backend yet (see TASKS.md L1.1/L3.2 for the real Laravel version), so
   this only has to prove out the field set and interactions: repeaters for
   facts/amenities/units, drag-and-click file zones with a chip list, and
   edit-mode prefill keyed off ?p=<slug> using the same demo shape as
   assets/js/project-detail.js. */
(function(){
  const form = document.getElementById('projectForm');
  if(!form) return;

  /* ---------- slug auto-fill ---------- */
  const nameField = document.getElementById('pName');
  const slugField = document.getElementById('pSlug');
  let slugTouched = false;
  slugField.addEventListener('input', () => { slugTouched = true; });
  nameField.addEventListener('input', () => {
    if(slugTouched) return;
    slugField.value = nameField.value.trim().toLowerCase()
      .replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');
  });

  /* ---------- progress field only matters while Ongoing ---------- */
  const statusField = document.getElementById('pStatus');
  const progressField = document.getElementById('progressField');
  function syncProgress(){ progressField.style.display = statusField.value === 'Ongoing' ? '' : 'none'; }
  statusField.addEventListener('change', syncProgress);
  syncProgress();

  /* ---------- generic key/value + single-value repeaters ---------- */
  function makeRepeater(listEl, addBtn, buildRow, seed){
    function addRow(values){
      const row = document.createElement('div');
      row.className = 'repeater-row';
      row.innerHTML = buildRow(values || {});
      const remove = document.createElement('button');
      remove.type = 'button';
      remove.className = 'repeater-remove';
      remove.setAttribute('aria-label', 'Remove');
      remove.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';
      remove.addEventListener('click', () => row.remove());
      row.appendChild(remove);
      listEl.appendChild(row);
    }
    addBtn.addEventListener('click', () => addRow());
    (seed || []).forEach(addRow);
    return addRow;
  }

  const addFact = makeRepeater(
    document.getElementById('factsList'), document.getElementById('addFact'),
    (v) => `<div class="field"><input type="text" placeholder="Label, e.g. Completion" value="${v.k || ''}" data-fact-key></div>
            <div class="field"><input type="text" placeholder="Value, e.g. Q4 2027" value="${v.v || ''}" data-fact-val></div>`,
    [{ k: 'Completion', v: 'Q4 2027' }, { k: 'Built area', v: '186,000 sq ft' }, { k: 'Plot', v: '18 katha' }]
  );

  const addAmenity = makeRepeater(
    document.getElementById('amenitiesList'), document.getElementById('addAmenity'),
    (v) => `<div class="field"><input type="text" placeholder="e.g. Rooftop swimming pool" value="${v.v || ''}" data-amenity></div>`,
    [{ v: '24-hour security' }, { v: 'Backup generator' }]
  );

  /* ---------- unit table repeater ---------- */
  const unitsBody = document.getElementById('unitsBody');
  function addUnitRow(v){
    v = v || {};
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td><input type="text" value="${v.type || ''}" placeholder="3 Bed"></td>
      <td><input type="text" value="${v.size || ''}" placeholder="1,980"></td>
      <td><input type="text" value="${v.beds || ''}" placeholder="3"></td>
      <td><input type="text" value="${v.baths || ''}" placeholder="3"></td>
      <td><button type="button" class="repeater-remove" aria-label="Remove row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button></td>`;
    tr.querySelector('.repeater-remove').addEventListener('click', () => tr.remove());
    unitsBody.appendChild(tr);
  }
  document.getElementById('addUnitRow').addEventListener('click', () => addUnitRow());
  addUnitRow({ type: '3 Bed', size: '1,980', beds: '3', baths: '3' });

  /* ---------- upload zones ----------
     Client-only preview: an image zone shows thumbnails, a PDF zone shows a
     filename chip. Nothing is actually uploaded until Phase 3 (L3.2). */
  function wireUploadZone(zoneId, inputId, chipsId, opts){
    const zone = document.getElementById(zoneId);
    const input = document.getElementById(inputId);
    const chips = document.getElementById(chipsId);
    if(!zone) return;

    zone.addEventListener('click', () => input.click());
    zone.addEventListener('dragover', (e) => { e.preventDefault(); zone.classList.add('is-drag'); });
    zone.addEventListener('dragleave', () => zone.classList.remove('is-drag'));
    zone.addEventListener('drop', (e) => {
      e.preventDefault(); zone.classList.remove('is-drag');
      handleFiles(e.dataTransfer.files);
    });
    input.addEventListener('change', () => handleFiles(input.files));

    function handleFiles(fileList){
      const files = Array.from(fileList);
      if(!opts.multiple) chips.innerHTML = '';
      files.forEach((file) => {
        const chip = document.createElement('div');
        chip.className = 'file-chip';
        chip.innerHTML = `
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
          <span></span>
          <button type="button" aria-label="Remove file">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="14" height="14"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>`;
        chip.querySelector('span').textContent = file.name;
        chip.querySelector('button').addEventListener('click', () => chip.remove());
        chips.appendChild(chip);
      });
      if(!opts.multiple) input.value = '';
    }
  }
  wireUploadZone('heroZone', 'heroInput', 'heroChips', { multiple: false });
  wireUploadZone('floorplanZone', 'floorplanInput', 'floorplanChips', { multiple: true });
  wireUploadZone('brochureZone', 'brochureInput', 'brochureChips', { multiple: false });

  /* ---------- edit mode: prefill from ?p=<slug> (dashboard/list links here) ---------- */
  const slug = new URLSearchParams(location.search).get('p');
  if(slug){
    document.getElementById('formTitle').textContent = 'Edit Project';
    document.getElementById('breadcrumbCurrent').textContent = 'Edit Project';
    document.getElementById('publishBtn').textContent = 'Save Changes';
    document.title = 'Edit Project | RHL Admin';
    // Demo prefill — a real edit screen would fetch this project's row.
    nameField.value = slug.split('-').map((w) => w[0].toUpperCase() + w.slice(1)).join(' ');
    slugField.value = slug; slugTouched = true;
  }

  /* ---------- submit / save draft (demo only — no backend) ---------- */
  function collectAndToast(message){
    const bad = Array.from(form.elements).filter((el) => el.willValidate && !el.checkValidity());
    if(bad.length){ bad[0].focus(); AdminToast('Please fill in the required fields.', 'error'); return; }
    AdminToast(message, 'success');
    setTimeout(() => { location.href = 'projects.html'; }, 700);
  }
  form.setAttribute('novalidate', '');
  form.addEventListener('submit', (e) => { e.preventDefault(); collectAndToast('Project published.'); });
  document.getElementById('saveDraftBtn').addEventListener('click', () => collectAndToast('Draft saved.'));
})();
