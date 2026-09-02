import fs from "node:fs/promises";
import path from "node:path";
import { pathToFileURL } from "node:url";

const artifactModule = "C:/Users/todda/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/node_modules/@oai/artifact-tool/dist/artifact_tool.mjs";
const { FileBlob, PresentationFile } = await import(pathToFileURL(artifactModule).href);

const projectRoot = path.resolve(".");
const sourceDeck = path.join(projectRoot, "client-handoff", "website-editing-instructions", "02-events", "events-video-v1.1", "Create-or-Update-an-Event-v1.1.pptx");
const C = { pine: "#174C3C", honey: "#D6A33A", cream: "#FFFDF6", ink: "#26342E", sage: "#A9C6A0", berry: "#8D4961", white: "#FFFFFF" };

const lessons = [
  {
    slug: "staff",
    root: path.join(projectRoot, "client-handoff", "website-editing-instructions", "03-staff-access", "staff-user-video-v1.1"),
    output: "Add-Change-or-Remove-a-Staff-WordPress-User-v1.1.pptx",
    pageName: "Staff access v1.1",
    startTitle: "Manage a staff WordPress user safely",
    startCopy: "Give each staff member their own login, assign the minimum safe role, and remove access without losing website content.",
    startNote: "Beginner lesson  |  Administrator only  |  No shared logins",
    roleValue: "Administrator",
    roleEnough: "Only an Administrator should manage user accounts.",
    roleStop: "Do not give Administrator access for routine practitioner or event editing. Editor is sufficient for those tasks.",
    preflightTitle: "Gather the approved account information first",
    preflightLabel: "BEFORE OPENING USERS",
    preflightMain: "Full name  |  unique work email  |  approved role",
    preflightNote: "Each person gets a separate account. Never reuse another person's username or password.",
    scenes: [
      { title: "Open Users and choose the correct action", route: "Users > Add New User", guide: ["To add someone, select Add New User.", "To change or remove someone, select All Users.", "Stop if Users is missing; your account is not an Administrator."], steps: ["Confirm the person's identity.", "Choose add, change, or remove.", "Never edit an unfamiliar Administrator."] },
      { title: "Create a permanent username and unique email", route: "Add New User > Account details", guide: ["Enter a simple username the staff member will recognize.", "Enter that person's unique work email address.", "A username cannot be changed later, so check spelling before continuing."], steps: ["Use one account per person.", "Check the email twice.", "Do not use a shared login."] },
      { title: "Complete the name and invitation settings", route: "Add New User > Name and notification", guide: ["Enter the person's first and last name.", "Leave the secure generated password in place.", "Keep the user-notification option selected so WordPress sends the setup email."], steps: ["Use the real staff name.", "Do not create an easy password.", "Let WordPress send the invitation."] },
      { title: "Choose the minimum role the person needs", route: "Add New User > Role", guide: ["Choose Editor for approved practitioner and event work.", "Choose Administrator only for an owner or trusted website manager who must manage users and settings.", "If the correct access is uncertain, stop and ask Blue Nova."], steps: ["Routine editing: Editor.", "User management: Administrator.", "Never upgrade access just to fix a menu."] },
      { title: "Add the user once and wait for confirmation", route: "Add New User > Add New User button", guide: ["Review every field before clicking the button.", "Select Add New User one time, then wait for the success message.", "If WordPress reports that the username or email already exists, stop and check All Users."], steps: ["Review before submitting.", "Click once and wait.", "Do not create a duplicate account."] },
      { title: "Have the staff member complete the first login", route: "Staff invitation email > Set password > Log in", guide: ["The staff member opens the WordPress invitation email.", "They create a strong password that only they know.", "They sign in at rebekahspureliving.com/wp-admin and confirm the required menu is visible."], steps: ["Use the invitation link.", "Create a private strong password.", "Confirm only the needed menus appear."] },
      { title: "Change an existing role carefully", route: "Users > All Users > Edit", guide: ["Find the exact staff name and email.", "Open Edit and choose the newly approved role.", "Save once, then ask the staff member to sign out and sign back in."], steps: ["Match both name and email.", "Change only the Role field.", "Recheck access after a new login."] },
      { title: "Use password reset instead of sharing passwords", route: "Users > All Users > Send password reset", guide: ["Use Send password reset when the person cannot sign in.", "The staff member follows the private reset email.", "Never ask them to send you their password, and never send a password by text or ordinary email."], steps: ["Send the reset link.", "Staff creates the new password.", "Passwords stay private."] },
      { title: "Remove access without deleting website content", route: "Users > All Users > Delete", guide: ["Confirm the exact departing staff account before selecting Delete.", "Choose Attribute all content to an approved continuing Administrator, such as Rebekah.", "Never choose Delete all content unless Blue Nova has reviewed the account and approved it."], steps: ["Verify the exact account.", "Reassign all content.", "Confirm the old login no longer works."] },
    ],
    closeMain: "CONFIRM PERSON  >  MINIMUM ROLE  >  VERIFY ACCESS",
    closeSub: "Every staff member keeps a separate login. Remove access promptly when employment or duties change.",
    closeHelp: "If you are unsure about a role, an Administrator, or content reassignment, stop and contact Blue Nova."
  },
  {
    slug: "cache",
    root: path.join(projectRoot, "client-handoff", "website-editing-instructions", "04-cache-and-verification", "cache-video-v1.1"),
    output: "Clear-the-Website-Cache-Safely-v1.1.pptx",
    pageName: "Cache safety v1.1",
    startTitle: "Clear the website cache safely",
    startCopy: "Use the smallest safe cache clear, refresh your browser, and verify the exact public page without changing server settings.",
    startNote: "Beginner lesson  |  Live website  |  Use only after saving",
    roleValue: "Editor: record cache  |  Administrator: sitewide cache",
    roleEnough: "Use only the cache control your role and instructions allow.",
    roleStop: "Do not give someone Administrator access only to clear cache. Ask Blue Nova when a sitewide or server purge is required.",
    preflightTitle: "Clear cache only when a saved change is stale",
    preflightLabel: "CACHE IS A VISIBILITY STEP",
    preflightMain: "Save first  |  exact public URL  |  purge only what applies",
    preflightNote: "Cache clearing does not save your work and does not correct wrong content.",
    scenes: [
      { title: "Save the correct item once before clearing cache", route: "Editor > Save, Update, or Publish", guide: ["Finish and review the approved change.", "Select Save, Update, or Publish one time and wait for confirmation.", "Copy the exact public URL that visitors use."], steps: ["Save first.", "Wait for confirmation.", "Keep the exact public URL."] },
      { title: "Start with the smallest applicable cache", route: "Content list > changed row > Clear Cache", guide: ["Return to the list that contains the item you changed.", "Find the exact row and use its Clear Cache action when available.", "Do not clear unrelated pages, products, events, or practitioner records."], steps: ["Match the exact item.", "Use its Clear Cache action.", "Do not purge unrelated content."] },
      { title: "Refresh the browser without changing the URL", route: "Exact public URL > hard refresh", guide: ["Open the exact public URL with no extra question mark or test text.", "On Windows press Control plus F5. On a Mac press Command, Shift, and R.", "Confirm the visible page now shows the saved information."], steps: ["Use the canonical URL.", "Perform one hard refresh.", "Read the visible result."] },
      { title: "Check in a private window when the browser stays stale", route: "Private or Incognito window > exact public URL", guide: ["Open a private or Incognito window.", "Paste the same exact public URL.", "A correct private-window result means the remaining problem was the regular browser cache."], steps: ["Open a private window.", "Use the same URL.", "Do not add cache-busting text."] },
      { title: "Use Breeze only when a broader purge is approved", route: "WordPress admin bar > Breeze > Purge All Cache", guide: ["Use this only when an approved saved change affects a shared page or several pages.", "Select the approved Breeze purge one time and wait.", "Do not change Breeze settings, preload options, minification, or optimization controls."], steps: ["Purge only when warranted.", "Click once and wait.", "Do not change settings."] },
      { title: "Leave Cloudways and Varnish controls to authorized users", route: "Approved Varnish purge control or Blue Nova", guide: ["A Varnish purge may be needed when the correct content remains stale after the page and Breeze cache are cleared.", "Use only the approved purge control if you were specifically authorized.", "Do not open server settings, restart services, or disable Varnish."], steps: ["Use only an approved purge.", "Never change server settings.", "Ask Blue Nova if the control is missing."] },
      { title: "Never solve cache trouble with random settings", route: "STOP: unrelated cache and optimization controls", guide: ["Do not repeatedly click Update or Publish.", "Do not clear object cache, Redis, CDN, database, image, or plugin caches unless Blue Nova directs you.", "Do not install, disable, or configure optimization plugins."], steps: ["Do not repeat saves.", "Do not purge everything.", "Do not change plugins or servers."] },
      { title: "Verify the visible public result on desktop", route: "Exact public URL > desktop check", guide: ["Read the exact changed text, image, date, link, or listing.", "Click every public link affected by the change.", "Confirm the page fits the screen and does not run off the side."], steps: ["Check the visible change.", "Test affected links.", "Confirm the layout remains correct."] },
      { title: "Repeat the check on a phone and know when to stop", route: "Exact public URL > phone-size check", guide: ["Check the same visible result at phone size.", "Confirm text, images, buttons, and links remain readable and usable.", "If the saved result is still missing, stop and contact Blue Nova instead of purging again."], steps: ["Repeat at phone size.", "Test buttons and links.", "Escalate if still stale."] },
    ],
    closeMain: "SAVE  >  CLEAR APPLICABLE CACHE  >  VERIFY LIVE",
    closeSub: "Open the exact public URL with no extra address text, then check desktop and phone.",
    closeHelp: "If the visible result is still wrong or missing, stop and contact Blue Nova."
  }
];

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
async function saveBlob(blob, filePath) { await fs.writeFile(filePath, new Uint8Array(await blob.arrayBuffer())); }

