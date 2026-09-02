import { fileURLToPath } from "node:url";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const inputPath = fileURLToPath(new URL("../client-inputs/phase-two/Product_Export_Establishment_3 (70)_results.xlsx", import.meta.url));
const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(inputPath));
const sheets = await workbook.inspect({ kind: "sheet", include: "id,name", maxChars: 5000 });
console.log("SHEETS");
console.log(sheets.ndjson);

const firstSheet = workbook.worksheets.getItemAt(0);
const region = await workbook.inspect({
  kind: "region",
  sheetId: firstSheet.name,
  range: "A1:Z40",
  maxChars: 30000,
});
console.log("REGION");
console.log(region.ndjson);
console.log("VALUES");
console.log(JSON.stringify(firstSheet.getRange("A1:Z40").values));

const matches = await workbook.inspect({
  kind: "match",
  searchTerm: "online|third party|display|active|weight|SKU|barcode|price|inventory|category|brand|manufacturer",
  options: { useRegex: true, maxResults: 300 },
  maxChars: 20000,
});
console.log("MATCHES");
console.log(matches.ndjson);
