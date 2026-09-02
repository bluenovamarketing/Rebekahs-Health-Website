import fs from "node:fs/promises";
import path from "node:path";

const projectDir = path.resolve(import.meta.dirname, "..");

const revisions = [
  ["shop-catalog-template-v1.1.html", "shop-catalog-template-v1.2.html", "Shop + Catalog Template", "Shop + Catalog Template"],
  ["product-page-templates-v1.1.html", "product-page-templates-v1.2.html", "Product Page Templates", "Product Page Templates"],
  ["purchase-path-mockup-v1.1.html", "purchase-path-mockup-v1.2.html", "Purchase Path", "Purchase Path"],
  ["customer-account-system-v1.1.html", "customer-account-system-v1.2.html", "Customer Account System", "Customer Account System"],
  ["store-states-components-v1.1.html", "store-states-components-v1.2.html", "Store States + Components", "Store States + Components"],
];

for (const [inputName, outputName, title, chipTitle] of revisions) {
  const source = await fs.readFile(path.join(projectDir, inputName), "utf8");
  const styleMatch = source.match(/<style>([\s\S]*?)<\/style>/);
  const mainMatch = source.match(/<main class="review-canvas mockup-page"[\s\S]*?<\/main>/);
  const scriptMatch = source.match(/<script>([\s\S]*?)<\/script>/);

  if (!styleMatch || !mainMatch || !scriptMatch) {
    throw new Error(`Could not extract the existing page body from ${inputName}.`);
  }

  const pageScriptStart = scriptMatch[1].indexOf("document.querySelectorAll('a[href=\"#\"]')");
  if (pageScriptStart < 0) {
    throw new Error(`Could not isolate the page interaction script in ${inputName}.`);
  }

  const pageStyleStart = styleMatch[1].indexOf(".mockup-page{");
  if (pageStyleStart < 0) {
    throw new Error(`Could not isolate the page styles in ${inputName}.`);
  }
  const pageStyles = styleMatch[1].slice(pageStyleStart).trim();

  const main = mainMatch[0].replace(
    /<div class="version-chip">[\s\S]*?<\/div>/,
    `<div class="version-chip">${chipTitle} · v1.2 · Page Body Only · Internal Review · Local Only</div>`,
  );
  const pageScript = scriptMatch[1].slice(pageScriptStart).trim();

  const output = `<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="description" content="Page-body-only local Phase Two ecommerce review mockup for Rebekah's Health & Nutrition.">
  <meta name="robots" content="noindex,nofollow">
  <title>${title} Body v1.2 | Rebekah's Health & Nutrition</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root{--pine:#174c3c;--leaf:#3f7d50;--cream:#f7f3e8;--berry:#8e4b61;--ink:#26342e;--sans:"DM Sans",Arial,sans-serif}
    *{box-sizing:border-box}html{min-width:320px}body{min-height:100vh;margin:0;overflow-x:clip;color:var(--ink);background:var(--cream);font-family:var(--sans)}a{color:inherit;text-decoration:none}img{display:block;width:100%}button,summary{font:inherit}[hidden]{display:none!important}.visually-hidden{position:absolute!important;width:1px!important;height:1px!important;padding:0!important;margin:-1px!important;overflow:hidden!important;clip:rect(0,0,0,0)!important;white-space:nowrap!important;border:0!important}
    :where(a,button,input,select,textarea,summary):focus-visible{outline:3px solid #d6a33a;outline-offset:3px}
    .review-canvas{position:relative;min-height:100vh;background:radial-gradient(circle at 12% 20%,rgba(214,163,58,.09),transparent 24%),radial-gradient(circle at 88% 80%,rgba(169,195,160,.14),transparent 28%),var(--cream)}
    .version-chip{position:absolute;top:18px;right:18px;z-index:1;padding:9px 12px;border:1px solid rgba(23,76,60,.14);border-radius:999px;background:rgba(255,255,255,.92);color:#63766d;box-shadow:0 8px 24px rgba(23,76,60,.1);font-size:11px;font-weight:700;letter-spacing:.07em;text-transform:uppercase;backdrop-filter:blur(10px)}
    ${pageStyles}
  </style>
</head>
<body>
  ${main}
  <script>
    ${pageScript}
  </script>
</body>
</html>
`;

  if (/<header\b|<footer\b|class="header-shell"|class="site-header"|id="site-footer"/.test(output)) {
    throw new Error(`${outputName} still contains global header or footer markup.`);
  }

  await fs.writeFile(path.join(projectDir, outputName), output, "utf8");
}

console.log("Built systems 03-07 v1.2 as page-body-only review mockups.");
