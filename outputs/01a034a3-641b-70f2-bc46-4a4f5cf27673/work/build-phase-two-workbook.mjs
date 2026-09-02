import fs from "node:fs/promises";
import path from "node:path";
import { SpreadsheetFile, Workbook } from "@oai/artifact-tool";

const workspace = "C:/Users/todda/Blue Nova Projects/Rebekahs Health Website";
const outputDir = path.join(workspace, "outputs/01a034a3-641b-70f2-bc46-4a4f5cf27673");
const previewDir = path.join(outputDir, "work/phase-two-preview");
const outputPath = path.join(outputDir, "Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx");
await fs.mkdir(previewDir, { recursive: true });

const workbook = Workbook.create();
const approval = workbook.worksheets.add("Approval Set");
const templates = workbook.worksheets.add("Templates & Global");
const product = workbook.worksheets.add("Product & Content");
const decisions = workbook.worksheets.add("Decisions & Access");
const implementation = workbook.worksheets.add("Implementation Plan");
const qa = workbook.worksheets.add("QA & Launch Gates");
const scope = workbook.worksheets.add("Scope & Costs");

const c = { forest: "#195C3B", sage: "#EEF5E8", header: "#E5E7EB", white: "#FFFFFF", ink: "#111827", muted: "#33413A", review: "#F7E8B8", approved: "#D9EFD9", pending: "#F8D7DA" };
const font = { name: "Carlito", size: 10, color: c.ink };

function title(sheet, range, text) {
  sheet.getRange(range).merge();
  const cell = sheet.getRange(range.split(":")[0]);
  cell.values = [[text]];
  cell.format = { fill: c.forest, font: { name: "Carlito", size: 16, bold: true, color: c.white }, verticalAlignment: "center" };
  sheet.getRange(range).format.rowHeight = 32;
}
function note(sheet, range, text) {
  sheet.getRange(range).merge();
  const cell = sheet.getRange(range.split(":")[0]);
  cell.values = [[text]];
  cell.format = { fill: c.sage, font: { name: "Carlito", size: 11, italic: true, color: c.muted }, wrapText: true, verticalAlignment: "center" };
  sheet.getRange(range).format.rowHeight = 36;
}
function headers(range) { range.format = { fill: c.header, font: { name: "Carlito", size: 10, bold: true, color: c.ink }, wrapText: true, verticalAlignment: "center" }; range.format.rowHeight = 30; }
function body(range, height = 66) { range.format = { font, wrapText: true, verticalAlignment: "top" }; range.format.rowHeight = height; }
function label(sheet, cell, text) { sheet.getRange(cell).values = [[text]]; sheet.getRange(cell).format = { fill: c.sage, font: { name: "Carlito", size: 10, bold: true, color: c.forest }, verticalAlignment: "center" }; }
function metric(sheet, cell, value) { if (typeof value === "string" && value.startsWith("=")) sheet.getRange(cell).formulas = [[value]]; else sheet.getRange(cell).values = [[value]]; sheet.getRange(cell).format = { fill: c.white, font: { name: "Carlito", size: 11, bold: true, color: c.ink }, horizontalAlignment: "center", verticalAlignment: "center" }; }
function base(sheet, freezeRows) { sheet.freezePanes.freezeRows(freezeRows); sheet.showGridLines = false; }
function statusRules(range) {
  range.conditionalFormats.add("containsText", { text: "Approved", format: { fill: c.approved, font: { bold: true, color: c.forest } } });
  range.conditionalFormats.add("containsText", { text: "Confirmed", format: { fill: c.approved, font: { bold: true, color: c.forest } } });
  range.conditionalFormats.add("containsText", { text: "In review", format: { fill: c.review, font: { bold: true, color: "#6B501C" } } });
  range.conditionalFormats.add("containsText", { text: "Internally Approved", format: { fill: c.approved, font: { bold: true, color: c.forest } } });
  range.conditionalFormats.add("containsText", { text: "Needs approval", format: { fill: c.review, font: { bold: true, color: "#6B501C" } } });
  range.conditionalFormats.add("beginsWith", { text: "Pending", format: { fill: c.pending, font: { color: "#842029" } } });
}

