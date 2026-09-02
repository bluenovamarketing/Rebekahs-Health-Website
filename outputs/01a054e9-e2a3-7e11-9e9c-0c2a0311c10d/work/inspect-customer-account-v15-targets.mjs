import path from "node:path";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const projectDir = "C:/Users/todda/Blue Nova Projects/Rebekahs Health Website";
const workbookPath = path.join(projectDir, "outputs/01a054e9-e2a3-7e11-9e9c-0c2a0311c10d/Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx");
const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(workbookPath));

const targets = [
  ["Approval Set", "A3:J12"],
  ["Templates & Global", "A4:E20"],
  ["Decisions & Access", "A3:G28"],
  ["Implementation Plan", "A4:F17"],
  ["QA & Launch Gates", "A4:G33"],
  ["Scope & Costs", "A4:G21"],
  ["Training & Handoff", "A4:H18"],
  ["Version Register", "A3:I18"],
];

for (const [sheetId, range] of targets) {
  const result = await workbook.inspect({
    kind: "table",
    sheetId,
    range,
    include: "values,formulas",
    tableMaxRows: 35,
    tableMaxCols: 10,
    tableMaxCellChars: 350,
    maxChars: 18000,
  });
  console.log(`TARGET ${sheetId} ${range}`);
  console.log(result.ndjson);
}

for (const [sheetId, range] of [
  ["Approval Set", "A10:J12"],
  ["Templates & Global", "A10:E12"],
  ["Decisions & Access", "A11:G13"],
  ["Implementation Plan", "A9:F11"],
  ["QA & Launch Gates", "A18:G20"],
  ["Scope & Costs", "A7:G9"],
  ["Training & Handoff", "A7:H10"],
  ["Version Register", "A9:I12"],
]) {
  const styles = await workbook.inspect({ kind: "computedStyle", sheetId, range, maxChars: 6000 });
  console.log(`STYLE ${sheetId} ${range}`);
  console.log(styles.ndjson);
}
