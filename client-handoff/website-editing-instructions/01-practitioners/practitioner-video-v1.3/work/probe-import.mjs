import { pathToFileURL } from "node:url";

const artifactModule = "C:/Users/todda/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/node_modules/@oai/artifact-tool/dist/artifact_tool.mjs";
const { FileBlob, PresentationFile } = await import(pathToFileURL(artifactModule).href);
const source = "C:/Users/todda/Blue Nova Projects/Rebekahs Health Website/client-handoff/website-editing-instructions/01-practitioners/practitioner-video-v1.2/Practitioner-Editing-Video-v1.2.pptx";
const presentation = await PresentationFile.importPptx(await FileBlob.load(source));
const slide = presentation.slides.items[13];
const image = slide.images.items[0];
console.log(JSON.stringify({
  slideKeys: Object.keys(slide || {}),
  imageCount: slide.images.items.length,
  keys: Object.keys(image || {}),
  constructor: image?.constructor?.name,
  position: image?.position,
  alt: image?.alt,
  name: image?.name,
}, null, 2));
console.log((await presentation.help("image.replace", { include: ["index", "examples", "notes"], maxChars: 3000 })).ndjson || "");