// 1. Complete seven-system design approval set.
title(approval, "A1:J1", "Rebekah's Health & Nutrition - Complete Phase Two Ecommerce Approval Set");
note(approval, "A2:J2", "Current authorization: local planning and responsive mockups only. No Cloudways, staging, live website, WordPress implementation, integrations, purchases, or paid services. Every system below must be approved before implementation.");
label(approval, "A3", "Systems"); metric(approval, "B3", "=COUNTA(B6:B12)");
label(approval, "C3", "Internally approved"); metric(approval, "D3", '=COUNTIF(E6:E12,"Internally Approved")');
label(approval, "E3", "Planned"); metric(approval, "F3", '=COUNTIF(E6:E12,"Planned")+COUNTIF(E6:E12,"Planned next")');
label(approval, "G3", "Client approved"); metric(approval, "H3", '=COUNTIF(E6:E12,"Client Approved")');
label(approval, "I3", "Current gate"); metric(approval, "J3", "Client approval"); approval.getRange("A3:J3").format.rowHeight = 26;
const approvalRows = [
  ["#", "Approval System", "Screens / States", "Route(s)", "Design Status", "Build Status", "Priority", "Approval Boundary", "SEO / Content Coverage", "Gate / Next Step"],
  [1, "Header + Footer Ecommerce Add-On", "Approved header, responsive menu, ecommerce utility row, fifth expandable footer section", "Sitewide", "Internally Approved", "Local mockup", "High", "Literal addition to approved Phase One chrome: Online Store, search, account, cart/count, and one fifth footer section only.", "Store entry, internal links, policy links, responsive navigation, account/cart indexation.", "Awaiting Rebekah’s client approval before starting the Online Store homepage."],
  [2, "Online Store Homepage", "Store introduction, Shop All path, category/brand/goal discovery, featured products, product-help guidance", "/online-store/ or approved store entry", "Planned next", "Not started", "High", "Separate from the main website homepage and the product archive. Rebuild the recovered early concept in the approved chrome.", "Unique store landing copy, discovery paths, internal links, trust/help messaging, representative products.", "Approve before finalizing the archive hierarchy."],
  [3, "Shop + Catalog Template", "Shop All, category, brand, collection, search results, product cards, filters, sorting, pagination, no results", "/shop/ and shared archives", "Planned", "Not started", "High", "One reusable archive template; no separate one-off designs for every category, brand, collection, or search result.", "Archive headings, breadcrumbs, canonicals, pagination, filter/indexation rules, card semantics.", "Approve before product-page work."],
  [4, "Product Page Templates", "Simple product and variation product examples", "/product/{slug}/", "Planned", "Not started", "High", "Two approval examples share one system. Final fields wait for the first Revel/Kosmos product. Reviews and unsupported claims are excluded.", "Product schema, unique copy, gallery, facts, cautions, stock, shipping note, variation errors, related items.", "Approve both examples before the purchase path."],
  [5, "Purchase Path", "Cart, empty cart, checkout, validation, order confirmation", "/cart/; /checkout/; confirmation", "Planned", "Not started", "High", "One connected prototype. Shipping, tax, payment, and failure specifics remain provisional until approved services are available.", "Transactional indexation, clear policy/help links, consent, field labels, useful validation and recovery.", "Approve the complete cart-to-confirmation journey."],
  [6, "Customer Account System", "Login, optional registration, password reset, dashboard, addresses, order history, order detail", "/my-account/ and child screens", "Planned", "Not started", "Medium", "One reusable account shell. Guest checkout with optional accounts remains proposed until client approval.", "Noindex account screens, privacy/consent, password recovery, order and address clarity.", "Approve after purchase-path rules are settled."],
  [7, "Store States + Components", "No results, filter empty, out of stock, unavailable variation, missing image, loading, cart empty, validation/errors, mobile filter", "Shared across store", "Planned", "Not started", "High", "Approve non-ideal states and run desktop, tablet, phone, keyboard, focus, contrast, labels, and reduced-motion review across all systems.", "Honest stock/error language, accessible recovery actions, complete responsive behavior.", "Final design-system QA and client approval."],
];
approval.getRange("A5:J12").values = approvalRows; headers(approval.getRange("A5:J5")); body(approval.getRange("A6:J12"), 80);
approval.getRange("A6:A12").format.horizontalAlignment = "center"; base(approval, 5);
approval.getRange("A:A").format.columnWidth = 8; approval.getRange("B:B").format.columnWidth = 25; approval.getRange("C:C").format.columnWidth = 32; approval.getRange("D:D").format.columnWidth = 23; approval.getRange("E:G").format.columnWidth = 14; approval.getRange("H:J").format.columnWidth = 37;
approval.getRange("E6:E30").dataValidation = { rule: { type: "list", values: ["Planned", "Planned next", "In review", "Revision needed", "Internally Approved", "Client Approved"] } };
approval.getRange("F6:F30").dataValidation = { rule: { type: "list", values: ["Not started", "Local mockup", "Local prototype", "Approved for build", "Implemented"] } };
approval.getRange("G6:G30").dataValidation = { rule: { type: "list", values: ["High", "Medium", "Low"] } }; statusRules(approval.getRange("E6:E30"));
approval.tables.add("A5:J12", true, "PhaseTwoApprovalSet").style = "TableStyleMedium2";

