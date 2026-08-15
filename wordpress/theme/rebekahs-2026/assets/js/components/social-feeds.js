(function () {
  'use strict';

  var postSelectors = {
    instagram: '.sbi_item',
    tiktok: '.sbtt-post-item'
  };

  function updateFeed(shell) {
    var platform = shell.getAttribute('data-rhn-social-feed');
    var live = shell.querySelector('.rhn-social-feed-live');
    var fallback = shell.querySelector('.rhn-social-feed-fallback');
    var selector = postSelectors[platform];
    var hasPosts = Boolean(live && selector && live.querySelector(selector));

    shell.classList.toggle('is-live', hasPosts);

    if (live) {
      live.setAttribute('aria-hidden', hasPosts ? 'false' : 'true');
    }
    if (fallback) {
      fallback.setAttribute('aria-hidden', hasPosts ? 'true' : 'false');
    }
  }

  function initializeFeed(shell) {
    updateFeed(shell);

    var live = shell.querySelector('.rhn-social-feed-live');
    if (!live || typeof MutationObserver === 'undefined') {
      return;
    }

    var observer = new MutationObserver(function () {
      updateFeed(shell);
      if (shell.classList.contains('is-live')) {
        observer.disconnect();
      }
    });

    observer.observe(live, { childList: true, subtree: true });
    window.setTimeout(function () {
      updateFeed(shell);
      observer.disconnect();
    }, 30000);
  }

  function initializeSocialFeeds() {
    document.querySelectorAll('.rhn-social-feed-switch').forEach(initializeFeed);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeSocialFeeds);
  } else {
    initializeSocialFeeds();
  }
}());
