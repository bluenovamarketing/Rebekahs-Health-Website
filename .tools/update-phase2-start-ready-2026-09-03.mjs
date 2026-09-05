import fs from "node:fs/promises";
import { fileURLToPath } from "node:url";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const sourcePath = fileURLToPath(
  new URL(
    "../outputs/01a034a3-641b-70f2-bc46-4a4f5cf27673/Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx",
    import.meta.url,
  ),
);
const outputPath = fileURLToPath(
  new URL(
    "../outputs/01a062b4-9ceb-7123-8739-1154f7acbf63/Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx",
    import.meta.url,
  ),
);
const previewDir = fileURLToPath(
  new URL(
    "../outputs/01a062b4-9ceb-7123-8739-1154f7acbf63/work/start-ready-previews/",
    import.meta.url,
  ),
);

await fs.mkdir(previewDir, { recursive: true });

const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(sourcePath));

const decisions = workbook.worksheets.getItem("Decisions & Access");
decisions.getRange("A2").values = [[
  "Updated through September 3, 2026. Revel Clarkston console access and the client-owned Kosmos dashboard login are operational. The local pre-staging packet, rollback plan, acceptance test, mockups, and dormant implementation scaffold are prepared. Rebekah's exact Shop/Catalog v1.9 approval remains the start gate; Clover/Fiserv and USPS developer access remain later service-specific test gates.",
]];
decisions.getRange("C23:D23").values = [[
  "Blue Nova's separate user reaches the Rebekah's - Clarkston console and can open Products, Inventory, and Settings; the specific Kosmos/API handshake remains for the one-product proof",
  "Confirmed",
]];
decisions.getRange("F23:G23").values = [[
  "Blue Nova",
  "Use the verified Clarkston access during the controlled one-product proof and document the actual handshake and field permissions.",
]];
decisions.getRange("C24:G24").values = [[
  "Client-owned Kosmos dashboard login verified; no synchronization Actions have been created",
  "Confirmed",
  "Integration",
  "Rebekah / Blue Nova",
  "After client approval and protected staging, Blue Nova creates the WooCommerce key/secret and performs the first connection test.",
]];
decisions.getRange("C27:G27").values = [[
  "Local staging packet, rollback plan, acceptance test, and dormant implementation scaffold are complete; no independent staging exists yet",
  "Future gate",
  "Implementation and realistic testing",
  "Blue Nova / Rebekah",
  "After Rebekah approves Shop/Catalog v1.9 and Todd authorizes the start: upgrade to 4 GB / 2 vCPU, take a fresh restore point, then create and secure the staging clone.",
]];
decisions.getRange("C28:G28").values = [[
  "Awaiting Rebekah's exact Shop/Catalog v1.9 approval and Todd's confirmation of the safe working window",
  "Needs approval",
  "Staging, synchronization, and handoff",
  "Todd / Rebekah",
  "Confirm the start window immediately after client approval; do not leave active work across a blackout period or weekend.",
]];

const implementation = workbook.worksheets.getItem("Implementation Plan");
implementation.getRange("E12:F16").values = [
  [
    "Receive Rebekah's exact Shop/Catalog v1.9 approval, confirm the safe working window, and obtain Todd's hosting-scale authorization. Revel and Kosmos access are confirmed.",
    "Exact client approval and start authorization recorded",
  ],
  [
    "Upgrade hosting to 4 GB / 2 vCPU, take a fresh restore point, create and secure the Cloudways staging clone, prevent indexing/email/payment side effects, and prepare a clean staging catalog while preserving the legacy products.",
    "Verified safe staging, rollback evidence, and isolated legacy catalog",
  ],
  [
    "Connect Revel → Kosmos → WooCommerce; test one representative product and document eligibility, identifier, price, inventory, category, weight, variation, content-overwrite, logging, and field-ownership behavior.",
    "Documented one-product pass and actual mapping ownership",
  ],
  [
    "If the one-product proof passes, synchronize and reconcile the remaining 24 products, audit content, resolve one consolidated gap list, apply approved templates, and finalize taxonomy/merchandising.",
    "Reconciled 25-product catalog and approved content",
  ],
  [
    "Configure approved shipping, tax, accounts, inventory, emails, policies, labels/packages, and fulfillment behavior. Add Clover/Fiserv and USPS live-rate testing only when their separate access is ready.",
    "Operational configuration complete; service-specific tests documented when available",
  ],
];

const scope = workbook.worksheets.getItem("Scope & Costs");
scope.getRange("C7:F7").values = [[
  "All seven reusable responsive systems are internally approved; Rebekah approved six exact systems and Shop/Catalog v1.9 remains the sole design-approval gate",
  "Blue Nova / Todd / Rebekah",
  "Current pre-staging phase",
  "Awaiting final client approval",
]];

const preStaging = workbook.worksheets.getItem("Pre-Staging Packet");
preStaging.getRange("C12:F12").values = [[
  "Access verified; client approval and working window pending",
  "Blue Nova Revel access and the client-owned Kosmos dashboard login are verified. No connection or data pull has started.",
  "Access records, support model, runbook, and one-product acceptance test are documented.",
  "Rebekah approves exact Shop/Catalog v1.9; Todd confirms the safe window and authorizes hosting scale and staging creation.",
]];

const checks = {
  decisions: decisions.getRange("A1:G28").values,
  implementation: implementation.getRange("A12:F16").values,
  scope: scope.getRange("A4:G12").values,
  preStaging: preStaging.getRange("A5:H16").values,
};

const formulaErrors = await workbook.inspect({
  kind: "match",
  searchTerm: "#REF!|#DIV/0!|#VALUE!|#NAME\\?|#N/A",
  options: { useRegex: true, maxResults: 300 },
  maxChars: 6000,
});

for (const sheet of workbook.worksheets.items) {
  const preview = await workbook.render({
    sheetName: sheet.name,
    autoCrop: "all",
    scale: 1,
    format: "png",
  });
  const safeName = sheet.name.replace(/[^a-z0-9]+/gi, "-").replace(/^-|-$/g, "");
  await fs.writeFile(
    `${previewDir}/${safeName}.png`,
    new Uint8Array(await preview.arrayBuffer()),
  );
}

await fs.mkdir(new URL("../outputs/01a062b4-9ceb-7123-8739-1154f7acbf63/", import.meta.url), {
  recursive: true,
});
const output = await SpreadsheetFile.exportXlsx(workbook);
await output.save(outputPath);

console.log(JSON.stringify({ outputPath, checks, formulaErrors: formulaErrors.ndjson }, null, 2));
