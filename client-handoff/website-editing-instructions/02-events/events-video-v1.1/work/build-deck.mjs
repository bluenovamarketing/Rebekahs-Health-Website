import fs from "node:fs/promises";
import path from "node:path";
import { pathToFileURL } from "node:url";

const artifactModule = "C:/Users/todda/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/node_modules/@oai/artifact-tool/dist/artifact_tool.mjs";
const { FileBlob, PresentationFile } = await import(pathToFileURL(artifactModule).href);

const root = path.resolve("client-handoff/website-editing-instructions/02-events/events-video-v1.1");
const projectRoot = path.resolve(".");
const workRoot = path.join(root, "work");
const previewRoot = path.join(root, "deck-preview");
const starterPath = path.join(workRoot, "template-starter.pptx");
const outputPath = path.join(root, "Create-or-Update-an-Event-v1.1.pptx");

const C = { pine: "#174C3C", honey: "#D6A33A", cream: "#FFFDF6", ink: "#26342E", sage: "#A9C6A0", white: "#FFFFFF" };

function byName(slide, name) {
  const shape = slide.shapes.items.find((item) => item.name === name);
  if (!shape) throw new Error(`Missing shape '${name}' on slide ${slide.index + 1}.`);
  return shape;
}

function setText(slide, name, value) { byName(slide, name).text = value; }

function addShape(slide, name, position, fill, lineFill = fill, lineWidth = 1) {
  return slide.shapes.add({ geometry: "roundRect", name, position, fill, line: { style: "solid", fill: lineFill, width: lineWidth }, borderRadius: "rounded-xl" });
}

function addText(slide, name, value, position, style) {
  const shape = slide.shapes.add({ geometry: "textbox", name, position, fill: "none", line: { style: "solid", fill: "none", width: 0 } });
  shape.text = value;
  shape.text.style = style;
  return shape;
}

async function blobFromFile(filePath) {
  const bytes = await fs.readFile(filePath);
  return bytes.buffer.slice(bytes.byteOffset, bytes.byteOffset + bytes.byteLength);
}

async function addLessonBody(slide, imagePath, steps, options = {}) {
  addShape(slide, "body-cover", { left: 28, top: 95, width: 1864, height: 925 }, C.cream, C.cream, 0);
  addShape(slide, "event-screen-frame", { left: 45, top: 125, width: 1280, height: 810 }, C.white, C.sage, 3);
  slide.images.add({
    blob: await blobFromFile(imagePath),
    contentType: "image/png",
    alt: options.alt || "Rebekah's live website event screen",
    fit: options.fit || "contain",
    position: options.position || { left: 58, top: 138, width: 1254, height: 784 },
  });
  addShape(slide, "written-panel", { left: 1360, top: 235, width: 475, height: 500 }, C.cream, C.honey, 5);
  addText(slide, "written-heading", "WRITTEN STEPS", { left: 1392, top: 272, width: 411, height: 36 }, { fontFamily: "Aptos", fontSize: 22, bold: true, color: C.pine, textAlign: "center" });
  addText(slide, "written-body", steps.map((step, i) => `${i + 1}. ${step}`).join("\n"), { left: 1400, top: 340, width: 395, height: 330 }, { fontFamily: "Aptos", fontSize: options.fontSize || 20, color: C.ink, verticalAlignment: "middle" });
}

async function saveBlob(blob, filePath) { await fs.writeFile(filePath, new Uint8Array(await blob.arrayBuffer())); }

