import fs from "node:fs/promises";
import { fileURLToPath } from "node:url";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const workbookPath = fileURLToPath(new URL("../outputs/01a034a3-641b-70f2-bc46-4a4f5cf27673/Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx", import.meta.url));
const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(workbookPath));

const decisions = workbook.worksheets.getItem("Decisions & Access");
const products = workbook.worksheets.getItem("Product & Content");
const costs = workbook.worksheets.getItem("Scope & Costs");

decisions.getRange("A2:G2").merge();
decisions.getRange("A2").values = [[
  "Updated through August 30, 2026. Email replies and supplied files are tracked separately from items that still require verification. A 25-product export does not by itself confirm the Revel online/third-party setting."
]];

const decisionUpdates = {
  "C10": "Do not allow PO boxes",
  "D10": "Confirmed",
  "G10": "Add checkout validation and matching policy wording.",
  "C12": "Guest checkout allowed; customer accounts optional",
  "D12": "Confirmed",
  "G12": "Configure and test on staging.",
  "C13": "Off",
  "D13": "Confirmed",
  "G13": "Configure and verify with the first synchronized order.",
  "C14": "Hold back one unit unless sync testing supports removal",
  "D14": "Confirmed",
  "G14": "Measure sync timing during the pilot before changing the buffer.",
  "C17": "Treat dietary supplements as Michigan tax-exempt food per client direction",
  "D17": "Confirmed",
  "G17": "Verify tax and shipping totals with test orders before launch.",
  "C19": "rebekahspureliving@gmail.com and clarkstonpurchaser@rebekahspureliving.com",
  "D19": "Confirmed",
  "G19": "Configure both recipients and test every required notification.",
  "C22": "Mark supplied a 25-product Revel export; fields and online eligibility remain unverified",
  "D22": "Pending product data",
  "G22": "Audit the export, then verify Active + online/third-party for all 25 in Revel/Kosmos.",
  "C23": "Revel invitation received and password created; Clarkston integration permissions not verified",
  "D23": "Pending access",
  "G23": "Log in and verify establishment-level product and integration permissions.",
  "C24": "Client-owned account created; 14-day trial active; access supplied",
  "D24": "Confirmed",
  "G24": "Blue Nova creates the WooCommerce key/secret and performs the first connection test.",
  "B25": "USPS account/API and live-rate plugin",
  "C25": "USPS business account exists; developer/API access and plugin setup are not confirmed",
  "D25": "Pending access",
  "G25": "Authorize USPS API access and test the free live-rate plugin before any paid upgrade.",
  "C28": "Kosmos 14-day trial is active; exact expiration date and safe test window must be confirmed",
  "D28": "Needs approval",
  "G28": "Confirm the expiration date before starting the Revel/WooCommerce connection test."
};

for (const [address, value] of Object.entries(decisionUpdates)) {
  decisions.getRange(address).values = [[value]];
}

const productUpdates = {
  "G6": "All 25 intended pilot products are active and appear through the integration test.",
  "H6": "Mark's export proves product selection only; verify the Revel online/third-party flag before pull.",
  "C11": "Revel if supported; otherwise WooCommerce",
  "G11": "Representative carts return valid rates without relying on one unverified live weight.",
  "H11": "Mark could not find the Revel field. A conservative placeholder may be used for staging only; confirm the live system of record before launch.",
  "F24": "In progress",
  "G24": "Mark supplied the 25-product export; Blue Nova must reconcile exact names, SKUs, and gaps.",
  "H24": "The export does not confirm Active + online/third-party eligibility."
};

for (const [address, value] of Object.entries(productUpdates)) {
  products.getRange(address).values = [[value]];
}

const costUpdates = {
  "C9": "$49 month-to-month or $39/month billed annually; 14-day trial is currently active",
  "E9": "Active trial window",
  "F9": "Active trial",
  "B10": "USPS live-rate plugin (free pilot first)",
  "C10": "Test the free Octolize USPS live-rate plugin; use paid PRO only if testing proves advanced packing is required",
  "E10": "Shipping configuration",
  "F10": "No purchase yet",
  "G10": "Reverify before purchase: https://octolize.com/product/usps-woocommerce-live-rates-pro-plugin/"
};

for (const [address, value] of Object.entries(costUpdates)) {
  costs.getRange(address).values = [[value]];
}

const confirmed = { fill: "#DCFCE7", font: { color: "#166534", bold: true } };
const pending = { fill: "#FEF3C7", font: { color: "#92400E", bold: true } };
const progress = { fill: "#DBEAFE", font: { color: "#1D4ED8", bold: true } };

for (const address of ["D10", "D12", "D13", "D14", "D17", "D19", "D24"]) {
  decisions.getRange(address).format = confirmed;
}
for (const address of ["D22", "D23", "D25", "D28"]) {
  decisions.getRange(address).format = pending;
}
products.getRange("F24").format = progress;
costs.getRange("F9").format = progress;
costs.getRange("F10").format = pending;

for (const range of ["A2:G2", "A10:G10", "A12:G14", "A17:G19", "A22:G25", "A28:G28"]) {
  decisions.getRange(range).format.wrapText = true;
}
decisions.getRange("A2:G2").format.rowHeight = 52;
decisions.getRange("A22:G25").format.rowHeight = 62;
decisions.getRange("A28:G28").format.rowHeight = 58;
products.getRange("A6:H6").format.rowHeight = 58;
products.getRange("A11:H11").format.rowHeight = 70;
products.getRange("A24:H24").format.rowHeight = 62;
costs.getRange("A9:G10").format.rowHeight = 62;

const errors = await workbook.inspect({
  kind: "match",
  searchTerm: "#REF!|#DIV/0!|#VALUE!|#NAME\\?|#N/A",
  options: { useRegex: true, maxResults: 100 },
  summary: "formula error scan",
  maxChars: 5000,
});
console.log(errors.ndjson);

const decisionsPreview = await workbook.render({
  sheetName: "Decisions & Access",
  autoCrop: "all",
  scale: 1,
  format: "png",
});
await fs.writeFile(new URL("../.tools/phase2-decisions-updated.png", import.meta.url), new Uint8Array(await decisionsPreview.arrayBuffer()));

const productsPreview = await workbook.render({
  sheetName: "Product & Content",
  autoCrop: "all",
  scale: 1,
  format: "png",
});
await fs.writeFile(new URL("../.tools/phase2-products-updated.png", import.meta.url), new Uint8Array(await productsPreview.arrayBuffer()));

const file = await SpreadsheetFile.exportXlsx(workbook);
await file.save(workbookPath);
console.log(workbookPath);
