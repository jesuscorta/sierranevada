const initMobileMenu = () => {
  const menu = document.querySelector('[data-mobile-menu]');
  const openBtn = document.querySelector('[data-mobile-toggle]');
  const closeBtns = document.querySelectorAll('[data-mobile-close]');

  if (!menu || !openBtn) return;

  const openMenu = () => {
    menu.classList.remove('hidden');
    void menu.offsetWidth; // ensure transition runs
    menu.classList.add('is-open');
    document.body.classList.add('overflow-hidden');
    openBtn.setAttribute('aria-expanded', 'true');
  };

  const closeMenu = () => {
    menu.classList.remove('is-open');
    menu.addEventListener(
      'transitionend',
      () => {
        if (!menu.classList.contains('is-open')) {
          menu.classList.add('hidden');
          document.body.classList.remove('overflow-hidden');
          openBtn.setAttribute('aria-expanded', 'false');
        }
      },
      { once: true }
    );
  };

  openBtn.addEventListener('click', () => {
    if (menu.classList.contains('is-open')) {
      closeMenu();
    } else {
      openMenu();
    }
  });

  closeBtns.forEach((btn) => {
    btn.addEventListener('click', closeMenu);
  });
};

const initHeroVideoLazy = () => {
  const videos = document.querySelectorAll('video[data-hero-video]');
  if (!videos.length) return;

  const loadVideo = (video) => {
    const src = video?.dataset?.src;
    if (!video || !src || video.src) return;
    video.src = src;
    video.load();
    video.play().catch(() => {});
  };

  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            loadVideo(entry.target);
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.1 }
    );
    videos.forEach((video) => observer.observe(video));
  } else {
    videos.forEach(loadVideo);
  }
};

const initScrollFade = () => {
  const items = document.querySelectorAll('.scroll-fade-left');
  if (!items.length || !('IntersectionObserver' in window)) return;

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const el = entry.target;
          const delay = Number(el.dataset.delay || 0);
          window.setTimeout(() => {
            el.classList.remove('opacity-0', 'translate-x-[-30px]');
            el.classList.add('opacity-100', 'translate-x-0');
          }, delay);
          observer.unobserve(el);
        }
      });
    },
    { threshold: 0.2 }
  );

  items.forEach((item) => observer.observe(item));
};

