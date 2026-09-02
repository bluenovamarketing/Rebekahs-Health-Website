import fs from "node:fs/promises";
import path from "node:path";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const projectDir = "C:/Users/todda/Blue Nova Projects/Rebekahs Health Website";
const outputDir = path.join(projectDir, "outputs/01a054e9-e2a3-7e11-9e9c-0c2a0311c10d");
const workbookPath = path.join(outputDir, "Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx");
const previewDir = path.join(outputDir, "work/neutral-placeholder-approved");
const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(workbookPath));

const approval = workbook.worksheets.getItem("Approval Set");
approval.getRange("C8").values = [[
  "Store introduction, four illustrated wellness-goal paths, personal shopping guidance, catalog search, sort, filters, six sample products, pagination, no-results recovery, and the approved neutral botanical missing-image fallback",
]];
approval.getRange("J8").values = [[
  "Review exact v1.7 page-body-only concept: shop-catalog-template-v1.7.html. The wellness paths and neutral botanical missing-image fallback are approved; full catalog approval remains deferred pending Revel/Kosmos validation and review of the remaining presentation and behavior.",
]];
approval.getRange("C9").values = [[
  "Simple and variation product examples with three-view galleries, clearly labeled neutral botanical missing-image states, quantity and variation controls, facts, cautions, team help, and related products",
]];
approval.getRange("H9").values = [[
  "Approve the shared structure and interactions now. Front, Supplement Facts, and Ingredients remain distinct image views; missing photography uses the approved neutral botanical 'Product image not available' state. Product media and connected catalog content wait for Revel/Kosmos.",
]];
approval.getRange("J9").values = [[
  "Review exact v1.5 page-body-only mockup: product-page-templates-v1.5.html. It replaces the single-R imagery in both galleries and all related-product cards with the approved neutral botanical fallback while preserving every product-page component and interaction.",
]];
approval.getRange("C10").values = [[
  "Connected cart with approved neutral missing-image fallback, empty-cart recovery, checkout fields, validation, order review, and customer-facing confirmation",
]];
approval.getRange("J10").values = [[
  "Review exact v1.5 page-body-only mockup: purchase-path-mockup-v1.5.html. Both cart items use the approved neutral botanical fallback; cart, checkout, validation, confirmation, and all existing interactions are preserved.",
]];
approval.getRange("C12").values = [[
  "Ten non-ideal states including the approved neutral missing-image component, plus the mobile filter dialog and responsive/accessibility checklist",
]];
approval.getRange("H12").values = [[
  "Approve recovery hierarchy and accessibility behavior now. The missing-image state uses the approved neutral botanical fallback. Exact inventory, shipping, tax, payment, authentication, and integration-error messages wait for real connected-service responses.",
]];
approval.getRange("J12").values = [[
  "Review exact v1.4 page-body-only mockup: store-states-components-v1.4.html. The missing-image example now demonstrates the approved reusable botanical treatment; drawer dialog semantics and focus-return behavior remain included.",
]];

const versions = workbook.worksheets.getItem("Version Register");
versions.getRange("C8:I8").values = [[
  "v1.7",
  "shop-catalog-template-v1.7.html",
  "Revel Validation Pending",
  "Placeholder and wellness paths approved; full catalog pending",
  "Not reviewed",
  "v1.8",
  "Replaced all six single-R bottle placeholders with the approved neutral botanical 'Product image not available' fallback. Preserved the approved illustrated paths and all catalog behavior; full approval still waits for remaining internal review and Revel/Kosmos validation.",
]];
versions.getRange("C9:I9").values = [[
  "v1.5",
  "product-page-templates-v1.5.html",
  "Internal Review",
  "Placeholder approved; full system awaiting Todd review",
  "Not reviewed",
  "v1.6",
  "Replaced the single-R treatment in both galleries and all three related-product cards with the approved neutral botanical missing-image state; preserved all product examples, controls, fact cards, help, and interactions.",
]];
versions.getRange("C10:I10").values = [[
  "v1.5",
  "purchase-path-mockup-v1.5.html",
  "Internal Review",
  "Placeholder approved; full system awaiting Todd review",
  "Not reviewed",
  "v1.6",
  "Replaced both single-R cart placeholders with the approved neutral botanical missing-image state; preserved cart, empty-cart recovery, checkout, validation, review, confirmation, and all interactions.",
]];
versions.getRange("C12:I12").values = [[
  "v1.4",
  "store-states-components-v1.4.html",
  "Internal Review",
  "Placeholder approved; full system awaiting Todd review",
  "Not reviewed",
  "v1.5",
  "Replaced the abstract missing-media glyph with the approved neutral botanical 'Product image not available' component; preserved all other store states, the mobile drawer, and responsive/accessibility checklist.",
]];

const productContent = workbook.worksheets.getItem("Product & Content");
productContent.getRange("H15").values = [[
  "The export contains no product-image fields. Todd approved a neutral cream-and-sage botanical 'Product image not available' fallback for every missing asset; real product photography still requires source confirmation and acceptance.",
]];

console.log((await workbook.inspect({
  kind: "table",
  sheetId: "Version Register",
  range: "A5:I12",
  include: "values,formulas",
  tableMaxRows: 8,
  tableMaxCols: 9,
  maxChars: 12000,
})).ndjson);

await fs.mkdir(previewDir, { recursive: true });
for (const sheet of workbook.worksheets.items) {
  const preview = await workbook.render({ sheetName: sheet.name, autoCrop: "all", scale: 0.85, format: "png" });
  const safe = sheet.name.toLowerCase().replace(/[^a-z0-9]+/g, "-").replace(/^-|-$/g, "");
  await fs.writeFile(path.join(previewDir, `${safe}.png`), new Uint8Array(await preview.arrayBuffer()));
}

const errors = await workbook.inspect({
  kind: "match",
  searchTerm: "#REF!|#DIV/0!|#VALUE!|#NAME\\?|#N/A",
  options: { useRegex: true, maxResults: 300 },
  summary: "final formula error scan",
});
console.log(errors.ndjson);

const output = await SpreadsheetFile.exportXlsx(workbook);
await output.save(workbookPath);
console.log(`OUTPUT ${workbookPath}`);
