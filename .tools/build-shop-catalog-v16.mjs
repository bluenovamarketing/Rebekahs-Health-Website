import fs from "node:fs";
import path from "node:path";

const root = process.cwd();
const sourcePath = path.join(root, "shop-catalog-template-v1.5.html");
const outputPath = path.join(root, "shop-catalog-template-v1.6.html");

if (fs.existsSync(outputPath)) {
  throw new Error(`Refusing to overwrite reviewed revision: ${outputPath}`);
}

let html = fs.readFileSync(sourcePath, "utf8");

const replacements = [
  ["Shop Homepage + Product Catalog v1.5", "Shop Homepage + Product Catalog v1.6"],
  ["Shop Homepage + Product Catalog · v1.5", "Shop Homepage + Product Catalog · v1.6"],
  [
    '<div><strong id="result-count">6 products</strong></div>',
    '<div><strong>Find support for your everyday wellness.</strong><span class="visually-hidden" id="result-count" aria-live="polite">6 products available</span></div>',
  ],
  [
    '<span class="muted">Showing 6 products</span>',
    '<span class="muted">Explore more wellness favorites.</span>',
  ],
  [
    "resultCount.textContent=visible+' product'+(visible===1?'':'s')",
    "resultCount.textContent=visible+' product'+(visible===1?'':'s')+' available'",
  ],
];

for (const [before, after] of replacements) {
  if (!html.includes(before)) throw new Error(`Expected source text not found: ${before}`);
  html = html.replaceAll(before, after);
}

const assertions = [
  [html.includes("Shop Homepage + Product Catalog v1.6"), "v1.6 title"],
  [html.includes("Find support for your everyday wellness."), "audience-focused toolbar copy"],
  [html.includes("Explore more wellness favorites."), "audience-focused continuation copy"],
  [(html.match(/class="shop-path"/g) || []).length === 4, "four wellness-goal paths"],
  [(html.match(/class="product-card"/g) || []).length === 6, "six catalog cards"],
  [!/<header\b/i.test(html) && !/<footer\b/i.test(html) && !/<nav\b/i.test(html), "page-body-only architecture"],
];

for (const [ok, label] of assertions) {
  if (!ok) throw new Error(`Build assertion failed: ${label}`);
}

fs.writeFileSync(outputPath, html, "utf8");
console.log(`Created ${path.basename(outputPath)} from preserved v1.5 source.`);
