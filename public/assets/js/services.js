/* ================= SERVICES PAGE ONLY =================
   Accordion rows (click to expand/collapse).
   Loaded only by services.html, after main.js. */
(function(){
  const rows = document.querySelectorAll('.service-row');
  if(!rows.length) return;

  rows.forEach(row => {
    row.addEventListener('click', () => {
      const wasOpen = row.classList.contains('open');
      rows.forEach(r => r.classList.remove('open'));
      if(!wasOpen) row.classList.add('open');
    });
  });
})();
