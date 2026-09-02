import fs from "node:fs/promises";
import path from "node:path";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const projectDir = "C:/Users/todda/Blue Nova Projects/Rebekahs Health Website";
const inputPath = path.join(
  projectDir,
  "outputs/01a054e9-e2a3-7e11-9e9c-0c2a0311c10d/Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx",
);
const previewDir = path.join(
  projectDir,
  "outputs/01a054e9-e2a3-7e11-9e9c-0c2a0311c10d/work/before",
);

const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(inputPath));
console.log((await workbook.inspect({ kind: "sheet", include: "id,name", maxChars: 5000 })).ndjson);

for (const sheetName of ["Approval Set", "Templates & Global", "Product & Content", "Version Register"]) {
  let sheet;
  try {
    sheet = workbook.worksheets.getItem(sheetName);
  } catch {
    continue;
  }
  const used = sheet.getUsedRange();
  console.log(`TABLE ${sheetName}`);
  console.log((await workbook.inspect({
    kind: "table",
    sheetId: sheetName,
    range: used.address,
    include: "values,formulas",
    tableMaxRows: 30,
    tableMaxCols: 12,
    tableMaxCellChars: 180,
    maxChars: 18000,
  })).ndjson);
  console.log(`STYLES ${sheetName}`);
  console.log((await workbook.inspect({
    kind: "computedStyle",
    sheetId: sheetName,
    range: sheetName === "Templates & Global" ? "A18:E20" : "A5:J12",
    maxChars: 8000,
  })).ndjson);
  await fs.mkdir(previewDir, { recursive: true });
  const preview = await workbook.render({ sheetName, autoCrop: "all", scale: 1.1, format: "png" });
  const safe = sheetName.toLowerCase().replace(/[^a-z0-9]+/g, "-").replace(/^-|-$/g, "");
  await fs.writeFile(path.join(previewDir, `${safe}.png`), new Uint8Array(await preview.arrayBuffer()));
}
