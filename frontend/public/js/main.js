// Main JavaScript for YouthConnekt Sahel 2025

// Lightweight, modular UI helpers and form handlers.

document.addEventListener('DOMContentLoaded', () => {
    initNavbar();
    initNewsletter();
    initScrollToTop();
    initAnimations();
    initCounterAnimation();
    initCountdown();
    initContactForm();
    initRegistrationForm();
});

function initNavbar() {
    const navbar = document.querySelector('.navbar');
    if (!navbar) return;
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.style.background = 'rgba(255,255,255,0.98)';
            navbar.style.boxShadow = '0 4px 20px rgba(0,0,0,0.1)';
        } else {
            navbar.style.background = 'rgba(255,255,255,0.95)';
            navbar.style.boxShadow = 'none';
        }
    });
}

function initCountdown() {
    const eventDate = new Date('2025-10-13T09:00:00').getTime();
    function update() {
        const now = Date.now();
        const d = eventDate - now;
        if (d <= 0) return;
        const days = Math.floor(d / (1000 * 60 * 60 * 24));
        const hours = Math.floor((d % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((d % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((d % (1000 * 60)) / 1000);
        const ids = ['days', 'hours', 'minutes', 'seconds'];
        [days, hours, minutes, seconds].forEach((v, i) => { const el = document.getElementById(ids[i]); if (el) el.textContent = v; });
    }
    update();
    setInterval(update, 1000);
}

function initNewsletter() {
    const f = document.getElementById('newsletter-form');
    if (!f) return;
    f.addEventListener('submit', (e) => {
        e.preventDefault();
        const email = f.querySelector('input[type="email"]').value;
        if (!validateEmail(email)) { showNotification('Veuillez entrer une adresse email valide.', 'warning'); return; }
        const btn = f.querySelector('button[type="submit"]'); const orig = btn.innerHTML; btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Inscription...'; btn.disabled = true;
        setTimeout(() => { f.innerHTML = '<div class="alert alert-success mb-0"><i class="fas fa-check-circle me-2"></i>Merci ! Vous êtes maintenant abonné(e) à notre newsletter.</div>'; showNotification('Inscription réussie !', 'success'); }, 1200);
    });
}

function initContactForm() {
    const f = document.getElementById('contact-form'); if (!f) return;
    f.addEventListener('submit', (e) => {
        e.preventDefault();
        const btn = f.querySelector('button[type="submit"]'); const orig = btn.innerHTML; btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Envoi en cours...'; btn.disabled = true;
        setTimeout(() => {
            const a = document.createElement('div'); a.className = 'alert alert-success mt-3'; a.innerHTML = '<i class="fas fa-check-circle me-2"></i><strong>Message envoyé !</strong> Nous vous répondrons bientôt.';
            f.appendChild(a);
            f.reset();
            btn.innerHTML = orig; btn.disabled = false;
            showNotification('Message envoyé avec succès !', 'success');
            a.scrollIntoView({ behavior: 'smooth' });
        }, 1000);
    });
}

function initRegistrationForm() {
    const f = document.getElementById('registration-form'); if (!f) return;
    f.addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = f.querySelector('button[type="submit"]'); const orig = btn.innerHTML; btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Inscription en cours...'; btn.disabled = true;
        const fd = new FormData(f);
        const interests = fd.getAll('interests[]');
        const payload = {
            firstName: fd.get('firstName') || '',
            lastName: fd.get('lastName') || '',
            email: fd.get('email') || '',
            phone: fd.get('phone') || '',
            country: fd.get('country') || '',
            city: fd.get('city') || '',
            occupation: fd.get('occupation') || '',
            organization: fd.get('organization') || '',
            interests: interests,
            experience: fd.get('experience') || '',
            motivation: fd.get('motivation') || '',
            registrationType: fd.get('registrationType') || ''
        };
        try {
            const r = await fetch('/participants/register', { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }, body: JSON.stringify(payload) });
            const data = await r.json();
            if (r.ok) {
                const a = document.createElement('div'); a.className = 'alert alert-success mt-3'; a.innerHTML = `<i class="fas fa-check-circle me-2"></i><strong>Inscription réussie !</strong> ${data.message || ''}`;
                f.appendChild(a);
                f.reset();
                showNotification('Inscription réussie !', 'success');
                a.scrollIntoView({ behavior: 'smooth' });
            } else {
                showNotification(data.message || 'Erreur lors de l\'inscription.', 'danger');
            }
        } catch (err) {
            console.error('Registration error', err);
            showNotification('Impossible de contacter le serveur. Réessayez plus tard.', 'danger');
        } finally {
            btn.innerHTML = orig; btn.disabled = false;
        }
    });
}

function initScrollToTop() {
    const btn = document.createElement('button'); btn.innerHTML = '<i class="fas fa-arrow-up"></i>'; btn.className = 'btn btn-primary scroll-to-top';
    btn.style.cssText = 'position:fixed;bottom:30px;right:30px;width:50px;height:50px;border-radius:50%;display:none;z-index:1000;border:none;box-shadow:0 4px 15px rgba(0,0,0,0.2);transition:all 0.3s ease;';
    document.body.appendChild(btn);
    window.addEventListener('scroll', () => { if (window.scrollY > 300) { btn.style.display = 'block'; btn.style.transform = 'scale(1)'; } else { btn.style.transform = 'scale(0)'; setTimeout(() => { if (window.scrollY <= 300) btn.style.display = 'none'; }, 300); } });
    btn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
}

function initAnimations() {
    const els = document.querySelectorAll('.card, .feature-card, .section-title');
    if (els.length > 0) {
        const obs = new IntersectionObserver(entries => { entries.forEach(entry => { if (entry.isIntersecting) { entry.target.classList.add('fade-in-up'); obs.unobserve(entry.target); } }); }, { threshold: 0.1 });
        els.forEach(el => obs.observe(el));
    }
    document.querySelectorAll('a[href^="#"]').forEach(a => a.addEventListener('click', function (e) { e.preventDefault(); const t = document.querySelector(this.getAttribute('href')); if (t) t.scrollIntoView({ behavior: 'smooth', block: 'start' }); }));
}

function animateCounters() {
    const counters = document.querySelectorAll('.stat-number[data-counter]');
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-counter')) || 0;
        const duration = 2000;
        const inc = target / (duration / 16);
        let cur = 0;
        const update = () => { if (cur < target) { cur += inc; counter.textContent = Math.floor(cur); requestAnimationFrame(update); } else counter.textContent = target; };
        update();
    });
}

