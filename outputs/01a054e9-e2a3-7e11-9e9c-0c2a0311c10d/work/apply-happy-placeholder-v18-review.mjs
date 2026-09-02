import fs from "node:fs/promises";
import path from "node:path";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const projectDir = "C:/Users/todda/Blue Nova Projects/Rebekahs Health Website";
const outputDir = path.join(projectDir, "outputs/01a054e9-e2a3-7e11-9e9c-0c2a0311c10d");
const workbookPath = path.join(outputDir, "Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx");
const previewDir = path.join(outputDir, "work/happy-placeholder-v18-review");
const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(workbookPath));

const approval = workbook.worksheets.getItem("Approval Set");
approval.getRange("C8").values = [[
  "Store introduction, four approved illustrated wellness-goal paths, personal shopping guidance, catalog search, sort, filters, six sample products, pagination, no-results recovery, and the brighter favicon-inspired missing-image fallback now under review",
]];
approval.getRange("J8").values = [[
  "Review exact v1.8 page-body-only concept: shop-catalog-template-v1.8.html. It replaces the sad downward leaves with Rebekah's upright green-and-honey brand mark, a warm honey/sage glow, and 'Product photo coming soon.' Full catalog approval still waits for remaining review and Revel/Kosmos validation.",
]];
approval.getRange("E9").values = [["Revision Needed"]];
approval.getRange("J9").values = [[
  "Product-page v1.5 remains preserved but is not approvable because its first botanical missing-image treatment was rejected. Create v1.6 with the catalog v1.8 treatment only after Todd approves that new component.",
]];
approval.getRange("E10").values = [["Revision Needed"]];
approval.getRange("J10").values = [[
  "Purchase Path v1.5 remains preserved but is not approvable because its first botanical missing-image treatment was rejected. Create v1.6 with the catalog v1.8 treatment only after Todd approves that new component.",
]];
approval.getRange("E12").values = [["Revision Needed"]];
approval.getRange("J12").values = [[
  "Store States v1.4 remains preserved but is not approvable because its first botanical missing-image treatment was rejected. Create v1.5 with the catalog v1.8 treatment only after Todd approves that new component.",
]];

const versions = workbook.worksheets.getItem("Version Register");
versions.getRange("C8:I8").values = [[
  "v1.8",
  "shop-catalog-template-v1.8.html",
  "Placeholder Review + Revel Validation Pending",
  "Wellness paths approved; brighter placeholder awaiting Todd review",
  "Not reviewed",
  "v1.9",
  "Replaced the sad downward botanical outline with Rebekah's upright green-and-honey standalone brand mark, a warm honey/sage glow, and 'Product photo coming soon.' This catalog-first test prevents another unnecessary cross-template revision cycle.",
]];
versions.getRange("E9:I9").values = [[
  "Revision Needed",
  "First botanical placeholder rejected; revision pending",
  "Not reviewed",
  "v1.6",
  "Preserve v1.5 as history. After Todd approves the brighter catalog v1.8 component, apply it to both galleries and all related-product cards in v1.6 without changing other product-page content.",
]];
versions.getRange("E10:I10").values = [[
  "Revision Needed",
  "First botanical placeholder rejected; revision pending",
  "Not reviewed",
  "v1.6",
  "Preserve v1.5 as history. After Todd approves the brighter catalog v1.8 component, apply it to both cart items in v1.6 without changing the purchase flow.",
]];
versions.getRange("E12:I12").values = [[
  "Revision Needed",
  "First botanical placeholder rejected; revision pending",
  "Not reviewed",
  "v1.5",
  "Preserve v1.4 as history. After Todd approves the brighter catalog v1.8 component, apply it to the missing-image state in v1.5 without changing the other nine states or accessibility behavior.",
]];

const productContent = workbook.worksheets.getItem("Product & Content");
productContent.getRange("H15").values = [[
  "The export contains no product-image fields. The first neutral botanical fallback was rejected as too sad. Catalog v1.8 now tests a brighter favicon-inspired 'Product photo coming soon' treatment before it is applied across other templates; real photography still requires source confirmation and acceptance.",
]];

console.log((await workbook.inspect({
  kind: "table",
  sheetId: "Version Register",
  range: "A5:I12",
  include: "values,formulas",
  tableMaxRows: 8,
  tableMaxCols: 9,
  maxChars: 12000,
})).ndjson);

await fs.mkdir(previewDir, { recursive: true });
for (const sheet of workbook.worksheets.items) {
  const preview = await workbook.render({ sheetName: sheet.name, autoCrop: "all", scale: 0.85, format: "png" });
  const safe = sheet.name.toLowerCase().replace(/[^a-z0-9]+/g, "-").replace(/^-|-$/g, "");
  await fs.writeFile(path.join(previewDir, `${safe}.png`), new Uint8Array(await preview.arrayBuffer()));
}

const errors = await workbook.inspect({
  kind: "match",
  searchTerm: "#REF!|#DIV/0!|#VALUE!|#NAME\\?|#N/A",
  options: { useRegex: true, maxResults: 300 },
  summary: "final formula error scan",
});
console.log(errors.ndjson);

const output = await SpreadsheetFile.exportXlsx(workbook);
await output.save(workbookPath);
console.log(`OUTPUT ${workbookPath}`);
