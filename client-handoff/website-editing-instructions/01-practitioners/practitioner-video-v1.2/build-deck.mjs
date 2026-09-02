import fs from "node:fs/promises";
import path from "node:path";
import { pathToFileURL } from "node:url";

const artifactModule = "C:/Users/todda/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/node_modules/@oai/artifact-tool/dist/artifact_tool.mjs";
const { Presentation, PresentationFile } = await import(pathToFileURL(artifactModule).href);

const root = path.resolve("client-handoff/website-editing-instructions/01-practitioners/practitioner-video-v1.2");
const captureRoot = path.resolve("client-handoff/website-editing-instructions/01-practitioners/.tmp/practitioner-video-v1.1/captures");
const outputRoot = path.join(root, "deck-preview");
const logoPath = path.resolve("tmp/source/current-site-logo-live.png");

const C = { pine: "#174C3C", leaf: "#3F7D50", sage: "#A9C3A0", honey: "#D6A33A", berry: "#8E4B61", cream: "#F7F3E8", ink: "#26342E", white: "#FFFFFF" };

const scenes = [
  { step: "START", title: "Update an existing practitioner listing", image: null, kind: "start" },
  { step: "1", title: "Required role: Editor or Administrator", image: null, kind: "role" },
  { step: "2", title: "Confirm the live login address", image: null, kind: "login" },
  { step: "3", title: "Open All Medical Practicioners", image: "01-practitioner-list-1600x900.png", focus: { x: 0, y: 285, width: 170, height: 190 } },
  { step: "4", title: "Find the correct record and choose Edit", image: "01-practitioner-list-1600x900.png", focus: { x: 175, y: 170, width: 780, height: 155 } },
  { step: "5", title: "Open Medical Practicioner Fields", image: "02-practitioner-editor-reloaded.png", focus: { x: 0, y: 485, width: 1515, height: 610 } },
  { step: "6", title: "Choose only approved service categories", image: "04-practitioner-fields.png", focus: { x: 0, y: 430, width: 1180, height: 650 } },
  { step: "7", title: "Update the practice and public name", image: "05-practitioner-name-practice.png", focus: { x: 0, y: 525, width: 1515, height: 300 } },
  { step: "8", title: "Edit the description in Visual mode", image: "06-practitioner-description.png", focus: { x: 0, y: 445, width: 1515, height: 600 } },
  { step: "9", title: "Add only approved public contact details", image: "07-practitioner-contact.png", focus: { x: 0, y: 420, width: 1515, height: 660 } },
  { step: "10", title: "Review every field you changed", image: "05-practitioner-name-practice.png", focus: { x: 0, y: 525, width: 1515, height: 300 } },
  { step: "11", title: "Select Save once—and wait", image: "02-practitioner-editor-top.png", focus: { x: 1280, y: 0, width: 245, height: 125 } },
  { step: "12", title: "Clear only the record’s applicable cache", image: "01-practitioner-list-1600x900.png", focus: { x: 175, y: 170, width: 780, height: 155 } },
  { step: "13", title: "Verify the exact public directory", image: "10-public-practitioner-filtered.png", focus: { x: 95, y: 330, width: 1440, height: 650 } },
  { step: "14", title: "Open the full listing and check a phone", image: "11-public-practitioner-expanded.png", focus: { x: 105, y: 0, width: 570, height: 920 } },
  { step: "DONE", title: "Finish every edit with the same three checks", image: null, kind: "done" },
];

async function blobFromFile(filePath) {
  const bytes = await fs.readFile(filePath);
  return bytes.buffer.slice(bytes.byteOffset, bytes.byteOffset + bytes.byteLength);
}

function addShape(slide, name, position, fill, lineFill = fill, lineWidth = 1, radius = "rounded-xl") {
  return slide.shapes.add({ geometry: "roundRect", name, position, fill, line: { style: "solid", fill: lineFill, width: lineWidth }, borderRadius: radius });
}

