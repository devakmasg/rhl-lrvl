/* ================= LIGHTBOX =================
   Generic click-to-enlarge overlay for anything marked [data-lightbox] —
   currently just the floor plan thumbnails on project.html. One shared
   overlay in the page markup rather than one per trigger, since only one
   can ever be open at a time. */
(function(){
  const box = document.getElementById('lightbox');
  if(!box) return;
  const img = document.getElementById('lightboxImg');
  const caption = document.getElementById('lightboxCaption');
  const closeBtn = document.getElementById('lightboxClose');

  function open(src, label){
    img.src = src;
    img.alt = label || '';
    caption.textContent = label || '';
    box.classList.add('is-open');
    document.body.style.overflow = 'hidden';
    closeBtn.focus();
  }
  function close(){
    box.classList.remove('is-open');
    document.body.style.overflow = '';
    img.src = '';
  }

  // Delegated: floor plan thumbnails are rendered by project-detail.js after
  // this script runs, so a direct querySelectorAll here would find nothing.
  document.addEventListener('click', (e) => {
    const trigger = e.target.closest('[data-lightbox]');
    if(trigger){ open(trigger.dataset.lightbox, trigger.dataset.caption); return; }
    if(e.target === box) close();
  });
  closeBtn.addEventListener('click', close);
  document.addEventListener('keydown', (e) => { if(e.key === 'Escape' && box.classList.contains('is-open')) close(); });
})();
