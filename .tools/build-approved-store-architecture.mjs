import fs from 'node:fs';

function assert(condition, message) {
  if (!condition) throw new Error(message);
}

function replaceOnce(source, find, replacement, label) {
  const first = source.indexOf(find);
  assert(first !== -1, `Missing ${label}`);
  assert(source.indexOf(find, first + find.length) === -1, `Multiple matches for ${label}`);
  return source.slice(0, first) + replacement + source.slice(first + find.length);
}

const homepageSource = 'online-store-homepage-mockup-v1.3.html';
const homepageTarget = 'main-homepage-ecommerce-integration-v1.4.html';
let homepage = fs.readFileSync(homepageSource, 'utf8');

homepage = replaceOnce(
  homepage,
  '<!-- Online Store Homepage v1.3: actual live homepage body preserved; Phase Two commerce section added; no global chrome. -->',
  '<!-- Main Homepage Ecommerce Integration v1.4: actual live homepage body preserved; Phase Two commerce entry points added; no global chrome. -->',
  'homepage file comment',
);
homepage = replaceOnce(
  homepage,
  '<title>Online Store Homepage v1.3 | Rebekah\'s Health & Nutrition</title>',
  '<title>Main Homepage Ecommerce Integration v1.4 | Rebekah\'s Health & Nutrition</title>',
  'homepage title',
);
homepage = replaceOnce(
  homepage,
  'Phase Two · Homepage v1.3 · Page body only',
  'Phase Two · Main Homepage Ecommerce Integration v1.4 · Page Body Only',
  'homepage version label',
);

const sectionIds = [...homepage.matchAll(/data-mock-section="([^"]+)"/g)].map(match => match[1]);
assert(sectionIds.join('|') === '2|3|3b|4|5|6|7|8|9|10|10b|11|12|13', 'Homepage component sequence changed');
assert((homepage.match(/class="phase-product"/g) || []).length === 4, 'Homepage Phase Two product count changed');
assert(!/<header\b|<footer\b|<nav\b/i.test(homepage), 'Homepage contains prohibited global chrome');
fs.writeFileSync(homepageTarget, homepage);

const shopSource = 'shop-catalog-template-v1.2.html';
const shopTarget = 'shop-catalog-template-v1.3.html';
let shop = fs.readFileSync(shopSource, 'utf8');

shop = replaceOnce(
  shop,
  '<title>Shop + Catalog Template Body v1.2 | Rebekah\'s Health & Nutrition</title>',
  '<title>Shop Homepage + Product Catalog v1.3 | Rebekah\'s Health & Nutrition</title>',
  'shop title',
);
shop = replaceOnce(
  shop,
  '.catalog-head{display:flex;',
  '.shop-home-paths{margin:30px 0 18px;display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px}.shop-path{min-height:142px;padding:22px;display:flex;flex-direction:column;justify-content:flex-end;border:1px solid rgba(23,76,60,.13);border-radius:18px;background:linear-gradient(145deg,#fff,#eef4ec);transition:transform .2s ease,box-shadow .2s ease}.shop-path:hover,.shop-path:focus-visible{transform:translateY(-3px);box-shadow:0 16px 32px rgba(23,76,60,.1)}.shop-path small{margin-bottom:8px;color:var(--leaf);font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase}.shop-path strong{color:var(--pine);font-family:Georgia,serif;font-size:21px;font-weight:500;line-height:1.2}.shop-trust-row{margin:0 0 24px;padding:18px 20px;display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px}.shop-trust-item{display:grid;gap:5px}.shop-trust-item strong{color:var(--pine);font-size:13px}.shop-trust-item span{color:#687a72;font-size:12px;line-height:1.45}@media(max-width:980px){.shop-home-paths,.shop-trust-row{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:560px){.shop-home-paths,.shop-trust-row{grid-template-columns:1fr}.shop-path{min-height:118px}.shop-trust-row{padding:18px}}\n+    .catalog-head{display:flex;',
  'shop homepage CSS insertion point',
);

