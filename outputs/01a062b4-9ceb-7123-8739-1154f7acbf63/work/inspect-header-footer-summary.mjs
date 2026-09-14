import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load("outputs/01a062b4-9ceb-7123-8739-1154f7acbf63/Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx"));
const result = await workbook.inspect({
  kind: "table",
  range: "Approval Set!A3:J3",
  include: "values,formulas",
  tableMaxRows: 3,
  tableMaxCols: 12,
});
console.log(result.ndjson);
