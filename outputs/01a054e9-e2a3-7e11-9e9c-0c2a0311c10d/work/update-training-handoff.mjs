import fs from "node:fs/promises";
import path from "node:path";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const projectDir = "C:/Users/todda/Blue Nova Projects/Rebekahs Health Website";
const inputPath = path.join(
  projectDir,
  "outputs/01a034a3-641b-70f2-bc46-4a4f5cf27673/Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx",
);
const outputDir = path.join(projectDir, "outputs/01a054e9-e2a3-7e11-9e9c-0c2a0311c10d");
const outputPath = path.join(outputDir, "Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx");
const previewDir = path.join(outputDir, "work/after");

await fs.mkdir(previewDir, { recursive: true });
const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(inputPath));

const colors = {
  forest: "#195C3B",
  sage: "#EEF5E8",
  header: "#E5E7EB",
  white: "#FFFFFF",
  ink: "#111827",
  muted: "#33413A",
  review: "#F7E8B8",
  approved: "#D9EFD9",
  pending: "#F8D7DA",
};
const font = { name: "Carlito", size: 10, color: colors.ink };

function applyStatusRules(range) {
  range.conditionalFormats.add("containsText", {
    text: "Complete",
    format: { fill: colors.approved, font: { bold: true, color: colors.forest } },
  });
  range.conditionalFormats.add("containsText", {
    text: "In progress",
    format: { fill: colors.review, font: { bold: true, color: "#6B501C" } },
  });
  range.conditionalFormats.add("containsText", {
    text: "Not started",
    format: { fill: colors.pending, font: { color: "#842029" } },
  });
}

const implementation = workbook.worksheets.getItem("Implementation Plan");
implementation.getRange("E17").values = [[
  "Pass QA and acceptance, obtain client approval, create and deliver the welcome packet with instructional videos, written guides, support boundaries, and escalation contacts; train designated staff; launch in a controlled window; and monitor stabilization before full-catalog planning.",
]];
implementation.getRange("F17").values = [[
  "All tests pass; client signoff; welcome packet delivered; staff training acknowledged; controlled launch and handoff.",
]];

const qa = workbook.worksheets.getItem("QA & Launch Gates");
qa.getRange("C31:G31").values = [[
  "The welcome packet is delivered and designated staff complete the instructional videos, written guides, live walkthrough, and escalation review for accounts, orders, Revel maintenance, fulfillment, refunds, and customer support.",
  "Prelaunch",
  "Blue Nova / Rebekah",
  "Not started",
  "Record the packet link, video links, written guides, staff attendees, acknowledgment, and escalation contact sheet.",
]];
qa.getRange("A31:G31").format.rowHeight = 82;

const handoff = workbook.worksheets.add("Training & Handoff");
handoff.showGridLines = false;
handoff.freezePanes.freezeRows(5);

handoff.getRange("A1:H1").merge();
handoff.getRange("A1").values = [["Phase Two Staff Training, Welcome Packet, and Handoff Tracker"]];
handoff.getRange("A1:H1").format = {
  fill: colors.forest,
  font: { name: "Carlito", size: 16, bold: true, color: colors.white },
  verticalAlignment: "center",
  rowHeight: 32,
};

handoff.getRange("A2:H2").merge();
handoff.getRange("A2").values = [[
  "Blue Nova prepares the materials and training. Rebekah designates the staff who will use them, confirms store procedures, attends the walkthrough, and keeps the completed packet. Staff follow documented steps and escalate technical issues; they do not diagnose the website, approve accounts, handle customer passwords, or create guest accounts.",
]];
handoff.getRange("A2:H2").format = {
  fill: colors.sage,
  font: { name: "Carlito", size: 11, italic: true, color: colors.muted },
  wrapText: true,
  verticalAlignment: "center",
  rowHeight: 52,
};

