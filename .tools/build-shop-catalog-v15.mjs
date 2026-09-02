import fs from "node:fs";
import path from "node:path";

const root = process.cwd();
const sourcePath = path.join(root, "shop-catalog-template-v1.4.html");
const targetPath = path.join(root, "shop-catalog-template-v1.5.html");
let html = fs.readFileSync(sourcePath, "utf8");

const replaceOnce = (from, to, label) => {
  const count = html.split(from).length - 1;
  if (count !== 1) throw new Error(`${label}: expected one match, found ${count}`);
  html = html.replace(from, to);
};

replaceOnce(
  "<title>Shop Homepage + Product Catalog v1.4 | Rebekah's Health & Nutrition</title>",
  "<title>Shop Homepage + Product Catalog v1.5 | Rebekah's Health & Nutrition</title>",
  "document title",
);

replaceOnce(
  "Shop Homepage + Product Catalog · v1.4 · Page Body Only",
  "Shop Homepage + Product Catalog · v1.5 · Page Body Only",
  "visible version",
);

replaceOnce(
  '<p class="page-kicker">System 03 · Store homepage + reusable catalog</p>',
  "",
  "internal system label",
);

replaceOnce(
  "Browse the Clarkston-fulfilled pilot collection by wellness goal, search the catalog, or ask our team for help choosing where to begin.",
  "Browse wellness favorites by goal, search the catalog, or ask our team for help choosing where to begin.",
  "catalog introduction",
);

replaceOnce(
  '<section class="shop-trust-row section-card" aria-label="Store fulfillment and help"><div class="shop-trust-item"><strong>Fulfilled in Clarkston</strong><span>Orders are packed by the Rebekah\'s team.</span></div><div class="shop-trust-item"><strong>Two-business-day handling</strong><span>Confirmed pilot handling expectation.</span></div><div class="shop-trust-item"><strong>Continental U.S. shipping</strong><span>Live shipping rates appear at checkout.</span></div><div class="shop-trust-item"><strong>Need product help?</strong><span>Contact the Clarkston store before ordering.</span></div></section>',
  '<section class="shop-trust-row section-card" aria-label="Shopping benefits and help"><div class="shop-trust-item"><strong>Carefully selected</strong><span>A broad mix of supplements and wellness essentials.</span></div><div class="shop-trust-item"><strong>Packed with care</strong><span>Every order is prepared by Rebekah\'s team.</span></div><div class="shop-trust-item"><strong>Easy online ordering</strong><span>Review your selections before checkout.</span></div><div class="shop-trust-item"><strong>Need product help?</strong><span>Call (248) 843-2011 for friendly guidance.</span></div></section>',
  "shopping benefits row",
);

replaceOnce(
  '<div class="notice"><span aria-hidden="true">ⓘ</span><div><strong>Mockup content boundary:</strong> product names and prices come from the Clarkston pilot export. Images, descriptions, inventory, weight, filters, and taxonomy remain provisional.</div></div>',
  "",
  "internal content notice",
);

replaceOnce(
  '<span class="muted"> · representative pilot items</span>',
  "",
  "toolbar pilot label",
);

replaceOnce(
  '<span class="mock-badge gold">Pilot product</span>',
  "",
  "product pilot badge",
);

replaceOnce(
  "Showing 6 representative products",
  "Showing 6 products",
  "load-more product count",
);

const forbiddenCustomerCopy = [
  "System 03",
  "pilot",
  "Clarkston",
  "Mockup content boundary",
  "representative pilot items",
  "representative products",
];

for (const term of forbiddenCustomerCopy) {
  if (html.toLowerCase().includes(term.toLowerCase())) {
    throw new Error(`Forbidden customer-facing copy remains: ${term}`);
  }
}

const assertions = [
  ["v1.5 title", html.includes("Product Catalog v1.5")],
  ["four wellness paths", (html.match(/class="shop-path"/g) || []).length === 4],
  ["four shopping benefits", (html.match(/class="shop-trust-item"/g) || []).length === 4],
  ["six product cards", (html.match(/class="product-card"/g) || []).length === 6],
  ["image asset retained", html.includes("wellness-goal-card-strip-v1.1.png")],
  ["catalog search retained", html.includes('id="catalog-search"')],
  ["sort retained", html.includes('id="sort-products"')],
  ["desktop filters retained", html.includes('class="filters section-card"')],
  ["mobile filter drawer retained", html.includes('id="filter-drawer"')],
  ["no-results state retained", html.includes('id="no-results"')],
  ["no global header", !/<header\b/i.test(html)],
  ["no global footer", !/<footer\b/i.test(html)],
  ["no nav", !/<nav\b/i.test(html)],
];

for (const [label, ok] of assertions) {
  if (!ok) throw new Error(`Assertion failed: ${label}`);
}

fs.writeFileSync(targetPath, html, "utf8");

console.log(`Created ${path.basename(targetPath)}`);
for (const [label] of assertions) console.log(`PASS ${label}`);
