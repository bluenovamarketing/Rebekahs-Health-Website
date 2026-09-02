import fs from "node:fs/promises";
import path from "node:path";
import { pathToFileURL } from "node:url";

const artifactModule = "C:/Users/todda/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/node_modules/@oai/artifact-tool/dist/artifact_tool.mjs";
const { FileBlob, PresentationFile } = await import(pathToFileURL(artifactModule).href);

const root = path.resolve("client-handoff/website-editing-instructions/01-practitioners/practitioner-video-v1.3");
const workRoot = path.join(root, "work");
const outputRoot = path.join(root, "deck-preview");
const starterPath = path.join(workRoot, "template-starter.pptx");
const publicCapturePath = path.resolve("client-handoff/website-editing-instructions/01-practitioners/.tmp/practitioner-video-v1.1/captures/10-public-practitioner-filtered-v1.3.png");
const outputPath = path.join(root, "Practitioner-Editing-Video-v1.3.pptx");

const C = {
  pine: "#174C3C",
  honey: "#D6A33A",
  cream: "#F7F3E8",
  ink: "#26342E",
  white: "#FFFFFF",
};

function addShape(slide, name, position, fill, lineFill = fill, lineWidth = 1) {
  return slide.shapes.add({
    geometry: "roundRect",
    name,
    position,
    fill,
    line: { style: "solid", fill: lineFill, width: lineWidth },
    borderRadius: "rounded-xl",
  });
}

function addText(slide, name, text, position, style) {
  const shape = slide.shapes.add({
    geometry: "textbox",
    name,
    position,
    fill: "none",
    line: { style: "solid", fill: "none", width: 0 },
  });
  shape.text = text;
  shape.text.style = style;
  return shape;
}

function addActionCue(slide, actionLabel, cueName) {
  addShape(
    slide,
    `${cueName}-box`,
    { left: 1210, top: 215, width: 585, height: 170 },
    C.cream,
    C.honey,
    6,
  );
  addText(
    slide,
    `${cueName}-instruction`,
    "HOVER OVER THE CORRECT ROW",
    { left: 1240, top: 245, width: 525, height: 38 },
    { fontFamily: "Aptos", fontSize: 23, bold: true, color: C.ink, textAlign: "center" },
  );
  addText(
    slide,
    `${cueName}-action`,
    `Then click ${actionLabel}`,
    { left: 1240, top: 303, width: 525, height: 55 },
    { fontFamily: "Aptos", fontSize: actionLabel.length > 8 ? 34 : 39, bold: true, color: C.pine, textAlign: "center" },
  );
}

async function saveBlob(blob, filePath) {
  await fs.writeFile(filePath, new Uint8Array(await blob.arrayBuffer()));
}

async function arrayBufferFromFile(filePath) {
  const bytes = await fs.readFile(filePath);
  return bytes.buffer.slice(bytes.byteOffset, bytes.byteOffset + bytes.byteLength);
}

async function main() {
  await fs.mkdir(outputRoot, { recursive: true });
  const presentation = await PresentationFile.importPptx(await FileBlob.load(starterPath));
  const slides = presentation.slides.items;

  for (const slide of slides) {
    const footer = slide.shapes.items.find((shape) => shape.name === "page");
    if (!footer) throw new Error(`Missing footer on slide ${slide.index + 1}`);
    footer.text = footer.text.toString().replace("v1.2", "v1.3");
  }

  addActionCue(slides[4], "Edit", "edit-cue");
  addActionCue(slides[12], "Clear Cache", "cache-cue");

  const cacheTitle = slides[12].shapes.items.find((shape) => shape.name === "title");
  if (!cacheTitle) throw new Error("Missing title on slide 13");
  cacheTitle.text.style = {
    fontFamily: "Georgia",
    fontSize: 34,
    bold: true,
    color: C.pine,
  };

  const publicImage = slides[13].images.items[0];
  if (!publicImage) throw new Error("Missing public-directory image on slide 14");
  publicImage.replace({
    blob: await arrayBufferFromFile(publicCapturePath),
    contentType: "image/png",
    alt: "Live public practitioner directory filtered to Laura Young and showing 1 of 26 practitioners and practices",
    fit: "contain",
  });

  slides[4].speakerNotes.textFrame.setText([
    "[Sources]",
    "- Live WordPress practitioner-list interface captured August 31, 2026.",
  ].join("\n"));
  slides[12].speakerNotes.textFrame.setText([
    "[Sources]",
    "- Live WordPress practitioner-list interface captured August 31, 2026.",
  ].join("\n"));
  slides[13].speakerNotes.textFrame.setText([
    "[Sources]",
    "- https://rebekahspureliving.com/practitioners/ (captured September 1, 2026).",
  ].join("\n"));

  for (const [index, slide] of slides.entries()) {
    const stem = `slide-${String(index + 1).padStart(2, "0")}`;
    const png = await presentation.export({ slide, format: "png", scale: 1 });
    await saveBlob(png, path.join(outputRoot, `${stem}.png`));
    const layout = await slide.export({ format: "layout" });
    await fs.writeFile(path.join(outputRoot, `${stem}.layout.json`), await layout.text());
  }

  const montage = await presentation.export({ format: "webp", montage: true, scale: 1 });
  await saveBlob(montage, path.join(outputRoot, "montage.webp"));
  const pptx = await PresentationFile.exportPptx(presentation);
  await pptx.save(outputPath);
  console.log(outputPath);
}

main().catch((error) => {
  console.error(error.stack || error.message || String(error));
  process.exitCode = 1;
});
