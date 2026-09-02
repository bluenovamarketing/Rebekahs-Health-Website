import fs from "node:fs/promises";
import { fileURLToPath } from "node:url";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const mode = process.argv[2] || "inspect";
const workbookPath = fileURLToPath(new URL("../outputs/01a034a3-641b-70f2-bc46-4a4f5cf27673/Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx", import.meta.url));
const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(workbookPath));
const costs = workbook.worksheets.getItem("Scope & Costs");
const decisions = workbook.worksheets.getItem("Decisions & Access");

if (mode === "edit") {
  costs.getRange("C10").values = [[
    "Start with Octolize Free; expected to cover the 25-product pilot. Buy PRO only if one-, two-, and three-product tests show inaccurate rates because orders require different or custom box sizes."
  ]];
  costs.getRange("E10").values = [["Only after failed multi-product packing test"]];
  costs.getRange("F10").values = [["Free-first decision"]];
  costs.getRange("G10").values = [[
    "Client email drafted but not sent; no purchase now. Reverify before purchase: https://octolize.com/product/usps-woocommerce-live-rates-pro-plugin/"
  ]];
  decisions.getRange("C9").values = [["USPS live address-dependent rates through Octolize Free first"]];
  decisions.getRange("G9").values = [[
    "Test one-, two-, and three-product carts. Recommend PRO only if custom/multiple box sizes make the free rates inaccurate."
  ]];

  costs.getRange("A10:G10").format.wrapText = true;
  costs.getRange("A10:G10").format.rowHeight = 76;
  decisions.getRange("A9:G9").format.wrapText = true;
  decisions.getRange("A9:G9").format.rowHeight = 66;

  const output = await SpreadsheetFile.exportXlsx(workbook);
  await output.save(workbookPath);
}

const check = await workbook.inspect({
  kind: "region",
  sheetId: "Scope & Costs",
  range: "A4:G13",
  maxChars: 6000,
});
console.log(check.ndjson);

const decisionCheck = await workbook.inspect({
  kind: "region",
  sheetId: "Decisions & Access",
  range: "A5:G10",
  maxChars: 4000,
});
console.log(decisionCheck.ndjson);

const styles = await workbook.inspect({
  kind: "computedStyle",
  sheetId: "Scope & Costs",
  range: "A9:G10",
  maxChars: 3000,
});
console.log(styles.ndjson);

const errors = await workbook.inspect({
  kind: "match",
  searchTerm: "#REF!|#DIV/0!|#VALUE!|#NAME\\?|#N/A",
  options: { useRegex: true, maxResults: 100 },
  summary: "formula error scan",
  maxChars: 3000,
});
console.log(errors.ndjson);

const preview = await workbook.render({
  sheetName: "Scope & Costs",
  range: "A1:G13",
  scale: 1.4,
  format: "png",
});
const previewName = mode === "edit" ? "shipping-decision-after.png" : "shipping-decision-before.png";
await fs.writeFile(new URL(`../.tools/${previewName}`, import.meta.url), new Uint8Array(await preview.arrayBuffer()));
console.log(workbookPath);
