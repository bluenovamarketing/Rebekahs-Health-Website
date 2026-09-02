import fs from "node:fs/promises";
import { fileURLToPath } from "node:url";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const workbookPath = fileURLToPath(new URL("../outputs/01a034a3-641b-70f2-bc46-4a4f5cf27673/Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx", import.meta.url));
const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(workbookPath));

const approval = workbook.worksheets.getItem("Approval Set");
approval.getRange("J3").values = [["Header + mockups + Revel"]];
approval.getRange("J6").values = [["Awaiting Rebekah’s client approval before any WordPress implementation or live-site work."]];
approval.getRange("B7:J7").values = [[
  "Main Homepage Ecommerce Integration",
  "All existing homepage components plus Shop Online hero entry and one focused Phase Two shopping section",
  "/",
  "In review",
  "Local page-body mockup",
  "High",
  "Keep the existing main homepage and preserve every current component. Add ecommerce entry points only; gold addition labels/outlines are review-only and will not appear live. Global header, menu, utility row, and footer remain outside this page mockup.",
  "Existing homepage SEO/content remains primary; add clear internal links to /shop/ and concise representative product discovery.",
  "Review exact v1.5 body-only mockup. It labels the Shop Online button and ecommerce section as the only additions: main-homepage-ecommerce-integration-v1.5.html."
]];
approval.getRange("B8:J8").values = [[
  "Shop Homepage + Product Catalog",
  "Store introduction, four illustrated wellness-goal paths, Clarkston fulfillment/help, catalog search, sort, filters, six pilot products, pagination, and no-results recovery",
  "/shop/ and shared archives",
  "In review",
  "Local page-body mockup",
  "High",
  "Use /shop/ as both the store homepage and full catalog. Do not create a duplicate /online-store/ landing page. Category, brand, collection, and search archives reuse the catalog pattern.",
  "Unique shop introduction, category discovery, archive headings, breadcrumbs, canonicals, pagination, filter/indexation rules, card semantics, and fulfillment/help content.",
  "Todd likes the v1.4 direction, but it is not approved. Final review waits for a Revel/Kosmos-connected revision populated with real product data, images, inventory, and taxonomy: shop-catalog-template-v1.4.html remains the current concept."
]];
approval.getRange("B9:J12").values = [[
  "Product Page Templates",
  "Simple product and variation product examples with labeled gallery previews, quantity, conditional add-to-cart, facts, cautions, fulfillment guidance, and related products",
  "/product/{slug}/",
  "In review",
  "Local page-body mockup",
  "High",
  "Approve the shared structure and interactions now. Real images, descriptions, supplement facts, prices, variations, stock language, weights, and related-product rules wait for Revel/Kosmos and client content approval.",
  "Product schema, unique copy, gallery, facts, cautions, stock, shipping note, variation errors, and related items.",
  "Review exact v1.3 page-body-only mockup: product-page-templates-v1.3.html. v1.1 and v1.2 remain preserved as superseded history."
],[
  "Purchase Path",
  "Connected cart, internally consistent empty cart, guest checkout, validation, order review, and order confirmation",
  "/cart/; /checkout/; confirmation",
  "In review",
  "Local page-body mockup",
  "High",
  "Approve the journey hierarchy now. Shipping rates, tax, payment fields, wallet options, provider messages, emails, and final totals wait for approved services and testing.",
  "Transactional indexation, policy/help links, consent, field labels, validation, and safe recovery.",
  "Review exact v1.3 page-body-only mockup: purchase-path-mockup-v1.3.html. Empty-cart preview now hides the filled summary and disables checkout."
],[
  "Customer Account System",
  "Login, optional registration, password reset, dashboard, addresses with edit demo, order history, and order detail",
  "/my-account/ and child screens",
  "In review",
  "Local page-body mockup",
  "Medium",
  "Approve the account structure and local-only feedback now. Authentication, email delivery, password policy, consent, customer fields, order sync, and retention rules wait for final configuration.",
  "Noindex account screens, privacy/consent, password recovery, order and address clarity.",
  "Review exact v1.3 page-body-only mockup: customer-account-system-v1.3.html. Demo interactions store and submit no customer data."
],[
  "Store States + Components",
  "Ten non-ideal states plus mobile filter dialog and responsive/accessibility checklist",
  "Shared across store",
  "In review",
  "Local page-body mockup",
  "High",
  "Approve recovery hierarchy and accessibility behavior now. Exact inventory, shipping, tax, payment, authentication, and integration-error messages wait for real connected-service responses.",
  "Honest stock/error language, accessible recovery actions, complete responsive behavior, keyboard/focus, labels, contrast, and reduced motion.",
  "Review exact v1.3 page-body-only mockup: store-states-components-v1.3.html. Drawer dialog semantics and focus-return behavior are included."
]];

