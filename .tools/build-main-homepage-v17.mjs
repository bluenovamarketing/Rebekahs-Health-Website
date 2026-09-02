import fs from "node:fs";
import path from "node:path";

const root = process.cwd();
const sourcePath = path.join(root, "main-homepage-ecommerce-integration-v1.6.html");
const outputPath = path.join(root, "main-homepage-ecommerce-integration-v1.7.html");

if (fs.existsSync(outputPath)) {
  throw new Error(`Refusing to overwrite existing reviewed revision: ${outputPath}`);
}

let html = fs.readFileSync(sourcePath, "utf8");

const replacements = [
  ["Main Homepage Ecommerce Integration v1.6", "Main Homepage Ecommerce Integration v1.7"],
  ["background-size:300% 100%", "background-size:300% auto"],
  ["Wellness favorites, thoughtfully selected.", "Wellness favorites thoughtfully selected."],
];

for (const [before, after] of replacements) {
  if (!html.includes(before)) {
    throw new Error(`Expected source text not found: ${before}`);
  }
  html = html.replaceAll(before, after);
}

const assertions = [
  [html.includes("Main Homepage Ecommerce Integration v1.7"), "v1.7 label"],
  [html.includes("background-size:300% auto"), "non-distorting sprite crop"],
  [html.includes("Wellness favorites thoughtfully selected."), "revised featured heading"],
  [(html.match(/class="phase-path-image"/g) || []).length === 3, "three shopping paths"],
  [(html.match(/class="phase-product"/g) || []).length === 4, "four featured product cards"],
  [!/<header\b/i.test(html) && !/<footer\b/i.test(html), "page-body-only architecture"],
];

for (const [ok, label] of assertions) {
  if (!ok) throw new Error(`Build assertion failed: ${label}`);
}

fs.writeFileSync(outputPath, html, "utf8");
console.log(`Created ${path.basename(outputPath)} from preserved v1.6 source.`);
