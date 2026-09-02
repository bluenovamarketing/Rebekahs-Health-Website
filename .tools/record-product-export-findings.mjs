import fs from "node:fs/promises";
import { fileURLToPath } from "node:url";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const workbookPath = fileURLToPath(new URL("../outputs/01a034a3-641b-70f2-bc46-4a4f5cf27673/Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx", import.meta.url));
const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(workbookPath));
const decisions = workbook.worksheets.getItem("Decisions & Access");
const products = workbook.worksheets.getItem("Product & Content");

decisions.getRange("A2").values = [[
  "Updated through August 30, 2026. Mark's 25-product Revel export is saved and inspected: every product is Active and marked for online/third-party applications. Remaining content, identifier, weight, inventory, access, and live-sync checks stay open below."
]];
decisions.getRange("C22").values = [[
  "Mark's 25-product export is saved; all 25 show Active = Yes and online/third-party = Yes"
]];
decisions.getRange("D22").values = [["In progress"]];
decisions.getRange("G22").values = [[
  "Run the first Kosmos pull to prove all 25 appear, then reconcile missing SKU, descriptions, weight, inventory, and media."
]];

const findings = {
  "F6": "Confirmed",
  "G6": "All 25 export rows show Active = Yes and Display on online/third-party = Yes.",
  "H6": "They are eligible to pull; the first Kosmos test still must prove that all 25 arrive.",
  "F7": "In progress",
  "G7": "All 25 names are present; customer-facing capitalization and wording still need review.",
  "H7": "The export uses register-style, mostly all-capital names.",
  "F8": "In progress",
  "G8": "All 25 have distinct barcodes, but the SKU column is blank; confirm the integration key.",
  "H8": "Do not assume a blank SKU is acceptable until the first Kosmos mapping test.",
  "F9": "In progress",
  "G9": "All 25 prices are present in the export and must match after synchronization.",
  "H9": "Verify with the first pull and one update test.",
  "F10": "Pending product data",
  "H10": "Clarkston inventory quantities and stock status are not included in this export.",
  "F11": "Pending product data",
  "H11": "No weight field appears in the export. A staging-only placeholder may be used; live rates require verified product weights.",
  "F12": "In progress",
  "G12": "Class, category, and subcategory are present for all 25; map them after the first pull.",
  "H12": "Website categories may be simplified after import.",
  "F13": "In progress",
  "G13": "Vendor is Rebekahs Private Label for all 25; confirm the customer-facing brand label.",
  "H13": "Test whether vendor/brand transfers through Kosmos.",
  "F15": "Pending sync test",
  "H15": "The export contains no product-image fields.",
  "F16": "Pending product data",
  "G16": "The Product Description field is blank for all 25 products.",
  "H16": "Add approved customer-facing copy only after confirming field ownership and overwrite behavior.",
  "G24": "The exact 25 are saved locally; all are active/online, with remaining gaps tracked in this sheet.",
  "H24": "Next proof is the first Kosmos pull, not another product-selection request."
};

for (const [address, value] of Object.entries(findings)) {
  products.getRange(address).values = [[value]];
}

const confirmed = { fill: "#DCFCE7", font: { color: "#166534", bold: true } };
const progress = { fill: "#DBEAFE", font: { color: "#1D4ED8", bold: true } };
decisions.getRange("D22").format = progress;
products.getRange("F6").format = confirmed;
for (const address of ["F7", "F8", "F9", "F12", "F13", "F24"]) {
  products.getRange(address).format = progress;
}

decisions.getRange("A2:G2").format.rowHeight = 56;
decisions.getRange("A22:G22").format.rowHeight = 66;
for (const row of [6, 7, 8, 9, 10, 11, 12, 13, 15, 16, 24]) {
  products.getRange(`A${row}:H${row}`).format.wrapText = true;
  products.getRange(`A${row}:H${row}`).format.rowHeight = row === 11 || row === 16 ? 72 : 62;
}

const errors = await workbook.inspect({
  kind: "match",
  searchTerm: "#REF!|#DIV/0!|#VALUE!|#NAME\\?|#N/A",
  options: { useRegex: true, maxResults: 100 },
  summary: "formula error scan",
  maxChars: 5000,
});
console.log(errors.ndjson);

const preview = await workbook.render({
  sheetName: "Product & Content",
  autoCrop: "all",
  scale: 1,
  format: "png",
});
await fs.writeFile(new URL("../.tools/phase2-products-final.png", import.meta.url), new Uint8Array(await preview.arrayBuffer()));

const file = await SpreadsheetFile.exportXlsx(workbook);
await file.save(workbookPath);
console.log(workbookPath);
