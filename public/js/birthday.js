document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.nav-brand,.brand-mark,.studio-logo').forEach((logo) => {
    logo.innerHTML = logo.innerHTML.replace(/S(?=\s|<|$)/, 'A');
  });

  const opening = document.querySelector('[data-opening]');
  if (opening) setTimeout(() => opening.classList.add('done'), 1200);

  const access = document.querySelector('[data-access]');
  if (access?.classList.contains('show')) setTimeout(() => access.classList.remove('show'), 1700);

  const navToggle = document.querySelector('[data-nav-toggle]');
  const navLinks = document.querySelector('.floral-nav-links');
  navToggle?.addEventListener('click', () => {
    const open = navLinks?.classList.toggle('open') ?? false;
    navToggle.setAttribute('aria-expanded', String(open));
    navToggle.setAttribute('aria-label', open ? 'Close navigation' : 'Open navigation');
  });
  navLinks?.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => {
    navLinks.classList.remove('open');
    navToggle?.setAttribute('aria-expanded', 'false');
    navToggle?.setAttribute('aria-label', 'Open navigation');
  }));

  const navSections = [...document.querySelectorAll('main section[id]')];
  if (navSections.length) {
    const updateActiveNav = () => {
      const current = navSections.reduce((active, section) => section.getBoundingClientRect().top <= 150 ? section : active, navSections[0]);
      const currentId = window.scrollY < 220 || current.id === 'intro' ? 'top' : current.id;
      navLinks?.querySelectorAll('a').forEach((link) => link.classList.toggle('active', link.getAttribute('href') === `#${currentId}`));
    };
    window.addEventListener('scroll', updateActiveNav, { passive: true });
    updateActiveNav();
  }

  document.querySelectorAll('.reveal').forEach((item) => {
    new IntersectionObserver((entries, observer) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: .14 }).observe(item);
  });

  document.querySelectorAll('.message-card').forEach((card) => card.addEventListener('click', () => card.classList.toggle('flipped')));

  const envelope = document.querySelector('[data-envelope]');
  const letterButton = document.querySelector('[data-open-letter]');
  const letterReveal = document.querySelector('[data-letter-reveal]');
  letterButton?.addEventListener('click', (event) => {
    event.stopPropagation();
    envelope?.classList.add('open');
    setTimeout(() => letterReveal?.classList.add('show'), 500);
  });

  const finalButton = document.querySelector('[data-final]');
  finalButton?.addEventListener('click', () => {
    document.querySelector('[data-final-message]')?.classList.add('show');
    finalButton.style.display = 'none';
  });

  const lightbox = document.querySelector('[data-lightbox-modal]');
  document.querySelectorAll('[data-lightbox]').forEach((tile) => tile.addEventListener('click', () => {
    if (!lightbox) return;
    lightbox.querySelector('[data-lightbox-image]').src = tile.dataset.src;
    lightbox.querySelector('[data-lightbox-caption]').textContent = tile.dataset.caption || '';
    lightbox.querySelector('[data-lightbox-date]').textContent = tile.dataset.date || '';
    lightbox.classList.add('show');
  }));
  lightbox?.querySelector('[data-lightbox-close]')?.addEventListener('click', () => lightbox.classList.remove('show'));
  lightbox?.addEventListener('click', (event) => { if (event.target === lightbox) lightbox.classList.remove('show'); });

  const player = document.querySelector('[data-player]');
  if (player) {
    const iframe = player.querySelector('[data-youtube]');
    const songs = window.storySongs || [];
    let index = 0;
    let playing = false;
    const load = () => {
      const song = songs[index];
      if (!song) return;
      iframe.src = song.video
        ? `https://www.youtube.com/embed/${song.video}?enablejsapi=1&playsinline=1&rel=0`
        : `https://www.youtube.com/embed/videoseries?list=${song.playlist}&enablejsapi=1&playsinline=1&rel=0`;
      player.querySelector('[data-song-title]').textContent = song.title;
    };
    const post = (command) => iframe.contentWindow?.postMessage(JSON.stringify({ event: 'command', func: command, args: [] }), '*');
    const start = () => {
      if (!iframe.src || iframe.src === 'about:blank') load();
      setTimeout(() => post('playVideo'), 300);
      playing = true;
      player.querySelector('[data-play]').textContent = '\u23f8';
    };
    document.querySelectorAll('[data-start-story]').forEach((button) => button.addEventListener('click', start));
    player.querySelector('[data-play]')?.addEventListener('click', () => {
      if (!playing) start();
      else { post('pauseVideo'); playing = false; player.querySelector('[data-play]').textContent = '\u25b6'; }
    });
    player.querySelector('[data-prev]')?.addEventListener('click', () => { index = (index - 1 + songs.length) % songs.length; load(); start(); });
    player.querySelector('[data-next]')?.addEventListener('click', () => { index = (index + 1) % songs.length; load(); start(); });
  }

  document.querySelector('[data-sidebar-toggle]')?.addEventListener('click', () => document.querySelector('.studio-sidebar')?.classList.toggle('open'));
  document.querySelectorAll('form[data-confirm]').forEach((form) => form.addEventListener('submit', (event) => {
    if (!window.confirm(form.dataset.confirm)) event.preventDefault();
  }));
});
