(() => {
  const sourceUrl = 'header-footer-mockup-v1.9.html';

  async function mountApprovedChrome() {
    const response = await fetch(sourceUrl, { cache: 'no-store' });
    if (!response.ok) throw new Error(`Approved chrome source returned ${response.status}`);

    const sourceHtml = await response.text();
    const sourceDocument = new DOMParser().parseFromString(sourceHtml, 'text/html');
    const approvedStyle = sourceDocument.querySelector('head style');
    const approvedHeader = sourceDocument.querySelector('body > header.site-header');
    const approvedFooter = sourceDocument.querySelector('body > footer');
    const approvedBehavior = [...sourceDocument.querySelectorAll('body > script:not([src])')].pop();
    const headerMount = document.querySelector('[data-approved-header-mount="v1.9"]');
    const footerMount = document.querySelector('[data-approved-footer-mount="v1.9"]');
    const compatibilityLink = document.querySelector('#approved-chrome-compat');

    if (!approvedStyle || !approvedHeader || !approvedFooter || !approvedBehavior || !headerMount || !footerMount || !compatibilityLink) {
      throw new Error('Approved header/footer v1.9 source is incomplete');
    }

    const sourceStyle = document.createElement('style');
    sourceStyle.dataset.approvedSource = 'header-footer-v1.9';
    sourceStyle.textContent = approvedStyle.textContent;
    compatibilityLink.before(sourceStyle);

    approvedHeader.dataset.approvedSource = 'v1.9';
    approvedFooter.dataset.approvedSource = 'v1.9';
    headerMount.replaceWith(approvedHeader);
    footerMount.replaceWith(approvedFooter);

    const sourceBehavior = document.createElement('script');
    sourceBehavior.dataset.approvedSource = 'header-footer-v1.9';
    sourceBehavior.textContent = approvedBehavior.textContent;
    document.body.append(sourceBehavior);
    document.documentElement.dataset.approvedChromeReady = 'v1.9';
  }

  mountApprovedChrome().catch((error) => {
    console.error('Unable to load the approved header/footer v1.9 source.', error);
  });
})();