// 2. Reusable templates, components, and global work.
title(templates, "A1:E1", "Phase Two Reusable Templates, Components, and Global Work");
note(templates, "A2:E2", "Approve reusable systems once, then adapt real data without redesigning sibling routes. Supporting content and technical systems are tracked even when they do not need their own polished page mockup.");
const templateRows = [
  ["Type", "Component", "Applies To", "Approval Requirement", "Coverage / Reuse Rule"],
  ["Template", "Online Store homepage", "Primary ecommerce landing page", "Approve one responsive mockup", "Store introduction, Shop All, discovery routes, representative featured products, product-help guidance, store/in-store relationship."],
  ["Template", "Catalog/archive shell", "Shop All, category, brand, collection, and product search", "Approve once", "Shared heading, intro, breadcrumbs, product grid, search, sorting, filters, pagination, and empty state."],
  ["Component", "Product card", "Every product grid and related-product area", "Approve with catalog", "Image, name, brand/category context, price placeholder, stock/sale state, and accessible action. No reviews or wishlist."],
  ["Template", "Simple product page", "Standard single products", "Approve example", "Gallery, facts, price/stock placeholders, quantity, add to cart, cautions, shipping note, related products."],
  ["Template", "Variation product page", "Products with selectable options", "Approve example", "Reuse simple layout with option selection, availability, price changes, unavailable combinations, and errors."],
  ["Prototype", "Purchase path", "Cart, checkout, and order confirmation", "Approve connected flow", "One customer journey with empty cart, validation, policy/help links, and confirmation—not unrelated pages."],
  ["Template", "Customer account shell", "Login, registration, reset, dashboard, addresses, and orders", "Approve representative states", "One compact shell; optional accounts remain provisional until approved."],
  ["Component", "Store states", "Catalog, product, cart, checkout, and account", "Approve component sheet", "No results, stock/variation, missing image, loading, cart empty, validation, payment placeholder, and mobile filter states."],
  ["Global", "Commerce navigation", "Sitewide", "Internally approved; awaiting client", "Approved Phase One header plus Online Store, search, My Account, and cart/count. Do not redesign original navigation."],
  ["Global", "Online Store footer section", "Sitewide footer", "Internally approved; awaiting client", "Add one fifth expandable section while preserving original footer and accordion behavior."],
  ["Content", "Transactional emails", "Customer and staff order notifications", "Approve during implementation", "Sender/business details, order content, help links, delivery, failed payment, refund, cancellation, and low-stock behavior."],
  ["Content", "Ecommerce policies", "Shipping, Returns, Terms, Privacy, Disclaimer", "Content approval required", "Accounts, checkout, payments, emails, processors, shipping, returns, cancellation, damage/loss, and customer-service details."],
  ["Global", "Commerce SEO", "Storefront and transaction screens", "Integrated QA", "Titles, metadata, canonicals, schema, breadcrumbs, sitemaps, archive/indexation rules, image SEO, and internal linking."],
  ["Global", "Accessibility + responsive system", "All seven approval systems", "Integrated QA", "Desktop/tablet/phone, keyboard, focus, labels, errors, contrast, reduced motion, and assistive-technology semantics."],
  ["Global", "Analytics + business events", "Catalog through confirmation", "Implementation QA", "Search, product view, add to cart, checkout start, purchase, errors, and consent-aware tracking."],
];
templates.getRange("A4:E19").values = templateRows; headers(templates.getRange("A4:E4")); body(templates.getRange("A5:E19"), 64); base(templates, 4);
templates.getRange("A:A").format.columnWidth = 14; templates.getRange("B:B").format.columnWidth = 29; templates.getRange("C:C").format.columnWidth = 34; templates.getRange("D:D").format.columnWidth = 25; templates.getRange("E:E").format.columnWidth = 54;
templates.getRange("A5:A35").dataValidation = { rule: { type: "list", values: ["Template", "Component", "Prototype", "Global", "Content"] } };
templates.tables.add("A4:E19", true, "PhaseTwoTemplates").style = "TableStyleMedium2";

