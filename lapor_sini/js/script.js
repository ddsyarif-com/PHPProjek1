// ===== LAPOR SINI - SCHOOL EDITION =====
// Script Utama

// Scroll reveal effect
function initScrollReveal() {
  const elemen = document.querySelectorAll('.reveal');
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry, i) => {
      if (entry.isIntersecting) {
        setTimeout(() => {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
        }, i * 80);
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });

  elemen.forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(28px)';
    el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    observer.observe(el);
  });
}

// Counter animation
function animasiAngka(el, target, durasi = 1500) {
  let mulai = 0;
  const langkah = target / (durasi / 16);
  const update = () => {
    mulai += langkah;
    if (mulai < target) {
      el.textContent = Math.floor(mulai).toLocaleString('id-ID');
      requestAnimationFrame(update);
    } else {
      el.textContent = target.toLocaleString('id-ID');
    }
  };
  requestAnimationFrame(update);
}

function initCounter() {
  const counters = document.querySelectorAll('[data-count]');
  if (!counters.length) return;
  const obs = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        animasiAngka(e.target, parseInt(e.target.dataset.count));
        obs.unobserve(e.target);
      }
    });
  }, { threshold: 0.5 });
  counters.forEach(c => obs.observe(c));
}

document.addEventListener('DOMContentLoaded', () => {
  initScrollReveal();
  initCounter();
});