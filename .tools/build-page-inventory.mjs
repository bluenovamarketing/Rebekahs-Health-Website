import fs from "node:fs/promises";
import { SpreadsheetFile, Workbook } from "@oai/artifact-tool";

const outDir = "output/page-inventory";
await fs.mkdir(outDir, { recursive: true });

const wb = Workbook.create();
const pages = wb.worksheets.add("Page Inventory");
const support = wb.worksheets.add("Templates & Global");
const workflow = wb.worksheets.add("Recommended Workflow");

const rows = [
  [1,"Home","/","Core","Design complete","Implement","High","Combined Homepage v1.21 is the production reference.","Homepage title/meta, local wellness intent, four-store trust signals, internal links, Organization/LocalBusiness structure.","Implement in WordPress; optimize 56 MB hero video; connect social feeds and forms."],
  [2,"Our Story / Meet Rebekah","/our-story/","Core","Design complete","Implement","High","Our Story v1.1.6 is approved. Merge Meet the Owner into this canonical page.","Founder/entity signals, Michigan wellness-store history, credentials, E-E-A-T, factual milestones, internal links.","Implement in WordPress; redirect /meet-the-owner/; validate researched facts with Rebekah."],
  [3,"Locations","/locations/","Locations","Needs design","Not started","High","Hub for all four stores.","Michigan location intent, clear NAP information, store comparison, links to each location.","Create concise hub using the approved design system."],
  [4,"Lapeer Location","/locations/lapeer/","Locations","Needs design","Not started","High","Confirmed individual store page.","Unique local copy, address/phone/hours/map, services, photos, events, reviews, LocalBusiness schema.","Research and build in its own task using the shared location template."],
  [5,"Grand Blanc Location","/locations/grand-blanc/","Locations","Needs design","Not started","High","Confirmed individual store page.","Unique local copy, address/phone/hours/map, services, photos, events, reviews, LocalBusiness schema.","Research and build after the location template is approved."],
  [6,"Clarkston Location","/locations/clarkston/","Locations","Needs design","Not started","High","Confirmed individual store page.","Unique local copy, address/phone/hours/map, services, photos, events, reviews, LocalBusiness schema.","Research and build after the location template is approved."],
  [7,"Lake Orion Location","/locations/lake-orion/","Locations","Needs design","Not started","High","Confirmed individual store page.","Unique local copy, address/phone/hours/map, services, photos, events, reviews, LocalBusiness schema.","Research and build after the location template is approved."],
  [8,"Classes & Events","/events/","Content archive","Needs design","Not started","High","Client priority; existing event content must be preserved.","Local class/event intent, crawlable event copy, Event schema where appropriate, location and topic linking.","Confirm registration scope; design archive and event-detail template together."],
  [9,"Wellness Source Blog","/blog/","Content archive","Minimal design","Not started","Medium","Very light design: intro, useful filters/search, and post grid.","Archive title/meta, topical organization, internal linking, author/reviewer signals; SEO value primarily comes from posts.","Build after global styles; avoid overdesigning the archive."],
  [10,"In-Store Products","/in-store-products/","Products","Needs design","Not started","High","Non-transactional discovery page; no ecommerce.","Product-category discovery, featured brands, Signature Line, store availability caveat, visit/call conversion paths.","Define categories and brands; build a rich informational page without prices, cart, or checkout."],
  [11,"Practitioners","/practitioners/","Directory","Needs design","Not started","Medium","Existing structured directory with 28 records.","Practitioner specialties, locations, credentials, unique bios, internal links; avoid thin/duplicate profiles.","Decide whether individual profiles stay public; build archive and detail template together."],
  [12,"Contact Us","/contact-us/","Utility","Light redesign","Not started","High","Reuse and retest the existing contact form.","Clear store/contact intent, NAP consistency, spam/consent controls, conversion tracking.","Build after location data is finalized; verify notifications and delivery."],
  [13,"Privacy Policy","/privacy-policy/","Legal","Reuse / quick revision","Not started","Medium","Current policy will be used and fitted to the new design.","Clear crawlable policy, accurate services/cookies/forms/vendors, updated effective date.","Apply legal-page template; revise only where site functionality requires it; seek legal review for material changes."],
  [14,"Refund & Returns Policy","/refund_returns/","Legal","Reuse current","Not started","Medium","Current policy will remain available even while onsite ecommerce is deferred.","Accurate scope and contact process; prevent conflicts with external partner purchase policies.","Apply legal-page template and confirm policy still matches actual operations."],
  [15,"Terms & Conditions","/terms-conditions/","Legal","New template/content","Not started","Medium","New page required.","Website use, intellectual property, external links, purchases/referrals, limitations, governing terms.","Draft starter copy based on actual site functionality; obtain qualified legal review before launch."],
  [16,"Disclaimer","/disclaimer/","Legal","New template/content","Not started","High","New wellness/educational disclaimer required.","Educational-not-medical-advice language, claims boundaries, professional consultation, affiliate disclosure alignment.","Draft with claims-safe language; obtain qualified legal review before launch."],
  [17,"Shop Fullscript","/shop-fullscript/","Affiliate","Proposed landing page","Verify program","Medium","External transaction; no onsite cart.","Useful original explanation, appropriate audience, ordering process, affiliate disclosure, external-link labeling.","Confirm exact URL/account/disclosures, then build a concise affiliate landing page."],
  [18,"Shop Designs for Health","/shop-designs-for-health/","Affiliate","Proposed landing page","Verify program","Medium","External transaction; no onsite cart.","Useful original explanation, ordering process, product/medical claim controls, affiliate disclosure.","Confirm exact URL/account/disclosures, then build from the affiliate-page template."],
  [19,"Shop LifeWave","/shop-lifewave/","Affiliate","Proposed landing page","Verify program","Medium","Currently promoted through the Practitioners menu.","Original explanatory content, careful wellness claims, ordering relationship, affiliate disclosure.","Confirm exact URL/account/disclosures, then build from the affiliate-page template."],
  [20,"Peptides & Injectables","/peptides-injectables/","Referral / affiliate","Proposed landing page","Verify program","High","Current navigation and approved homepage reference injections.","Especially careful medical/eligibility language, provider relationship, disclosures, external/referral CTA; avoid unsupported claims.","Clarify whether this is affiliate, referral, direct contact, or clinical service before drafting."],
];