// 3. Product and content requirements.
title(product, "A1:H1", "Phase Two Product and Content Requirements");
note(product, "A2:H2", "Test one prepared product before assuming what Revel/Kosmos transfers or overwrites. Blue Nova audits the 25-product set and sends one consolidated gap list; final product accuracy and permitted claims require Rebekah's approval.");
label(product, "A3", "Requirements"); metric(product, "B3", "=COUNTA(B6:B24)"); label(product, "C3", "Pending"); metric(product, "D3", '=COUNTIF(F6:F24,"Pending access")+COUNTIF(F6:F24,"Pending product data")+COUNTIF(F6:F24,"Pending pilot data")+COUNTIF(F6:F24,"Pending sync test")'); label(product, "E3", "Confirmed"); metric(product, "F3", '=COUNTIF(F6:F24,"Confirmed")'); label(product, "G3", "Pilot size"); metric(product, "H3", 25); product.getRange("A3:H3").format.rowHeight = 26;
const productRows = [
  ["Area", "Field / Asset", "System of Record", "Required For", "Owner", "Status", "Validation / Acceptance", "Notes"],
  ["Eligibility", "Active + Display on online and 3rd party", "Revel", "Synchronization", "Rebekah / Mark", "Pending product data", "Only intended pilot products appear online.", "Confirm Clarkston establishment eligibility."],
  ["Identity", "Customer-friendly product name", "Revel", "Catalog and product page", "Rebekah / Mark", "Pending product data", "No register shorthand; variation parent name is consistent.", "Must survive synchronization."],
  ["Identity", "Unique SKU / barcode / UPC", "Revel", "Mapping and orders", "Rebekah / Mark", "Pending product data", "No reused identifier across products or variations.", "Primary reconciliation key."],
  ["Commerce", "Current selling price", "Revel", "Product, cart, checkout", "Rebekah / Mark", "Pending product data", "Website price matches Revel after update.", "Test overwrite behavior."],
  ["Commerce", "Clarkston inventory + stock status", "Revel", "Availability and orders", "Rebekah / Mark", "Pending product data", "Nonnegative quantity; order reduces Clarkston only.", "Validate buffer/backorder rules."],
  ["Shipping", "Accurate weight; dimensions for exceptions", "Revel", "USPS live rates", "Rebekah / Mark", "Pending product data", "Representative carts return valid rates.", "Blue Nova configures packing; client flags bulky items."],
  ["Taxonomy", "Operational category", "Revel + Kosmos mapping", "Import structure", "Rebekah / Blue Nova", "Pending product data", "Duplicates and spelling variants reconciled.", "Website taxonomy may differ."],
  ["Taxonomy", "Brand / manufacturer", "Revel when supported", "Filters and product detail", "Rebekah / Mark", "Pending product data", "Consistent approved brand label.", "Map after first import."],
  ["Variations", "Parent, options, SKUs, prices, inventory", "Revel", "Variable product page", "Rebekah / Mark", "Pending product data", "Every combination maps and unavailable choices behave correctly.", "One variation example required."],
  ["Media", "Primary and gallery images", "WordPress after ownership test", "Product presentation", "Blue Nova / Rebekah", "Pending sync test", "Approved, high quality, correct product, usable alt text.", "Do not polish before overwrite behavior is known."],
  ["Content", "Customer-facing description", "WordPress after ownership test", "Product detail and SEO", "Blue Nova / Rebekah", "Pending sync test", "Accurate, original, claims-safe, approved.", "Authorized manufacturer sources first."],
  ["Compliance", "Ingredients and allergen information", "WordPress / approved source", "Product detail", "Blue Nova / Rebekah", "Pending product data", "Matches approved label/source.", "Where applicable."],
  ["Compliance", "Directions and serving information", "WordPress / approved source", "Product detail", "Blue Nova / Rebekah", "Pending product data", "Matches approved label/source.", "No invented guidance."],
  ["Compliance", "Supplement Facts / required label details", "WordPress / approved source", "Product detail", "Blue Nova / Rebekah", "Pending product data", "Readable and accurate.", "Use approved assets/data."],
  ["Compliance", "Warnings, restrictions, shipping eligibility", "WordPress / Revel flag", "Product and checkout", "Rebekah / Blue Nova", "Pending product data", "Known restrictions are visible and enforced.", "Routine products assumed shippable until exceptions identified."],
  ["Merchandising", "Website categories, goals, collections, filters", "WordPress", "Store home and catalog", "Blue Nova / Rebekah", "Pending pilot data", "Useful, nonduplicative, supported by the pilot catalog.", "Provisional until 25 products are visible."],
  ["SEO", "SEO title, meta, product schema, image alt text", "WordPress", "Search visibility", "Blue Nova", "Not started", "Unique, accurate, valid, indexation rules followed.", "Complete after final content."],
  ["Merchandising", "Related products and collection rules", "WordPress", "Product detail", "Blue Nova / Rebekah", "Pending pilot data", "Relevant and available; no unsupported automation assumptions.", "Approve after taxonomy."],
  ["Approval", "Final 25-product SKU/name list and content gaps", "Reconciled pilot record", "Pilot approval", "Blue Nova / Rebekah", "Pending access", "Rebekah approves exact list, missing content, and final customer presentation.", "Blue Nova produces the list; client does not build a separate sheet."],
];
product.getRange("A5:H24").values = productRows; headers(product.getRange("A5:H5")); body(product.getRange("A6:H24"), 62); base(product, 5);
product.getRange("A:A").format.columnWidth = 15; product.getRange("B:B").format.columnWidth = 33; product.getRange("C:C").format.columnWidth = 27; product.getRange("D:D").format.columnWidth = 27; product.getRange("E:E").format.columnWidth = 23; product.getRange("F:F").format.columnWidth = 20; product.getRange("G:G").format.columnWidth = 38; product.getRange("H:H").format.columnWidth = 35;
product.getRange("F6:F40").dataValidation = { rule: { type: "list", values: ["Not started", "Pending access", "Pending product data", "Pending pilot data", "Pending sync test", "In review", "Confirmed"] } }; statusRules(product.getRange("F6:F40"));
product.tables.add("A5:H24", true, "PhaseTwoProductContent").style = "TableStyleMedium2";