const initAutocaravanasCarouselTabs = () => {
  if (typeof Swiper === 'undefined') return;

  const blocks = document.querySelectorAll('[data-mauswp-autocaravanas-carousel]');
  if (!blocks.length) return;

  blocks.forEach((carousel) => {
    if (carousel.dataset.init === '1') return;
    carousel.dataset.init = '1';

    const root = carousel.closest('section');
    const tabsWrap = root?.querySelector('[data-mauswp-tabs]');
    const wrapper = carousel.querySelector('[data-mauswp-carousel-wrapper]');
    const btnPrev = root?.querySelector('[data-mauswp-carousel-prev]');
    const btnNext = root?.querySelector('[data-mauswp-carousel-next]');

    if (!tabsWrap || !wrapper) return;

    const ajaxUrl = carousel.dataset.ajaxUrl;
    const nonce = carousel.dataset.nonce;
    const limit = parseInt(carousel.dataset.limit || '10', 10);
    const iconUp = tabsWrap.dataset.iconUp || '';
    const iconDown = tabsWrap.dataset.iconDown || '';

    const initSwiperInstance = () => {
      const swiperEl = carousel.querySelector('.swiper');
      if (!swiperEl) return null;
      if (swiperEl.swiper) swiperEl.swiper.destroy(true, true);
      return new Swiper(swiperEl, {
        slidesPerView: 'auto',
        spaceBetween: 16,
        centeredSlides: true,
        navigation: {
          nextEl: btnNext,
          prevEl: btnPrev,
        },
        breakpoints: {
          1024: { spaceBetween: 20, centeredSlides: false },
        },
      });
    };

    const setActiveTab = (tab) => {
      tabsWrap.querySelectorAll('[data-mauswp-tab]').forEach((btn) => {
        const isActive = btn.dataset.tab === tab;
        btn.classList.toggle('border-[1.5px]', isActive);
        btn.classList.toggle('border-[#267348]', isActive);
        btn.classList.toggle('bg-[#F8FAFC]', isActive);
        btn.classList.toggle('text-[#0F172A]', isActive);
        btn.classList.toggle('font-bold', isActive);
        btn.setAttribute('aria-pressed', isActive ? 'true' : 'false');
        const iconWrap = btn.querySelector('.mauswp-tab-icon');
        if (iconWrap) {
          iconWrap.innerHTML = isActive ? iconDown : iconUp;
        }
      });
    };

    const fetchTab = (tab) => {
      if (!ajaxUrl) return;
      wrapper.innerHTML =
        '<div class="swiper-slide"><div class="rounded-2xl border border-neutral-200 bg-neutral-50 p-6 text-sm text-neutral-700">' +
        (window.mauswpI18n?.loading || 'Cargando…') +
        '</div></div>';

      const url = new URL(ajaxUrl);
      url.searchParams.set('action', 'mauswp_autocaravanas_tabs');
      url.searchParams.set('estado', tab);
      url.searchParams.set('limit', limit);
      url.searchParams.set('nonce', nonce);

      fetch(url.toString(), { credentials: 'same-origin' })
        .then((res) => res.json())
        .then((data) => {
          if (data.success && data.data?.html) {
            wrapper.innerHTML = data.data.html;
          } else {
            wrapper.innerHTML =
              '<div class="swiper-slide"><div class="rounded-2xl border border-neutral-200 bg-neutral-50 p-6"><p class="text-sm text-neutral-700">' +
              (window.mauswpI18n?.empty || 'No hay resultados para este filtro.') +
              '</p></div></div>';
          }
          initSwiperInstance();
        })
        .catch(() => {
          wrapper.innerHTML =
            '<div class="swiper-slide"><div class="rounded-2xl border border-neutral-200 bg-neutral-50 p-6"><p class="text-sm text-neutral-700">' +
            (window.mauswpI18n?.error || 'Error al cargar.') +
            '</p></div></div>';
        });
    };

    tabsWrap.querySelectorAll('[data-mauswp-tab]').forEach((btn) => {
      btn.addEventListener('click', () => {
        const tab = btn.dataset.tab;
        setActiveTab(tab);
        fetchTab(tab);
      });
    });

    initSwiperInstance();
  });
};

const initAutocaravanasCategorias = () => {
  const blocks = document.querySelectorAll('[data-cat-block]');
  blocks.forEach((block) => {
    const tabs = block.querySelectorAll('[data-cat-tab]');
    const panels = block.querySelectorAll('[data-cat-panel]');
    if (!tabs.length || !panels.length) return;

    const setActive = (key) => {
      tabs.forEach((btn) => {
        const isActive = btn.dataset.catTab === key;
        btn.classList.toggle('border-primary-300', isActive);
        btn.classList.toggle('text-primary-300', isActive);
        btn.classList.toggle('font-bold', isActive);
        btn.classList.toggle('border-neutral-300', !isActive);
        btn.classList.toggle('text-slate-600', !isActive);
        btn.classList.toggle('font-medium', !isActive);
        btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
      });
      panels.forEach((panel) => {
        const isActive = panel.dataset.catPanel === key;
        panel.classList.toggle('hidden', !isActive);
        panel.setAttribute('aria-hidden', isActive ? 'false' : 'true');
      });
    };

    tabs.forEach((btn) => {
      btn.addEventListener('click', () => {
        setActive(btn.dataset.catTab);
      });
    });
  });
};

