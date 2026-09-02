import fs from "node:fs/promises";
import path from "node:path";
import { fileURLToPath } from "node:url";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const workDir = path.dirname(fileURLToPath(import.meta.url));
const outputPath = path.join(workDir, "Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx");
const sourcePath = outputPath;

const input = await FileBlob.load(sourcePath);
const workbook = await SpreadsheetFile.importXlsx(input);

const approval = workbook.worksheets.getItem("Approval Set");
approval.getRange("D3").values = [[7]];
approval.getRange("J3").values = [["Client approval"]];
approval.getRange("E6").values = [["Internally Approved"]];
approval.getRange("J6").values = [[
  "Exact Header + Footer Ecommerce Add-On v1.5 was approved by Todd on September 2, 2026 and is ready for Rebekah's separate client review. In the local review, the header, tablet, and footer Online Store links open Shop Homepage + Product Catalog v1.8; production will use the real /shop/ route. v1.4 remains preserved as superseded history.",
]];
approval.getRange("E7").values = [["Internally Approved"]];
approval.getRange("J7").values = [[
  "Exact Main Homepage Ecommerce Integration v1.9 was approved by Todd on September 2, 2026 and is ready for Rebekah's separate client review. The Shop Online hero entry opens the separate Shop Homepage + Product Catalog v1.8 preview instead of jumping to the homepage ecommerce section. Production will use /shop/. v1.8 is superseded without approval; any later design change requires v1.10 and a new approval.",
]];

const implementation = workbook.worksheets.getItem("Implementation Plan");
implementation.getRange("C6").values = [["Awaiting client approval"]];
implementation.getRange("F6").values = [["Rebekah approves system 02"]];

const register = workbook.worksheets.getItem("Version Register");
register.getRange("F3").values = [[7]];
register.getRange("I3").values = [["Gates: Client approval"]];
register.getRange("C6:I6").values = [[
  "v1.5",
  "online-store-header-footer-add-on-v1.5.html",
  "Awaiting Client Approval",
  "Approved by Todd",
  "Pending Rebekah",
  "v1.6",
  "Exact v1.5 internally approved September 2, 2026. The local header, tablet, and footer Online Store links open Shop Homepage + Product Catalog v1.8; production will use /shop/. v1.4 remains preserved as superseded history. Any later design change requires v1.6 and a new approval.",
]];
register.getRange("C7:I7").values = [[
  "v1.9",
  "main-homepage-ecommerce-integration-v1.9.html",
  "Awaiting Client Approval",
  "Approved by Todd",
  "Pending Rebekah",
  "v1.10",
  "Exact v1.9 internally approved September 2, 2026. The Shop Online hero entry opens the separate Shop Homepage + Product Catalog v1.8 preview; production will use /shop/. It preserves the v1.8 numbering removal, approved imagery, copy, components, all other interactions, review-only addition labels, and page-body-only architecture. v1.8 is superseded without approval. Any later design change requires v1.10 and a new approval.",
]];

const output = await SpreadsheetFile.exportXlsx(workbook);
await output.save(outputPath);

const checkApproval = await workbook.inspect({
  kind: "table",
  range: "'Approval Set'!A3:J7",
  include: "values,formulas",
  tableMaxRows: 10,
  tableMaxCols: 12,
  maxChars: 12000,
});
const checkRegister = await workbook.inspect({
  kind: "table",
  range: "'Version Register'!A3:I7",
  include: "values,formulas",
  tableMaxRows: 10,
  tableMaxCols: 12,
  maxChars: 12000,
});
const errors = await workbook.inspect({
  kind: "match",
  searchTerm: "#REF!|#DIV/0!|#VALUE!|#NAME\\?|#N/A",
  options: { useRegex: true, maxResults: 300 },
  maxChars: 6000,
});

console.log("APPROVAL CHECK");
console.log(checkApproval.ndjson);
console.log("REGISTER CHECK");
console.log(checkRegister.ndjson);
console.log("FORMULA ERROR SCAN");
console.log(errors.ndjson);

for (const sheet of workbook.worksheets.items) {
  const preview = await workbook.render({
    sheetName: sheet.name,
    autoCrop: "all",
    scale: 0.8,
    format: "png",
  });
  const safeName = sheet.name.replace(/[^A-Za-z0-9_-]+/g, "-");
  await fs.writeFile(path.join(workDir, `after-${safeName}.png`), new Uint8Array(await preview.arrayBuffer()));
}
