import fs from 'node:fs';

const catalog = fs.readFileSync('shop-catalog-template-v1.8.html', 'utf8');
const happyCssMatch = catalog.match(/\.placeholder-art\{[^}]+\}\.photo-placeholder\{[^}]+\}\.photo-placeholder:before,\.photo-placeholder:after\{[^}]+\}\.photo-placeholder:before\{[^}]+\}\.photo-placeholder:after\{[^}]+\}\.placeholder-brand-mark\{[^}]+\}\.placeholder-brand-mark img\{[^}]+\}\.placeholder-label\{[^}]+\}\.placeholder-kicker\{[^}]+\}/);
if (!happyCssMatch) throw new Error('Could not read the approved v1.8 placeholder CSS.');
const happyCss = happyCssMatch[0];

const sadCss = '.placeholder-art{min-height:220px;display:grid;place-items:center;overflow:hidden;border-radius:16px;background:linear-gradient(145deg,#f2f6ed,#fbf2df);color:var(--pine)}.botanical-placeholder{display:grid;place-items:center;gap:11px;padding:18px;text-align:center}.botanical-mark{position:relative;width:68px;height:58px;display:block}.botanical-mark:before,.botanical-mark:after{content:"";position:absolute;top:2px;width:29px;height:40px;border:2px solid #52785f;background:rgba(255,255,255,.62)}.botanical-mark:before{left:7px;border-radius:100% 0 100% 0;transform:rotate(-16deg)}.botanical-mark:after{right:7px;border-radius:0 100% 0 100%;transform:rotate(16deg)}.botanical-mark>span{position:absolute;left:50%;bottom:2px;height:40px;border-left:2px solid #52785f;transform:translateX(-50%)}.botanical-label{max-width:150px;color:#425f50;font-size:12px;font-weight:700;letter-spacing:.02em;line-height:1.25}';
const mark = '<span class="placeholder-brand-mark" aria-hidden="true"><img src="output/brand-kit/assets/logos/rebekahs-mark.svg" alt=""></span>';

createProductPages();
createPurchasePath();
createStoreStates();

function createProductPages() {
  let html = fs.readFileSync('product-page-templates-v1.5.html', 'utf8');
  html = replaceOnce(html, sadCss, happyCss, 'product-page base placeholder CSS');
  html = replaceOnce(
    html,
    '.image-placeholder-content{display:grid;place-items:center;gap:10px;text-align:center}.image-placeholder-content>.botanical-mark{width:82px;height:70px}.image-placeholder-content>.botanical-mark:before,.image-placeholder-content>.botanical-mark:after{width:34px;height:46px}.image-placeholder-content>.botanical-mark>span{height:48px}',
    '.image-placeholder-content{display:grid;place-items:center;gap:10px;text-align:center}.main-image .placeholder-brand-mark{width:112px;height:112px}.main-image .placeholder-brand-mark img{width:88px;height:82px}.main-image .placeholder-label{font-size:23px}.main-image .image-view{color:#687a72;font-size:12px;font-weight:700}',
    'product-page main placeholder sizing',
  );
  html = replaceOnce(
    html,
    '.mini-no-image{align-content:center;gap:7px;color:var(--pine);text-align:center}.mini-no-image .botanical-mark{width:34px;height:29px}.mini-no-image .botanical-mark:before,.mini-no-image .botanical-mark:after{width:14px;height:19px;border-width:1.5px}.mini-no-image .botanical-mark>span{height:20px;border-left-width:1.5px}.mini-no-image small{max-width:76px;font-size:10px;line-height:1.15}',
    '.mini-photo-coming-soon{align-content:center;gap:5px;color:var(--pine);text-align:center}.mini-photo-coming-soon .placeholder-brand-mark{width:42px;height:42px;border-width:1px;box-shadow:0 7px 14px rgba(23,76,60,.12)}.mini-photo-coming-soon .placeholder-brand-mark img{width:33px;height:30px}.mini-photo-coming-soon small{max-width:76px;font-size:9px;line-height:1.15}',
    'product-page compact placeholder sizing',
  );
  html = html.split('— no image available').join('— image coming soon');
  html = html.split('<span class="thumb-state">No image</span>').join('<span class="thumb-state">Coming soon</span>');
  const oldMain = '<div class="image-placeholder-content"><span class="botanical-mark" aria-hidden="true"><span></span></span><strong class="image-state">Product image not available</strong><span class="image-view">Front image</span></div>';
  const newMain = `<div class="photo-placeholder image-placeholder-content">${mark}<span class="placeholder-kicker">Finishing touch</span><strong class="placeholder-label image-state">Product photo<br>coming soon</strong><span class="image-view">Front image</span></div>`;
  html = replaceCount(html, oldMain, newMain, 2, 'product-page main placeholders');
  const oldMini = '<div class="placeholder-art mini-no-image" role="img" aria-label="Product image — image coming soon"><span class="botanical-mark" aria-hidden="true"><span></span></span><small>Product image not available</small></div>';
  const newMini = `<div class="placeholder-art mini-photo-coming-soon" role="img" aria-label="Product photo coming soon">${mark}<small>Product photo coming soon</small></div>`;
  html = replaceCount(html, oldMini, newMini, 3, 'product-page related placeholders');
  html = html.split('v1.5').join('v1.6');
  fs.writeFileSync('product-page-templates-v1.6.html', html);
  console.log('product-page-templates-v1.6.html: created');
}

