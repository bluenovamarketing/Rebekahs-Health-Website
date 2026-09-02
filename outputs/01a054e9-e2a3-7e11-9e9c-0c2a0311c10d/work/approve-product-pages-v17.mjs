import fs from "node:fs/promises";
import path from "node:path";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const projectDir = "C:/Users/todda/Blue Nova Projects/Rebekahs Health Website";
const outputDir = path.join(projectDir, "outputs/01a054e9-e2a3-7e11-9e9c-0c2a0311c10d");
const workbookPath = path.join(outputDir, "Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx");
const previewDir = path.join(outputDir, "work/product-pages-v17-approved");
const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(workbookPath));

const approval = workbook.worksheets.getItem("Approval Set");
approval.getRange("D3").values = [[4]];
approval.getRange("E9").values = [["Internally Approved"]];
approval.getRange("J9").values = [[
  "Exact Product Page Templates v1.7 was approved by Todd on August 31, 2026 and is ready for Rebekah's separate client review. Approval covers both product examples, galleries, the approved missing-photo treatment, quantity and variation controls, compact details, optional source-verified Directions & Warnings, phone guidance near Add to Cart, and related products. Revel/Kosmos data and verified manufacturer, supplier, label, or approved-catalog content remain separate implementation-validation gates.",
]];

const versions = workbook.worksheets.getItem("Version Register");
versions.getRange("F3").values = [[4]];
versions.getRange("E9:I9").values = [[
  "Awaiting Client Approval",
  "Approved by Todd",
  "Pending Rebekah",
  "v1.8",
  "Exact v1.7 internally approved August 31, 2026. Approval covers the complete product-page design and interactions shown in the mockup. Revel/Kosmos product data, real photography, and verified manufacturer, supplier, label, or approved-catalog Directions & Warnings remain separate implementation-validation gates. Any later design change requires v1.8 and a new approval.",
]];

console.log((await workbook.inspect({
  kind: "table",
  sheetId: "Approval Set",
  range: "A3:J9",
  include: "values,formulas",
  tableMaxRows: 7,
  tableMaxCols: 10,
  maxChars: 18000,
})).ndjson);
console.log((await workbook.inspect({
  kind: "table",
  sheetId: "Version Register",
  range: "A3:I9",
  include: "values,formulas",
  tableMaxRows: 7,
  tableMaxCols: 9,
  maxChars: 16000,
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
