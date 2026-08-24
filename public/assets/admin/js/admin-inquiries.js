/* ================= ADMIN — Inquiries list (A3.1) ================= */
(function(){
  const body = document.getElementById('inquiriesBody');
  if(!body) return;
  const DATA = window.RHL_INQUIRIES;

  function badgeClass(status){ return 'badge-' + status; }
  function label(status){ return status.split('-').map((w) => w[0].toUpperCase() + w.slice(1)).join('-'); }
  function formatDate(iso){
    return new Date(iso + 'T00:00:00').toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
  }

  function rowHtml(inq){
    return `
      <tr data-id="${inq.id}">
        <td><input type="checkbox" class="row-check" aria-label="Select ${inq.name}"></td>
        <td>
          <div class="cell-main">${inq.name}</div>
          <div class="cell-sub">${inq.id}</div>
        </td>
        <td>
          <div>${inq.phone}</div>
          <div class="cell-sub">${inq.email}</div>
        </td>
        <td>${inq.project}</td>
        <td><span class="badge ${badgeClass(inq.status)}">${label(inq.status)}</span></td>
        <td>${formatDate(inq.date)}</td>
        <td class="cell-actions">
          <a href="inquiry-detail.html?id=${inq.id}" class="btn btn-ghost btn-sm">View</a>
          <button class="btn btn-ghost btn-sm" type="button" data-delete="${inq.name}" data-id-target="${inq.id}" data-modal-open="deleteModal">Delete</button>
        </td>
      </tr>`;
  }

  function render(list){
    body.innerHTML = list.length
      ? list.map(rowHtml).join('')
      : `<tr><td colspan="7"><div class="empty-state">
           <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
           <h3>No inquiries match</h3><p>Try clearing the search or filters.</p>
         </div></td></tr>`;

    body.querySelectorAll('[data-delete]').forEach((btn) => {
      btn.addEventListener('click', () => {
        document.getElementById('deleteModalBody').textContent =
          `Delete the inquiry from "${btn.dataset.delete}"? This removes it and its notes permanently. This cannot be undone.`;
        document.getElementById('confirmDelete').dataset.target = btn.dataset.idTarget;
      });
    });
  }

  render(DATA);
  document.getElementById('pgInfo').textContent = `Showing all ${DATA.length} inquiries`;

  const searchEl = document.getElementById('iSearch');
  const statusEl = document.getElementById('iStatus');
  const dateEl = document.getElementById('iDate');

  function applyFilters(){
    const term = searchEl.value.trim().toLowerCase();
    const status = statusEl.value;
    const days = dateEl.value ? Number(dateEl.value) : null;
    const cutoff = days ? Date.now() - days * 86400000 : null;

    const filtered = DATA.filter((inq) =>
      (!term || inq.name.toLowerCase().includes(term) || inq.phone.includes(term) || inq.email.toLowerCase().includes(term)) &&
      (!status || inq.status === status) &&
      (!cutoff || new Date(inq.date + 'T00:00:00').getTime() >= cutoff)
    );
    render(filtered);
    document.getElementById('pgInfo').textContent = `Showing ${filtered.length} of ${DATA.length} inquiries`;
  }

  [searchEl, statusEl, dateEl].forEach((el) => el.addEventListener('input', applyFilters));
  document.getElementById('iClear').addEventListener('click', () => {
    searchEl.value = ''; statusEl.value = ''; dateEl.value = '';
    applyFilters();
  });

  document.getElementById('confirmDelete').addEventListener('click', function(){
    const idx = DATA.findIndex((inq) => inq.id === this.dataset.target);
    if(idx > -1) DATA.splice(idx, 1);
    document.getElementById('deleteModal').classList.remove('open');
    render(DATA);
    document.getElementById('pgInfo').textContent = `Showing all ${DATA.length} inquiries`;
    AdminToast('Inquiry deleted', 'success');
  });
})();