const register = workbook.worksheets.getItem("Version Register");
register.getRange("I3").values = [["Current gates: header + mockups + Revel"]];
register.getRange("B7:I7").values = [[
  "Main Homepage Ecommerce Integration",
  "v1.5",
  "main-homepage-ecommerce-integration-v1.5.html",
  "Internal Review",
  "Awaiting Todd review",
  "Not reviewed",
  "v1.6",
  "Review-only gold labels identify the Shop Online button and ecommerce section as the only additions. Existing components remain preserved; hero-video preview source corrected; no global chrome."
]];
register.getRange("B8:I8").values = [[
  "Shop Homepage + Product Catalog",
  "v1.4",
  "shop-catalog-template-v1.4.html",
  "Revel Validation Pending",
  "Direction liked; approval deferred pending Revel validation",
  "Not reviewed",
  "v1.5",
  "Todd likes the illustrated v1.4 direction, but it is not approved. A new populated revision must be reviewed after Revel/Kosmos supplies real product data, images, inventory, and taxonomy; no global chrome."
]];
register.getRange("B9:I12").values = [[
  "Product Page Templates",
  "v1.3",
  "product-page-templates-v1.3.html",
  "Internal Review",
  "Awaiting Todd review",
  "Not reviewed",
  "v1.4",
  "Preserved both product examples; added structure-versus-Revel boundary, labeled gallery preview behavior, and required variation selection before add-to-cart; no global chrome."
],[
  "Purchase Path",
  "v1.3",
  "purchase-path-mockup-v1.3.html",
  "Internal Review",
  "Awaiting Todd review",
  "Not reviewed",
  "v1.4",
  "Preserved cart through confirmation; corrected empty-cart summary and checkout state; added connected-service review boundary; no global chrome."
],[
  "Customer Account System",
  "v1.3",
  "customer-account-system-v1.3.html",
  "Internal Review",
  "Awaiting Todd review",
  "Not reviewed",
  "v1.4",
  "Preserved all account screens; added safe local-only feedback and address-edit demonstration plus configuration boundary; no global chrome."
],[
  "Store States + Components",
  "v1.3",
  "store-states-components-v1.3.html",
  "Internal Review",
  "Awaiting Todd review",
  "Not reviewed",
  "v1.4",
  "Preserved all shared states and checklist; added connected-service boundary and accessible mobile filter dialog behavior; no global chrome."
]];

const exported = await SpreadsheetFile.exportXlsx(workbook);
await exported.save(workbookPath);

const registerPreview = await workbook.render({sheetName:"Version Register",range:"A1:I18",scale:1.25,format:"png"});
await fs.writeFile(new URL("../.tools/phase2-version-register-approved-architecture.png",import.meta.url),new Uint8Array(await registerPreview.arrayBuffer()));
const approvalPreview = await workbook.render({sheetName:"Approval Set",range:"A1:J12",scale:1.1,format:"png"});
await fs.writeFile(new URL("../.tools/phase2-approval-set-approved-architecture.png",import.meta.url),new Uint8Array(await approvalPreview.arrayBuffer()));

const registerCheck = await workbook.inspect({kind:"table",sheetId:"Version Register",range:"A1:I12",include:"values,formulas",tableMaxRows:12,tableMaxCols:9,maxChars:14000});
console.log("VERSION_REGISTER");
console.log(registerCheck.ndjson);
const approvalCheck = await workbook.inspect({kind:"table",sheetId:"Approval Set",range:"A1:J12",include:"values,formulas",tableMaxRows:12,tableMaxCols:10,maxChars:18000});
console.log("APPROVAL_SET");
console.log(approvalCheck.ndjson);
const errors = await workbook.inspect({kind:"match",searchTerm:"#REF!|#DIV/0!|#VALUE!|#NAME\\?|#N/A",options:{useRegex:true,maxResults:300},summary:"final formula error scan"});
console.log("FORMULA_ERRORS");
console.log(errors.ndjson);
console.log(`OUTPUT ${workbookPath}`);
