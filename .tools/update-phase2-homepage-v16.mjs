import fs from "node:fs/promises";
import { fileURLToPath } from "node:url";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const workbookPath = fileURLToPath(new URL("../outputs/01a034a3-641b-70f2-bc46-4a4f5cf27673/Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx", import.meta.url));
const previewDir = fileURLToPath(new URL("./phase2-workbook-v17-qa", import.meta.url));
await fs.mkdir(previewDir, { recursive: true });

const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(workbookPath));

const approval = workbook.worksheets.getItem("Approval Set");
approval.getRange("J3").values = [["Header + Home + Shop + Product + Revel"]];
approval.getRange("J6").values = [["Review Main Homepage Ecommerce Integration v1.7 internally. Approved systems may then proceed to client approval; no WordPress implementation or live-site work is authorized."]];
approval.getRange("B7:J7").values = [[
  "Main Homepage Ecommerce Integration",
  "All existing homepage components plus the Shop Online hero entry and one ecommerce section with three illustrated shopping paths and four featured product cards",
  "/",
  "In review",
  "Local page-body mockup",
  "High",
  "Preserve every existing homepage component. Add only the Shop Online button and ecommerce section. Use broad-assortment language; omit Clarkston, fulfillment, shipping, and internal notes. Global chrome stays outside this page mockup.",
  "Existing homepage SEO/content remains primary; add clear internal links to /shop/ and useful product discovery without internal mockup or integration language.",
  "Review exact v1.7 body-only mockup: main-homepage-ecommerce-integration-v1.7.html. It keeps the illustrated paths while correcting the stretched image crop and featured-heading punctuation. v1.6 is preserved and superseded."
]];
approval.getRange("B8:J8").values = [[
  "Shop Homepage + Product Catalog",
  "Store introduction, four illustrated wellness-goal paths, shopping benefits and help, catalog search, sort, filters, six sample products, pagination, and no-results recovery",
  "/shop/ and shared archives",
  "In review",
  "Local page-body mockup",
  "High",
  "Use /shop/ as both the store homepage and full catalog. Preserve the complete catalog system while keeping customer-facing copy free of internal system labels, pilot terminology, and location-specific fulfillment language. Real product data still requires Revel/Kosmos validation.",
  "Unique shop introduction, category discovery, archive headings, breadcrumbs, canonicals, pagination, filter/indexation rules, card semantics, and product-help content.",
  "Review exact v1.5 page-body-only concept: shop-catalog-template-v1.5.html. v1.4 is preserved as superseded. Final approval remains deferred until Revel/Kosmos supplies real product data, images, inventory, and taxonomy."
]];
approval.getRange("B9:J9").values = [[
  "Product Page Templates",
  "Simple and variation product examples with three-view galleries, explicit no-image states, quantity and variation controls, facts, cautions, team help, and related products",
  "/product/{slug}/",
  "In review",
  "Local page-body mockup",
  "High",
  "Approve the shared structure and interactions now. Front, Supplement Facts, and Ingredients remain distinct image views; missing photography must use the branded No image available state. Product media and connected catalog content wait for Revel/Kosmos.",
  "Product schema, unique copy, gallery semantics and alt text, facts, cautions, stock language, variation errors, related items, and support information.",
  "Review exact v1.4 page-body-only mockup: product-page-templates-v1.4.html. It removes Clarkston and pilot wording, standardizes both galleries to three clearly labeled views, and preserves every product-page component and interaction."
]];
approval.getRange("B10:J10").values = [[
  "Purchase Path",
  "Connected cart, empty-cart recovery, checkout fields, validation, order review, and customer-facing confirmation",
  "/cart/; /checkout/; confirmation",
  "In review",
  "Local page-body mockup",
  "High",
  "Approve the journey hierarchy and interactions now. Shipping rates, tax, payment fields, wallet options, provider messages, emails, and final totals wait for approved services and testing. Customer-facing copy must remain free of location and internal-review terminology.",
  "Transactional indexation, policy and help links, consent, field labels, validation, recovery, order status, and customer support information.",
  "Review exact v1.4 page-body-only mockup: purchase-path-mockup-v1.4.html. It removes Clarkston, demo, prototype, pilot, and visible local-mockup wording while preserving cart, checkout, validation, confirmation, and all interactions."
]];
approval.getRange("B11:J11").values = [[
  "Customer Account System",
  "Guest checkout, automatic optional account creation, sign-in, password recovery, dashboard, addresses, order history, order detail, and client expectation guidance",
  "/my-account/ and child screens",
  "In review",
  "Local page-body mockup",
  "Medium",
  "Approve the customer experience and operating model now: guest checkout stays available, customers create optional accounts automatically, and staff never approve accounts individually. Email delivery, privacy wording, retention, order sync, and password security wait for connected testing.",
  "Noindex account screens, privacy and consent, recovery security, clear field labels, order access, address editing, and customer support guidance.",
  "Review exact v1.4 page-body-only mockup: customer-account-system-v1.4.html. Standard account features need no separate plugin; routine management is low; focused staff training and a written handoff are required. v1.3 is preserved as superseded."
]];

