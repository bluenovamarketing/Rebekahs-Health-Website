import fs from 'node:fs';

const specs = [
  {
    source: 'shop-catalog-template-v1.6.html',
    target: 'shop-catalog-template-v1.7.html',
    fromVersion: 'v1.6',
    toVersion: 'v1.7',
    transform(html) {
      html = replaceOnce(html, OLD_PLACEHOLDER_CSS, NEW_PLACEHOLDER_CSS, 'catalog placeholder CSS');
      const oldMarkup = '<div class="placeholder-art" aria-label="Product image placeholder"><div class="bottle" aria-hidden="true"></div></div>';
      const newMarkup = productPlaceholder('Product image not available');
      html = replaceCount(html, oldMarkup, newMarkup, 6, 'catalog product placeholders');
      return html;
    },
  },
  {
    source: 'product-page-templates-v1.4.html',
    target: 'product-page-templates-v1.5.html',
    fromVersion: 'v1.4',
    toVersion: 'v1.5',
    transform(html) {
      html = replaceOnce(html, OLD_PLACEHOLDER_CSS, NEW_PLACEHOLDER_CSS, 'product-page placeholder CSS');
      html = replaceOnce(
        html,
        '.image-brand-mark{width:96px;height:96px;display:grid;place-items:center;border:2px solid rgba(23,76,60,.2);border-radius:50%;background:rgba(255,255,255,.86);color:var(--leaf);box-shadow:0 12px 26px rgba(23,76,60,.1);font-family:Georgia,serif;font-size:48px}',
        '.image-placeholder-content>.botanical-mark{width:82px;height:70px}.image-placeholder-content>.botanical-mark:before,.image-placeholder-content>.botanical-mark:after{width:34px;height:46px}.image-placeholder-content>.botanical-mark>span{height:48px}',
        'product-page main botanical sizing',
      );
      html = replaceOnce(
        html,
        '.mini-no-image{align-content:center;gap:4px;color:var(--leaf);font-family:Georgia,serif;font-size:25px}',
        '.mini-no-image{align-content:center;gap:7px;color:var(--pine);text-align:center}.mini-no-image .botanical-mark{width:34px;height:29px}.mini-no-image .botanical-mark:before,.mini-no-image .botanical-mark:after{width:14px;height:19px;border-width:1.5px}.mini-no-image .botanical-mark>span{height:20px;border-left-width:1.5px}.mini-no-image small{max-width:76px;font-size:10px;line-height:1.15}',
        'product-page compact botanical sizing',
      );
      const oldMain = '<span class="image-brand-mark" aria-hidden="true">R</span><strong class="image-state">No image available</strong>';
      const newMain = `${botanicalMark()}<strong class="image-state">Product image not available</strong>`;
      html = replaceCount(html, oldMain, newMain, 2, 'product-page main placeholders');
      const oldMini = '<span aria-hidden="true">R</span><small>No image</small>';
      const newMini = `${botanicalMark()}<small>Product image not available</small>`;
      html = replaceCount(html, oldMini, newMini, 3, 'product-page related placeholders');
      return html;
    },
  },
  {
    source: 'purchase-path-mockup-v1.4.html',
    target: 'purchase-path-mockup-v1.5.html',
    fromVersion: 'v1.4',
    toVersion: 'v1.5',
    transform(html) {
      html = replaceOnce(html, OLD_PLACEHOLDER_CSS, NEW_PLACEHOLDER_CSS, 'purchase-path placeholder CSS');
      html = replaceOnce(
        html,
        '.line-item .bottle{width:40px;height:62px;border-radius:6px}.line-item .bottle:before{left:11px;right:11px;top:-9px;height:11px}.line-item .bottle:after{inset:17px 4px auto;height:23px;font-size:13px}',
        '.line-item .botanical-placeholder{gap:5px;padding:8px}.line-item .botanical-mark{width:32px;height:28px}.line-item .botanical-mark:before,.line-item .botanical-mark:after{width:14px;height:19px;border-width:1.5px}.line-item .botanical-mark>span{height:20px;border-left-width:1.5px}.line-item .botanical-label{max-width:78px;font-size:9px;line-height:1.1}',
        'purchase-path compact botanical sizing',
      );
      const oldMarkup = '<div class="placeholder-art"><div class="bottle"></div></div>';
      const newMarkup = productPlaceholder('Product image not available');
      html = replaceCount(html, oldMarkup, newMarkup, 2, 'purchase-path product placeholders');
      return html;
    },
  },
  {
    source: 'store-states-components-v1.3.html',
    target: 'store-states-components-v1.4.html',
    fromVersion: 'v1.3',
    toVersion: 'v1.4',
    transform(html) {
      html = replaceOnce(html, OLD_PLACEHOLDER_CSS, NEW_PLACEHOLDER_CSS, 'store-states placeholder CSS');
      html = replaceOnce(
        html,
        '.image-icon{border-radius:12px;background:#f2f3ef;color:#75867e}',
        '.image-icon{border-radius:12px;background:#f2f3ef;color:#75867e}.state-visual .botanical-placeholder{display:flex;justify-content:flex-start;gap:12px;padding:0}.state-visual .botanical-mark{width:48px;height:42px}.state-visual .botanical-mark:before,.state-visual .botanical-mark:after{width:20px;height:28px}.state-visual .botanical-mark>span{height:29px}.state-visual .botanical-label{max-width:160px;text-align:left;font-size:11px}',
        'store-states botanical sizing',
      );
      const oldMarkup = '<div class="state-visual"><div class="image-icon" aria-hidden="true">▧</div></div><h2>Missing Product Image</h2><p>Use a branded neutral placeholder without implying a product photo exists.</p>';
      const newMarkup = `<div class="state-visual" role="img" aria-label="Product image not available"><div class="botanical-placeholder">${botanicalMark()}<span class="botanical-label">Product image not available</span></div></div><h2>Missing Product Image</h2><p>Show the same neutral botanical placeholder anywhere approved product photography is unavailable.</p>`;
      html = replaceOnce(html, oldMarkup, newMarkup, 'store-states missing-image example');
      return html;
    },
  },
];