function addText(slide, name, text, position, style) {
  const shape = slide.shapes.add({ geometry: "textbox", name, position, fill: "none", line: { style: "solid", fill: "none", width: 0 } });
  shape.text = text;
  shape.text.style = style;
  return shape;
}

function addHeader(slide, scene, index) {
  slide.shapes.add({ geometry: "rect", name: "header-band", position: { left: 0, top: 0, width: 1920, height: 100 }, fill: C.cream, line: { style: "solid", fill: C.cream, width: 0 } });
  addText(slide, "title", scene.title, { left: 48, top: 23, width: 1550, height: 58 }, { fontFamily: "Georgia", fontSize: 40, bold: true, color: C.pine });
  addShape(slide, "step", { left: 1700, top: 20, width: 170, height: 58 }, C.honey, C.honey, 1, "rounded-2xl");
  addText(slide, "step-text", scene.step, { left: 1712, top: 34, width: 146, height: 30 }, { fontFamily: "Aptos", fontSize: scene.step.length > 3 ? 20 : 25, bold: true, color: C.ink, textAlign: "center" });
  addText(slide, "page", `Practitioner editing v1.2  •  ${index + 1}/${scenes.length}`, { left: 1545, top: 1048, width: 325, height: 18 }, { fontFamily: "Aptos", fontSize: 13, color: C.leaf, textAlign: "right" });
}

async function addScreenshot(slide, scene) {
  const imagePath = path.join(captureRoot, scene.image);
  addShape(slide, "screen-frame", { left: 30, top: 100, width: 1860, height: 935 }, C.white, C.sage, 2, "rounded-lg");
  slide.images.add({ blob: await blobFromFile(imagePath), contentType: "image/png", alt: `Live WordPress or public-site capture for ${scene.title}`, fit: "contain", position: { left: 34, top: 104, width: 1852, height: 927 } });

  if (!scene.focus) return;
  const sourceWidth = 1869;
  const sourceHeight = 1125;
  const scale = Math.min(1852 / sourceWidth, 927 / sourceHeight);
  const originX = 34 + (1852 - sourceWidth * scale) / 2;
  const originY = 104 + (927 - sourceHeight * scale) / 2;
  const focus = {
    left: originX + scene.focus.x * scale,
    top: originY + scene.focus.y * scale,
    width: scene.focus.width * scale,
    height: scene.focus.height * scale,
  };
  addShape(slide, "look-here-highlight", focus, "none", C.honey, 8, "rounded-lg");
  addShape(slide, "look-here-label", { left: focus.left, top: Math.max(106, focus.top - 48), width: 170, height: 50 }, C.honey, C.honey, 1, "rounded-lg");
  addText(slide, "look-here-text", "LOOK HERE", { left: focus.left + 10, top: Math.max(119, focus.top - 35), width: 150, height: 24 }, { fontFamily: "Aptos", fontSize: 18, bold: true, color: C.ink, textAlign: "center" });
}

