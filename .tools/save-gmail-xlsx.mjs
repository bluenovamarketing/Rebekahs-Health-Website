import fs from "node:fs/promises";
import path from "node:path";

let rawBase64Url;
if (process.argv[2]) {
  rawBase64Url = (await fs.readFile(process.argv[2], "utf8")).trim();
} else {
  const chunks = [];
  for await (const chunk of process.stdin) {
    chunks.push(chunk);
    const joined = Buffer.concat(chunks).toString("utf8");
    if (joined.includes("__CODEX_END__")) break;
  }
  const input = Buffer.concat(chunks).toString("utf8");
  rawBase64Url = input.slice(0, input.indexOf("__CODEX_END__")).trim();
}
if (!rawBase64Url) throw new Error("No raw Gmail message was supplied.");

const messageBytes = Buffer.from(rawBase64Url.replaceAll("-", "+").replaceAll("_", "/"), "base64");
const message = messageBytes.toString("latin1");
const boundaryMatch = message.match(/boundary=(?:"([^"]+)"|([^;\r\n]+))/i);
if (!boundaryMatch) throw new Error("Could not locate the MIME boundary.");

const boundary = boundaryMatch[1] || boundaryMatch[2].trim();
const parts = message.split(`--${boundary}`);
const attachmentPart = parts.find((part) => /filename[^\r\n]*Product_Export_Establishment_3/i.test(part) || /filename[^\r\n]*\.xlsx/i.test(part));
if (!attachmentPart) throw new Error("Could not locate the XLSX attachment MIME part.");

const separator = attachmentPart.includes("\r\n\r\n") ? "\r\n\r\n" : "\n\n";
const separatorIndex = attachmentPart.indexOf(separator);
if (separatorIndex < 0) throw new Error("Could not separate attachment headers from the body.");

const headers = attachmentPart.slice(0, separatorIndex);
let body = attachmentPart.slice(separatorIndex + separator.length).replace(/\r?\n--\s*$/, "").trim();
let attachmentBytes;
if (/Content-Transfer-Encoding:\s*base64/i.test(headers)) {
  attachmentBytes = Buffer.from(body.replace(/\s+/g, ""), "base64");
} else {
  attachmentBytes = Buffer.from(body, "latin1");
}

if (attachmentBytes.length < 4 || attachmentBytes[0] !== 0x50 || attachmentBytes[1] !== 0x4b) {
  throw new Error("Extracted bytes are not an XLSX/ZIP file.");
}

const outputDir = path.resolve("client-inputs", "phase-two");
const outputPath = path.join(outputDir, "Product_Export_Establishment_3 (70)_results.xlsx");
await fs.mkdir(outputDir, { recursive: true });
await fs.writeFile(outputPath, attachmentBytes);
console.log(JSON.stringify({ outputPath, size: attachmentBytes.length }));
