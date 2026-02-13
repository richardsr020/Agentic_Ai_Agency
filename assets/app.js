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

  let isSending = false;

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
      loadChatHistory().then(() => {
        requestAnimationFrame(() => {
          if (chatInput) chatInput.focus();
        });
      });
    }
  }

  function appendMessage({ from, text }) {
    if (!chatMessages) return;
    const row = document.createElement('div');
    row.className = 'chat-row ' + (from === 'assistant' ? 'from-skill' : 'from-user');
    const bubble = document.createElement('div');
    bubble.className = 'chat-bubble';
    bubble.textContent = text;
    row.appendChild(bubble);
    chatMessages.appendChild(row);
    chatMessages.scrollTop = chatMessages.scrollHeight;

    return bubble;
  }

  async function typeIntoBubble(bubble, fullText) {
    if (!bubble) return;
    bubble.textContent = '';
    const text = String(fullText || '');
    return new Promise((resolve) => {
      let i = 0;
      const step = () => {
        i += 1;
        bubble.textContent = text.slice(0, i);
        if (chatMessages) chatMessages.scrollTop = chatMessages.scrollHeight;
        if (i < text.length) {
          window.setTimeout(step, 14);
        } else {
          resolve();
        }
      };
      step();
    });
  }

  async function loadChatHistory() {
    try {
      const res = await fetch('/api/chat/history');
      const data = await res.json();
      if (data.messages && Array.isArray(data.messages)) {
        chatMessages.innerHTML = '';
        const onlyOneAssistant = data.messages.length === 1 && data.messages[0].role !== 'user';
        data.messages.forEach((msg, index) => {
          const bubble = appendMessage({
            from: msg.role === 'user' ? 'user' : 'assistant',
            text: onlyOneAssistant && index === 0 ? '' : msg.content,
          });
          if (onlyOneAssistant && index === 0) {
            typeIntoBubble(bubble, msg.content);
          }
        });
      }
    } catch (err) {
      console.error('Error loading chat history:', err);
    }
  }

  async function sendMessage(message) {
    if (isSending) return;
    isSending = true;

    const loadingBubble = appendMessage({ from: 'assistant', text: '...' });
    if (loadingBubble) loadingBubble.classList.add('is-loading');

    try {
      const res = await fetch('/api/chat/message', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ message }),
      });

      const data = await res.json();

      if (loadingBubble && loadingBubble.parentElement) {
        loadingBubble.parentElement.remove();
      }

      if (data.response) {
        const bubble = appendMessage({ from: 'assistant', text: '' });
        await typeIntoBubble(bubble, data.response);
        if (data.redirect) {
          window.setTimeout(() => {
            window.location.href = data.redirect;
          }, 650);
        }
      } else if (data.error) {
        appendMessage({ from: 'assistant', text: 'Désolé, une erreur s\'est produite. Veuillez réessayer.' });
      }
    } catch (err) {
      if (loadingBubble && loadingBubble.parentElement) {
        loadingBubble.parentElement.remove();
      }

      appendMessage({ from: 'assistant', text: 'Erreur réseau. Veuillez réessayer.' });
    } finally {
      isSending = false;
      if (chatInput) chatInput.disabled = false;
    }
  }

  if (modal && openChatBtns.length) {
    openChatBtns.forEach((b) => {
      b.addEventListener('click', () => {
        setModalOpen(true);
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
    chatForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      if (!chatInput || isSending) return;
      const value = (chatInput.value || '').trim();
      if (!value) return;
      
      appendMessage({ from: 'user', text: value });
      chatInput.value = '';
      chatInput.disabled = true;
      
      await sendMessage(value);
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

    const logoutEl = e.target && e.target.closest ? e.target.closest('[data-logout]') : null;
    if (logoutEl) {
      e.preventDefault();
      fetch('/api/auth/logout', { method: 'POST' })
        .catch(() => null)
        .finally(() => {
          window.location.href = '/';
        });
      return;
    }

    const a = e.target && e.target.closest ? e.target.closest('a') : null;
    if (!a) return;
    const href = a.getAttribute('href');
    if (!href) return;

    // Only intercept internal one-page anchors
    if (href.startsWith('/#') || href.startsWith('#')) {
      const isHomePage = window.location.pathname === '/' || window.location.pathname === '/index.php';
      let hash = href.startsWith('#') ? href : href.slice(1);
      
      // If we're on another page and clicking a section link, redirect to homepage
      if (!isHomePage) {
        e.preventDefault();
        window.location.href = '/' + hash;
        return;
      }
      
      // If we're on the homepage, just scroll to the section
      e.preventDefault();
      history.pushState(null, '', hash);
      document.body.classList.remove('nav-open');
      scrollToHash(hash, { behavior: 'smooth' });
      return;
    }
  });

  window.addEventListener('hashchange', () => {
    scrollToHash(window.location.hash, { behavior: 'smooth' });
  });

  // On load: if URL has a hash, jump to that section (after layout)
  window.addEventListener('load', () => {
    if (modal) {
      try {
        const key = 'agentic_skill_modal_seen';
        if (!window.localStorage.getItem(key)) {
          window.localStorage.setItem(key, '1');
          window.setTimeout(() => setModalOpen(true), 650);
        }
      } catch (err) {
        window.setTimeout(() => setModalOpen(true), 650);
      }
    }
    if (window.location.hash) {
      scrollToHash(window.location.hash, { behavior: 'auto' });
    }
  });
})();