const initAutocaravanasLoadMore = () => {
  const container = document.querySelector('[data-autos-grid]');
  if (!container) return;

  const loadMoreBtn = document.querySelector('[data-autos-load-more]');
  const ajaxUrl = container.dataset.ajaxUrl;
  const perPage = Number(container.dataset.perPage || 6);
  let offset = Number(container.dataset.offset || 0);
  const total = Number(container.dataset.total || 0);
  let loading = false;

  if (!ajaxUrl || !loadMoreBtn) return;

  const setIdleState = () => {
    loadMoreBtn.disabled = false;
    loadMoreBtn.classList.remove('opacity-70', 'cursor-not-allowed');
    loadMoreBtn.innerHTML =
      '<span>Mostrar más</span><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"><path d="M12 5v14m-7-7h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" /></svg>';
  };

  loadMoreBtn.addEventListener('click', () => {
    if (loading) return;
    loading = true;
    loadMoreBtn.disabled = true;
    loadMoreBtn.classList.add('opacity-70', 'cursor-not-allowed');
    loadMoreBtn.innerHTML =
      '<svg class="h-5 w-5 animate-spin text-white" viewBox="0 0 24 24" fill="none"><circle class="opacity-30" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"/><path class="opacity-80" d="M22 12a10 10 0 0 1-10 10" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>';

    const url = new URL(ajaxUrl);
    url.searchParams.set('action', 'mauswp_autocaravanas_load_more');
    url.searchParams.set('offset', offset);
    url.searchParams.set('per_page', perPage);

    fetch(url.toString(), { credentials: 'same-origin' })
      .then((res) => res.json())
      .then((data) => {
        if (data.success && data.data?.html) {
          container.insertAdjacentHTML('beforeend', data.data.html);
          offset += perPage;
          if (!data.data.has_more || offset >= total) {
            loadMoreBtn.remove();
          } else {
            setIdleState();
          }
        } else {
          setIdleState();
        }
      })
      .catch(() => setIdleState())
      .finally(() => {
        loading = false;
      });
  });
};

const initTrustindexHeaderAlign = () => {
  const updateClass = () => {
    document
      .querySelectorAll('.mauswp-trustindex .ti-v-center')
      .forEach((el) => {
        el.classList.remove('ti-v-center');
        el.classList.add('ti-v-left');
      });
  };

  updateClass();

  const observer = new MutationObserver(updateClass);
  observer.observe(document.body, { childList: true, subtree: true });
};

const initHeroTimelineSlider = () => {
  if (typeof Swiper === 'undefined') return;

  document.querySelectorAll('.js-hero-timeline-slider').forEach((block) => {
    if (block.dataset.init === '1') return;
    block.dataset.init = '1';

    const swiperEl = block.querySelector('.js-hero-timeline-swiper');
    if (!swiperEl) return;

    const bullets = Array.from(block.querySelectorAll('[data-hero-bullet]'));
    const bulletsMobile = Array.from(
      block.querySelectorAll('[data-hero-bullet-mobile]')
    );

    const setActiveBullet = (index) => {
      const update = (btns) => {
        btns.forEach((btn, idx) => {
          const isActive = idx === index;
          btn.dataset.active = isActive ? 'true' : 'false';
          if (isActive) {
            btn.setAttribute('aria-current', 'true');
          } else {
            btn.removeAttribute('aria-current');
          }
        });
      };
      update(bullets);
      update(bulletsMobile);
    };

    const setActiveContent = (swiper) => {
      const contents = swiperEl.querySelectorAll('[data-hero-slide-content]');
      contents.forEach((el) => {
        el.classList.add('opacity-0', 'translate-y-3');
        el.classList.remove('opacity-100', 'translate-y-0');
      });
      const active = swiper.slides[swiper.activeIndex]?.querySelector(
        '[data-hero-slide-content]'
      );
      if (active) {
        active.classList.remove('opacity-0', 'translate-y-3');
        active.classList.add('opacity-100', 'translate-y-0');
      }
    };

    const swiper = new Swiper(swiperEl, {
      slidesPerView: 1,
      speed: 900,
      direction: 'vertical',
      autoHeight: true,
      loop: true,
      allowTouchMove: false,
      simulateTouch: false,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      },
      on: {
        init() {
          setActiveBullet(this.realIndex);
          setActiveContent(this);
        },
        slideChange() {
          setActiveBullet(this.realIndex);
          setActiveContent(this);
        },
      },
    });

    bullets.forEach((btn) => {
      btn.addEventListener('click', () => {
        const index = Number(btn.dataset.index || 0);
        swiper.slideToLoop(index);
      });
    });

    bulletsMobile.forEach((btn) => {
      btn.addEventListener('click', () => {
        const index = Number(btn.dataset.index || 0);
        swiper.slideToLoop(index);
      });
    });
  });
};

document.addEventListener('DOMContentLoaded', () => {
  initMobileMenu();
  initHeroVideoLazy();
  initScrollFade();
  initAutocaravanasCarouselTabs();
  initAutocaravanasCategorias();
  initTrustindexHeaderAlign();
  initHeroTimelineSlider();
  initAutocaravanasLoadMore();
});