const OLD_PLACEHOLDER_CSS = '.placeholder-art{min-height:220px;display:grid;place-items:center;overflow:hidden;border-radius:16px;background:radial-gradient(circle at 50% 28%,#fff 0 16%,transparent 17%),linear-gradient(145deg,#eff5e9,#f8eed7)}.bottle{position:relative;width:80px;height:128px;border:2px solid rgba(23,76,60,.24);border-radius:12px 12px 18px 18px;background:#fff;box-shadow:0 14px 24px rgba(23,76,60,.12)}.bottle:before{content:"";position:absolute;left:22px;right:22px;top:-18px;height:20px;border-radius:5px 5px 2px 2px;background:var(--pine)}.bottle:after{content:"R";position:absolute;inset:32px 8px auto;height:48px;display:grid;place-items:center;border-top:2px solid #d6a33a;border-bottom:2px solid #d6a33a;color:var(--leaf);font-family:Georgia,serif;font-size:27px}';

const NEW_PLACEHOLDER_CSS = '.placeholder-art{min-height:220px;display:grid;place-items:center;overflow:hidden;border-radius:16px;background:linear-gradient(145deg,#f2f6ed,#fbf2df);color:var(--pine)}.botanical-placeholder{display:grid;place-items:center;gap:11px;padding:18px;text-align:center}.botanical-mark{position:relative;width:68px;height:58px;display:block}.botanical-mark:before,.botanical-mark:after{content:"";position:absolute;top:2px;width:29px;height:40px;border:2px solid #52785f;background:rgba(255,255,255,.62)}.botanical-mark:before{left:7px;border-radius:100% 0 100% 0;transform:rotate(-16deg)}.botanical-mark:after{right:7px;border-radius:0 100% 0 100%;transform:rotate(16deg)}.botanical-mark>span{position:absolute;left:50%;bottom:2px;height:40px;border-left:2px solid #52785f;transform:translateX(-50%)}.botanical-label{max-width:150px;color:#425f50;font-size:12px;font-weight:700;letter-spacing:.02em;line-height:1.25}';

function botanicalMark() {
  return '<span class="botanical-mark" aria-hidden="true"><span></span></span>';
}

function productPlaceholder(label) {
  return `<div class="placeholder-art" role="img" aria-label="${label}"><div class="botanical-placeholder">${botanicalMark()}<span class="botanical-label">${label}</span></div></div>`;
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

for (const spec of specs) {
  let html = fs.readFileSync(spec.source, 'utf8');
  html = spec.transform(html);
  html = html.split(spec.fromVersion).join(spec.toVersion);
  fs.writeFileSync(spec.target, html);
  console.log(`${spec.target}: created`);
}
