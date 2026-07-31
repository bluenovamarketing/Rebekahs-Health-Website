import { readFileSync, writeFileSync } from "node:fs";
import { fileURLToPath } from "node:url";
import { dirname, join } from "node:path";

const toolDirectory = dirname(fileURLToPath(import.meta.url));
const projectDirectory = dirname(toolDirectory);
const sourcePath = join(projectDirectory, "second-mockup.html");
const outputPath = join(projectDirectory, "combined-original-sections-source-v3.1.0.js");
const sourceBase64 = readFileSync(sourcePath).toString("base64");

writeFileSync(
  outputPath,
  `/* Generated from second-mockup.html for file://-safe homepage previews. */\n` +
    `globalThis.REBEKAHS_SOURCE_HTML_BASE64=${JSON.stringify(sourceBase64)};\n`,
  "utf8",
);