const register = workbook.worksheets.getItem("Version Register");
register.getRange("I3").values = [["Gates: Header + Home + Shop + Product + Revel"]];
register.getRange("B7:I7").values = [[
  "Main Homepage Ecommerce Integration",
  "v1.7",
  "main-homepage-ecommerce-integration-v1.7.html",
  "Internal Review",
  "Awaiting Todd review",
  "Not reviewed",
  "v1.8",
  "Corrected the three-panel shopping-path asset to crop proportionally instead of stretching, preserving the phone-guidance concept; removed the comma from the featured heading; preserved all homepage components, shopping paths, product cards, interactions, and body-only architecture. v1.6 is preserved and superseded."
]];
register.getRange("B8:I8").values = [[
  "Shop Homepage + Product Catalog",
  "v1.5",
  "shop-catalog-template-v1.5.html",
  "Revel Validation Pending",
  "Awaiting Todd review; final approval deferred pending Revel validation",
  "Not reviewed",
  "v1.6",
  "Removed internal system, pilot, location, and mockup-note copy; added general shopping benefits and team guidance; preserved imagery, six products, search, sort, filters, drawer, load-more, and no-results behavior; no global chrome."
]];
register.getRange("B9:I9").values = [[
  "Product Page Templates",
  "v1.4",
  "product-page-templates-v1.4.html",
  "Internal Review",
  "Awaiting Todd review",
  "Not reviewed",
  "v1.5",
  "Removed all Clarkston and pilot wording; standardized both galleries to Front, Facts, and Ingredients; added branded No image available states; preserved both product examples, controls, fact cards, help, related items, and interactions; no global chrome."
]];
register.getRange("B10:I10").values = [[
  "Purchase Path",
  "v1.4",
  "purchase-path-mockup-v1.4.html",
  "Internal Review",
  "Awaiting Todd review",
  "Not reviewed",
  "v1.5",
  "Removed Clarkston, demo, prototype, pilot, and visible local-mockup wording; replaced confirmation with order number, confirmed status, and the main help number; preserved cart, empty cart, checkout, validation, order review, confirmation, and all interactions; no global chrome."
]];
register.getRange("B11:I11").values = [[
  "Customer Account System",
  "v1.4",
  "customer-account-system-v1.4.html",
  "Internal Review",
  "Awaiting Todd review",
  "Not reviewed",
  "v1.5",
  "Clarified guest checkout and automatic optional accounts with no staff approval; added cost, low-management, training, and written-handoff expectations; removed Clarkston, demo, prototype, and pilot wording; preserved all six screens and interactions; no global chrome."
]];

const exported = await SpreadsheetFile.exportXlsx(workbook);
await exported.save(workbookPath);

for (const [file, sheetName, range, scale] of [
  ["approval-set.png", "Approval Set", "A1:J12", 1.1],
  ["version-register.png", "Version Register", "A1:I18", 1.2],
]) {
  const preview = await workbook.render({ sheetName, range, scale, format: "png" });
  await fs.writeFile(`${previewDir}/${file}`, new Uint8Array(await preview.arrayBuffer()));
}

const approvalCheck = await workbook.inspect({
  kind: "table",
  sheetId: "Approval Set",
  range: "A1:J12",
  include: "values,formulas",
  tableMaxRows: 12,
  tableMaxCols: 10,
  maxChars: 18000,
});
const registerCheck = await workbook.inspect({
  kind: "table",
  sheetId: "Version Register",
  range: "A1:I12",
  include: "values,formulas",
  tableMaxRows: 12,
  tableMaxCols: 9,
  maxChars: 16000,
});
const errors = await workbook.inspect({
  kind: "match",
  searchTerm: "#REF!|#DIV/0!|#VALUE!|#NAME\\?|#N/A",
  options: { useRegex: true, maxResults: 300 },
  summary: "formula error scan",
});

console.log("APPROVAL_SET");
console.log(approvalCheck.ndjson);
console.log("VERSION_REGISTER");
console.log(registerCheck.ndjson);
console.log("FORMULA_ERRORS");
console.log(errors.ndjson);
console.log(`OUTPUT ${workbookPath}`);
