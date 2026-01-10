(function () {
  const html = document.documentElement;
  const header = document.querySelector('.header');

  // Chat modal + guided chat (front-only)
  const modal = document.querySelector('[data-chat-modal]');
  const openChatBtns = Array.from(document.querySelectorAll('[data-open-chat]'));
  const closeChatBtns = Array.from(document.querySelectorAll('[data-close-chat]'));
  const chatMessages = document.querySelector('[data-chat-messages]');
  const chatForm = document.querySelector('[data-chat-form]');
  const chatInput = document.querySelector('[data-chat-input]');

  const profile = {
    name: null,
    company: null,
    role: null,
    website: null,
    need: null,
    channels: null,
    volume: null,
    timeline: null,
    budget: null,
  };

  const questions = [
    { key: 'name', q: "Bonjour, je suis Skill. Comment vous appelez-vous ?" },
    { key: 'company', q: 'Quel est le nom de votre entreprise ?' },
    { key: 'role', q: 'Quel est votre rôle (ex: CEO, Sales, Support) ?' },
    { key: 'need', q: 'Quel est votre objectif principal (support, RDV, prospection, autre) ?' },
    { key: 'channels', q: 'Sur quels canaux voulez-vous automatiser (email, chat, WhatsApp, LinkedIn...) ?' },
    { key: 'volume', q: 'Quel volume approx. (messages / leads) par semaine ?' },
    { key: 'timeline', q: 'Quel délai souhaitez-vous pour démarrer ?' },
    { key: 'budget', q: 'Avez-vous une fourchette de budget mensuel ? (optionnel)' },
  ];

  // Hero slider (auto)
  const heroSlider = document.querySelector('[data-hero-slider]');
  const heroSlides = heroSlider ? Array.from(heroSlider.querySelectorAll('[data-hero-slide]')) : [];
  if (heroSlides.length > 1) {
    let heroIndex = Math.max(0, heroSlides.findIndex((n) => n.classList.contains('is-active')));
    heroSlides.forEach((s, i) => s.classList.toggle('is-active', i === heroIndex));

    window.setInterval(() => {
      heroSlides[heroIndex].classList.remove('is-active');
      heroIndex = (heroIndex + 1) % heroSlides.length;
      heroSlides[heroIndex].classList.add('is-active');
    }, 3200);
  }

  let currentQuestionIndex = 0;

  function setModalOpen(isOpen) {
    if (!modal) return;
    modal.classList.toggle('is-open', isOpen);
    modal.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
    document.body.classList.toggle('modal-open', isOpen);
    if (isOpen) {
      requestAnimationFrame(() => {
        if (chatInput) chatInput.focus();
      });
    }
  }

  function appendMessage({ from, text }) {
    if (!chatMessages) return;
    const row = document.createElement('div');
    row.className = 'chat-row ' + (from === 'skill' ? 'from-skill' : 'from-user');
    const bubble = document.createElement('div');
    bubble.className = 'chat-bubble';
    bubble.textContent = text;
    row.appendChild(bubble);
    chatMessages.appendChild(row);
    chatMessages.scrollTop = chatMessages.scrollHeight;
  }

  function resetChat() {
    if (chatMessages) chatMessages.innerHTML = '';
    currentQuestionIndex = 0;
    Object.keys(profile).forEach((k) => (profile[k] = null));
    appendMessage({ from: 'skill', text: questions[0].q });
  }

  function handleAnswer(value) {
    const q = questions[currentQuestionIndex];
    if (q) {
      profile[q.key] = value;
    }
    currentQuestionIndex += 1;
    if (currentQuestionIndex < questions.length) {
      appendMessage({ from: 'skill', text: questions[currentQuestionIndex].q });
      return;
    }

    const summary =
      'Merci. Résumé rapide :\n' +
      'Nom: ' + (profile.name || '-') + '\n' +
      'Entreprise: ' + (profile.company || '-') + '\n' +
      'Rôle: ' + (profile.role || '-') + '\n' +
      'Objectif: ' + (profile.need || '-') + '\n' +
      'Canaux: ' + (profile.channels || '-') + '\n' +
      'Volume: ' + (profile.volume || '-') + '\n' +
      'Délai: ' + (profile.timeline || '-') + '\n' +
      'Budget: ' + (profile.budget || '-') + '\n' +
      '\nJe peux maintenant vous orienter vers la meilleure mise en place.';
    appendMessage({ from: 'skill', text: summary });
  }

  if (modal && openChatBtns.length) {
    openChatBtns.forEach((b) => {
      b.addEventListener('click', () => {
        setModalOpen(true);
        resetChat();
      });
    });
  }

  if (modal && closeChatBtns.length) {
    closeChatBtns.forEach((b) => {
      b.addEventListener('click', () => setModalOpen(false));
    });
  }

  window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      if (modal && modal.classList.contains('is-open')) {
        setModalOpen(false);
      }
    }
  });

  if (chatForm) {
    chatForm.addEventListener('submit', (e) => {
      e.preventDefault();
      if (!chatInput) return;
      const value = (chatInput.value || '').trim();
      if (!value) return;
      appendMessage({ from: 'user', text: value });
      chatInput.value = '';
      setTimeout(() => handleAnswer(value), 200);
    });
  }

  // Mobile nav
  const btn = document.querySelector('[data-nav-toggle]');
  if (btn) {
    btn.addEventListener('click', () => {
      document.body.classList.toggle('nav-open');
    });
  }

  // Scroll reveal
  let io = null;
  function initReveal(scope) {
    const root = scope || document;
    const revealEls = Array.from(root.querySelectorAll('.reveal'));
    if (!revealEls.length) return;
    if ('IntersectionObserver' in window) {
      if (!io) {
        io = new IntersectionObserver(
          (entries) => {
            for (const e of entries) {
              if (e.isIntersecting) {
                e.target.classList.add('is-visible');
                io.unobserve(e.target);
              }
            }
          },
          { root: null, threshold: 0.12 }
        );
      }
      revealEls.forEach((el) => io.observe(el));
    } else {
      revealEls.forEach((el) => el.classList.add('is-visible'));
    }
  }

  initReveal(document);

  // Theme transition helper (when theme changes server-side after POST)
  // We add a short-lived class on first paint to smooth the swap.
  requestAnimationFrame(() => {
    html.classList.add('theme-anim');
    setTimeout(() => html.classList.remove('theme-anim'), 350);
  });

  function scrollToHash(hash, { behavior } = {}) {
    if (!hash || hash === '#') return;
    const id = hash.startsWith('#') ? hash.slice(1) : hash;
    const el = document.getElementById(id);
    if (!el) return;

    const offset = header ? header.getBoundingClientRect().height + 10 : 0;
    const top = window.scrollY + el.getBoundingClientRect().top - offset;
    window.scrollTo({ top, behavior: behavior || 'smooth' });
  }

  document.addEventListener('click', (e) => {
    if (e.defaultPrevented) return;
    if (e.button !== 0) return;
    if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;

    const a = e.target && e.target.closest ? e.target.closest('a') : null;
    if (!a) return;
    const href = a.getAttribute('href');
    if (!href) return;

    // Only intercept internal one-page anchors
    if (href.startsWith('/#') || href.startsWith('#')) {
      const hash = href.startsWith('#') ? href : href.slice(1);
      e.preventDefault();
      history.pushState(null, '', href.startsWith('#') ? href : '#' + hash);
      document.body.classList.remove('nav-open');
      scrollToHash('#' + hash, { behavior: 'smooth' });
      return;
    }
  });

  window.addEventListener('hashchange', () => {
    scrollToHash(window.location.hash, { behavior: 'smooth' });
  });

  // On load: if URL has a hash, jump to that section (after layout)
  window.addEventListener('load', () => {
    if (window.location.hash) {
      scrollToHash(window.location.hash, { behavior: 'auto' });
    }
  });
})();
