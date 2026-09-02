import fs from "node:fs/promises";
import path from "node:path";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const projectDir = "C:/Users/todda/Blue Nova Projects/Rebekahs Health Website";
const outputDir = path.join(projectDir, "outputs/01a054e9-e2a3-7e11-9e9c-0c2a0311c10d");
const workbookPath = path.join(outputDir, "Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx");
const previewDir = path.join(outputDir, "work/final-all-sheets");
const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(workbookPath));

await fs.mkdir(previewDir, { recursive: true });
const sheets = workbook.worksheets.items;
for (const sheet of sheets) {
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
console.log(`SHEETS ${sheets.map((sheet) => sheet.name).join(" | ")}`);
console.log(errors.ndjson);
