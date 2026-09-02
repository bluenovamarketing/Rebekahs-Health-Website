import fs from "node:fs/promises";
import { fileURLToPath } from "node:url";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const workbookPath = fileURLToPath(new URL("../outputs/01a034a3-641b-70f2-bc46-4a4f5cf27673/Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx", import.meta.url));
const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(workbookPath));

const approval = workbook.worksheets.getItem("Approval Set");
approval.getRange("E7").values = [["In review"]];
approval.getRange("F7").values = [["Local mockup"]];
approval.getRange("C7").values = [["All existing live homepage components plus Shop Online entry, shopping paths, and representative pilot products"]];
approval.getRange("D7").values = [["/"]];
approval.getRange("H7").values = [["Use the existing live homepage body. Preserve every live page component and add commerce content only; mockups contain no global header, menu, utility bar, or footer."]];
approval.getRange("J7").values = [["Todd reviews homepage v1.3: live body retained; no header, menu, or footer."]];

const register = workbook.worksheets.getItem("Version Register");
register.getRange("C7:I7").values = [[
  "v1.3",
  "online-store-homepage-mockup-v1.3.html",
  "Internal Review",
  "Awaiting Todd review",
  "Not reviewed",
  "v1.4",
  "Current live-body-based page-only review version; v1.1-v1.2 are superseded."
]];

const exported = await SpreadsheetFile.exportXlsx(workbook);
await exported.save(workbookPath);

const registerPreview = await workbook.render({sheetName:"Version Register",range:"A1:I18",scale:1.5,format:"png"});
await fs.writeFile(new URL("../.tools/phase2-version-register-v13.png",import.meta.url),new Uint8Array(await registerPreview.arrayBuffer()));
const approvalPreview = await workbook.render({sheetName:"Approval Set",range:"A1:J12",scale:1.25,format:"png"});
await fs.writeFile(new URL("../.tools/phase2-approval-set-v13.png",import.meta.url),new Uint8Array(await approvalPreview.arrayBuffer()));

const registerCheck = await workbook.inspect({kind:"table",sheetId:"Version Register",range:"A1:I12",include:"values,formulas",tableMaxRows:12,tableMaxCols:9,maxChars:10000});
console.log("VERSION_REGISTER");
console.log(registerCheck.ndjson);
const approvalCheck = await workbook.inspect({kind:"table",sheetId:"Approval Set",range:"A1:J12",include:"values,formulas",tableMaxRows:12,tableMaxCols:10,maxChars:10000});
console.log("APPROVAL_SET");
console.log(approvalCheck.ndjson);
const errors = await workbook.inspect({kind:"match",searchTerm:"#REF!|#DIV/0!|#VALUE!|#NAME\\?|#N/A",options:{useRegex:true,maxResults:300},summary:"final formula error scan"});
console.log("FORMULA_ERRORS");
console.log(errors.ndjson);
console.log(`OUTPUT ${workbookPath}`);
