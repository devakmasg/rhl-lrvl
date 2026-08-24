/* ================= ADMIN — News list (A4.9) ================= */
(function(){
  const body = document.getElementById('newsBody');
  if(!body) return;
  const DATA = window.RHL_NEWS;

  function formatDate(iso){
    return new Date(iso + 'T00:00:00').toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
  }

  function rowHtml(a){
    return `<tr data-id="${a.id}">
      <td><span class="cell-main">${a.title}</span><div class="cell-sub">${a.excerpt.slice(0, 70)}${a.excerpt.length > 70 ? '…' : ''}</div></td>
      <td>${a.category}</td>
      <td>${formatDate(a.date)}</td>
      <td><span class="badge ${a.published ? 'badge-published' : 'badge-draft'}">${a.published ? 'Published' : 'Draft'}</span></td>
      <td class="cell-actions">
        <a href="news-form.html?id=${a.id}" class="btn btn-ghost btn-sm">Edit</a>
        <button class="btn btn-ghost btn-sm" type="button" data-delete="${a.title}" data-id-target="${a.id}" data-modal-open="deleteModal">Delete</button>
      </td>
    </tr>`;
  }

  function render(){
    body.innerHTML = DATA.length
      ? DATA.slice().sort((a, b) => b.date.localeCompare(a.date)).map(rowHtml).join('')
      : `<tr><td colspan="5"><div class="empty-state">
           <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h13a2 2 0 0 1 2 2v13a1 1 0 0 1-1.7.7L15 17H6a2 2 0 0 1-2-2V4Z"/></svg>
           <h3>No articles yet</h3><p>Publish the first update to get started.</p>
         </div></td></tr>`;
    document.getElementById('pgInfo').textContent = `Showing all ${DATA.length} articles`;

    body.querySelectorAll('[data-delete]').forEach((btn) => {
      btn.addEventListener('click', () => {
        document.getElementById('deleteModalBody').textContent = `Delete "${btn.dataset.delete}"? This cannot be undone.`;
        document.getElementById('confirmDelete').dataset.target = btn.dataset.idTarget;
      });
    });
  }
  render();

  document.getElementById('confirmDelete').addEventListener('click', function(){
    const id = Number(this.dataset.target);
    const idx = DATA.findIndex((a) => a.id === id);
    if(idx > -1) DATA.splice(idx, 1);
    document.getElementById('deleteModal').classList.remove('open');
    render();
    AdminToast('Article deleted', 'success');
  });
})();
