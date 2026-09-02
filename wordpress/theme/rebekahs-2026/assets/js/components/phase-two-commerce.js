(() => {
  'use strict';

  const drawer = document.querySelector('[data-rhn-filter-drawer]');
  const openers = Array.from(document.querySelectorAll('[data-rhn-filter-open]'));

  if (!drawer || openers.length === 0) return;

  const closeControls = Array.from(drawer.querySelectorAll('[data-rhn-filter-close]'));
  const panel = drawer.querySelector('[role="dialog"]');
  let returnFocus = null;

  const focusable = () => Array.from(drawer.querySelectorAll('a[href],button:not([disabled]),input:not([disabled]),select:not([disabled]),textarea:not([disabled]),[tabindex]:not([tabindex="-1"])'))
    .filter((element) => element.getClientRects().length > 0);

  const openDrawer = (trigger) => {
    returnFocus = trigger;
    drawer.hidden = false;
    document.documentElement.classList.add('rhn-filter-drawer-open');
    trigger.setAttribute('aria-expanded', 'true');
    const targets = focusable();
    (targets[0] || panel || drawer).focus();
  };

  const closeDrawer = () => {
    drawer.hidden = true;
    document.documentElement.classList.remove('rhn-filter-drawer-open');
    openers.forEach((opener) => opener.setAttribute('aria-expanded', 'false'));
    if (returnFocus) returnFocus.focus();
  };

  openers.forEach((opener) => opener.addEventListener('click', () => openDrawer(opener)));
  closeControls.forEach((control) => control.addEventListener('click', closeDrawer));

  drawer.addEventListener('keydown', (event) => {
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
})();

