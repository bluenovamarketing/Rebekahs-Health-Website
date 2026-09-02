import fs from "node:fs/promises";
import path from "node:path";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const projectDir = "C:/Users/todda/Blue Nova Projects/Rebekahs Health Website";
const outputDir = path.join(projectDir, "outputs/01a054e9-e2a3-7e11-9e9c-0c2a0311c10d");
const workbookPath = path.join(outputDir, "Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx");
const previewDir = path.join(outputDir, "work/homepage-v17-approved");
const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(workbookPath));

const approval = workbook.worksheets.getItem("Approval Set");
approval.getRange("E7").values = [["Internally Approved"]];
approval.getRange("J7").values = [[
  "Main Homepage Ecommerce Integration v1.7 was approved by Todd on August 31, 2026 and is ready for Rebekah's separate client review. Real product data and connected commerce remain future validation gates.",
]];

const versions = workbook.worksheets.getItem("Version Register");
versions.getRange("E7:I7").values = [[
  "Awaiting Client Approval",
  "Approved by Todd",
  "Pending Rebekah",
  "v1.8",
  "Exact v1.7 internally approved August 31, 2026. Preserves the existing homepage, broader-store language, three proportional illustrated shopping paths, four representative products, and review-only addition labels. Awaiting Rebekah's client approval; real product and connected-service validation remains separate.",
]];

console.log((await workbook.inspect({
  kind: "table",
  sheetId: "Approval Set",
  range: "A3:J8",
  include: "values,formulas",
  tableMaxRows: 8,
  tableMaxCols: 10,
  maxChars: 10000,
})).ndjson);
console.log((await workbook.inspect({
  kind: "table",
  sheetId: "Version Register",
  range: "A3:I8",
  include: "values,formulas",
  tableMaxRows: 8,
  tableMaxCols: 9,
  maxChars: 10000,
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
