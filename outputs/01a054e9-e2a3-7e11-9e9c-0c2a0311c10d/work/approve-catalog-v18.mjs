import fs from "node:fs/promises";
import path from "node:path";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const projectDir = "C:/Users/todda/Blue Nova Projects/Rebekahs Health Website";
const outputDir = path.join(projectDir, "outputs/01a054e9-e2a3-7e11-9e9c-0c2a0311c10d");
const workbookPath = path.join(outputDir, "Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx");
const previewDir = path.join(outputDir, "work/catalog-v18-approved");
const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(workbookPath));

const approval = workbook.worksheets.getItem("Approval Set");
approval.getRange("D3").values = [[3]];
approval.getRange("E8").values = [["Internally Approved"]];
approval.getRange("J8").values = [[
  "Exact Shop Homepage + Product Catalog v1.8 was approved by Todd on August 31, 2026 and is ready for Rebekah's separate client review. This visual approval includes the page structure, wellness paths, personal messaging, benefits/help row, product cards, search, sorting, filters, load-more, no-results recovery, and sitewide missing-photo component. Revel/Kosmos data, synchronization, inventory, pricing, categories, real photography, and final product content remain separate implementation-validation gates.",
]];

const versions = workbook.worksheets.getItem("Version Register");
versions.getRange("F3").values = [[3]];
versions.getRange("E8:I8").values = [[
  "Awaiting Client Approval",
  "Approved by Todd",
  "Pending Rebekah",
  "v1.9",
  "Exact v1.8 internally approved August 31, 2026. Approval covers the complete catalog design and interactions shown in the mockup. Revel/Kosmos product data, sync, inventory, pricing, category mapping, real photography, and final content remain separate implementation-validation gates. Any later design change requires v1.9 and a new approval.",
]];

console.log((await workbook.inspect({
  kind: "table",
  sheetId: "Approval Set",
  range: "A3:J8",
  include: "values,formulas",
  tableMaxRows: 6,
  tableMaxCols: 10,
  maxChars: 16000,
})).ndjson);
console.log((await workbook.inspect({
  kind: "table",
  sheetId: "Version Register",
  range: "A3:I8",
  include: "values,formulas",
  tableMaxRows: 6,
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