async function buildSlide(presentation, scene, index) {
  const slide = presentation.slides.add();
  slide.background.fill = C.cream;
  addHeader(slide, scene, index);
  if (scene.image) {
    await addScreenshot(slide, scene);
    return;
  }

  if (scene.kind === "start") {
    slide.images.add({ blob: await blobFromFile(logoPath), contentType: "image/png", alt: "Rebekah's Health and Nutrition logo", fit: "contain", position: { left: 155, top: 245, width: 650, height: 350 } });
    addText(slide, "start-copy", "Update approved practitioner information, save it safely, clear the applicable cache, and verify the live result.", { left: 930, top: 290, width: 770, height: 260 }, { fontFamily: "Aptos", fontSize: 42, color: C.ink });
    addText(slide, "start-note", "Beginner lesson  •  Live website  •  Full-screen version", { left: 935, top: 600, width: 750, height: 42 }, { fontFamily: "Aptos", fontSize: 25, bold: true, color: C.berry });
    return;
  }

  if (scene.kind === "role") {
    addText(slide, "role-label", "WORDPRESS ROLE REQUIRED", { left: 260, top: 255, width: 1400, height: 46 }, { fontFamily: "Aptos", fontSize: 28, bold: true, color: C.leaf, textAlign: "center" });
    addText(slide, "role-value", "EDITOR OR ADMINISTRATOR", { left: 160, top: 345, width: 1600, height: 95 }, { fontFamily: "Georgia", fontSize: 66, bold: true, color: C.pine, textAlign: "center" });
    addShape(slide, "enough-box", { left: 520, top: 515, width: 880, height: 130 }, C.white, C.sage, 2, "rounded-2xl");
    addText(slide, "enough", "Editor is enough for this task.", { left: 570, top: 550, width: 780, height: 60 }, { fontFamily: "Aptos", fontSize: 36, bold: true, color: C.ink, textAlign: "center" });
    addText(slide, "role-stop", "If you do not know your role, stop and ask Blue Nova before editing.", { left: 365, top: 730, width: 1190, height: 55 }, { fontFamily: "Aptos", fontSize: 27, color: C.berry, textAlign: "center" });
    return;
  }

  if (scene.kind === "login") {
    addText(slide, "login-label", "LIVE WORDPRESS LOGIN", { left: 250, top: 285, width: 1420, height: 44 }, { fontFamily: "Aptos", fontSize: 28, bold: true, color: C.leaf, textAlign: "center" });
    addShape(slide, "url-box", { left: 190, top: 365, width: 1540, height: 180 }, C.white, C.sage, 2, "rounded-2xl");
    addText(slide, "login-url", "rebekahspureliving.com/wp-admin", { left: 260, top: 420, width: 1400, height: 72 }, { fontFamily: "Aptos", fontSize: 52, bold: true, color: C.pine, textAlign: "center" });
    addText(slide, "login-note", "Confirm this live address before making a change.", { left: 300, top: 650, width: 1320, height: 55 }, { fontFamily: "Aptos", fontSize: 30, color: C.berry, textAlign: "center" });
    return;
  }

  addShape(slide, "closing", { left: 150, top: 265, width: 1620, height: 390 }, C.pine, C.pine, 1, "rounded-2xl");
  addText(slide, "closing-main", "SAVE  →  CLEAR THE APPLICABLE CACHE  →  VERIFY THE EXACT PUBLIC PAGE", { left: 255, top: 370, width: 1410, height: 120 }, { fontFamily: "Aptos", fontSize: 42, bold: true, color: C.white, textAlign: "center" });
  addText(slide, "closing-sub", "Check the result on desktop and phone.", { left: 390, top: 545, width: 1140, height: 52 }, { fontFamily: "Aptos", fontSize: 30, color: C.white, textAlign: "center" });
  addText(slide, "closing-help", "If any check fails, take a screenshot and contact Blue Nova before changing anything again.", { left: 320, top: 750, width: 1280, height: 60 }, { fontFamily: "Aptos", fontSize: 27, color: C.berry, textAlign: "center" });
}

async function main() {
  await fs.mkdir(outputRoot, { recursive: true });
  const presentation = Presentation.create({ slideSize: { width: 1920, height: 1080 } });
  for (let index = 0; index < scenes.length; index += 1) await buildSlide(presentation, scenes[index], index);
  for (const [index, slide] of presentation.slides.items.entries()) {
    const stem = `slide-${String(index + 1).padStart(2, "0")}`;
    const png = await presentation.export({ slide, format: "png", scale: 1 });
    await fs.writeFile(path.join(outputRoot, `${stem}.png`), new Uint8Array(await png.arrayBuffer()));
    const layout = await slide.export({ format: "layout" });
    await fs.writeFile(path.join(outputRoot, `${stem}.layout.json`), await layout.text());
  }
  const montage = await presentation.export({ format: "webp", montage: true, scale: 1 });
  await fs.writeFile(path.join(outputRoot, "montage.webp"), new Uint8Array(await montage.arrayBuffer()));
  const pptx = await PresentationFile.exportPptx(presentation);
  await pptx.save(path.join(root, "Practitioner-Editing-Video-v1.2.pptx"));
}

main().catch((error) => { console.error(error); process.exitCode = 1; });
