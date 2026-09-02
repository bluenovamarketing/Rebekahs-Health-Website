import fs from "node:fs/promises";
import path from "node:path";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const projectDir = "C:/Users/todda/Blue Nova Projects/Rebekahs Health Website";
const outputDir = path.join(projectDir, "outputs/01a054e9-e2a3-7e11-9e9c-0c2a0311c10d");
const workbookPath = path.join(outputDir, "Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx");
const previewDir = path.join(outputDir, "work/product-pages-v17");
const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(workbookPath));

const approval = workbook.worksheets.getItem("Approval Set");
approval.getRange("C9").values = [[
  "Simple and variation product examples with three-view galleries, the approved sitewide missing-product-photo component, quantity and variation controls, compact product details, optional source-verified Directions & Warnings, phone help beside Add to Cart, and related products",
]];
approval.getRange("H9").values = [[
  "Approve the shared structure and interactions now. Directions & Warnings is collapsed and appears only when verified packaging, manufacturer/supplier materials, or approved catalog data exists; otherwise omit it. Shipping and returns stay in the purchase path and policy pages. Product media and connected catalog content still wait for Revel/Kosmos.",
]];
approval.getRange("I9").values = [[
  "Product schema, unique copy, gallery semantics and alt text, compact product details, source-verified directions/warnings when applicable, stock language, variation errors, related items, and accessible support information.",
]];
approval.getRange("J9").values = [[
  "Review exact v1.7 page-body-only mockup: product-page-templates-v1.7.html. It removes the repeated Shipping & Help card, keeps phone guidance near Add to Cart, and applies the optional source rule to Directions & Warnings on both product examples. Any further design change requires v1.8.",
]];
approval.getRange("A9:J9").format.autofitRows();

const templates = workbook.worksheets.getItem("Templates & Global");
templates.getRange("E8").values = [[
  "Gallery, compact product details, price/stock placeholders, quantity, add to cart, phone guidance, related products, and a collapsed Directions & Warnings disclosure only when verified source content exists. Shipping and returns remain in the purchase path and policy pages.",
]];
templates.getRange("E9").values = [[
  "Reuse the simple layout and the same optional content rules with option selection, availability, price changes, unavailable combinations, and errors.",
]];
templates.getRange("A8:E9").format.autofitRows();

const products = workbook.worksheets.getItem("Product & Content");
products.getRange("C18:H18").values = [[
  "WordPress from approved label, manufacturer, or supplier source",
  "Product detail when applicable",
  "Blue Nova / Rebekah",
  "Pending source validation",
  "Publish only when verified source information is available; otherwise omit the section.",
  "No invented guidance. Display as a collapsed Directions & Warnings disclosure when applicable.",
]];
products.getRange("C20:H20").values = [[
  "WordPress / Revel flag / approved source",
  "Product and checkout when applicable",
  "Rebekah / Blue Nova",
  "Pending source validation",
  "Verified product-specific warnings and restrictions are visible where they apply and enforced when needed.",
  "Do not show a generic warning block on every product; omit it when no verified product-specific content exists.",
]];
products.getRange("A18:H20").format.autofitRows();

const versions = workbook.worksheets.getItem("Version Register");
versions.getRange("C9:I9").values = [[
  "v1.7",
  "product-page-templates-v1.7.html",
  "Internal Review",
  "Revision requested by Todd; full system pending",
  "Not reviewed",
  "v1.8",
  "Removed the repeated Shipping & Help card while keeping phone guidance beside Add to Cart. Both product examples now use compact Product Details and a collapsed Directions & Warnings disclosure that appears only when verified packaging, manufacturer/supplier materials, or approved catalog data exists; otherwise it is omitted. v1.6 remains preserved as review history.",
]];
versions.getRange("A9:I9").format.autofitRows();

console.log((await workbook.inspect({
  kind: "table",
  sheetId: "Approval Set",
  range: "A5:J9",
  include: "values,formulas",
  tableMaxRows: 5,
  tableMaxCols: 10,
  maxChars: 16000,
})).ndjson);
console.log((await workbook.inspect({
  kind: "table",
  sheetId: "Product & Content",
  range: "A15:H20",
  include: "values,formulas",
  tableMaxRows: 6,
  tableMaxCols: 8,
  maxChars: 14000,
})).ndjson);
console.log((await workbook.inspect({
  kind: "table",
  sheetId: "Version Register",
  range: "A5:I9",
  include: "values,formulas",
  tableMaxRows: 5,
  tableMaxCols: 9,
  maxChars: 14000,
})).ndjson);

const errors = await workbook.inspect({
  kind: "match",
  searchTerm: "#REF!|#DIV/0!|#VALUE!|#NAME\\?|#N/A",
  options: { useRegex: true, maxResults: 300 },
  summary: "final formula error scan",
});
console.log(errors.ndjson);

await fs.mkdir(previewDir, { recursive: true });
for (const sheet of workbook.worksheets.items) {
  const preview = await workbook.render({ sheetName: sheet.name, autoCrop: "all", scale: 0.85, format: "png" });
  const safe = sheet.name.toLowerCase().replace(/[^a-z0-9]+/g, "-").replace(/^-|-$/g, "");
  await fs.writeFile(path.join(previewDir, `${safe}.png`), new Uint8Array(await preview.arrayBuffer()));
}

const output = await SpreadsheetFile.exportXlsx(workbook);
await output.save(workbookPath);
console.log(`OUTPUT ${workbookPath}`);
