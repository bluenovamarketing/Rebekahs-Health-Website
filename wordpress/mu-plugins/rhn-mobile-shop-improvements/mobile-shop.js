(() => {
  'use strict';

  const breakpoint = window.matchMedia('(max-width: 820px)');
  const body = document.body;
  const header = document.querySelector('.rhn-catalog-head');
  const lede = header?.querySelector('p');
  const panel = document.querySelector('[data-rhn-filter-drawer]');
  const layout = document.querySelector('.rhn-catalog-layout');
  const toolbarLead = document.querySelector('.rhn-catalog-toolbar__summary strong');
  const ordering = document.querySelector('.rhn-catalog-toolbar .woocommerce-ordering');
  const groups = panel ? Array.from(panel.querySelectorAll('.rhn-filter-group')) : [];

  if (!body || !header || !panel || !layout || groups.length === 0) return;

  const original = {
    lede: lede?.textContent || '',
    toolbarLead: toolbarLead?.textContent || '',
    parent: panel.parentNode,
    nextSibling: panel.nextSibling,
    groupLabels: groups.map((group) => group.querySelector('summary > span:first-child')?.textContent || ''),
    groupOpen: groups.map((group) => group.open),
  };

  const phone = document.createElement('p');
  phone.className = 'rhn-mobile-shop-phone';
  phone.innerHTML = 'Need help choosing a product? Call <a href="tel:+12488432011">(248) 843-2011</a>.';

  const sortLabel = document.createElement('span');
  sortLabel.className = 'rhn-mobile-sort-label';
  sortLabel.textContent = 'Sort products';

  const setGroupLabel = (group, label) => {
    const target = group.querySelector('summary > span:first-child');
    if (target) target.textContent = label;
  };

  const restorePanelPosition = () => {
    if (!original.parent) return;
    if (original.nextSibling && original.nextSibling.parentNode === original.parent) {
      original.parent.insertBefore(panel, original.nextSibling);
    } else {
      original.parent.appendChild(panel);
    }
  };

  const applyMobileState = () => {
    body.classList.add('rhn-mobile-shop-enhanced');
    if (lede) lede.textContent = 'Search products, shop by category, or shop by brand.';
    if (toolbarLead) toolbarLead.textContent = 'Shop products';

    header.appendChild(panel);
    panel.hidden = false;
    panel.removeAttribute('aria-hidden');
    groups.forEach((group, index) => {
      setGroupLabel(group, index === 0 ? 'Shop by category' : index === 1 ? 'Shop by brand' : original.groupLabels[index]);
      group.open = true;
    });

    if (!phone.isConnected) header.appendChild(phone);
    if (ordering && !sortLabel.isConnected) ordering.insertBefore(sortLabel, ordering.firstChild);
  };

  const restoreDesktopState = () => {
    body.classList.remove('rhn-mobile-shop-enhanced');
    if (lede) lede.textContent = original.lede;
    if (toolbarLead) toolbarLead.textContent = original.toolbarLead;
    phone.remove();
    sortLabel.remove();
    groups.forEach((group, index) => {
      setGroupLabel(group, original.groupLabels[index]);
      group.open = original.groupOpen[index];
    });
    restorePanelPosition();
    panel.hidden = false;
  };

  const sync = () => {
    if (breakpoint.matches) applyMobileState();
    else restoreDesktopState();
  };

  if (typeof breakpoint.addEventListener === 'function') breakpoint.addEventListener('change', sync);
  else breakpoint.addListener(sync);

  sync();
})();
