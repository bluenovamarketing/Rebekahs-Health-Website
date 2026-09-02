import fs from "node:fs/promises";
import path from "node:path";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const workDir = path.resolve(import.meta.dirname);
const outputDir = path.resolve(workDir, "..");
const workbookPath = path.join(outputDir, "Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx");
const previewDir = path.join(workDir, "phase-two-preview");
const mode = process.argv[2] || "inspect";

const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(workbookPath));

async function inspectAndRender(suffix) {
  const approvalCheck = await workbook.inspect({
    kind: "table",
    sheetId: "Approval Set",
    range: "A1:J12",
    include: "values,formulas",
    tableMaxRows: 12,
    tableMaxCols: 10,
    maxChars: 9000,
  });
  console.log(`APPROVAL_SET_${suffix.toUpperCase()}`);
  console.log(approvalCheck.ndjson);

  const styleCheck = await workbook.inspect({
    kind: "computedStyle",
    sheetId: "Approval Set",
    range: "C3:J6",
    maxChars: 3000,
  });
  console.log(`STYLE_${suffix.toUpperCase()}`);
  console.log(styleCheck.ndjson);

  await fs.mkdir(previewDir, { recursive: true });
  const preview = await workbook.render({
    sheetName: "Approval Set",
    range: "A1:J12",
    scale: 1.5,
    format: "png",
  });
  await fs.writeFile(
    path.join(previewDir, `approval-set-${suffix}.png`),
    new Uint8Array(await preview.arrayBuffer()),
  );
}

if (mode === "inspect") {
  await inspectAndRender("before-status-update");
  process.exit(0);
}

if (mode !== "apply") {
  throw new Error(`Unknown mode: ${mode}`);
}

const approval = workbook.worksheets.getItem("Approval Set");
approval.getRange("C3").values = [["Internally approved"]];
approval.getRange("D3").formulas = [['=COUNTIF(E6:E12,"Internally Approved")']];
approval.getRange("G3").values = [["Client approved"]];
approval.getRange("H3").formulas = [['=COUNTIF(E6:E12,"Client Approved")']];
approval.getRange("J3").values = [["Client approval"]];
approval.getRange("E6").values = [["Internally Approved"]];
approval.getRange("J6").values = [["Awaiting Rebekah’s client approval before starting the Online Store homepage."]];
approval.getRange("E6:E30").dataValidation = {
  rule: {
    type: "list",
    values: [
      "Planned",
      "Planned next",
      "In review",
      "Revision needed",
      "Internally Approved",
      "Client Approved",
    ],
  },
};

const templates = workbook.worksheets.getItem("Templates & Global");
templates.getRange("D13:D14").values = [
  ["Internally approved; awaiting client"],
  ["Internally approved; awaiting client"],
];

const implementation = workbook.worksheets.getItem("Implementation Plan");
implementation.getRange("C5").values = [["Awaiting client approval"]];
implementation.getRange("F5").values = [["Rebekah approves system 01"]];

const exported = await SpreadsheetFile.exportXlsx(workbook);
await exported.save(workbookPath);

await inspectAndRender("after-status-update");

const errors = await workbook.inspect({
  kind: "match",
  searchTerm: "#REF!|#DIV/0!|#VALUE!|#NAME\\?|#N/A",
  options: { useRegex: true, maxResults: 300 },
  summary: "final formula error scan",
});
console.log("FORMULA_ERRORS");
console.log(errors.ndjson);
console.log(`OUTPUT ${workbookPath}`);
