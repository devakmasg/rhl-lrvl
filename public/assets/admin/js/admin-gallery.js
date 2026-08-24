/* ================= ADMIN — Project gallery manager (A2.3) =================
   Native HTML5 drag-and-drop for reordering (no library — the public site
   already carries Swiper/GSAP for its own sliders and this doesn't need
   either). State lives in a plain array per project slug; nothing persists
   past a reload until Phase 3 wires this to project_images (TASKS.md L1.1). */
(function(){
  const grid = document.getElementById('galleryGrid');
  if(!grid) return;

  const G = 'https://images.unsplash.com/';
  const q = '?auto=format&fit=crop&w=500&q=80';

  const GALLERIES = {
    'gulshan-heights': [
      { src: G + 'photo-1497366754035-f200968a6e72' + q, featured: true },
      { src: G + 'photo-1545324418-cc1a3fa10c00' + q, featured: false },
      { src: G + 'photo-1502005229762-cf1b2da7c5d6' + q, featured: false }
    ],
    'rhl-trade-centre': [
      { src: G + 'photo-1470723710355-95304d8aece4' + q, featured: true },
      { src: G + 'photo-1497366754035-f200968a6e72' + q, featured: false },
      { src: G + 'photo-1512917774080-9991f1c4c750' + q, featured: false }
    ],
    'banani-lake-residences': [
      { src: G + 'photo-1560250097-0b93528c311a' + q, featured: true },
      { src: G + 'photo-1502005229762-cf1b2da7c5d6' + q, featured: false }
    ]
  };

  const select = document.getElementById('gallerySelect');
  const label = document.getElementById('galleryProjectLabel');
  let current = select.value;
  let images = GALLERIES[current].slice();

  function render(){
    grid.innerHTML = '';
    if(!images.length){
      grid.innerHTML = `<div class="empty-state" style="grid-column:1/-1;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-5-5L5 21"/></svg>
        <h3>No images yet</h3><p>Upload photos above to build this project's gallery.</p>
      </div>`;
      return;
    }
    images.forEach((img, i) => {
      const item = document.createElement('div');
      item.className = 'g-item';
      item.draggable = true;
      item.dataset.index = i;
      item.innerHTML = `
        <span class="g-order">${i + 1}</span>
        <img src="${img.src}" alt="">
        <div class="g-actions">
          <button type="button" class="g-btn${img.featured ? ' is-featured' : ''}" data-star aria-label="Set as featured">
            <svg viewBox="0 0 24 24" fill="${img.featured ? 'currentColor' : 'none'}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          </button>
          <button type="button" class="g-btn" data-delete aria-label="Delete image">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>
        ${img.featured ? '<div class="g-caption">Featured shot</div>' : ''}`;
      grid.appendChild(item);
    });
  }

  /* ---------- drag reorder ---------- */
  let dragIndex = null;
  grid.addEventListener('dragstart', (e) => {
    const item = e.target.closest('.g-item');
    if(!item) return;
    dragIndex = Number(item.dataset.index);
    item.classList.add('dragging');
  });
  grid.addEventListener('dragend', (e) => {
    const item = e.target.closest('.g-item');
    if(item) item.classList.remove('dragging');
    grid.querySelectorAll('.drag-over').forEach((el) => el.classList.remove('drag-over'));
  });
  grid.addEventListener('dragover', (e) => {
    e.preventDefault();
    const item = e.target.closest('.g-item');
    if(!item) return;
    grid.querySelectorAll('.drag-over').forEach((el) => el.classList.remove('drag-over'));
    item.classList.add('drag-over');
  });
  grid.addEventListener('drop', (e) => {
    e.preventDefault();
    const item = e.target.closest('.g-item');
    if(!item || dragIndex === null) return;
    const dropIndex = Number(item.dataset.index);
    if(dropIndex === dragIndex) return;
    const [moved] = images.splice(dragIndex, 1);
    images.splice(dropIndex, 0, moved);
    dragIndex = null;
    render();
    AdminToast('Gallery order updated', 'success');
  });

  /* ---------- star / delete ---------- */
  grid.addEventListener('click', (e) => {
    const item = e.target.closest('.g-item');
    if(!item) return;
    const i = Number(item.dataset.index);
    if(e.target.closest('[data-star]')){
      images.forEach((img, idx) => img.featured = idx === i);
      render();
      AdminToast('Featured image updated', 'success');
    } else if(e.target.closest('[data-delete]')){
      images.splice(i, 1);
      render();
      AdminToast('Image removed', 'success');
    }
  });

  /* ---------- upload ---------- */
  const zone = document.getElementById('galleryZone');
  const input = document.getElementById('galleryInput');
  zone.addEventListener('click', () => input.click());
  zone.addEventListener('dragover', (e) => { e.preventDefault(); zone.classList.add('is-drag'); });
  zone.addEventListener('dragleave', () => zone.classList.remove('is-drag'));
  zone.addEventListener('drop', (e) => {
    e.preventDefault(); zone.classList.remove('is-drag');
    addFiles(e.dataTransfer.files);
  });
  input.addEventListener('change', () => { addFiles(input.files); input.value = ''; });
  function addFiles(fileList){
    Array.from(fileList).forEach((file) => {
      if(!file.type.startsWith('image/')) return;
      images.push({ src: URL.createObjectURL(file), featured: images.length === 0 });
    });
    render();
    if(fileList.length) AdminToast(`${fileList.length} image${fileList.length > 1 ? 's' : ''} added`, 'success');
  }

  /* ---------- project switch ---------- */
  select.addEventListener('change', () => {
    current = select.value;
    images = (GALLERIES[current] || []).slice();
    label.textContent = `Managing gallery for ${select.options[select.selectedIndex].text}.`;
    render();
  });

  render();
})();