// 4. Client decisions, access, and prerequisites.
title(decisions, "A1:G1", "Phase Two Decisions, Access, and Prerequisite Gate");
note(decisions, "A2:G2", "Confirmed items may appear in local mockups. Proposed or pending items remain provisional. No staging, integration trial, purchase, or live work begins until the applicable prerequisite gate is complete.");
label(decisions, "A3", "Tracked"); metric(decisions, "B3", "=COUNTA(B6:B28)"); label(decisions, "C3", "Confirmed"); metric(decisions, "D3", '=COUNTIF(D6:D28,"Confirmed")'); label(decisions, "E3", "Needs approval"); metric(decisions, "F3", '=COUNTIF(D6:D28,"Needs approval")'); label(decisions, "G3", "Current work: local only"); decisions.getRange("A3:G3").format.rowHeight = 26;
const decisionRows = [
  ["Area", "Decision / Access", "Current Direction", "Status", "Needed For", "Owner", "Gate / Next Action"],
  ["Fulfillment", "Pilot fulfillment location", "Clarkston only", "Confirmed", "Shipping, inventory, order routing", "Rebekah / Blue Nova", "Use Clarkston only."],
  ["Fulfillment", "Origin and handling time", "7093 Suite B, Dixie Highway, Clarkston; two business days", "Confirmed", "Rates, policies, notices", "Rebekah", "Use approved handling wording."],
  ["Shipping", "Service region", "Continental United States only", "Confirmed", "Store notice and checkout", "Rebekah", "No Alaska, Hawaii, or international claims."],
  ["Shipping", "Rate method", "USPS live address-dependent rates", "Confirmed", "Checkout implementation", "Rebekah / Blue Nova", "No free-shipping promise in mockups."],
  ["Shipping", "PO boxes", "Not yet decided", "Needs approval", "Checkout and policy", "Rebekah", "Approve before shipping configuration."],
  ["Shipping", "Known special-handling or nonmailable items", "Routine products assumed shippable; exceptions unknown", "Needs approval", "Product restrictions and rates", "Rebekah / Mark", "Flag known exceptions during pilot selection."],
  ["Accounts", "Guest checkout and accounts", "Guest checkout with optional accounts proposed", "Needs approval", "Checkout and account system", "Rebekah", "Approve before final copy/configuration."],
  ["Inventory", "Backorders", "Off proposed", "Needs approval", "Product/cart behavior", "Rebekah / Blue Nova", "Validate with sync, then approve."],
  ["Inventory", "Safety buffer", "One unit proposed unless sync is immediate", "Needs approval", "Oversell protection", "Rebekah / Blue Nova", "Validate sync timing, then approve."],
  ["Promotions", "Coupons, gift certificates, store credit, loyalty", "Excluded unless specifically approved", "Confirmed", "Pilot scope", "Rebekah", "Keep out of design and build."],
  ["Merchandising", "Product reviews and wishlist", "Excluded", "Confirmed", "Catalog and products", "Rebekah / Blue Nova", "Do not show controls or ratings."],
  ["Tax", "Tax rules and tax on shipping", "Not supplied", "Needs approval", "Checkout and testing", "Rebekah", "Provide approved configuration direction."],
  ["Payments", "Final Fiserv/Clover gateway product", "Clover Ecommerce/Clover Payments likely", "Pending decision", "Payment configuration", "Rebekah", "Confirm actual gateway before connection."],
  ["Operations", "Order/customer-service email", "Not supplied", "Needs approval", "Notifications and customer help", "Rebekah", "Approve operational address and response details."],
  ["Operations", "Return address, cancellation, damage/loss process", "Existing Returns policy needs ecommerce clarification", "Needs approval", "Policies, emails, fulfillment", "Rebekah", "Finalize before public launch."],
  ["Policies", "Shipping, Returns, Terms, Privacy, Disclaimer", "Ecommerce updates required", "Needs approval", "Launch readiness", "Rebekah / qualified reviewer", "Approve after operational rules are settled."],
  ["Pilot", "Twenty-five representative products", "Not selected/prepared", "Pending product data", "Integration pilot", "Rebekah / Mark", "Prepare in Revel and enable only intended items."],
  ["Access", "Revel administrator/integration access", "Not available", "Pending access", "Product mapping and sync", "Rebekah", "Provide establishment-capable access."],
  ["Access", "Client-owned Kosmos account and configuration access", "Not available", "Pending access", "Integration", "Rebekah", "Create only when test window is ready."],
  ["Access", "USPS account/API and live-rate extension", "Business account exists; integration access/extension not active", "Pending access", "Live-rate testing", "Rebekah / Blue Nova", "Purchase/connect only after approval and test readiness."],
  ["Access", "Fiserv/Clover integration access", "Not available", "Pending access", "Payment testing", "Rebekah / Blue Nova", "Provide secure delegated/test access when merchant product is ready."],
  ["Environment", "Protected staging, restore point, and temporary server increase", "Deferred; no independent staging exists", "Future gate", "Implementation and realistic testing", "Blue Nova / Rebekah", "Do not create or purchase during local design phase."],
  ["Schedule", "Safe working window", "Must avoid blackout dates/weekends and preserve recovery time", "Needs approval", "Trial, sync, payment, handoff", "Todd / Rebekah", "Confirm before starting any time-limited trial or active integration."],
];
decisions.getRange("A5:G28").values = decisionRows; headers(decisions.getRange("A5:G5")); body(decisions.getRange("A6:G28"), 61); base(decisions, 5);
decisions.getRange("A:A").format.columnWidth = 16; decisions.getRange("B:B").format.columnWidth = 34; decisions.getRange("C:C").format.columnWidth = 43; decisions.getRange("D:D").format.columnWidth = 20; decisions.getRange("E:E").format.columnWidth = 31; decisions.getRange("F:F").format.columnWidth = 24; decisions.getRange("G:G").format.columnWidth = 40;
decisions.getRange("D6:D45").dataValidation = { rule: { type: "list", values: ["Confirmed", "Needs approval", "Pending decision", "Pending access", "Pending product data", "Future gate"] } }; statusRules(decisions.getRange("D6:D45"));
decisions.tables.add("A5:G28", true, "PhaseTwoDecisions").style = "TableStyleMedium2";

