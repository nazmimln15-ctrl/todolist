// main.js
(function(){
  'use strict';

  const RULES = {
    title: { required: true, min: 2, max: 200 },
    description: { required: false, max: 2000 }
  };

  function getErrorNode(input) {
    if (!input) return null;
    let next = input.nextElementSibling;
    if (next && next.classList.contains('field-error')) return next;
    const err = document.createElement('div');
    err.className = 'field-error';
    input.parentNode.insertBefore(err, input.nextSibling);
    return err;
  }

  function validateField(name, value) {
    const r = RULES[name];
    if (!r) return { ok: true, msg: '' };
    const v = (value || '').trim();
    if (r.required && v.length === 0) return { ok: false, msg: 'Kolom ini wajib diisi' };
    if (r.min && v.length < r.min) return { ok: false, msg: `Minimal ${r.min} karakter` };
    if (r.max && v.length > r.max) return { ok: false, msg: `Maksimal ${r.max} karakter` };
    return { ok: true, msg: '' };
  }

  function validateForm(form) {
    let ok = true;
    const title = form.querySelector('[name="title"]');
    const desc  = form.querySelector('[name="description"]');

    if (title) {
      const vt = validateField('title', title.value);
      const errTitle = getErrorNode(title);
      if (!vt.ok) {
        errTitle.textContent = vt.msg;
        title.classList.add('input-invalid');
        ok = false;
      } else {
        errTitle.textContent = '';
        title.classList.remove('input-invalid');
      }
    }

    if (desc) {
      const vd = validateField('description', desc.value);
      const errDesc = getErrorNode(desc);
      if (!vd.ok) {
        errDesc.textContent = vd.msg;
        desc.classList.add('input-invalid');
        ok = false;
      } else {
        errDesc.textContent = '';
        desc.classList.remove('input-invalid');
      }
    }

    return ok;
  }

  function attachValidation(selector) {
    const forms = document.querySelectorAll(selector);
    forms.forEach(form => {
      form.addEventListener('submit', function(e){
        if (!validateForm(form)) {
          e.preventDefault();
          e.stopPropagation();
          const t = form.querySelector('[name="title"]');
          if (t && t.classList.contains('input-invalid')) t.focus();
        }
      });

      const fields = form.querySelectorAll('[name="title"], [name="description"]');
      fields.forEach(f => {
        f.addEventListener('input', function(){ validateForm(form); });
      });
    });
  }

  document.addEventListener('DOMContentLoaded', function(){
    attachValidation('form');
  });

  window.TodoValidation = {
    validateField,
    validateForm
  };
})();
