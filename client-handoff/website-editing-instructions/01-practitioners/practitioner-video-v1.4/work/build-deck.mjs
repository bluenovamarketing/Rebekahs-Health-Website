import fs from "node:fs/promises";
import path from "node:path";
import { pathToFileURL } from "node:url";

const artifactModule = "C:/Users/todda/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/node_modules/@oai/artifact-tool/dist/artifact_tool.mjs";
const { FileBlob, PresentationFile } = await import(pathToFileURL(artifactModule).href);

const root = path.resolve("client-handoff/website-editing-instructions/01-practitioners/practitioner-video-v1.4");
const workRoot = path.join(root, "work");
const outputRoot = path.join(root, "deck-preview");
const starterPath = path.join(workRoot, "template-starter.pptx");
const outputPath = path.join(root, "Practitioner-Editing-Video-v1.4.pptx");

const C = {
  pine: "#174C3C",
  honey: "#D6A33A",
  cream: "#FFFDF6",
  ink: "#26342E",
};

function addShape(slide, name, position, fill, lineFill, lineWidth = 1) {
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

function addWrittenSteps(slide, key, steps, box, options = {}) {
  const compact = options.compact === true;
  addShape(slide, `${key}-box`, box, C.cream, C.honey, compact ? 4 : 5);
  addText(
    slide,
    `${key}-heading`,
    compact ? "WRITTEN CHECK" : "WRITTEN STEPS",
    {
      left: box.left + (compact ? 14 : 22),
      top: box.top + (compact ? 8 : 18),
      width: box.width - (compact ? 28 : 44),
      height: compact ? 22 : 32,
    },
    {
      fontFamily: "Aptos",
      fontSize: compact ? 15 : 22,
      bold: true,
      color: C.pine,
      textAlign: "center",
    },
  );
  addText(
    slide,
    `${key}-body`,
    steps.map((step, index) => `${index + 1}. ${step}`).join("\n"),
    {
      left: box.left + (compact ? 13 : 24),
      top: box.top + (compact ? 34 : 66),
      width: box.width - (compact ? 26 : 48),
      height: box.height - (compact ? 39 : 86),
    },
    {
      fontFamily: "Aptos",
      fontSize: compact ? 14 : options.fontSize || 20,
      color: C.ink,
      verticalAlignment: "middle",
    },
  );
}

function rewriteExistingCue(slide, prefix, firstTwoSteps, thirdStep) {
  const instruction = slide.shapes.items.find((shape) => shape.name === `${prefix}-instruction`);
  const action = slide.shapes.items.find((shape) => shape.name === `${prefix}-action`);
  if (!instruction || !action) throw new Error(`Missing inherited ${prefix} cue.`);
  instruction.text = `1. ${firstTwoSteps[0]}\n2. ${firstTwoSteps[1]}`;
  instruction.position = { left: 1240, top: 232, width: 525, height: 64 };
  instruction.text.style = {
    fontFamily: "Aptos",
    fontSize: 19,
    bold: true,
    color: C.ink,
    textAlign: "left",
  };
  action.text = `3. ${thirdStep}`;
  action.position = { left: 1240, top: 308, width: 525, height: 42 };
  action.text.style = {
    fontFamily: "Aptos",
    fontSize: 27,
    bold: true,
    color: C.pine,
    textAlign: "left",
  };
}

async function saveBlob(blob, filePath) {
  await fs.writeFile(filePath, new Uint8Array(await blob.arrayBuffer()));
}

async function main() {
  await fs.mkdir(outputRoot, { recursive: true });
  const presentation = await PresentationFile.importPptx(await FileBlob.load(starterPath));
  const slides = presentation.slides.items;
  if (slides.length !== 16) throw new Error(`Expected 16 slides, found ${slides.length}.`);

  for (const slide of slides) {
    const footer = slide.shapes.items.find((shape) => shape.name === "page");
    if (!footer) throw new Error(`Missing footer on slide ${slide.index + 1}.`);
    footer.text = footer.text.toString().replace("v1.3", "v1.4");
  }

  addWrittenSteps(slides[3], "written-steps-s04", [
    "Click Medical Practicioners.",
    "Click All Medical Practicioners.",
    "Search by name if needed.",
  ], { left: 1460, top: 275, width: 350, height: 330 });

  rewriteExistingCue(slides[4], "edit-cue", ["Find the correct row.", "Hover over it."], "Click Edit.");

  addWrittenSteps(slides[5], "written-steps-s06", [
    "Scroll to Meta Boxes.",
    "Open Medical Practicioner fields.",
    "Stop if you do not see them.",
  ], { left: 1460, top: 260, width: 350, height: 345 }, { fontSize: 19 });

  addWrittenSteps(slides[6], "written-steps-s07", [
    "Find Medical Service.",
    "Check the approved category.",
    "Uncheck incorrect categories.",
  ], { left: 1460, top: 260, width: 350, height: 345 }, { fontSize: 19 });

  addWrittenSteps(slides[7], "written-steps-s08", [
    "Update Medical Center.",
    "Update Full Name.",
    "Match the approved spelling.",
  ], { left: 1460, top: 260, width: 350, height: 345 }, { fontSize: 19 });

  addWrittenSteps(slides[8], "written-steps-s09", [
    "Select Visual.",
    "Replace the approved description.",
    "Keep the paragraph breaks.",
  ], { left: 1460, top: 260, width: 350, height: 345 }, { fontSize: 19 });

  addWrittenSteps(slides[9], "written-steps-s10", [
    "Add only approved public details.",
    "Test every link.",
    "Remove outdated contact information.",
  ], { left: 1460, top: 260, width: 350, height: 345 }, { fontSize: 18 });

  addWrittenSteps(slides[10], "written-steps-s11", [
    "Re-read every changed field.",
    "Compare with the approved information.",
    "Fix errors before saving.",
  ], { left: 1460, top: 260, width: 350, height: 345 }, { fontSize: 18 });

  addWrittenSteps(slides[11], "written-steps-s12", [
    "Click Save once.",
    "Wait for confirmation.",
    "Do not click repeatedly.",
  ], { left: 1460, top: 260, width: 350, height: 345 }, { fontSize: 19 });

  rewriteExistingCue(slides[12], "cache-cue", ["Return to the practitioner list.", "Hover over the same row."], "Click Clear Cache.");

  addWrittenSteps(slides[13], "written-steps-s14", [
    "Search the name.",
    "Confirm the public details.",
    "Check the links.",
  ], { left: 1440, top: 900, width: 360, height: 105 }, { compact: true });

  addWrittenSteps(slides[14], "written-steps-s15", [
    "Click View full listing.",
    "Check the full details and links.",
    "Repeat the check on a phone.",
  ], { left: 1040, top: 270, width: 650, height: 350 }, { fontSize: 23 });

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
