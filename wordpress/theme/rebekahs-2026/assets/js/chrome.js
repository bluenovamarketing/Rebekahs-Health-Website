(function () {
  const menu = document.querySelector('.menu');
  const nav = document.querySelector('#nav');
  const triggers = Array.from(document.querySelectorAll('.nav-trigger'));
  const footerGroups = Array.from(document.querySelectorAll('#site-footer .footer-group'));
  const tabletFooterButtons = Array.from(document.querySelectorAll('#site-footer .tablet-tab'));
  const tabletFooterPanels = Array.from(document.querySelectorAll('#site-footer .tablet-panel'));
  let footerIsCompact;
  if (!menu || !nav) return;
  function closeDropdowns(except) {
    triggers.forEach(function (trigger) {
      if (trigger === except) return;
      trigger.setAttribute('aria-expanded', 'false');
      trigger.closest('.nav-dropdown').classList.remove('is-open');
    });
  }
  function closeMenu() {
    nav.classList.remove('open');
    menu.setAttribute('aria-expanded', 'false');
    closeDropdowns();
  }
  triggers.forEach(function (trigger) {
    trigger.addEventListener('click', function () {
      const open = trigger.getAttribute('aria-expanded') !== 'true';
      closeDropdowns(trigger);
      trigger.setAttribute('aria-expanded', String(open));
      trigger.closest('.nav-dropdown').classList.toggle('is-open', open);
    });
  });
  menu.addEventListener('click', function () {
    const open = menu.getAttribute('aria-expanded') !== 'true';
    menu.setAttribute('aria-expanded', String(open));
    nav.classList.toggle('open', open);
    if (!open) closeDropdowns();
  });
  nav.querySelectorAll('a').forEach(function (link) { link.addEventListener('click', closeMenu); });
  document.addEventListener('click', function (event) { if (!event.target.closest('.site-header')) closeDropdowns(); });
  document.addEventListener('keydown', function (event) { if (event.key === 'Escape') closeMenu(); });

  function closeTabletFooter(except) {
    tabletFooterButtons.forEach(function (button) {
      if (button === except) return;
      button.setAttribute('aria-expanded', 'false');
    });
    tabletFooterPanels.forEach(function (panel) {
      if (except && panel.id === except.getAttribute('aria-controls')) return;
      panel.hidden = true;
    });
  }
  tabletFooterButtons.forEach(function (button) {
    button.addEventListener('click', function () {
      const panel = document.querySelector('#' + button.getAttribute('aria-controls'));
      const willOpen = button.getAttribute('aria-expanded') !== 'true';
      closeTabletFooter(button);
      button.setAttribute('aria-expanded', String(willOpen));
      panel.hidden = !willOpen;
    });
  });

  function syncResponsiveState() {
    if (window.innerWidth > 1120) closeMenu();
    if (window.innerWidth <= 720 || window.innerWidth > 1000) closeTabletFooter();
    const isCompactFooter = window.innerWidth <= 1000;
    if (isCompactFooter !== footerIsCompact) {
      footerGroups.forEach(function (group) { group.open = !isCompactFooter; });
      footerIsCompact = isCompactFooter;
    }
  }
  syncResponsiveState();
  window.addEventListener('resize', syncResponsiveState);

  document.querySelectorAll('.rhn-copy-link').forEach(function (button) {
    button.addEventListener('click', function () {
      if (!navigator.clipboard) return;
      navigator.clipboard.writeText(button.dataset.url).then(function () {
        button.setAttribute('aria-label', 'Article link copied');
      });
    });
  });

  document.querySelectorAll('.rhn-location-newsletter[data-default-store]').forEach(function (container) {
    const select = container.querySelector('select[name="select-1"]');
    if (!select || select.value) return;
    select.value = container.dataset.defaultStore;
    select.dispatchEvent(new Event('change', { bubbles: true }));
  });
})();
