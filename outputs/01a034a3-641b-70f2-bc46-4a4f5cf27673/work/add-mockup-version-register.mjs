import fs from "node:fs/promises";
import path from "node:path";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const workDir = path.resolve(import.meta.dirname);
const outputDir = path.resolve(workDir, "..");
const workbookPath = path.join(outputDir, "Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx");
const previewDir = path.join(workDir, "phase-two-preview");
const mode = process.argv[2] || "inspect";

const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(workbookPath));

if (mode === "inspect") {
  const sheets = await workbook.inspect({ kind: "sheet", include: "id,name", maxChars: 4000 });
  console.log(sheets.ndjson);
  process.exit(0);
}

if (mode !== "apply") throw new Error(`Unknown mode: ${mode}`);

const c = {
  forest: "#195C3B",
  pale: "#EEF5E8",
  cyan: "#BFE6F4",
  header: "#E5E7EB",
  ink: "#111827",
  muted: "#52645C",
  white: "#FFFFFF",
  gold: "#F6E8B1",
  green: "#D8EFD5",
  red: "#F6D1D5",
  line: "#6DC5EA",
};

const sheet = workbook.worksheets.add("Version Register");
sheet.showGridLines = false;
sheet.mergeCells("A1:I1");
sheet.getRange("A1").values = [["Rebekah's Phase Two Ecommerce Mockup Version Register"]];
sheet.getRange("A1:I1").format = {
  fill: c.forest,
  font: { bold: true, color: c.white, fontSize: 16, typeface: "Carlito" },
  verticalAlignment: "center",
};
sheet.getRange("A1:I1").format.rowHeight = 36;

sheet.mergeCells("A2:I2");
sheet.getRange("A2").values = [["Permanent rule: each mockup starts at v1.1; each reviewed revision advances to v1.2, v1.3, and so on; earlier reviewed files remain preserved; internal and client approval are separate gates tied to an exact version."]];
sheet.getRange("A2:I2").format = {
  fill: c.pale,
  font: { italic: true, color: c.muted, fontSize: 10, typeface: "Carlito" },
  wrapText: true,
  verticalAlignment: "center",
};
sheet.getRange("A2:I2").format.rowHeight = 38;

const summaryLabels = ["Systems", "Versioned", "Awaiting client", "Client approved"];
const labelCells = ["A3", "C3", "E3", "G3"];
const metricCells = ["B3", "D3", "F3", "H3"];
for (let i = 0; i < summaryLabels.length; i += 1) {
  sheet.getRange(labelCells[i]).values = [[summaryLabels[i]]];
  sheet.getRange(labelCells[i]).format = { fill: c.pale, font: { bold: true, color: c.forest, fontSize: 10, typeface: "Carlito" } };
  sheet.getRange(metricCells[i]).format = { fill: c.white, font: { bold: true, color: c.ink, fontSize: 11, typeface: "Carlito" }, horizontalAlignment: "center" };
}
sheet.getRange("B3").formulas = [["=COUNTA(B6:B12)"]];
sheet.getRange("D3").formulas = [["=COUNTIF(C6:C12,\"<>Not started\")"]];
sheet.getRange("F3").formulas = [["=COUNTIF(E6:E12,\"Awaiting Client Approval\")"]];
sheet.getRange("H3").formulas = [["=COUNTIF(E6:E12,\"Client Approved\")"]];
sheet.getRange("I3").values = [["Current gate: Rebekah approval"]];
sheet.getRange("I3").format = { fill: c.pale, font: { bold: true, color: c.forest, fontSize: 10, typeface: "Carlito" }, horizontalAlignment: "center" };
sheet.getRange("A3:I3").format.rowHeight = 26;

sheet.getRange("A5:I12").values = [
  ["#", "Mockup System", "Current Version", "Latest File", "Revision Status", "Internal Approval", "Client Approval", "Next Version", "Tracking Note"],
  [1, "Header + Footer Ecommerce Add-On", "v1.1", "online-store-header-footer-add-on-v1.1.html", "Awaiting Client Approval", "Approved by Todd", "Pending Rebekah", "v1.2", "Current internally approved review version; pre-rule v1.0.0 remains preserved."],
  [2, "Online Store Homepage", "Not started", "—", "Planned", "Not reviewed", "Not reviewed", "v1.1", "Begins after client approval of system 01."],
  [3, "Shop + Catalog Template", "Not started", "—", "Planned", "Not reviewed", "Not reviewed", "v1.1", "Independent version sequence."],
  [4, "Product Page Templates", "Not started", "—", "Planned", "Not reviewed", "Not reviewed", "v1.1", "Simple and variation examples share this system version."],
  [5, "Purchase Path", "Not started", "—", "Planned", "Not reviewed", "Not reviewed", "v1.1", "Cart, checkout, validation, and confirmation share this system version."],
  [6, "Customer Account System", "Not started", "—", "Planned", "Not reviewed", "Not reviewed", "v1.1", "Account states share this system version."],
  [7, "Store States + Components", "Not started", "—", "Planned", "Not reviewed", "Not reviewed", "v1.1", "Final responsive/accessibility component-system sequence."],
];

