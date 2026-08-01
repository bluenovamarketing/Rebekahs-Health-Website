(() => {
  const chromeHeader = document.querySelector('.chrome-header');
  const chromeNav = chromeHeader?.querySelector('#nav');
  const chromeMenu = chromeHeader?.querySelector('.menu');
  const dropdownTriggers = [...document.querySelectorAll('.chrome-header .nav-trigger')];
  const footerGroups = [...document.querySelectorAll('.global-footer .footer-group')];
  const tabletFooterButtons = [...document.querySelectorAll('.global-footer .tablet-tab')];
  const tabletFooterPanels = [...document.querySelectorAll('.global-footer .tablet-panel')];
  let footerIsCompact;

  if (!chromeHeader || !chromeNav || !chromeMenu) return;

  function closeDropdowns(except) {
    dropdownTriggers.forEach((trigger) => {
      if (trigger === except) return;
      trigger.setAttribute('aria-expanded', 'false');
      trigger.closest('.nav-dropdown')?.classList.remove('is-open');
    });
  }

  function closeMenu() {
    chromeNav.classList.remove('open');
    chromeMenu.setAttribute('aria-expanded', 'false');
    closeDropdowns();
  }

  dropdownTriggers.forEach((trigger) => {
    trigger.addEventListener('click', () => {
      const willOpen = trigger.getAttribute('aria-expanded') !== 'true';
      closeDropdowns(trigger);
      trigger.setAttribute('aria-expanded', String(willOpen));
      trigger.closest('.nav-dropdown')?.classList.toggle('is-open', willOpen);
    });
  });

  document.addEventListener('click', (event) => {
    if (!event.target.closest('.chrome-header')) closeDropdowns();
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
      closeMenu();
      chromeMenu.focus();
    }
  });

  function closeTabletFooter(except) {
    tabletFooterButtons.forEach((button) => {
      if (button === except) return;
      button.setAttribute('aria-expanded', 'false');
    });
    tabletFooterPanels.forEach((panel) => {
      if (except && panel.id === except.getAttribute('aria-controls')) return;
      panel.hidden = true;
    });
  }

  tabletFooterButtons.forEach((button) => {
    button.addEventListener('click', () => {
      const panel = document.getElementById(button.getAttribute('aria-controls'));
      const willOpen = button.getAttribute('aria-expanded') !== 'true';
      closeTabletFooter(button);
      button.setAttribute('aria-expanded', String(willOpen));
      if (panel) panel.hidden = !willOpen;
    });
  });

  function syncResponsiveState() {
    if (window.innerWidth > 1120) closeMenu();
    if (window.innerWidth <= 720 || window.innerWidth > 1000) closeTabletFooter();
    const isCompactFooter = window.innerWidth <= 1000;
    if (isCompactFooter !== footerIsCompact) {
      footerGroups.forEach((group) => { group.open = !isCompactFooter; });
      footerIsCompact = isCompactFooter;
    }
  }

  syncResponsiveState();
  window.addEventListener('resize', syncResponsiveState);
})();
