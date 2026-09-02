import fs from "node:fs/promises";
import path from "node:path";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const workDir = path.resolve(import.meta.dirname);
const outputDir = path.resolve(workDir, "..");
const workbookPath = path.join(outputDir, "Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx");
const previewDir = path.join(workDir, "phase-two-preview");
const mode = process.argv[2] || "inspect";

const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(workbookPath));

async function inspectAndRender(label) {
  const approval = await workbook.inspect({
    kind: "table",
    sheetId: "Approval Set",
    range: "A1:J12",
    include: "values,formulas",
    tableMaxRows: 12,
    tableMaxCols: 10,
    maxChars: 11000,
  });
  const versions = await workbook.inspect({
    kind: "table",
    sheetId: "Version Register",
    range: "A1:I15",
    include: "values,formulas",
    tableMaxRows: 15,
    tableMaxCols: 9,
    maxChars: 11000,
  });
  console.log(`APPROVAL_${label}`);
  console.log(approval.ndjson);
  console.log(`VERSIONS_${label}`);
  console.log(versions.ndjson);

  await fs.mkdir(previewDir, { recursive: true });
  for (const sheetName of ["Approval Set", "Version Register"]) {
    const preview = await workbook.render({ sheetName, autoCrop: "all", scale: 1.2, format: "png" });
    await fs.writeFile(path.join(previewDir, `${sheetName.toLowerCase().replaceAll(" ", "-")}-${label.toLowerCase()}-page-body-v12.png`), new Uint8Array(await preview.arrayBuffer()));
  }
}

if (mode === "inspect") {
  await inspectAndRender("BEFORE");
  process.exit(0);
}

if (mode !== "apply") throw new Error(`Unknown mode: ${mode}`);

const mockups = [
  [3, "shop-catalog-template-v1.2.html", "Catalog page body only; global header, menus, ecommerce utility row, and footer remain outside this mockup."],
  [4, "product-page-templates-v1.2.html", "Simple and variation page bodies only; global chrome remains outside this mockup."],
  [5, "purchase-path-mockup-v1.2.html", "Cart through confirmation page body only; global chrome remains outside this mockup."],
  [6, "customer-account-system-v1.2.html", "Account page body only; global chrome remains outside this mockup."],
  [7, "store-states-components-v1.2.html", "Shared states page body only; global chrome remains outside this mockup."],
];

const versions = workbook.worksheets.getItem("Version Register");
const approval = workbook.worksheets.getItem("Approval Set");

for (const [system, file, note] of mockups) {
  const row = 5 + system;
  versions.getRange(`C${row}:I${row}`).values = [["v1.2", file, "Internal Review", "Not reviewed", "Not reviewed", "v1.3", note]];
  approval.getRange(`E${row}`).values = [["In review"]];
  approval.getRange(`F${row}`).values = [["Local page-body mockup"]];
  approval.getRange(`J${row}`).values = [[`v1.2 page-body-only correction ready for internal review: ${file}; v1.1 preserved as superseded.`]];
}

const exported = await SpreadsheetFile.exportXlsx(workbook);
await exported.save(workbookPath);

await inspectAndRender("AFTER");
const errors = await workbook.inspect({
  kind: "match",
  searchTerm: "#REF!|#DIV/0!|#VALUE!|#NAME\\?|#N/A",
  options: { useRegex: true, maxResults: 300 },
  summary: "final formula error scan",
});
console.log("FORMULA_ERRORS");
console.log(errors.ndjson);
console.log(`OUTPUT ${workbookPath}`);
