import fs from "node:fs/promises";
import path from "node:path";

const projectDir = path.resolve(import.meta.dirname, "..");

const desktopChromeFix = String.raw`

    /* v1.2: keep the complete approved header visible at ordinary desktop widths. */
    @media(min-width:1201px) and (max-width:1380px){
      .site-header{padding:0 18px}
      .brand{width:205px;margin:0;flex:0 0 auto}
      .site-header nav{position:absolute;top:auto;left:50%;right:auto;width:auto;max-height:none;padding:0;display:flex;flex-direction:row;align-items:center;gap:10px;overflow:visible;background:transparent;border-bottom:0;box-shadow:none;font-size:13px;transform:translateX(-50%)}
      .site-header nav>a,.nav-trigger{width:auto;min-height:44px;padding:0;display:inline-flex;border-bottom:0;text-align:left}
      .nav-trigger{justify-content:flex-start;gap:5px}
      .nav-trigger .chevron{margin-left:0}
      .nav-dropdown{width:auto}
      .dropdown-panel{position:absolute;width:242px;margin:0;padding:10px;border-radius:15px;box-shadow:0 20px 50px rgba(23,76,60,.15)}
      .menu{display:none}
      .header-actions{margin-left:auto;display:flex;gap:6px}
      .header-actions .pill{min-height:42px;padding:0 11px;font-size:13px}
      .mobile-actions{display:none}
    }
`;

const revisions = [
  {
    input: "online-store-homepage-mockup-v1.1.html",
    output: "online-store-homepage-mockup-v1.2.html",
    title: "Online Store Homepage v1.2 | Rebekah's Health & Nutrition",
    chip: "Online Store Homepage · v1.2 · Internal Review · Local Only",
  },
  {
    input: "shop-catalog-template-v1.1.html",
    output: "shop-catalog-template-v1.2.html",
    title: "Shop + Catalog Template v1.2 | Rebekah's Health & Nutrition",
    chip: "Shop + Catalog Template · v1.2 · Internal Review · Local Only",
  },
  {
    input: "product-page-templates-v1.1.html",
    output: "product-page-templates-v1.2.html",
    title: "Product Page Templates v1.2 | Rebekah's Health & Nutrition",
    chip: "Product Page Templates · v1.2 · Internal Review · Local Only",
  },
  {
    input: "purchase-path-mockup-v1.1.html",
    output: "purchase-path-mockup-v1.2.html",
    title: "Purchase Path v1.2 | Rebekah's Health & Nutrition",
    chip: "Purchase Path · v1.2 · Internal Review · Local Only",
  },
  {
    input: "customer-account-system-v1.1.html",
    output: "customer-account-system-v1.2.html",
    title: "Customer Account System v1.2 | Rebekah's Health & Nutrition",
    chip: "Customer Account System · v1.2 · Internal Review · Local Only",
  },
  {
    input: "store-states-components-v1.1.html",
    output: "store-states-components-v1.2.html",
    title: "Store States + Components v1.2 | Rebekah's Health & Nutrition",
    chip: "Store States + Components · v1.2 · Internal Review · Local Only",
  },
];

for (const revision of revisions) {
  const inputPath = path.join(projectDir, revision.input);
  let html = await fs.readFile(inputPath, "utf8");
  html = html
    .replace(/<title>[\s\S]*?<\/title>/, `<title>${revision.title}</title>`)
    .replace("  </style>", `${desktopChromeFix}\n  </style>`)
    .replace(/<div class="version-chip">[\s\S]*?<\/div>/, `<div class="version-chip">${revision.chip}</div>`);
  await fs.writeFile(path.join(projectDir, revision.output), html, "utf8");
}

console.log("Built Phase Two page mockups v1.2 with corrected desktop chrome visibility.");
