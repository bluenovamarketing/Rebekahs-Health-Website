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

const sourcePath = 'main-homepage-ecommerce-integration-v1.4.html';
const targetPath = 'main-homepage-ecommerce-integration-v1.5.html';
let html = fs.readFileSync(sourcePath, 'utf8');

html = replaceOnce(
  html,
  '<!-- Main Homepage Ecommerce Integration v1.4: actual live homepage body preserved; Phase Two commerce entry points added; no global chrome. -->',
  '<!-- Main Homepage Ecommerce Integration v1.5: review-only highlights identify the two Phase Two additions; actual homepage body remains preserved; no global chrome. -->',
  'file comment',
);
html = replaceOnce(
  html,
  '<title>Main Homepage Ecommerce Integration v1.4 | Rebekah\'s Health & Nutrition</title>',
  '<title>Main Homepage Ecommerce Integration v1.5 | Rebekah\'s Health & Nutrition</title>',
  'page title',
);
html = replaceOnce(
  html,
  'https://wordpress-1651482-6565113.cloudwaysapps.com/wp-content/uploads/2026/08/rebekahs-homepage-hero-loop-full-v3-hd.mp4',
  'https://rebekahspureliving.com/wp-content/uploads/2026/08/rebekahs-homepage-hero-loop-full-v3-hd.mp4',
  'hero video preview URL',
);
html = replaceOnce(
  html,
  '<style id="phase-two-homepage-store-styles">',
  `<style id="phase-two-homepage-store-styles">
    .review-change-note{position:relative;z-index:10;padding:18px max(24px,calc((100vw - 1180px)/2));border-top:1px solid #d6a33a;border-bottom:1px solid #d6a33a;background:#fff8e6;color:#3b4e45;font-family:"DM Sans",sans-serif;font-size:14px;line-height:1.55}.review-change-note strong{color:#174c3c}.review-change-note span{display:inline-flex;margin-right:10px;padding:5px 9px;border-radius:999px;background:#d6a33a;color:#173e32;font-size:10px;font-weight:800;letter-spacing:.08em;text-transform:uppercase}.phase-review-button-wrap{position:relative;display:inline-flex;align-items:center;flex-wrap:wrap;gap:9px}.phase-review-button{box-shadow:0 0 0 3px #d6a33a,0 8px 20px rgba(0,0,0,.18)!important}.review-change-badge,.review-section-flag{display:inline-flex;align-items:center;min-height:28px;padding:0 10px;border-radius:999px;background:#d6a33a;color:#173e32;font:800 10px/1 "DM Sans",sans-serif;letter-spacing:.07em;text-transform:uppercase;box-shadow:0 8px 20px rgba(36,56,47,.18)}.review-added-section{outline:4px solid #d6a33a;outline-offset:-4px}.review-section-flag{position:absolute;z-index:3;left:20px;top:18px}.review-added-section .phase-version{top:58px}@media(max-width:760px){.review-change-note{padding:16px 20px}.phase-review-button-wrap{width:100%;align-items:flex-start;flex-direction:column}.review-section-flag{left:10px;top:10px;max-width:210px;text-align:center}.review-added-section .phase-version{top:74px}}
`,
  'review highlight styles',
);
html = replaceOnce(
  html,
  '<main id="main">',
  '<main id="main">\n    <aside class="review-change-note" aria-label="Review note"><span>Review only</span><strong>The existing homepage remains unchanged.</strong> Gold highlights identify the only two Phase Two additions: the Shop Online hero button and the ecommerce shopping section. These review labels and outlines will not appear on the live website.</aside>',
  'review summary note',
);
html = replaceOnce(
  html,
  '<a class="pill glass" href="#online-store">Shop Online</a>',
  '<span class="phase-review-button-wrap"><a class="pill glass phase-review-button" href="#online-store">Shop Online</a><span class="review-change-badge">Addition 1 · New button</span></span>',
  'Shop Online highlight',
);
html = replaceOnce(
  html,
  '<section class="phase-two-shop section" id="online-store" data-mock-section="3b" aria-labelledby="online-store-title">',
  '<section class="phase-two-shop section review-added-section" id="online-store" data-mock-section="3b" aria-labelledby="online-store-title">\n      <div class="review-section-flag">Addition 2 · New ecommerce section</div>',
  'ecommerce section highlight',
);
html = replaceOnce(
  html,
  'Phase Two · Main Homepage Ecommerce Integration v1.4 · Page Body Only',
  'Phase Two · Main Homepage Ecommerce Integration v1.5 · Page Body Only',
  'visible version label',
);

const sectionIds = [...html.matchAll(/data-mock-section="([^"]+)"/g)].map(match => match[1]);
assert(sectionIds.join('|') === '2|3|3b|4|5|6|7|8|9|10|10b|11|12|13', 'Homepage component sequence changed');
assert((html.match(/class="phase-product"/g) || []).length === 4, 'Featured product count changed');
assert((html.match(/Addition 1 · New button/g) || []).length === 1, 'Button addition marker missing');
assert((html.match(/Addition 2 · New ecommerce section/g) || []).length === 1, 'Section addition marker missing');
assert(!/<header\b|<footer\b|<nav\b/i.test(html), 'Prohibited global chrome found');
fs.writeFileSync(targetPath, html);

console.log(JSON.stringify({
  file: targetPath,
  preservedSectionSequence: sectionIds,
  reviewOnlyHighlights: 2,
  globalChromeElements: 0,
  heroVideoSource: 'live-domain media URL',
}, null, 2));
