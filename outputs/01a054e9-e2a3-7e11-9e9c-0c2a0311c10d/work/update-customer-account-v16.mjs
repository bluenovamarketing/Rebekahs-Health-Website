import fs from "node:fs/promises";
import { fileURLToPath } from "node:url";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const workbookPath = fileURLToPath(new URL("../Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx", import.meta.url));
const previewDir = new URL("./after-v16/", import.meta.url);
await fs.mkdir(previewDir, { recursive: true });
const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(workbookPath));

const updates = {
  "Approval Set": {
    C11: "Guest checkout, automatic optional account creation, sign-in, password recovery, dashboard, addresses, order history, order detail, and clearly separated review guidance",
    H11: "Approve the customer experience now: guest checkout stays available during checkout; customers create optional accounts without staff approval; standard email, password, and recovery are used; customer 2FA is not planned. Staff/administrator backend security is configured and tested separately before launch.",
    I11: "Noindex account screens; privacy and consent; standard sign-in and password recovery, clear field labels, order access, address editing, and customer-safe support guidance.",
    J11: "Review exact v1.6 page-body-only mockup: customer-account-system-v1.6.html. It removes customer 2FA and the customer-verification add-on dependency, clarifies that guest checkout appears during checkout because it creates no account, and labels implementation expectations as review guidance rather than live-page content. v1.5 is preserved as superseded."
  },
  "Templates & Global": {
    C11: "Login, optional registration, password reset, dashboard, addresses, and orders",
    D11: "Approve representative states, guest checkout, and optional-account presentation",
    E11: "One compact customer shell. Guest checkout remains available during checkout; optional customer accounts are automatic and use the standard email, password, and reset flow. No customer 2FA add-on is planned. Staff/administrator backend protection is handled separately."
  },
  "Decisions & Access": {
    C12: "Guest checkout allowed; optional customer accounts use standard email/password access; no customer 2FA; staff/admin backend protection is separate",
    G12: "Configure and test guest checkout, optional accounts, password reset, email delivery, consent, and privacy on staging. Configure staff/administrator backend protection separately from the customer flow."
  },
  "Implementation Plan": {
    E10: "Create guest checkout, automatic optional accounts, sign-in, password reset, dashboard, addresses, and order states. Do not add customer 2FA. Configure staff/administrator backend protection as a separate staging security task.",
    F10: "Approved system 06 after exact-version review; staff/admin backend security configured and tested separately"
  },
  "QA & Launch Gates": {
    C19: "Guest checkout works without an account. Optional account creation, consent, login, password reset, addresses, and order history all work. Customer 2FA is not shown or required. Staff/administrator backend protection is tested separately.",
    G19: "Record guest checkout, optional-account creation, sign-in, reset-email, address, order-history, accessibility, and separate staff/admin backend-security evidence."
  },
  "Scope & Costs": {
    B8: "WooCommerce core customer accounts",
    C8: "$0 additional customer-account software planned; guest checkout and standard My Account features are included with WooCommerce core",
    F8: "Included",
    G8: "No customer 2FA extension or customer-verification purchase is planned. Staff/administrator backend protection is a separate staging security task; confirm the approved compatible method and any cost before launch."
  },
  "Version Register": {
    C11: "v1.6",
    D11: "customer-account-system-v1.6.html",
    F11: "Revision direction approved; exact v1.6 awaiting review",
    H11: "v1.7",
    I11: "Exact v1.6 is the current internal-review version. It removes the unapproved customer 2FA screen and add-on dependency, restores standard optional WooCommerce accounts, clarifies that guest checkout appears during checkout because it creates no account, and separates staff/administrator backend security from the customer experience. v1.5 remains preserved as superseded history."
  },
  "Training & Handoff": {
    C7: "Never request or view a customer password. Keep staff/administrator passwords and backend verification codes private; use approved account, order, reset, and escalation procedures only.",
    D7: "Written access, privacy, backend-security, and escalation rules.",
    B8: "Guest checkout and optional accounts video",
    C8: "Explain that guest checkout creates no account, optional accounts are automatic, and customers use the standard email, password, and reset flow without customer 2FA.",
    D8: "Short screen-recorded walkthrough plus written account setup, sign-in, and recovery summary.",
    H8: "Video, written guide, and tested reset path",
    C9: "Direct customers to the standard reset flow, confirm the generic success message, and escalate missing reset emails, repeated failures, or lockouts.",
    D9: "Video, quick-reference password-reset steps, and a customer-safe response script.",
    C10: "Check only customer-safe basics such as the correct email, reset-link timing, and browser steps. Never handle a customer's password.",
    D10: "One-page sign-in, reset, lockout, and escalation guide.",
    E10: "Use the guide; escalate repeated failures, lockouts, missing reset emails, or suspected security issues."
  }
};

for (const [sheetName, cells] of Object.entries(updates)) {
  const sheet = workbook.worksheets.getItem(sheetName);
  for (const [address, value] of Object.entries(cells)) sheet.getRange(address).values = [[value]];
}

workbook.worksheets.getItem("Scope & Costs").getRange("F8").format = {
  fill: "#DCFCE7",
  font: { color: "#166534", bold: true }
};

for (const [sheetName, range] of [
  ["Approval Set", "A11:J11"],
  ["Templates & Global", "A11:E11"],
  ["Decisions & Access", "A12:G12"],
  ["Implementation Plan", "A10:F10"],
  ["QA & Launch Gates", "A19:G19"],
  ["Scope & Costs", "A8:G8"],
  ["Version Register", "A11:I11"],
  ["Training & Handoff", "A7:H10"]
]) workbook.worksheets.getItem(sheetName).getRange(range).format.wrapText = true;

const checks = [];
for (const [sheetName, range] of [
  ["Approval Set", "A10:J12"],
  ["Decisions & Access", "A11:G13"],
  ["Scope & Costs", "A7:G9"],
  ["Version Register", "A10:I12"],
  ["Training & Handoff", "A5:H11"]
]) {
  checks.push((await workbook.inspect({ kind: "table", sheetId: sheetName, range, include: "values,formulas", tableMaxRows: 12, tableMaxCols: 10, maxChars: 12000 })).ndjson);
}
console.log(checks.join("\n"));

const errors = await workbook.inspect({
  kind: "match",
  searchTerm: "#REF!|#DIV/0!|#VALUE!|#NAME\\?|#N/A",
  options: { useRegex: true, maxResults: 300 },
  summary: "final formula error scan",
  maxChars: 5000
});
console.log(errors.ndjson);

const oldCustomer2fa = await workbook.inspect({
  kind: "match",
  searchTerm: "required customer 2FA|mandatory customer 2FA|customer accounts require mandatory 2FA|every customer account requires two-step verification|2FA-recovery|code-delivery delay",
  options: { useRegex: true, maxResults: 100 },
  maxChars: 10000
});
console.log(oldCustomer2fa.ndjson);

for (const name of [
  "Approval Set",
  "Templates & Global",
  "Product & Content",
  "Decisions & Access",
  "Implementation Plan",
  "QA & Launch Gates",
  "Scope & Costs",
  "Version Register",
  "Training & Handoff"
]) {
  const blob = await workbook.render({ sheetName: name, autoCrop: "all", scale: 0.7, format: "png" });
  await fs.writeFile(new URL(`${name.replaceAll(" ", "-")}.png`, previewDir), new Uint8Array(await blob.arrayBuffer()));
}

const output = await SpreadsheetFile.exportXlsx(workbook);
await output.save(workbookPath);
console.log(workbookPath);