async function buildLesson(lesson) {
  await fs.mkdir(path.join(lesson.root, "deck-preview"), { recursive: true });
  const presentation = await PresentationFile.importPptx(await FileBlob.load(sourceDeck));
  const slides = presentation.slides.items;
  if (slides.length !== 13) throw new Error(`Expected 13 source slides; found ${slides.length}.`);
  for (const [index, slide] of slides.entries()) {
    setText(slide, "page", `${lesson.pageName}  |  ${index + 1}/13`);
    slide.speakerNotes.textFrame.setText(`[Sources]\n- Rebekah project training standard and client records in CLIENT-NOTES.md.\n- Established WordPress user-role and cache workflow used by this project.\n- No live WordPress login or website change was used to create this lesson.`);
  }

  setText(slides[0], "title", lesson.startTitle);
  setText(slides[0], "start-copy", lesson.startCopy);
  setText(slides[0], "start-note", lesson.startNote);

  setText(slides[1], "title", "Required WordPress access");
  setText(slides[1], "role-value", lesson.roleValue);
  setText(slides[1], "enough", lesson.roleEnough);
  setText(slides[1], "role-stop", lesson.roleStop);

  setText(slides[2], "title", lesson.preflightTitle);
  setText(slides[2], "login-label", lesson.preflightLabel);
  setText(slides[2], "login-url", lesson.preflightMain);
  setText(slides[2], "login-note", lesson.preflightNote);

  for (let n = 0; n < lesson.scenes.length; n++) {
    const slide = slides[n + 3];
    const scene = lesson.scenes[n];
    setText(slide, "title", scene.title);
    setText(slide, "step-text", String(n + 3));
    setText(slide, "written-heading", "DO THIS");
    setText(slide, "written-body", scene.steps.map((step, i) => `${i + 1}. ${step}`).join("\n"));

    addShape(slide, `guide-cover-${n + 1}`, { left: 58, top: 138, width: 1254, height: 784 }, C.white, C.sage, 2);
    addText(slide, `guide-label-${n + 1}`, "WHERE TO GO", { left: 115, top: 190, width: 1140, height: 38 }, { fontFamily: "Aptos", fontSize: 22, bold: true, color: C.berry, textAlign: "center" });
    addShape(slide, `guide-route-box-${n + 1}`, { left: 115, top: 250, width: 1140, height: 115 }, C.cream, C.honey, 3);
    addText(slide, `guide-route-${n + 1}`, scene.route, { left: 150, top: 280, width: 1070, height: 58 }, { fontFamily: "Aptos Display", fontSize: 29, bold: true, color: C.pine, textAlign: "center", verticalAlignment: "middle" });
    addText(slide, `guide-body-${n + 1}`, scene.guide.map((line, i) => `${i + 1}. ${line}`).join("\n"), { left: 155, top: 425, width: 1050, height: 390 }, { fontFamily: "Aptos", fontSize: 24, color: C.ink, verticalAlignment: "middle" });
    addText(slide, `guide-warning-${n + 1}`, "Follow the steps in order. Stop when the screen does not match.", { left: 155, top: 835, width: 1050, height: 42 }, { fontFamily: "Aptos", fontSize: 18, bold: true, color: C.berry, textAlign: "center" });
  }

  setText(slides[12], "title", lesson.slug === "staff" ? "Finish by confirming the correct access" : "Always finish with the same three checks");
  setText(slides[12], "closing-main", lesson.closeMain);
  setText(slides[12], "closing-sub", lesson.closeSub);
  setText(slides[12], "closing-help", lesson.closeHelp);

  const previewRoot = path.join(lesson.root, "deck-preview");
  for (const [index, slide] of slides.entries()) {
    const stem = `slide-${String(index + 1).padStart(2, "0")}`;
    await saveBlob(await presentation.export({ slide, format: "png", scale: 1 }), path.join(previewRoot, `${stem}.png`));
    const layout = await slide.export({ format: "layout" });
    await fs.writeFile(path.join(previewRoot, `${stem}.layout.json`), await layout.text());
  }
  await saveBlob(await presentation.export({ format: "webp", montage: true, scale: 1 }), path.join(previewRoot, "montage.webp"));
  const outPath = path.join(lesson.root, lesson.output);
  await (await PresentationFile.exportPptx(presentation)).save(outPath);
  console.log(outPath);
}

const requested = process.argv[2];
for (const lesson of lessons.filter((item) => !requested || item.slug === requested)) await buildLesson(lesson);
