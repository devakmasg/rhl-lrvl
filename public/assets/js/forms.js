/* ================= FORMS (shared: contact enquiry + partner submission) =================
   Generic client-side validation for any <form data-validate>. Nothing here
   knows what the form is for — fields declare their own rules in the markup
   (required, type="email", pattern, minlength), so a new form needs no code.

   This deliberately validates on top of the browser's own constraint API
   rather than replacing it: `novalidate` on the form suppresses only the
   native BUBBLE, and every rule is still read back through
   checkValidity()/validity, so the rules live in one place — the HTML — and
   cannot drift from what this file believes them to be.

   There is no backend on this template. Submit is intercepted and answered
   with a success state, so the interaction is complete and testable, and the
   place to POST is marked with a single obvious TODO. */
(function(){
  const forms = document.querySelectorAll('form[data-validate]');
  if(!forms.length) return;

  /* Messages by failure kind rather than by field, so every form speaks the
     same language and a new field inherits sensible copy for free. A field can
     still override with data-error-<kind>. */
  function messageFor(field){
    const v = field.validity;
    const label = (field.dataset.label || field.name || 'This field');
    if(v.valueMissing) return field.dataset.errorRequired || `${label} is required.`;
    if(v.typeMismatch && field.type === 'email') return 'Enter a valid email address, e.g. name@company.com.';
    if(v.patternMismatch) return field.dataset.errorPattern || `Enter a valid ${label.toLowerCase()}.`;
    if(v.tooShort) return `${label} needs at least ${field.minLength} characters.`;
    return 'Please check this field.';
  }

  /* Bangladeshi mobile numbers: 11 digits starting 01, or with the +880/880
     country code in place of the leading 0 — the operator digit (3–9) rules
     out the few ranges that were never issued. Formatting characters (spaces,
     hyphens, parentheses) are stripped before the check so "+880 1711-234567"
     and "01711234567" both validate the same way; the pattern attribute above
     only catches stray letters, this catches the wrong number of digits. */
  function isValidBdPhone(raw){
    const digits = raw.replace(/[^\d]/g, '');
    const local = digits.startsWith('880') ? digits.slice(3) : digits.startsWith('0') ? digits.slice(1) : digits;
    return /^1[3-9]\d{8}$/.test(local);
  }

  function errorSlot(field){
    const wrap = field.closest('.field');
    return wrap ? wrap.querySelector('.field-error') : null;
  }

  function showError(field, message){
    const wrap = field.closest('.field');
    const slot = errorSlot(field);
    if(wrap) wrap.classList.add('has-error');
    if(slot) slot.textContent = message;
    // aria-invalid is what actually tells a screen reader the control is bad;
    // the red border only tells people who can see it.
    field.setAttribute('aria-invalid', 'true');
  }

  function clearError(field){
    const wrap = field.closest('.field');
    const slot = errorSlot(field);
    if(wrap) wrap.classList.remove('has-error');
    if(slot) slot.textContent = '';
    field.removeAttribute('aria-invalid');
  }

  function validate(field){
    if(field.disabled || field.type === 'hidden') return true;
    if(!field.checkValidity()){
      showError(field, messageFor(field));
      return false;
    }
    if('bdPhone' in field.dataset && field.value.trim() && !isValidBdPhone(field.value)){
      showError(field, 'Enter a valid Bangladeshi mobile number, e.g. +880 1XXX-XXXXXX.');
      return false;
    }
    clearError(field);
    return true;
  }

  forms.forEach((form) => {
    // Suppress the native bubbles only — the constraints themselves stay live.
    form.setAttribute('novalidate', '');
    const status = form.querySelector('.form-status');
    const submit = form.querySelector('[type="submit"]');
    const fields = () => Array.from(form.elements).filter((el) => el.willValidate);

    /* Re-check on blur, but only re-check on input for a field ALREADY marked
       bad. Validating while someone is still typing their first character
       shouts at them for an incomplete email they are halfway through. */
    form.addEventListener('blur', (e) => {
      if(e.target.willValidate) validate(e.target);
    }, true);
    form.addEventListener('input', (e) => {
      if(e.target.willValidate && e.target.getAttribute('aria-invalid') === 'true') validate(e.target);
    });

    form.addEventListener('submit', (e) => {
      e.preventDefault();
      const bad = fields().filter((field) => !validate(field));

      if(bad.length){
        if(status){
          status.className = 'form-status is-bad';
          status.textContent = bad.length === 1
            ? 'One field needs attention before this can be sent.'
            : `${bad.length} fields need attention before this can be sent.`;
        }
        bad[0].focus(); // land the caret on the first problem, not the last
        return;
      }

      // TODO: POST here (fetch to your endpoint / form service). Everything
      // below just plays back the success state this template needs.
      if(submit){ submit.disabled = true; submit.dataset.label = submit.textContent; submit.textContent = 'Sending…'; }

      setTimeout(() => {
        // A lead form (data-thanks) sends the visitor on to a dedicated
        // confirmation page — the pattern a real POST-redirect-GET backend
        // will follow in Phase 3. Forms without it (e.g. a quick inline
        // search) just show the status message in place.
        if(form.dataset.thanks){
          const url = new URL(form.dataset.thanks, location.href);
          if(form.dataset.success) url.searchParams.set('m', form.dataset.success);
          location.href = url.toString();
          return;
        }
        if(status){
          status.className = 'form-status is-ok';
          status.textContent = form.dataset.success || 'Thank you — your message has been received. We usually reply within two working days.';
        }
        form.reset();
        fields().forEach(clearError);
        if(submit){ submit.disabled = false; submit.textContent = submit.dataset.label || 'Send'; }
        if(typeof ScrollTrigger !== 'undefined') ScrollTrigger.refresh(); // the status message changes page height
      }, 600);
    });
  });
})();
