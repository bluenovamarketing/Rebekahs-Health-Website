import fs from "node:fs/promises";

const sourcePath = "product-page-templates-v1.3.html";
const targetPath = "product-page-templates-v1.4.html";

let html = await fs.readFile(sourcePath, "utf8");

const replaceOnce = (from, to, label) => {
  if (!html.includes(from)) throw new Error(`Missing expected ${label}`);
  html = html.replace(from, to);
};

replaceOnce(
  "<title>Product Page Templates Body v1.3 | Rebekah's Health & Nutrition</title>",
  "<title>Product Page Templates Body v1.4 | Rebekah's Health & Nutrition</title>",
  "document title",
);

replaceOnce(
  ".thumb{width:76px;height:76px;border:1px solid rgba(23,76,60,.15);border-radius:11px;background:#f0f5ec;cursor:pointer}",
  ".thumb{width:76px;height:76px;padding:8px 5px;display:grid;place-content:center;gap:3px;border:1px solid rgba(23,76,60,.15);border-radius:11px;background:#f0f5ec;color:#5f7269;text-align:center;cursor:pointer}.thumb-title{color:var(--pine);font-size:11px;font-weight:700}.thumb-state{font-size:9px;font-weight:600;line-height:1.15}",
  "thumbnail styles",
);

replaceOnce(
  ".main-image{min-height:500px}",
  ".main-image{min-height:500px}.image-placeholder-content{display:grid;place-items:center;gap:10px;text-align:center}.image-brand-mark{width:96px;height:96px;display:grid;place-items:center;border:2px solid rgba(23,76,60,.2);border-radius:50%;background:rgba(255,255,255,.86);color:var(--leaf);box-shadow:0 12px 26px rgba(23,76,60,.1);font-family:Georgia,serif;font-size:48px}.image-state{color:var(--pine);font-size:18px}.image-view{color:#66796f;font-size:13px}.image-caption{position:absolute;left:16px;bottom:16px;padding:7px 10px;border-radius:999px;background:rgba(255,255,255,.9);color:#63766d;font-size:11px;font-weight:700}",
  "main image placeholder styles",
);

replaceOnce(
  ".thumb{padding:6px;color:#5f7269;font-size:10px;font-weight:700;line-height:1.2}.main-image{position:relative}.image-caption{position:absolute;left:16px;bottom:16px;padding:7px 10px;border-radius:999px;background:rgba(255,255,255,.9);color:#63766d;font-size:11px;font-weight:700}",
  ".main-image{position:relative}",
  "obsolete thumbnail and caption styles",
);

replaceOnce(
  "Product Page Templates · v1.3 · Page Body Only · Internal Review · Local Only",
  "Product Page Templates · v1.4 · Page Body Only · Internal Review · Local Only",
  "version chip",
);

replaceOnce(
  "One shared product-page system shown with a simple product and a selectable variation product.",
  "One shared product-page system for Rebekah's products and other store brands, shown with simple and selectable variation products.",
  "page introduction",
);

replaceOnce(
  "Approve the shared layout, gallery behavior, simple/variation controls, quantity, cautions, fulfillment note, and related-product placement.",
  "Approve the shared layout, three-view image gallery, clear no-image state, simple/variation controls, quantity, cautions, help note, and related-product placement.",
  "review scope",
);

const oldSimpleGallery = '<div class="gallery"><div class="thumbs" aria-label="Product images"><button class="thumb" type="button" aria-current="true" aria-label="Front image" data-caption="Front label preview">Front</button><button class="thumb" type="button" aria-current="false" aria-label="Supplement facts image" data-caption="Supplement facts preview">Facts</button><button class="thumb" type="button" aria-current="false" aria-label="Ingredient image" data-caption="Ingredients preview">Ingredients</button></div><div class="placeholder-art main-image" aria-label="Product image placeholder"><div class="bottle" aria-hidden="true"></div><span class="image-caption">Front label preview</span></div></div>';
const newSimpleGallery = '<div class="gallery"><div class="thumbs" aria-label="Product image views"><button class="thumb" type="button" aria-current="true" aria-label="Front image — no image available" data-view="Front image"><span class="thumb-title">Front</span><span class="thumb-state">No image</span></button><button class="thumb" type="button" aria-current="false" aria-label="Supplement facts image — no image available" data-view="Supplement facts image"><span class="thumb-title">Facts</span><span class="thumb-state">No image</span></button><button class="thumb" type="button" aria-current="false" aria-label="Ingredients image — no image available" data-view="Ingredients image"><span class="thumb-title">Ingredients</span><span class="thumb-state">No image</span></button></div><div class="placeholder-art main-image" role="img" aria-label="Front image — no image available"><div class="image-placeholder-content"><span class="image-brand-mark" aria-hidden="true">R</span><strong class="image-state">No image available</strong><span class="image-view">Front image</span></div></div></div>';
replaceOnce(oldSimpleGallery, newSimpleGallery, "simple product gallery");

replaceOnce(
  '<div class="meta-line"><span>Energy &amp; Vitality</span><span>•</span><span>Clarkston pilot export</span></div>',
  '<div class="meta-line"><span>Energy &amp; Vitality</span><span>•</span><span>Capsules</span></div>',
  "simple product metadata",
);

replaceOnce(
  '<div class="draft-copy"><strong>Draft content placeholder.</strong> Client-approved product description, ingredients, directions, supplement facts, cautions, shipping weight, and final inventory language must be added before implementation.</div>',
  '<div class="draft-copy"><strong>Product information.</strong> Complete descriptions, ingredients, directions, supplement facts, cautions, and product specifications will populate from the connected catalog and approved content.</div>',
  "simple product copy",
);