sheet.getRange("A5:I5").format = {
  fill: c.header,
  font: { bold: true, color: c.ink, fontSize: 10, typeface: "Carlito" },
  verticalAlignment: "center",
};
sheet.getRange("A5:I5").format.rowHeight = 28;
sheet.getRange("A6:I12").format = {
  font: { color: c.ink, fontSize: 10, typeface: "Carlito" },
  wrapText: true,
  verticalAlignment: "top",
};
for (let row = 6; row <= 12; row += 1) {
  sheet.getRange(`A${row}:I${row}`).format.fill = row % 2 === 0 ? c.cyan : c.white;
  sheet.getRange(`A${row}:I${row}`).format.rowHeight = 58;
}
sheet.getRange("A5:I12").format.borders = {
  insideHorizontal: { style: "thin", color: c.line },
  bottom: { style: "thin", color: c.line },
};
sheet.getRange("A6:A12").format.horizontalAlignment = "center";
sheet.getRange("C6:C12").format.horizontalAlignment = "center";
sheet.getRange("H6:H12").format.horizontalAlignment = "center";

sheet.getRange("E6:E30").dataValidation = {
  rule: {
    type: "list",
    values: ["Draft", "Internal Review", "Revision Needed", "Internally Approved", "Awaiting Client Approval", "Client Approved", "Superseded", "Planned"],
  },
};
sheet.getRange("E6:E30").conditionalFormats.add("containsText", { text: "Awaiting Client Approval", format: { fill: c.gold, font: { bold: true, color: "#6B501C" } } });
sheet.getRange("E6:E30").conditionalFormats.add("containsText", { text: "Client Approved", format: { fill: c.green, font: { bold: true, color: c.forest } } });
sheet.getRange("E6:E30").conditionalFormats.add("containsText", { text: "Revision Needed", format: { fill: c.red, font: { bold: true, color: "#9F2940" } } });

sheet.mergeCells("A14:I14");
sheet.getRange("A14").values = [["Required update sequence for every revision"]];
sheet.getRange("A14:I14").format = { fill: c.forest, font: { bold: true, color: c.white, fontSize: 11, typeface: "Carlito" } };
sheet.getRange("A14:I14").format.rowHeight = 26;
sheet.mergeCells("A15:I18");
sheet.getRange("A15").values = [["1. Save the new revision as the next versioned file; never overwrite the prior reviewed file.\n2. Update this Version Register and PHASE-TWO-MOCKUP-VERSION-REGISTER.md.\n3. Update the local review hub so it links only to the newest reviewable version.\n4. Record internal approval, client approval, or superseded status for the exact version in CLIENT-NOTES.md."]];
sheet.getRange("A15:I18").format = { fill: c.pale, font: { color: c.muted, fontSize: 10, typeface: "Carlito" }, wrapText: true, verticalAlignment: "center" };

const widths = [6, 30, 16, 38, 24, 20, 20, 15, 45];
for (let col = 0; col < widths.length; col += 1) sheet.getRangeByIndexes(0, col, 18, 1).format.columnWidth = widths[col];
sheet.freezePanes.freezeRows(5);

const exported = await SpreadsheetFile.exportXlsx(workbook);
await exported.save(workbookPath);

await fs.mkdir(previewDir, { recursive: true });
const preview = await workbook.render({ sheetName: "Version Register", range: "A1:I18", scale: 1.5, format: "png" });
await fs.writeFile(path.join(previewDir, "version-register.png"), new Uint8Array(await preview.arrayBuffer()));

const tableCheck = await workbook.inspect({ kind: "table", sheetId: "Version Register", range: "A1:I18", include: "values,formulas", tableMaxRows: 18, tableMaxCols: 9, maxChars: 10000 });
console.log("VERSION_REGISTER");
console.log(tableCheck.ndjson);
const errors = await workbook.inspect({ kind: "match", searchTerm: "#REF!|#DIV/0!|#VALUE!|#NAME\\?|#N/A", options: { useRegex: true, maxResults: 300 }, summary: "final formula error scan" });
console.log("FORMULA_ERRORS");
console.log(errors.ndjson);
console.log(`OUTPUT ${workbookPath}`);
