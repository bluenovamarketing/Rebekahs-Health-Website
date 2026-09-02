import fs from "node:fs/promises";
import path from "node:path";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const workDir = path.resolve(import.meta.dirname);
const outputDir = path.resolve(workDir, "..");
const workbookPath = path.join(outputDir, "Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx");
const previewDir = path.join(workDir, "phase-two-preview");
const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(workbookPath));
const sheet = workbook.worksheets.getItem("Version Register");

sheet.getRange("C6").values = [["v1.4"]];
sheet.getRange("D6").values = [["online-store-header-footer-add-on-v1.4.html"]];
sheet.getRange("H6").values = [["v1.5"]];
sheet.getRange("I6").values = [["Current internally approved review version; earlier v1.1-v1.3 iterations and pre-rule v1.0.0 remain preserved."]];

const exported = await SpreadsheetFile.exportXlsx(workbook);
await exported.save(workbookPath);

await fs.mkdir(previewDir, { recursive: true });
const preview = await workbook.render({ sheetName: "Version Register", range: "A1:I18", scale: 1.5, format: "png" });
await fs.writeFile(path.join(previewDir, "version-register.png"), new Uint8Array(await preview.arrayBuffer()));

const check = await workbook.inspect({ kind: "table", sheetId: "Version Register", range: "A1:I12", include: "values,formulas", tableMaxRows: 12, tableMaxCols: 9, maxChars: 8000 });
console.log(check.ndjson);
const errors = await workbook.inspect({ kind: "match", searchTerm: "#REF!|#DIV/0!|#VALUE!|#NAME\\?|#N/A", options: { useRegex: true, maxResults: 300 }, summary: "final formula error scan" });
console.log("FORMULA_ERRORS");
console.log(errors.ndjson);
console.log(`OUTPUT ${workbookPath}`);
