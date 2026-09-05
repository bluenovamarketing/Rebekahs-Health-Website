import path from "node:path";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const root = "C:/Users/todda/Blue Nova Projects/Rebekahs Health Website";
const workbookPath = path.join(root, "outputs/01a034a3-641b-70f2-bc46-4a4f5cf27673/Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx");
const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(workbookPath));

for (const [sheetId, range] of [
  ["Approval Set", "A1:J12"],
  ["Implementation Plan", "A1:F17"],
  ["Version Register", "A1:I15"],
  ["Pre-Staging Packet", "A1:H16"],
]) {
  console.log(`===== ${sheetId} ${range} =====`);
  console.log((await workbook.inspect({
    kind: "table",
    sheetId,
    range,
    include: "values,formulas",
    tableMaxRows: 30,
    tableMaxCols: 12,
    tableMaxCellChars: 500,
    maxChars: 30000,
  })).ndjson);
}