// 5. End-to-end implementation plan and gates.
title(implementation, "A1:F1", "Phase Two End-to-End Implementation Plan");
note(implementation, "A2:F2", "The first seven rows are the current local approval workflow. Every later row is future gated work and is documented here so nothing is improvised after design approval.");
const planRows = [
  ["Order", "Phase", "Current / Future", "Primary Owner", "Work", "Required Gate / Output"],
  [1, "Approve global commerce add-on", "Awaiting client approval", "Blue Nova / Todd", "Review literal header/footer additions against approved Phase One chrome at desktop, tablet, and phone.", "Rebekah approves system 01"],
  [2, "Approve Online Store homepage", "Current: local", "Blue Nova / Todd", "Rebuild the recovered early store-home direction using approved chrome and controlled samples.", "Approved system 02"],
  [3, "Approve catalog template", "Current: local", "Blue Nova / Todd", "Create Shop All/archive, cards, search, filters, sorting, pagination, and no-results behavior.", "Approved system 03"],
  [4, "Approve product pages", "Current: local", "Blue Nova / Todd", "Create simple and variable product examples with all required content, stock, warning, and variation states.", "Approved system 04"],
  [5, "Approve purchase path", "Current: local", "Blue Nova / Todd", "Create connected cart, checkout, validation, and confirmation prototype.", "Approved system 05"],
  [6, "Approve account system", "Current: local", "Blue Nova / Todd", "Create login, optional registration, reset, dashboard, addresses, and order states.", "Approved system 06"],
  [7, "Approve states + complete design QA", "Current: local", "Blue Nova / Todd / Rebekah", "Approve edge states and cross-system responsive/accessibility behavior; record revisions and approve full set.", "Approved system 07 + client design approval"],
  [8, "Close prerequisite gate", "Future", "Rebekah / Mark / Blue Nova", "Approve rules, prepare 25 products, provide access, approve costs, and confirm safe working window.", "No unresolved prerequisite for the next activity"],
  [9, "Prepare protected staging", "Future", "Blue Nova", "Scale only if approved, take restore point, clone/protect staging, prevent indexing/email/payment side effects, clean baseline, reconcile legacy products and retained system pages.", "Verified safe staging and rollback plan"],
  [10, "Prove one product and one action", "Future", "Blue Nova / Kosmos / Revel", "Connect systems; test eligibility, identifiers, price, inventory, category, weight, variations, content overwrite, logs, and field ownership.", "Documented one-product pass and mapping ownership"],
  [11, "Run 25-product pilot + storefront build", "Future", "Blue Nova / Rebekah / Mark", "Sync/reconcile remaining products, audit content, resolve one gap list, apply approved templates, and finalize taxonomy/merchandising.", "Approved 25-product catalog and content"],
  [12, "Configure operations and checkout", "Future", "Blue Nova / Rebekah", "Configure approved shipping, tax, accounts, inventory, emails, gateway/test mode, policies, labels/packages, and fulfillment behavior.", "Operational configuration complete"],
  [13, "Acceptance, approval, training, launch", "Future", "Blue Nova / Rebekah", "Pass QA/acceptance sheet, preserve new production content, obtain client approval, train staff, launch in controlled window, and monitor stabilization before full-catalog planning.", "All tests pass; client signoff; controlled launch and handoff"],
];
implementation.getRange("A4:F17").values = planRows; headers(implementation.getRange("A4:F4")); body(implementation.getRange("A5:F17"), 72); implementation.getRange("A5:A17").format.horizontalAlignment = "center"; base(implementation, 4);
implementation.getRange("A:A").format.columnWidth = 8; implementation.getRange("B:B").format.columnWidth = 31; implementation.getRange("C:C").format.columnWidth = 19; implementation.getRange("D:D").format.columnWidth = 27; implementation.getRange("E:E").format.columnWidth = 62; implementation.getRange("F:F").format.columnWidth = 43;
implementation.tables.add("A4:F17", true, "PhaseTwoImplementation").style = "TableStyleMedium2";

