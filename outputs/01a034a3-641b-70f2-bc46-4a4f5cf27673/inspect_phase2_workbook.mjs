import fs from "node:fs/promises";
import path from "node:path";
import { fileURLToPath } from "node:url";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const workDir = path.dirname(fileURLToPath(import.meta.url));
const workbookPath = process.argv[2] || path.join(workDir, "Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx");
const input = await FileBlob.load(workbookPath);
const workbook = await SpreadsheetFile.importXlsx(input);

const overview = await workbook.inspect({
  kind: "sheet",
  include: "id,name",
  maxChars: 6000,
});
const matches = await workbook.inspect({
  kind: "match",
  searchTerm: "v1.4|Header \\+ Footer|online-store-header-footer-add-on",
  options: { useRegex: true, maxResults: 100 },
  maxChars: 12000,
});
const approvalTable = await workbook.inspect({
  kind: "table",
  range: "'Approval Set'!A1:J12",
  include: "values,formulas",
  tableMaxRows: 20,
  tableMaxCols: 12,
  maxChars: 24000,
});
const registerTable = await workbook.inspect({
  kind: "table",
  range: "'Version Register'!A1:I18",
  include: "values,formulas",
  tableMaxRows: 24,
  tableMaxCols: 12,
  maxChars: 24000,
});

console.log("SHEETS");
console.log(overview.ndjson);
console.log("MATCHES");
console.log(matches.ndjson);
console.log("APPROVAL TABLE");
console.log(approvalTable.ndjson);
console.log("VERSION TABLE");
console.log(registerTable.ndjson);

for (const sheet of workbook.worksheets.items) {
  const preview = await workbook.render({
    sheetName: sheet.name,
    autoCrop: "all",
    scale: 0.8,
    format: "png",
  });
  const safeName = sheet.name.replace(/[^A-Za-z0-9_-]+/g, "-");
  await fs.writeFile(path.join(workDir, `before-${safeName}.png`), new Uint8Array(await preview.arrayBuffer()));
}
