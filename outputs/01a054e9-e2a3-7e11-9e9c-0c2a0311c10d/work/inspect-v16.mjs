import fs from "node:fs/promises";
import { fileURLToPath } from "node:url";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const workbookPath = fileURLToPath(new URL("../Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx", import.meta.url));
const previewDir = new URL("./before-v16/", import.meta.url);
await fs.mkdir(previewDir, { recursive: true });
const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(workbookPath));

const sheets = await workbook.inspect({ kind: "sheet", include: "id,name", maxChars: 10000 });
console.log(sheets.ndjson);
const matches = await workbook.inspect({
  kind: "match",
  searchTerm: "v1.5|2FA|two-step|verification|Customer Account|guest checkout|account add-on",
  options: { useRegex: true, maxResults: 300 },
  maxChars: 30000,
});
console.log(matches.ndjson);

for (const name of [
  "Approval Set",
  "Templates & Global",
  "Decisions & Access",
  "Implementation Plan",
  "QA & Launch Gates",
  "Scope & Costs",
  "Version Register",
  "Training & Handoff",
]) {
  const blob = await workbook.render({ sheetName: name, autoCrop: "all", scale: 0.7, format: "png" });
  await fs.writeFile(new URL(`${name.replaceAll(" ", "-")}.png`, previewDir), new Uint8Array(await blob.arrayBuffer()));
}
for (const [sheetName, range] of [
  ["Approval Set", "A10:J12"],
  ["Templates & Global", "A10:E12"],
  ["Decisions & Access", "A11:G13"],
  ["Implementation Plan", "A9:F11"],
  ["QA & Launch Gates", "A18:G20"],
  ["Scope & Costs", "A7:G9"],
  ["Version Register", "A10:I12"],
  ["Training & Handoff", "A5:H11"],
]) {
  const sheet = workbook.worksheets.getItem(sheetName);
  console.log(sheetName, range, JSON.stringify(sheet.getRange(range).values));
}
