(() => {
  'use strict';

  const searchToggle = document.querySelector('.rhn-store-search-toggle');
  const storeSearch = document.querySelector('.rhn-store-search');
  const siteMenu = document.querySelector('#site-header .menu');
  const siteNav = document.querySelector('#site-header #nav');

  if (searchToggle && storeSearch) {
    searchToggle.addEventListener('click', () => {
      const willOpen = searchToggle.getAttribute('aria-expanded') !== 'true';
      searchToggle.setAttribute('aria-expanded', String(willOpen));
      storeSearch.hidden = !willOpen;

      if (willOpen) {
        if (siteMenu) siteMenu.setAttribute('aria-expanded', 'false');
        if (siteNav) siteNav.classList.remove('open');
        const input = storeSearch.querySelector('input[type="search"]');
        if (input) input.focus();
      }
    });

    if (siteMenu) {
      siteMenu.addEventListener('click', () => {
        if (siteMenu.getAttribute('aria-expanded') === 'true') {
          searchToggle.setAttribute('aria-expanded', 'false');
          storeSearch.hidden = true;
        }
      });
    }
  }

  const productMainImage = document.querySelector('[data-rhn-product-main-image]');
  const productThumbs = Array.from(document.querySelectorAll('[data-rhn-product-image]'));
  productThumbs.forEach((thumb) => thumb.addEventListener('click', () => {
    if (!productMainImage) return;
    productMainImage.src = thumb.dataset.rhnProductImage;
    productMainImage.alt = thumb.dataset.rhnProductAlt || '';
    productThumbs.forEach((item) => item.setAttribute('aria-current', String(item === thumb)));
  }));

  document.querySelectorAll('.rhn-catalog-filters').forEach((form) => form.addEventListener('submit', () => {
    const results = document.querySelector('.rhn-catalog-results');
    if (results) results.classList.add('processing');
  }));

  const drawer = document.querySelector('[data-rhn-filter-drawer]');
  const openers = Array.from(document.querySelectorAll('[data-rhn-filter-open]'));
  if (!drawer || openers.length === 0) return;

  const closeControls = Array.from(drawer.querySelectorAll('[data-rhn-filter-close]'));
  const panel = drawer.querySelector('[role="dialog"]');
  const mobileFilters = window.matchMedia('(max-width: 820px)');
  let returnFocus = null;
  let isOpen = false;

  const focusable = () => Array.from(drawer.querySelectorAll('a[href],button:not([disabled]),input:not([disabled]),select:not([disabled]),textarea:not([disabled]),[tabindex]:not([tabindex="-1"])'))
    .filter((element) => element.getClientRects().length > 0);

  const openDrawer = (trigger) => {
    if (!mobileFilters.matches) return;
    returnFocus = trigger;
    isOpen = true;
    drawer.hidden = false;
    document.documentElement.classList.add('rhn-filter-drawer-open');
    trigger.setAttribute('aria-expanded', 'true');
    const targets = focusable();
    (targets[0] || panel || drawer).focus();
  };

  const closeDrawer = () => {
    isOpen = false;
    drawer.hidden = mobileFilters.matches;
    document.documentElement.classList.remove('rhn-filter-drawer-open');
    openers.forEach((opener) => opener.setAttribute('aria-expanded', 'false'));
    if (returnFocus) returnFocus.focus();
  };

  const syncDrawerMode = () => {
    if (mobileFilters.matches) {
      drawer.hidden = !isOpen;
    } else {
      isOpen = false;
      drawer.hidden = false;
      document.documentElement.classList.remove('rhn-filter-drawer-open');
      openers.forEach((opener) => opener.setAttribute('aria-expanded', 'false'));
    }
  };

  openers.forEach((opener) => opener.addEventListener('click', () => openDrawer(opener)));
  closeControls.forEach((control) => control.addEventListener('click', closeDrawer));

  drawer.addEventListener('keydown', (event) => {
    if (!mobileFilters.matches) return;
    if (event.key === 'Escape') {
      event.preventDefault();
      closeDrawer();
      return;
    }

    if (event.key !== 'Tab') return;
    const targets = focusable();
    if (targets.length === 0) return;
    const first = targets[0];
    const last = targets[targets.length - 1];
    if (event.shiftKey && document.activeElement === first) {
      event.preventDefault();
      last.focus();
    } else if (!event.shiftKey && document.activeElement === last) {
      event.preventDefault();
      first.focus();
    }
  });

  if (typeof mobileFilters.addEventListener === 'function') {
    mobileFilters.addEventListener('change', syncDrawerMode);
  } else {
    mobileFilters.addListener(syncDrawerMode);
  }
  syncDrawerMode();
})();
