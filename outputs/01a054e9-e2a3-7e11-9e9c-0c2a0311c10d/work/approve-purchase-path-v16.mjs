import fs from "node:fs/promises";
import path from "node:path";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const projectDir = "C:/Users/todda/Blue Nova Projects/Rebekahs Health Website";
const outputDir = path.join(projectDir, "outputs/01a054e9-e2a3-7e11-9e9c-0c2a0311c10d");
const workbookPath = path.join(outputDir, "Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx");
const previewDir = path.join(outputDir, "work/purchase-path-v16-approved");
const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(workbookPath));

const approval = workbook.worksheets.getItem("Approval Set");
approval.getRange("D3").values = [[5]];
approval.getRange("E10").values = [["Internally Approved"]];
approval.getRange("J10").values = [[
  "Exact Purchase Path v1.6 was approved by Todd on August 31, 2026 and is ready for Rebekah's separate client review. Approval covers the cart, approved missing-photo cart treatment, empty-cart recovery, guest-checkout fields, validation, order review, customer-facing confirmation, and representative interactions. Live payment fields and failures, shipping rates, tax calculations, wallet options, fraud rules, transactional emails, inventory and order synchronization, final totals, and provider messages remain separate connected-service and Revel/Kosmos validation gates.",
]];

const versions = workbook.worksheets.getItem("Version Register");
versions.getRange("F3").values = [[5]];
versions.getRange("E10:I10").values = [[
  "Awaiting Client Approval",
  "Approved by Todd",
  "Pending Rebekah",
  "v1.7",
  "Exact v1.6 internally approved August 31, 2026. Approval covers the complete cart-to-confirmation design and interactions shown in the mockup. Payment, shipping, tax, email, inventory, order, provider-message, and Revel/Kosmos behavior remain separate connected-service implementation-validation gates. Any later design change requires v1.7 and a new approval.",
]];

console.log((await workbook.inspect({
  kind: "table",
  sheetId: "Approval Set",
  range: "A3:J10",
  include: "values,formulas",
  tableMaxRows: 8,
  tableMaxCols: 10,
  maxChars: 20000,
})).ndjson);
console.log((await workbook.inspect({
  kind: "table",
  sheetId: "Version Register",
  range: "A3:I10",
  include: "values,formulas",
  tableMaxRows: 8,
  tableMaxCols: 9,
  maxChars: 18000,
})).ndjson);

const errors = await workbook.inspect({
  kind: "match",
  searchTerm: "#REF!|#DIV/0!|#VALUE!|#NAME\\?|#N/A",
  options: { useRegex: true, maxResults: 300 },
  summary: "final formula error scan",
});
console.log(errors.ndjson);

await fs.mkdir(previewDir, { recursive: true });
for (const sheet of workbook.worksheets.items) {
  const preview = await workbook.render({ sheetName: sheet.name, autoCrop: "all", scale: 0.85, format: "png" });
  const safe = sheet.name.toLowerCase().replace(/[^a-z0-9]+/g, "-").replace(/^-|-$/g, "");
  await fs.writeFile(path.join(previewDir, `${safe}.png`), new Uint8Array(await preview.arrayBuffer()));
}

const output = await SpreadsheetFile.exportXlsx(workbook);
await output.save(workbookPath);
console.log(`OUTPUT ${workbookPath}`);
