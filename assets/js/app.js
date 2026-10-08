/* ============================================================
   Vanegas Royal — interacciones y animaciones
   ============================================================ */
(function () {
  'use strict';
  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const isTouch = window.matchMedia('(hover: none), (pointer: coarse)').matches;

  /* ---------- Carrito (Alpine store + localStorage) ---------- */
  const STORE_KEY = 'vr_cart';
  function loadCart() {
    try { return JSON.parse(localStorage.getItem(STORE_KEY)) || []; }
    catch (e) { return []; }
  }

  document.addEventListener('alpine:init', () => {
    Alpine.store('cart', {
      items: loadCart(),
      open: false,
      save() { localStorage.setItem(STORE_KEY, JSON.stringify(this.items)); },
      add(p, qty = 1) {
        const found = this.items.find(i => i.id === p.id);
        if (found) { found.qty += qty; }
        else { this.items.push({ id: p.id, nombre: p.nombre, precio: p.precio, imagen: p.imagen, qty }); }
        this.save();
        this.flash();
      },
      remove(id) { this.items = this.items.filter(i => i.id !== id); this.save(); },
      setQty(id, q) {
        const it = this.items.find(i => i.id === id);
        if (!it) return;
        it.qty = Math.max(1, parseInt(q) || 1);
        this.save();
      },
      inc(id) { const it = this.items.find(i => i.id === id); if (it) { it.qty++; this.save(); } },
      dec(id) { const it = this.items.find(i => i.id === id); if (it) { it.qty = Math.max(1, it.qty - 1); this.save(); } },
      clear() { this.items = []; this.save(); },
      get count() { return this.items.reduce((s, i) => s + i.qty, 0); },
      get total() { return this.items.reduce((s, i) => s + i.precio * i.qty, 0); },
      money(n) { return '$' + (n | 0).toLocaleString('es-CO'); },
      flash() {
        this.open = true;
        clearTimeout(this._t);
        this._t = setTimeout(() => { this.open = false; }, 2600);
      },
      // mensaje de WhatsApp con el carrito
      waMessage(numero) {
        let m = 'Hola Vanegas Royal, quiero pedir:%0A%0A';
        this.items.forEach(i => { m += `• ${i.nombre} x${i.qty} — ${this.money(i.precio * i.qty)}%0A`; });
        m += `%0ATotal: ${this.money(this.total)}`;
        return 'https://wa.me/' + numero + '?text=' + m;
      }
    });
  });

  /* ---------- Cursor custom ---------- */
  function initCursor() {
    if (isTouch) return;
    const dot = document.createElement('div');
    const ring = document.createElement('div');
    dot.className = 'cursor-dot'; ring.className = 'cursor-ring';
    document.body.append(dot, ring);
    let mx = innerWidth / 2, my = innerHeight / 2, rx = mx, ry = my;
    addEventListener('mousemove', e => {
      mx = e.clientX; my = e.clientY;
      dot.style.transform = `translate(${mx}px,${my}px) translate(-50%,-50%)`;
    });
    (function loop() {
      rx += (mx - rx) * 0.18; ry += (my - ry) * 0.18;
      ring.style.transform = `translate(${rx}px,${ry}px) translate(-50%,-50%)`;
      requestAnimationFrame(loop);
    })();
    const hov = 'a,button,.card-prod,input,textarea,select,[data-cursor]';
    document.addEventListener('mouseover', e => { if (e.target.closest(hov)) ring.classList.add('is-hover'); });
    document.addEventListener('mouseout', e => { if (e.target.closest(hov)) ring.classList.remove('is-hover'); });
  }

  /* ---------- Botones magnéticos ---------- */
  function initMagnetic() {
    if (isTouch || reduce) return;
    document.querySelectorAll('[data-magnetic]').forEach(el => {
      el.addEventListener('mousemove', e => {
        const r = el.getBoundingClientRect();
        const x = e.clientX - r.left - r.width / 2;
        const y = e.clientY - r.top - r.height / 2;
        el.style.transform = `translate(${x * 0.25}px, ${y * 0.35}px)`;
      });
      el.addEventListener('mouseleave', () => { el.style.transform = ''; });
    });
  }

  /* ---------- GSAP reveals / parallax / contadores ---------- */
  function initGsap() {
    document.documentElement.classList.add('reveal-ready');
    if (!window.gsap) {
      document.querySelectorAll('.reveal').forEach(el => { el.style.opacity = 1; el.style.transform = 'none'; });
      return;
    }
    const gsap = window.gsap;
    if (window.ScrollTrigger) gsap.registerPlugin(window.ScrollTrigger);

    // Velo de revelado (esmeralda → oro) sobre imágenes
    gsap.utils.toArray('.media').forEach((m) => {
      if (m.querySelector('.media__veil')) return;
      const veil = document.createElement('span');
      veil.className = 'media__veil';
      m.appendChild(veil);
      if (reduce) { veil.remove(); return; }
      gsap.set(veil, { yPercent: 0 });
      gsap.to(veil, {
        yPercent: -101, duration: 1.15, ease: 'expo.inOut',
        scrollTrigger: { trigger: m, start: 'top 86%' }
      });
    });

    if (reduce) {
      gsap.set('.reveal', { opacity: 1, y: 0 });
    } else {
      // reveal por grupos (stagger por contenedor)
      gsap.utils.toArray('[data-reveal-group]').forEach(group => {
        const items = group.querySelectorAll('.reveal');
        gsap.to(items, {
          opacity: 1, y: 0, duration: 0.9, ease: 'power3.out', stagger: 0.08,
          scrollTrigger: { trigger: group, start: 'top 82%' }
        });
      });
      // reveals sueltos
      gsap.utils.toArray('.reveal:not([data-reveal-group] .reveal)').forEach(el => {
        gsap.to(el, {
          opacity: 1, y: 0, duration: 0.9, ease: 'power3.out',
          scrollTrigger: { trigger: el, start: 'top 88%' }
        });
      });
      // parallax
      gsap.utils.toArray('[data-parallax]').forEach(el => {
        const sp = parseFloat(el.dataset.parallax) || 0.2;
        gsap.to(el, {
          yPercent: sp * 100, ease: 'none',
          scrollTrigger: { trigger: el.parentElement, start: 'top bottom', end: 'bottom top', scrub: true }
        });
      });
      // hero: entrada
      const heroEls = document.querySelectorAll('[data-hero]');
      if (heroEls.length) {
        gsap.from(heroEls, { opacity: 0, y: 40, duration: 1.1, ease: 'power3.out', stagger: 0.12, delay: 0.15 });
      }
    }

    // contadores
    document.querySelectorAll('[data-count]').forEach(el => {
      const end = parseFloat(el.dataset.count);
      const obj = { v: 0 };
      const run = () => gsap.to(obj, {
        v: end, duration: 1.6, ease: 'power1.out',
        onUpdate: () => { el.textContent = Math.round(obj.v).toLocaleString('es-CO') + (el.dataset.suffix || ''); }
      });
      if (window.ScrollTrigger) window.ScrollTrigger.create({ trigger: el, start: 'top 90%', once: true, onEnter: run });
      else run();
    });
  }

  /* ---------- Tilt 3D en tarjetas ---------- */
  function initTilt() {
    if (isTouch || reduce) return;
    document.querySelectorAll('.card-prod').forEach(card => {
      card.addEventListener('mouseenter', () => card.classList.add('is-tilting'));
      card.addEventListener('mousemove', e => {
        const r = card.getBoundingClientRect();
        const px = (e.clientX - r.left) / r.width - 0.5;
        const py = (e.clientY - r.top) / r.height - 0.5;
        card.style.transform = `perspective(900px) rotateX(${py * -4}deg) rotateY(${px * 5}deg) translateY(-6px)`;
      });
      card.addEventListener('mouseleave', () => { card.classList.remove('is-tilting'); card.style.transform = ''; });
    });
  }

  /* ---------- Intro loader (saltar en visitas repetidas) ---------- */
  function initIntro() {
    const intro = document.getElementById('vr-intro');
    if (!intro) return;
    try {
      if (sessionStorage.getItem('vr_seen')) intro.classList.add('skip');
      else sessionStorage.setItem('vr_seen', '1');
    } catch (e) {}
  }

  /* ---------- init ---------- */
  function init() {
    initIntro();
    initCursor();
    initMagnetic();
    initTilt();
    initGsap();
  }
  if (document.readyState !== 'loading') init();
  else document.addEventListener('DOMContentLoaded', init);
})();