replaceOnce(
  '<p class="status-note">Availability language is provisional. Exact stock counts are intentionally not shown.</p>',
  '<p class="status-note">Availability will be shown when live inventory is connected.</p>',
  "simple product availability",
);

replaceOnce(
  '<button class="btn add-demo" id="variation-add" type="button" disabled>Add to Cart</button>',
  '<button class="btn add-demo" type="button">Add to Cart</button>',
  "simple add-to-cart button",
);

replaceOnce(
  '<p class="meta-line">Online orders ship from Clarkston · Allow two business days for packing · Continental U.S. only</p>',
  '<p class="meta-line">Questions before ordering? Call (248) 843-2011 for friendly product guidance.</p>',
  "simple purchase help",
);

replaceOnce(
  '<p>Clarkston fulfillment notice, shipping limits, returns link, and product-help phone path.</p>',
  '<p>Returns information, order support, and friendly product guidance from Rebekah\'s team.</p>',
  "shipping and help card",
);

const oldVariationGallery = '<div class="gallery"><div class="thumbs"><button class="thumb" type="button" aria-current="true" aria-label="Front image" data-caption="Front label preview">Front</button><button class="thumb" type="button" aria-current="false" aria-label="Back image" data-caption="Back label preview">Back</button></div><div class="placeholder-art main-image"><div class="bottle" aria-hidden="true"></div><span class="image-caption">Front label preview</span></div></div>';
const newVariationGallery = '<div class="gallery"><div class="thumbs" aria-label="Product image views"><button class="thumb" type="button" aria-current="true" aria-label="Front image — no image available" data-view="Front image"><span class="thumb-title">Front</span><span class="thumb-state">No image</span></button><button class="thumb" type="button" aria-current="false" aria-label="Supplement facts image — no image available" data-view="Supplement facts image"><span class="thumb-title">Facts</span><span class="thumb-state">No image</span></button><button class="thumb" type="button" aria-current="false" aria-label="Ingredients image — no image available" data-view="Ingredients image"><span class="thumb-title">Ingredients</span><span class="thumb-state">No image</span></button></div><div class="placeholder-art main-image" role="img" aria-label="Front image — no image available"><div class="image-placeholder-content"><span class="image-brand-mark" aria-hidden="true">R</span><strong class="image-state">No image available</strong><span class="image-view">Front image</span></div></div></div>';
replaceOnce(oldVariationGallery, newVariationGallery, "variation product gallery");

replaceOnce(
  '<div class="draft-copy"><strong>Approval example only.</strong> This demonstrates the behavior of a product with selectable options. Final product relationships require confirmation.</div>',
  '<div class="draft-copy"><strong>Choose your option.</strong> Select an available count to see its price and add it to the cart.</div>',
  "variation product copy",
);

replaceOnce(
  '<button class="btn add-demo" type="button">Add to Cart</button></div><p class="meta-line">Online orders ship from Clarkston · Allow two business days for packing · Continental U.S. only</p>',
  '<button class="btn add-demo" id="variation-add" type="button" disabled>Add to Cart</button></div><p class="meta-line">Questions before ordering? Call (248) 843-2011 for friendly product guidance.</p>',
  "variation purchase help and button",
);

replaceOnce(
  '<h2 class="subhead">Related products pattern</h2>',
  '<h2 class="subhead">Related products</h2>',
  "related products heading",
);

const relatedImage = '<div class="placeholder-art"><div class="bottle"></div></div>';
if ((html.split(relatedImage).length - 1) !== 3) throw new Error("Expected three related-product image placeholders");
html = html.replaceAll(
  relatedImage,
  '<div class="placeholder-art mini-no-image" role="img" aria-label="Product image — no image available"><span aria-hidden="true">R</span><small>No image</small></div>',
);

replaceOnce(
  ".mini-product .placeholder-art{min-height:86px}",
  ".mini-product .placeholder-art{min-height:86px}.mini-no-image{align-content:center;gap:4px;color:var(--leaf);font-family:Georgia,serif;font-size:25px}.mini-no-image small{color:#687a72;font-family:var(--sans);font-size:9px;font-weight:700}",
  "related product no-image styles",
);

replaceOnce(
  "gallery.querySelector('.image-caption').textContent=thumb.dataset.caption",
  "gallery.querySelector('.image-view').textContent=thumb.dataset.view;gallery.querySelector('.main-image').setAttribute('aria-label',thumb.getAttribute('aria-label'))",
  "gallery interaction",
);

replaceOnce(
  "Selected option is ready to add in this prototype.",
  "Your selection is ready to add.",
  "variation status feedback",
);

replaceOnce(
  "button.textContent='Added in Demo'",
  "button.textContent='Added'",
  "add-to-cart feedback",
);

if (/clarkston|pilot/i.test(html)) throw new Error("Clarkston or pilot wording remains in v1.4");
if ((html.match(/class="screen-panel"/g) || []).length !== 2) throw new Error("Both product examples must remain");
if ((html.match(/class="thumb"/g) || []).length !== 6) throw new Error("Both three-view galleries must remain");
if ((html.match(/no image available/gi) || []).length < 8) throw new Error("Visible and accessible no-image states are incomplete");
if (/<header\b|<footer\b/i.test(html)) throw new Error("Page-body mockup must not include global chrome");

await fs.writeFile(targetPath, html, "utf8");
console.log(`Built ${targetPath}`);
