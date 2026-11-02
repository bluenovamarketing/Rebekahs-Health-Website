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

  document.querySelectorAll('[data-rhn-placeholder-view]').forEach((thumb) => thumb.addEventListener('click', () => {
    const gallery = thumb.closest('.rhn-product-gallery');
    if (!gallery) return;
    gallery.querySelectorAll('[data-rhn-placeholder-view]').forEach((item) => item.setAttribute('aria-current', String(item === thumb)));
    const currentView = gallery.querySelector('[data-rhn-placeholder-current-view]');
    if (currentView) currentView.textContent = thumb.dataset.rhnPlaceholderView || 'Front image';
    const mainImage = gallery.querySelector('.rhn-product-main-image--fallback .rhn-product-image-fallback');
    if (mainImage) mainImage.setAttribute('aria-label', thumb.getAttribute('aria-label') || 'Product image coming soon');
  }));

  document.querySelectorAll('.rhn-catalog-filters').forEach((form) => form.addEventListener('submit', () => {
    const results = document.querySelector('.rhn-catalog-results');
    if (results) results.classList.add('processing');
  }));

  // WooCommerce Blocks can select Pickup when it is the only rate available
  // before a new shopper enters an address. Default a new checkout tab to Ship,
  // then preserve any delivery choice the shopper deliberately makes.
  const deliveryChoiceKey = 'rhnCheckoutDeliveryChoice';
  let applyingShipDefault = false;
  let deliveryRefreshTimer = 0;

  const refreshCheckoutForDeliveryRules = () => {
    if (deliveryRefreshTimer) return;
    deliveryRefreshTimer = window.setTimeout(() => window.location.reload(), 1400);
  };

  const deliveryControlFor = (target) => {
    if (!(target instanceof Element)) return null;
    const control = target.closest('input[type="radio"],[role="radio"]');
    return control instanceof HTMLElement ? control : null;
  };

  const deliveryChoiceFor = (target) => {
    const input = deliveryControlFor(target);
    if (!input) return '';
    const id = input.id || '';
    const label = id ? document.querySelector(`label[for="${CSS.escape(id)}"]`) : null;
    const text = (input.getAttribute('aria-label') || label?.textContent || input.textContent || input.parentElement?.textContent || '')
      .replace(/\s+/g, ' ')
      .trim();
    if (/^Ship(?:\s|$)/i.test(text)) return 'ship';
    if (/^Pickup(?:\s|$)/i.test(text)) return 'pickup';
    return '';
  };

  const savedDeliveryChoice = () => {
    try {
      return window.sessionStorage.getItem(deliveryChoiceKey) || '';
    } catch (error) {
      return '';
    }
  };

  const saveDeliveryChoice = (choice) => {
    try {
      window.sessionStorage.setItem(deliveryChoiceKey, choice);
    } catch (error) {
      // Checkout still works when browser storage is unavailable.
    }
  };

  const deliveryRadios = () => Array.from(document.querySelectorAll('input[type="radio"],[role="radio"]'))
    .map((input) => ({ input, choice: deliveryChoiceFor(input) }))
    .filter(({ choice }) => choice);

  const deliveryRadioIsChecked = (input) => input instanceof HTMLInputElement
    ? input.checked
    : input.getAttribute('aria-checked') === 'true';

  const applyShipDefault = () => {
    if (savedDeliveryChoice()) return;
    const radios = deliveryRadios();
    const ship = radios.find(({ choice }) => choice === 'ship')?.input;
    const pickup = radios.find(({ choice }) => choice === 'pickup')?.input;
    if (!ship || !pickup || deliveryRadioIsChecked(ship) || !deliveryRadioIsChecked(pickup)) return;

    applyingShipDefault = true;
    ship.click();
    refreshCheckoutForDeliveryRules();
    window.requestAnimationFrame(() => { applyingShipDefault = false; });
  };

  if (document.body.classList.contains('woocommerce-checkout')) {
    const rememberDeliveryChoice = (event) => {
      if (applyingShipDefault || !event.isTrusted) return;
      const choice = deliveryChoiceFor(event.target);
      if (choice) {
        saveDeliveryChoice(choice);
        refreshCheckoutForDeliveryRules();
      }
    };
    document.addEventListener('click', rememberDeliveryChoice, true);
    document.addEventListener('change', rememberDeliveryChoice, true);

    const checkoutObserver = new MutationObserver(applyShipDefault);
    checkoutObserver.observe(document.body, { childList: true, subtree: true });
    [0, 250, 1000, 2500].forEach((delay) => window.setTimeout(applyShipDefault, delay));
  }

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