function initCounterAnimation() {
    const s = document.querySelector('.stats-section'); if (!s) return;
    const obs = new IntersectionObserver(entries => { entries.forEach(e => { if (e.isIntersecting) { animateCounters(); obs.unobserve(e.target); } }); }, { threshold: 0.5 });
    obs.observe(s);
}

function showNotification(message, type = 'info') {
    const n = document.createElement('div'); n.className = `alert alert-${type} notification`;
    n.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;max-width:350px;animation:slideInRight 0.3s ease;box-shadow:0 4px 15px rgba(0,0,0,0.1);backdrop-filter:blur(10px);border:none;';
    n.innerHTML = `<div class="d-flex align-items-center"><span>${message}</span><button type="button" class="btn-close ms-auto" aria-label="Close"></button></div>`;
    document.body.appendChild(n);
    setTimeout(() => { n.style.animation = 'slideOutRight 0.3s ease'; setTimeout(() => n.remove(), 300); }, 5000);
    const cb = n.querySelector('.btn-close'); if (cb) cb.addEventListener('click', () => { n.style.animation = 'slideOutRight 0.3s ease'; setTimeout(() => n.remove(), 300); });
}

function validateEmail(e) { const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; return re.test(e); }
function formatDate(d) { return new Date(d).toLocaleDateString('fr-FR', { year: 'numeric', month: 'long', day: 'numeric' }); }

// Append minimal CSS animations used by the script
const style = document.createElement('style');
style.textContent = `
@keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
@keyframes slideInRight { from { opacity: 0; transform: translateX(100%); } to { opacity: 1; transform: translateX(0); } }
@keyframes slideOutRight { from { opacity: 1; transform: translateX(0); } to { opacity: 0; transform: translateX(100%); } }
.fade-in-up { animation: fadeInUp 0.8s ease-out; }
`;
document.head.appendChild(style);