function createPurchasePath() {
  let html = fs.readFileSync('purchase-path-mockup-v1.5.html', 'utf8');
  html = replaceOnce(html, sadCss, happyCss, 'purchase-path base placeholder CSS');
  html = replaceOnce(
    html,
    '.line-item .botanical-placeholder{gap:5px;padding:8px}.line-item .botanical-mark{width:32px;height:28px}.line-item .botanical-mark:before,.line-item .botanical-mark:after{width:14px;height:19px;border-width:1.5px}.line-item .botanical-mark>span{height:20px;border-left-width:1.5px}.line-item .botanical-label{max-width:78px;font-size:9px;line-height:1.1}',
    '.line-item .photo-placeholder{min-width:0;gap:4px;padding:6px}.line-item .placeholder-brand-mark{width:42px;height:42px;border-width:1px;box-shadow:0 7px 14px rgba(23,76,60,.12)}.line-item .placeholder-brand-mark img{width:33px;height:30px}.line-item .placeholder-label{max-width:78px;font-family:var(--sans);font-size:9px;line-height:1.1}',
    'purchase-path compact placeholder sizing',
  );
  const oldMarkup = '<div class="placeholder-art" role="img" aria-label="Product image not available"><div class="botanical-placeholder"><span class="botanical-mark" aria-hidden="true"><span></span></span><span class="botanical-label">Product image not available</span></div></div>';
  const newMarkup = `<div class="placeholder-art" role="img" aria-label="Product photo coming soon"><div class="photo-placeholder">${mark}<span class="placeholder-label">Product photo coming soon</span></div></div>`;
  html = replaceCount(html, oldMarkup, newMarkup, 2, 'purchase-path cart placeholders');
  html = html.split('v1.5').join('v1.6');
  fs.writeFileSync('purchase-path-mockup-v1.6.html', html);
  console.log('purchase-path-mockup-v1.6.html: created');
}

function createStoreStates() {
  let html = fs.readFileSync('store-states-components-v1.4.html', 'utf8');
  html = replaceOnce(html, sadCss, happyCss, 'store-states base placeholder CSS');
  html = replaceOnce(
    html,
    '.image-icon{border-radius:12px;background:#f2f3ef;color:#75867e}.state-visual .botanical-placeholder{display:flex;justify-content:flex-start;gap:12px;padding:0}.state-visual .botanical-mark{width:48px;height:42px}.state-visual .botanical-mark:before,.state-visual .botanical-mark:after{width:20px;height:28px}.state-visual .botanical-mark>span{height:29px}.state-visual .botanical-label{max-width:160px;text-align:left;font-size:11px}',
    '.image-icon{border-radius:12px;background:#f2f3ef;color:#75867e}.state-visual .photo-placeholder{display:flex;justify-content:flex-start;gap:12px;min-width:0;padding:0}.state-visual .placeholder-brand-mark{width:56px;height:56px;border-width:1px;box-shadow:0 8px 16px rgba(23,76,60,.12)}.state-visual .placeholder-brand-mark img{width:43px;height:40px}.state-visual .placeholder-label{max-width:170px;text-align:left;font-family:var(--sans);font-size:12px}.site-rule{margin-top:13px;padding:12px 14px;border-left:3px solid #d6a33a;border-radius:0 10px 10px 0;background:#fff7e6;color:#40554b;font-size:13px;line-height:1.45}',
    'store-states approved placeholder sizing',
  );
  const oldMarkup = '<div class="state-visual" role="img" aria-label="Product image not available"><div class="botanical-placeholder"><span class="botanical-mark" aria-hidden="true"><span></span></span><span class="botanical-label">Product image not available</span></div></div><h2>Missing Product Image</h2><p>Show the same neutral botanical placeholder anywhere approved product photography is unavailable.</p>';
  const newMarkup = `<div class="state-visual" role="img" aria-label="Product photo coming soon"><div class="photo-placeholder">${mark}<span class="placeholder-label">Product photo coming soon</span></div></div><h2>Missing Product Image</h2><p>Use this exact Rebekah sprout treatment anywhere required product photography is temporarily unavailable.</p><div class="site-rule"><strong>Approved site rule:</strong> replace this automatically when approved photography arrives. Do not use it for loading, broken-image errors, decorative imagery, or content that was never intended to have a photo.</div>`;
  html = replaceOnce(html, oldMarkup, newMarkup, 'store-states missing-image site rule');
  html = html.split('v1.4').join('v1.5');
  fs.writeFileSync('store-states-components-v1.5.html', html);
  console.log('store-states-components-v1.5.html: created');
}

function replaceOnce(value, search, replacement, label) {
  const count = value.split(search).length - 1;
  if (count !== 1) throw new Error(`${label}: expected 1 match, found ${count}`);
  return value.replace(search, replacement);
}

function replaceCount(value, search, replacement, expected, label) {
  const count = value.split(search).length - 1;
  if (count !== expected) throw new Error(`${label}: expected ${expected} matches, found ${count}`);
  return value.split(search).join(replacement);
}
