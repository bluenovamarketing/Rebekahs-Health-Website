import fs from "node:fs/promises";
import path from "node:path";
import { pathToFileURL } from "node:url";

const artifactModule = "C:/Users/todda/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/node_modules/@oai/artifact-tool/dist/artifact_tool.mjs";
const { FileBlob, PresentationFile } = await import(pathToFileURL(artifactModule).href);

const root = path.resolve("client-handoff/website-editing-instructions/01-practitioners/add-new-practitioner-video-v1.1");
const workRoot = path.join(root, "work");
const previewRoot = path.join(root, "deck-preview");
const starterPath = path.join(workRoot, "template-starter.pptx");
const outputPath = path.join(root, "Add-New-Practitioner-or-Practice-v1.1.pptx");

function byName(slide, name) {
  const shape = slide.shapes.items.find((item) => item.name === name);
  if (!shape) throw new Error(`Missing shape '${name}' on slide ${slide.index + 1}.`);
  return shape;
}

function text(slide, name, value) {
  byName(slide, name).text = value;
}

async function saveBlob(blob, filePath) {
  await fs.writeFile(filePath, new Uint8Array(await blob.arrayBuffer()));
}

async function main() {
  await fs.mkdir(previewRoot, { recursive: true });
  const presentation = await PresentationFile.importPptx(await FileBlob.load(starterPath));
  const s = presentation.slides.items;
  if (s.length !== 16) throw new Error(`Expected 16 slides, found ${s.length}.`);

  for (const [index, slide] of s.entries()) {
    text(slide, "page", `Add practitioner v1.1  •  ${index + 1}/16`);
  }

  text(s[0], "title", "Add a new practitioner or practice listing");
  text(s[0], "start-copy", "Create one approved directory listing, keep it as a draft until it is complete, clear the applicable cache, and verify the live result.");
  text(s[0], "start-note", "Beginner lesson  •  Live website  •  Full-screen version");

  text(s[1], "title", "Required WordPress access");
  text(s[1], "role-value", "Editor or Administrator");
  text(s[1], "enough", "Editor access is enough for this task.");
  text(s[1], "role-stop", "If Medical Practicioners is missing, stop and ask the website administrator for help. User accounts are covered separately.");

  text(s[2], "title", "Sign in to the live website");
  text(s[2], "login-url", "rebekahspureliving.com/wp-admin");
  text(s[2], "login-note", "Use your own login. Confirm the live domain. Do not use an old staging address.");

  text(s[3], "title", "Open All Medical Practicioners");
  text(s[3], "written-steps-s04-body", "1. Click Medical Practicioners.\n2. Click All Medical Practicioners.\n3. Find Add New at the top.");

  text(s[4], "title", "Choose Add New Medical Practitioner");
  text(s[4], "edit-cue-instruction", "1. Find Add New Medical Practitioner.\n2. Create only one approved record.");
  text(s[4], "edit-cue-action", "3. Click Add New once.");

  text(s[5], "title", "Keep the new record as a draft");
  text(s[5], "written-steps-s06-body", "1. Keep the record as Draft.\n2. Open Medical Practicioner fields.\n3. Stop if the fields are missing.");

  text(s[6], "title", "Choose only approved service categories");
  text(s[6], "written-steps-s07-body", "1. Find Medical Service.\n2. Check only approved categories.\n3. Do not create or guess a category.");

  text(s[7], "title", "Enter the practice and public name");
  text(s[7], "written-steps-s08-body", "1. Enter the approved Medical Center.\n2. Enter the exact public name.\n3. Match spelling and credentials.");

  text(s[8], "title", "Add the approved description in Visual mode");
  text(s[8], "written-steps-s09-body", "1. Select Visual.\n2. Add only approved public copy.\n3. Preserve the paragraph breaks.");

  text(s[9], "title", "Add only approved public contact details");
  text(s[9], "written-steps-s10-body", "1. Add approved public details.\n2. Test every phone and web link.\n3. Remove placeholders or private data.");

  text(s[10], "title", "Review every field before publishing");
  text(s[10], "written-steps-s11-body", "1. Re-read every field.\n2. Compare with the approved information.\n3. Leave as Draft if anything is uncertain.");

  text(s[11], "title", "Publish or Save once—and wait");
  text(s[11], "written-steps-s12-body", "1. Click Publish or Save once.\n2. Wait for confirmation.\n3. Do not click repeatedly.");

  text(s[12], "title", "Clear only the applicable cache");
  text(s[12], "cache-cue-instruction", "1. Return to the practitioner list.\n2. Find the new record.");
  text(s[12], "cache-cue-action", "3. Click Clear Cache once.");

  text(s[13], "title", "Verify the exact public directory");
  text(s[13], "written-steps-s14-body", "1. Search the name, practice, or city.\n2. Confirm the category and details.\n3. Test the public links.");

  text(s[14], "title", "Open the full listing and check a phone");
  text(s[14], "written-steps-s15-body", "1. Click View full listing.\n2. Check every detail and link.\n3. Repeat the check on a phone.");

  text(s[15], "title", "Always finish with the same three checks");
  text(s[15], "closing-main", "SAVE  →  CLEAR APPLICABLE CACHE  →  VERIFY LIVE");
  text(s[15], "closing-sub", "Check the exact /practitioners/ directory on desktop and phone. There is no separate public practitioner page to manage.");
  text(s[15], "closing-help", "If the live result is wrong or missing, stop and ask for help.");

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
