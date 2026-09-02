import fs from "node:fs/promises";
import { fileURLToPath } from "node:url";
import { FileBlob, SpreadsheetFile } from "@oai/artifact-tool";

const workbookPath = fileURLToPath(new URL("../outputs/01a034a3-641b-70f2-bc46-4a4f5cf27673/Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx", import.meta.url));
const workbook = await SpreadsheetFile.importXlsx(await FileBlob.load(workbookPath));
const sheets = await workbook.inspect({kind:"sheet",include:"id,name",maxChars:12000});
console.log("SHEETS");
console.log(sheets.ndjson);

const versionSheet = workbook.worksheets.getItem("Version Register");
const used = versionSheet.getUsedRange();
console.log("VALUES");
console.log(JSON.stringify(used.values));

const styles = await workbook.inspect({kind:"computedStyle",sheetId:"Version Register",range:"A1:H18",maxChars:12000});
console.log("STYLES");
console.log(styles.ndjson);

const preview = await workbook.render({sheetName:"Version Register",autoCrop:"all",scale:1,format:"png"});
await fs.writeFile(new URL("../.tools/phase2-version-register-before.png",import.meta.url),new Uint8Array(await preview.arrayBuffer()));

const approvalSheet = workbook.worksheets.getItem("Approval Set");
console.log("APPROVAL_VALUES");
console.log(JSON.stringify(approvalSheet.getRange("A1:J12").values));
const approvalPreview = await workbook.render({sheetName:"Approval Set",range:"A1:J12",scale:1,format:"png"});
await fs.writeFile(new URL("../.tools/phase2-approval-set-before-architecture.png",import.meta.url),new Uint8Array(await approvalPreview.arrayBuffer()));
