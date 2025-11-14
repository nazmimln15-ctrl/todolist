// main.js
// Validasi form tambah dan edit task (client-side)
// Cara pakai: sertakan <script src="assets/js/main.js"></script> sebelum </body>

(function(){
  'use strict';

  // Konfigurasi aturan
  const RULES = {
    title: { required: true, min: 2, max: 200 },
    description: { required: false, max: 2000 }
  };

  // Utility: buat atau ambil elemen pesan error di bawah input
  function getErrorNode(input) {
    let next = input.nextElementSibling;
    if (next && next.classList.contains('field-error')) return next;
    const err = document.createElement('div');
    err.className = 'field-error';
    err.style.color = '#b91c1c';
    err.style.fontSize = '13px';
    err.style.marginTop = '6px';
    input.parentNode.insertBefore(err, input.nextSibling);
    return err;
  }

  // Validasi satu field, mengembalikan {ok: bool, msg: string}
  function validateField(name, value) {
    const r = RULES[name];
    if (!r) return { ok: true, msg: '' };

    const v = (value || '').trim();
    if (r.required && v.length === 0) return { ok: false, msg: 'Kolom ini wajib diisi' };
    if (r.min && v.length < r.min) return { ok: false, msg: `Minimal ${r.min} karakter` };
    if (r.max && v.length > r.max) return { ok: false, msg: `Maksimal ${r.max} karakter` };
    return { ok: true, msg: '' };
  }

  // Validasi form (formElement harus mengandung input[name="title"] dan textarea[name="description"])
  function validateForm(form) {
    let ok = true;

    const title = form.querySelector('[name="title"]');
    const desc  = form.querySelector('[name="description"]');

    // Title
    const vt = validateField('title', title ? title.value : '');
    const errTitle = getErrorNode(title);
    if (!vt.ok) {
      errTitle.textContent = vt.msg;
      title.classList.add('input-invalid');
      ok = false;
    } else {
      errTitle.textContent = '';
      title.classList.remove('input-invalid');
    }

    // Description
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

  // Pasang handler submit ke form-form target
  function attachValidation(selector) {
    const forms = document.querySelectorAll(selector);
    forms.forEach(form => {
      // validasi on submit
      form.addEventListener('submit', function(e){
        if (!validateForm(form)) {
          e.preventDefault();
          e.stopPropagation();
          // fokus ke input title jika error
          const t = form.querySelector('[name="title"]');
          if (t && t.classList.contains('input-invalid')) t.focus();
        }
      });

      // live validation pada input/textarea
      const fields = form.querySelectorAll('[name="title"], [name="description"]');
      fields.forEach(f => {
        f.addEventListener('input', function(){ validateForm(form); });
      });
    });
  }

  // Inisialisasi saat DOM siap
  document.addEventListener('DOMContentLoaded', function(){
    // Pasang ke form tambah / edit. Sesuaikan selector jika berbeda.
    attachValidation('form');
  });

  // Expose fungsi untuk testing/debug (opsional)
  window.TodoValidation = {
    validateField,
    validateForm
  };
})();