handoff.getRange("A3").values = [["Items"]];
handoff.getRange("B3").formulas = [["=COUNTA(B6:B18)"]];
handoff.getRange("C3").values = [["Complete"]];
handoff.getRange("D3").formulas = [["=COUNTIF(G6:G18,\"Complete\")"]];
handoff.getRange("E3").values = [["Open"]];
handoff.getRange("F3").formulas = [["=COUNTIF(G6:G18,\"Not started\")+COUNTIF(G6:G18,\"In progress\")"]];
handoff.getRange("G3:H3").merge();
handoff.getRange("G3").values = [["Required before controlled launch"]];
for (const cell of ["A3", "C3", "E3"]) {
  handoff.getRange(cell).format = {
    fill: colors.sage,
    font: { name: "Carlito", size: 10, bold: true, color: colors.forest },
    verticalAlignment: "center",
  };
}
for (const cell of ["B3", "D3", "F3"]) {
  handoff.getRange(cell).format = {
    fill: colors.white,
    font: { name: "Carlito", size: 11, bold: true, color: colors.ink },
    horizontalAlignment: "center",
    verticalAlignment: "center",
  };
}
handoff.getRange("G3:H3").format = {
  fill: colors.sage,
  font: { name: "Carlito", size: 10, bold: true, color: colors.forest },
  horizontalAlignment: "center",
  verticalAlignment: "center",
};
handoff.getRange("A3:H3").format.rowHeight = 26;

const rows = [
  ["#", "Welcome Packet Item", "What Staff Will Learn or Do", "Blue Nova Provides", "Client Provides or Does", "Timing / Gate", "Status", "Evidence / Link"],
  [1, "Designated ecommerce support contacts", "Know which staff members own routine account and order questions.", "Define the support role and escalation route.", "Name one primary and one backup staff contact.", "Before packet production", "Not started", "Names and preferred contact details"],
  [2, "Access and privacy boundaries", "Never request or view a customer password; use approved account and order tools only.", "Written access, verification, and privacy rules.", "Confirm which staff members receive WordPress, WooCommerce, Revel, or support access.", "Before access is granted", "Not started", "Approved access list"],
  [3, "Guest checkout and optional accounts video", "Explain that guest checkout requires no account and that customer accounts are optional and automatic.", "Short screen-recorded walkthrough plus written summary.", "Review the customer-facing process and confirm it matches store policy.", "Before customer training", "Not started", "Video and guide links"],
  [4, "Password reset assistance", "Direct customers to the standard reset flow, confirm the generic success message, and escalate delivery failures.", "Video, quick-reference steps, and a customer-safe response script.", "Confirm the public support phone/email route staff will use.", "Before launch", "Not started", "Video, script, and test result"],
  [5, "Sign-in troubleshooting", "Check basic issues such as the correct email and reset-link timing without handling passwords.", "One-page troubleshooting guide and escalation criteria.", "Use the guide; escalate lockouts, repeated failures, or suspected security issues.", "Before launch", "Not started", "Guide link"],
  [6, "Order lookup and status questions", "Locate an order, explain the visible status, and share approved next steps.", "Video and written order-support procedure.", "Confirm who may view orders and which statuses staff may explain or change.", "Before launch", "Not started", "Video, procedure, and access test"],
  [7, "Changes, cancellations, refunds, and exceptions", "Follow the approved decision path rather than promising an outcome.", "Escalation chart and approved response templates.", "Approve store rules, authorized roles, and financial limits.", "After policies are approved", "Not started", "Policy and escalation links"],
  [8, "Revel product maintenance and online eligibility", "Maintain approved names, prices, SKUs, weights, stock, and online-eligibility settings without breaking synchronization.", "Video, field-ownership guide, and do-not-change list based on integration testing.", "Designate the staff member who maintains Revel product data.", "After one-product proof", "Not started", "Video, field guide, and staff name"],
  [9, "Fulfillment, packing, and shipping", "Process online orders using approved packing, label, status, and exception steps.", "Recorded workflow and written daily procedure.", "Confirm packing staff, supplies, cutoffs, and exception handling.", "After shipping configuration", "Not started", "Workflow, test order, and procedure"],
  [10, "Daily operations checklist", "Review new orders, exceptions, failed payments, low stock, and unresolved support items.", "Printable and digital checklist.", "Confirm who performs each check and the expected schedule.", "Before launch", "Not started", "Completed checklist template"],
  [11, "Escalation matrix and support contacts", "Know what staff handles, what Blue Nova handles, and what must go to Revel, Kosmos, shipping, or payment support.", "One-page escalation matrix with contact paths and required evidence.", "Approve internal contacts and keep vendor account details current in an approved secure location.", "Before launch", "Not started", "Escalation matrix link"],
  [12, "Live staff walkthrough and questions", "Practice the account, order, fulfillment, refund, and escalation workflows using test cases.", "Focused training session, test scenarios, and Q&A notes.", "Ensure designated staff attend and identify any missing instructions.", "Prelaunch training gate", "Not started", "Attendee list and open-question log"],
  [13, "Welcome packet delivery and acknowledgment", "Know where the final videos, written instructions, checklists, and support contacts are stored.", "Deliver the organized welcome packet and final handoff index.", "Confirm receipt, access, designated staff, and acceptance of the handoff materials.", "Required before controlled launch", "Not started", "Packet link and client acknowledgment"],
];

