import fs from "node:fs/promises";
import path from "node:path";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const root = "C:/Users/todda/Blue Nova Projects/Rebekahs Health Website";
const outputDir = path.join(root, "outputs/01a034a3-641b-70f2-bc46-4a4f5cf27673");
const workbookPath = path.join(outputDir, "Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx");
const previewDir = path.join(outputDir, "work/client-approval-and-catalog-v19");
const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(workbookPath));

if (process.argv.includes('--inspect-final') || process.argv.includes('--final-approval')) {
  const views = [['Approval Set', 'C3:J8'], ['Implementation Plan', 'A2:F7'], ['Version Register', 'C3:I8'], ['Pre-Staging Packet', 'C3:H6']];
  const finalDir = path.join(outputDir, 'work/final-approval');
  await fs.mkdir(finalDir, { recursive: true });
  if (process.argv.includes('--inspect-final')) {
    for (const [name, range] of views) {
      console.log((await workbook.inspect({kind:'table',sheetId:name,range,include:'values,formulas',tableMaxRows:8,tableMaxCols:10,maxChars:12000})).ndjson);
      const p = await workbook.render({sheetName:name,range,scale:1,format:'png'});
      await fs.writeFile(path.join(finalDir, `${name}-before.png`), new Uint8Array(await p.arrayBuffer()));
    }
    process.exit(0);
  }
  const source = 'https://mail.google.com/mail/#all/1a069170a83b3a6e';
  const changes = {
    'Approval Set': {
      F3:0, H3:7, J3:'Authorize infrastructure', E8:'Client Approved',
      J8:`Exact Shop/Catalog v1.9 approved by Todd and Rebekah on September 3, 2026. Her reply to the exact-version request says Looks great, thank you. No further changes requested. Actual product mappings remain connected-data validation. Source: ${source}`,
    },
    'Implementation Plan': {
      A2:'All seven exact systems are internally and client approved. Rebekah confirmed Shop/Catalog v1.9 on September 3, 2026. Local readiness documents and dormant scaffold are complete. Infrastructure authorization, working window, and connection testing remain separate gates.',
      C7:'Client approved', F7:'Exact version approved September 3, 2026',
    },
    'Version Register': {
      F3:0, H3:7, I3:'Design approved; infrastructure authorization next', E8:'Client Approved', G8:'Approved by Rebekah 2026-09-03',
      I8:`Exact v1.9 approved by Todd and Rebekah on September 3, 2026. Two expandable groups preserve the supplied nine brands and 21 wellness categories. Actual pilot choices and mappings remain connected-data validation. v1.8 remains superseded history. Source: ${source}`,
    },
    'Pre-Staging Packet': {
      F3:0, C6:'Client approved', D6:'All seven exact systems are internally and client approved. Shop/Catalog v1.9 was confirmed September 3, 2026.',
      E6:'Exact-version email reply verified. No further design changes requested.', F6:'Design gate complete. Obtain infrastructure authorization and confirm a safe working window.',
      H6:source,
    },
  };
  const before = workbook.worksheets.items.map(s=>({name:s.name,values:JSON.stringify(s.getUsedRange().values),formulas:JSON.stringify(s.getUsedRange().formulas)}));
  for (const [name, cells] of Object.entries(changes)) for (const [address,value] of Object.entries(cells)) workbook.worksheets.getItem(name).getRange(address).values=[[value]];
  for(const [name,address] of [['Implementation Plan','C7'],['Version Register','E8'],['Version Register','G8'],['Pre-Staging Packet','C6']]) {
    const r=workbook.worksheets.getItem(name).getRange(address); r.format.fill='D9EFD9';r.format.font={color:'195C3B',bold:true};
  }
  workbook.worksheets.getItem('Approval Set').getRange('A8:J8').format.rowHeight=130;
  workbook.worksheets.getItem('Version Register').getRange('A8:I8').format.rowHeight=115;
  for(const old of before){const s=workbook.worksheets.getItem(old.name);if(JSON.stringify(s.getUsedRange().formulas)!==old.formulas)throw new Error(`Formula change: ${old.name}`);if(!changes[old.name]&&JSON.stringify(s.getUsedRange().values)!==old.values)throw new Error(`Unrelated sheet change: ${old.name}`);}
  console.log((await workbook.inspect({kind:'match',searchTerm:'#REF!|#DIV/0!|#VALUE!|#NAME\\?|#N/A|#NUM!|#NULL!|#SPILL!|#CALC!',options:{useRegex:true,maxResults:100},maxChars:3000})).ndjson);
  for (const [name, range] of views) {
    const p=await workbook.render({sheetName:name,range,scale:1,format:'png'});
    await fs.writeFile(path.join(finalDir,`${name}-after.png`),new Uint8Array(await p.arrayBuffer()));
    console.log(name,JSON.stringify(Object.fromEntries(Object.keys(changes[name]).map(a=>[a,workbook.worksheets.getItem(name).getRange(a).values]))));
  }
  await (await SpreadsheetFile.exportXlsx(workbook)).save(workbookPath);
  console.log('Saved final approval. Unrelated sheet values and all formulas preserved.');
  process.exit(0);
}