const headers = ["#","Page","Proposed URL","Family","Design Status","Build Status","Priority","Notes / Decisions","SEO & Content Focus","Next Step"];
pages.getRange("A1:J1").merge();
pages.getRange("A1").values = [["Rebekah's Health & Nutrition - Phase One Page Inventory"]];
pages.getRange("A2:J2").merge();
pages.getRange("A2").values = [["Non-ecommerce scope. Rows 17-20 are proposed affiliate/referral landing pages pending program verification."]];
pages.getRange("A4:J4").values = [headers];
pages.getRange(`A5:J${rows.length+4}`).values = rows;
pages.tables.add(`A4:J${rows.length+4}`, true, "PageInventoryTable");
pages.freezePanes.freezeRows(4);
pages.showGridLines = false;
pages.getRange("A1:J1").format = {fill:"#195C3B",font:{bold:true,color:"#FFFFFF",size:16},rowHeight:30};
pages.getRange("A2:J2").format = {fill:"#EEF5E8",font:{italic:true,color:"#33413A"},wrapText:true,rowHeight:30};
pages.getRange("A4:J4").format = {fill:"#E5E7EB",font:{bold:true,color:"#111827"},wrapText:true};
pages.getRange(`A5:J${rows.length+4}`).format = {font:{size:10},verticalAlignment:"top",wrapText:true};
pages.getRange(`A5:A${rows.length+4}`).format.horizontalAlignment = "center";
pages.getRange(`F5:F${rows.length+4}`).dataValidation = {rule:{type:"list",values:["Not started","Implement","In progress","Ready for review","Approved","Verify program"]}};
pages.getRange(`G5:G${rows.length+4}`).dataValidation = {rule:{type:"list",values:["High","Medium","Low"]}};
const widths = [42,165,175,105,115,105,70,285,340,290];
for (let i=0;i<widths.length;i++) pages.getRangeByIndexes(0,i,rows.length+4,1).format.columnWidthPx=widths[i];
pages.getRange(`A5:J${rows.length+4}`).format.rowHeight = 82;

