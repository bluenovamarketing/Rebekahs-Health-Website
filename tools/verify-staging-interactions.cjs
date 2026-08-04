const { chromium } = require('playwright');

(async () => {
const base = 'https://wordpress-1651482-6565113.cloudwaysapps.com';
const failures = [];
const checked = [];
const browser = await chromium.launch({ headless: true, executablePath: 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe' });
const page = await browser.newPage({ viewport: { width: 390, height: 844 } });
page.setDefaultTimeout(20000);
page.on('pageerror', error => failures.push(`pageerror: ${error.message}`));

async function open(path) {
  await page.goto(base + path, { waitUntil: 'domcontentloaded', timeout: 30000 });
  await page.waitForTimeout(1200);
  checked.push(path);
}

await open('/');
const menu = page.locator('.menu');
await menu.click();
if (await menu.getAttribute('aria-expanded') !== 'true') failures.push('mobile menu did not open');
if (!(await page.locator('#nav').evaluate(el => el.classList.contains('open')))) failures.push('mobile nav missing open state');
const about = page.locator('.nav-trigger').filter({ hasText: 'About' });
await about.click();
if (await about.getAttribute('aria-expanded') !== 'true') failures.push('mobile About dropdown did not open');
await page.keyboard.press('Escape');
if (await menu.getAttribute('aria-expanded') !== 'false') failures.push('Escape did not close mobile menu');
const footerSummary = page.locator('.footer-group summary').first();
const footerInitial = await footerSummary.evaluate(el => el.parentElement.open);
await footerSummary.click();
const footerAfterClick = await footerSummary.evaluate(el => el.parentElement.open);
if (footerAfterClick === footerInitial) failures.push('mobile footer accordion did not toggle');
if (!footerAfterClick) await footerSummary.click();
const footerMark = await footerSummary.evaluate(el => getComputedStyle(el, '::after').content);
if (footerMark.includes('Ã') || footerMark.includes('â')) failures.push(`mobile footer accordion mark is garbled: ${footerMark}`);

for (const [path, store] of [
  ['/locations/lapeer/', 'lapeer'],
  ['/locations/grand-blanc/', 'grand-blanc'],
  ['/locations/clarkston/', 'clarkston'],
  ['/locations/lake-orion/', 'lake-orion'],
]) {
  await open(path);
  const form = page.locator('.rhn-location-newsletter form.forminator-custom-form-313');
  if (await form.count() !== 1) failures.push(`${path} missing live newsletter form`);
  const select = form.locator('select[name="select-1"]');
  if (await select.count() !== 1 || await select.inputValue() !== store) failures.push(`${path} preferred store not preselected`);
  if (await form.locator('button[type="submit"], button.forminator-button-submit').count() !== 1) failures.push(`${path} missing submit button`);
  const overflow = await page.evaluate(() => Math.max(0, document.documentElement.scrollWidth - document.documentElement.clientWidth));
  if (overflow > 1) failures.push(`${path} horizontal overflow ${overflow}px`);
}

await open('/blog/');
const articleCards = page.locator('[data-category]');
const beforeArticles = await articleCards.count();
await page.locator('.filter').filter({ hasText: 'Healthy living' }).click();
const visibleArticles = await articleCards.evaluateAll(cards => cards.filter(card => getComputedStyle(card).display !== 'none').length);
if (!beforeArticles || !visibleArticles || visibleArticles >= beforeArticles) failures.push(`blog category filter did not narrow articles (before=${beforeArticles}, visible=${visibleArticles}, hidden=${await articleCards.evaluateAll(cards => cards.filter(card => card.hidden).length)})`);

await open('/in-store-products/');
const productCards = page.locator('.category[data-kind]');
const beforeProducts = await productCards.count();
await page.locator('.filter').filter({ hasText: 'Daily wellness' }).click();
const visibleProducts = await productCards.evaluateAll(cards => cards.filter(card => getComputedStyle(card).display !== 'none').length);
if (!beforeProducts || !visibleProducts || visibleProducts >= beforeProducts) failures.push('product category filter did not narrow cards');

await open('/practitioners/');
const practitionerCards = page.locator('#grid .card[data-search]');
const practitionerCount = await practitionerCards.count();
await page.locator('#search').fill('Lapeer');
await page.waitForTimeout(100);
const visiblePractitioners = await practitionerCards.evaluateAll(cards => cards.filter(card => !card.hidden && getComputedStyle(card).display !== 'none').length);
if (practitionerCount < 20 || !visiblePractitioners || visiblePractitioners >= practitionerCount) failures.push('practitioner search did not narrow directory');

await browser.close();
console.log(JSON.stringify({ checks: checked.length, checked, failures }, null, 2));
process.exit(failures.length ? 1 : 0);
})().catch(error => {
  console.error(error);
  process.exit(1);
});