// 6. Acceptance testing and launch gates.
title(qa, "A1:G1", "Phase Two QA, Acceptance, and Launch Gates");
note(qa, "A2:G2", "The full catalog and public store remain blocked until every applicable test passes and evidence is recorded. Failed tests return to the responsible implementation phase; they are not waived by visual approval.");
label(qa, "A3", "Tests"); metric(qa, "B3", "=COUNTA(C6:C33)"); label(qa, "C3", "Passed"); metric(qa, "D3", '=COUNTIF(F6:F33,"Passed")'); label(qa, "E3", "Blocking open"); metric(qa, "F3", '=COUNTIF(F6:F33,"Not started")+COUNTIF(F6:F33,"Failed")'); label(qa, "G3", "Launch requires all applicable tests"); qa.getRange("A3:G3").format.rowHeight = 26;
const qaRows = [
  ["#", "Area", "Acceptance Test", "When", "Owner", "Status", "Evidence / Notes"],
  [1, "Integration", "Simple product creates and updates correctly.", "One-product proof", "Blue Nova", "Not started", ""],
  [2, "Integration", "Matrix/variation product creates and updates correctly when applicable.", "Pilot", "Blue Nova", "Not started", ""],
  [3, "Integration", "Active and online/third-party flags control website eligibility.", "One-product proof", "Blue Nova / Mark", "Not started", ""],
  [4, "Integration", "SKU, name, price, category, weight, and Clarkston inventory match Revel.", "One-product + pilot", "Blue Nova / Mark", "Not started", ""],
  [5, "Integration", "Images/descriptions follow documented transfer and overwrite ownership.", "One-product proof", "Blue Nova", "Not started", ""],
  [6, "Integration", "Sync schedule, logs, and errors are visible and documented.", "Pilot", "Blue Nova", "Not started", ""],
  [7, "Orders", "WooCommerce order reaches Revel correctly.", "End-to-end test", "Blue Nova", "Not started", ""],
  [8, "Orders", "Order reduces inventory at Clarkston only.", "End-to-end test", "Blue Nova / Mark", "Not started", ""],
  [9, "Inventory", "Out-of-stock, backorder, and safety-buffer behavior matches approval.", "Pilot", "Blue Nova / Rebekah", "Not started", ""],
  [10, "Shipping", "Approved regions, exclusions, PO-box rule, services, labels, packages, and representative cart/address rates are correct.", "Checkout QA", "Blue Nova", "Not started", ""],
  [11, "Tax", "Calculations match the client-supplied approved rules, including shipping treatment.", "Checkout QA", "Blue Nova / Rebekah", "Not started", ""],
  [12, "Payments", "Success, failure, authorization/capture if applicable, void, cancellation, and refund tests pass in test mode.", "Gateway QA", "Blue Nova", "Not started", ""],
  [13, "Emails", "Customer/admin order, failed-payment, refund, cancellation, and low-stock emails deliver with correct details.", "Operations QA", "Blue Nova / Rebekah", "Not started", ""],
  [14, "Accounts", "Guest/account checkout, consent, login, password reset, addresses, and order history work.", "Customer journey QA", "Blue Nova", "Not started", ""],
  [15, "Policies", "Shipping, Returns, Terms, Privacy, Disclaimer, contact, return address, cancellation, damage/loss details are approved and linked.", "Prelaunch", "Rebekah / reviewer", "Not started", ""],
  [16, "Storefront", "Store home, catalog, cards, product pages, cart, checkout, account, confirmation, search, filters, and states match approvals.", "Design QA", "Blue Nova / Todd", "Not started", ""],
  [17, "Responsive", "Desktop, tablet, and phone layouts pass without overflow, clipping, overlap, or missing controls.", "Design + staging QA", "Blue Nova", "Not started", ""],
  [18, "Accessibility", "Keyboard, focus, labels, errors, contrast, reduced motion, headings, and semantics pass review.", "Design + staging QA", "Blue Nova", "Not started", ""],
  [19, "SEO", "Titles, metadata, canonicals, schema, breadcrumbs, sitemaps, archive rules, and transactional noindex controls are correct.", "Prelaunch", "Blue Nova", "Not started", ""],
  [20, "Analytics", "Search, product view, add-to-cart, checkout, purchase, and error events work with consent controls.", "Prelaunch", "Blue Nova", "Not started", ""],
  [21, "Performance", "Uncached cart/checkout and realistic load pass without unacceptable response time or resource queueing.", "Prelaunch", "Blue Nova", "Not started", ""],
  [22, "Recovery", "Transactional backup frequency, restore procedure, rollback point, and test-state recovery are verified.", "Before pilot + launch", "Blue Nova / Rebekah", "Not started", ""],
  [23, "Content safety", "New production blogs/events/forms/users are preserved; no full-database overwrite is used for launch.", "Deployment", "Blue Nova", "Not started", ""],
  [24, "Product approval", "Rebekah approves the exact 25-product list, remaining content, claims, warnings, and customer presentation.", "Pilot signoff", "Rebekah", "Not started", ""],
  [25, "Operations", "Fulfillment, packing, labels, customer service, cancellations/refunds, and exception handling are documented.", "Prelaunch", "Rebekah / Blue Nova", "Not started", ""],
  [26, "Training", "Selected staff are trained on Revel maintenance, online eligibility, fulfillment, orders, refunds, and escalation.", "Prelaunch", "Blue Nova / Rebekah", "Not started", ""],
  [27, "Client approval", "Rebekah approves the final customer journey and authorizes controlled public launch.", "Launch gate", "Rebekah", "Not started", ""],
  [28, "Stabilization", "Monitoring, backups, integration errors, orders, inventory, performance, and support ownership are confirmed for first 30 days.", "Postlaunch", "Blue Nova / Rebekah", "Not started", ""],
];
qa.getRange("A5:G33").values = qaRows; headers(qa.getRange("A5:G5")); body(qa.getRange("A6:G33"), 58); qa.getRange("A6:A33").format.horizontalAlignment = "center"; base(qa, 5);
qa.getRange("A:A").format.columnWidth = 7; qa.getRange("B:B").format.columnWidth = 18; qa.getRange("C:C").format.columnWidth = 62; qa.getRange("D:D").format.columnWidth = 23; qa.getRange("E:E").format.columnWidth = 24; qa.getRange("F:F").format.columnWidth = 17; qa.getRange("G:G").format.columnWidth = 45;
qa.getRange("F6:F50").dataValidation = { rule: { type: "list", values: ["Not started", "In progress", "Passed", "Failed", "Not applicable"] } }; statusRules(qa.getRange("F6:F50"));
qa.tables.add("A5:G33", true, "PhaseTwoQAGates").style = "TableStyleMedium2";

