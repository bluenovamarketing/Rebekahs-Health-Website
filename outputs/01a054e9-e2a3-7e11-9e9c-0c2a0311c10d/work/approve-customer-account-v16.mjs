import fs from "node:fs/promises";
import path from "node:path";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const projectDir = "C:/Users/todda/Blue Nova Projects/Rebekahs Health Website";
const outputDir = path.join(projectDir, "outputs/01a054e9-e2a3-7e11-9e9c-0c2a0311c10d");
const workbookPath = path.join(outputDir, "Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx");
const previewDir = path.join(outputDir, "work/customer-account-v16-approved");
const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(workbookPath));

const approval = workbook.worksheets.getItem("Approval Set");
approval.getRange("D3").values = [[6]];
approval.getRange("E11").values = [["Internally Approved"]];
approval.getRange("J11").values = [[
  "Exact Customer Account System v1.6 was approved by Todd on September 1, 2026 and is ready for Rebekah's separate client review. Approval covers guest checkout, automatic optional account creation, standard email/password sign-in and recovery, dashboard, addresses, order history, order detail, representative interactions, and no customer 2FA. Staff/administrator backend security remains a separate staging task, and real email delivery, privacy/consent wording, order synchronization, and retention behavior remain implementation-validation gates.",
]];

const versions = workbook.worksheets.getItem("Version Register");
versions.getRange("F3").values = [[6]];
versions.getRange("E11:I11").values = [[
  "Awaiting Client Approval",
  "Approved by Todd",
  "Pending Rebekah",
  "v1.7",
  "Exact v1.6 internally approved September 1, 2026. Approval covers guest checkout, automatic optional customer accounts, standard sign-in and recovery, dashboard, addresses, orders, order detail, representative interactions, and no customer 2FA. Staff/administrator backend security and all connected-service behavior remain separate staging and implementation-validation gates. Any later design change requires v1.7 and a new approval.",
]];

console.log((await workbook.inspect({
  kind: "table",
  sheetId: "Approval Set",
  range: "A3:J12",
  include: "values,formulas",
  tableMaxRows: 10,
  tableMaxCols: 10,
  maxChars: 22000,
})).ndjson);
console.log((await workbook.inspect({
  kind: "table",
  sheetId: "Version Register",
  range: "A3:I12",
  include: "values,formulas",
  tableMaxRows: 10,
  tableMaxCols: 9,
  maxChars: 20000,
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
