/* ================= ADMIN — generic list + modal-form CRUD (A4) =================
   Services, Directors, Team and Testimonials are the same shape of screen —
   a table, an add/edit modal, a delete confirm — differing only in which
   fields they carry. Rather than four near-identical copies of that wiring
   (which would drift the first time one of them got a bugfix the others
   didn't), the table/modal logic lives here once and each page just passes
   its own field list and demo rows. Each page still owns its own modal
   markup (see admin.css .modal-overlay) since there's no template partial
   mechanism without a build step. */
function initCrudList(opts){
  const body = document.getElementById(opts.bodyId);
  if(!body) return;
  const data = opts.data;
  let editingId = null;

  function rowHtml(item){
    return `<tr data-id="${item.id}">
      ${opts.columns.map((col) => `<td>${col.render(item)}</td>`).join('')}
      <td class="cell-actions">
        <button class="btn btn-ghost btn-sm" type="button" data-edit>Edit</button>
        <button class="btn btn-ghost btn-sm" type="button" data-delete>Delete</button>
      </td>
    </tr>`;
  }

  function render(){
    body.innerHTML = data.length
      ? data.map(rowHtml).join('')
      : `<tr><td colspan="${opts.columns.length + 1}"><div class="empty-state">
           <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
           <h3>No ${opts.title.toLowerCase()}s yet</h3><p>Add the first one to get started.</p>
         </div></td></tr>`;

    body.querySelectorAll('[data-edit]').forEach((btn) => {
      btn.addEventListener('click', () => openForm(Number(btn.closest('tr').dataset.id)));
    });
    body.querySelectorAll('[data-delete]').forEach((btn) => {
      btn.addEventListener('click', () => {
        const id = Number(btn.closest('tr').dataset.id);
        const item = data.find((d) => d.id === id);
        document.getElementById(opts.deleteBodyId).textContent =
          `Delete "${item[opts.fields[0].key]}"? This cannot be undone.`;
        document.getElementById(opts.confirmDeleteId).dataset.target = id;
      });
    });
  }

  const modal = document.getElementById(opts.modalId);
  const modalTitle = document.getElementById(opts.modalTitleId);
  const formEl = document.getElementById(opts.formId);

  function openForm(id){
    editingId = id || null;
    const item = editingId ? data.find((d) => d.id === editingId) : {};
    modalTitle.textContent = editingId ? `Edit ${opts.title}` : `Add ${opts.title}`;
    opts.fields.forEach((f) => {
      const el = formEl.querySelector(`[name="${f.key}"]`);
      if(el) el.value = item[f.key] || '';
    });
    modal.classList.add('open');
  }
  document.getElementById(opts.addBtnId).addEventListener('click', () => openForm(null));

  formEl.addEventListener('submit', (e) => {
    e.preventDefault();
    const bad = Array.from(formEl.elements).filter((el) => el.willValidate && !el.checkValidity());
    if(bad.length){ bad[0].focus(); return; }

    const values = {};
    opts.fields.forEach((f) => { values[f.key] = formEl.querySelector(`[name="${f.key}"]`).value.trim(); });

    if(editingId){
      Object.assign(data.find((d) => d.id === editingId), values);
      AdminToast(`${opts.title} updated`, 'success');
    } else {
      values.id = data.length ? Math.max(...data.map((d) => d.id)) + 1 : 1;
      data.push(values);
      AdminToast(`${opts.title} added`, 'success');
    }
    modal.classList.remove('open');
    render();
  });

  document.getElementById(opts.confirmDeleteId).addEventListener('click', function(){
    const id = Number(this.dataset.target);
    const idx = data.findIndex((d) => d.id === id);
    if(idx > -1) data.splice(idx, 1);
    document.getElementById(opts.deleteModalId).classList.remove('open');
    AdminToast(`${opts.title} deleted`, 'success');
    render();
  });

  render();
}
