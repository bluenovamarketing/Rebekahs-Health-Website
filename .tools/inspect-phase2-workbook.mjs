import fs from "node:fs/promises";
import { fileURLToPath } from "node:url";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const workbookPath = fileURLToPath(new URL("../outputs/01a034a3-641b-70f2-bc46-4a4f5cf27673/Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx", import.meta.url));
const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(workbookPath));

const decisionsSheet = workbook.worksheets.getItem("Decisions & Access");
const productsSheet = workbook.worksheets.getItem("Product & Content");
const costsSheet = workbook.worksheets.getItem("Scope & Costs");

const sheets = await workbook.inspect({
  kind: "sheet",
  include: "id,name",
  maxChars: 12000,
});

const matches = await workbook.inspect({
  kind: "match",
  searchTerm: "online|third party|weight|Kosmos|Revel|USPS|25 product|question|issue|pending",
  options: { useRegex: true, maxResults: 300 },
  maxChars: 24000,
});

const decisions = await workbook.inspect({
  kind: "region",
  sheetId: "Decisions & Access",
  range: "A1:G28",
  maxChars: 24000,
});

const products = await workbook.inspect({
  kind: "region",
  sheetId: "Product & Content",
  range: "A1:H24",
  maxChars: 24000,
});

const costs = await workbook.inspect({
  kind: "region",
  sheetId: "Scope & Costs",
  range: "A1:G21",
  maxChars: 20000,
});

const preview = await workbook.render({
  sheetName: "Decisions & Access",
  autoCrop: "all",
  scale: 1,
  format: "png",
});
await fs.writeFile(new URL("../.tools/phase2-decisions-preview.png", import.meta.url), new Uint8Array(await preview.arrayBuffer()));

console.log("SHEETS");
console.log(sheets.ndjson);
console.log("MATCHES");
console.log(matches.ndjson);
console.log("DECISIONS");
console.log(decisions.ndjson);
console.log("PRODUCTS");
console.log(products.ndjson);
console.log("COSTS");
console.log(costs.ndjson);
console.log("DECISION VALUES");
console.log(JSON.stringify(decisionsSheet.getRange("A5:G28").values));
console.log("PRODUCT VALUES");
console.log(JSON.stringify(productsSheet.getRange("A5:H24").values));
console.log("COST VALUES");
console.log(JSON.stringify(costsSheet.getRange("A4:G21").values));
