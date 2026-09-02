import fs from "node:fs/promises";
import { fileURLToPath } from "node:url";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const workbookPath = fileURLToPath(new URL("../outputs/01a034a3-641b-70f2-bc46-4a4f5cf27673/Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx", import.meta.url));
const previewDir = fileURLToPath(new URL("./phase2-workbook-qa", import.meta.url));
await fs.mkdir(previewDir, { recursive: true });
const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(workbookPath));
const sheetNames = [
  "Approval Set",
  "Templates & Global",
  "Product & Content",
  "Decisions & Access",
  "Implementation Plan",
  "QA & Launch Gates",
  "Scope & Costs",
  "Version Register",
  "Pre-Staging Packet",
];
for (const [index, sheetName] of sheetNames.entries()) {
  const preview = await workbook.render({ sheetName, autoCrop: "all", scale: 0.7, format: "png" });
  await fs.writeFile(`${previewDir}/${String(index + 1).padStart(2, "0")}.png`, new Uint8Array(await preview.arrayBuffer()));
}
const errors = await workbook.inspect({kind:"match",searchTerm:"#REF!|#DIV/0!|#VALUE!|#NAME\\?|#N/A",options:{useRegex:true,maxResults:300},summary:"final formula error scan"});
console.log(errors.ndjson);
console.log(`Rendered ${sheetNames.length} sheets from ${workbookPath}`);
