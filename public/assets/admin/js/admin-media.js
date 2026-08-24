/* ================= ADMIN — Media library (A5.1) =================
   Single flat library shared by every content screen (project hero/gallery,
   news covers, director photos). Client-only: uploads are held as
   object URLs and vanish on reload until Phase 3 wires this to real file
   storage (TASKS.md L3.5). */
(function(){
  const grid = document.getElementById('mediaGrid');
  if(!grid) return;

  const G = 'https://images.unsplash.com/';
  const q = '?auto=format&fit=crop&w=300&q=80';
  let nextId = 1;
  const FILES = [
    { id: nextId++, name: 'hero-1-residential.jpg', type: 'image', src: G + 'photo-1497366754035-f200968a6e72' + q },
    { id: nextId++, name: 'hero-2-commercial.jpg', type: 'image', src: G + 'photo-1470723710355-95304d8aece4' + q },
    { id: nextId++, name: 'gulshan-heights-01.jpg', type: 'image', src: G + 'photo-1545324418-cc1a3fa10c00' + q },
    { id: nextId++, name: 'banani-lake-residences-01.jpg', type: 'image', src: G + 'photo-1502005229762-cf1b2da7c5d6' + q },
    { id: nextId++, name: 'md-portrait.jpg', type: 'image', src: G + 'photo-1560250097-0b93528c311a' + q },
    { id: nextId++, name: 'RHL-gulshan-heights-brochure.pdf', type: 'doc' },
    { id: nextId++, name: 'RHL-trade-centre-brochure.pdf', type: 'doc' },
    { id: nextId++, name: 'news-trade-centre-topout.jpg', type: 'image', src: G + 'photo-1470723710355-95304d8aece4' + q }
  ];

  const selected = new Set();

  function itemHtml(f){
    const inner = f.type === 'image'
      ? `<img src="${f.src}" alt="">`
      : `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg><span>${f.name}</span>`;
    return `
      <div class="m-item${f.type === 'doc' ? ' is-doc' : ''}" data-id="${f.id}">
        <input type="checkbox" class="m-check" aria-label="Select ${f.name}" ${selected.has(f.id) ? 'checked' : ''}>
        ${inner}
        <button type="button" class="m-delete" aria-label="Delete ${f.name}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
        ${f.type === 'image' ? `<div class="m-name">${f.name}</div>` : ''}
      </div>`;
  }

  function updateSelCount(){
    document.getElementById('selCount').textContent = selected.size;
    document.getElementById('deleteSelected').disabled = selected.size === 0;
  }

  function render(list){
    grid.innerHTML = list.length
      ? list.map(itemHtml).join('')
      : `<div class="empty-state" style="grid-column:1/-1;">
           <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-5-5L5 21"/></svg>
           <h3>No files match</h3><p>Try clearing the search or type filter.</p>
         </div>`;

    grid.querySelectorAll('.m-check').forEach((cb) => {
      cb.addEventListener('change', () => {
        const id = Number(cb.closest('.m-item').dataset.id);
        if(cb.checked) selected.add(id); else selected.delete(id);
        updateSelCount();
      });
    });
    grid.querySelectorAll('.m-delete').forEach((btn) => {
      btn.addEventListener('click', () => {
        const id = Number(btn.closest('.m-item').dataset.id);
        const file = FILES.find((f) => f.id === id);
        document.getElementById('mediaDeleteBody').textContent = `Delete "${file.name}"? This cannot be undone.`;
        document.getElementById('confirmMediaDelete').dataset.target = String(id);
        document.getElementById('mediaDeleteModal').classList.add('open');
      });
    });
  }

  function applyFilters(){
    const term = document.getElementById('mSearch').value.trim().toLowerCase();
    const type = document.getElementById('mType').value;
    render(FILES.filter((f) =>
      (!term || f.name.toLowerCase().includes(term)) && (!type || f.type === type)
    ));
  }
  document.getElementById('mSearch').addEventListener('input', applyFilters);
  document.getElementById('mType').addEventListener('input', applyFilters);

  render(FILES);

  /* ---------- upload ---------- */
  const zone = document.getElementById('mediaZone');
  const input = document.getElementById('mediaInput');
  zone.addEventListener('click', () => input.click());
  zone.addEventListener('dragover', (e) => { e.preventDefault(); zone.classList.add('is-drag'); });
  zone.addEventListener('dragleave', () => zone.classList.remove('is-drag'));
  zone.addEventListener('drop', (e) => { e.preventDefault(); zone.classList.remove('is-drag'); addFiles(e.dataTransfer.files); });
  input.addEventListener('change', () => { addFiles(input.files); input.value = ''; });

  function addFiles(fileList){
    Array.from(fileList).forEach((file) => {
      FILES.unshift({
        id: nextId++,
        name: file.name,
        type: file.type === 'application/pdf' ? 'doc' : 'image',
        src: file.type.startsWith('image/') ? URL.createObjectURL(file) : undefined
      });
    });
    applyFilters();
    if(fileList.length) AdminToast(`${fileList.length} file${fileList.length > 1 ? 's' : ''} uploaded`, 'success');
  }

  /* ---------- delete (single via modal, bulk via toolbar) ---------- */
  document.getElementById('deleteSelected').addEventListener('click', () => {
    document.getElementById('mediaDeleteBody').textContent = `Delete ${selected.size} selected file${selected.size > 1 ? 's' : ''}? This cannot be undone.`;
    document.getElementById('confirmMediaDelete').dataset.bulk = '1';
    document.getElementById('mediaDeleteModal').classList.add('open');
  });

  document.getElementById('confirmMediaDelete').addEventListener('click', function(){
    if(this.dataset.bulk){
      const count = selected.size;
      selected.forEach((id) => {
        const idx = FILES.findIndex((f) => f.id === id);
        if(idx > -1) FILES.splice(idx, 1);
      });
      selected.clear();
      delete this.dataset.bulk;
      AdminToast(`${count} file${count > 1 ? 's' : ''} deleted`, 'success');
    } else {
      const id = Number(this.dataset.target);
      const idx = FILES.findIndex((f) => f.id === id);
      if(idx > -1) FILES.splice(idx, 1);
      selected.delete(id);
      AdminToast('File deleted', 'success');
    }
    document.getElementById('mediaDeleteModal').classList.remove('open');
    applyFilters(); updateSelCount();
  });
})();