const greenFill = "D9EFD9";
const greenText = "195C3B";
const goldFill = "F6E8B1";
const goldText = "6B501C";

function statusStyle(range, fill, color) {
  range.format.fill = fill;
  range.format.font = { color, bold: true };
}

const approval = workbook.worksheets.getItem("Approval Set");
approval.getRange("C3:J3").values = [[
  "Internally approved", 7, "Awaiting client", 1, "Client approved", 6, "Current gate", "Rebekah confirms exact Shop/Catalog v1.9",
]];
for (const row of [6, 7, 9, 10, 11, 12]) {
  approval.getRange(`E${row}`).values = [["Client Approved"]];
  statusStyle(approval.getRange(`E${row}`), greenFill, greenText);
}
approval.getRange("E8").values = [["Internally Approved"]];
statusStyle(approval.getRange("E8"), greenFill, greenText);
approval.getRange("C8").values = [[
  "Store introduction, four illustrated wellness-goal paths, personal guidance, catalog search, sorting, six sample products, pagination, no-results recovery, the approved missing-photo component, and two expandable filter groups for Brands and Wellness Categories",
]];
approval.getRange("H8").values = [[
  "Use /shop/ as the store homepage and full catalog. The client-requested v1.9 filter revision uses exactly two compact expandable groups on desktop and mobile. Actual choices enabled for the 25-product pilot and each product's mapping remain connected-data validation.",
]];
approval.getRange("J6:J12").values = [[
  "Exact Header + Footer Ecommerce Add-On v1.5 was approved by Todd and approved by Rebekah on September 2, 2026. Production Online Store links will use /shop/. Any later design change requires v1.6 and new exact-version approval.",
], [
  "Exact Main Homepage Ecommerce Integration v1.9 was approved by Todd and approved by Rebekah on September 2, 2026. Its Shop Online control routes directly to the separate Shop page, satisfying the requirement repeated in Rebekah's email.",
], [
  "Shop Homepage + Product Catalog v1.9 was approved internally by Todd on September 3, 2026 and now awaits Rebekah's exact-version confirmation. It preserves v1.8 and replaces the former product-form and price filters with two expandable groups containing Rebekah's nine brands and 21 wellness categories on desktop and mobile. Real product mappings remain a later Revel/Kosmos validation gate.",
], [
  "Exact Product Page Templates v1.7 was approved by Todd and approved by Rebekah on September 2, 2026. Connected product data, real photography, and verified source content remain separate implementation-validation gates.",
], [
  "Exact Purchase Path v1.6 was approved by Todd and approved by Rebekah on September 2, 2026. Payment, shipping, tax, email, inventory, order, and provider behavior remain connected-service validation gates.",
], [
  "Exact Customer Account System v1.6 was approved by Todd and approved by Rebekah on September 2, 2026. Staff/admin backend security and all connected-service behavior remain separate staging and implementation-validation gates.",
], [
  "Exact Store States + Components v1.5 was approved by Todd and approved by Rebekah on September 2, 2026. Provider-specific messages remain connected-service implementation-validation inputs.",
]];

const implementation = workbook.worksheets.getItem("Implementation Plan");
implementation.getRange("A2").values = [[
  "All seven exact systems are internally approved. Rebekah approved six on September 2, 2026; exact Shop/Catalog v1.9 now awaits only her confirmation. Local readiness documents and the dormant WooCommerce scaffold remain complete; external and staging work is still separately gated.",
]];
for (const row of [5, 6, 8, 9, 10, 11]) {
  implementation.getRange(`C${row}`).values = [["Client approved"]];
  implementation.getRange(`F${row}`).values = [["Exact version approved September 2, 2026"]];
  statusStyle(implementation.getRange(`C${row}`), greenFill, greenText);
}
implementation.getRange("C7").values = [["Awaiting client approval"]];
implementation.getRange("E7:F7").values = [[
  "Exact Shop Homepage + Product Catalog v1.9 was approved by Todd on September 3, 2026 with Rebekah's two expandable filter groups; product mappings remain a later connected-data task.",
  "Rebekah confirms exact v1.9",
]];
statusStyle(implementation.getRange("C7"), goldFill, goldText);

