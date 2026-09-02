import fs from "node:fs/promises";
import path from "node:path";
import { pathToFileURL } from "node:url";

const artifactModule = "C:/Users/todda/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/node_modules/@oai/artifact-tool/dist/artifact_tool.mjs";
const { Presentation, PresentationFile } = await import(pathToFileURL(artifactModule).href);

const root = path.resolve("client-handoff/website-editing-instructions/01-practitioners/practitioner-video-v1.1");
const captureRoot = path.resolve("client-handoff/website-editing-instructions/01-practitioners/.tmp/practitioner-video-v1.1/captures");
const outputRoot = path.join(root, "deck-preview");
const logoPath = path.resolve("tmp/source/current-site-logo-live.png");

const C = {
  pine: "#174C3C",
  leaf: "#3F7D50",
  sage: "#A9C3A0",
  honey: "#D6A33A",
  berry: "#8E4B61",
  cream: "#F7F3E8",
  ink: "#26342E",
  white: "#FFFFFF",
  mist: "#E9EFEA",
};

const scenes = [
  {
    step: "START",
    title: "Update an existing practitioner listing",
    body: "Find the correct record, edit only approved information, save once, clear the applicable cache, and verify the live page.",
    image: null,
  },
  {
    step: "1",
    title: "Use an Editor account",
    body: "Recommended staff role: Editor. Administrator access is not required for routine practitioner updates. Each staff member should use a separate login.",
    image: "02-practitioner-editor-top.png",
  },
  {
    step: "2",
    title: "Confirm the live login address",
    body: "Go to rebekahspureliving.com/wp-admin. Before editing, confirm the address begins with the live domain—not a future staging address.",
    image: null,
  },
  {
    step: "3",
    title: "Open All Medical Practicioners",
    body: "In the left menu, select Medical Practicioners, then All Medical Practicioners. The current WordPress label is spelled “Practicioners.”",
    image: "01-practitioner-list-1600x900.png",
    focus: { left: 48, top: 300, width: 92, height: 94 },
  },
  {
    step: "4",
    title: "Find the correct record and choose Edit",
    body: "Search by name when needed. Hover over the correct row and select Edit. Do not choose Quick Edit, Trash, or Clear Cache yet.",
    image: "01-practitioner-list-1600x900.png",
    focus: { left: 145, top: 252, width: 250, height: 55 },
  },
  {
    step: "5",
    title: "Open Medical Practicioner Fields",
    body: "Wait for the editor to load, then scroll down. If the fields are hidden, select Meta Boxes and enlarge the panel with Drag to resize.",
    image: "02-practitioner-editor-reloaded.png",
    focus: { left: 56, top: 440, width: 785, height: 202 },
  },
  {
    step: "6",
    title: "Choose only approved service categories",
    body: "Check the approved Medical Service category or categories. Leave unrelated boxes alone. Stop and ask if the needed category does not exist.",
    image: "04-practitioner-fields.png",
    focus: { left: 55, top: 392, width: 575, height: 242 },
  },
  {
    step: "7",
    title: "Update the practice and public name",
    body: "Medical Center is the public practice name. Medical Practitioner Full Name is the public name and credentials. Verify every character.",
    image: "05-practitioner-name-practice.png",
    focus: { left: 55, top: 454, width: 785, height: 102 },
  },
  {
    step: "8",
    title: "Edit the description in Visual mode",
    body: "Select Visual before editing. Replace only the approved text. Re-read the first and last sentence after pasting. Do not use the Text or HTML tab.",
    image: "06-practitioner-description.png",
    focus: { left: 55, top: 423, width: 785, height: 215 },
  },
  {
    step: "9",
    title: "Add only approved public contact details",
    body: "Use Visual mode. Add approved website, email, phone, or location details. Test every link and never publish private information.",
    image: "07-practitioner-contact.png",
    focus: { left: 55, top: 427, width: 785, height: 211 },
  },
  {
    step: "10",
    title: "Review only the fields you touched",
    body: "Check category, practice, name, credentials, description, and contact links. Do not change the page address, SEO settings, code, or unrelated controls.",
    image: "05-practitioner-name-practice.png",
    focus: { left: 55, top: 454, width: 785, height: 102 },
  },
  {
    step: "11",
    title: "Select Save once—and wait",
    body: "Use the blue Save button in the upper-right corner. Wait for the saved confirmation. If WordPress displays an error, stop and take a screenshot.",
    image: "02-practitioner-editor-top.png",
    focus: { left: 640, top: 190, width: 47, height: 30 },
  },
  {
    step: "12",
    title: "Clear only the record’s applicable cache",
    body: "Return to the practitioner list. Hover over the updated row and select its Clear Cache action. Server-wide purges are covered in the separate cache lesson.",
    image: "01-practitioner-list-1600x900.png",
    focus: { left: 145, top: 252, width: 250, height: 55 },
  },
  {
    step: "13",
    title: "Verify the exact public directory",
    body: "Open rebekahspureliving.com/practitioners/. Search for the person and confirm the name, practice, category, and description preview.",
    image: "10-public-practitioner-filtered.png",
    focus: { left: 116, top: 374, width: 535, height: 270 },
  },
  {
    step: "14",
    title: "Open the full listing and check a phone",
    body: "Select View full listing. Confirm the complete description and contact information. Repeat the check on a phone and test every link.",
    image: "11-public-practitioner-expanded.png",
    focus: { left: 126, top: 173, width: 256, height: 468 },
  },
  {
    step: "DONE",
    title: "Finish every website edit the same way",
    body: "SAVE  →  CLEAR THE APPLICABLE CACHE  →  VERIFY THE EXACT PUBLIC PAGE ON DESKTOP AND PHONE",
    image: null,
  },
];

