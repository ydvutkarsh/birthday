document.addEventListener('DOMContentLoaded', () => {
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

  const memoryTriggers = [...document.querySelectorAll('[data-bs-toggle="modal"][data-bs-target^="#memory-"]')];
  const memoryModals = memoryTriggers.map((trigger) => {
    const targetSelector = trigger.getAttribute('data-bs-target');
    return {
      trigger,
      modal: targetSelector ? document.querySelector(targetSelector) : null,
    };
  }).filter(({ modal }) => modal);

  if (memoryModals.length) {
    const bootstrapAvailable = Boolean(window.bootstrap?.Modal);
    let fallbackBackdrop = null;
    let activeFallbackModal = null;
    let activeFallbackTrigger = null;

    const closeFallbackModal = (modal) => {
      if (!modal) return;
      modal.classList.remove('show', 'memory-modal-fallback-open');
      modal.setAttribute('aria-hidden', 'true');
      modal.removeAttribute('aria-modal');
      modal.style.display = 'none';
      fallbackBackdrop?.remove();
      fallbackBackdrop = null;
      activeFallbackModal = null;
      document.body.classList.remove('modal-open');
      activeFallbackTrigger?.focus();
      activeFallbackTrigger = null;
    };

    const openFallbackModal = (modal, trigger) => {
      closeFallbackModal(activeFallbackModal);
      activeFallbackModal = modal;
      activeFallbackTrigger = trigger;
      fallbackBackdrop = document.createElement('div');
      fallbackBackdrop.className = 'memory-modal-fallback-backdrop';
      fallbackBackdrop.addEventListener('click', () => closeFallbackModal(modal));
      document.body.append(fallbackBackdrop);
      modal.classList.add('show', 'memory-modal-fallback-open');
      modal.style.display = 'block';
      modal.removeAttribute('aria-hidden');
      modal.setAttribute('aria-modal', 'true');
      document.body.classList.add('modal-open');
      modal.querySelector('[data-bs-dismiss="modal"]')?.focus();
    };

    memoryModals.forEach(({ trigger, modal }) => {
      const instance = bootstrapAvailable
        ? window.bootstrap.Modal.getOrCreateInstance(modal, { backdrop: true, keyboard: true, focus: true })
        : null;

      trigger.addEventListener('click', (event) => {
        event.preventDefault();
        event.stopPropagation();
        if (instance) instance.show();
        else openFallbackModal(modal, trigger);
      });

      if (!bootstrapAvailable) {
        modal.querySelector('[data-bs-dismiss="modal"]')?.addEventListener('click', () => closeFallbackModal(modal));
        modal.addEventListener('click', (event) => {
          if (event.target === modal) closeFallbackModal(modal);
        });
      }
    });

    if (!bootstrapAvailable) {
      document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') closeFallbackModal(activeFallbackModal);
      });
    }
  }

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
    const videoMount = player.querySelector('[data-youtube]');
    const seek = player.querySelector('[data-seek]');
    const progress = player.querySelector('.player-progress > span');
    const currentTime = player.querySelector('[data-current-time]');
    const totalTime = player.querySelector('[data-total-time]');
    const songs = window.storySongs || [];
    let index = 0;
    let playing = false;
    let youtubePlayer = null;
    let playerReady = false;
    let pollTimer = null;
    let autoplayWhenReady = false;
    const formatTime = (seconds) => {
      const value = Math.max(0, Math.floor(Number(seconds) || 0));
      return `${Math.floor(value / 60)}:${String(value % 60).padStart(2, '0')}`;
    };
    const updateProgress = () => {
      if (!youtubePlayer || !playerReady) return;
      const duration = youtubePlayer.getDuration() || 0;
      const position = youtubePlayer.getCurrentTime() || 0;
      if (duration > 0) {
        seek.max = duration;
        seek.value = position;
        progress.style.width = `${(position / duration) * 100}%`;
        totalTime.textContent = formatTime(duration);
      }
      currentTime.textContent = formatTime(position);
    };
    const startProgressPolling = () => {
      clearInterval(pollTimer);
      pollTimer = setInterval(updateProgress, 500);
    };
    const load = (autoplay = false) => {
      const song = songs[index];
      if (!song) return;
      autoplayWhenReady = autoplay;
      player.querySelector('[data-song-title]').textContent = song.title;
      seek.value = 0;
      progress.style.width = '0%';
      currentTime.textContent = '0:00';
      totalTime.textContent = '0:00';
      if (!youtubePlayer || !playerReady) return;
      if (song.video) autoplay ? youtubePlayer.loadVideoById(song.video) : youtubePlayer.cueVideoById(song.video);
      else autoplay ? youtubePlayer.loadPlaylist({ listType: 'playlist', list: song.playlist }) : youtubePlayer.cuePlaylist({ listType: 'playlist', list: song.playlist });
    };
    const createYoutubePlayer = () => {
      if (youtubePlayer || !window.YT?.Player) return;
      const song = songs[index];
      youtubePlayer = new YT.Player(videoMount, {
        width: '1', height: '1', videoId: song?.video || undefined,
        playerVars: { autoplay: 0, controls: 0, rel: 0, playsinline: 1, origin: window.location.origin, listType: song?.playlist && !song?.video ? 'playlist' : undefined, list: song?.playlist || undefined },
        events: {
          onReady: (event) => { playerReady = true; startProgressPolling(); if (autoplayWhenReady) event.target.playVideo(); },
          onStateChange: (event) => {
            if (event.data === YT.PlayerState.PLAYING) playing = true;
            if (event.data === YT.PlayerState.PAUSED || event.data === YT.PlayerState.ENDED) playing = false;
            player.querySelector('[data-play]').textContent = playing ? '\u23f8' : '\u25b6';
          }
        }
      });
    };
    const start = () => {
      autoplayWhenReady = true;
      createYoutubePlayer();
      if (playerReady) youtubePlayer.playVideo();
    };
    seek?.addEventListener('input', () => {
      const value = Number(seek.value);
      progress.style.width = seek.max > 0 ? `${(value / Number(seek.max)) * 100}%` : '0%';
      currentTime.textContent = formatTime(value);
    });
    seek?.addEventListener('change', () => { if (playerReady) youtubePlayer.seekTo(Number(seek.value), true); });
    document.querySelectorAll('[data-start-story]').forEach((button) => button.addEventListener('click', start));
    player.querySelector('[data-play]')?.addEventListener('click', () => {
      if (!playerReady || !playing) start();
      else youtubePlayer.pauseVideo();
    });
    player.querySelector('[data-prev]')?.addEventListener('click', () => { index = (index - 1 + songs.length) % songs.length; load(true); createYoutubePlayer(); });
    player.querySelector('[data-next]')?.addEventListener('click', () => { index = (index + 1) % songs.length; load(true); createYoutubePlayer(); });
    if (window.YT?.Player) createYoutubePlayer();
    else window.onYouTubeIframeAPIReady = createYoutubePlayer;
  }

  document.querySelector('[data-sidebar-toggle]')?.addEventListener('click', () => document.querySelector('.studio-sidebar')?.classList.toggle('open'));
  document.querySelectorAll('form[data-confirm]').forEach((form) => form.addEventListener('submit', (event) => {
    if (!window.confirm(form.dataset.confirm)) event.preventDefault();
  }));
});