const supportRows = [
  ["Template","Blog post","21 existing posts","Build once; preserve URL, author, date, category, image and SEO data; strengthen expertise, sourcing and internal links."],
  ["Template","Event detail","Existing and future events","Build once with location/date/organizer structure and Event schema where appropriate."],
  ["Template","Practitioner profile","28 practitioner records","Build once if profiles remain public; require meaningful unique bios and credentials."],
  ["Template","Location detail","Four store pages","Approve Lapeer first as the master, then adapt with genuinely unique store content."],
  ["Template","Affiliate landing page","Four proposed partner/referral pages","Reusable structure, but every page needs original partner-specific copy and disclosure."],
  ["Template","Legal page","Privacy, Returns, Terms, Disclaimer","Shared clean typography and navigation; content differs per policy."],
  ["Global","Header and navigation","Sitewide","Use the final Combined Homepage v1.21 direction and approved page labels."],
  ["Global","Footer","Sitewide","Include locations, policies, contact details, social links and legal navigation."],
  ["Global","Newsletter and text signup","Sitewide / selected pages","Preferred-store field is required; confirm integrations, consent, routing and delivery."],
  ["Global","Instagram and TikTok feeds","Homepage / selected pages","Requires plugin setup and account authorization; use crawlable surrounding copy."],
  ["Global","Search and 404","Sitewide","Create useful search/empty states and a branded 404 with recovery links."],
  ["Global","SEO system","Sitewide","Titles/metas, canonicals, redirects, schema, breadcrumbs, XML sitemaps, image SEO, internal linking and QA."],
];
support.getRange("A1:D1").merge(); support.getRange("A1").values=[["Reusable Templates and Global Work"]];
support.getRange("A3:D3").values=[["Type","Component","Applies To","Notes"]];
support.getRange(`A4:D${supportRows.length+3}`).values=supportRows;
support.tables.add(`A3:D${supportRows.length+3}`,true,"SupportWorkTable");
support.freezePanes.freezeRows(3); support.showGridLines=false;
support.getRange("A1:D1").format={fill:"#195C3B",font:{bold:true,color:"#FFFFFF",size:16},rowHeight:30};
support.getRange("A3:D3").format={fill:"#E5E7EB",font:{bold:true},wrapText:true};
support.getRange(`A4:D${supportRows.length+3}`).format={verticalAlignment:"top",wrapText:true,rowHeight:62};
[95,180,190,560].forEach((w,i)=>support.getRangeByIndexes(0,i,supportRows.length+3,1).format.columnWidthPx=w);

const workflowRows = [
  [1,"Lock shared standards","One setup task","Create the reusable design system, SEO checklist, copy/claims rules, schema plan and internal-link map from Homepage v1.21 and Our Story v1.1.6."],
  [2,"Implement approved pages","One task per page","Home, then Our Story. These establish the WordPress global styles and reusable sections."],
  [3,"Build page families","One task per family","Locations hub + Lapeer master template; Events archive + detail; Practitioners archive + detail; affiliate template + first verified partner."],
  [4,"Adapt family pages","One task per page","Grand Blanc, Clarkston and Lake Orion each get their own research, unique copy and SEO—not search-and-replace duplicates."],
  [5,"Build supporting pages","One task per substantive page","In-Store Products, Contact, affiliate/referral pages and legal content. Blog archive can share a small implementation task."],
  [6,"Perform integrated SEO QA","One final sitewide task","Check crawlability, metadata, canonicals, redirects, schema, internal links, mobile, accessibility, forms, analytics and performance."],
];
workflow.getRange("A1:D1").merge(); workflow.getRange("A1").values=[["Recommended Codex Task Workflow"]];
workflow.getRange("A2:D2").merge(); workflow.getRange("A2").values=[["Use one task per substantive page or page family. Begin every task by referencing Homepage v1.21, Our Story v1.1.6, the brand kit, this Sheet, and the approved image-source rules."]];
workflow.getRange("A4:D4").values=[["Order","Stage","Task Structure","What Happens"]];
workflow.getRange("A5:D10").values=workflowRows;
workflow.tables.add("A4:D10",true,"WorkflowTable");
workflow.freezePanes.freezeRows(4); workflow.showGridLines=false;
workflow.getRange("A1:D1").format={fill:"#195C3B",font:{bold:true,color:"#FFFFFF",size:16},rowHeight:30};
workflow.getRange("A2:D2").format={fill:"#EEF5E8",font:{italic:true,color:"#33413A"},wrapText:true,rowHeight:44};
workflow.getRange("A4:D4").format={fill:"#E5E7EB",font:{bold:true},wrapText:true};
workflow.getRange("A5:D10").format={verticalAlignment:"top",wrapText:true,rowHeight:72};
[55,190,190,610].forEach((w,i)=>workflow.getRangeByIndexes(0,i,10,1).format.columnWidthPx=w);

const inspection = await wb.inspect({kind:"table",range:"Page Inventory!A1:J24",include:"values,formulas",tableMaxRows:25,tableMaxCols:10,maxChars:12000});
console.log(inspection.ndjson);
const errors = await wb.inspect({kind:"match",searchTerm:"#REF!|#DIV/0!|#VALUE!|#NAME\\?|#N/A",options:{useRegex:true,maxResults:100},summary:"formula error scan"});
console.log(errors.ndjson);
for (const name of ["Page Inventory","Templates & Global","Recommended Workflow"]) {
  const preview = await wb.render({sheetName:name,autoCrop:"all",scale:1,format:"png"});
  await fs.writeFile(`${outDir}/${name.replaceAll(" ","-")}.png`,new Uint8Array(await preview.arrayBuffer()));
}
const file = await SpreadsheetFile.exportXlsx(wb);
await file.save(`${outDir}/Rebekahs-Phase-One-Page-Inventory.xlsx`);