async function blobFromFile(filePath) {
  const bytes = await fs.readFile(filePath);
  return bytes.buffer.slice(bytes.byteOffset, bytes.byteOffset + bytes.byteLength);
}

function addBox(slide, name, position, fill, lineFill = fill, radius = "rounded-xl") {
  return slide.shapes.add({
    geometry: "roundRect",
    name,
    position,
    fill,
    line: { style: "solid", fill: lineFill, width: 1 },
    borderRadius: radius,
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

async function addImage(slide, fileName) {
  const imagePath = path.join(captureRoot, fileName);
  addBox(slide, "screen-frame", { left: 38, top: 154, width: 820, height: 510 }, C.white, C.sage);
  slide.images.add({
    blob: await blobFromFile(imagePath),
    contentType: "image/png",
    alt: `Live WordPress or public-site capture for ${fileName}`,
    fit: "contain",
    position: { left: 51, top: 167, width: 794, height: 484 },
  });
}

function addFocus(slide, focus) {
  if (!focus) return;
  slide.shapes.add({
    geometry: "roundRect",
    name: "look-here-highlight",
    position: focus,
    fill: "none",
    line: { style: "solid", fill: C.honey, width: 5 },
    borderRadius: "rounded-lg",
  });
  addBox(slide, "look-here-label", {
    left: focus.left,
    top: Math.max(142, focus.top - 28),
    width: 112,
    height: 30,
  }, C.honey, C.honey, "rounded-lg");
  addText(slide, "look-here-text", "LOOK HERE", {
    left: focus.left + 8,
    top: Math.max(148, focus.top - 22),
    width: 96,
    height: 18,
  }, {
    fontFamily: "Aptos",
    fontSize: 12,
    bold: true,
    color: C.ink,
    textAlign: "center",
  });
}

function addHeader(slide, scene, index) {
  addText(slide, "library-label", "REBEKAH'S WEBSITE EDITING LIBRARY", { left: 38, top: 28, width: 640, height: 22 }, {
    fontFamily: "Aptos",
    fontSize: 13,
    bold: true,
    color: C.leaf,
  });
  addText(slide, "scene-title", scene.title, { left: 38, top: 61, width: 1150, height: 68 }, {
    fontFamily: "Georgia",
    fontSize: 34,
    bold: true,
    color: C.pine,
  });
  const badge = addBox(slide, "step-badge", { left: 1135, top: 22, width: 105, height: 46 }, C.honey, C.honey, "rounded-2xl");
  addText(slide, "step-label", scene.step, { left: 1143, top: 33, width: 89, height: 24 }, {
    fontFamily: "Aptos",
    fontSize: scene.step.length > 3 ? 14 : 18,
    bold: true,
    color: C.ink,
    textAlign: "center",
  });
  addText(slide, "footer", `Practitioner pilot v1.1  •  ${index + 1} of ${scenes.length}`, { left: 910, top: 684, width: 330, height: 18 }, {
    fontFamily: "Aptos",
    fontSize: 10,
    color: C.leaf,
    textAlign: "right",
  });
}

async function buildSlide(presentation, scene, index) {
  const slide = presentation.slides.add();
  slide.background.fill = C.cream;
  addHeader(slide, scene, index);

  if (!scene.image) {
    if (index === 0) {
      const logoBytes = await blobFromFile(logoPath);
      slide.images.add({
        blob: logoBytes,
        contentType: "image/png",
        alt: "Rebekah's Health and Nutrition logo",
        fit: "contain",
        position: { left: 78, top: 175, width: 420, height: 220 },
      });
      addBox(slide, "outcome-card", { left: 570, top: 185, width: 610, height: 310 }, C.white, C.sage);
      addText(slide, "outcome-label", "WHAT YOU WILL BE ABLE TO DO", { left: 610, top: 225, width: 520, height: 26 }, {
        fontFamily: "Aptos",
        fontSize: 14,
        bold: true,
        color: C.leaf,
      });
      addText(slide, "outcome-copy", scene.body, { left: 610, top: 276, width: 500, height: 165 }, {
        fontFamily: "Aptos",
        fontSize: 26,
        color: C.ink,
      });
      addText(slide, "duration", "Beginner lesson  •  Live website  •  About 7 minutes", { left: 570, top: 525, width: 610, height: 32 }, {
        fontFamily: "Aptos",
        fontSize: 16,
        bold: true,
        color: C.berry,
        textAlign: "center",
      });
    } else if (index === 2) {
      addBox(slide, "url-card", { left: 110, top: 205, width: 1060, height: 250 }, C.white, C.sage);
      addText(slide, "live-label", "LIVE WORDPRESS LOGIN", { left: 160, top: 245, width: 960, height: 32 }, {
        fontFamily: "Aptos",
        fontSize: 17,
        bold: true,
        color: C.leaf,
        textAlign: "center",
      });
      addText(slide, "live-url", "rebekahspureliving.com/wp-admin", { left: 160, top: 302, width: 960, height: 56 }, {
        fontFamily: "Aptos",
        fontSize: 34,
        bold: true,
        color: C.pine,
        textAlign: "center",
      });
      addText(slide, "live-note", "Confirm this address before changing the live site.", { left: 160, top: 377, width: 960, height: 30 }, {
        fontFamily: "Aptos",
        fontSize: 18,
        color: C.berry,
        textAlign: "center",
      });
    } else {
      addBox(slide, "closing-card", { left: 95, top: 190, width: 1090, height: 325 }, C.pine, C.pine);
      addText(slide, "closing-copy", scene.body, { left: 145, top: 260, width: 990, height: 150 }, {
        fontFamily: "Aptos",
        fontSize: 31,
        bold: true,
        color: C.white,
        textAlign: "center",
      });
      addText(slide, "closing-help", "If any check fails, take a screenshot and contact Blue Nova before changing anything again.", { left: 210, top: 545, width: 860, height: 46 }, {
        fontFamily: "Aptos",
        fontSize: 18,
        color: C.berry,
        textAlign: "center",
      });
    }
    return;
  }

  await addImage(slide, scene.image);
  addFocus(slide, scene.focus);
  addBox(slide, "instruction-card", { left: 892, top: 154, width: 348, height: 510 }, C.white, C.sage);
  addText(slide, "instruction-label", "DO THIS", { left: 926, top: 190, width: 280, height: 28 }, {
    fontFamily: "Aptos",
    fontSize: 14,
    bold: true,
    color: C.leaf,
  });
  addText(slide, "instruction-copy", scene.body, { left: 926, top: 238, width: 280, height: 330 }, {
    fontFamily: "Aptos",
    fontSize: 21,
    color: C.ink,
  });
  addText(slide, "safety", "If unsure: stop before saving.", { left: 926, top: 601, width: 280, height: 28 }, {
    fontFamily: "Aptos",
    fontSize: 15,
    bold: true,
    color: C.berry,
  });
}

async function main() {
  await fs.mkdir(outputRoot, { recursive: true });
  const presentation = Presentation.create({ slideSize: { width: 1280, height: 720 } });
  for (let i = 0; i < scenes.length; i += 1) {
    await buildSlide(presentation, scenes[i], i);
  }

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
  await pptx.save(path.join(root, "Practitioner-Editing-Video-v1.1.pptx"));
}

main().catch((error) => {
  console.error(error);
  process.exitCode = 1;
});