const versions = workbook.worksheets.getItem("Version Register");
versions.getRange("E3:I3").values = [[
  "Awaiting client", 1, "Client approved", 6, "Gate: Rebekah confirms exact Shop v1.9",
]];
for (const row of [6, 7, 9, 10, 11, 12]) {
  versions.getRange(`E${row}`).values = [["Client Approved"]];
  versions.getRange(`G${row}`).values = [["Approved by Rebekah 2026-09-02"]];
  statusStyle(versions.getRange(`E${row}`), greenFill, greenText);
  statusStyle(versions.getRange(`G${row}`), greenFill, greenText);
}
versions.getRange("C8:I8").values = [[
  "v1.9",
  "shop-catalog-template-v1.9.html",
  "Awaiting Client Approval",
  "Approved by Todd 2026-09-03",
  "Pending Rebekah",
  "v1.10",
  "Client-requested revision internally approved by Todd on September 3, 2026. Preserves v1.8 and uses exactly two compact expandable filter groups—Brands and Wellness Categories—with nine supplied brand choices and 21 supplied wellness categories on desktop and mobile. Actual pilot availability and per-product mappings remain connected-data validation. v1.8 is preserved as superseded client-reviewed history.",
]];
statusStyle(versions.getRange("E8"), goldFill, goldText);
statusStyle(versions.getRange("G8"), goldFill, goldText);
versions.getRange("I6:I12").values = [[
  "Exact v1.5 approved by Todd and Rebekah on September 2, 2026. Production Online Store links will use /shop/. Any later design change requires v1.6 and new exact-version approval.",
], [
  "Exact v1.9 approved by Todd and Rebekah on September 2, 2026. The Shop Online control routes directly to the separate Shop page. Any later design change requires v1.10 and new approval.",
], [
  "Client-requested revision internally approved by Todd on September 3, 2026. Preserves v1.8 and uses exactly two compact expandable filter groups—Brands and Wellness Categories—with nine supplied brand choices and 21 supplied wellness categories on desktop and mobile. Actual pilot availability and per-product mappings remain connected-data validation. v1.8 is preserved as superseded client-reviewed history.",
], [
  "Exact v1.7 approved by Todd and Rebekah. Connected product data, photography, and verified source content remain implementation-validation gates. Any later design change requires v1.8 and new approval.",
], [
  "Exact v1.6 approved by Todd and Rebekah. Connected payment, shipping, tax, email, inventory, order, and provider behavior remain implementation-validation gates. Any later design change requires v1.7 and new approval.",
], [
  "Exact v1.6 approved by Todd and Rebekah. Staff/admin security and connected-service behavior remain later staging validation. Any later design change requires v1.7 and new approval.",
], [
  "Exact v1.5 approved by Todd and Rebekah. Provider-specific messages remain later validation inputs. Any later design change requires v1.6 and new approval.",
]];

const packet = workbook.worksheets.getItem("Pre-Staging Packet");
packet.getRange("E3:H3").values = [["Pending design", 1, "Gated later", 5]];
packet.getRange("C6:H6").values = [[
  "Pending exact v1.9 client approval",
  "All seven exact systems are internally approved; six are client approved and Shop/Catalog v1.9 awaits Rebekah's confirmation.",
  "Review hub and version register isolate exact v1.9 as the only remaining design-approval gate.",
  "Rebekah confirms exact Shop/Catalog v1.9.",
  "Rebekah",
  "phase-two-ecommerce-mockup-sheet.html; shop-catalog-template-v1.9.html",
]];
statusStyle(packet.getRange("C6"), goldFill, goldText);
packet.getRange("C12:F12").values = [[
  "Access verified; working window pending",
  "Blue Nova Revel access and the client-owned Kosmos dashboard login are verified. No connection or data pull has started.",
  "Access records, support model, runbook, and one-product acceptance test are documented.",
  "Confirm the safe working window and representative test product before staging connection work.",
]];

const errors = await workbook.inspect({
  kind: "match",
  searchTerm: "#REF!|#DIV/0!|#VALUE!|#NAME\\?|#N/A",
  options: { useRegex: true, maxResults: 300 },
  maxChars: 8000,
});
console.log("FORMULA ERROR SCAN");
console.log(errors.ndjson);

await fs.mkdir(previewDir, { recursive: true });
for (const sheetName of ["Approval Set", "Implementation Plan", "Version Register", "Pre-Staging Packet"]) {
  const preview = await workbook.render({ sheetName, autoCrop: "all", scale: 0.8, format: "png" });
  const safe = sheetName.toLowerCase().replace(/[^a-z0-9]+/g, "-").replace(/^-|-$/g, "");
  await fs.writeFile(path.join(previewDir, `${safe}.png`), new Uint8Array(await preview.arrayBuffer()));
}

const output = await SpreadsheetFile.exportXlsx(workbook);
await output.save(workbookPath);

for (const [sheetId, range] of [
  ["Approval Set", "A1:J12"],
  ["Implementation Plan", "A1:F12"],
  ["Version Register", "A1:I12"],
  ["Pre-Staging Packet", "A1:H12"],
]) {
  console.log(`===== ${sheetId} =====`);
  console.log((await workbook.inspect({
    kind: "table",
    sheetId,
    range,
    include: "values,formulas",
    tableMaxRows: 20,
    tableMaxCols: 12,
    tableMaxCellChars: 450,
    maxChars: 24000,
  })).ndjson);
}

console.log(`OUTPUT ${workbookPath}`);