handoff.getRange("A5:H18").values = rows;
handoff.getRange("A5:H5").format = {
  fill: colors.header,
  font: { name: "Carlito", size: 10, bold: true, color: colors.ink },
  wrapText: true,
  verticalAlignment: "center",
  rowHeight: 34,
};
handoff.getRange("A6:H18").format = {
  font,
  wrapText: true,
  verticalAlignment: "top",
  rowHeight: 78,
};
handoff.getRange("A6:A18").format.horizontalAlignment = "center";
handoff.getRange("A:A").format.columnWidth = 7;
handoff.getRange("B:B").format.columnWidth = 30;
handoff.getRange("C:C").format.columnWidth = 43;
handoff.getRange("D:D").format.columnWidth = 40;
handoff.getRange("E:E").format.columnWidth = 43;
handoff.getRange("F:F").format.columnWidth = 24;
handoff.getRange("G:G").format.columnWidth = 17;
handoff.getRange("H:H").format.columnWidth = 34;
handoff.getRange("G6:G30").dataValidation = {
  rule: { type: "list", values: ["Not started", "In progress", "Complete", "Not applicable"] },
};
applyStatusRules(handoff.getRange("G6:G30"));
handoff.tables.add("A5:H18", true, "PhaseTwoTrainingHandoff").style = "TableStyleMedium2";

const checks = [];
checks.push(await workbook.inspect({
  kind: "table",
  sheetId: "Training & Handoff",
  range: "A1:H18",
  include: "values,formulas",
  tableMaxRows: 20,
  tableMaxCols: 8,
  maxChars: 22000,
}));
checks.push(await workbook.inspect({
  kind: "table",
  sheetId: "QA & Launch Gates",
  range: "A29:G33",
  include: "values,formulas",
  tableMaxRows: 6,
  tableMaxCols: 7,
  maxChars: 6000,
}));
checks.push(await workbook.inspect({
  kind: "table",
  sheetId: "Implementation Plan",
  range: "A15:F17",
  include: "values,formulas",
  tableMaxRows: 4,
  tableMaxCols: 6,
  maxChars: 5000,
}));
for (const [index, check] of checks.entries()) console.log(`CHECK_${index + 1}\n${check.ndjson}`);

for (const sheetName of ["Training & Handoff", "QA & Launch Gates", "Implementation Plan"]) {
  const preview = await workbook.render({ sheetName, autoCrop: "all", scale: 1.1, format: "png" });
  const safe = sheetName.toLowerCase().replace(/[^a-z0-9]+/g, "-").replace(/^-|-$/g, "");
  await fs.writeFile(path.join(previewDir, `${safe}.png`), new Uint8Array(await preview.arrayBuffer()));
}

const errors = await workbook.inspect({
  kind: "match",
  searchTerm: "#REF!|#DIV/0!|#VALUE!|#NAME\\?|#N/A",
  options: { useRegex: true, maxResults: 300 },
  summary: "final formula error scan",
});
console.log(`FORMULA_ERRORS\n${errors.ndjson}`);

const output = await SpreadsheetFile.exportXlsx(workbook);
await output.save(outputPath);
console.log(`OUTPUT ${outputPath}`);
