import fs from "node:fs/promises";
import path from "node:path";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const projectDir = "C:/Users/todda/Blue Nova Projects/Rebekahs Health Website";
const outputDir = path.join(projectDir, "outputs/01a054e9-e2a3-7e11-9e9c-0c2a0311c10d");
const workbookPath = path.join(outputDir, "Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx");
const previewDir = path.join(outputDir, "work/sitewide-placeholder-approved");
const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(workbookPath));

const approval = workbook.worksheets.getItem("Approval Set");
approval.getRange("C8").values = [[
  "Store introduction, four approved illustrated wellness-goal paths, personal shopping guidance, catalog search, sort, filters, six sample products, pagination, no-results recovery, and the approved sitewide missing-product-photo component",
]];
approval.getRange("J8").values = [[
  "The exact v1.8 placeholder component is approved as the sitewide rule: Rebekah's upright green-and-honey brand mark, a warm honey/sage glow, and 'Product photo coming soon.' Full catalog approval still waits for the remaining screen review and Revel/Kosmos validation.",
]];

approval.getRange("C9").values = [[
  "Simple and variation product examples with three-view galleries, the approved sitewide missing-product-photo component, quantity and variation controls, facts, cautions, team help, and related products",
]];
approval.getRange("E9").values = [["In review"]];
approval.getRange("H9").values = [[
  "Approve the shared structure and interactions now. Every true product-photo slot without approved photography uses the approved sitewide placeholder and is replaced when approved photography arrives. Product media and connected catalog content still wait for Revel/Kosmos.",
]];
approval.getRange("J9").values = [[
  "Review exact v1.6 page-body-only mockup: product-page-templates-v1.6.html. The approved placeholder now appears in both galleries and all related-product cards; the rest of the product-page structure and interactions are preserved.",
]];

approval.getRange("C10").values = [[
  "Connected cart with the approved sitewide missing-product-photo component, empty-cart recovery, checkout fields, validation, order review, and customer-facing confirmation",
]];
approval.getRange("E10").values = [["In review"]];
approval.getRange("J10").values = [[
  "Review exact v1.6 page-body-only mockup: purchase-path-mockup-v1.6.html. The approved placeholder now appears on both cart items; the cart, checkout, validation, order review, and confirmation flow are otherwise unchanged.",
]];

approval.getRange("C12").values = [[
  "Ten non-ideal states including the approved sitewide missing-product-photo component and its usage rule, plus the mobile filter dialog and responsive/accessibility checklist",
]];
approval.getRange("E12").values = [["In review"]];
approval.getRange("H12").values = [[
  "Approve recovery hierarchy and accessibility behavior now. The missing-product-photo component is an approved site rule: use it only in real product-photo slots lacking approved photography, then replace it automatically when photography arrives.",
]];
approval.getRange("J12").values = [[
  "Review exact v1.5 page-body-only mockup: store-states-components-v1.5.html. It is the dedicated visual approval record for the sitewide placeholder rule and also preserves the other nine states, mobile filter behavior, and accessibility guidance.",
]];

const templates = workbook.worksheets.getItem("Templates & Global");
templates.getRange("A20:E20").copyFrom(templates.getRange("A18:E18"), "all");
templates.getRange("A20:E20").values = [[
  "Component",
  "Missing product photo placeholder",
  "Catalog cards, product galleries, related products, cart items, and store-state examples",
  "Approved site rule; client sees exact component in Store States v1.5",
  "Use Rebekah's upright green-and-honey brand mark, warm honey/sage treatment, and 'Product photo coming soon.' Replace it automatically when approved photography arrives. Do not use it for loading, broken-image errors, decorative imagery, or content never intended to have a photo. Build one reusable WordPress component rather than redesigning it page by page.",
]];
templates.getRange("A20:E20").format = {
  fill: "#FFFFFF",
  font: { color: "#111827", size: 10, name: "Carlito" },
  borders: {
    top: { style: "thin", color: "#6DC5EA" },
    bottom: { style: "thin", color: "#6DC5EA" },
  },
  wrapText: true,
  verticalAlignment: "top",
  rowHeight: 108,
};

const productContent = workbook.worksheets.getItem("Product & Content");
productContent.getRange("H15").values = [[
  "The export contains no product-image fields. Todd approved the catalog v1.8 treatment as the sitewide rule: the upright Rebekah green-and-honey mark, warm honey/sage treatment, and 'Product photo coming soon.' Use it only for genuine product-photo slots without approved photography; replace it automatically when approved photography arrives. Real photography still requires source confirmation and acceptance.",
]];

const versions = workbook.worksheets.getItem("Version Register");
versions.getRange("C8:I8").values = [[
  "v1.8",
  "shop-catalog-template-v1.8.html",
  "Revel Validation Pending",
  "Wellness paths and sitewide placeholder approved; full system pending",
  "Not reviewed",
  "v1.9",
  "Todd approved the upright green-and-honey mark, warm honey/sage treatment, and 'Product photo coming soon' as the exact sitewide missing-product-photo component. Full catalog review and Revel/Kosmos validation remain separate gates.",
]];
versions.getRange("C9:I9").values = [[
  "v1.6",
  "product-page-templates-v1.6.html",
  "Internal Review",
  "Placeholder component approved; full system pending",
  "Not reviewed",
  "v1.7",
  "Applied the approved sitewide missing-product-photo component to both product galleries and all related-product cards while preserving v1.5 and leaving the remaining page structure and interactions unchanged.",
]];
versions.getRange("C10:I10").values = [[
  "v1.6",
  "purchase-path-mockup-v1.6.html",
  "Internal Review",
  "Placeholder component approved; full system pending",
  "Not reviewed",
  "v1.7",
  "Applied the approved sitewide missing-product-photo component to both cart items while preserving v1.5 and leaving the cart, checkout, validation, review, and confirmation flow unchanged.",
]];
versions.getRange("C12:I12").values = [[
  "v1.5",
  "store-states-components-v1.5.html",
  "Internal Review",
  "Sitewide placeholder rule approved; full system pending",
  "Not reviewed",
  "v1.6",
  "Created the dedicated visual approval record for the approved missing-product-photo rule, including where it is and is not used, while preserving v1.4 and the other nine states plus mobile/accessibility behavior.",
]];

console.log((await workbook.inspect({
  kind: "table",
  sheetId: "Approval Set",
  range: "A5:J12",
  include: "values,formulas",
  tableMaxRows: 8,
  tableMaxCols: 10,
  maxChars: 18000,
})).ndjson);
console.log((await workbook.inspect({
  kind: "table",
  sheetId: "Templates & Global",
  range: "A17:E20",
  include: "values,formulas",
  tableMaxRows: 4,
  tableMaxCols: 5,
  maxChars: 10000,
})).ndjson);
console.log((await workbook.inspect({
  kind: "table",
  sheetId: "Product & Content",
  range: "A15:H15",
  include: "values,formulas",
  tableMaxRows: 1,
  tableMaxCols: 8,
  maxChars: 6000,
})).ndjson);
console.log((await workbook.inspect({
  kind: "table",
  sheetId: "Version Register",
  range: "A5:I12",
  include: "values,formulas",
  tableMaxRows: 8,
  tableMaxCols: 9,
  maxChars: 18000,
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
