//   const form = document.querySelector('#contact-form');
//   if (form) {
//     form.addEventListener('submit', (e) => {
//       e.preventDefault();
//       const btn = form.querySelector('button[type="submit"]');
//       const orig = btn.innerHTML;
//       btn.innerHTML = '<span>Envoi…</span>';
//       btn.disabled = true;
//       setTimeout(() => {
//         btn.innerHTML = '<span>Envoyé ✓</span>';
//         form.reset();
//         setTimeout(() => { btn.innerHTML = orig; btn.disabled = false; }, 4000);
//       }, 1500);
//     });
//   }


  const form = document.querySelector('#contact-form');
if (form) {
    alert('Form script loaded'); // Debug: confirm script is running
  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const btn  = form.querySelector('button[type="submit"]');
    const orig = btn.innerHTML;

    // ── Config ────────────────────────────────────────────────
    const BACKENDAPI_URL = 'https://crm.smartagents.ma/backendapi/formsubmit';
    const API_TOKEN      = 'PASTE_YOUR_TOKEN_FROM_SETTINGS_HERE';

    // ── Loading state ─────────────────────────────────────────
    btn.innerHTML = '<span>Envoi…</span>';
    btn.disabled  = true;

    // ── Build payload from form fields ────────────────────────
    const data = {
      prenom:     form.querySelector('[name="prenom"]')?.value.trim()     || '',
      nom:        form.querySelector('[name="nom"]')?.value.trim()        || '',
      email:      form.querySelector('[name="email"]')?.value.trim()      || '',
      telephone:  form.querySelector('[name="telephone"]')?.value.trim()  || '',
      entreprise: form.querySelector('[name="entreprise"]')?.value.trim() || '',
      secteur:    form.querySelector('[name="secteur"]')?.value           || '',
      objet:      form.querySelector('[name="objet"]')?.value             || '',
      message:    form.querySelector('[name="message"]')?.value.trim()    || '',
      budget:     form.querySelector('[name="budget"]')?.value            || '',
      gdpr:       form.querySelector('[name="gdpr"]')?.checked ? '1' : '0',
      _honey:     form.querySelector('[name="_honey"]')?.value            || '',
    };

    // ── Client-side GDPR check ────────────────────────────────
    if (data.gdpr !== '1') {
      showError('Veuillez accepter la politique de confidentialité.');
      btn.innerHTML = orig;
      btn.disabled  = false;
      return;
    }

    // ── Submit to BackendAPI ───────────────────────────────────
    try {
      const res  = await fetch(BACKENDAPI_URL, {
        method: 'POST',
        headers: {
          'Content-Type':     'application/json',
          'X-API-TOKEN':      API_TOKEN,
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify(data),
      });

      const json = await res.json();

      if (res.ok && json.status === 'success') {
        // ── Success ──────────────────────────────────────────
        btn.innerHTML = '<span>Envoyé ✓</span>';
        form.reset();
        showSuccess('Votre demande a bien été reçue. Nous vous répondons dans 24h ouvrées.');
        setTimeout(() => {
          btn.innerHTML = orig;
          btn.disabled  = false;
          hideMessages();
        }, 4000);

      } else if (res.status === 429) {
        throw new Error('Trop de demandes. Veuillez réessayer dans une heure.');
      } else if (res.status === 422) {
        const firstError = json.errors
          ? Object.values(json.errors)[0][0]
          : 'Veuillez vérifier vos informations.';
        throw new Error(firstError);
      } else if (res.status === 403) {
        throw new Error('Erreur de sécurité. Veuillez recharger la page.');
      } else {
        throw new Error(json.message || 'Une erreur est survenue. Veuillez réessayer.');
      }

    } catch (err) {
      showError(err.message);
      btn.innerHTML = orig;
      btn.disabled  = false;
    }
  });
}

// ── Helper: show/hide inline messages ─────────────────────────────────
function showError(msg) {
  let el = document.getElementById('form-error');
  if (!el) {
    el = document.createElement('p');
    el.id = 'form-error';
    el.style.cssText = 'color:#e63946;font-size:0.8rem;margin-top:12px;';
    document.querySelector('#contact-form button[type="submit"]').insertAdjacentElement('afterend', el);
  }
  el.textContent    = '⚠ ' + msg;
  el.style.display  = 'block';
  const ok = document.getElementById('form-success');
  if (ok) ok.style.display = 'none';
}

function showSuccess(msg) {
  let el = document.getElementById('form-success');
  if (!el) {
    el = document.createElement('p');
    el.id = 'form-success';
    el.style.cssText = 'color:#2dc653;font-size:0.8rem;margin-top:12px;';
    document.querySelector('#contact-form button[type="submit"]').insertAdjacentElement('afterend', el);
  }
  el.textContent    = '✓ ' + msg;
  el.style.display  = 'block';
  const err = document.getElementById('form-error');
  if (err) err.style.display = 'none';
}

function hideMessages() {
  ['form-error', 'form-success'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.style.display = 'none';
  });
}