async function main() {
  await fs.mkdir(previewRoot, { recursive: true });
  const presentation = await PresentationFile.importPptx(await FileBlob.load(starterPath));
  const s = presentation.slides.items;
  if (s.length !== 13) throw new Error(`Expected 13 slides, found ${s.length}.`);

  for (const [index, slide] of s.entries()) {
    setText(slide, "page", `Event editing v1.1  •  ${index + 1}/13`);
  }

  setText(s[0], "title", "Create or update an event");
  setText(s[0], "start-copy", "Create or update an approved event, preview it carefully, clear the applicable cache, and verify the live Events page.");
  setText(s[0], "start-note", "Beginner lesson  •  Live website  •  Full-screen version");

  setText(s[1], "title", "Required WordPress access");
  setText(s[1], "role-value", "Editor or Administrator");
  setText(s[1], "enough", "Editor access is enough for this task.");
  setText(s[1], "role-stop", "If Events is missing, stop and ask the website administrator for help. User accounts are covered separately.");

  setText(s[2], "title", "Sign in to the live website");
  setText(s[2], "login-url", "rebekahspureliving.com/wp-admin");
  setText(s[2], "login-note", "Use your own login. Confirm the live domain. Do not use an old staging address.");

  const guideAssets = path.join(projectRoot, "output", "client-guide", "assets");
  const publicAssets = path.join(workRoot, "assets");
  const sceneData = [
    { i: 3, title: "Open Events and choose the correct action", image: path.join(guideAssets, "events-list-header.png"), steps: ["Click Events in the left menu.", "For a new event, click Add New Event.", "For an update, find the correct title and click Edit."] },
    { i: 4, title: "Add the title and description as a draft", image: path.join(guideAssets, "event-editor-top.png"), steps: ["Enter the approved event title.", "Add the complete description in Visual mode.", "Click Save Draft while you work."] },
    { i: 5, title: "Choose the correct store category", image: path.join(guideAssets, "event-editor-top.png"), steps: ["Open Event Categories.", "Choose Clarkston, Grand Blanc, Lake Orion, or Lapeer.", "Do not create or guess a category."] },
    { i: 6, title: "Complete every event detail", image: path.join(guideAssets, "event-details.png"), steps: ["Enter date, time, and time zone.", "Choose venue and organizer; add cost or link if needed.", "Add a 16:9 featured image and alt text."], fontSize: 18 },
    { i: 7, title: "Preview before publishing", image: path.join(guideAssets, "event-sidebar.png"), steps: ["Click Preview.", "Check every detail at desktop size.", "Repeat the preview at phone size."] },
    { i: 8, title: "Publish, update, or cancel safely", image: path.join(guideAssets, "events-list.png"), steps: ["Publish or Update once, then wait.", "For a cancellation, set status to Canceled.", "Add a clear cancellation note; do not immediately delete."], fontSize: 18 },
    { i: 9, title: "Clear only the applicable cache", image: path.join(guideAssets, "events-list-header.png"), steps: ["Return to the Events list.", "Find the event you changed.", "Clear only the applicable page, record, Breeze, or Varnish cache."], fontSize: 18 },
    { i: 10, title: "Verify the exact public Events page", image: path.join(publicAssets, "public-events-desktop.png"), steps: ["Open /events/ with no extra address text.", "Find the event and open it.", "Confirm details, image, and every public link."], fontSize: 18 },
    { i: 11, title: "Repeat the public check on a phone", image: path.join(publicAssets, "public-events-mobile-list.png"), steps: ["Check the location filter.", "Confirm the event card fits and is readable.", "Make sure nothing runs off the side."], position: { left: 250, top: 138, width: 870, height: 784 } },
  ];

  for (const scene of sceneData) {
    setText(s[scene.i], "title", scene.title);
    setText(s[scene.i], "step-text", String(scene.i));
    await addLessonBody(s[scene.i], scene.image, scene.steps, { fontSize: scene.fontSize, position: scene.position });
  }

  setText(s[12], "title", "Always finish with the same three checks");
  setText(s[12], "closing-main", "SAVE  →  CLEAR APPLICABLE CACHE  →  VERIFY LIVE");
  setText(s[12], "closing-sub", "Check the exact public event and /events/ page on desktop and phone.");
  setText(s[12], "closing-help", "If the live result is wrong or missing, stop and ask for help.");

  for (const [index, slide] of s.entries()) {
    const stem = `slide-${String(index + 1).padStart(2, "0")}`;
    const png = await presentation.export({ slide, format: "png", scale: 1 });
    await saveBlob(png, path.join(previewRoot, `${stem}.png`));
    const layout = await slide.export({ format: "layout" });
    await fs.writeFile(path.join(previewRoot, `${stem}.layout.json`), await layout.text());
  }
  const montage = await presentation.export({ format: "webp", montage: true, scale: 1 });
  await saveBlob(montage, path.join(previewRoot, "montage.webp"));
  const pptx = await PresentationFile.exportPptx(presentation);
  await pptx.save(outputPath);
  console.log(outputPath);
}

main().catch((error) => {
  console.error(error.stack || error.message || String(error));
  process.exitCode = 1;
});
