import fs from "node:fs/promises";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const outputDir = "outputs/01a062b4-9ceb-7123-8739-1154f7acbf63";
const workbookPath = `${outputDir}/Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx`;

const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(workbookPath));

const beforePreview = await workbook.render({
  sheetName: "Approval Set",
  range: "A1:J12",
  scale: 1.25,
  format: "png",
});
await fs.writeFile(`${outputDir}/work/header-footer-v16-before.png`, new Uint8Array(await beforePreview.arrayBuffer()));

const approvalSheet = workbook.worksheets.getItem("Approval Set");
const approvalValues = approvalSheet.getRange("A1:J30").values;
const approvalRowIndex = approvalValues.findIndex((row) => String(row[1] ?? "").trim() === "Header + Footer Ecommerce Add-On");
if (approvalRowIndex < 0) throw new Error("Header + Footer Ecommerce Add-On was not found on Approval Set.");

const versionSheet = workbook.worksheets.getItem("Version Register");
const versionValues = versionSheet.getRange("A1:I30").values;
const versionRowIndex = versionValues.findIndex((row) => String(row[1] ?? "").trim() === "Header + Footer Ecommerce Add-On");
if (versionRowIndex < 0) throw new Error("Header + Footer Ecommerce Add-On was not found on Version Register.");

approvalSheet.getRange("C3:F3").values = [["Internally approved", 6, "Awaiting internal", 1]];
approvalSheet.getRange("I3:J3").values = [["Current gate", "Todd reviews Header + Footer v1.6"]];
approvalSheet.getCell(approvalRowIndex, 4).values = [["Revision Needed"]];
approvalSheet.getCell(approvalRowIndex, 9).values = [[
  "v1.6 moves Shop Fullscript out of the In-Store Products submenu and places it directly on the main navigation bar. Awaiting Todd review; Rebekah's prior v1.5 approval does not transfer to this revision.",
]];

versionSheet.getRangeByIndexes(versionRowIndex, 2, 1, 7).values = [[
  "v1.6",
  "online-store-header-footer-add-on-v1.6.html",
  "Revision Needed",
  "Awaiting Todd",
  "Pending Rebekah",
  "v1.7",
  "Shop Fullscript is now a direct main-menu item rather than a child of In-Store Products. The approved responsive header, ecommerce utility row, and footer are preserved.",
]];

workbook.recalculate();

const approvalCheck = await workbook.inspect({
  kind: "table",
  range: `Approval Set!A${approvalRowIndex + 1}:J${approvalRowIndex + 1}`,
  include: "values,formulas",
  tableMaxRows: 3,
  tableMaxCols: 12,
});
console.log(approvalCheck.ndjson);

const versionCheck = await workbook.inspect({
  kind: "table",
  range: `Version Register!A${versionRowIndex + 1}:I${versionRowIndex + 1}`,
  include: "values,formulas",
  tableMaxRows: 3,
  tableMaxCols: 12,
});
console.log(versionCheck.ndjson);

const errors = await workbook.inspect({
  kind: "match",
  searchTerm: "#REF!|#DIV/0!|#VALUE!|#NAME\\?|#N/A|#NUM!|#NULL!|#SPILL!|#CALC!",
  options: { useRegex: true, maxResults: 300 },
  summary: "final formula error scan",
});
console.log(errors.ndjson);

const afterPreview = await workbook.render({
  sheetName: "Approval Set",
  range: "A1:J12",
  scale: 1.25,
  format: "png",
});
await fs.writeFile(`${outputDir}/work/header-footer-v16-after.png`, new Uint8Array(await afterPreview.arrayBuffer()));

const output = await SpreadsheetFile.exportXlsx(workbook);
await output.save(workbookPath);
