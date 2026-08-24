/* ================= RHL ADMIN — shared shell behaviour =================
   One file, loaded by every admin/*.html page, covering everything the
   static shell needs: sidebar collapse on tablet/mobile, active-link
   highlighting from the current filename, and the modal/toast primitives
   the CRUD screens (A2–A5) will reuse. No page-specific logic lives here. */
(function(){
  const body = document.body;

  /* ---------- SIDEBAR ---------- */
  const sbToggle = document.getElementById('sbToggle');
  const sidebar = document.getElementById('adminSidebar');
  const scrim = document.getElementById('sidebarScrim');

  function closeSidebar(){ body.classList.remove('sb-open'); if(sbToggle) sbToggle.setAttribute('aria-expanded', 'false'); }
  function toggleSidebar(){
    const open = body.classList.toggle('sb-open');
    if(sbToggle) sbToggle.setAttribute('aria-expanded', String(open));
  }
  if(sbToggle) sbToggle.addEventListener('click', toggleSidebar);
  if(scrim) scrim.addEventListener('click', closeSidebar);
  document.addEventListener('keydown', (e) => { if(e.key === 'Escape') closeSidebar(); });

  /* ---------- ACTIVE NAV LINK ----------
     Every admin page ships the same sidebar markup (same discipline as the
     public header/footer — see rhl-html-partials-discipline), so "which
     link is active" has to be derived at runtime from the URL rather than
     hand-edited per page, or it will drift the first time a page is copied. */
  const here = location.pathname.split('/').pop() || 'dashboard.html';
  document.querySelectorAll('.sb-link[href]').forEach((link) => {
    const href = link.getAttribute('href').split('/').pop();
    if(href === here) link.classList.add('active');
  });

  /* ---------- MODALS ----------
     data-modal-open="id" on a trigger, data-modal-close inside the overlay
     or dialog. Kept generic so A2–A5's confirm/edit dialogs need zero JS. */
  document.querySelectorAll('[data-modal-open]').forEach((trigger) => {
    trigger.addEventListener('click', () => {
      const modal = document.getElementById(trigger.dataset.modalOpen);
      if(modal) modal.classList.add('open');
    });
  });
  document.querySelectorAll('.modal-overlay').forEach((overlay) => {
    overlay.addEventListener('click', (e) => {
      if(e.target === overlay || e.target.closest('[data-modal-close]')) overlay.classList.remove('open');
    });
  });
  document.addEventListener('keydown', (e) => {
    if(e.key !== 'Escape') return;
    document.querySelectorAll('.modal-overlay.open').forEach((m) => m.classList.remove('open'));
  });

  /* ---------- TOASTS ----------
     window.AdminToast(message, kind) — kind is 'success' | 'error' | 'info'.
     A single stack is created lazily so pages that never call it pay nothing. */
  let stack = null;
  window.AdminToast = function(message, kind){
    if(!stack){
      stack = document.createElement('div');
      stack.className = 'toast-stack';
      document.body.appendChild(stack);
    }
    const toast = document.createElement('div');
    toast.className = 'toast' + (kind ? ` is-${kind}` : '');
    toast.textContent = message;
    stack.appendChild(toast);
    setTimeout(() => toast.remove(), 3200);
  };
})();

/* ================= LOGIN FORM =================
   Same validate-on-blur pattern as the public site's forms.js, kept as a
   separate small block (not a shared include — see admin isolation note
   above) so admin/login.html works with zero backend: any password other
   than the demo one shows the wrong-credentials banner, and a hidden field
   also traps the deliberately-blank state for the F0-style empty check. */
(function(){
  const form = document.getElementById('loginForm');
  if(!form) return;

  const emailField = form.querySelector('#loginEmail');
  const passField = form.querySelector('#loginPassword');
  const banner = document.getElementById('loginBanner');
  const submit = form.querySelector('[type="submit"]');

  function errorSlot(field){
    const wrap = field.closest('.field');
    return wrap ? wrap.querySelector('.field-error') : null;
  }
  function showError(field, message){
    field.closest('.field').classList.add('has-error');
    const slot = errorSlot(field);
    if(slot) slot.textContent = message;
    field.setAttribute('aria-invalid', 'true');
  }
  function clearError(field){
    field.closest('.field').classList.remove('has-error');
    const slot = errorSlot(field);
    if(slot) slot.textContent = '';
    field.removeAttribute('aria-invalid');
  }
  function validate(field){
    if(!field.checkValidity()){
      const label = field.dataset.label || field.name;
      showError(field, field.validity.typeMismatch ? 'Enter a valid email address.' : `${label} is required.`);
      return false;
    }
    clearError(field);
    return true;
  }

  [emailField, passField].forEach((field) => {
    field.addEventListener('blur', () => validate(field));
    field.addEventListener('input', () => { if(field.getAttribute('aria-invalid') === 'true') validate(field); });
  });

  form.setAttribute('novalidate', '');
  form.addEventListener('submit', (e) => {
    e.preventDefault();
    const ok = [emailField, passField].map(validate).every(Boolean);
    if(banner) banner.style.display = 'none';
    if(!ok) return;

    // Demo-only credential check — Phase 3 replaces this with a real POST
    // to the Laravel auth route (see TASKS.md L3.1).
    submit.disabled = true; submit.textContent = 'Signing in…';
    setTimeout(() => {
      if(passField.value !== 'demo1234'){
        if(banner) banner.style.display = 'flex';
        submit.disabled = false; submit.textContent = 'Sign In';
        passField.focus();
        return;
      }
      location.href = 'dashboard.html';
    }, 500);
  });
})();
