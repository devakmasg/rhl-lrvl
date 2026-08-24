/* ================= ADMIN — Projects list (A2.1) =================
   Demo dataset mirrors the twelve developments in assets/js/project-detail.js
   (same slugs, types, statuses, locations) so admin and public site agree
   until Phase 3 replaces both with the `projects` table (see TASKS.md L1.1).
   Toggles and delete are visual-only here — no backend yet. */
(function(){
  const body = document.getElementById('projectsBody');
  if(!body) return;

  const G = 'https://images.unsplash.com/';
  const q = '?auto=format&fit=crop&w=200&q=80';

  const PROJECTS = [
    { slug: 'gulshan-heights', name: 'Gulshan Heights', type: 'Residential', status: 'Completed', location: 'Gulshan', img: G + 'photo-1545324418-cc1a3fa10c00' + q, published: true, featured: true },
    { slug: 'rhl-trade-centre', name: 'RHL Trade Centre', type: 'Commercial', status: 'Ongoing', location: 'Gulshan', img: G + 'photo-1470723710355-95304d8aece4' + q, published: true, featured: true },
    { slug: 'banani-lake-residences', name: 'Banani Lake Residences', type: 'Residential', status: 'Ongoing', location: 'Banani', img: G + 'photo-1502005229762-cf1b2da7c5d6' + q, published: true, featured: false },
    { slug: 'banani-exchange', name: 'The Banani Exchange', type: 'Commercial', status: 'Upcoming', location: 'Banani', img: G + 'photo-1477959858617-67f85cf4f1df' + q, published: false, featured: false },
    { slug: 'dhanmondi-garden-villas', name: 'Dhanmondi Garden Villas', type: 'Residential', status: 'Completed', location: 'Dhanmondi', img: G + 'photo-1497366754035-f200968a6e72' + q, published: true, featured: false },
    { slug: 'lakeview-court', name: 'Lakeview Court', type: 'Residential', status: 'Upcoming', location: 'Dhanmondi', img: G + 'photo-1545324418-cc1a3fa10c00' + q, published: false, featured: false },
    { slug: 'tejgaon-industrial-park', name: 'Tejgaon Industrial Park', type: 'Commercial', status: 'Ongoing', location: 'Tejgaon', img: G + 'photo-1477959858617-67f85cf4f1df' + q, published: true, featured: false },
    { slug: 'rhl-logistics-hub', name: 'RHL Logistics Hub', type: 'Commercial', status: 'Completed', location: 'Tejgaon', img: G + 'photo-1502005229762-cf1b2da7c5d6' + q, published: true, featured: false },
    { slug: 'gulshan-park-avenue', name: 'Gulshan Park Avenue', type: 'Residential', status: 'Upcoming', location: 'Gulshan', img: G + 'photo-1470723710355-95304d8aece4' + q, published: false, featured: false },
    { slug: 'banani-corporate-tower', name: 'Banani Corporate Tower', type: 'Commercial', status: 'Completed', location: 'Banani', img: G + 'photo-1497366754035-f200968a6e72' + q, published: true, featured: true },
    { slug: 'dhanmondi-central', name: 'Dhanmondi Central', type: 'Commercial', status: 'Upcoming', location: 'Dhanmondi', img: G + 'photo-1502005229762-cf1b2da7c5d6' + q, published: false, featured: false },
    { slug: 'tejgaon-riverside-homes', name: 'Tejgaon Riverside Homes', type: 'Residential', status: 'Ongoing', location: 'Tejgaon', img: G + 'photo-1545324418-cc1a3fa10c00' + q, published: true, featured: false }
  ];

  function statusBadgeClass(status){
    return 'badge-' + status.toLowerCase();
  }

  function rowHtml(p){
    return `
      <tr data-slug="${p.slug}" data-name="${p.name.toLowerCase()}" data-location="${p.location.toLowerCase()}" data-status="${p.status.toLowerCase()}" data-type="${p.type.toLowerCase()}">
        <td><input type="checkbox" class="row-check" aria-label="Select ${p.name}"></td>
        <td>
          <div style="display:flex;align-items:center;gap:12px;">
            <img src="${p.img}" alt="" style="width:44px;height:44px;border-radius:8px;object-fit:cover;flex:none;">
            <div>
              <div class="cell-main">${p.name}</div>
              <div class="cell-sub">${p.location}</div>
            </div>
          </div>
        </td>
        <td>${p.type}</td>
        <td><span class="badge ${statusBadgeClass(p.status)}">${p.status}</span></td>
        <td>${p.location}</td>
        <td>
          <label class="toggle">
            <input type="checkbox" ${p.published ? 'checked' : ''} data-field="published" aria-label="Published">
            <span class="track"></span>
          </label>
        </td>
        <td>
          <label class="toggle">
            <input type="checkbox" ${p.featured ? 'checked' : ''} data-field="featured" aria-label="Featured">
            <span class="track"></span>
          </label>
        </td>
        <td class="cell-actions">
          <a href="project-form.html?p=${p.slug}" class="btn btn-ghost btn-sm">Edit</a>
          <a href="project-gallery.html?p=${p.slug}" class="btn btn-ghost btn-sm">Gallery</a>
          <button class="btn btn-ghost btn-sm" type="button" data-delete="${p.name}" data-modal-open="deleteModal">Delete</button>
        </td>
      </tr>`;
  }

  function render(list){
    body.innerHTML = list.length
      ? list.map(rowHtml).join('')
      : `<tr><td colspan="8"><div class="empty-state">
           <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
           <h3>No projects match</h3><p>Try clearing the search or filters.</p>
         </div></td></tr>`;

    body.querySelectorAll('input[data-field]').forEach((toggle) => {
      toggle.addEventListener('change', () => {
        const row = toggle.closest('tr');
        const label = toggle.dataset.field === 'published' ? 'Published' : 'Featured';
        AdminToast(`${row.dataset.name.replace(/\b\w/g, c => c.toUpperCase())}: ${label} ${toggle.checked ? 'on' : 'off'}`, 'success');
      });
    });

    body.querySelectorAll('[data-delete]').forEach((btn) => {
      btn.addEventListener('click', () => {
        document.getElementById('deleteModalBody').textContent =
          `Delete "${btn.dataset.delete}"? This removes its gallery, floor plans and brochure permanently. This cannot be undone.`;
        document.getElementById('confirmDelete').dataset.target = btn.closest('tr').dataset.slug;
      });
    });
  }

  render(PROJECTS);

  const searchEl = document.getElementById('pSearch');
  const statusEl = document.getElementById('pStatus');
  const typeEl = document.getElementById('pType');

  function applyFilters(){
    const term = searchEl.value.trim().toLowerCase();
    const status = statusEl.value;
    const type = typeEl.value;
    const filtered = PROJECTS.filter((p) =>
      (!term || p.name.toLowerCase().includes(term) || p.location.toLowerCase().includes(term)) &&
      (!status || p.status.toLowerCase() === status) &&
      (!type || p.type.toLowerCase() === type)
    );
    render(filtered);
    document.getElementById('pgInfo').textContent = `Showing 1–${filtered.length} of ${filtered.length} projects`;
  }

  [searchEl, statusEl, typeEl].forEach((el) => el.addEventListener('input', applyFilters));
  document.getElementById('pClear').addEventListener('click', () => {
    searchEl.value = ''; statusEl.value = ''; typeEl.value = '';
    applyFilters();
  });

  document.getElementById('confirmDelete').addEventListener('click', function(){
    const row = body.querySelector(`tr[data-slug="${this.dataset.target}"]`);
    if(row) row.remove();
    document.getElementById('deleteModal').classList.remove('open');
    AdminToast('Project deleted', 'success');
  });
})();
