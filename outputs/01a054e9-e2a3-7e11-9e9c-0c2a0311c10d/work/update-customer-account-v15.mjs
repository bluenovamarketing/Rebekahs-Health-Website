import fs from "node:fs/promises";
import path from "node:path";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const projectDir = "C:/Users/todda/Blue Nova Projects/Rebekahs Health Website";
const outputDir = path.join(projectDir, "outputs/01a054e9-e2a3-7e11-9e9c-0c2a0311c10d");
const workbookPath = path.join(outputDir, "Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx");
const previewDir = path.join(outputDir, "work/customer-account-v15");
const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(workbookPath));

const approval = workbook.worksheets.getItem("Approval Set");
approval.getRange("B11:C11").values = [[
  "Customer Account System",
  "Guest checkout, automatic optional account creation, sign-in, password recovery, required two-step verification, dashboard, addresses, order history, order detail, and client expectation guidance",
]];
approval.getRange("E11:J11").values = [[
  "In review",
  "Local page-body mockup",
  "Medium",
  "Approve the customer experience and security rule now: guest checkout stays available; customers create optional accounts without staff approval; every customer and staff/admin account requires two-step verification. The compatible extension, verification method, code delivery, backup/recovery path, privacy wording, order sync, testing result, and any added cost are selected and tested before staging.",
  "Noindex account screens; privacy and consent; required 2FA setup, code verification, recovery security, clear field labels, order access, address editing, and customer-safe support guidance.",
  "Review exact v1.5 page-body-only mockup: customer-account-system-v1.5.html. No additional client information is needed for this design review. Before staging, select and approve the compatible 2FA extension, method, delivery/recovery process, test plan, and any cost. v1.4 is preserved as superseded.",
]];

const templates = workbook.worksheets.getItem("Templates & Global");
templates.getRange("B11:E11").values = [[
  "Customer account shell",
  "Login, optional registration, required 2FA setup/sign-in/recovery, reset, dashboard, addresses, and orders",
  "Approve representative states and mandatory 2FA rule",
  "One compact shell. Guest checkout remains available; optional customer accounts are automatic but require 2FA. Staff/admin accounts also require 2FA. Select and test the compatible extension, method, delivery, recovery, and any cost before staging.",
]];

const decisions = workbook.worksheets.getItem("Decisions & Access");
decisions.getRange("C12:G12").values = [[
  "Guest checkout allowed; optional customer accounts require mandatory 2FA; staff/admin accounts also require 2FA",
  "Confirmed",
  "Checkout, account system, security, and training",
  "Rebekah / Blue Nova",
  "Before staging, select and test the compatible extension, verification method, frontend setup, code delivery, backup/recovery path, enforcement, accessibility, privacy wording, and any paid-tier cost.",
]];

const implementation = workbook.worksheets.getItem("Implementation Plan");
implementation.getRange("E10:F10").values = [[
  "Create guest checkout, automatic optional accounts, sign-in, required customer and staff/admin 2FA setup/verification/recovery, password reset, dashboard, addresses, and order states. Keep the approved security requirement separate from the later extension, method, testing, and cost decision.",
  "Approved system 06; compatible 2FA implementation selected and tested before staging",
]];

const qa = workbook.worksheets.getItem("QA & Launch Gates");
qa.getRange("C19:G19").values = [[
  "Guest checkout works without an account. Optional account creation, consent, login, password reset, required customer 2FA enrollment/code verification/recovery, staff/admin enforcement, addresses, and order history all work.",
  "Customer journey + security QA",
  "Blue Nova",
  "Not started",
  "Record guest-path, enrollment, successful/failed code, recovery, lockout, staff/admin enforcement, accessibility, and email/code-delivery evidence.",
]];

const scope = workbook.worksheets.getItem("Scope & Costs");
scope.getRange("B8:G8").values = [[
  "WooCommerce core + required 2FA extension",
  "$0 WooCommerce core account features; mandatory customer 2FA requires a compatible extension and may require a paid tier",
  "Existing project / Rebekah",
  "Before protected staging",
  "Pending decision",
  "Core has no built-in mandatory 2FA. Approve method, extension, recovery, compatibility, and cost before staging. Sources: https://developer.woocommerce.com/docs/best-practices/security/security-best-practices and https://melapress.com/support/kb/wp-2fa-configure-2fa-policies-enforce/",
]];

const training = workbook.worksheets.getItem("Training & Handoff");
training.getRange("C7:D7").values = [[
  "Never request or view a customer password or verification code; use approved account, order, 2FA-recovery, and escalation procedures only.",
  "Written access, verification-code, privacy, and escalation rules.",
]];
training.getRange("B8:H8").values = [[
  "Guest checkout, optional accounts, and 2FA video",
  "Explain that guest checkout needs no account, optional accounts are automatic, and every customer account requires two-step verification.",
  "Short screen-recorded walkthrough plus written setup, sign-in, and recovery summary.",
  "Review the customer-facing process and confirm it matches store policy.",
  "Before customer training",
  "Not started",
  "Video, written guide, and tested recovery path",
]];
training.getRange("C9:D9").values = [[
  "Direct customers to the standard reset flow, explain that password reset does not bypass 2FA, confirm the generic success message, and escalate delivery or lockout failures.",
  "Video, quick-reference reset and 2FA-recovery steps, and a customer-safe response script.",
]];
training.getRange("C10:E10").values = [[
  "Check only customer-safe basics such as the correct email, reset-link timing, code-delivery delay, and approved recovery steps. Never handle a customer's password or code.",
  "One-page sign-in, code-delivery, recovery, lockout, and escalation guide.",
  "Use the guide; escalate repeated failures, lockouts, missing codes, recovery problems, or suspected security issues.",
]];

const versions = workbook.worksheets.getItem("Version Register");
versions.getRange("C11:I11").values = [[
  "v1.5",
  "customer-account-system-v1.5.html",
  "Internal Review",
  "Revision requested by Todd; required 2FA added",
  "Not reviewed",
  "v1.6",
  "Exact v1.5 is the current internal-review version. It preserves guest checkout and automatic optional accounts while requiring 2FA for every customer and staff/admin account. It adds code-verification and recovery states plus staff privacy boundaries. The exact compatible extension, method, delivery/recovery process, testing result, and any cost remain pre-staging implementation decisions.",
]];

for (const [sheet, ranges] of [
  [approval, ["A11:J11"]],
  [templates, ["A11:E11"]],
  [decisions, ["A12:G12"]],
  [implementation, ["A10:F10"]],
  [qa, ["A19:G19"]],
  [scope, ["A8:G8"]],
  [training, ["A7:H10"]],
  [versions, ["A11:I11"]],
]) {
  for (const range of ranges) sheet.getRange(range).format.autofitRows();
}

for (const [sheetId, range] of [
  ["Approval Set", "A10:J12"],
  ["Templates & Global", "A10:E12"],
  ["Decisions & Access", "A11:G13"],
  ["Implementation Plan", "A9:F11"],
  ["QA & Launch Gates", "A18:G20"],
  ["Scope & Costs", "A7:G9"],
  ["Training & Handoff", "A7:H10"],
  ["Version Register", "A9:I12"],
]) {
  const check = await workbook.inspect({
    kind: "table",
    sheetId,
    range,
    include: "values,formulas",
    tableMaxRows: 8,
    tableMaxCols: 10,
    tableMaxCellChars: 500,
    maxChars: 12000,
  });
  console.log(`CHECK ${sheetId} ${range}`);
  console.log(check.ndjson);
}

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
