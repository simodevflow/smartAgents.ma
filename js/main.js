// ============================================
// SMARTAGENTS INTERNATIONAL - i18n ENGINE
// ============================================
// To add a new language:
// 1. Create /lang/XX.json (copy en.json, translate values)
// 2. Add the language code to AVAILABLE_LANGS below
// 3. Add a <button> in the lang switcher with data-lang="XX"
// That's it. No other files to touch.
// ============================================

const AVAILABLE_LANGS = ['fr', 'en', 'es', 'de', 'cz', 'it', 'pt'];
const DEFAULT_LANG = 'fr';

async function loadLang(lang) {
  if (!AVAILABLE_LANGS.includes(lang)) lang = DEFAULT_LANG;
  try {
    const base = getBasePath();
    const res = await fetch(`${base}lang/${lang}.json?v=1`);
    if (!res.ok) throw new Error('Lang file not found');
    return await res.json();
  } catch (e) {
    console.warn('i18n: falling back to default language');
    const base = getBasePath();
    const res = await fetch(`${base}lang/${DEFAULT_LANG}.json?v=1`);
    return await res.json();
  }
}

function getBasePath() {
  const path = window.location.pathname;
  return path.includes('/pages/') ? '../' : './';
}

function applyTranslations(t) {
  document.querySelectorAll('[data-i18n]').forEach(el => {
    const key = el.getAttribute('data-i18n');
    const val = getNestedValue(t, key);
    if (val !== undefined) el.textContent = val;
  });
  document.querySelectorAll('[data-i18n-html]').forEach(el => {
    const key = el.getAttribute('data-i18n-html');
    const val = getNestedValue(t, key);
    if (val !== undefined) el.innerHTML = val;
  });
  document.documentElement.setAttribute('lang', t.lang);
}

function getNestedValue(obj, path) {
  return path.split('.').reduce((acc, key) => acc?.[key], obj);
}

function setActiveLangButton(lang) {
  document.querySelectorAll('.lang-btn').forEach(btn => {
    btn.classList.toggle('active', btn.getAttribute('data-lang') === lang);
  });
}

async function switchLang(lang) {
  localStorage.setItem('sa_lang', lang);
  const t = await loadLang(lang);
  applyTranslations(t);
  setActiveLangButton(lang);
}

async function initI18n() {
  const saved = localStorage.getItem('sa_lang');
  const browser = navigator.language?.slice(0, 2);
  const lang = saved || (AVAILABLE_LANGS.includes(browser) ? browser : DEFAULT_LANG);
  const t = await loadLang(lang);
  applyTranslations(t);
  setActiveLangButton(lang);
  document.querySelectorAll('.lang-btn').forEach(btn => {
    btn.addEventListener('click', () => switchLang(btn.getAttribute('data-lang')));
  });
}

// ============================================
// MAIN APP
// ============================================

document.addEventListener('DOMContentLoaded', async () => {

  await initI18n();

  const nav = document.querySelector('.nav');
  if (nav) {
    window.addEventListener('scroll', () => nav.classList.toggle('scrolled', window.scrollY > 60));
  }

  const burger = document.querySelector('.nav-burger');
  const mobileNav = document.querySelector('.mobile-nav');
  const mobileClose = document.querySelector('.mobile-close');
  if (burger && mobileNav) {
    burger.addEventListener('click', () => mobileNav.classList.add('open'));
    mobileClose?.addEventListener('click', () => mobileNav.classList.remove('open'));
    mobileNav.querySelectorAll('a').forEach(a => a.addEventListener('click', () => mobileNav.classList.remove('open')));
  }

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); observer.unobserve(e.target); } });
  }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
  document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));

  const backTop = document.querySelector('.back-top');
  if (backTop) {
    window.addEventListener('scroll', () => backTop.classList.toggle('visible', window.scrollY > 400));
    backTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
  }

  const cookieBanner = document.querySelector('.cookie-banner');
  if (cookieBanner && !localStorage.getItem('sa_cookies_accepted')) {
    cookieBanner.style.display = 'flex';
    document.querySelector('.btn-accept')?.addEventListener('click', () => {
      localStorage.setItem('sa_cookies_accepted', '1');
      cookieBanner.style.transform = 'translateY(100%)';
      setTimeout(() => cookieBanner.remove(), 500);
    });
    document.querySelector('.btn-decline')?.addEventListener('click', () => {
      localStorage.setItem('sa_cookies_accepted', '0');
      cookieBanner.style.transform = 'translateY(100%)';
      setTimeout(() => cookieBanner.remove(), 500);
    });
  } else if (cookieBanner) {
    cookieBanner.remove();
  }




  // ── COUNTER ANIMATION ──
  const counters = document.querySelectorAll('.metric-num[data-target]');
  const counterObserver = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        const target = parseInt(e.target.dataset.target);
        const suffix = e.target.dataset.suffix || '';
        let current = 0;
        const step = target / 50;
        const timer = setInterval(() => {
          current += step;
          if (current >= target) { current = target; clearInterval(timer); }
          e.target.textContent = Math.floor(current) + suffix;
        }, 30);
        counterObserver.unobserve(e.target);
      }
    });
  }, { threshold: 0.5 });
  counters.forEach(c => counterObserver.observe(c));

});
