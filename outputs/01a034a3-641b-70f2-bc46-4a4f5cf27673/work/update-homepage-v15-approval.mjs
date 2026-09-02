import fs from 'node:fs/promises';
import path from 'node:path';
import { FileBlob, SpreadsheetFile } from '@oai/artifact-tool';

const mode = process.argv[2] || 'inspect';
const workDir = process.cwd();
const workbookPath = path.resolve(workDir, '..', 'Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx');
const previewDir = path.resolve(workDir, 'phase-two-preview', 'homepage-v15-approval');

const input = await FileBlob.load(workbookPath);
const workbook = await SpreadsheetFile.importXlsx(input);
const approvalSheet = workbook.worksheets.getItem('Approval Set');
const versionSheet = workbook.worksheets.getItem('Version Register');

const sheetSummary = await workbook.inspect({
  kind: 'sheet',
  include: 'id,name',
  maxChars: 5000,
});

const approvalBefore = await workbook.inspect({
  kind: 'table',
  sheetId: 'Approval Set',
  range: 'A1:J12',
  maxChars: 12000,
  tableMaxRows: 12,
  tableMaxCols: 10,
  tableMaxCellChars: 240,
});

const versionsBefore = await workbook.inspect({
  kind: 'table',
  sheetId: 'Version Register',
  range: 'A1:I15',
  maxChars: 12000,
  tableMaxRows: 15,
  tableMaxCols: 9,
  tableMaxCellChars: 240,
});

const approvalStyle = await workbook.inspect({
  kind: 'computedStyle',
  sheetId: 'Approval Set',
  range: 'E7:J7',
  maxChars: 5000,
});

const versionStyle = await workbook.inspect({
  kind: 'computedStyle',
  sheetId: 'Version Register',
  range: 'E7:I7',
  maxChars: 5000,
});

console.log('SHEETS');
console.log(sheetSummary.ndjson);
console.log('APPROVAL_BEFORE');
console.log(approvalBefore.ndjson);
console.log('VERSIONS_BEFORE');
console.log(versionsBefore.ndjson);
console.log('APPROVAL_STYLE');
console.log(approvalStyle.ndjson);
console.log('VERSION_STYLE');
console.log(versionStyle.ndjson);

if (mode === 'inspect') {
  await fs.mkdir(previewDir, { recursive: true });
  const approvalPreview = await workbook.render({
    sheetName: 'Approval Set',
    range: 'A1:J12',
    scale: 1,
    format: 'png',
  });
  await fs.writeFile(
    path.join(previewDir, 'approval-set-before.png'),
    new Uint8Array(await approvalPreview.arrayBuffer()),
  );
  const versionPreview = await workbook.render({
    sheetName: 'Version Register',
    range: 'A1:I15',
    scale: 1,
    format: 'png',
  });
  await fs.writeFile(
    path.join(previewDir, 'version-register-before.png'),
    new Uint8Array(await versionPreview.arrayBuffer()),
  );
  console.log(`PREVIEW_DIR ${previewDir}`);
  process.exit(0);
}

if (mode !== 'apply') {
  throw new Error(`Unsupported mode: ${mode}`);
}

approvalSheet.getRange('E7').values = [['Internally Approved']];
approvalSheet.getRange('J7').values = [[
  "Internally approved by Todd with notes: the existing homepage remains intact, and the gold Addition labels/outlines are review guidance only—not part of the proposed live design. Awaiting Rebekah's client approval.",
]];

versionSheet.getRange('E7:G7').values = [[
  'Awaiting Client Approval',
  'Approved by Todd',
  'Pending Rebekah',
]];
versionSheet.getRange('I7').values = [[
  'Current internally approved v1.5 with notes: existing homepage components remain intact; gold Addition labels/outlines are review-only and excluded from the proposed live design. Awaiting Rebekah approval.',
]];

const output = await SpreadsheetFile.exportXlsx(workbook);
await output.save(workbookPath);

await fs.mkdir(previewDir, { recursive: true });
const sheetNames = workbook.worksheets.items.map((sheet) => sheet.name);
for (const sheetName of sheetNames) {
  const safeName = sheetName.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
  const preview = await workbook.render({
    sheetName,
    autoCrop: 'all',
    scale: 1,
    format: 'png',
  });
  await fs.writeFile(
    path.join(previewDir, `${safeName}-after.png`),
    new Uint8Array(await preview.arrayBuffer()),
  );
}

const approvalAfter = await workbook.inspect({
  kind: 'table',
  sheetId: 'Approval Set',
  range: 'A1:J12',
  maxChars: 12000,
  tableMaxRows: 12,
  tableMaxCols: 10,
  tableMaxCellChars: 240,
});
const versionsAfter = await workbook.inspect({
  kind: 'table',
  sheetId: 'Version Register',
  range: 'A1:I15',
  maxChars: 12000,
  tableMaxRows: 15,
  tableMaxCols: 9,
  tableMaxCellChars: 240,
});
const formulaErrors = await workbook.inspect({
  kind: 'match',
  searchTerm: '#REF!|#DIV/0!|#VALUE!|#NAME\\?|#N/A',
  options: { useRegex: true, maxResults: 300 },
  summary: 'final formula error scan',
});

console.log('APPROVAL_AFTER');
console.log(approvalAfter.ndjson);
console.log('VERSIONS_AFTER');
console.log(versionsAfter.ndjson);
console.log('FORMULA_ERRORS');
console.log(formulaErrors.ndjson);
console.log(`OUTPUT ${workbookPath}`);
console.log(`PREVIEW_DIR ${previewDir}`);
