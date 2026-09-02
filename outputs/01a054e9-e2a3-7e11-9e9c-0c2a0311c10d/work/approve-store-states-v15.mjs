import fs from "node:fs/promises";
import path from "node:path";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const projectDir = "C:/Users/todda/Blue Nova Projects/Rebekahs Health Website";
const outputDir = path.join(projectDir, "outputs/01a054e9-e2a3-7e11-9e9c-0c2a0311c10d");
const workbookPath = path.join(outputDir, "Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx");
const previewDir = path.join(outputDir, "work/store-states-v15-approved");
const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(workbookPath));

const approval = workbook.worksheets.getItem("Approval Set");
approval.getRange("D3").values = [[7]];
approval.getRange("J3").values = [["Client approval + Revel/Kosmos + payment gateway"]];
approval.getRange("E12").values = [["Internally Approved"]];
approval.getRange("J12").values = [[
  "Exact Store States + Components v1.5 was approved by Todd on September 1, 2026 and is ready for Rebekah's separate client review. Approval covers all ten states, recovery actions, the approved sitewide missing-product-photo rule, loading treatment, mobile filter drawer, and responsive/accessibility checklist. Exact inventory, shipping, tax, payment-provider, authentication, and integration-error messages remain connected-service implementation-validation gates.",
]];

const templates = workbook.worksheets.getItem("Templates & Global");
templates.getRange("D12").values = [["Internally approved; client approval pending"]];

const implementation = workbook.worksheets.getItem("Implementation Plan");
implementation.getRange("F11").values = [["Internal system 07 approved; Rebekah client design approval pending"]];

const scope = workbook.worksheets.getItem("Scope & Costs");
scope.getRange("C7").values = [["Seven reusable responsive systems internally approved; Rebekah client approval pending"]];
scope.getRange("F7").values = [["Awaiting client approval"]];

const versions = workbook.worksheets.getItem("Version Register");
versions.getRange("F3").values = [[7]];
versions.getRange("I3").values = [["Gates: Client approval + Revel/Kosmos + payment"]];
versions.getRange("E12:I12").values = [[
  "Awaiting Client Approval",
  "Approved by Todd",
  "Pending Rebekah",
  "v1.6",
  "Exact v1.5 internally approved September 1, 2026. Approval covers no-results, empty-filter, out-of-stock, unavailable-variation, approved missing-photo, loading, empty-cart, checkout-validation, payment-failure, mobile-filter, and responsive/accessibility states. Connected-service wording remains an implementation-validation gate. Any later design change requires v1.6 and a new approval.",
]];

console.log((await workbook.inspect({ kind: "table", sheetId: "Approval Set", range: "A3:J12", include: "values,formulas", tableMaxRows: 10, tableMaxCols: 10, maxChars: 24000 })).ndjson);
console.log((await workbook.inspect({ kind: "table", sheetId: "Version Register", range: "A3:I12", include: "values,formulas", tableMaxRows: 10, tableMaxCols: 9, maxChars: 22000 })).ndjson);

const errors = await workbook.inspect({ kind: "match", searchTerm: "#REF!|#DIV/0!|#VALUE!|#NAME\\?|#N/A", options: { useRegex: true, maxResults: 300 }, summary: "final formula error scan" });
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
