(function () {
  const menu = document.querySelector('.menu');
  const nav = document.querySelector('#nav');
  const triggers = Array.from(document.querySelectorAll('.nav-trigger'));
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

  document.querySelectorAll('.rhn-copy-link').forEach(function (button) {
    button.addEventListener('click', function () {
      if (!navigator.clipboard) return;
      navigator.clipboard.writeText(button.dataset.url).then(function () {
        button.setAttribute('aria-label', 'Article link copied');
      });
    });
  });
})();