const oldHead = '<main class="review-canvas mockup-page" id="main"><div class="version-chip">Shop + Catalog Template · v1.2 · Page Body Only · Internal Review · Local Only</div><div class="mockup-wrap"><nav class="crumbs" aria-label="Breadcrumb"><a href="#">Home</a><span>/</span><span>Online Store</span></nav><div class="catalog-head"><div><p class="page-kicker">System 03 · Reusable archive</p><h1 class="page-title">Shop All Products</h1><p class="page-lede">One catalog pattern for Shop All, category, brand, collection, and search-result pages.</p></div><form class="catalog-search" id="catalog-search"><label class="visually-hidden" for="catalog-query">Search this catalog</label><input id="catalog-query" type="search" placeholder="Search this catalog"><button type="submit" aria-label="Search">→</button></form></div>';
const newHead = '<main class="review-canvas mockup-page" id="main"><div class="version-chip">Shop Homepage + Product Catalog · v1.3 · Page Body Only · Internal Review · Local Only</div><div class="mockup-wrap"><div class="crumbs" aria-label="Breadcrumb"><a href="#">Home</a><span>/</span><span>Shop</span></div><div class="catalog-head"><div><p class="page-kicker">System 03 · Store homepage + reusable catalog</p><h1 class="page-title">Shop Rebekah\'s Online</h1><p class="page-lede">Browse the Clarkston-fulfilled pilot collection by wellness goal, search the catalog, or ask our team for help choosing where to begin.</p></div><form class="catalog-search" id="catalog-search"><label class="visually-hidden" for="catalog-query">Search this catalog</label><input id="catalog-query" type="search" placeholder="Search this catalog"><button type="submit" aria-label="Search">→</button></form></div><section class="shop-home-paths" aria-label="Shop by wellness goal"><a class="shop-path" href="#products-grid"><small>Shop by goal</small><strong>Energy &amp; Vitality</strong></a><a class="shop-path" href="#products-grid"><small>Shop by goal</small><strong>Immune Support</strong></a><a class="shop-path" href="#products-grid"><small>Shop by goal</small><strong>Stress &amp; Sleep</strong></a><a class="shop-path" href="#products-grid"><small>Shop by goal</small><strong>Brain &amp; Cognitive</strong></a></section><section class="shop-trust-row section-card" aria-label="Store fulfillment and help"><div class="shop-trust-item"><strong>Fulfilled in Clarkston</strong><span>Orders are packed by the Rebekah\'s team.</span></div><div class="shop-trust-item"><strong>Two-business-day handling</strong><span>Confirmed pilot handling expectation.</span></div><div class="shop-trust-item"><strong>Continental U.S. shipping</strong><span>Live shipping rates appear at checkout.</span></div><div class="shop-trust-item"><strong>Need product help?</strong><span>Contact the Clarkston store before ordering.</span></div></section>';
shop = replaceOnce(shop, oldHead, newHead, 'shop page heading and discovery content');

assert(!/<header\b|<footer\b|<nav\b/i.test(shop), 'Shop page contains prohibited global chrome');
assert((shop.match(/class="shop-path"/g) || []).length === 4, 'Shop category path count is not four');
assert((shop.match(/class="shop-trust-item"/g) || []).length === 4, 'Shop trust item count is not four');
assert((shop.match(/class="product-card"/g) || []).length === 6, 'Shop product card count changed');
assert((shop.match(/<h1\b/g) || []).length === 1, 'Shop H1 count is not one');
for (const requiredId of ['catalog-search', 'catalog-query', 'products-grid', 'no-results', 'reset-results', 'filter-drawer', 'open-filters', 'close-filters', 'apply-filters']) {
  assert(shop.includes(`id="${requiredId}"`), `Shop interaction ${requiredId} is missing`);
}
fs.writeFileSync(shopTarget, shop);

console.log(JSON.stringify({
  homepage: { file: homepageTarget, sections: sectionIds, phaseProducts: 4, globalChromeElements: 0 },
  shop: { file: shopTarget, categoryPaths: 4, trustItems: 4, productCards: 6, globalChromeElements: 0 },
}, null, 2));
