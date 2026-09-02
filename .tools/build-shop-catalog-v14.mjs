import fs from 'node:fs';
import path from 'node:path';

const root = process.cwd();
const sourcePath = path.join(root, 'shop-catalog-template-v1.3.html');
const targetPath = path.join(root, 'shop-catalog-template-v1.4.html');
let html = fs.readFileSync(sourcePath, 'utf8');

const replaceOnce = (from, to, label) => {
  const count = html.split(from).length - 1;
  if (count !== 1) throw new Error(`${label}: expected one match, found ${count}`);
  html = html.replace(from, to);
};

replaceOnce(
  '<title>Shop Homepage + Product Catalog v1.3 | Rebekah\'s Health & Nutrition</title>',
  '<title>Shop Homepage + Product Catalog v1.4 | Rebekah\'s Health & Nutrition</title>',
  'document title'
);

replaceOnce(
  '.shop-home-paths{margin:30px 0 18px;display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px}.shop-path{min-height:142px;padding:22px;display:flex;flex-direction:column;justify-content:flex-end;border:1px solid rgba(23,76,60,.13);border-radius:18px;background:linear-gradient(145deg,#fff,#eef4ec);transition:transform .2s ease,box-shadow .2s ease}.shop-path:hover,.shop-path:focus-visible{transform:translateY(-3px);box-shadow:0 16px 32px rgba(23,76,60,.1)}.shop-path small{margin-bottom:8px;color:var(--leaf);font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase}.shop-path strong{color:var(--pine);font-family:Georgia,serif;font-size:21px;font-weight:500;line-height:1.2}.shop-trust-row{margin:0 0 24px;padding:18px 20px;display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px}.shop-trust-item{display:grid;gap:5px}.shop-trust-item strong{color:var(--pine);font-size:13px}.shop-trust-item span{color:#687a72;font-size:12px;line-height:1.45}@media(max-width:980px){.shop-home-paths,.shop-trust-row{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:560px){.shop-home-paths,.shop-trust-row{grid-template-columns:1fr}.shop-path{min-height:118px}.shop-trust-row{padding:18px}}',
  '.shop-home-paths{margin:30px 0 18px;display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px}.shop-path{--goal-x:0%;position:relative;isolation:isolate;min-height:220px;padding:22px;display:flex;overflow:hidden;flex-direction:column;justify-content:flex-end;border:1px solid rgba(255,255,255,.38);border-radius:18px;background:#214c3f;box-shadow:0 12px 26px rgba(23,76,60,.12);transition:transform .2s ease,box-shadow .2s ease}.shop-path::before{content:"";position:absolute;inset:0;z-index:-2;background-image:url("assets/phase-two-shop/wellness-goal-card-strip-v1.1.png");background-repeat:no-repeat;background-size:400% auto;background-position:var(--goal-x) 48%;transition:transform .35s ease}.shop-path::after{content:"";position:absolute;inset:0;z-index:-1;background:linear-gradient(180deg,rgba(10,35,27,.05) 18%,rgba(10,35,27,.28) 54%,rgba(10,35,27,.91) 100%)}.shop-path:nth-child(1){--goal-x:0%}.shop-path:nth-child(2){--goal-x:33.333%}.shop-path:nth-child(3){--goal-x:66.667%}.shop-path:nth-child(4){--goal-x:100%}.shop-path:hover,.shop-path:focus-visible{transform:translateY(-4px);box-shadow:0 18px 38px rgba(23,76,60,.2)}.shop-path:hover::before,.shop-path:focus-visible::before{transform:scale(1.035)}.shop-path small{margin-bottom:8px;color:#f3d985;font-size:11px;font-weight:700;letter-spacing:.1em;text-shadow:0 1px 4px rgba(0,0,0,.45);text-transform:uppercase}.shop-path strong{color:#fff;font-family:Georgia,serif;font-size:23px;font-weight:500;line-height:1.15;text-shadow:0 2px 8px rgba(0,0,0,.5)}.shop-trust-row{margin:0 0 24px;padding:18px 20px;display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px}.shop-trust-item{display:grid;gap:5px}.shop-trust-item strong{color:var(--pine);font-size:13px}.shop-trust-item span{color:#687a72;font-size:12px;line-height:1.45}@media(max-width:980px){.shop-home-paths,.shop-trust-row{grid-template-columns:repeat(2,minmax(0,1fr))}.shop-path{min-height:230px}}@media(max-width:560px){.shop-home-paths,.shop-trust-row{grid-template-columns:1fr}.shop-path{min-height:210px}.shop-trust-row{padding:18px}}',
  'wellness-goal card styles'
);

replaceOnce('\n+    .catalog-head{', '\n    .catalog-head{', 'stray CSS prefix');
replaceOnce('Shop Homepage + Product Catalog · v1.3 · Page Body Only', 'Shop Homepage + Product Catalog · v1.4 · Page Body Only', 'visible version');

fs.writeFileSync(targetPath, html, 'utf8');

const assertions = [
  ['v1.4 title', html.includes('Product Catalog v1.4')],
  ['four wellness paths', (html.match(/class="shop-path"/g) || []).length === 4],
  ['six product cards', (html.match(/class="product-card"/g) || []).length === 6],
  ['image asset reference', html.includes('wellness-goal-card-strip-v1.1.png')],
  ['catalog search retained', html.includes('id="catalog-search"')],
  ['mobile filter drawer retained', html.includes('id="filter-drawer"')],
  ['no global header', !/<header\b/i.test(html)],
  ['no global footer', !/<footer\b/i.test(html)],
  ['no nav', !/<nav\b/i.test(html)]
];

for (const [label, ok] of assertions) {
  if (!ok) throw new Error(`Assertion failed: ${label}`);
}

console.log(`Created ${path.basename(targetPath)}`);
for (const [label] of assertions) console.log(`PASS ${label}`);
