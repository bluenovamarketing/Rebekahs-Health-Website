const { chromium } = require('playwright');

const base = 'https://wordpress-1651482-6565113.cloudwaysapps.com';
const browserPath = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';
const allRoutes = [
  '/',
  '/our-story/',
  '/our-team/',
  '/locations/',
  '/locations/lapeer/',
  '/locations/grand-blanc/',
  '/locations/clarkston/',
  '/locations/lake-orion/',
  '/events/',
  '/blog/',
  '/in-store-products/',
  '/practitioners/',
  '/contact-us/',
  '/privacy-policy/',
  '/refund_returns/',
  '/terms-conditions/',
  '/disclaimer/',
  '/shop-fullscript/',
  '/shop-designs-for-health/',
  '/shop-lifewave/',
  '/peptides-injectables/'
];
const allViewports = [
  { label: 'phone', width: 390, height: 844 },
  { label: 'tablet', width: 768, height: 1024 },
  { label: 'desktop', width: 1440, height: 1000 }
];
const routeFilter = (process.env.QA_ROUTES || '').split(',').filter(Boolean);
const viewportFilter = (process.env.QA_VIEWPORTS || '').split(',').filter(Boolean);
const routes = routeFilter.length ? allRoutes.filter((route) => routeFilter.includes(route)) : allRoutes;
const viewports = viewportFilter.length ? allViewports.filter(({ label }) => viewportFilter.includes(label)) : allViewports;
const navigationTimeout = Number(process.env.QA_TIMEOUT || 15000);

(async () => {
  const browser = await chromium.launch({
    executablePath: browserPath,
    headless: true,
    args: ['--disable-dev-shm-usage']
  });

  const failures = [];
  let checks = 0;

  const pages = await Promise.all(viewports.map(async (viewport) => {
    const context = await browser.newContext({ viewport, serviceWorkers: 'block' });
    await context.route('**/*', async (route) => {
      const request = route.request();
      const type = request.resourceType();
      const external = new URL(request.url()).origin !== new URL(base).origin;
      if (external || ['image', 'media', 'font'].includes(type)) return route.abort();
      return route.continue();
    });
    return { viewport, context, page: await context.newPage() };
  }));

  for (const route of routes) {
    const results = await Promise.all(pages.map(async ({ viewport, page }) => {
      try {
        const url = process.env.QA_BUST_CACHE === '1'
          ? `${base}${route}${route.includes('?') ? '&' : '?'}responsive_qa=${Date.now()}`
          : `${base}${route}`;
        const response = await page.goto(url, { waitUntil: 'domcontentloaded', timeout: navigationTimeout });
        await page.waitForTimeout(500);
        const metrics = await page.evaluate(() => ({
          viewportWidth: document.documentElement.clientWidth,
          scrollWidth: document.documentElement.scrollWidth,
          h1Count: document.querySelectorAll('h1').length,
          criticalError: document.body.innerText.includes('There has been a critical error on this website'),
          overflowingElements: Array.from(document.querySelectorAll('body *'))
            .map((element) => {
              const rect = element.getBoundingClientRect();
              return {
                element: `${element.tagName.toLowerCase()}${element.id ? `#${element.id}` : ''}${element.classList.length ? `.${Array.from(element.classList).join('.')}` : ''}`,
                text: (element.textContent || '').trim().replace(/\s+/g, ' ').slice(0, 80),
                href: element.getAttribute('href') || '',
                parent: element.parentElement ? `${element.parentElement.tagName.toLowerCase()}${element.parentElement.id ? `#${element.parentElement.id}` : ''}${element.parentElement.classList.length ? `.${Array.from(element.parentElement.classList).join('.')}` : ''}` : '',
                left: Math.round(rect.left),
                right: Math.round(rect.right),
                width: Math.round(rect.width)
              };
            })
            .filter(({ left, right }) => left < -1 || right > document.documentElement.clientWidth + 1)
            .slice(0, 12)
        }));
        return {
          route,
          viewport: viewport.label,
          status: response ? response.status() : 0,
          overflow: Math.max(0, metrics.scrollWidth - metrics.viewportWidth),
          h1Count: metrics.h1Count,
          criticalError: metrics.criticalError,
          overflowingElements: metrics.overflowingElements
        };
      } catch (error) {
        return { route, viewport: viewport.label, error: error.message };
      }
    }));

    checks += results.length;
    for (const result of results) {
      if (result.error || result.status !== 200 || result.overflow > 1 || result.h1Count !== 1 || result.criticalError) {
        failures.push(result);
      }
    }
    console.error(`checked ${route}`);
  }

  await Promise.all(pages.map(({ context }) => context.close()));
  await browser.close();
  console.log(JSON.stringify({ checks, routes: routes.length, viewports: viewports.length, failures }, null, 2));
  if (failures.length) process.exitCode = 1;
})();
