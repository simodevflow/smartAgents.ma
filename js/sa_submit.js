/**
 * SmartAgents Contact Form — BackendAPI Integration
 * Replace the existing form submit handler in your website with this code.
 *
 * Replace YOUR_API_TOKEN below with the token from:
 *   crm.smartagents.ma/backendapi/settings
 */

alert('In');
const BACKENDAPI_URL = 'https://crm.smartagents.ma/backendapi/formsubmit';

document.getElementById('contact-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const form   = e.target;
    const btn    = form.querySelector('button[type="submit"]');
    const errBox = document.getElementById('form-error');
    const okBox  = document.getElementById('form-success');

    // Client-side validation
    const required = ['prenom', 'nom', 'email', 'objet', 'message'];
    for (const field of required) {
        const el = form.querySelector(`[name="${field}"]`);
        if (!el || !el.value.trim()) {
            if (errBox) {
                errBox.textContent = `Veuillez remplir le champ : ${field}`;
                errBox.style.display = 'block';
            }
            el && el.focus();
            return;
        }
    }

    // GDPR check
    const gdpr = form.querySelector('[name="gdpr"]');
    if (!gdpr || !gdpr.checked) {
        if (errBox) {
            errBox.textContent = 'Veuillez accepter la politique de confidentialité.';
            errBox.style.display = 'block';
        }
        return;
    }

    // Build payload
    const data = {
        prenom:     form.prenom.value.trim(),
        nom:        form.nom.value.trim(),
        email:      form.email.value.trim(),
        telephone:  form.telephone?.value.trim() || '',
        entreprise: form.entreprise?.value.trim() || '',
        secteur:    form.secteur?.value || '',
        objet:      form.objet.value,
        message:    form.message.value.trim(),
        budget:     form.budget?.value || '',
        gdpr:       '1',
    };

    // Show loading
    const originalText = btn.innerHTML;
    btn.innerHTML      = 'Envoi en cours…';
    btn.disabled       = true;
    if (errBox) errBox.style.display = 'none';

    try {
        const res = await fetch(BACKENDAPI_URL, {
            method: 'POST',
            headers: {
                'Content-Type':  'application/json',
                'X-API-TOKEN':   API_TOKEN,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify(data),
        });

        const json = await res.json();

        if (res.ok && json.status === 'success') {
            // Success!
            form.reset();
            if (okBox) {
                okBox.style.display = 'block';
                okBox.textContent   = '✓ Votre demande a bien été reçue. Nous vous répondons dans 24h.';
            }
        } else if (res.status === 429) {
            throw new Error('Trop de demandes. Veuillez réessayer dans une heure.');
        } else if (res.status === 422) {
            const firstError = json.errors
                ? Object.values(json.errors)[0][0]
                : 'Veuillez vérifier vos informations.';
            throw new Error(firstError);
        } else {
            throw new Error(json.message || 'Une erreur est survenue.');
        }

    } catch (err) {
        if (errBox) {
            errBox.textContent   = err.message;
            errBox.style.display = 'block';
        }
    } finally {
        btn.innerHTML = originalText;
        btn.disabled  = false;
    }
});

/**
 * Add these elements near your form button:
 *
 * <div id="form-error"   style="display:none;color:#e63946;margin-top:12px;font-size:0.875rem;"></div>
 * <div id="form-success" style="display:none;color:#2dc653;margin-top:12px;font-size:0.875rem;"></div>
 *
 * Also add a honeypot hidden field inside your <form>:
 * <input type="text" name="_honey" style="display:none;" tabindex="-1" autocomplete="off">
 */
