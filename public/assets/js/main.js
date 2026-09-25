(() => {
  const nav = document.getElementById('site-nav');
  const toggle = document.querySelector('.nav-toggle');
  if (nav && toggle) {
    toggle.addEventListener('click', () => {
      const open = nav.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      toggle.innerHTML = open
        ? '<i class="fa-solid fa-xmark" aria-hidden="true"></i>'
        : '<i class="fa-solid fa-bars" aria-hidden="true"></i>';
    });
    nav.querySelectorAll('a[href*="#"]').forEach((link) => {
      link.addEventListener('click', () => {
        nav.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
        toggle.innerHTML = '<i class="fa-solid fa-bars" aria-hidden="true"></i>';
      });
    });
  }

  const hero = document.getElementById('hero');
  const track = document.querySelector('[data-hero-track]');
  const dotsWrap = document.querySelector('[data-hero-dots]');
  const btnPrev = document.querySelector('[data-hero-prev]');
  const btnNext = document.querySelector('[data-hero-next]');

  if (hero && track && dotsWrap) {
    const slides = [...track.querySelectorAll('.hero__slide')];
    const total = slides.length;
    let index = 0;
    let timer = null;
    let paused = false;
    let dragging = false;
    let startX = 0;
    let deltaX = 0;

    const AUTOPLAY_MS = 6000;
    const SWIPE_THRESHOLD = 48;

    slides.forEach((_, i) => {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'hero__dot' + (i === 0 ? ' is-active' : '');
      btn.setAttribute('role', 'tab');
      btn.setAttribute('aria-label', `Aller au slide ${i + 1} sur ${total}`);
      btn.setAttribute('aria-selected', i === 0 ? 'true' : 'false');
      btn.addEventListener('click', () => goTo(i, true));
      dotsWrap.appendChild(btn);
    });
    const dots = [...dotsWrap.querySelectorAll('.hero__dot')];

    const render = () => {
      track.style.transform = `translateX(-${index * 100}%)`;
      slides.forEach((slide, i) => {
        const active = i === index;
        slide.setAttribute('aria-hidden', active ? 'false' : 'true');
        slide.querySelectorAll('a, button').forEach((el) => {
          if (active) el.removeAttribute('tabindex');
          else el.setAttribute('tabindex', '-1');
        });
      });
      dots.forEach((dot, i) => {
        const active = i === index;
        dot.classList.toggle('is-active', active);
        dot.setAttribute('aria-selected', active ? 'true' : 'false');
      });
    };

    const goTo = (n, userAction = false) => {
      index = ((n % total) + total) % total;
      render();
      if (userAction) restartAutoplay();
    };

    const next = (userAction = false) => goTo(index + 1, userAction);
    const prev = (userAction = false) => goTo(index - 1, userAction);

    const stopAutoplay = () => {
      if (timer) {
        window.clearInterval(timer);
        timer = null;
      }
    };

    const startAutoplay = () => {
      stopAutoplay();
      if (paused || document.hidden || total < 2) return;
      timer = window.setInterval(() => next(false), AUTOPLAY_MS);
    };

    const restartAutoplay = () => {
      stopAutoplay();
      startAutoplay();
    };

    if (btnPrev) btnPrev.addEventListener('click', () => prev(true));
    if (btnNext) btnNext.addEventListener('click', () => next(true));

    hero.addEventListener('mouseenter', () => {
      paused = true;
      stopAutoplay();
    });
    hero.addEventListener('mouseleave', () => {
      paused = false;
      startAutoplay();
    });

    document.addEventListener('visibilitychange', () => {
      if (document.hidden) stopAutoplay();
      else if (!paused) startAutoplay();
    });

    hero.addEventListener('keydown', (e) => {
      if (e.key === 'ArrowRight') {
        e.preventDefault();
        next(true);
      } else if (e.key === 'ArrowLeft') {
        e.preventDefault();
        prev(true);
      }
    });

    const onPointerDown = (e) => {
      if (e.pointerType !== 'touch') return;
      if (e.target.closest('a, button')) return;
      dragging = true;
      startX = e.clientX;
      deltaX = 0;
      track.classList.add('is-dragging');
      stopAutoplay();
      track.setPointerCapture?.(e.pointerId);
    };

    const onPointerMove = (e) => {
      if (!dragging) return;
      deltaX = e.clientX - startX;
      const pct = (deltaX / hero.offsetWidth) * 100;
      track.style.transform = `translateX(calc(-${index * 100}% + ${pct}%))`;
    };

    const onPointerUp = () => {
      if (!dragging) return;
      dragging = false;
      track.classList.remove('is-dragging');
      if (Math.abs(deltaX) > SWIPE_THRESHOLD) {
        if (deltaX < 0) next(true);
        else prev(true);
      } else {
        render();
        restartAutoplay();
      }
      deltaX = 0;
    };

    track.addEventListener('pointerdown', onPointerDown);
    track.addEventListener('pointermove', onPointerMove);
    track.addEventListener('pointerup', onPointerUp);
    track.addEventListener('pointercancel', onPointerUp);

    render();
    startAutoplay();
  }

  const faqRoot = document.querySelector('[data-faq]');
  if (faqRoot) {
    const tabs = [...faqRoot.querySelectorAll('[data-faq-tab]')];
    const panels = [...faqRoot.querySelectorAll('[data-faq-panel]')];
    tabs.forEach((tab) => {
      tab.addEventListener('click', () => {
        const id = tab.getAttribute('data-faq-tab');
        tabs.forEach((t) => {
          const on = t === tab;
          t.classList.toggle('is-active', on);
          t.setAttribute('aria-selected', on ? 'true' : 'false');
        });
        panels.forEach((panel) => {
          const on = panel.getAttribute('data-faq-panel') === id;
          panel.classList.toggle('is-active', on);
          panel.hidden = !on;
        });
      });
    });
  }

  /* ---------- Modal ---------- */
  const modal = document.getElementById('site-modal');
  const openModal = (payload) => {
    if (!modal || !payload) return;
    const media = modal.querySelector('[data-modal-media]');
    const eyebrow = modal.querySelector('[data-modal-eyebrow]');
    const title = modal.querySelector('[data-modal-title]');
    const subtitle = modal.querySelector('[data-modal-subtitle]');
    const content = modal.querySelector('[data-modal-content]');
    const actions = modal.querySelector('[data-modal-actions]');

    eyebrow.textContent = payload.eyebrow || '';
    title.textContent = payload.title || '';
    subtitle.textContent = payload.subtitle || '';
    content.innerHTML = '';
    actions.innerHTML = '';

    if (payload.image) {
      media.hidden = false;
      media.innerHTML = '<img src="' + String(payload.image).replace(/"/g, '&quot;') + '" alt="">';
    } else if (payload.icon) {
      media.hidden = false;
      media.innerHTML = '<div class="modal__icon"><i class="fa-solid ' + payload.icon + '" aria-hidden="true"></i></div>';
    } else {
      media.hidden = true;
      media.innerHTML = '';
    }

    if (payload.type === 'service') {
      if (payload.lead) {
        const p = document.createElement('p');
        p.textContent = payload.lead;
        content.appendChild(p);
      }
      if (payload.more) {
        const p = document.createElement('p');
        p.className = 'modal__muted';
        p.textContent = payload.more;
        content.appendChild(p);
      }
      (payload.sections || []).forEach((block) => {
        const sec = document.createElement('section');
        sec.className = 'modal__block';
        const h = document.createElement('h3');
        h.textContent = block.title || '';
        const b = document.createElement('p');
        b.textContent = block.body || '';
        sec.appendChild(h);
        sec.appendChild(b);
        content.appendChild(sec);
      });
      if (payload.external) {
        actions.innerHTML += '<a class="btn btn--primary" href="' + payload.external + '" target="_blank" rel="noopener"><i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i> Ouvrir</a>';
      }
      actions.innerHTML += '<a class="btn btn--ghost" href="index.php#contact"><i class="fa-solid fa-envelope" aria-hidden="true"></i> Nous contacter</a>';
    } else {
      if (payload.desc) {
        const p = document.createElement('p');
        p.textContent = payload.desc;
        content.appendChild(p);
      }
      if (payload.file) {
        const meta = document.createElement('p');
        meta.className = 'modal__muted';
        meta.innerHTML = '<i class="fa-solid fa-file" aria-hidden="true"></i> ' + String(payload.file).replace(/[<>&]/g, '');
        content.appendChild(meta);
      }
      if (payload.download) {
        actions.innerHTML = '<a class="btn btn--primary" href="' + String(payload.download).replace(/"/g, '&quot;') + '" target="_blank" rel="noopener"><i class="fa-solid fa-download" aria-hidden="true"></i> Télécharger / ouvrir</a>';
      }
    }

    modal.hidden = false;
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('modal-open');
    modal.querySelector('.modal__close')?.focus();
  };

  const closeModal = () => {
    if (!modal) return;
    modal.hidden = true;
    modal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('modal-open');
  };

  document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-modal-open]');
    if (btn) {
      e.preventDefault();
      try {
        openModal(JSON.parse(btn.getAttribute('data-modal-payload') || '{}'));
      } catch (_) { /* ignore */ }
      return;
    }
    if (e.target.closest('[data-modal-close]')) {
      closeModal();
    }
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal && !modal.hidden) closeModal();
  });

  /* ---------- Live search -> cards ---------- */
  const escapeHtml = (s) => String(s)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');

  const cardHtml = (item) => {
    const payload = {
      type: 'ressource',
      title: item.title || '',
      eyebrow: String(item.ext || item.kind || '').toUpperCase(),
      subtitle: item.subtitle || '',
      desc: item.desc || '',
      image: item.image || null,
      file: item.file || '',
      download: item.download || item.url || '',
    };
    const img = item.image
      ? '<img src="' + escapeHtml(item.image) + '" alt="" loading="lazy">'
      : '<div class="feature-card__fallback"><i class="fa-solid fa-file-pdf" aria-hidden="true"></i></div>';
    return (
      '<button type="button" class="feature-card" data-modal-open data-modal-payload="' + escapeHtml(JSON.stringify(payload)) + '">' +
        '<div class="feature-card__media">' + img +
          '<span class="feature-card__badge">' + escapeHtml(payload.eyebrow) + '</span>' +
        '</div>' +
        '<div class="feature-card__body">' +
          '<h3>' + escapeHtml(item.title || '') + '</h3>' +
          '<p>' + escapeHtml(item.subtitle || '') + '</p>' +
          '<span class="feature-card__cta">Voir le détail <i class="fa-solid fa-expand" aria-hidden="true"></i></span>' +
        '</div>' +
      '</button>'
    );
  };

  document.querySelectorAll('[data-live-search]').forEach((root) => {
    const input = root.querySelector('[data-live-search-input]');
    const status = root.querySelector('[data-live-search-status]');
    const grid = root.parentElement?.querySelector('[data-ressource-grid]')
      || document.querySelector('[data-ressource-grid]');
    if (!input || !grid) return;

    const isLocal = root.getAttribute('data-live-search-source') === 'local';
    const featuredHtml = grid.hasAttribute('data-featured-html') ? grid.innerHTML : null;
    let timer = null;
    let seq = 0;

    const setStatus = (msg) => {
      if (!status) return;
      if (!msg) {
        status.hidden = true;
        status.textContent = '';
        return;
      }
      status.hidden = false;
      status.textContent = msg;
    };

    const runLocal = (q) => {
      const cards = [...grid.querySelectorAll('.feature-card')];
      let visible = 0;
      cards.forEach((card) => {
        const hay = (card.getAttribute('data-search-hay') || card.textContent || '').toLowerCase();
        const show = !q || hay.includes(q);
        card.hidden = !show;
        if (show) visible += 1;
      });
      setStatus(q ? visible + ' résultat(s)' : '');
    };

    const runRemote = async (q) => {
      const my = ++seq;
      if (q.length < 2) {
        if (featuredHtml !== null) grid.innerHTML = featuredHtml;
        setStatus('');
        return;
      }
      try {
        const res = await fetch('api/search.php?q=' + encodeURIComponent(q), {
          headers: { Accept: 'application/json' },
        });
        if (!res.ok) return;
        const data = await res.json();
        if (my !== seq) return;
        const items = Array.isArray(data.items) ? data.items : [];
        if (!items.length) {
          grid.innerHTML = '';
          setStatus('Aucun résultat pour « ' + q + ' »');
          return;
        }
        grid.innerHTML = items.map(cardHtml).join('');
        setStatus(items.length + ' résultat(s)');
      } catch (_) { /* ignore */ }
    };

    input.addEventListener('input', () => {
      window.clearTimeout(timer);
      const q = input.value.trim().toLowerCase();
      timer = window.setTimeout(() => {
        if (isLocal) runLocal(q);
        else runRemote(q);
      }, 160);
    });
  });
})();
