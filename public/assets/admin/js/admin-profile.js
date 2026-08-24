/* ================= ADMIN — Profile settings (A5.2) ================= */
(function(){
  const profileForm = document.getElementById('profileForm');
  if(!profileForm) return;

  /* ---------- avatar ---------- */
  const avatarBtn = document.getElementById('avatarBtn');
  const avatarInput = document.getElementById('avatarInput');
  const avatarPreview = document.getElementById('avatarPreview');
  avatarBtn.addEventListener('click', () => avatarInput.click());
  avatarInput.addEventListener('change', () => {
    if(avatarInput.files[0]) avatarPreview.src = URL.createObjectURL(avatarInput.files[0]);
  });

  /* ---------- shared field validation helpers ---------- */
  function errorSlot(field){ const w = field.closest('.field'); return w ? w.querySelector('.field-error') : null; }
  function showError(field, message){
    field.closest('.field').classList.add('has-error');
    const slot = errorSlot(field); if(slot) slot.textContent = message;
    field.setAttribute('aria-invalid', 'true');
  }
  function clearError(field){
    field.closest('.field').classList.remove('has-error');
    const slot = errorSlot(field); if(slot) slot.textContent = '';
    field.removeAttribute('aria-invalid');
  }
  function validate(field){
    if(!field.checkValidity()){
      const label = field.dataset.label || field.name;
      const v = field.validity;
      showError(field, v.typeMismatch ? 'Enter a valid email address.' : v.tooShort ? `${label} needs at least ${field.minLength} characters.` : `${label} is required.`);
      return false;
    }
    clearError(field);
    return true;
  }

  /* ---------- profile form ---------- */
  profileForm.setAttribute('novalidate', '');
  [profileForm.querySelector('#profName'), profileForm.querySelector('#profEmail')].forEach((f) => {
    f.addEventListener('blur', () => validate(f));
    f.addEventListener('input', () => { if(f.getAttribute('aria-invalid') === 'true') validate(f); });
  });
  profileForm.addEventListener('submit', (e) => {
    e.preventDefault();
    const ok = [profileForm.querySelector('#profName'), profileForm.querySelector('#profEmail')].map(validate).every(Boolean);
    if(!ok) return;
    document.querySelector('.sb-user-name').textContent = profileForm.querySelector('#profName').value;
    AdminToast('Profile updated.', 'success');
  });

  /* ---------- password form ---------- */
  const pwForm = document.getElementById('passwordForm');
  const pwCurrent = pwForm.querySelector('#pwCurrent');
  const pwNew = pwForm.querySelector('#pwNew');
  const pwConfirm = pwForm.querySelector('#pwConfirm');

  function validatePw(field){
    if(!field.checkValidity()) return validate(field);
    if(field === pwConfirm && pwConfirm.value !== pwNew.value){
      showError(pwConfirm, 'Passwords do not match.');
      return false;
    }
    clearError(field);
    return true;
  }
  [pwCurrent, pwNew, pwConfirm].forEach((f) => {
    f.addEventListener('blur', () => validatePw(f));
    f.addEventListener('input', () => { if(f.getAttribute('aria-invalid') === 'true') validatePw(f); });
  });

  pwForm.setAttribute('novalidate', '');
  pwForm.addEventListener('submit', (e) => {
    e.preventDefault();
    const ok = [pwCurrent, pwNew, pwConfirm].map(validatePw).every(Boolean);
    if(!ok) return;
    // Demo-only — Phase 3 replaces this with a real password-change endpoint (TASKS.md L3.1).
    if(pwCurrent.value !== 'demo1234'){
      showError(pwCurrent, 'Current password is incorrect.');
      return;
    }
    AdminToast('Password updated.', 'success');
    pwForm.reset();
    [pwCurrent, pwNew, pwConfirm].forEach(clearError);
  });
})();
