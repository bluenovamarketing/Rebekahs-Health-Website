import fs from "node:fs/promises";
import path from "node:path";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const workDir = path.resolve(import.meta.dirname);
const projectDir = path.resolve(workDir, "..", "..", "..");
const outputDir = path.resolve(workDir, "..");
const planningPath = path.join(outputDir, "Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx");
const productPath = path.join(projectDir, "client-inputs", "phase-two", "Product_Export_Establishment_3 (70)_results.xlsx");
const previewDir = path.join(workDir, "phase-two-preview");
const mode = process.argv[2] || "inspect";

const planning = await SpreadsheetFile.importXlsx(await FileBlob.load(planningPath));

async function inspectPlanning(label) {
  const approval = await planning.inspect({
    kind: "table",
    sheetId: "Approval Set",
    range: "A1:J12",
    include: "values,formulas",
    tableMaxRows: 12,
    tableMaxCols: 10,
    maxChars: 10000,
  });
  const versions = await planning.inspect({
    kind: "table",
    sheetId: "Version Register",
    range: "A1:I18",
    include: "values,formulas",
    tableMaxRows: 18,
    tableMaxCols: 9,
    maxChars: 10000,
  });
  console.log(`APPROVAL_${label}`);
  console.log(approval.ndjson);
  console.log(`VERSIONS_${label}`);
  console.log(versions.ndjson);
}

if (mode === "inspect") {
  const products = await SpreadsheetFile.importXlsx(await FileBlob.load(productPath));
  const productSheets = await products.inspect({ kind: "sheet", include: "id,name", maxChars: 3000 });
  console.log("PRODUCT_SHEETS");
  console.log(productSheets.ndjson);
  const productTable = await products.inspect({
    kind: "table",
    sheetId: products.worksheets.getItemAt(0).name,
    range: "A1:Z18",
    include: "values,formulas",
    tableMaxRows: 18,
    tableMaxCols: 26,
    tableMaxCellChars: 120,
    maxChars: 18000,
  });
  console.log("PRODUCTS");
  console.log(productTable.ndjson);
  await inspectPlanning("BEFORE");
  await fs.mkdir(previewDir, { recursive: true });
  for (const sheetName of ["Approval Set", "Version Register"]) {
    const preview = await planning.render({ sheetName, autoCrop: "all", scale: 1.2, format: "png" });
    await fs.writeFile(path.join(previewDir, `${sheetName.toLowerCase().replaceAll(" ", "-")}-before-03-07.png`), new Uint8Array(await preview.arrayBuffer()));
  }
  process.exit(0);
}

if (mode !== "apply") throw new Error(`Unknown mode: ${mode}`);

const mockups = [
  [3, "Shop + Catalog Template", "v1.1", "shop-catalog-template-v1.1.html", "Catalog grid, filters, sorting, search, pagination, and responsive mobile filter drawer."],
  [4, "Product Page Templates", "v1.1", "product-page-templates-v1.1.html", "Simple and variation product examples share this system version."],
  [5, "Purchase Path", "v1.1", "purchase-path-mockup-v1.1.html", "Cart, empty cart, checkout, validation, and confirmation share this system version."],
  [6, "Customer Account System", "v1.1", "customer-account-system-v1.1.html", "Login, registration, reset, dashboard, addresses, order history, and order detail."],
  [7, "Store States + Components", "v1.1", "store-states-components-v1.1.html", "Empty, error, loading, unavailable, missing-image, and mobile drawer states."],
];

const versionSheet = planning.worksheets.getItem("Version Register");
for (const [system, name, version, file, note] of mockups) {
  const row = 5 + system;
  versionSheet.getRange(`A${row}:I${row}`).values = [[system, name, version, file, "Internal Review", "Not reviewed", "Not reviewed", "v1.2", note]];
}

const approval = planning.worksheets.getItem("Approval Set");
for (const [system, , version, file] of mockups) {
  const row = 5 + system;
  approval.getRange(`E${row}`).values = [["In review"]];
  approval.getRange(`F${row}`).values = [["Local mockup complete"]];
  approval.getRange(`J${row}`).values = [[`${version} ready for internal review: ${file}`]];
}

const templates = planning.worksheets.getItem("Templates & Global");
const used = templates.getUsedRange();
const templateInspect = await planning.inspect({ kind: "table", sheetId: "Templates & Global", range: used.address, include: "values", tableMaxRows: 40, tableMaxCols: 12, maxChars: 12000 });
console.log("TEMPLATES_CONTEXT");
console.log(templateInspect.ndjson);

const exported = await SpreadsheetFile.exportXlsx(planning);
await exported.save(planningPath);

await inspectPlanning("AFTER");
await fs.mkdir(previewDir, { recursive: true });
for (const sheetName of ["Approval Set", "Version Register"]) {
  const preview = await planning.render({ sheetName, autoCrop: "all", scale: 1.2, format: "png" });
  await fs.writeFile(path.join(previewDir, `${sheetName.toLowerCase().replaceAll(" ", "-")}-after-03-07.png`), new Uint8Array(await preview.arrayBuffer()));
}

const errors = await planning.inspect({
  kind: "match",
  searchTerm: "#REF!|#DIV/0!|#VALUE!|#NAME\\?|#N/A",
  options: { useRegex: true, maxResults: 300 },
  summary: "final formula error scan",
});
console.log("FORMULA_ERRORS");
console.log(errors.ndjson);
console.log(`OUTPUT ${planningPath}`);