// 7. Scope, recorded cost assumptions, estimates, and exclusions.
title(scope, "A1:G1", "Phase Two Scope, Cost Assumptions, Estimates, and Exclusions");
note(scope, "A2:G2", "Recorded vendor prices and time estimates are planning assumptions from the current project record, not purchase authorization. Reverify pricing and obtain client approval immediately before any subscription, extension, hosting change, or out-of-scope work.");
const scopeRows = [
  ["Type", "Item", "Current Planning Assumption", "Owner / Payer", "Trigger", "Status", "Source / Boundary"],
  ["Included scope", "Fulfillment model", "One location: Clarkston; shipping only; continental U.S.", "Rebekah / Blue Nova", "Pilot", "Confirmed", "PHASE-TWO-MASTER-CHECKLIST.md"],
  ["Included scope", "Pilot catalog", "25 representative products after one-product proof", "Rebekah / Mark / Blue Nova", "Integration", "Pending product data", "Full catalog is deferred."],
  ["Included scope", "Design approval", "Seven reusable responsive systems", "Blue Nova / Todd / Rebekah", "Current local phase", "In progress", "No WordPress or hosting work."],
  ["Software", "WooCommerce core", "$0; retained but inactive until protected staging is ready", "Existing project", "Future staging", "No purchase required", "Current project record."],
  ["Software", "Kosmos eSync Warmup", "$49 month-to-month or $39/month billed annually; advertised trial", "Rebekah", "Ready test window", "Future approval", "Reverify: https://kosmoscentral.com/esync-cloud-pricing-monthly"],
  ["Software", "Official USPS live-rate extension", "$109/year recorded assumption", "Rebekah", "Shipping configuration", "Future approval", "Reverify: https://woocommerce.com/products/usps-shipping-method/"],
  ["Software", "Likely Clover WooCommerce gateway", "$0 plugin; merchant processing contract/rates are separate", "Rebekah", "Merchant account ready", "Pending decision", "Reverify actual Fiserv/Clover product."],
  ["Exception", "Custom Kosmos mapping", "Not expected; recorded starting setup estimate $150 only if testing proves necessary", "Rebekah", "Failed standard mapping", "Not budgeted", "Requires exact quote and approval."],
  ["Infrastructure", "Temporary server increase", "Plan for 4 GB / 2 vCPU before heavy staging/sync/load work; keep through first 30 days", "Rebekah", "Prerequisites + working window", "Future approval", "Do not increase storage merely for easy CPU/RAM reversal."],
  ["Estimate", "25-product synchronization proof", "8–14 Blue Nova hours; normally 2–5 business days", "Blue Nova", "All prerequisites ready", "Planning estimate", "Does not include custom storefront design."],
  ["Estimate", "Launch-ready shipping-only operational pilot", "18–30 Blue Nova hours; about 1–2 weeks", "Blue Nova", "Full operational scope ready", "Planning estimate", "Vendor/content exceptions may extend timing."],
  ["Separate scope", "Custom storefront design and refinement", "Separate Phase Two design/build work", "Blue Nova / Rebekah", "Design approval", "Current planning", "Not included in synchronization estimate."],
  ["Excluded", "Local pickup and multi-location fulfillment", "Outside pilot", "Rebekah", "Future change request", "Excluded", "Requires separate design, inventory, and operational planning."],
  ["Excluded", "Full-catalog rollout", "Deferred until pilot metrics, errors, content gaps, and time are known", "Rebekah / Blue Nova", "Pilot passes", "Excluded from pilot", "Estimate from actual pilot results."],
  ["Excluded", "Coupons, gift certificates, store credit, loyalty, reviews, wishlist", "Excluded unless specifically approved", "Rebekah", "Change request", "Excluded", "Would change design, testing, operations, and possibly plugins."],
  ["Separate scope", "New photography, extensive editing, original copywriting, full-catalog cleanup, manual bulk entry", "Quote separately if requested", "Rebekah", "Audit identifies need", "Not included", "Pilot includes focused audit and one consolidated gap list."],
  ["Separate scope", "Postlaunch integration monitoring and ongoing ecommerce support", "Define after included stabilization period", "Rebekah / Blue Nova", "Before handoff", "Future approval", "Document support owner and response path."],
];
scope.getRange("A4:G21").values = scopeRows; headers(scope.getRange("A4:G4")); body(scope.getRange("A5:G21"), 62); base(scope, 4);
scope.getRange("A:A").format.columnWidth = 18; scope.getRange("B:B").format.columnWidth = 34; scope.getRange("C:C").format.columnWidth = 48; scope.getRange("D:D").format.columnWidth = 25; scope.getRange("E:E").format.columnWidth = 28; scope.getRange("F:F").format.columnWidth = 20; scope.getRange("G:G").format.columnWidth = 45;
scope.tables.add("A4:G21", true, "PhaseTwoScopeCosts").style = "TableStyleMedium2"; statusRules(scope.getRange("F5:F35"));

const renderTargets = [
  ["Approval Set", "A1:J12"], ["Templates & Global", "A1:E19"], ["Product & Content", "A1:H24"],
  ["Decisions & Access", "A1:G28"], ["Implementation Plan", "A1:F17"], ["QA & Launch Gates", "A1:G33"], ["Scope & Costs", "A1:G21"],
];
for (const [sheetName, range] of renderTargets) {
  const check = await workbook.inspect({ kind: "table", range: `${sheetName}!${range}`, include: "values,formulas", tableMaxRows: 35, tableMaxCols: 12, maxChars: 14000 });
  console.log(`CHECK ${sheetName}`); console.log(check.ndjson);
  const preview = await workbook.render({ sheetName, autoCrop: "all", scale: 1.1, format: "png" });
  const safe = sheetName.replace(/[^a-z0-9]+/gi, "-").replace(/^-|-$/g, "").toLowerCase();
  await fs.writeFile(path.join(previewDir, `${safe}.png`), new Uint8Array(await preview.arrayBuffer()));
}
const errors = await workbook.inspect({ kind: "match", searchTerm: "#REF!|#DIV/0!|#VALUE!|#NAME\\?|#N/A", options: { useRegex: true, maxResults: 300 }, summary: "final formula error scan" });
const output = await SpreadsheetFile.exportXlsx(workbook); await output.save(outputPath);
console.log("FORMULA_ERRORS"); console.log(errors.ndjson); console.log(`OUTPUT ${outputPath}`);
