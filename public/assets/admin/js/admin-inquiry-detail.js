/* ================= ADMIN — Inquiry detail (A3.2) ================= */
(function(){
  const content = document.getElementById('detailContent');
  if(!content) return;
  const DATA = window.RHL_INQUIRIES;

  const id = new URLSearchParams(location.search).get('id') || DATA[0].id;
  const inquiry = DATA.find((inq) => inq.id === id);

  if(!inquiry){
    document.getElementById('notFoundState').style.display = '';
    document.getElementById('detailName').textContent = 'Inquiry not found';
    return;
  }

  content.style.display = '';
  document.title = `${inquiry.name} | RHL Admin`;
  document.getElementById('breadcrumbId').textContent = inquiry.id;
  document.getElementById('detailName').textContent = inquiry.name;
  document.getElementById('detailMeta').textContent = `${inquiry.id} — submitted ${inquiry.date}`;
  document.getElementById('detailMessage').textContent = inquiry.message;

  document.getElementById('infoName').textContent = inquiry.name;
  const phoneLink = document.getElementById('infoPhone');
  phoneLink.textContent = inquiry.phone;
  phoneLink.href = 'tel:' + inquiry.phone.replace(/[^\d+]/g, '');
  const emailLink = document.getElementById('infoEmail');
  emailLink.textContent = inquiry.email;
  emailLink.href = 'mailto:' + inquiry.email;
  document.getElementById('infoProject').textContent = inquiry.project;
  document.getElementById('infoDate').textContent = new Date(inquiry.date + 'T00:00:00')
    .toLocaleDateString('en-GB', { day: '2-digit', month: 'long', year: 'numeric' });

  const statusSelect = document.getElementById('statusSelect');
  statusSelect.value = inquiry.status;
  document.getElementById('saveStatusBtn').addEventListener('click', () => {
    inquiry.status = statusSelect.value;
    AdminToast('Status updated to ' + statusSelect.options[statusSelect.selectedIndex].text, 'success');
  });

  const thread = document.getElementById('notesThread');
  function renderNotes(){
    thread.innerHTML = inquiry.notes.length
      ? inquiry.notes.map((n) => `
          <div class="note">
            <div class="note-head"><strong>${n.author}</strong><span>${n.date}</span></div>
            <p>${n.text}</p>
          </div>`).join('')
      : '<p class="hint">No notes yet — log the first call or email below.</p>';
  }
  renderNotes();

  document.getElementById('addNoteBtn').addEventListener('click', () => {
    const textarea = document.getElementById('newNote');
    const text = textarea.value.trim();
    if(!text) return;
    inquiry.notes.push({
      author: 'Md. Rezaul Haque',
      text,
      date: new Date().toLocaleString('en-GB', { day: '2-digit', month: 'short', year: 'numeric', hour: 'numeric', minute: '2-digit' })
    });
    textarea.value = '';
    renderNotes();
    AdminToast('Note added', 'success');
  });
})();
