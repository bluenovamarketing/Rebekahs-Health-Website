import fs from "node:fs/promises";
import { fileURLToPath } from "node:url";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const workbookPath = fileURLToPath(new URL("../outputs/01a034a3-641b-70f2-bc46-4a4f5cf27673/Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx", import.meta.url));
const previewDir = fileURLToPath(new URL("./phase2-workbook-shop-v16-qa", import.meta.url));
await fs.mkdir(previewDir, { recursive: true });

const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(workbookPath));

const approval = workbook.worksheets.getItem("Approval Set");
approval.getRange("B8:J8").values = [[
  "Shop Homepage + Product Catalog",
  "Store introduction, four illustrated wellness-goal paths, personal shopping guidance, catalog search, sort, filters, six sample products, pagination, and no-results recovery",
  "/shop/ and shared archives",
  "In review",
  "Local page-body mockup",
  "High",
  "Use /shop/ as both the store homepage and full catalog. Keep the customer-facing toolbar personal and helpful instead of emphasizing sample-product counts. Real product data still requires Revel/Kosmos validation.",
  "Unique shop introduction, category discovery, archive headings, breadcrumbs, canonicals, pagination, filter/indexation rules, card semantics, accessible result feedback, and product-help content.",
  "Review exact v1.6 page-body-only concept: shop-catalog-template-v1.6.html. It replaces visible product-count language with audience-focused guidance while preserving an accessible live result count. Final approval remains deferred pending Revel/Kosmos data."
]];

const register = workbook.worksheets.getItem("Version Register");
register.getRange("B8:I8").values = [[
  "Shop Homepage + Product Catalog",
  "v1.6",
  "shop-catalog-template-v1.6.html",
  "Revel Validation Pending",
  "Awaiting Todd review; final approval deferred pending Revel validation",
  "Not reviewed",
  "v1.7",
  "Replaced visible numeric product-count labels with personal wellness guidance; kept the live count visually hidden for accessible search feedback; preserved four illustrated paths, six cards, search, sort, filters, drawer, load-more, no-results recovery, and body-only architecture. v1.5 is preserved and superseded."
]];

const exported = await SpreadsheetFile.exportXlsx(workbook);
await exported.save(workbookPath);

for (const [file, sheetName, range, scale] of [
  ["approval-set.png", "Approval Set", "A1:J12", 1.1],
  ["version-register.png", "Version Register", "A1:I18", 1.2],
]) {
  const preview = await workbook.render({ sheetName, range, scale, format: "png" });
  await fs.writeFile(`${previewDir}/${file}`, new Uint8Array(await preview.arrayBuffer()));
}

const approvalCheck = await workbook.inspect({kind:"table",sheetId:"Approval Set",range:"A1:J12",include:"values,formulas",tableMaxRows:12,tableMaxCols:10,maxChars:18000});
const registerCheck = await workbook.inspect({kind:"table",sheetId:"Version Register",range:"A1:I12",include:"values,formulas",tableMaxRows:12,tableMaxCols:9,maxChars:16000});
const errors = await workbook.inspect({kind:"match",searchTerm:"#REF!|#DIV/0!|#VALUE!|#NAME\\?|#N/A",options:{useRegex:true,maxResults:300},summary:"formula error scan"});

console.log("APPROVAL_SET");
console.log(approvalCheck.ndjson);
console.log("VERSION_REGISTER");
console.log(registerCheck.ndjson);
console.log("FORMULA_ERRORS");
console.log(errors.ndjson);
console.log(`OUTPUT ${workbookPath}`);
