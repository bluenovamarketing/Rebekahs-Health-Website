# Client Notes

## 2026-08-17 - Phase Two email response saved

- Reviewed Rebekah's reply to `Phase Two online store: what we need before the 25-product test` and saved the operational decisions locally without retaining the USPS account number or any credentials.
- Rebekah confirmed Clarkston as the sole fulfillment location, 7093 Suite B, Dixie Highway, Clarkston, MI 48346 as the shipping origin, a two-day handling time, continental-U.S.-only shipping, USPS as the carrier, an existing USPS business account, and live address-based checkout rates.
- Updated `PHASE-TWO-MASTER-CHECKLIST.md` and created `PHASE-TWO-CLIENT-RESPONSE-2026-08-17.md` for the 25-product pilot.
- The reply did not address the remaining prerequisites: 25-product Revel preparation, Revel/Kosmos/PayPal/USPS access, USPS service and PO-box choices, operational email, tax settings, backorder and inventory-buffer rules, guest checkout, promotional-feature exclusions, or commercial approvals.
- Client-facing next step: finalize the open decisions and professional fee at the meeting, then have Rebekah's team prepare the 25 products and account access before Blue Nova starts the Kosmos trial or technical pilot.
- Clarification: the phrase transcribed as “S4” was a speech-to-text error, not a request for an SOW. No SOW is part of the active request unless Todd asks for one separately.
- At Todd's direction, saved the USPS business account number in the local, git-ignored `PRIVATE-CLIENT-ACCESS.md`. The value remains excluded from general client notes and committed project documentation.

## 2026-07-23 - Canonical planning Sheet saved at project level

- Confirmed the shared native Google Sheet as the canonical page inventory, prompt library, status tracker and workflow for the project: `https://docs.google.com/spreadsheets/d/13zutb5DxPIvqX4yv30QSBmSVyc7UoiTCIeCoqAKF7hU/edit?usp=sharing`.
- Added the Sheet and its usage rules to the project-level `AGENTS.md` so new Codex tasks opened in this workspace can locate and consult it automatically.
- Future page tasks should read the applicable row and `Copy/Paste Prompt`, follow Homepage v1.21 and Our Story v1.1.6 as the visual direction, handle page-specific SEO during the page task, and defer technical sitewide QA until WordPress assembly.

## 2026-07-22 - Practitioner profile scope confirmed

- Confirmed that the practitioner profile template is required because the site already has an existing practitioner profile system.
- Scope is strictly a visual and usability refresh: make existing profiles attractive, understandable, consistent, responsive and usable to their current extent while preserving existing information and functionality.
- Do not invent or add services, appointment capabilities, practitioner-event relationships, contact methods or other functionality.
- Show related classes/events only when an existing data relationship already supports them. Use appointment language or an appointment CTA only when the existing profile explicitly supports appointments; otherwise preserve current contact information or omit the CTA.
- Confirmed the final reusable-template count as exactly four: blog post, event detail, location detail and practitioner profile.
- Updated the local Phase One inventory and native Google Sheet, including the practitioner row, practitioner copy/paste prompt, template classification and future-workflow wording.

## 2026-07-22 - Page-specific Codex prompts added to planning Sheet

- Added a `Copy/Paste Prompt` column to every row in the native Google Sheet page inventory so Todd can launch 3-5 page tasks concurrently without rewriting project context.
- Each of the 20 prompts includes the Sheet link; the approved Homepage v1.21 and Our Story v1.1.6 references; page-specific scope and decisions; SEO/content research requirements; metadata, heading, internal-link, image and schema deliverables; claims-safe language; responsive mockup and verification requirements; ecommerce exclusion; and the instruction not to implement in WordPress unless explicitly requested.
- Tailored the prompts for each page family, including unique local-store content, minimal Blog design, non-transactional In-Store Products, legal-review caveats and affiliate/referral verification requirements.
- Rebuilt only the native `PageInventory` table metadata to extend it through the new prompt column while preserving all page data and the existing Build Status and Priority dropdowns.

## 2026-07-22 - Workflow clarification

- Removed the separate `Shared design and SEO standards` phase from the Google Sheet workflow. The approved Combined Homepage v1.21 and Our Story v1.1.6 already establish the design direction, so no additional design-definition task is needed.
- Clarified that page-specific SEO research, relevant content, metadata, internal linking and applicable schema planning will be handled within each page or page-family task by Codex.
- Moved technical sitewide SEO and performance QA to a clearly deferred WordPress launch stage; it will not be performed until the pages have been assembled in WordPress.

## 2026-07-22 - Google Sheets page inventory and execution workflow

- Created and visually verified a three-tab page-planning workbook covering the full non-ecommerce Phase One scope: `Page Inventory`, `Templates & Global`, and `Recommended Workflow`.
- Imported it as the native Google Sheet `Rebekah's Health & Nutrition - Phase One Page Inventory`: `https://docs.google.com/spreadsheets/d/13zutb5DxPIvqX4yv30QSBmSVyc7UoiTCIeCoqAKF7hU/edit`.
- The Sheet contains 20 current/proposed pages with status, priority, decisions, SEO/content focus and next steps; affiliate/referral rows remain explicitly pending program verification.
- Added native Sheet tables plus editable dropdowns for build status and priority. Home and Our Story are marked design-complete; the Blog archive is marked for minimal design.
- Recommended one Codex task per substantive page or page family, with a shared standards task first and an integrated sitewide SEO QA task last. Each task should reference Homepage v1.21, Our Story v1.1.6, the brand kit and approved image-source rules.
- Emailed the native Google Sheet link to Todd's connected Gmail account so it can be moved into the appropriate Drive folder.

## 2026-07-22 - Page inventory refinement for blog and affiliate shopping

- Clarified that the Home and Our Story/Meet Rebekah page designs are already complete; both still require WordPress implementation.
- Reduced the Wellness Source Blog archive to a minimal design treatment rather than a major custom page.
- Defined In-Store Products as a non-transactional discovery page for physical-store categories, featured brands, Rebekah's Signature Line, and visit/call actions; it will not include prices, cart, or checkout behavior.
- Identified four current affiliate/referral pathways that may warrant landing pages: Fullscript, Designs for Health, LifeWave, and Peptides & Injectables. Recommended either four short internal landing pages with external checkout CTAs or one combined `Shop Trusted Partners` hub.
- Working count remains 16 confirmed primary pages, increasing to 20 with four affiliate landing pages or 17 with one combined affiliate hub.
- Pending follow-up: verify exact destination URLs, program ownership and required disclosures, especially for Peptides & Injectables.

## 2026-07-22 - Phase One page inventory decisions

- Client confirmed that each of the four stores will receive its own location detail page.
- Client confirmed the current Privacy Policy and Refund & Returns Policy will remain; both will be fitted into the redesigned site, with the Privacy Policy receiving a quick revision during the build if needed.
- Added new Terms & Conditions and Disclaimer pages to the scope. These need starter content/templates and professional legal review before launch.
- Revised the working Phase One inventory from 13 to 16 primary pages, excluding ecommerce screens, plus four reusable content templates.

## 2026-07-22 - Phase One non-ecommerce page inventory

- Created `PHASE-ONE-PAGE-INVENTORY.md`, reconciling the 13-page current-site audit with Rebekah's original website brief and excluding WooCommerce shop, product, cart, checkout, and account screens.
- Recommended 13 primary Phase One pages: Home, the consolidated Our Story/Meet Rebekah page, a Locations hub, four store detail pages, Classes & Events, Wellness Source Blog, In-Store Products, Practitioners, Contact Us, and Privacy Policy.
- Identified four reusable build templates for blog posts, event details, practitioner profiles, and location details; separated the newsletter/text signup and social feeds as global components rather than standalone pages.
- Key pending client decisions: whether all four stores receive detail pages, whether Signature Line needs its own landing page or sits within In-Store Products, whether event registration is in scope, and whether individual practitioner profiles remain public.
- Client-facing next step: approve the proposed inventory and open decisions before converting the page list into the production sitemap and build checklist.

## 2026-07-22 - Our Story page redesign concept

- Reviewed the live `/our-story/` page and reorganized its long founder narrative, hidden 14-image carousel, testimonials, beliefs, and goals into a more focused, visitor-friendly story journey.
- Created `story-mockup-v1.1.0.html`, a standalone responsive concept using the approved Pine/Leaf/Sage/Honey/Berry brand system and existing Rebekah's photography.
- Established story-page version tracking at `v1.1.0`; subsequent revisions will increment sequentially as `v1.1.1`, `v1.1.2`, and so on. The current version appears in both the filename and browser title.
- The new page leads with Rebekah's personal story, then moves through the founding purpose, four-part growth journey, three core values, historical photo gallery, and a store/events call to action. Copy was tightened to reduce repetition and avoid overly strong medical claims.
- Client-facing next steps: review the visual direction and rewritten story for voice/factual approval; confirm whether the 2007 founding date and the four current locations should remain prominent; then translate the approved concept into Bricks on staging.
- Story Mockup v1.1.1: replaced the photograph-cover hero with a split editorial layout that shows the complete founder image, removed every repeated image use (including replacing the footer's repeated logo with text), and expanded the page using newly researched, source-supported content.
- Added Rebekah's HHP/NES/Reiki credentials; exact store-growth milestones (Lapeer 2007, Grand Blanc 2017, Clarkston 2019, Lake Orion 2020); the 200+ formula private-label story; the stores' education-first service model; health fairs, classes, practitioner events and local-maker connections; and personal context around Spencer's Pond, Spencer's Honey and Mark Spencer's beekeeping education.
- Client-facing next step: confirm the newly researched personal details and credentials with Rebekah, then approve which expanded sections should remain before the Bricks staging build.
- Story Mockup v1.1.2: removed the mockup header and footer because the sitewide header/footer direction is already approved separately. Story mockups now represent only the page-specific content area, from the hero through the final story CTA.
- Story Mockup v1.1.3: filled the journey section's unused left-column space with a unique historical image and milestone caption; added `Explore upcoming events` and store-location event links to the community section; and added a new three-article Wellness Source section that connects the founder story to ongoing education, expert access, private-label standards and beekeeping content.
- Story Mockup v1.1.4: removed the redundant `Find events near your store` action, retaining one direct Events CTA; confirmed the hero's `Follow the journey` button is an on-page anchor to the history section; confirmed all three Wellness Source cards use real published article destinations; and changed the journey image from a hard cover crop to a contained full-image presentation so heads are not cut off.
- Story Mockup v1.1.5: clarified the blog connection by renaming the section eyebrow to `Rebekah's Wellness Blog`, changing the CTA to `Explore all blog articles`, moving it directly beneath the explanatory paragraph, and increasing its green/white contrast. The CTA continues to link to the existing `/blog/` archive.
- Story Mockup v1.1.6: completed a responsive CSS audit after automated control of the local `file://` preview was blocked by browser security. Removed fixed hero minimum heights at tablet sizes; stacked all primary grids; corrected negative/off-canvas decorative offsets; reduced mobile badges; made hero, blog, event and final CTA buttons full-width where appropriate; removed the fixed crop from the journey image on phones; tightened timeline columns; reduced mobile card height; and preserved two-column gallery behavior on tablets with one-column behavior on phones. Manual visual confirmation remains recommended at approximately 390×844 and 768×1024 before WordPress implementation.
- Client approved Story Mockup v1.1.6 for client review and requested a GitHub Pages link. Confirmed the existing repository Pages site is live and prepared the standalone target `story-review/index.html`, but publication is pending because the GitHub app has read-only repository access for this write (403) and the local `gh` authentication token for `bluenovamarketing` is invalid. After authentication is refreshed, publish only the approved v1.1.6 file to the intended public URL `https://bluenovamarketing.github.io/Rebekahs-Health-Website/story-review/` without including unrelated working-tree changes.
- GitHub authentication was refreshed and Story Mockup v1.1.6 was published for client review as the repository's standalone `story-review/index.html` page. Commit `a5e1a52` was pushed directly to `main`; the GitHub Pages build/deployment completed successfully, and the public URL returned HTTP 200 with the correct v1.1.6 title. Client review link: `https://bluenovamarketing.github.io/Rebekahs-Health-Website/story-review/`.
- Client correction for the company timeline: Rebekah's first Lapeer store opened in **2010**, not 2007. Story Mockup v1.1.7 supersedes every earlier 2007 reference in the story-page mockup, including hero copy, the hero year badge, founder narrative, and journey timeline. Use 2010 as the authoritative founding year in all future page content and WordPress implementation; earlier project notes citing 2007 are now outdated and should not be reused.
- Published the corrected Story Mockup v1.1.7 to the existing GitHub Pages client-review URL in commit `b8c6b24`. The Pages deployment completed successfully and the public HTML was verified at HTTP 200 with four `2010` references and no remaining `2007` references.
- Client supplied an additional biographical correction for Mark: describe him as a beekeeper, bee-school instructor, and **past President and Vice President of the Seven Ponds Bee Club**. Story Mockup v1.1.8 incorporates this wording and supersedes v1.1.7 for client review.
- Approved information-architecture decision: consolidate the existing Our Story and Meet the Owner pages into one canonical `/our-story/` page. Use either `Meet Rebekah` or `Our Story` as the navigation label but always link it to `/our-story/`; update all internal Meet Rebekah links accordingly; and permanently 301 redirect `/meet-the-owner/` to `/our-story/` during the WordPress build. Apply this rule consistently while building other pages and the final site.
- Implementation note: the story-page content can be recreated without paid Elementor add-ons by using core containers, headings, text, image and button widgets, plus an HTML widget or WordPress/theme stylesheet for the few custom decorative styles. Elementor's built-in global/element Custom CSS controls are paid-plan features, so a strictly free Elementor implementation should not rely on those controls. The staging site currently uses Bricks, so confirm the page-builder decision before installing or mixing Elementor into the production architecture.

## 2026-07-20 - Pre-redesign WordPress and Cloudways audit

- Audited the Rebekah's Health and Nutrition staging site's active theme, plugin inventory, public URL/sitemap structure, blog and events content, WordPress reading/permalink settings, cache headers, Site Health status, and basic cached-versus-uncached response times.
- Confirmed Bricks is the active theme, pretty permalinks use `/%postname%/`, staging is correctly blocked from indexing, and Site Health reports no critical or recommended issues. Cloudways/Varnish is producing cache hits, but the deeper plugin-state audit confirmed Breeze and Object Cache Pro are currently inactive; Redis/Object Cache Pro must be verified in Cloudways rather than assumed active.
- Found a large Phase One performance opportunity: WooCommerce and its payment, tax, coupon, advertising, invoice, gateway, and updater add-ons are all active even though e-commerce is deferred to Phase Two. Recommended backing up first, hiding commerce routes, then deactivating the commerce stack without deleting product/order data.
- Measured cached homepage TTFB at roughly 0.46–0.53 seconds and an uncached request at roughly 3.59 seconds, indicating server-side/plugin/database work needs optimization even though page caching is functioning.
- Confirmed the content inventory includes 13 published pages, 21 blog posts, an events archive with individual event URLs, product and custom-post-type sitemaps, and the `/blog/` page. Preserve retained slugs and build a one-to-one URL disposition map before redesigning or redirecting pages.
- Recommended Cloudways follow-ups: verify Varnish, Redis/Object Cache Pro, Breeze auto-purge, Cron Optimizer, PHP 8.3 compatibility, Cloudflare integration, backups, and monitoring; investigate PHP/database/plugin load before scaling the server.
- Client-facing next step: approve the Phase One plugin deactivation list and the URL/content map before any plugins are turned off or page URLs are changed.
- Completed a read-only pre-design inventory in `PRE-DESIGN-INVENTORY.md`, covering 13 pages, 21 blog posts, 28 practitioners, 4 testimonials, 36 products, 10 current REST-visible events, 220 media records, two Forminator forms, one ACF field group, six Bricks templates, both menus, plugin status, taxonomy counts, and Cloudways items requiring account-level verification. No sitemap, redirect, image, plugin, content, theme, or hosting changes were made.

## 2026-07-20 - Staging WordPress access recorded

- Verified that the Cody account can access the Rebekah's Health and Nutrition staging WordPress dashboard at `https://wordpress-1651482-6565113.cloudwaysapps.com/wp-admin` with administrator-level menus available.
- Added the staging site, login URL, Cody username, supplied password, Admin role, saved date, and verification date to the `WP Logins` tab in the shared `Cody WP Logins` Google Sheet.
- No WordPress application password was created because the current task only required recording and verifying interactive dashboard access; create one later if REST API or automated publishing access is needed.

## 2026-07-15 - Brand kit development

- Created a complete client-facing brand system for Rebekah's Health & Nutrition based on the current public website and the Blue Nova kit's level of structure and practicality.
- Positioned the brand around trusted, personal guidance for confident everyday wellness choices; proposed the campaign line "Own your everyday wellness."
- Built a Pine, Leaf, Sage, Honey, Berry, Cream, Mist, and Ink palette; Georgia + Segoe UI typography system; claim-safe voice guidance; refreshed botanical logo direction; social profile guidance and assets; and CSS/JSON web tokens.
- Key decision pending: confirm the primary naming hierarchy ("Rebekah's Health & Nutrition" versus the legacy "Source" wording) before permanent signage or packaging.
- Client-facing next steps: approve positioning/tagline/logo direction, complete trademark review, then update the website, social profiles, location listings, and reusable marketing templates.
- Added dedicated Instagram, TikTok, and YouTube assets: platform avatars, Instagram highlights/post/Story templates, TikTok vertical cover template, and a safe-area-aware YouTube banner and watermark.
- Added a verified URL directory covering the website, shop, Facebook pages, Instagram, TikTok, YouTube, X, Pinterest, LinkedIn, founder profile, and private Facebook community. Recommended standardizing future handles around `@rebekahspureliving`.

## 2026-07-15 - Phase One homepage mockup

- Reviewed Rebekah's email brief, the current website, Harvest Health Foods, Tango Pools, the supplied ChatGPT concept, and the new brand kit.
- Built a full-width responsive HTML homepage concept using the approved brand palette and refreshed logo system. The experience emphasizes guided wellness pathways, signature products, classes/events, the Wellness Source blog, all four stores, and a store-specific newsletter/text signup.
- Kept e-commerce clearly scoped to Phase Two: Phase One presents in-store categories and creates product pathways that can later become WooCommerce collections without restructuring the homepage.
- Designed the hero to be film-ready in the spirit of Tango Pools. Current YouTube content did not provide a polished store-tour asset suitable for the hero, so the mockup uses photography now and can accept professional store/drone video later.
- Pending client decisions: approve the overall visual direction, confirm final navigation labels, choose/produce hero footage, confirm newsletter/text platform and store-list routing, and provide approved professional store/team/product photography for the Elementor build.

## 2026-07-15 - Phase One mockup revision from browser feedback

- Reworked the homepage to return to the richer, more vibrant and flowing direction of the client's supplied ChatGPT concept, with curved section transitions, layered decorative forms, hover motion, scroll reveals and responsive animation handling.
- Removed all visible "Phase One concept" labeling, removed the unexplained play control, and removed the unrelated shoe image flagged in review.
- Replaced the static hero background with Rebekah's real YouTube video, "From Farm to Bottle: Inside Our Private Label: Purity, Potency, Price," displayed as an autoplaying muted cinematic background.
- Restored Rebekah's requested navigation language: Visit Our Stores, Shop Online, Classes & Events, Wellness Source Blog and Meet Rebekah.
- Replaced generic/fabricated story and Signature Line language with verified content from Rebekah's website, including the 200+ formula collection, whole-food ingredients, amber glass packaging, her 2007 founding date, and her published quote.
- Client-facing next step: review the revised motion/video direction and identify any remaining images or sections that should use only client-supplied photography before Elementor production.

## 2026-07-15 - Homepage review refinements

- Tightened the oversized curved transition gaps above the wellness categories and events sections.
- Replaced internal development copy in the product pathways with customer-facing SEO-oriented language about trusted supplements, herbs, natural essentials and common wellness goals.
- Removed the duplicate Rebekah portrait from the Signature Line section and replaced it with a custom branded 200+ formulas visual; retained the preferred portrait in the Meet Rebekah section.
- Consolidated newsletter and text enrollment into one form with email, mobile number and preferred store fields, plus email/SMS consent, frequency, rates, STOP and purchase-condition disclosure language.
- Removed the remaining Phase One reference from the document metadata and connected the footer's Blue Nova Marketing credit to the agency website.
- Confirmed the current hero uses Rebekah's YouTube video `Qeqxu1A8f-Y` ("From Farm to Bottle: Inside Our Private Label: Purity, Potency, Price").
- Added footer utility links for Privacy Policy, Terms & Conditions and Sitemap. The current Terms link routes to the site's existing terms/returns page, and the Sitemap link routes to the current XML sitemap.
- Adjusted the Blue Nova Marketing credit to match the copyright line color at rest and change color only on hover.
- Replaced the refreshed brand-kit logo in both the header and footer with the verified current Rebekah's Health & Nutrition Source logo at the client's request.
- Compressed the footer from a tall centered block to a 187px desktop utility footer with the logo, tagline, navigation, legal links and copyright arranged horizontally.
- Redesigned the intro-to-wellness-pathways transition as a layered overlapping composition with a soft gradient, rounded raised panel, oversized background typography and elevated category content so the sections feel custom and connected.
- Established visible mockup version tracking beginning with `v1.6`; future design revisions should increment the footer version number.
- Rebuilt the "Real guidance" introduction for v1.6 as an editorial two-column composition using a real Rebekah's Private Label Liver Detox product image on the left and a vertically stacked eyebrow, title, customer-facing guidance copy and CTA on the right.
- Updated Mockup v1.7 by moving the Meet Rebekah copy to the left and the preferred founder portrait to the right.
- Tested a white footer-logo treatment, found that the legacy PNG's faint matte still read as a box, and instead moved the compact footer to a light cream-to-sage band so the exact current green logo integrates naturally without a card or recoloring.
- Mockup v1.8 changes: switched the compact footer to pure white for client comparison and removed the version label from all website content; version tracking will now be reported only in the Codex chat.
- Priority-feature audit: classes/events, Wellness Source blog, Signature Line and all four locations are represented in the homepage mockup. Professional photography is only partially addressed—real founder, product and event assets are used, but final professional store interiors/exteriors, team and broader product photography still need to be supplied or commissioned.
- Mockup v1.9: reversed the Signature Line layout to place its content on the left and the 200+ formulas visual on the right; reversed the Meet Rebekah layout to place the founder portrait on the left and the story content on the right, creating an alternating section rhythm.
- Mockup v2.0: widened the Signature Line content side to roughly 61% and reduced the 200+ visual to roughly 39%, reducing the section to about 574px tall at the reviewed desktop viewport while allowing the headline and body copy to wrap more naturally.
- Mockup v2.1: replaced the block-like four-location table with individual destination cards using staggered vertical positions, asymmetric rounded corners, color-coded accent strips, circular location numbers, floating shadows, pill actions, hover lift and a subtle oversized Michigan background treatment.
- Mockup v2.2: added a responsive Instagram feature between the Wellness Source Blog and Locations sections, styled as six custom portrait tiles with Reel play indicators and a direct follow CTA for `@rebekahs_health_and_nutrition`.
- Verified Harvest Health Foods uses Smash Balloon Instagram Feed Pro. Recommended using the same plugin for the Elementor production build so Rebekah's photos, videos and Reels update automatically; created `ELEMENTOR-INSTAGRAM-SETUP.md` with connection, layout, responsive, shortcode/widget, CSS and maintenance instructions.

## 2026-07-15 - Second mockup review handoff

- Saved the supplied second homepage mockup as `second-mockup.html` and opened it as a live local preview in the Codex in-app browser for visual annotation.
- No design changes were made during this handoff; pending follow-up is to apply the client's highlighted annotations and requested revisions.

## 2026-07-15 - Hero video art direction

- Compared the second mockup hero with Tango Pools' full-bleed cinematic video treatment and Rebekah's supplied YouTube video, `Qeqxu1A8f-Y` ("From Farm to Bottle: Inside Our Private Label: Purity, Potency, Price").
- Recommended a custom editorial video hero rather than copying Tango Pools directly: full-bleed muted looping footage, brand-tinted gradient overlays, a protected text-safe area, botanical framing details, concise positioning copy, and a restrained sound/full-video control.
- Production priorities: create a short clean hero cut from the strongest farm, ingredient, bottling and product close-ups; avoid interview/dialogue shots behind text; provide a poster image and reduced-motion/mobile fallback; and self-host the optimized video for the production site where possible.
- Pending client decision: choose between a fully cinematic full-width hero and a more editorial split-layout treatment that preserves the current mockup's distinctive brand character.

## 2026-07-15 - Cinematic hero mockup

- Implemented the recommended full-width cinematic hero in `second-mockup.html` using Rebekah's supplied private-label YouTube video as a muted looping background.
- Added layered pine-green cinematic overlays, protected high-contrast headline space, a gold primary CTA, glass secondary CTA, botanical line details, proof points and a "Watch the full story" link.
- Reframed the hero copy around sourced ingredients, purposeful formulas, quality, transparency and personal guidance; retained reduced-motion and responsive mobile fallbacks.
- Refreshed the live in-app preview for client review. Pending follow-up: confirm the visual direction and, for production, create a short purpose-edited/self-hosted hero video for tighter cropping, faster loading and more reliable playback.
- Follow-up refinement: made the embedded footage visibly dominant rather than relying on a YouTube-style action button. Added the video's poster frame as an immediate loading fallback, lightened the right-side overlay, strengthened the foreground text stacking and replaced the link-out control with a subtle "Private-label story playing" status badge.
- Browser-comment correction: replaced the ambiguous status badge with a real, clearly labeled `Play Hero Video` control. It now starts the embedded hero video with sound, changes to `Pause Video`, and toggles playback in place without sending the visitor to YouTube; live interaction was verified in the mockup.
- Version-tracking decision: identify every mockup revision in the Codex chat only, not in the website design. The current second-mockup build with the working hero play/pause control is `Second Mockup v1.3`; increment the minor version with each subsequent review revision.
- `Second Mockup v1.4`: investigated the client's six-second Screencast showing music over an apparently frozen cleanroom frame. Removed the oversized transformed/filtered iframe treatment, switched to a normally rendered full-hero player layer and moved playback to the 12-second motion segment so the play control begins with visible footage rather than the video's static musical opening. Verified the player source, play/pause state and control behavior after the revision.
- `Second Mockup v1.5`: replaced the YouTube background iframe entirely with a local native MP4 of the client-supplied video (`output/rebekahs-hero-video.mp4`). Configured muted inline looping playback with `object-fit: cover`, a poster fallback and a native play/pause control. Verified the loaded media is 128.6 seconds at 640×360 and, critically, that browser playback time advanced from 35.03 to 37.31 seconds while `paused=false`, confirming real moving video rather than audio over a frozen iframe frame.
- Live v1.5 playback diagnosis: the issue is not browser cache or a failed media request. In the client's currently visible preview, the native MP4 reported `readyState=4`, no media error, `paused=false`, and advanced from 59.06 to 61.14 seconds with no console warnings. The remaining failure is visual repaint/compositing of a video used as a layered background in the Codex in-app browser. Recommended next direction: present the video as a normal visible inline player/panel (or validate the full-bleed treatment in Chrome/production) rather than continuing cache changes.
- Manually pressed the live `Play Hero Video` control at the client's request and compared visible screenshots three seconds apart. The control changed to `Pause Video`, but the first capture still showed the same cleanroom image and the second capture contained large black/unpainted areas rather than a later video frame. This visually confirms the client's report: the in-app browser is not painting the layered background video correctly even though media time advances. No new mockup version was assigned because this was diagnosis only.
- `Second Mockup v1.6`: redesigned the hero to avoid layered-background video compositing. The editorial copy now occupies the left column, while the native MP4 is a large normal inline video panel on the right with rounded custom framing, a "From farm to bottle" label and its own Play/Pause control. Responsive layouts stack the copy and video on narrower screens. The in-app browser blocked the automated local-page refresh during final visual verification, so the client needs to manually refresh the existing preview tab before review.
- `Second Mockup v1.7`: responded to the client's report that the preview would not refresh and the Codex task was freezing. Removed hero autoplay and changed the native video from full preload to `preload="metadata"`; the hero now loads as a lightweight poster and only seeks/plays after an explicit click. Started a clean preview endpoint on port 4174 so review can resume without the hung page state from the old 4173 tab.
- `Second Mockup v1.8`: rejected the generic bordered video-card direction after client feedback. Removed the card, pill label and Play/Pause UI; rebuilt the hero as an asymmetric cinematic composition with editorial copy overlapping a full-height, organically clipped motion field, a custom illuminated vertical accent and edge-to-viewport treatment. To avoid the in-app browser's broken video compositor, generated a 741 KB five-second animated WebP directly from the client's real footage (`output/rebekahs-hero-motion.webp`), providing visible motion without video decoding, audio or heavy autoplay. Started a clean review endpoint on port 4175.
- `Second Mockup v1.9`: reviewed a 12-frame contact sheet spanning the full 128-second source video and replaced the weak/cropped machine-only clip. Built an eight-second cinematic montage from four stronger sequences: farm fields (0–2s), Rebekah and the team in the cleanroom (40–42s), fresh herbs (20–22s), and amber bottle production (60–62s). The 1.2 MB animated WebP now runs as a true edge-to-edge hero background, with the green readability gradient concentrated behind the left-side copy rather than placing the footage in a box. Started a clean review endpoint on port 4176.
- `Second Mockup v1.10`: replaced the grainy 640×360 animated preview with the complete 128.6-second source video in a higher-quality 1080p MP4 (about 56 MB). The full video now autoplays muted as the edge-to-edge hero background, runs from beginning to end and loops continuously; `object-fit: cover` preserves the full-bleed treatment while the left-side gradient protects copy readability. Started a clean review endpoint on port 4177. Production follow-up: compress or stream the final 1080p asset before launch to avoid serving the full 56 MB file directly on first load.
- `Second Mockup v1.11`: corrected a conflicting `prefers-reduced-motion` stylesheet rule that was setting the hero `<video>` to `display:none` in the client's Chrome/Windows configuration, leaving only the green fallback background visible. Removed both video-hiding declarations so the full 1080p background video remains present and can autoplay/loop as requested. Started a clean review endpoint on port 4178.
- `Second Mockup v1.12`: removed the separate YouTube poster image after the client showed the visible poster-to-video swap on load. Changed the full-background video from metadata-only loading to `preload="auto"` with no poster, so the browser proceeds directly into the video's opening frames instead of displaying a mismatched still first. Started a clean review endpoint on port 4179.
- Client approved the `Second Mockup v1.12` hero direction. Preserve the full-width 1080p background video, beginning immediately without a poster-image transition, in subsequent revisions unless the client requests otherwise.
## 2026-07-22 - Combined homepage mockup

- Created `third-mockup.html` as the 14-section combined direction selected from homepage mockups 1A and 1B, preserving the requested order.
- Updated the section 1 navigation by removing Shop Online and adding Practitioners and Call To Order.
- Used the second mockup newsletter treatment with the preferred-store dropdown from the first mockup, and changed the practitioner section to the berry background used by first mockup section 9.
- Client-facing next step: review the combined page and identify any extra sections or partial-section edits before production implementation.
- Follow-up correction: restored the Stay Connected header button alongside Call To Order, changed the hero from the unreliable embedded player to the approved local 1080p autoplaying background video, corrected the second-mockup guidance strip, and restored a complete three-image nationwide-shipping collage.
- Combined Mockup v1.2: added a fourth practitioner action, `Shop Injections`, beneath LifeWave. Began displaying the combined mockup version in the browser title and footer so each recreated revision can be identified during review.
- Combined Mockup v1.3: replaced every selected 1B recreation with the live original section from `second-mockup.html`, preserving the original HTML, CSS, content, photography, spacing and hover behaviors. Removed Practitioners from the header and returned Call To Order to a normal navigation link so the 1A menu stays on one row with the original Stay Connected button. Kept only the client-specified deviations: preferred-store dropdown, berry practitioner background and Shop Injections.
- Visually reviewed v1.3 at the client's 1211px browser width: the header stays on one row; the hero video advances while visible; all original shipping, events and education images load; the original shipping/events/article hover rules are present; the original newsletter content is restored with the added store selector; and the practitioner panel retains the requested berry background plus all four shopping actions.
- Combined Mockup v1.4: moved the selected original 1B sections out of embedded frames and into the combined page's main DOM so browser annotations can target their text, cards, images and controls normally. This was a structural annotation fix only; the source HTML, scoped source CSS, content, photography, margins and hover behaviors remain drawn from `second-mockup.html` with no requested design changes.
- Combined Mockup v1.5: changed only section 1, applying mockup 1B's exact warm-white translucent header background, blur, border treatment and original transparent logo asset. No other section or behavior was changed.
- Combined Mockup v1.6: corrected section 1's final rendered background to mockup 1B's established `--cream` value, `#fffdf8`. The earlier translucent declaration had blended over mockup 1A's darker page background and therefore did not render as the exact 1B color. No other element was changed.
- Combined Mockup v1.7: fixed only section 3 after the annotation restructuring. Restored the original pathway strip's visible state and removed the accidental wrapper overflow that created an internal scrollbar; all original 1B section 3 content, negative overlap, spacing and styles remain unchanged.
- Combined Mockup v1.8: adjusted only the transition around the four-item pathway card. Restored the exact 30px of additional space measured from mockup 1B so the card reads as a floating bridge between the hero and the following content, not as a separate section; no card content, styling, interactions or other page sections changed.
- Combined Mockup v1.9: corrected only the pathway import wrapper. Removed its inherited full-width background so it is transparent like mockup 1B's original direct-child structure; the hero now remains visible around the floating card overlap instead of the wrapper appearing as a larger section. All pathway content, original styling, spacing and every other page section remain unchanged.
- Combined Mockup v1.10: changed only the two client-annotated header/hero details. Styled Call To Order with the existing original honey pill treatment so it differs from the green Stay Connected button, and moved the original hero proof badge upward by 22px to clear the floating pathway card. No other page elements changed.
- Combined Mockup v1.11: changed only the header navigation row alignment, vertically centering the four standard menu links with the Call To Order and Stay Connected buttons. No spacing, colors, content or other sections changed.
- Combined Mockup v1.12: changed only the client-annotated signature-formulas panel. Added `Purity · Potency · Price` as a third centered line beneath the existing private-label descriptor, and converted the two existing honey and sage decorative circles into opposite-direction animations along the original outer ring. All original colors, ring artwork, section content and surrounding sections remain unchanged.
- Combined Mockup v1.13: changed only the three client-annotated details. Recolored Call To Order with the existing light sage brand color and white text; moved the yellow circle to the inner ring while retaining the green circle on the outer ring, opposite directions and slightly different 18/21-second speeds; and matched `PURITY · POTENCY · PRICE` to the uppercase, color, weight and spacing of the two descriptor lines above it. No other elements changed.
- Combined Mockup v1.14: changed only the two client-annotated items. Removed the visible combined-mockup version label from the footer while retaining version tracking in the browser title, and tightened the Stay Connected button's horizontal padding from 18px to 13px while keeping its original height, color and single-line label. No other header or footer details changed.
- Combined Mockup v1.15: fixed only the missing mobile Stay Connected action by adding the existing green pill inside the opened mobile navigation. The original desktop Stay Connected button and all other header/menu styling remain unchanged. TikTok feed options were also reviewed for a possible future social section; no Instagram or TikTok section was changed in this revision.
- Combined Mockup v1.16: changed only the client-annotated desktop header spacing by reducing the gap between Call To Order and Stay Connected from 32px to 16px. No button sizing, colors, navigation spacing or other page sections changed. Confirmed the free Smash Balloon Feeds for TikTok plugin can maintain an automatically refreshed account feed without a recurring license; no social section was changed yet.
- Combined Mockup v1.17: added a TikTok feed preview directly below the existing Instagram section so the client can compare both social directions. The new section reuses the approved Instagram layout, existing approved imagery and card interactions, labels all six preview cards as videos, links to `@rebekahspureliving`, and is structured to be supplied later by the free automatically refreshed TikTok feed plugin. The Instagram section and all other existing sections remain unchanged.
- Combined Mockup v1.18: changed only the two client-annotated visual details: set the Call To Order button background to the exact requested `#D9EDB3` while retaining white text, and removed the underline from the Blue Nova Marketing footer credit. Clarified that the current TikTok cards and category line are visual placeholders rather than live feed data; the saved verified account URL is `@rebekahspureliving`, but the actual feed still requires WordPress plugin installation and TikTok account authorization.
- Combined Mockup v1.19: changed only the three requested items. Set Call To Order to the exact `#C2E093` background with white text, increased the inner yellow signature-orbit circle's speed by exactly 25% (18 seconds to 14.4 seconds), and replaced the six TikTok preview placeholders with locally saved thumbnails from the six newest non-pinned posts currently shown by TikTok's official creator-profile embed for `@rebekahspureliving`. Verified all six 1080×1920 images load, the requested computed styles are exact, and the desktop page has no horizontal overflow. The future live automatic feed still requires the free WordPress plugin plus account authorization.
- Client approved Combined Mockup v1.19 as the version to present for stakeholder approval. Preserve this exact build as the review baseline and make no further adjustments unless requested after feedback.
- Combined Mockup v1.20 is a separate visual experiment derived from the approved v1.19 baseline. Changed only the four location cards: each now uses a different soft brand tint, matching tinted number badge and a slow blurred color bloom moving within the card background at staggered 16–20-second speeds. Verified the four-card desktop arrangement, single-column mobile arrangement and zero horizontal overflow. Preserved the approval baseline separately as `third-mockup-v1.19.html` using the unchanged v1.19 stylesheet so the client can compare or revert cleanly.
- Combined Mockup v1.21: changed only the Call To Order button from pale green to the brighter saturated `#078A42` green sampled from the existing Rebekah's logo, retaining its white label and all v1.20 location-card treatments. Preserved v1.20 separately as `third-mockup-v1.20.html` and verified the live button renders as RGB 7/138/66 with white text.
- WordPress implementation decision: the approved mockup can be reproduced with WordPress and Elementor Free without buying Elementor Pro or another premium plugin. The layout, responsive behavior, hero video, hover effects and ambient card animations can use Elementor containers plus HTML/CSS/JS. Automatic Instagram/TikTok feeds and newsletter submission still require either free plugins/account authorization or custom integrations; they do not require a paid license, but they are not provided by Elementor Free alone. Plan to optimize the large hero video and test social-account tokens, form delivery and responsive behavior during production QA.
- Final client-selection decision: Combined Mockup v1.21 supersedes v1.19 as the official client-final homepage concept. Its browser title now identifies it as `FINAL Client Homepage Mockup v1.21`; no design, content or behavior changed. Preserve v1.21 as the production reference unless stakeholder feedback specifically requests revisions.

## 2026-07-22 - Approved image sources and art direction

- Client approved using images from the shared Google Drive folder: `https://drive.google.com/drive/folders/1je-cIaGYpXcj7X08jeX5Ol3_8V5NoxBL?usp=sharing`.
- Existing images from the live website and WordPress media library are also approved for this project; WordPress backend access is available.
- Additional royalty-free stock images and AI-generated images may be used when the supplied assets do not meet the design need.
- Preferred art direction: photographic, lifelike and human-centered imagery. Prioritize authentic people and natural lifestyle scenes over obviously synthetic or heavily illustrated visuals.
- For future image selection, check the shared Drive folder and existing website assets first, then supplement with suitable royalty-free or AI-generated imagery as needed.

## 2026-07-22 - Final mockup published for client review

- Published the approved Combined Mockup v1.21 to the existing public GitHub Pages repository at `https://bluenovamarketing.github.io/Rebekahs-Health-Website/third-mockup.html`.
- Deployment commits: `663e30c` published the final page and required assets; `c14deec` removed a newsletter initialization timing error found during live QA; `f714f6b` versioned the script reference so reviewers receive the corrected file immediately rather than a stale cached copy. These QA fixes did not change the design or content.
- Verified the Pages build completed successfully, all six imported original sections mounted, all local Instagram/TikTok/footer images loaded, and the 1080p hero video loaded and played without a media error.
- Client-facing next step: send the public URL for stakeholder approval. Production follow-up remains optimizing the 56 MB hero video and replacing preview-only social cards with authorized live feeds if approved.
- Sent both public review links—the final homepage v1.21 and Our Story v1.1.6—to Rebekah at `rebekahspureliving@gmail.com`, and sent a copy to Todd's connected Gmail account for reference.

## 2026-07-23 - Founding-year correction

- Client corrected the first Rebekah's location opening year from 2007 to 2010.
- Updated only the annotated homepage sentence and advanced the approved final homepage identifier from v1.21 to v1.22; no homepage design, layout, behavior, or other content changed.
- Published commit `46760fb` to the existing GitHub Pages review URL and verified the live page title is `FINAL Client Homepage Mockup v1.22`, the sentence reads `Since opening the first location in 2010`, the hero video still plays, and the page has no console errors or horizontal overflow.
- The concurrent Our Story factual update also synchronizes the same 2010 founding year in Story Mockup v1.1.7, keeping the two approved concepts consistent.

## 2026-07-23 - Page inventory spreadsheet rebuilt

- Rebuilt the canonical Google Sheet after client review showed giant mostly blank rows and missing inventory content.
- Added a first-position `START HERE` tab with the complete working count, exact four-template count, ecommerce exclusion, approved design references, WordPress timing, usage instructions, open decisions, and the strict practitioner-profile guardrail.
- Restored all 20 working page rows in `Page Inventory`: 16 confirmed pages plus four proposed affiliate/referral landing pages. Added concise scope, design/build status, priority, page-specific SEO/content focus, and prompt-location fields; restored status dropdowns; compacted row heights; and hid unused columns.
- Preserved all 20 existing page prompts and moved them into a dedicated `Page Prompts` tab so each can be copied into a separate Codex task without making the inventory unreadable.
- Cleaned `Templates & Global` to show exactly four reusable templates—blog post, event detail, location detail, and practitioner profile—separately from shared treatments and global components.
- Reworked `Recommended Workflow` around running 3–5 page mockup tasks in parallel, approving mockups before WordPress implementation, and deferring the final technical SEO/performance audit until the WordPress site is assembled.
- Visually verified the rebuilt `START HERE`, `Page Inventory`, and `Page Prompts` views in the client's open Google Sheet and left the browser on `START HERE`.
- Client-facing next step: refresh/open the canonical sheet, begin on `START HERE`, select a 3–5 page batch, and copy each matching prompt from `Page Prompts` into its own task.
## 2026-07-23 — Locations hub mockup v1.0

- Created `locations-mockup-v1.0.html` as a polished, responsive design for `/locations/`, following the approved homepage v1.21 and Our Story v1.1.6 visual system.
- Removed the page-specific header and footer after client direction. The approved global header and footer from the combined homepage mockup will be reused during site assembly; future page mockups should design page content only and omit duplicate global chrome.
- Kept the page intentionally concise: a four-store comparison hub with scannable address, phone, regular hours, directions, and internal links to each future store-detail page.
- Added page-specific SEO metadata, a single clear H1, structured H2/H3 hierarchy, cautious wellness copy, descriptive image text, accessible table semantics, reduced-motion support, and mobile-friendly tap targets.
- Used the business's live Locations and Contact pages as the primary source for NAP details. Grand Blanc is shown as `252 Perry Road, Suite 4, Grand Blanc, MI 48439` based on the Contact page. Lake Orion hours are shown as Mon–Fri 9am–7pm and Sat–Sun 10am–5pm based on the live Locations page.
- Pending before production: client should confirm all four NAP records and holiday-hours workflow, especially Lake Orion hours because an external chamber listing conflicts with the live site. Add verified store photography and Organization/LocalBusiness graph details during WordPress assembly; do not publish four separate LocalBusiness entities until each location's facts are verified.
- Client-facing next step: review the hub layout and decide whether the stylized Michigan map or store photography should lead the hero before starting the Lapeer master location-detail design.
## 2026-07-23 — Classes & Events archive + event-detail design v1.0.0

- Created `classes-events-mockup-v1.0.0.html`, `event-detail-mockup-v1.0.0.html`, and the shared `classes-events-v1.0.0.css`.
- Followed the canonical Classes & Events prompt and the approved homepage v1.21 / Our Story v1.1.6 visual direction.
- Grounded the archive examples in current public event content and designed for the actual mix of classes, recurring markets/pop-ups, health fairs, store promotions, and visiting educators/representatives.
- Added archive search, location and event-type filtering concepts; clear list/month view controls; date, time, location, category, and status hierarchy; and responsive desktop/tablet/mobile layouts.
- Designed the reusable detail template around the current “Ask The Rep” event, with a fact sidebar, cautious educational disclaimer, related-event cards, source-aware attendance language, and a store call action.
- Decision: no registration/ticketing control was added because Phase One registration scope is still unconfirmed. “Free” is displayed only on examples where the current event content explicitly confirms it.
- Included page-specific title/meta drafts, accessible headings, contextual internal-link patterns, image alt-text treatment, and content structure suitable for Event schema during WordPress implementation.
- Pending follow-ups: client to confirm whether Phase One includes registration/ticketing; verify all event image URLs and live event facts during production; map archive filters and view controls to the event plugin; add Event schema from verified structured fields.
- Client-facing next step: review the archive and detail visual direction together, then confirm the registration model before WordPress build.

### Review adjustment

- Removed the standalone header and footer from both Classes & Events mockups. The approved global header/footer from the combined homepage mockup will be reused across this page and all subsequent page designs rather than redesigned per page.
- Corrected the broken Lapeer microgreens event image using the live WordPress media URL and added descriptive alt text.
- Corrected the Ask The Rep detail-page hero image to the verified July 2026 WordPress media path for Pete LaDuke.

## 2026-07-23 — Wellness Source Blog archive mockup v1.0

- Created `wellness-source-blog/wellness-source-blog-v1.0.html` as the responsive `/blog/` archive concept, following the canonical Sheet prompt and the approved homepage v1.21 / Our Story v1.1.6 visual system.
- Kept the archive intentionally minimal: concise editorial hero, one latest-story feature, four useful discovery filters, search, eight current article cards, pagination, and a restrained newsletter close.
- Grounded the mockup in the live July–March 2026 archive, using the current titles, dates, featured images, and existing article URLs so no post URL changes are implied.
- Added page-specific title and meta-description drafts, one H1, clear H2/H3 structure, descriptive image alt text, cautious rewritten excerpts, a canonical URL, and `CollectionPage` schema guidance.
- Verified desktop, tablet, and mobile layouts in-browser: no horizontal overflow or broken images, no console warnings/errors, two-column tablet and one-column mobile grids, and working combined category/search filtering.
- Decision: archive copy remains brief because individual posts carry the primary topical SEO value. No ecommerce, cart, checkout, or WordPress changes were included.
- Production follow-ups: connect filters/search/pagination to WordPress query behavior; generate archive schema dynamically; review article claims and metadata individually; confirm newsletter form destination and consent copy; retain all existing post URLs.
- Client-facing next step: review the archive hierarchy, featured-story treatment, and category labels before converting the approved design into the WordPress archive template.
- Client review decision: removed the mockup header, newsletter signup, and footer. These are approved global components from the combined homepage and should not be redesigned or repeated in this page or future page-specific mockups. The newsletter form shown in v1.0 was only a visual placeholder and was not connected to an email platform.

## 2026-07-23 - Lapeer location mockup v1.0

- Created `lapeer-location-mockup-v1.0.html` as the master location-page concept, following Homepage v1.21 and Our Story v1.1.6.
- Verified 588 S Main St, (810) 660-8585, Monday-Saturday 9am-7pm, and Sunday 10am-5pm against the official site and Lapeer Area Chamber.
- Included Lapeer-specific origin content, product categories, cautious guidance language, current local events, nearby-area context, directions/map, preferred-store signup, metadata and draft local-business schema.
- Review placeholders: confirm permission/source treatment for the customer quote and replace general team imagery with approved Lapeer storefront/interior photography if stronger originals are available.
- Live-content follow-up: the July 2026 “Ask The Rep” event body contains an old address/phone although its venue record has the current details. Correct the event copy in WordPress.
- Client-facing next step: approve the responsive visual direction and content before WordPress implementation.
- Global mockup decision: removed the page-specific header and footer. All remaining interior-page mockups should contain page content only and rely on the approved global header/footer from Homepage v1.21.
- Corrected all Lapeer location mockup founding-year references from 2007 to the client-confirmed 2010 date, including the hero eyebrow, year badge and supporting origin statement.
- Client confirmed the shared Drive folder contains clearly labeled Lapeer photography and access was already available. Replaced generic website imagery with downloaded client-provided Lapeer team/store and Victoria education photos; use location-labeled client assets first on every location page.
- Removed the negative overlap beneath the Lapeer hero so the directions and phone buttons are no longer covered by the quick-information panel.
- Matched the Lapeer signup to the approved homepage fields and language: name, email, preferred store, submission label and consent note.
- Location-event implementation decision: every location page will reuse one event component that queries future events by the event's assigned venue/location taxonomy. The Lapeer page uses `lapeer`; Grand Blanc, Clarkston and Lake Orion will use their matching values, preventing cross-location events from appearing.
- Corrected a wide-desktop-only Lapeer hero transition: restored the quick-information panel's green-background overlap and added 56px of internal hero clearance so the CTA buttons remain unobstructed at both standard and wide desktop widths.
- Lake Orion location refinement: enhanced the three community/event cards so hover and keyboard focus lift 8px, enlarge 2.5%, strengthen the shadow and rise above neighboring cards; reduced-motion users retain the static treatment.

## 2026-07-23 - In-Store Products mockup v1.0.0

- Created `in-store-products-mockup-v1.0.0.html` as the responsive, non-transactional discovery page for `/in-store-products/`, following the canonical Sheet prompt and the approved homepage/Our Story design system.
- Kept global header/footer out of the page mockup per the established interior-page decision.
- Built the page around eight useful product-category paths, simple category filtering, a three-step guidance section, the 200+ formula Signature Line, a featured-brand strip, explicit inventory/availability caveats, and direct links to all four store pages.
- Did not add prices, live stock claims, product cards, cart, checkout or other ecommerce behavior.
- Grounded product breadth and featured-brand examples in the current public site, while using cautious language and a supplement/medical-advice disclaimer.
- Added page-specific title/meta/canonical markup, one H1, an accessible H2/H3 structure, descriptive image alt text, internal store links and draft `CollectionPage` schema.
- Pending production follow-ups: replace general stock imagery with approved store/product photography where available; client to confirm the `20,000+` and `200+` claims, current featured-brand list, category names, and whether the online Signature Line preview remains an approved outbound path; verify final location URL slugs.
- Client-facing next step: review the category mix, Signature Line emphasis, brand list and call-ahead language before WordPress implementation.

### Client review adjustments

- Replaced the rejected Sports Nutrition shoe image and recorded that it should not be reused.
- Replaced the generic guidance portrait with the client-provided Victoria education photo from the shared Drive asset set.
- Reused the homepage’s exact working Signature Line composition, copy treatment, orbit animation and responsive behavior instead of maintaining a similar-but-separate version.
- Rebuilt the four-store close using the homepage’s destination-card visual system, including staggered cards, numbered badges, color variation, Michigan background typography and richer store details.
- Client approved the revised In-Store Products mockup v1.0.0 on July 23, 2026. Treat this version as the approved visual/content direction for later WordPress implementation.
- Phase One CTA correction: changed the Signature Line button from an ecommerce/product-category destination to `Find the Signature Line Near You`, which moves visitors to the four-store section and its location/phone options. This avoids implying a Signature Line catalog or ecommerce experience that is not included in this phase.

## 2026-07-23 - Contact Us mockup v1.0.0

- Created `contact-us-mockup-v1.0.0.html` as a light, responsive, conversion-focused design for `/contact-us/`, following the canonical Sheet prompt and approved homepage / Our Story visual direction.
- Kept the mockup to page content only; the approved global header and footer will be reused during WordPress assembly.
- Organized contact intent into three routes: call a preferred store for product and local questions, visit Classes & Events for event information, or use one general-inquiry form.
- Used current public contact details for Lapeer, Grand Blanc, Clarkston and Lake Orion. Included location-specific phone numbers, regular hours and directions links. Grand Blanc uses Suite 4 and ZIP 48439, consistent with the current Contact page and prior project decision.
- Preserved the existing form’s name, email, phone, subject and message concepts while adding preferred-store routing, explicit consent, a private-health-information caution and a non-medical-advice note.
- Added page-specific title, meta description, canonical URL, one H1, accessible heading/form structure, internal links, mobile tap targets and reduced-motion support. No ecommerce or WordPress changes were included.
- WordPress follow-ups: connect the form; configure reply-to, recipient routing, confirmation/error states, server-side validation, spam protection and consent logging; test delivery for every subject/store combination; publish structured Organization/contact-point data only from verified business records.
- Client-facing next step: review the three contact routes, store facts and form fields, then confirm the receiving inbox/routing rules before WordPress implementation.
- Client review adjustment: added homepage-style color, soft green/honey gradients and elevated card treatments to the four-store section. Added hover lift, shadow and small icon/number motion to the contact-route and store cards, with motion removed under `prefers-reduced-motion`.
- Client review adjustment: strengthened each store card’s hover treatment to visibly enlarge the individual card, lift it above neighboring cards, add a deeper shadow and introduce a honey border highlight.
- Client approved `contact-us-mockup-v1.0.0.html` on 2026-07-23, including the added homepage-style color and hover/pop interactions. Treat this mockup as the approved Contact Us design direction for future WordPress implementation.

## 2026-07-23 - Practitioners directory + profile design v1.0.0

- Created `practitioners-mockup-v1.0.0.html`, `practitioner-profile-mockup-v1.0.0.html`, and shared `practitioners-v1.0.0.css`, following the canonical Sheet prompt and approved interior-page visual direction.
- Preserved the current public directory's 28 unique practitioner/practice profiles and existing category structure, with search and area-of-practice filtering for easier discovery.
- Client review identified Dr. Khadra Kahin's separate Acupuncture and Chiropractic cards as a confusing duplicate. Combined them into one profile card labeled `Acupuncture · Chiropractic`; the single card still appears when either individual category filter is selected.
- Removed dead `#` contact links from directory cards because they incorrectly jumped visitors to the top of the page. Only records with an implemented profile route display a clickable detail link; other cards now show a non-interactive note until their profile content is connected.
- Client confirmed separate pages will not be built for every practitioner. Reworked the directory into 28 inline expandable profiles: collapsed cards provide category, name, practice, summary and city; the accessible disclosure button reveals only the address, phone, website, social handle, appointment note and rate currently available for that record.
- The standalone Dr. Kahin profile mockup is no longer part of the recommended implementation. Production should use one directory/archive template with structured practitioner fields and expandable details, avoiding thin individual profile pages.
- Preserved the longer practitioner descriptions where the live directory contains them. Each collapsed card keeps a concise preview; expanding `View full listing` reveals an `About` section before contact details. Listings without meaningful source biography omit the section rather than duplicating or inventing copy. Sensitive wellness language was kept cautious and directs visitors to confirm current services with the provider.
- Social-link review: added the confirmed IV Lounge Facebook page. Tammy Guerin's live listing contains spaced/malformed Instagram and Facebook handles rather than reliable profile URLs; retained the handles as plain text and visibly flagged them for confirmation instead of guessing destinations.
- Client approved the directory's content and expandable interaction but found the page visually bland. Added restrained, brand-aligned color without making the utility page promotional: a sage/honey hero wash, four rotating card accent families, colored category pills and disclosure controls, tinted expanded panels, and a warmer directory/footer transition. Kept contrast, scanability and the existing information hierarchy intact.
- Client approved Practitioners directory mockup v1.0.0 as the accepted design direction. Approved scope is one searchable/filterable directory page with 28 unique expandable listings, fuller source-based descriptions where available, inline contact/social details, no separate practitioner pages, no unsupported booking features, and the final restrained color treatment. Next step is WordPress implementation only when explicitly authorized.
- Designed a reusable profile template using Dr. Khadra Kahin as a content-complete representative example. The template supports only fields already present in the current directory: name, credentials, practice, category, bio, address, phone, website and other verified contact details.
- Used neutral detail/contact language throughout. No booking controls, invented services, practitioner-event relationships, unsupported contact methods or ecommerce were added. Appointment language remains reserved for Rebekah Niman's listing because the current content explicitly mentions appointments.
- Added concise page-specific metadata, accessible heading structure, verification guidance and cautious directory disclaimers. Global header/footer remain intentionally excluded per the approved interior-page convention.
- Pending production follow-ups: complete interactive desktop/tablet/mobile browser QA; confirm whether all 28 unique records are still active; correct source spelling and credential inconsistencies; obtain approved practitioner photos only where consistently available; and map verified fields into the WordPress practitioner content model.
- Client-facing next step: review the archive density, filters and representative profile structure, then approve the field model before WordPress implementation.

## 2026-07-23 - Page Inventory design statuses reconciled

- Rechecked the canonical Google Sheet against completed mockup files and client notes after the client noticed the Wellness Source Blog was still listed as unfinished.
- Updated Design Status to `Mockup ready` for Locations, Lapeer, Clarkston, Classes & Events, Wellness Source Blog, In-Store Products, Practitioners, and Contact Us, including their current mockup versions.
- Kept Build Status as `Not started` because these designs have not yet been implemented in WordPress.
- The next five unfinished mockups in inventory order are Grand Blanc, Lake Orion, Privacy Policy, Refund & Returns, and Terms & Conditions.
- Client correction: Clarkston is still actively being revised and has not been approved. Its Sheet Design Status was corrected to `In progress — not approved`; its WordPress Build Status remains `Not started`. Do not treat the existence of a mockup file as client approval.
- Client decision for Refund & Returns: preserve the current policy wording exactly. The canonical Sheet scope and copy/paste prompt now prohibit rewriting, paraphrasing, adding, removing, reordering, correcting, modernizing, or SEO-editing the policy copy. Work is limited to the shared legal-page presentation, responsive behavior, and accessibility that does not change wording; concerns must be documented separately rather than edited into the policy.
- Disclaimer-page decision: a dedicated `/disclaimer/` page remains required as page 16. The existing disclaimer text at the bottom of the live homepage is required source material and its core protections must not be weakened. Retain a concise sitewide footer disclaimer and change the footer Disclaimer link—which currently returns to the homepage—to the full `/disclaimer/` page during WordPress implementation. The dedicated page may organize and cautiously extend the existing concepts for education/non-medical advice, professional consultation, product/FDA claims, diagnosis/treatment, results, external resources, and applicable affiliate relationships, with qualified legal review before launch.

## 2026-07-23 - Clarkston location page design v1.0

- Created `clarkston-location-mockup-v1.0.html` for `/locations/clarkston/`, following the canonical Sheet prompt and the approved homepage, Our Story and location-page visual system.
- Built genuinely Clarkston-specific positioning around the Dixie Highway store, its 2019 opening, ribbon-cutting/community imagery, greater Clarkston-area context and the location's history of wellness fairs, visiting practitioners, demonstrations and local vendors.
- Verified and used the current public NAP and regular hours: 7093 Dixie Highway, Suite B, Clarkston, MI 48346; (248) 843-2011; Monday-Saturday 9am-7pm and Sunday 10am-5pm. Added direct call, directions and embedded-map routes.
- Added page-specific title, meta description, one H1, H2-H3 structure, cautious wellness language, local internal-link targets, descriptive image alt text and `HealthAndBeautyBusiness` schema. Ecommerce and WordPress implementation remain deferred.
- The events area is explicitly designed to pull only upcoming entries assigned to Clarkston in WordPress rather than hard-coding expiring dates. The preferred-store signup defaults to Clarkston but still needs to be connected to the client's actual email platform and routing.
- Structural QA passed for heading count, image alt text, responsive desktop/tablet/mobile breakpoints, Clarkston event targeting, preferred-store state, metadata and schema. Direct visual rendering of the local file was blocked by the browser security policy, so an interactive browser pass remains pending.
- Before launch: confirm the 2019 opening date with the client, approve the third-party review excerpt or replace it, confirm image usage/crops, verify holiday hours, connect live Clarkston events, validate form consent/routing and recheck NAP against the Google Business Profile.
- Client-facing next step: review the Clarkston story, product mix, community/event emphasis, photography and local-area wording before WordPress assembly.

### Clarkston photography correction

- Replaced every general brand/history placeholder in `clarkston-location-mockup-v1.0.html` with media verified as Clarkston-specific in Rebekah's WordPress library.
- The page now uses the current Clarkston interior (`cLARKSTON-STORE3.jpg`), Clarkston Dixie Highway storefront (`CLARKSTON-STORE.jpg`), earlier decorated Clarkston interior (`Clarkston-Store.jpg`) and Clarkston ribbon-cutting photo.
- Updated all image alternative text to describe the actual Clarkston scene shown. No unverified health-fair photos were used.
- Removed the internal WordPress event-filter instruction from the visible mockup; that implementation requirement remains documented only in project notes.
- Added lift and shadow hover behavior to the three Clarkston event/community cards, with equivalent keyboard focus styling and reduced-motion support.
- Revised the event/community cards after client review: confirmed all photographs are Clarkston-specific, kept the consistent 16:9 presentation, removed the `View larger photo` feature and replaced the third repeated interior with the verified Clarkston Dixie Highway storefront.
- Added more dark-green breathing room below the hero buttons before the quick-information bar.
- Client approved `clarkston-location-mockup-v1.0.html` on 2026-07-23 as the accepted Clarkston location-page design direction.
- Next step when authorized: assemble the approved design in WordPress, connect the live Clarkston event feed and preferred-store signup routing, then complete production responsive/SEO QA.
- On 2026-07-27, corrected a reduced-motion rule that was canceling the visible card enlargement in the client's browser. The standard hover now lifts 10px and scales to 105% with a deeper shadow; reduced-motion mode applies an immediate 103.5% enlargement without animated movement.
- Applied the approved Clarkston community-card pop-out interaction consistently to the Lapeer, Grand Blanc and Lake Orion location mockups: 10px lift, 105% scale, deeper shadow, stacking protection, extra grid room and an immediate 103.5% reduced-motion state.

## 2026-07-23 - Lake Orion location page design v1.0

- Created `lake-orion-location-mockup-v1.0.html` for `/locations/lake-orion/`, following the canonical Sheet prompt and the approved homepage, Our Story and location-page visual system.
- Built genuinely Lake Orion-specific content around the store's 2020 opening, its convenient M-24 / Clarkston Road location, nearby Orion-area communities, educational workshops, wellness fairs and seasonal local-goods events.
- Verified current public NAP and regular hours: 1095 S Lapeer Road, Lake Orion, MI 48360; (248) 929-8990; Monday-Friday 9am-7pm and Saturday-Sunday 10am-5pm. The mockup includes direct call, directions and embedded-map routes.
- Used Lake Orion-specific public WordPress media: the April 2026 store interior, a Lake Orion-hosted educational event image and the 2026 Lake Orion health-fair graphic. Photography source/crop approval remains required before production.
- Added a Lake Orion-specific title, meta description, canonical URL, one H1, clear H2-H3 structure, cautious wellness language, contextual internal links, descriptive alt text and draft `HealthAndBeautyBusiness` schema.
- The events section targets only the `lake-orion` event location/category and links to the Lake Orion event archive. During WordPress implementation it should query upcoming events dynamically rather than hard-code expiring dates.
- Preferred-store signup defaults to Lake Orion and mirrors the approved homepage field set, but still needs connection to the client's email platform and location routing.
- Structural QA passed for metadata, schema, heading count, image alt text, responsive breakpoints, location-event targeting and preferred-store selection. Browser rendering of the local file was blocked by the app's file-URL security policy, so interactive desktop/tablet/mobile visual QA remains pending.
- Before launch: confirm the 2020 opening date and regular/holiday hours with the client or Google Business Profile; approve the third-party review excerpt or replace it; confirm photography usage; connect the live Lake Orion event feed and signup routing; and validate final NAP/schema in WordPress.
- Client-facing next step: review the Lake Orion story, store imagery, local-area wording, event/community emphasis and customer-quote treatment before WordPress assembly.

### Lake Orion photography correction

- Client clarified that no image may appear more than once on the same page and that the shared Drive folder contains sufficient Lake Orion photography.
- Replaced all six image placements with six distinct, client-provided Lake Orion assets from the approved shared folder: two wide store views, one additional store/local-goods view, the Lake Orion team education display, a team member with a featured product and the Orion Area Chamber Bagels & Brew visit.
- Removed the repeated public WordPress store image and updated every alt description to match the actual client photo shown.
- Ongoing rule for this project: do not repeat the same image within a page when location-specific client photography is available.
- Client approved `lake-orion-location-mockup-v1.0.html` on July 23, 2026 after the shared-folder photography correction. Treat v1.0 as the accepted Lake Orion design direction for later WordPress implementation.
- Updated the canonical Page Inventory Design Status to `Approved v1.0`; WordPress Build Status remains `Not started`.
- Post-approval interaction adjustment: strengthened the three Lake Orion event/community cards so each card lifts and scales to 104.5% on hover or keyboard focus, matching the more noticeable pop-out treatment used on other approved pages. Preserved reduced-motion behavior.
- Follow-up correction: the initial enlargement was still too subtle in review. Increased the card interaction to a 7.5% scale with a 14px lift, stronger foreground shadow and a slight 3.5% image zoom, while retaining keyboard-focus parity and reduced-motion handling.
- Second follow-up correction: increased the Lake Orion event-card hover to a clearly visible 14% scale and 22px lift, added a honey-colored border, deeper shadow and 6% image zoom after the client found the prior treatment still too subtle.

## 2026-07-23 - Privacy Policy page design v1.0.0

- Created `privacy-policy-mockup-v1.0.0.html` for `/privacy-policy/`, following the canonical Sheet prompt and the approved homepage/interior-page visual system.
- Reorganized the current live WordPress policy into a readable legal-page layout with a restrained green hero, sticky section navigation, clear H1-H3 hierarchy, high-contrast body copy, related-policy links and responsive desktop/tablet/mobile behavior.
- Preserved the substance of the current policy while clearly marking unverified or incomplete areas for review. The mockup does not claim that unknown vendors, analytics tools, consent controls, security measures or retention periods are already in use.
- Added implementation review notes for public comments, Gravatar, media uploads, public accounts, contact/newsletter/text forms, spam protection, analytics, social embeds, cookies, backups, retention periods and incident procedures. Features that are not active should be removed from the final policy rather than retained as boilerplate.
- Added page-specific title, meta description, canonical URL, one H1, descriptive section headings and relevant internal links. No imagery or image schema was added because decorative photography would distract from a utility/legal page; crawlable text is the priority.
- The draft prominently states that material policy wording requires qualified legal review and is not presented as legal advice.
- Pending client/legal follow-ups: confirm the legal business identity and monitored privacy contact; inventory actual production vendors and cookies; confirm whether comments, accounts or public uploads exist; approve retention periods and request-verification procedures; and supply the final effective date.
- Client-facing next step: review the visual treatment and information architecture, then have the verified vendor/function inventory and material policy language reviewed before WordPress implementation.
- Client approved `privacy-policy-mockup-v1.0.0.html` on 2026-07-23 as the accepted Privacy Policy design direction. The canonical Page Inventory Design Status is now `Approved v1.0.0`; WordPress Build Status remains `Not started`.
- Approval covers the page’s visual treatment and information architecture, not final legal wording. Vendor/function verification, privacy contact details, effective date and qualified legal review remain required before production publication.
- Post-approval cleanup removed all visitor-facing editorial/review callouts from the mockup. Replaced the privacy-contact placeholders with the live site’s published Rebekah Spencer contact details: `rebekah@rebekahspureliving.com`, 588 S. Main Street, Lapeer, MI 48446 and (810) 660-8585.
- Client requested removal of the direct email address from the privacy contact card. The approved mockup now shows only the published Lapeer mailing address, phone number and general Contact Us route.
- Client gave final approval after the post-approval cleanup on 2026-07-23. Treat `privacy-policy-mockup-v1.0.0.html`—with all editorial callouts and the direct email address removed—as the final accepted Privacy Policy design direction. WordPress implementation and final legal/content verification remain pending.

## 2026-07-23 - Terms & Conditions page design v1.0.0

- Created `terms-conditions-mockup-v1.0.0.html` for `/terms-conditions/`, following the canonical Sheet prompt and the shared Privacy Policy legal-page presentation.
- Replaced the current live page's thin combination of wellness disclaimer and return policy with a practical starter Terms structure covering website use, informational content, cautious wellness limitations, practitioner listings, external shopping/referral links, purchases, acceptable use, intellectual property, submissions, warranties, liability, governing law, changes and contact information.
- Kept Refund & Returns as a separate linked policy rather than rewriting or absorbing its wording. The redesign still assumes no onsite cart or checkout; external provider purchases remain governed by the provider's terms.
- Added explicit disclosure language for possible referral, affiliate or commercial relationships, while requiring the actual relationships and link-level disclosures to be verified before launch.
- Added page-specific title, meta description, canonical URL, one H1, H2 structure, related internal links, responsive section navigation, mobile tap targets and reduced-motion support. No decorative imagery or image schema was added because a legal utility page benefits from direct, crawlable text.
- Marked the entire document as a starter draft for qualified legal review and left visible placeholders for the effective date, legal entity, jurisdiction, monitored legal contact and mailing information. The mockup is not presented as final legal advice.
- Pending client/legal follow-ups: verify the operating legal entity and jurisdiction; confirm all affiliate/referral relationships; define terms for call-to-order and shipped store purchases; decide whether public comments, reviews or uploads remain enabled; tailor IP, submissions, warranty, indemnity, liability and dispute provisions; and supply the final effective date and contact information.
- Client-facing next step: review the visual treatment and business-process descriptions, then send the verified entity, sales-channel and referral details to qualified counsel before WordPress implementation.
- Client review adjustment: removed the visible introductory legal-review notice and the internal launch/legal-drafting callouts from the Purchases and Disclaimers sections. Legal-review requirements remain documented in client notes rather than displayed as production-facing page content.
- Client review adjustment: replaced the contact placeholders with information published on the live Contact Us page: Rebekah’s Health & Nutrition, owner Rebekah Spencer, 588 S. Main Street in Lapeer, and (810) 660-8585. Linked message inquiries to the live Contact Us page because the public email address is protected/obfuscated in the fetched page rather than safely guessable.
- Client review adjustment: removed the owner line from the public terms contact card. Promoted practitioner-listing language from a colored callout inside the wellness disclaimer to its own numbered section, then renumbered the remaining sections and added the new section to the page navigation.
- Client approved `terms-conditions-mockup-v1.0.0.html` on 2026-07-23 as the accepted Terms & Conditions design and content direction. The canonical Page Inventory Design Status is now `Approved v1.0.0`; WordPress implementation remains not started and requires explicit authorization.
## 2026-07-23 — Refund & Returns Policy page mockup v1.0.0

- Created `refund-returns-policy-mockup-v1.0.0.html` as a polished, responsive legal-page presentation aligned with the approved homepage/Our Story brand direction and the existing Privacy Policy legal-page system.
- Client approved the v1.0.0 page design on 2026-07-23.
- Preserved the live Refund & Returns Policy wording, headings, punctuation, order, and the existing forced line break exactly; no policy copy was rewritten or SEO-edited.
- Added presentation-only improvements: branded header/footer, restrained policy hero, sticky/stacking section navigation, clearer typographic hierarchy, accessible skip link and landmarks, keyboard focus states, reduced-motion handling, and desktop/tablet/mobile layouts.
- Added page-level metadata without changing policy copy: title tag, meta description, canonical URL, one H1, structured H2/H3 hierarchy, and internal links to Privacy Policy, Contact Us, and Store Locations.
- Legal/operational review needed before WordPress implementation: confirm whether this policy still matches current return intake and refund operations; confirm where customers send or bring returns; clarify whether the policy applies only to purchases made directly from Rebekah's; and determine how purchases completed on third-party partner/affiliate sites are handled. These concerns were intentionally not inserted into the page because the canonical task requires the current wording to remain unchanged.
- Client-facing next step: review and approve the visual treatment, then obtain qualified legal/operational confirmation of the existing wording before production. WordPress implementation remains deferred.

## 2026-07-23 - Grand Blanc location page design v1.0

- Created `grand-blanc-location-mockup-v1.0.html` for `/locations/grand-blanc/`, following the canonical Sheet prompt and the approved homepage, Our Story and location-page visual system.
- Built genuinely Grand Blanc-specific content around the Perry Road store, greater Genesee County service area, recurring Mighty Greens Wednesday pop-up, local honey education, past classes and documented community-business partnerships.
- Verified the current public NAP and regular hours across Rebekah's live locations page and current third-party listings: 252 Perry Road, Suite 4, Grand Blanc, MI 48439; (810) 866-4642; Monday-Saturday 9am-7pm and Sunday 10am-5pm. The live page has one stray `48449` instance, but its detailed listing and other current sources use `48439`; production should use `48439` after final Google Business Profile confirmation.
- Added direct call, directions and embedded-map routes; a page-specific title, meta description, canonical URL, one H1, clear H2-H3 structure, internal links, cautious wellness language, descriptive alt text and draft `HealthAndBeautyBusiness` schema.
- Used a Grand Blanc storefront/local-product collage from a current public business listing as a design-only photo placeholder and Rebekah's official Grand Blanc Mighty Greens event artwork. Image ownership, crop and replacement with client-provided original Grand Blanc photography must be confirmed before production.
- The events section targets `grand-blanc` and is designed to pull current Grand Blanc entries during WordPress assembly. Only the recurring microgreens feature links to its official event page; other event copy stays evergreen to avoid hard-coding expired schedules.
- Preferred-store signup defaults to Grand Blanc but still needs connection to the client's email platform and store-routing fields.
- Pending client-facing review: approve the local story and service-area wording; provide or select original Grand Blanc store/team photography; approve or replace the third-party review excerpt; confirm NAP, holiday hours and any opening-year claim; connect the live event feed and signup routing.
- WordPress implementation remains deferred until explicitly authorized.

### Grand Blanc browser-review corrections

- Reworked the page after client browser comments to follow the approved location-page template more closely.
- Corrected the hero crop so the Grand Blanc storefront sign and entrance remain visible; removed the overlapping Perry Road stamp.
- Removed the experimental `GB` graphic and abstract circles. Replaced that panel with a full team photograph and placed the local-context copy over a readable dark image gradient.
- Replaced the four abstract guidance tiles with official Rebekah's photography while preserving the concise comparison/help facts.
- Rebuilt the events area as three equal image cards, changed the unclear `Recurring local pop-up` label to `Wednesday local pop-up`, shortened the microgreens copy and removed the oversized half-poster/half-empty layout.
- Client corrected the image-source direction: Grand Blanc photography was already available in the approved shared Drive folder and should have been used before unrelated archive imagery.
- Removed every borrowed archive/location image from the page and deleted those temporary files. The corrected design uses the shared-folder originals `Grand blanc team.jpg`, `Grand Blanc- Todd store pick.jpg`, and `Grand Blanc Staff for About us section of website`, plus the official Grand Blanc Mighty Greens event artwork. Each image appears only once.
- The team/store image now leads the hero, the Grand Blanc staff portrait supports the local-story section, and Todd's in-store product photo supports the guidance section. The remaining event cards are intentionally text-only so the design does not repeat images or imply that unrelated photos are Grand Blanc-specific.
- Client-facing next step: review the corrected image placements and identify a verified exterior storefront original if an exterior storefront hero is still preferred. Do not substitute another location's storefront or a third-party collage.
- Follow-up image audit confirmed the approved shared folder contains only three Grand Blanc-labelled photos and all three are already used once. Rebekah's current WordPress media search returns only the official Mighty Greens Grand Blanc artwork, which is also already used. Keep the Classes & Conversations and Community Connections cards image-free until additional verified Grand Blanc assets are supplied.
- Removed the redundant `We are in Suite 4.` sentence from the visit section after browser review; Suite 4 remains correctly included in the structured address details and map destination.
- Client approved `grand-blanc-location-mockup-v1.0.html` on 2026-07-23 as the accepted Grand Blanc location-page design direction. Updated the canonical Page Inventory Design Status to `Approved v1.0`; WordPress Build Status remains `Not started`.

## 2026-07-27 - Grand Blanc approved-page interaction adjustment

- Added the established location-card hover behavior to all three Grand Blanc event/community cards. After two client reviews found the initial effects too subtle, increased the final interaction to an unmistakable 18px upward lift, 12% scale increase and heavy floating shadow.
- Added matching keyboard `focus-within` behavior and a visible link focus outline. Reduced-motion preferences disable the movement while retaining the static card presentation.

## 2026-07-23 - Disclaimer page design v1.0.0

- Created `disclaimer-mockup-v1.0.0.html` for the planned `/disclaimer/` page, following the canonical Sheet prompt and the approved shared legal-page visual system.
- Used the disclaimer at the bottom of the live homepage as required source material and preserved its core protections covering informational use, non-medical-advice language, FDA/product-claim limitations, diagnosis and treatment, medication changes, product labels and consultation with qualified healthcare professionals.
- Cautiously extended the starter draft to address individual results and testimonials, practitioner and external resources, applicable affiliate/referral relationships and emergency limitations. The draft requires qualified legal review before production and does not present itself as final legal advice.
- Added a descriptive title, meta description, canonical URL, one H1, accessible H2 structure, section navigation, related legal links, mobile/reduced-motion behavior and an example concise sitewide footer disclaimer linking to the planned full Disclaimer page.
- No imagery or image schema was added because this is a legal-clarity page whose priority is readable, crawlable text. Ecommerce and WordPress implementation remain deferred.
- Pending client/legal follow-ups: supply the effective date; verify the legal entity and final wording; inventory actual affiliate/referral relationships and required link-level disclosures; confirm manufacturer/product-description practices; and have qualified counsel review the FDA, liability, testimonial, practitioner and emergency language.
- Client-facing next step: review the visual treatment and clarity of the draft, then route the wording to qualified legal counsel before WordPress assembly. During implementation, point the footer Disclaimer link to `/disclaimer/` instead of the homepage.
- Client approved `disclaimer-mockup-v1.0.0.html` on 2026-07-23. The canonical Page Inventory already records the Design Status as `Approved v1.0.0`; WordPress Build Status remains `Not started`.

## 2026-07-23 - Tracker approval update

- Client confirmed approval of the Clarkston Location page and the Refund & Returns Policy page.
- Updated `Page Inventory!E10` and `Page Inventory!E18` in the canonical Google Sheet to `Approved`.
- The Refund & Returns Policy remains presentation-only: preserve the current policy wording exactly during WordPress implementation.
- WordPress Build Status remains `Not started` for both pages.

## 2026-07-23 - Peptides & Injectables scope confirmed

- Client confirmed the current Peptides & Injectables pathway sends visitors directly to an affiliate website.
- Treat the planned page like the other external affiliate/referral landing pages, not as an onsite clinical service, appointment system, ecommerce checkout, or prescribing pathway.
- Updated the canonical Page Inventory type to `Affiliate/referral`, Design Status to `Needs mockup`, and revised the scope, SEO/content notes, and copy/paste prompt accordingly.
- The page task must verify the current destination URL and required disclosure, clearly label the external action, use careful medical language, and avoid unsupported claims.

## 2026-07-23 - Active affiliate-page work

- Client is currently working on the Shop Designs for Health and Peptides & Injectables mockups.
- Updated both canonical Page Inventory Design Status cells to `In progress — not approved`; WordPress Build Status remains `Not started`.
- Shop Fullscript and Shop LifeWave are already recorded as approved, so no additional untouched affiliate mockups remain after the two active pages.

## 2026-07-23 - Internal approval versus end-client approval

- Agency/project-owner approval is distinct from Rebekah's final client approval.
- The Locations hub, Lapeer Location, Classes & Events, Wellness Source Blog, and In-Store Products mockups have been approved internally and are being taken to the end client for review.
- Updated their canonical Page Inventory Design Status to `Internally approved — awaiting client approval`.
- WordPress Build Status remains `Not started`; client feedback or final approval should be recorded before production implementation.
- Practitioners and Contact Us are also internally approved and awaiting end-client approval. Their canonical Design Status cells now use the same wording; WordPress Build Status remains `Not started`.

## 2026-07-23 - Remaining design coverage check

- Confirmed all 20 Page Inventory entries now have a completed, approved, internally approved, or active design direction.
- The required individual Blog Post reusable template is still outstanding; the Wellness Source Blog work covers the archive only.
- Search Results and no-results states are deferred until Phase Two ecommerce introduces site search; they are not part of the Phase One design scope.
- The Event Detail, Location Detail, and Practitioner Profile reusable templates already have corresponding mockup files.
- Client subsequently approved the Individual Blog Post template and branded 404 design internally. Both are now awaiting end-client approval, and the canonical Templates & Global notes reflect that status.
- With Search Results deferred to Phase Two, no additional Phase One page or template designs remain unless end-client feedback requires revisions.

## 2026-07-24 - Page approval statuses normalized

- Confirmed the approval-stage distinction across the complete 20-page inventory.
- Home is now marked `Client approved v1.21`, and Our Story / Meet Rebekah is marked `Client approved v1.1.6`.
- Every other Page Inventory entry is now marked `Internally approved — awaiting client approval`.
- All WordPress Build Status values remain `Not started`; this update records design approval stage only.

## 2026-07-23 - Shop Designs for Health page mockup v1.0.0

- Created `shop-designs-for-health-mockup-v1.0.0.html` for the proposed `/shop-designs-for-health/` affiliate/external-partner landing page, following the canonical Sheet prompt and approved homepage/Our Story visual system.
- Verified the current live Rebekah's navigation destination as `https://www.designsforhealth.com/u/rebekahspencer/products/`. The URL identifies the external catalog as associated with Rebekah Spencer; the mockup uses that exact destination and labels every shopping CTA as external.
- Confirmed Designs for Health operates the destination catalog, account, checkout, payment, shipping, returns and customer-support experience. No onsite cart, product grid, pricing or checkout was added.
- The exact commercial relationship is not publicly verifiable. Before launch, the client must confirm account ownership/control and whether Rebekah's receives commissions, referral compensation, discounts, credits or another material benefit, then have qualified counsel approve the final disclosure wording.
- Used original educational copy and original CSS artwork rather than copying partner product photography, catalog text or logo assets. Any future Designs for Health trademarks or marketing materials require confirmation of account authorization and current brand-use rules.
- Added page-specific title, meta description, canonical URL, one H1, H2/H3 structure, internal links, a conservative `WebPage` schema draft, external-link security attributes, clear partner handoff language and cautious supplement/medical language.
- Client-facing next step: approve the design and tone, confirm the commercial/account relationship and required disclosure, and have Designs for Health or counsel confirm permitted brand references before WordPress implementation. WordPress implementation remains deferred.
- Client review adjustment: removed the mockup-specific header and footer because the approved sitewide header/footer already exist and should be inherited during WordPress assembly. Confirmed that the page’s supplement artwork is original CSS illustration—not sourced photography or Designs for Health creative—and that removing the global chrome also removes the only referenced raster image, Rebekah’s local logo.
- Client review adjustment: replaced the abstract CSS supplement artwork with two original, AI-generated, brand-neutral photographs: an unbranded amber-bottle hero still life and an unbranded product-comparison scene. Saved optimized WebP assets under `output/shop-designs-for-health/` for eventual upload to the WordPress Media Library. The images intentionally avoid Designs for Health logos, copied packaging, readable label claims and medical-treatment imagery; they are presentation assets rather than depictions of specific products.
- Client review adjustment: client requested a more creative direction with no pill bottles. Replaced both v1 images with original v2 assets: a sculptural “science meets nature” hero built from translucent leaves, glass, stone and brass, plus a hands-on “wellness map” of herbs, seeds, citrus and color swatches. Both are product-neutral, contain no pills, bottles, packaging, logos, claims or medical props, and are saved as optimized local WebP files for future WordPress Media Library upload. The earlier v1 assets remain in the project as unused alternatives.
- Browser-review correction: reduced and constrained the hero’s `External shop & checkout` badge typography so every word fits cleanly inside the circular badge at desktop and mobile sizes.
- Client approved `shop-designs-for-health-mockup-v1.0.0.html` on 2026-07-23 as the accepted Shop Designs for Health page design direction. Updated `Page Inventory!E22` in the canonical Google Sheet to `Approved`; WordPress Build Status remains `Not started`. Before production, confirm the exact commercial/account relationship and final disclosure language, then upload the approved local WebP assets to the WordPress Media Library.

## 2026-07-23 - Disclaimer approval

- Client confirmed that the highlighted “Product labels still matter” warning should remain visible as visitor-facing safety language.
- Client approved `disclaimer-mockup-v1.0.0.html` as the accepted Disclaimer page design and starter-content direction.
- Updated the canonical `Page Inventory!E20` Design Status to `Approved v1.0.0`; WordPress Build Status remains `Not started`.
- Approval does not replace the qualified legal review, effective-date confirmation and affiliate/referral verification required before publication.

## 2026-07-23 - Shop Fullscript page mockup v1.0.0

- Created `shop-fullscript-mockup-v1.0.0.html` for the proposed `/shop-fullscript/` landing page, following the canonical Sheet prompt and the approved homepage/Our Story visual direction.
- Verified that the live Rebekah's navigation currently promotes `Shop 355+ Brands at Fullscript` and sends visitors to `https://us.fullscript.com/welcome/rebekahs`.
- Built a clear external-partner journey explaining what Fullscript is, when it may be useful, how account-based ordering works, and that catalog, checkout, payment, shipping, returns, privacy and support are handled by Fullscript rather than on Rebekah's website.
- Used cautious wellness language, label and interaction reminders, a non-medical-advice notice, descriptive image alt text, external-link cues, responsive desktop/tablet/mobile layouts, keyboard focus styling and reduced-motion support.
- Added page-specific SEO metadata, one H1, H2/H3 hierarchy, internal links to Locations and Disclaimer, `WebPage` schema, and `rel="sponsored noopener noreferrer"` on Fullscript calls to action.
- The public website establishes the destination and active promotion but does not verify the account owner, compensation terms or exact affiliate/referral relationship. The mockup therefore includes a conspicuous publication-review disclosure instead of asserting an unverified financial arrangement.
- Pending client/legal follow-ups: confirm the Fullscript account owner; confirm whether Rebekah's receives compensation, discounts, data access or another benefit; approve the required link-level disclosure; confirm whether `355+ brands` remains current; and approve or replace the generic design-only supplement image.
- Client-facing next step: review the page design and send the verified Fullscript relationship details for final disclosure wording. WordPress implementation remains deferred until explicitly authorized.
- Browser-review image revision: replaced the clinical ibuprofen blister-pack hero photo with an original, brand-aligned still life of unbranded amber supplement bottles, capsules and restrained botanical accents. Saved the project asset as `output/shop-fullscript/fullscript-hero-supplements-v1.png` and updated the descriptive alt text.
- Client approved `shop-fullscript-mockup-v1.0.0.html` on 2026-07-23 as the accepted Shop Fullscript page design and starter-content direction. Updated the canonical Page Inventory Design Status to `Approved v1.0.0`; WordPress Build Status remains `Not started`. Approval does not remove the pre-publication requirement to verify Fullscript account ownership, relationship/compensation terms, the current brand count and legally appropriate link-level disclosure wording.
## 2026-07-23 — Shop LifeWave page mockup v1.0.0

- Created `shop-lifewave-mockup-v1.0.0.html` using the approved homepage v1.21 / Our Story v1.1.6 visual system, with a responsive external-partner landing-page experience and no onsite ecommerce.
- Grounded the content in the canonical Page Inventory/Page Prompts row 23 and reviewed the current live Rebekah's LifeWave event content plus LifeWave's current official compliance, advertising, FAQ, science, and policies materials.
- Used cautious, original copy focused on product format, shopping transparency, label review, and general wellness context. Avoided medical, disease, performance, stem-cell, guaranteed-result, and unsupported efficacy claims.
- Added page-specific title/meta description, one H1, structured H2/H3 content, relevant internal links, external-link labeling, `sponsored` link attributes, and WebPage schema.
- The only Rebekah's-associated destination found was `https://lifewave.com/rniman` on a 2023 event page. It remains a pre-publication placeholder, not a fully verified current affiliate URL.
- Pending before publication: confirm the exact destination URL; confirm account ownership; confirm whether Rebekah's is an Independent Brand Partner and/or receives compensation or another benefit; obtain the exact approved relationship/affiliate disclosure; have LifeWave/legal compliance review the page; replace the review note with final disclosure copy.
- Client-facing next step: ask the client to supply or confirm the current LifeWave account/link and relationship details before WordPress implementation.
- Client approved the headerless/footerless `shop-lifewave-mockup-v1.0.0.html` page-body design on 2026-07-23. Updated the canonical Page Inventory Design Status to `Approved v1.0.0`; WordPress Build Status remains `Not started`.
- Approval does not remove the pre-publication requirement to verify the current LifeWave destination URL, account ownership, Brand Partner/compensation relationship, and final approved disclosure language.

## 2026-07-23 — Peptides & Injectables page mockup v1.0.0

- Created `peptides-injectables-mockup-v1.0.0.html` for the planned `/peptides-injectables/` affiliate/referral page, following the approved homepage v1.21 / Our Story v1.1.6 visual direction and the established headerless/footerless partner-page treatment.
- Verified the current live Rebekah's Shop navigation destination as `https://elliemd.com/?bp=rebekahspencer`. All referral CTAs clearly open EllieMD as an external destination and use sponsored/external-link attributes.
- Clarified the role boundary throughout the page: Rebekah's provides context and a referral link; EllieMD, independent licensed providers and participating pharmacies handle assessment, medical review, eligibility, prescribing decisions, payment, fulfillment and support.
- Used cautious, original educational copy without recommending products, making outcome claims, implying guaranteed eligibility or presenting Rebekah's as a clinical provider. No onsite cart, checkout, prescribing, booking or medical intake was added.
- Added page-specific title/meta description, canonical URL, one H1, structured H2/H3 content, internal links to In-Store Products and Disclaimer, descriptive external-action labels, responsive desktop/tablet/mobile layouts, focus styling, reduced-motion support and conservative `WebPage` schema.
- The query parameter strongly indicates a Rebekah Spencer partner link, but the exact account ownership, commercial terms, compensation and required disclosure are not publicly verifiable. The mockup therefore includes a visible pre-publication review note rather than asserting an unverified commission arrangement.
- Pending before publication: confirm the exact EllieMD destination and account owner; confirm Independent Brand Partner/referral status and any compensation or other material benefit; obtain EllieMD- and counsel-approved disclosure wording; verify current state availability, program/refund language and brand-reference permissions; complete legal/medical compliance review.
- Client-facing next step: review the page design and role-boundary language, then provide the verified EllieMD relationship details so the disclosure can be finalized before WordPress implementation.
- Browser-review correction: fixed the three pre-continuation guidance cards so their green summary lines participate in the card layout instead of being absolutely positioned. This removes the paragraph/summary overlap seen at the client's review viewport and preserves consistent bottom alignment responsively.
- Client disliked the original CSS vial/orbit illustration and overlaid “Review first” card. Replaced the entire hero visual with an original AI-generated editorial still life featuring an unbranded amber vial, blank kraft carton, consultation notebook and restrained botanicals in the approved cream/sage/amber palette. The asset intentionally contains no needles, people, logos, readable claims or treatment imagery and is saved under `output/peptides-injectables/`.
- Client requested that the replacement hero contain no pill bottle. Generated and installed a v2 consultation-focused still life with the bottle/vial removed entirely; the visible scene now uses only a notebook, pen, blank kraft carton, ceramic vase and botanicals. Updated the image alt text accordingly.
- Client approved `peptides-injectables-mockup-v1.0.0.html` on 2026-07-23 as the accepted Peptides & Injectables affiliate/referral page design and starter-content direction. Updated the canonical Page Inventory Design Status to `Approved v1.0.0`; WordPress Build Status remains `Not started`.
- Approval does not remove the pre-publication requirement to verify the EllieMD destination, account ownership, commercial/referral relationship, compensation terms, final disclosure wording and applicable medical/legal compliance review.

## 2026-07-23 — Individual Blog Post reusable template v1.0.0

- Created `blog-post-mockup-v1.0.0.html` as the outstanding reusable Individual Blog Post template for the 21 existing articles, following the approved homepage v1.21 / Our Story v1.1.6 visual direction and the Wellness Source archive styling.
- Used the current “Making Sense of Sunscreen Ingredients” article as representative content while preserving its URL, author, publication date, category, featured image and core Q&A format.
- Added a responsive editorial hero, breadcrumb, reading time, share controls, sticky contents navigation, highly readable article measure, cautious editorial note, visible sources, author/expertise panel, preserved taxonomy links and related-article pathways.
- Added page-specific title/meta/canonical data and conservative `BlogPosting` schema. The revised sample copy intentionally removes or qualifies unsupported absolutes from the live article and points readers to FDA, CDC and American Academy of Dermatology guidance.
- This is a design/content-system mockup only. Before WordPress implementation, map each existing post’s original SEO fields and media; verify author bios and reviewer credentials; establish a source-review/date-modified workflow; replace placeholder share actions with the production component; and medically/editorially review health claims article by article.
- Client-facing next step: review and approve the template’s visual hierarchy, sourcing treatment and cautious editorial tone before it is applied to all existing posts. WordPress implementation remains deferred.
- Client approved `blog-post-mockup-v1.0.0.html` on 2026-07-23 as the accepted Individual Blog Post reusable-template design direction. The canonical `Templates & Global` row does not include a design-status field, so no Sheet status was changed; the separate Wellness Source Blog archive status remains unchanged.
- Approval does not remove the pre-WordPress requirements to preserve each existing post’s URL, author, date, categories, imagery and SEO data; review health claims and sources article by article; confirm author/reviewer credentials; and implement the production share and related-content components.
## 2026-07-23 — 404 / Page Not Found mockup v1.0.0

- Created `404-page-mockup-v1.0.0.html` as the branded sitewide 404 treatment requested in the canonical `Templates & Global` plan.
- Kept the experience intentionally concise and recovery-focused: clear error explanation, homepage/store actions, functional site search, and direct links to in-store products, classes/events, and contact.
- Followed the approved homepage v1.21 / Our Story v1.1.6 visual system with pine, cream, sage and honey colors, Fraunces/DM Sans typography, botanical geometry, responsive layouts, accessible focus states, skip navigation and reduced-motion support.
- Added `noindex,follow`, a descriptive browser title, one H1, semantic search markup and useful internal links. No schema or meta description was added because a 404 response is not an SEO landing page.
- Pending WordPress implementation: connect the mockup to the theme's real global header/footer and search template, ensure the server returns HTTP 404 for missing URLs, test search/no-results behavior, and verify analytics tracking for broken-path recovery.
- Client-facing next step: review and approve the 404 design direction before WordPress implementation.
- Client review revision: removed the mockup header and footer so the deliverable is page-body only, matching the current component-review workflow. Restacked the illustration so the `404` numerals remain fully in front of both decorative leaves, and moved the circular recovery message above the artwork with a light contrast border and responsive sizing for improved readability.
- Second client review revision: added a third muted-berry leaf behind the `404` for a light additional color accent. Moved the circular “Let’s get you back on a helpful path” message completely below the floral artwork and reserved responsive spacing beneath the illustration so the circle does not overlap the artwork or recovery cards.
- Third client review revision: removed all three decorative leaf shapes from the `404` artwork at the client’s request. Retained the clean oversized numerals and the circular recovery message below them.
- Client approved `404-page-mockup-v1.0.0.html` on 2026-07-23 as the accepted headerless/footerless 404 page-body design. The canonical Sheet lists Search and 404 as a global component rather than an inventory page, so it has no dedicated Design Status cell to update. WordPress implementation remains pending; production must use the real global header/footer around this approved page body, return a true HTTP 404 response, retain `noindex,follow`, connect site search, and verify broken-link recovery behavior.

## 2026-07-24 — Location-page Google Business Profile links

- Audited the four approved location mockups. Each already has prominent `Get Directions` links, an embedded Google map, and a `hasMap` schema property, but these currently use address-based Google Maps URLs rather than verified permalinks to the exact Google Business Profile listings.
- Decision: keep the existing placement and design. During WordPress implementation, replace each address-based `Get Directions` destination with that store's verified Google Maps/Business Profile permalink. A separate “View our GBP” link is unnecessary.
- Pending follow-up: obtain and verify the exact GBP/Google Maps listing URL for Lapeer, Grand Blanc, Clarkston, and Lake Orion, and ensure each GBP's website field links back to its matching website location page.

## 2026-07-24 — Single GitHub Pages client review hub

- Confirmed GitHub Pages is already connected once to the repository's `main` branch at the root, with HTTPS enabled and a successful deployment from the current remote commit.
- Replaced the repository landing page with a no-index client review dashboard linking to the current mock-up for all 20 canonical page concepts, the event-detail, practitioner-profile, and individual-blog-post templates, and the approved 404 design.
- Kept the canonical approved versions for Home (`third-mockup.html`, v1.21) and Our Story (`story-mockup-v1.1.6.html`) in the review hub; older version files remain in the repository but are not presented to the client.
- Added `.nojekyll` so GitHub Pages serves the static mock-up assets directly without Jekyll processing.
- Client-facing next step: share the single GitHub Pages review URL after the updated deployment is verified. Future mock-ups can use the same connection and be added to the dashboard.

## 2026-07-27 — Practitioner profile removed from review hub

- Removed the reusable Practitioner Profile option from the GitHub Pages client review dashboard at the client's direction.
- Decision: individual practitioner pages will be built separately, so the client will not be asked to review or choose a generic reusable practitioner-profile option from the hub.
- Updated the review-hub summary to show two reusable templates plus the approved 404 design. The existing practitioner profile mock-up file remains in the project record but is no longer linked from the client dashboard.

## 2026-07-29 — Location-system consistency pass v2.0.0

- Reviewed client feedback that body typography and repeated modules appeared inconsistent between the approved homepage, Locations hub, and individual location pages.
- Confirmed the files already specify the same core typefaces—DM Sans for body copy and Fraunces for headings—but differences in sizing, line height, spacing, card treatments, and interactions made the pages feel less unified.
- Preserved all existing v1.0 location mockups unchanged and created separate v2.0.0 files for the Locations hub, Lapeer, Grand Blanc, Clarkston, and Lake Orion.
- Added `location-system-v2.0.0.css` as a narrowly scoped consistency layer derived from the approved homepage. It standardizes body typography, labels, buttons, card radius/shadows, event-card proportions and typography, form controls, focus states, responsive behavior, and reduced-motion behavior.
- Kept page-specific structure, store content, maps, hours, imagery, and CTAs unchanged. This pass intentionally addresses the stated consistency concern without introducing a broader redesign.
- Versioning decision: substantial client-feedback rounds advance the minor version (`v2.0.0`, `v2.1.0`, `v2.2.0`); small corrections within a round advance the patch version (`v2.0.1`, `v2.0.2`).
- Pending: visually review the v2.0.0 pages in the normal client-preview environment before presenting them, then address the client's remaining feedback items individually.
- Client-facing next step: compare the v2.0.0 Locations and individual-location pages against the approved homepage and confirm the shared typography and repeated-module system now feels consistent.

## 2026-07-29 — Locations hub client-feedback revision v2.1.0

- Created `locations-mockup-v2.1.0.html` from v2.0.0 so the earlier consistency pass remains available for comparison and rollback.
- Removed the complete “At a glance / Which store works for your day?” hours-comparison section at the client's request. Store hours remain available within each location card, so essential information was not removed from the page.
- Replaced the confusing abstract map-pin illustration in the hero with a clear, labeled four-store photo panel using existing Lapeer, Grand Blanc, Clarkston, and Lake Orion imagery.
- Each hero photo links directly to its matching store card, improving usefulness without changing the page's navigation model or adding new functionality.
- Added `locations-hub-v2.1.0.css` only for the new hero photo panel. The shared v2.0.0 typography and component-consistency layer remains unchanged.
- No other page sections, copy, store details, CTAs, individual location pages, or prior versions were changed in this feedback round.
- Pending: visually confirm image cropping and label readability in the normal client-preview environment at desktop and mobile widths before client presentation.
- Client-facing next step: review the simplified page flow and confirm that the four-store photo panel is clearer and more useful than the former abstract map.

## 2026-07-29 — Our Story condensation pass v2.2.0

- Created `story-mockup-v2.2.0.html` as a separate client-feedback revision; approved `story-mockup-v1.1.6.html` remains unchanged for comparison and rollback.
- Reduced the page from 12 content sections to 7 while retaining the approved visual direction and the parts that contribute distinct information.
- Removed five sections that repeated themes or diverted from the core story: the second “Rebekah’s difference” explanation, separate values-card section, blog-promotion section, second historical photo timeline, and standalone quote.
- Retained the hero, Rebekah’s personal background, concise business journey, private-label chapter, community role, personal/family context, and final store/event action.
- Added `story-condensed-v2.2.0.css` to tighten vertical spacing and improve the retained private-label section’s information density without redesigning the page.
- No biographical facts, credentials, store-opening dates, product claims, links, imagery in retained sections, or prior versions were altered during this pass.
- The revised HTML has seven sections versus twelve in v1.1.6, a five-section reduction. Static structure checks confirmed the retained hero navigation targets remain present.
- Pending: visually verify pacing, image crops, and mobile spacing in the normal client-preview environment before presenting the revision.
- Client-facing next step: compare v2.2.0 with v1.1.6 and confirm the shorter page still communicates Rebekah’s story, business growth, product-development chapter, community role, and personal grounding without feeling repetitive.

## 2026-07-29 — Homepage portrait and mobile-shipping fixes v2.3.0

- Created `third-mockup-v2.3.0.html` as a separate feedback revision; the approved `third-mockup.html` and its existing shared styles remain unchanged for comparison and rollback.
- Replaced the low-resolution remote Meet Rebekah image with the previously created AI-enhanced 1254×1254 portrait at `output/homepage/rebekah-story-ai-enhanced-v1.png`.
- Changed only the homepage story-image presentation from a forced `object-fit: cover` crop to a full-image, square presentation so Rebekah is not cut off at desktop widths.
- Added `homepage-feedback-v2.3.0.css` with narrowly scoped responsive fixes for the dynamically mounted “We Ship Nationwide” section.
- The shipping fix constrains the mount, container, panel, content columns, buttons, and three-image collage to the viewport; reduces padding and headline size on narrow phones; and adds a 360px safeguard without changing the banner copy, phone number, action, or desktop layout.
- Confirmed the enhanced portrait exists at 1254×1254, the v2.3.0 page links the new stylesheet and image, the shipping mount remains present once, and the original homepage/CSS files are untouched.
- Pending: visually verify the portrait framing at representative desktop widths and the full shipping panel at approximately 360px and 320px in the normal client-preview environment.
- Client-facing next step: confirm that Rebekah’s portrait now appears clear and complete on desktop and that the nationwide-shipping banner no longer clips or creates horizontal scrolling on the client’s phone.

## 2026-07-29 — Grand Blanc inventory accuracy corrections v2.4.0

- Created `grand-blanc-location-mockup-v2.4.0.html` as a separate client-feedback revision; the existing v2.0.0 Grand Blanc mockup remains available for comparison and rollback.
- Removed the inaccurate statement that Grand Blanc carries refrigerated or frozen selections.
- Revised the Specialty Foods card to describe better-for-you snacks and pantry finds and to clarify that fresh items may appear only during special vendor pop-ups.
- Changed the events heading from “Fresh food, useful learning and local connection” to “Useful learning and local connection” so the page no longer presents fresh food as a regular store offering.
- Retained the Mighty Greens microgreens event card because it is explicitly presented as a dated vendor pop-up with a schedule-confirmation instruction, consistent with the client's clarification.
- No other Grand Blanc content, store information, events, imagery, layout, shared location styling, or prior versions were changed.
- Static text verification confirmed the revised file contains no remaining refrigerator, freezer, or regular-fresh-food claim.
- Client-facing next step: confirm the revised Specialty Foods wording accurately reflects Grand Blanc's normal inventory and that the vendor-pop-up exception is stated clearly.

## 2026-07-29 — AI-restored Rebekah portrait preview

- Confirmed the WordPress media-library “full size” source for `story.jpg` is only 405×400 pixels; no larger original of that exact photograph exists in the current media library.
- Created a conservative AI-restored and upscaled preview that reduces JPEG artifacts and grain while preserving the existing portrait composition and recognizable likeness.
- Saved the project asset as `output/homepage/rebekah-story-ai-enhanced-v1.png`; the original WordPress image remains unchanged.
- Created `third-mockup-v2.3.0.html` so the approved homepage file remains available for rollback. The restored portrait is used in the large Meet Rebekah placement and matching social-preview placements.
- Added `homepage-image-v2.3.0.css` to show the large restored portrait without the previous aggressive crop. No other homepage feedback, including the mobile shipping-banner issue, was addressed in this narrowly scoped revision.
- Updated the current condensed `story-mockup-v2.2.0.html` to use the same restored portrait in its hero for consistency.
- Pending client approval: show the original and restored portraits side by side and disclose that the larger image is an AI-restored version of the existing low-resolution source. Obtain approval of the restored likeness before live WordPress use.

## 2026-07-29 — Lake Orion community-photo crop correction v2.5.0

- Created `lake-orion-location-mockup-v2.5.0.html` as a separate Page 7 feedback revision; the existing v2.0.0 Lake Orion mockup remains available for comparison and rollback.
- Kept all three existing photos, cards, copy, links, and the rest of the Lake Orion page unchanged.
- Added the narrowly scoped `lake-orion-photo-crops-v2.5.0.css` override for the “Local Learning and Real Community Connection” section only.
- Corrected the initial crop approach after review: removed all custom photo-frame heights so Lake Orion retains the exact shared event-card dimensions used by the other location pages.
- Kept only individual `object-position` adjustments so people and faces remain visible within the standard shared crop.
- Pending: visually verify all three focal positions at representative desktop, tablet, and phone widths in the normal client-preview environment.
- Client-facing next step: confirm that the same community photos now show people’s heads comfortably on the client’s desktop.

## 2026-07-29 — In-Store Products featured-brand correction v2.6.0

- Confirmed in the canonical planning sheet that client “Page 10” refers to the In-Store Products page.
- Created `in-store-products-mockup-v2.6.0.html` as a separate revision; the existing v1.0.0 mockup remains unchanged for comparison and rollback.
- Under “Featured Brands You May Find,” replaced the Metagenics tile and its “Professional nutrition” descriptor with “Spencer’s Raw Honey” and the neutral descriptor “Raw honey.”
- No other featured brands, product categories, copy, links, layout, styling, or page content were changed.
- Client-facing next step: confirm the Spencer’s Raw Honey spelling and presentation in the featured-brand strip.

## 2026-07-29 — Practitioner roster correction v2.7.0

- Confirmed in the canonical planning sheet that client “Page 11” refers to the Practitioners directory.
- Created `practitioners-mockup-v2.7.0.html` as a separate revision; the existing v1.0.0 mockup remains unchanged for comparison and rollback.
- Removed the complete directory records for IV Lounge and Troy Farwell, including their cards, contact details, and expanded descriptions.
- Interpreted the client’s “Kim McCabe” request as the existing “Kimberly Cabe, FNP-BC” listing and removed that complete record as well. This name mismatch should be acknowledged in the client response if confirmation is needed.
- Preserved the approved layout, filters, interactions, disclaimer, styling, and all remaining practitioner records without redesigning the page.
- The directory now contains 25 practitioner/practice records instead of 28.
- Client-facing next step: confirm that “Kim McCabe” referred to the “Kimberly Cabe, FNP-BC” listing shown in the prior mockup.

## 2026-07-29 — Proposed Meet Our Team approach

- The client expressed interest in featuring or linking to individual team members.
- Agreed recommendation: create one concise, dedicated “Meet Our Team” page and link to it from Our Story and potentially the main navigation, rather than lengthening every location page.
- Keep employee/team profiles separate from the independent Practitioners directory.
- No page or design changes have been made for this request.
- Add a clarification request to the consolidated client email asking for the current roster, preferred names and titles, store assignments, approved individual photos, short bios or permission to draft them, public-feature consent, and any approved profile/contact links.
- Pending client decision: confirm the dedicated-page approach and provide the required team information and assets before design work begins.

## 2026-07-29 — Client review hub and version timeline

- Updated the GitHub Pages review hub so all 20 page cards open the current client-feedback version where a revision exists.
- Standardized every page and reusable-template badge on the review hub to “Awaiting approval” for the current feedback round.
- Added a “Version Journey” section showing nine revised pages from their original or approved baseline through intermediate versions to the current revision.
- Preserved direct access to prior mockups so the client can compare decisions and understand how each page evolved.
- Added a direct link from the review hub to the canonical website inventory spreadsheet.
- Added a new `Client Review Timeline` tab to the canonical Google Sheet with all 20 pages, baseline/current versions, clickable old/new mockups, version journeys, and approval statuses.
- Updated `Page Inventory!E5:E24` so every tracked page is marked `Awaiting client approval`.
- Visually verified the new Sheet tab at normal zoom; headers, links, version history, filters, frozen rows, widths, and statuses are readable and consistent with the workbook.
- Client-facing next step: use the updated review hub as the single starting point and approve or comment on each current page while referring to the timeline when comparison is helpful.

## 2026-07-29 — Review timeline visibility correction

- Clarified that the version history is an internal project-management reference, not client-facing content at this stage.
- Removed the complete “Version Journey” section and the Google Sheet link from the public GitHub Pages review hub.
- Kept the current page list and “Awaiting approval” badges on the client-facing hub.
- Preserved the `Client Review Timeline` tab and all baseline/current links in the private canonical Google Sheet for internal reference.

## 2026-07-29 — Location quick-info bar consistency v2.8.0

- Created new v2.8.0 revisions for Grand Blanc, Clarkston, and Lake Orion while preserving every prior mockup for comparison and rollback.
- Moved only the quick-info bar upward so it straddles the dark-green hero and cream content boundary in the same way as the approved Lapeer page.
- Preserved each bar's dimensions, four-column layout, wording, responsive behavior, and all other page content and styling.
- Kept Lapeer v2.0.0 unchanged because it is the positioning reference.
- Updated the client review hub to open the three new v2.8.0 revisions; all remain marked awaiting client approval.
- Pending client-facing next step: review the four location pages together and confirm the quick-info bar placement is consistent.

## 2026-07-29 — Locations shortcut removal v2.9.0 and Rebekah image audit

- Created `locations-mockup-v2.9.0.html` while preserving v2.1.0 for comparison and rollback.
- Removed only the “All locations / Lapeer / Grand Blanc / Clarkston / Lake Orion” shortcut-button row; the four store cards and all surrounding content remain unchanged.
- Confirmed the prominent Rebekah portrait on Home v2.3.0 is the AI-enhanced asset `output/homepage/rebekah-story-ai-enhanced-v1.png`.
- Confirmed the current Our Story v2.2.0 hero also uses that same enhanced asset.
- Older archived mockups intentionally retain the original remote image, and the small social-feed placeholder on the current Home mockup still references the original website image.
- Updated the client review hub to open Locations v2.9.0, still marked awaiting client approval.

## 2026-07-29 — Homepage shipping collage correction v2.10.0

- Acknowledged that the v2.3.0 “We Ship Nationwide” photo collage looked crowded and visually broken on common desktop and laptop widths.
- Identified the cause as three fixed-size, absolutely positioned circular photos competing for a narrowing right-hand column.
- Created `third-mockup-v2.10.0.html` and `homepage-feedback-v2.10.0.css`, preserving Home v2.3.0 for comparison and rollback.
- Kept the original spacious editorial collage on displays wider than 1400px.
- At 1400px and below, arranged the same three photos into a clean, equal three-column row so they cannot collide or overlap.
- Preserved all section wording, buttons, colors, images, mobile containment fixes, and unrelated homepage content.
- Updated the client review hub to open Home v2.10.0, still marked awaiting client approval.

## 2026-07-29 — Location section-spacing correction v2.11.0

- Confirmed the large blank areas identified on Lapeer, Grand Blanc, Clarkston, and Lake Orion came from adjacent sections each contributing the full 8rem vertical padding.
- Created new v2.11.0 revisions for all four location pages while preserving every earlier version for comparison and rollback.
- Reduced only the two repeated transitions identified by the client: the intro-to-products transition and the events-to-community transition.
- Changed those adjoining edges from 8rem per side to 4rem per side on desktop and 3rem per side on phones.
- Preserved normal spacing inside the sections, card layouts, typography, photos, quick-info bar positioning, wording, and all other page styling.
- Updated the client review hub to open the four v2.11.0 location revisions; all remain awaiting client approval.

## 2026-07-29 — Client feedback response prepared

- Prepared a plain-language client email summarizing the completed feedback revisions, WordPress-stage items, and the remaining asset or clarification requests.
- The response directs the client to the current mockup hub and explains that the shared header/footer and directly hosted homepage video will be finalized during WordPress assembly.
- The response asks the client to identify the exact Page 2 replacement images, provide the approved Clarkston team photo, confirm the Kim McCabe/Kimberly Cabe name match, and supply the roster and assets needed for a future Meet Our Team page.
- The response explains that the attached AI-enhanced Rebekah portrait is the strongest practical improvement available from the current source image and that a sharper result would require a new high-resolution photograph.
- Emailed the completed client-response draft to Todd's authenticated Gmail account with the AI-enhanced Rebekah portrait attached, ready for his review and forwarding.

## 2026-07-31 — Homepage client-requested revision v3.1.0

- Created `third-mockup-v3.1.0.html` as the Page 1 client-feedback revision while preserving v2.10.0 and all earlier homepage versions for comparison and rollback.
- Resolved the nonfunctional “Explore Departments” pathway: the review mockup now opens the approved In-Store Products page, and the planned WordPress destination is `/in-store-products/`.
- After direct review, removed the rejected grid/mosaic treatment and restored the approved `third-mockup.html` shipping composition: a centered 1180px panel with the original three intentionally overlapping circular photos and the original image sizes and positions.
- Repositioned and slightly reduced the “Rooted in Care” badge at phone and tablet widths so it sits over Rebekah's lower shoulder rather than her face.
- Verified the corrected desktop section against the approved live baseline at 1280px; the panel and all three photo rectangles match the approved mockup. Rechecked phone behavior at 390px with no horizontal overflow, the shoulder-level badge placement retained, and the departments link still resolving to the current In-Store Products mockup.
- Updated the private client review hub to open v3.1.0 and updated `Client Review Timeline!E5:G5` in the canonical Google Sheet with the new version journey, current version, and mockup link.
- Pending: client review and approval of homepage v3.1.0. The `/in-store-products/` production URL will be applied during WordPress assembly.
- Client-facing next step: confirm that the desktop shipping section now matches the approved overlapping-circle design, along with the mobile badge placement and In-Store Products destination for “Explore Departments.”
- Local-browser parity correction: identified that normal browsers block the homepage's `fetch("second-mockup.html")` call when v3.1.0 is opened directly with `file://`, causing six dynamically mounted sections to disappear while the in-app preview still displayed them.
- Replaced that runtime fetch in v3.1.0 with the generated local bundle `combined-original-sections-source-v3.1.0.js`; added `.tools/build-homepage-source-bundle.mjs` so the bundle can be regenerated from `second-mockup.html` when needed.
- Verified all six mounts populate from the bundle with no console warnings or errors: pathway, nationwide shipping, classes/events, Wellness Source Blog, newsletter, and practitioner brands. The approved shipping composition, In-Store Products link, and responsive badge correction remain intact.
- After deeper asset inspection, confirmed the three shipping PNGs were exported from a single overlapping composition: the store photo contains a precisely aligned slice of the Rebekah image, the Rebekah photo contains a precisely aligned slice of the capsule image, and the capsule photo contains a precisely aligned slice of the product display. The attempted independent-circle crop exposed those baked edge pieces and was rejected.

## 2026-07-31 — Homepage shipping collage correction v3.1.1

- Created `third-mockup-v3.1.1.html` as the next Page 1 revision and preserved `third-mockup-v3.1.0.html` unchanged for version history.
- Restored the approved three original shipping PNGs and their original overlap geometry without inner frames, zoom crops, masking, pixel reconstruction, or replacement imagery.
- Kept the original hover lift-and-enlarge interaction. The entire collage now scales responsively as one 1:1 composition so the three exported edge pieces remain aligned on desktop, iPad Air, and iPhone layouts.
- Capped the desktop collage at 388px so the 2.5% hover enlargement still renders every source below its native resolution: store 249.7px maximum from a 250px source, Rebekah 268.2px from 288px, and capsules 203.5px from 224px.
- Verified at 1280px, 820px, and 390px: no exposed rectangular fragments, no image upscaling, no horizontal overflow, and no `.shipping-photo-frame` crop wrappers.
- Updated the private review hub and `Client Review Timeline!E5:G5` to identify v3.1.1 as the current Page 1 version.
- Pending: client visual approval of Page 1 v3.1.1; publish to GitHub Pages only when requested.

## 2026-07-31 — Grand Blanc mobile caption correction v3.1.2

- Created `grand-blanc-location-mockup-v3.1.2.html` as the next numbered client-feedback revision while preserving v2.11.0.
- Corrected the phone layout where the long location caption rose into the rounded top of Jackelyn's photo and was clipped by the arched mask.
- At widths up to 600px, Jackelyn's uncropped portrait now sits above a separate dark caption panel inside the same card; desktop retains the approved photo-overlay presentation.
- Verified at 1280px, 390px, and 320px: the complete headline and supporting sentence remain inside the card, there is no horizontal overflow, and the existing desktop structure is unchanged.
- Updated the private review hub and `Client Review Timeline!E9:G9` to identify v3.1.2 as the current Grand Blanc version.
- Pending: client visual approval of the Grand Blanc v3.1.2 mobile correction; publish to GitHub Pages only when requested.

## 2026-07-31 — Grand Blanc portrait-height correction v3.1.3

- Created `grand-blanc-location-mockup-v3.1.3.html` as the next revision and preserved v3.1.2.
- Increased the desktop story-photo frame from the previous near-square crop to a 4:5 portrait frame and anchored the source image to the top, revealing Jackelyn's complete head and the original space above it.
- Preserved the approved desktop caption as a bottom overlay and retained the separate mobile caption panel introduced in v3.1.2.
- Verified at 1920px and 390px: the complete head is visible, caption text remains contained, and there is no horizontal overflow or text-encoding damage.
- Updated the private review hub and `Client Review Timeline!E9:G9` to identify v3.1.3 as the current Grand Blanc version.
- Pending: client visual approval of the Grand Blanc v3.1.3 portrait framing; publish to GitHub Pages only when requested.

## 2026-07-31 — Grand Blanc v3.1.3 full-page mobile audit

- Confirmed all local stylesheets and images referenced by v3.1.3 exist and the viewport meta tag is present.
- Confirmed the page's responsive rules stack all split layouts below 900px and switch the quick-info bar, product cards, events, facts, visit details and signup form to one column below 600px; no fixed minimum page width was found.
- The currently open Chrome tab is a normal desktop-width `file://` page, so the phone layout will not activate unless Chrome's CSS viewport is 600px or narrower. Automated inspection of the local `file://` tab is blocked by Chrome's browser-control security policy.
- No design change or version bump was made from this audit. Pending: obtain a screenshot and device/viewport details if the full page still fails at a true phone-width viewport, then reproduce and correct the specific failure.

## 2026-07-31 — Grand Blanc v3.1.3 mobile approval

- Displayed the Grand Blanc page in the in-app browser at a 393 × 852 iPhone viewport and positioned it at Jackelyn's portrait section for direct review.
- Confirmed the mobile treatment keeps Jackelyn's portrait unobstructed with the complete caption in the separate dark panel beneath the image.
- The user approved the iPhone presentation. Grand Blanc v3.1.3 is the accepted current revision; no further mobile changes are pending.

## 2026-07-31 — Grand Blanc unobstructed portrait correction v3.1.4

- The user confirmed the iPhone treatment looked good but clarified that the desktop caption still covered too much of Jackelyn's portrait.
- Created `grand-blanc-location-mockup-v3.1.4.html` and `grand-blanc-feedback-v3.1.4.css`, preserving v3.1.3 for version history.
- Removed the desktop photo overlay and placed the complete location caption in a dedicated dark panel beneath the portrait, matching the already approved mobile treatment.
- Verified at 1440px and a 393 × 852 iPhone viewport: Jackelyn's face and portrait remain unobstructed, the complete caption is readable below the image, and there is no horizontal overflow.
- Updated the private review hub and `Client Review Timeline!E9:G9` to identify v3.1.4 as the current Grand Blanc revision.
- Pending: client visual approval of the corrected desktop treatment; the iPhone layout remains approved.

## 2026-07-31 — Grand Blanc v3.1.4 solution accepted

- The user reviewed the unobstructed desktop portrait treatment and confirmed it is a good solution.
- Grand Blanc v3.1.4 is the accepted internal revision for this client-requested correction; both desktop and iPhone treatments are complete.
- Client-facing next step: include v3.1.4 in the next client review round. The page remains marked as awaiting the client's final approval and has not been published to GitHub Pages or WordPress.

## 2026-07-31 — Clarkston team-photo replacement v3.1.5

- Addressed the Page 6 client request to remove the blurry storefront image from the middle of the Clarkston page.
- Located the client-supplied `Clarkston team photo for Clarkston pagepg.jpg` in the shared drive, copied the original 2048 × 1553 source into `output/clarkston-location/clarkston-team-client.jpg`, and used it without enlarging or degrading it.
- Created `clarkston-location-mockup-v3.1.5.html`, preserving v2.11.0 for version history, and replaced only the middle Personal Guidance section image and its alt text.
- Verified at 1440px, 820px and a 393 × 852 iPhone viewport: all three team members remain visible, the 4:3 crop fits the existing approved layout, and there is no horizontal overflow or image upscaling.
- Updated the private review hub and `Client Review Timeline!E10:G10` to identify v3.1.5 as the current Clarkston revision.
- Pending: client visual approval of the Clarkston team-photo replacement; publish to GitHub Pages or WordPress only when requested.

## 2026-07-31 — Clarkston v3.1.5 solution accepted

- The user reviewed the client-supplied team photo in the middle Personal Guidance section and confirmed the replacement looks good.
- Clarkston v3.1.5 is the accepted internal revision for this client-requested correction.
- Client-facing next step: include v3.1.5 in the next client review round; the page remains awaiting the client's final approval and has not been published to GitHub Pages or WordPress.

## 2026-07-31 — Classes & Events community invitation v3.1.6

- Addressed the Page 8 client request for an invitation to educators, practitioners, and local makers, growers, and bakers who may want to host something in a Rebekah's store.
- Created `classes-events-mockup-v3.1.6.html` and `classes-events-v3.1.6.css`, preserving the v1.0.0 archive and shared event-detail styling for version history.
- Added a distinct community-host panel after the existing editorial section inviting ideas for classes, discussions, pop-ups, and small events.
- Kept the wording appropriately noncommittal: opportunities are considered according to store space, schedule, and community fit. The button routes to the existing Contact Us mockup instead of inventing registration, ticketing, or a new submission workflow.
- Verified the panel at 1440px, 820px, and a 393 × 852 iPhone viewport with no horizontal overflow; the responsive layout and contact link work as intended.
- Updated the private review hub and `Client Review Timeline!E12:G12` to identify v3.1.6 as the current Classes & Events revision.
- Pending: client visual and wording approval; confirm the final WordPress Contact Us URL during assembly.

## 2026-07-31 — Classes & Events CTA spacing correction v3.1.7

- The user approved the new community-host invitation but identified that the purple Stay in the Loop card began flush against the top edge of its white section.
- Created `classes-events-mockup-v3.1.7.html` and `classes-events-v3.1.7.css`, preserving v3.1.6 for version history.
- Added responsive top breathing room before the purple CTA: approximately 58px at 1440px and 40px at the iPhone viewport, while leaving the green invitation panel and its wording unchanged.
- Verified at 1440px and a 393 × 852 iPhone viewport: the purple card now sits clearly inside its section, the rounded top corners read correctly, and there is no horizontal overflow.
- Updated the private review hub and `Client Review Timeline!E12:G12` to identify v3.1.7 as the current Classes & Events revision.
- Pending: client visual and wording approval of Page 8; confirm the final WordPress Contact Us URL during assembly.

## 2026-07-31 — In-Store Products authentic category photography v3.1.8

- Addressed the client request to replace the generic “What Are You Looking For?” category images with photography that feels like Rebekah's.
- Created `in-store-products-mockup-v3.1.8.html`, preserving v2.6.0 for version history and leaving the already approved page layout and copy unchanged.
- Replaced all eight stock category images with original Rebekah's store, private-label, product-education, local-maker and Michigan-grown product photos stored locally in `output/in-store-products-v3.1.8/`.
- Kept every source at or above its rendered size to avoid blurry enlargement; verified all eight files load at their natural dimensions.
- Verified the category grid at 1440px, 820px and a 393 × 852 iPhone viewport: four, two and one columns respectively, with no page-level horizontal overflow.
- Updated the private review hub and `Client Review Timeline!E14:G14` to identify v3.1.8 as the current In-Store Products revision.
- Pending: user/client visual approval of the new authentic image selections; publish to GitHub Pages or WordPress only when requested.

## 2026-07-31 — In-Store Products v3.1.8 solution accepted

- The user compared v3.1.8 directly with the previous v2.6.0 category-photo treatment and approved the new authentic Rebekah's imagery.
- In-Store Products v3.1.8 is the accepted internal revision for this client-requested correction.
- Client-facing next step: include v3.1.8 in the next client review round; the page remains awaiting the client's final approval and has not been published to GitHub Pages or WordPress.

## 2026-07-31 — Lapeer major-holiday hours notice v3.1.9

- Began the client-requested store-hours update with the Lapeer location page, keeping the sitewide change separated into page-specific review versions.
- Created `lapeer-location-mockup-v3.1.9.html`, preserving v2.11.0 for version history.
- Added the exact client-requested wording, “Closed on all major holidays.”, directly beneath the hours grid in the Plan Your Visit section.
- Styled the notice as a compact gold-accented pill that is easy to spot without changing the approved hours, location content, map or page structure.
- Verified at 1440px, 820px and a 393 × 852 iPhone viewport: the complete sentence is visible, the hours remain readable, and there is no horizontal overflow or browser-console error.
- Updated the private review hub and `Client Review Timeline!E8:G8` to identify v3.1.9 as the current Lapeer revision.
- Pending: user/client visual approval of the Lapeer notice; after approval, repeat the same treatment on Grand Blanc, Clarkston and Lake Orion with the next sequential version numbers.

## 2026-07-31 — Lapeer v3.1.9 solution accepted

- The user reviewed and approved the major-holiday closure notice in the Lapeer Plan Your Visit section.
- Lapeer v3.1.9 is the accepted internal treatment and will serve as the styling pattern for the remaining Grand Blanc, Clarkston and Lake Orion store-hours revisions.
- Client-facing next step: include v3.1.9 in the next client review round; the page remains awaiting the client's final approval and has not been published to GitHub Pages or WordPress.

## 2026-07-31 — Grand Blanc major-holiday hours notice v3.1.10

- Applied the user-approved Lapeer holiday-hours treatment to the current accepted Grand Blanc v3.1.4 page.
- Created `grand-blanc-location-mockup-v3.1.10.html` and `grand-blanc-feedback-v3.1.10.css`, preserving v3.1.4 and its unobstructed portrait solution for version history.
- Added the exact wording, “Closed on all major holidays.”, beneath the hours grid in the Plan Your Visit section without changing the approved hours, map, portrait treatment or location content.
- Verified at 1440px, 820px and a 393 × 852 iPhone viewport: the notice remains complete and readable, the hours layout responds correctly, and there is no horizontal overflow or browser-console error.
- Updated the private review hub and `Client Review Timeline!E9:G9` to identify v3.1.10 as the current Grand Blanc revision.
- Pending: user/client visual approval of the Grand Blanc notice; after approval, continue with Clarkston v3.1.11 and Lake Orion v3.1.12.

## 2026-07-31 — New Our Team page mockup v1.0.0

- Designed the new `Our Team` page as `our-team-mockup-v1.0.0.html`, following the approved Homepage v1.21 / Our Story v1.1.6 palette, Fraunces and DM Sans typography, photo shapes, spacing, buttons, header, and footer system.
- Confirmed that the canonical planning sheet does not yet contain an Our Team row; treated the client's new request as a user-authorized addition rather than changing the existing 20-page inventory.
- Located the correct current shared-drive source folder and included all 10 submitted team profiles and embedded portraits: Angela Basner, Pamela Stuewer, Ashlyn, Todd Cochell, Jackelyn, Debbie, Brian, Margaret, Catie, and Mikayla.
- Organized the page into four clearly labeled store chapters (Lapeer, Grand Blanc, Clarkston, and Lake Orion). Every person remains visible on the page; no filter hides staff members.
- Used accessible native `details` disclosures for the complete bios so visitors first see a concise, photo-forward team directory and can expand any story without navigating away.
- Lightly edited submitted text for web clarity, punctuation, consistency, and cautious wellness language while preserving each person's stated role, background, goals, and voice. Added a clear education-not-medical-advice boundary near the end of the page.
- Preserved Catie's smaller submitted portrait at its native 295 × 580 dimensions inside an intentional branded frame rather than enlarging it and introducing visible blur.
- Added page-specific title/meta copy, descriptive image alt text, internal links, accessible heading structure, mobile navigation, and AboutPage/ItemList/Person structured data for all 10 profiles.
- Verified at 1440px desktop, 820px tablet, and 393 × 852 mobile: all 10 images load, all 10 cards render, the bio disclosures and mobile menu work, there is no horizontal overflow, and the browser console reports no errors or warnings.
- Pending before production: client confirmation of final staff name spelling, current role, tenure, education/certification wording, and whether any additional submitted team members should be added; then add this page to the canonical inventory/navigation and assemble it in WordPress when explicitly approved.

## 2026-07-31 — Our Team hero portrait correction

- Addressed the user's first review comments that the overlapping hero collage cropped or covered most of the featured faces and that the `Meet the team` button was unnecessary.
- Replaced the overlapping collage with a non-overlapping 2 × 2 editorial portrait grid. Jackelyn, Margaret, Brian, and Ashlyn now each have a dedicated unobstructed image frame and visible name label.
- Moved the `10 team stories · one shared purpose` message into its own full-width strip beneath the four portraits so it no longer covers any person.
- Removed the `Meet the team` hero button and retained one clear primary `Find your store` action.
- Verified the revised hero at the exact 928 × 720 commented viewport, 1440 × 900 desktop, and 393 × 852 mobile: all four faces remain visible, all labels fit, no page-level overflow appears, and the browser console reports no errors or warnings.

## 2026-07-31 — Our Team mockup chrome removal

- Removed the global site header and footer from `our-team-mockup-v1.0.0.html` at the client's direction.
- The mockup now opens directly on the team hero and ends with the source/accuracy note; page-specific calls to action remain in place.

## 2026-07-31 — Our Team hero switched to store teams

- Replaced the four individual portraits in the hero with authentic team photos representing Lapeer, Grand Blanc, Clarkston, and Lake Orion.
- Relabeled the four hero panels by store so the introduction now communicates “four stores, one team,” while individual staff portraits appear only with their bios below.
- Preserved the approved non-overlapping 2 × 2 composition and used crop-safe image treatment for the wider team photos so edge faces remain visible.

## 2026-07-31 — Clarkston and Lake Orion major-holiday notices v3.1.11–v3.1.12

- Completed the remaining client-requested store-hours revisions using the Lapeer treatment the user approved.
- Created `clarkston-location-mockup-v3.1.11.html` and `clarkston-feedback-v3.1.11.css`, preserving the accepted v3.1.5 Clarkston team-photo replacement.
- Created `lake-orion-location-mockup-v3.1.12.html` and `lake-orion-feedback-v3.1.12.css`, preserving the current Lake Orion layout and imagery.
- Added the exact wording, “Closed on all major holidays.”, beneath each Plan Your Visit hours grid without changing the listed hours, maps, directions, phone numbers or structured data.
- Verified both pages at 1440px, 820px and a 393 × 852 iPhone viewport: the full notice remains visible, the controls fit, there is no horizontal overflow, and the browser console reports no errors.
- Updated the private review hub and `Client Review Timeline!E10:G11` to identify Clarkston v3.1.11 and Lake Orion v3.1.12 as current.
- All four store pages now include the holiday notice. Pending: client approval and publication only when explicitly requested.

## 2026-07-31 — New client review hub for revision round 3.1

- Created a separate versioned review hub at `client-review-v3.1/index.html` so the previously published root review hub remains available as a rollback/reference point.
- The new hub contains the latest versions from the current client-feedback round, including Homepage v3.1.1, the four store-hours revisions through Lake Orion v3.1.12, Classes & Events v3.1.7 and In-Store Products v3.1.8.
- Added `Our Team` as page 21 and linked it to the current `our-team-mockup-v1.0.0.html` design with all four store-team sections and 10 submitted staff profiles.
- Added a clear `Open the previous review hub` link from the new hub back to the original root URL.
- Verified the new hub at 1440px, 820px and 393px: it renders as three, two and one card columns with no horizontal overflow. Verified the Team page at 393px with all 15 images and all 10 team cards loaded.
- Client-facing next step: use the new versioned hub for the next review round while retaining the original root hub for comparison.

## 2026-07-31 — Header and footer component study v1.0

- Separated the header/navigation and footer from the current homepage mockup into `header-footer-mockup-v1.0.html` for focused review.
- Kept the Homepage v1.21 visual treatment and existing menu/footer content as the starting point; this is explicitly an exploratory component study, not a final or production implementation.
- Added a visible `v1.0 · Exploratory` marker and established the requested future version sequence (`v1.1`, `v1.2`, and so on) without changing the homepage mockup itself.
- Kept the mockup self-contained and used the existing verified local Rebekah's logo for dependable previewing.
- Verified the standalone component at 1440px desktop, 820px tablet, and 390px mobile. The desktop navigation, responsive menu, footer reflow, logo assets, and mobile menu interaction work without horizontal overflow or console warnings/errors.
- Pending review: decide what visual/content changes should become v1.1. Before production, verify every navigation destination and expand/adjust the footer to the canonical global scope as approved, including accurate location, contact, social, policy, and legal links.

## 2026-07-31 — Header and footer component study v1.1

- Created `header-footer-mockup-v1.1.html` as an exploratory comparison while preserving v1.0 unchanged.
- Preserved both client-liked desktop buttons at the top right: `Call To Order` and `Stay Connected`.
- Kept the approved header styling and added a `Visit Our Stores` dropdown for the locations hub plus all four stores, a direct `In-Store Products` route, and an `About` dropdown for Our Story, Our Team, Practitioners and Contact Us.
- Expanded the footer into four concise groups—Explore, Locations, About and Policies—while retaining the white background, verified logo, tagline and restrained visual treatment from v1.0.
- Corrected the footer information architecture so Refund & Returns and Terms & Conditions are separate destinations; added Disclaimer, contact, Instagram, TikTok and Sitemap positions for complete future global navigation.
- On mobile, both header actions remain inside the opened menu and the footer groups collapse into native accordions.
- Verified at 1440px desktop, 820px tablet and 390px mobile: both desktop buttons are visible, dropdowns and accordions work, all assets load, and there is no page-level horizontal overflow or browser-console warning/error.
- Pending review: choose between v1.0 and v1.1 organization and confirm the final Instagram/TikTok URLs plus all production navigation destinations before WordPress implementation.

## 2026-07-31 — Header and footer component study v1.2

- Created `header-footer-mockup-v1.2.html`, preserving v1.0 and v1.1 for comparison.
- Applied the user's browser feedback by moving `About` to the first position in the desktop and mobile navigation; its dropdown continues to contain Our Story, Our Team, Practitioners and Contact Us.
- Preserved both client-liked top-right desktop buttons—`Call To Order` and `Stay Connected`—and their corresponding mobile-menu actions.
- Replaced the footer's plain Instagram and TikTok text links with two compact, accessible circular SVG icon buttons. Kept `Call To Order` as a readable text link beside them.
- Limited the social treatment to Instagram and TikTok because those are the two networks currently identified in the canonical global-component scope; final account URLs remain pending.
- Verified at the commented desktop layout and a narrow mobile layout: About is first, both top-right/mobile action buttons remain visible, both dropdowns work, both social icons render at 38 × 38 pixels with accessible names, footer accordions remain responsive, and there are no console warnings/errors or page-level horizontal overflow.
- Pending review: approve the v1.2 order/icon treatment and provide or verify the final Instagram and TikTok profile URLs before production implementation.

## 2026-07-31 — Header and footer component study v1.3

- Created `header-footer-mockup-v1.3.html`, preserving v1.0–v1.2 for comparison.
- Addressed the user's compact-computer feedback by delaying the hamburger transition from 1240px to 1040px, allowing the full navigation and both top-right action buttons to remain visible across common small-laptop layouts.
- Added compact-desktop spacing tiers that reduce only header padding, navigation gaps and button padding. Preserved the existing liked logo sizing from v1.2; the logo was not reduced for this revision.
- Addressed the mobile-menu feedback by moving each submenu chevron directly beside its label instead of aligning it at the far-right edge of the menu row.
- Preserved About as the first navigation item, both Call To Order and Stay Connected actions, and the Instagram/TikTok footer icons introduced in v1.2.
- Verified a 1156px rendered compact-laptop viewport: the full navigation, 190px logo and both action buttons remain visible with no overflow. Verified a 929px rendered hamburger layout: submenu arrows sit 10px from their labels, both mobile actions remain visible, and the About submenu opens correctly.
- Browser console reports no warnings/errors. Pending: user review of the later breakpoint and compact mobile submenu treatment.

## 2026-07-31 - Header and footer component study v1.4

- Created `header-footer-mockup-v1.4.html`, preserving v1.0-v1.3 as separate comparison versions.
- Addressed all six comments on v1.3 together: restored a 230px desktop/laptop logo, increased header navigation to a readable 13-14px range, and removed the large empty area between the logo and navigation.
- Changed the header's flexible spacing so the navigation begins 18-20px after the logo while any remaining room sits between the navigation and the two action buttons.
- Preserved the client-liked `Call To Order` and `Stay Connected` buttons, About-first menu order, store and About dropdowns, and Instagram/TikTok footer icons.
- Moved the hamburger breakpoint to 1120px so the complete header remains visible at the tested 1156px rendered small-laptop width while retaining a comfortable fallback below that width.
- Retained v1.3's close mobile submenu arrows. Verified the open hamburger at a 945px rendered width: arrows use flex-start alignment with an 8px gap and 2px margin, both mobile actions remain present, and there is no horizontal overflow.
- Verified phone behavior at a 534px rendered width: the larger mobile logo, hamburger, collapsed footer accordions and page width all behave correctly.
- Pending review: client preference on the v1.4 logo/navigation proportions and final Instagram/TikTok profile URLs before production implementation.

## 2026-07-31 - Header and footer component study v1.5

- Created `header-footer-mockup-v1.5.html`, preserving v1.0-v1.4 as separate comparison versions.
- Removed the footer `Call To Order` link because the sticky header already keeps that action continuously available.
- Kept the Instagram and TikTok icon buttons visible in the footer and preserved every Explore, Locations, About, Policies, contact, social and legal destination required by the canonical global scope.
- Converted all four footer link groups to native accordions at 1000px and below, covering both tablet and phone layouts. Desktop continues to display all four groups openly in the established five-column footer.
- Reworked the tablet footer intro into a compact logo/tagline/social row followed by four 50px collapsed accordion rows. This reduced the tested tablet footer to approximately 391px high from the much taller v1.4 presentation.
- On phones, the logo, tagline and social icons center above the same four collapsed accordions; opening an accordion reveals only its relevant links.
- Verified at rendered 902px tablet, 534px phone and 1423px desktop widths: accordion state changes correctly, there is no horizontal overflow, both logo assets load, the v1.4 header proportions remain intact, and the browser console reports no warnings or errors.
- Pending review: client approval of the v1.5 compact footer and final Instagram/TikTok profile URLs before production implementation.

## 2026-07-31 - Header and footer component study v1.6

- Created `header-footer-mockup-v1.6.html`, preserving v1.0-v1.5 for comparison.
- Corrected the phone-menu alignment reported by the user: In-Store Products, Classes & Events and Wellness Source Blog were plain links with a 48px minimum height but no vertical alignment rule, while About and Visit Our Stores were flex buttons.
- Applied the same `display: flex` and `align-items: center` treatment to all five mobile navigation rows, without changing their labels, routes, order, heights or submenu behavior.
- Created `header-footer-mockup-v1.6-phone-preview.html` so the corrected menu can be reviewed interactively inside the same 393px phone frame.
- Verified at true phone width with the menu open: all five navigation rows render at 47.99px high and report flex/center alignment; the header actions, footer social icons and footer accordions remain unchanged.
- Pending review: client approval of the v1.6 mobile-row alignment and final Instagram/TikTok profile URLs before production implementation.

## 2026-07-31 - Header and footer component study v1.7

- Created `header-footer-mockup-v1.7.html` and a matching `header-footer-mockup-v1.7-tablet-preview.html`, preserving all earlier comparison versions.
- Changed only the 721-1000px tablet footer: Explore, Locations, About and Policies now appear as four equal buttons in one horizontal row.
- All four tablet sections start collapsed. Selecting one opens a shared full-width two-column link panel beneath the row; selecting another automatically closes the previous section, and selecting the active section closes it again.
- Reduced the tested tablet footer from approximately 391px in v1.6 to 229px in its default collapsed state, a reduction of about 162px. An open section measures approximately 321px and remains shorter than the former always-expanded footer.
- Preserved the phone footer exactly as stacked native accordions with all four groups closed initially; the tablet button row is hidden at phone widths.
- Preserved the desktop footer exactly as the established open five-column grid; the tablet button row is hidden at desktop widths.
- Verified the four tablet buttons share one row, only one shared panel opens at a time, all intended links remain available, both regression layouts retain their prior structures, there is no horizontal overflow, and the browser console reports no warnings or errors.
- Pending review: client approval of the v1.7 tablet-only footer treatment and final Instagram/TikTok profile URLs before production implementation.

## 2026-07-31 - Header and footer component study v1.8

- Created `header-footer-mockup-v1.8.html` and `header-footer-mockup-v1.8-tablet-preview.html`, preserving v1.7 and all earlier comparison versions.
- Addressed the user's objection to the tablet footer's rounded button appearance while preserving the requested click-to-expand interaction.
- Restyled the four tablet-only controls as a clean horizontal footer menu with transparent backgrounds, square edges, subtle vertical separators and small dropdown chevrons.
- The selected section now uses a thin green underline and rotated chevron instead of a filled button state. Its links continue to open in the shared full-width panel beneath the row, with only one section open at a time.
- The collapsed tablet footer now measures approximately 216px at the tested 902px rendered width.
- Confirmed that phone retains the stacked native accordions and centered intro, while desktop retains the open five-column grid. The tablet menu is hidden in both layouts.
- Verified dropdown interaction, visible link sets, chevron rotation, active underline, zero-radius transparent controls, page width and browser console health with no warnings or errors.
- Pending review: client approval of the v1.8 tablet dropdown-menu appearance and final Instagram/TikTok profile URLs before production implementation.

## 2026-07-31 - Header and footer component study v1.9

- Created `header-footer-mockup-v1.9.html` and `header-footer-mockup-v1.9-tablet-preview.html`, preserving all earlier comparison versions.
- Removed every line surrounding the tablet footer dropdown labels: the outer top/bottom rules, row divider, vertical separators, shared-panel divider and active underline.
- Kept Explore, Locations, About and Policies as four clean, evenly spaced, clickable text labels with dropdown chevrons.
- The active tablet section is now indicated only by leaf-green text and the upward-facing chevron; its links still open in the shared panel below, with only one section open at a time.
- Verified all relevant computed borders are 0px, the active control has no shadow, dropdown content remains correct and the chevron rotates as expected.
- Confirmed phone retains the stacked native accordions and desktop retains the open five-column grid; the tablet menu remains hidden in both layouts. Browser console reports no warnings or errors.
- Pending review: client approval of the borderless v1.9 tablet menu and final Instagram/TikTok profile URLs before production implementation.

## 2026-07-31 - Header and footer v1.9 client preference

- The client confirmed that they like the borderless v1.9 tablet footer treatment.
- Treat v1.9 as the preferred current header/footer direction: full desktop footer, borderless four-item tablet dropdown menu, and stacked phone accordions.
- Remaining production follow-up is confirmation of the final Instagram and TikTok profile URLs and implementation approval.

## 2026-08-01 - Homepage v3.1.13 global header/footer integration

- Created `third-mockup-v3.1.13.html` from the approved Homepage v3.1.1 body and preserved `third-mockup-v3.1.1.html` as the rollback/reference version.
- Replaced only the homepage header and footer with the preferred Header + Footer v1.9 direction: desktop dropdown navigation and five-column footer, tablet menu plus borderless four-item footer dropdown, and phone menu plus stacked footer accordions.
- Kept interior page mock-ups body-only so the client can review the global component once without repeating it on every page.
- Updated the Round 3.1 review hub so Home opens v3.1.13 and added the standalone `header-footer-mockup-v1.9.html` as a reusable global component; the previous root review hub remains unchanged for rollback access.
- Updated the canonical Client Review Timeline sheet so the Home version journey and current link now point to v3.1.13.
- Verified desktop, tablet and iPhone layouts with no horizontal overflow, no broken images and no browser-console warnings/errors. Confirmed the tablet footer opens only one panel at a time and the phone footer retains native accordion behavior.
- Client-facing next step: review Homepage v3.1.13 together with the standalone Header & Footer v1.9 component. Production routes and final social profile destinations can be reconfirmed when the pages are assembled in WordPress.

## 2026-08-01 - Homepage v3.1.14 shared header/footer correction

- The user reported that Homepage v3.1.13 showed a large empty desktop-header gap at wide external-browser widths and that the desktop footer columns did not appear evenly aligned.
- Direct comparison confirmed the v3.1.13 integration and standalone v1.9 component had matching measurements at 1280px; the remaining spacing problems were therefore present in the v1.9 component itself, while the prior integration approach also made future visual drift possible.
- Created Header + Footer v1.10 and Homepage v3.1.14. Both now load the exact same `global-header-footer-v1.10.css` and `global-header-footer-v1.10.js` files rather than separate rewritten copies.
- The desktop header content is centered in one shared inner container and the action buttons follow the navigation with a fixed responsive gap, removing the large blank middle area at wide widths.
- The desktop footer now uses a 230px identity column plus four equal navigation columns; the visible logo and navigation headings were optically aligned. Tablet and phone footer behavior remains unchanged.
- Preserved Homepage v3.1.13 and Header + Footer v1.9 for rollback. Updated the Round 3.1 hub and canonical Client Review Timeline to point to Homepage v3.1.14 and Header + Footer v1.10.
- Verified desktop, tablet and iPhone behavior, shared-asset loading, menu/dropdown/accordion interactions, no broken images, no horizontal overflow and no browser-console warnings or errors.

## 2026-08-01 - Homepage v3.1.15 restored approved header/footer v1.9

- The user rejected the rewritten v1.10 header/footer and reiterated that approved components must be reused without visual reinterpretation.
- Created `third-mockup-v3.1.15.html` with the Homepage v3.1.14 body unchanged and the actual Header + Footer v1.9 source loaded directly from `header-footer-mockup-v1.9.html`.
- Added a narrowly scoped homepage compatibility reset only to neutralize obsolete global footer rules in `styles.css`; it does not redefine the approved component's visual design.
- Returned the Round 3.1 hub's Header & Footer card to approved v1.9 and advanced the Home card and canonical Client Review Timeline to v3.1.15. Versions v3.1.14 and v1.10 remain available for rollback history but are no longer current.
- Verified exact matching footer geometry and spacing against v1.9 at desktop, tablet and iPhone widths, with working mobile menu and footer accordions and no horizontal overflow. Confirmed the homepage main body is byte-for-byte unchanged between v3.1.14 and v3.1.15.
- Standing decision: once a component is approved, future page integrations must use that approved source as-is. Do not rewrite or restyle it unless the user explicitly requests a new component version.

## 2026-08-01 - Homepage v3.1.16 body-only review correction

- The user reported that Homepage v3.1.15 still showed the same unacceptable header/footer result and correctly noted that the live page should have been visually checked before it was called fixed.
- Revisited the earlier explicit decision: the homepage review should not contain a header or footer. The approved global header/footer is to remain a separate mockup so it cannot be altered by homepage styles.
- Created `third-mockup-v3.1.16.html` as a body-only version. Its `<main>` content is unchanged from v3.1.15; the header/footer mounts, integration loader and compatibility stylesheet were removed.
- Updated the Round 3.1 hub and canonical Client Review Timeline to make v3.1.16 the current homepage while retaining Header & Footer v1.9 as the separate global-component review item.
- Checked the rendered body-only page at desktop, tablet and iPhone widths: no header, no footer, no broken images and no horizontal overflow.
- Client-facing next step: review the homepage body and the approved global header/footer as two separate mockups, exactly as previously requested.

## 2026-08-01 - Header and footer v1.11 restores the original header

- The user identified the remaining wide-screen header problem in v1.9: later revisions had placed all flexible space between `Wellness Source Blog` and the two action buttons, creating a large blank gap.
- The user directed that the original header be reused rather than recreated. Created `header-footer-mockup-v1.11.html` using the exact header layout and responsive rules from `header-footer-mockup-v1.3.html`; no new header spacing system was invented.
- Kept the preferred v1.9 footer markup, desktop columns, borderless tablet menu and phone accordions unchanged. Desktop footer geometry matches v1.9 exactly.
- Verified the v1.11 wide-screen header geometry matches v1.3 exactly. The navigation and action buttons are again one adjacent group, with the original flexible space between the logo and navigation.
- Verified desktop, tablet and phone layouts with no horizontal overflow. Updated the Round 3.1 review hub to point its separate Header & Footer card to v1.11; the Homepage remains body-only at v3.1.16.
- Client-facing next step: review the separate v1.11 component and confirm the restored original header with the preferred v1.9 footer.

## 2026-08-01 - Round 3.1 review hub cleanup

- Removed the `Open the previous review hub` link and its unused styling from the current Round 3.1 review hub at the user's request.
- The hub now keeps reviewers focused on the current page cards and separate global-component mockup only.

## 2026-08-01 - Client revision-summary email drafted

- Prepared an initial detailed revision-summary draft, but the user correctly noted that it read like a project checklist rather than the prior mockup-review email.
- Reviewed the exact text of the previously sent mockup-review email supplied by the user and matched its numbered `Your feedback` / `What we did` response structure, warm tone and plain-language level.
- The final draft answers the client's feedback in the same order, identifies the approved pages that remain unchanged, summarizes every completed revision, explains why the global header/footer is reviewed separately, and introduces the new Our Team page.
- The closing asks only for approval of the revised mockups, the new Our Team page and the separate global header/footer; it does not add a new client task checklist.
- Included the current Round 3.1 review-hub link and separate Header & Footer v1.11 link. No email was sent or added to Gmail; the revised draft is awaiting the user's approval.

## 2026-08-03 - Clarkston photo-placement correction v3.1.17

- Created `clarkston-location-mockup-v3.1.17.html` from v3.1.11, preserving the prior version for rollback.
- Corrected the client-reported photo mix-up: the shared-drive Clarkston Team Photo now replaces the unwanted lower-resolution store image in the earlier “Wellness help close to home” section.
- Restored the original clear Clarkston in-store photo to the later “Personal guidance, practical next steps” section where the team photo had been placed by mistake.
- Updated both image alt descriptions to match their restored content and advanced the current Round 3.1 review-hub card to v3.1.17.
- Follow-up completed: the second issue was addressed in Homepage v3.1.18 and both corrected mockups were published to GitHub Pages; same-device client review remains pending.

## 2026-08-03 - Homepage mobile shipping and product-photo correction v3.1.18

- Reviewed the client’s Samsung-browser screenshot showing the “We Ship Nationwide” section clipped on the right even though it did not reproduce on the project owner’s phone.
- The evidence points to a narrow effective viewport and/or enlarged browser/system text exposing intrinsic-width behavior in the shipping heading and actions; this is a real responsive edge case rather than something that should be dismissed as cache alone.
- Created `third-mockup-v3.1.18.html` from the approved body-only v3.1.16 homepage and preserved the previous version for rollback.
- Added the narrowly scoped `homepage-feedback-v3.1.18.css`: shipping grid/flex children may now shrink, the phone heading and phone number scale within safe limits, and long content has a controlled wrapping fallback. Desktop and tablet styling remain unchanged.
- Replaced every homepage use of the soft 600×600 Liver Detox image with the existing sharp 2000×1500 photo of Rebekah’s private-label amber-bottle supplements and updated its alt text. No AI reconstruction or invented product label was used.
- Advanced the current Round 3.1 review-hub Home card to v3.1.18. The homepage remains body-only; the global header/footer is still reviewed separately.
- Updated the canonical Client Review Timeline so Home points to v3.1.18 and Clarkston points to v3.1.17; the existing wrapped-cell and hyperlink formatting was preserved.
- Published commit `c43beb7` to the GitHub Pages `main` branch and confirmed the Pages build completed successfully. Added a public QA harness for exact 360px, 390px and 360px/125%-text homepage checks.
- The first live enlarged-text screenshot revealed an orphaned heading period caused by the emergency anywhere-wrap fallback. Replaced that fallback with a viewport-based 32–42px heading scale and normal word breaking, then reran the live mobile cases before client handoff.
- Client-facing next step: recheck the updated shipping section on the same Samsung phone/browser and at its current text-size setting, then confirm the sharper private-label product image.

## 2026-08-03 - WordPress build-environment and Phase Two architecture recommendation

- Recommended a hybrid, cloud-first implementation workflow after final approval of Home and Clarkston: keep custom theme/plugin code in a GitHub repository so Codex cloud tasks can continue without Todd's computer, and use the existing Cloudways staging clone as the canonical WordPress integration, content, URL-preservation and client-approval environment.
- Do not treat a laptop checkout or direct edits inside the Cloudways filesystem as the sole source of truth. Use local Codex/Desktop work for interactive WordPress admin, SSH/WP-CLI operations, browser-based visual QA and carefully supervised destructive changes; deploy reviewed repository changes to staging in checkpoints.
- Before removing Bricks or other components, take a restorable Cloudways backup and complete a one-to-one disposition map for existing URLs, content types, SEO metadata, redirects, forms, media, practitioners, events and commerce data. Phase One commerce plugins may be deactivated where safe, but product/order/customer data should not be deleted.
- Build Phase One with a WooCommerce-ready separation of concerns: the theme owns visual presentation and reusable page/template styles; site-specific behavior and data structures belong in a small companion plugin; WooCommerce remains a Phase Two functional layer. Avoid hard-coding catalog, cart or checkout assumptions into Phase One page layouts.
- Client-facing next step: after the last two page approvals, establish the GitHub-to-Cloudways staging deployment workflow, snapshot staging, inventory retained URLs/data, then remove or deactivate legacy builder/commerce components in verified batches rather than performing one large deletion.

## 2026-08-03 - Staging-first WordPress implementation clarification

- Clarified that the actual WordPress site should be assembled directly on the existing Cloudways staging clone because its database already contains the retained URLs, posts, media, ACF-backed practitioner records, events, forms and Phase Two commerce data. A separate local WordPress installation would be optional and disposable, not the authoritative build.
- Keep the custom theme and any small project-specific plugin in Git even while deploying and testing them on staging. Use Codex cloud for isolated code work that benefits from running without Todd's computer; use staging for plugin configuration, WordPress content, database-backed layouts, social OAuth connections and final browser QA.
- Current social destinations are documented as Instagram `@rebekahs_health_and_nutrition` and TikTok `@rebekahspureliving`. Recommended starting with Smash Balloon's free Social Photo Feed and Feeds for TikTok plugins, subject to live compatibility/performance testing; the account owner must complete each platform's OAuth authorization without sharing social passwords.
- Instagram must be a Professional Business or Creator account for the current API connection. Confirm the desired feed behavior, privacy/consent treatment and whether free layouts are sufficient. Expect to verify or reconnect social sources after the production-domain cutover.
- Access still needed for implementation: Cloudways project/team access with backup and deployment capability, application SSH/SFTP or an approved equivalent, a private Git deployment target for theme/plugin code, and account-owner availability for Instagram/TikTok authorization. Existing Cody WordPress administrator access is already recorded.

## 2026-08-03 - Confirmed production stack and independent pre-build backup

- Corrected the production stack: use Elementor Free with a customized theme and supporting custom CSS/JavaScript; do not purchase Elementor Pro. This supersedes the later suggestion to avoid a visual builder.
- Use SEOPress Free + SEOPress PRO instead of Yoast. Before removing Yoast, back up the site, import its titles, descriptions, social metadata, robots directives, canonicals and global settings into SEOPress, verify representative pages, then deactivate Yoast so two SEO plugins are not active together.
- Use free plugins wherever practical. SEOPress PRO is the explicit approved paid exception; Instagram and TikTok should start with the free Smash Balloon plugins unless a later client-approved requirement cannot be met without a paid feature.
- Todd will handle Cloudways access, staging SSH/SFTP or deployment access, the private GitHub repository and verification of the existing Cody WordPress administrator account. These are not client action items.
- Leave the complete WooCommerce stack and all product, order, customer, coupon and payment data untouched for now because Phase Two is expected roughly one month after Phase One. Reassess deactivation only if performance requires it and a verified backup exists.
- Before rebuild changes, create both a Cloudways on-demand restore point and an independent full WordPress backup. The independent archive should include the database, plugins, themes, uploads and other WordPress files, plus a plugin/version manifest, and be stored in a restricted project Google Drive backup folder because it may contain WooCommerce customer/order information.
- Preferred free WordPress-level backup method is a one-time UpdraftPlus backup, downloaded and then uploaded into the project Google Drive folder; direct Google Drive authorization is optional and does not need to remain connected after the archival snapshot. Verify that all backup components completed and can be read before removing Bricks or migrating SEO data.
- Prepare a short client social-connection email at the integration stage, not as an immediate pre-approval task. It should ask the account owner to confirm Instagram is a Professional Business/Creator account, be available to authorize Instagram and TikTok through OAuth, and approve the final feed behavior. Never request social passwords by email.

## 2026-08-03 - WordPress access and backup-plugin verification

- Confirmed the Cloudways staging dashboard at `https://wordpress-1651482-6565113.cloudwaysapps.com/` is accessible with full administrator menus. The `Cody` account still exists with the Administrator role; Todd Bailey, Rebekah Spencer, Chelsea Stein and Winning Team are also listed as administrators.
- The shared `Cody WP Logins` Sheet currently contains the staging login record only; it does not contain a separate row for the live `rebekahspureliving.com` dashboard.
- Read-only review of the current staging plugin inventory confirmed that no backup/migration plugin is installed. UpdraftPlus is explicitly rejected and should not be recommended or installed for this client.
- Todd will sign into the live WordPress dashboard so its current plugin/backup setup can be inspected. No content, settings, plugins or files will be changed on the live site; all redesign implementation remains restricted to staging.
- SEOPress PRO licensing is already owned and available, so no new SEO-plugin purchase is required.

## 2026-08-03 - Live WordPress access and backup-state audit

- Confirmed authenticated administrator access to the live WordPress dashboard at `https://rebekahspureliving.com/wp-admin/` using Todd Bailey's administrator account. No live settings, content, plugins or files were changed.
- Corrected the account assumption: the live user list does not contain the `Cody` account. Cody exists as an Administrator on the Cloudways staging clone only. Todd Bailey is an Administrator on both live and staging, which is sufficient for authorized inspection and backup coordination.
- The live site has Cloudways WordPress Migrator active, explaining the existing Cloudways staging clone, but it does not have a true backup/restore plugin installed. The staging clone also has no backup/migration plugin installed.
- Preserve the rule that redesign changes occur only on staging. Do not install a backup plugin on live merely to create an archive. Treat the existing staging clone as the full pre-build copy, take a Cloudways on-demand restore point before construction, and use a non-Updraft backup tool on staging if a portable Google Drive archive is required.
- UpdraftPlus remains explicitly excluded. A suitable free staging-only alternative is WPvivid Backup & Migration, which supports full backups and Google Drive in its free version; installation and Google Drive authorization require Todd's explicit go-ahead at action time.

## 2026-08-03 - ManageWP staging-backup setup initiated

- Todd approved using the existing Blue Nova ManageWP account for the independent pre-build backup instead of installing WPvivid or UpdraftPlus. Neither WPvivid nor another true backup plugin is currently installed on live or staging.
- Began adding only the Cloudways staging application (`wordpress-1651482-6565113.cloudwaysapps.com`) to ManageWP. ManageWP accepted the staging URL and displayed the authenticated WordPress connection step, but the browser-control connection stopped responding before the final Add Website action could be verified.
- No purchase or paid ManageWP backup add-on was authorized, no backup was claimed as complete, and no changes were made to the live WordPress site. The staging connection screen was left open for continuation.
- Next step: restore browser control, finish the staging-only ManageWP connection, confirm whether the account provides a no-cost backup slot or would incur a charge, and create and verify the backup only if no new charge is introduced. A paid add-on requires Todd's approval first.

## 2026-08-03 - WPvivid staging backup installed and initiated

- Todd superseded the ManageWP-first plan: use WPvivid Free on the Cloudways staging clone for the independent pre-build backup, then add the production site to ManageWP after launch.
- Installed and activated WPvivid Backup Plugin Free v0.9.132 on staging only. No plugin or configuration changes were made on the live WordPress site.
- Started a manually protected full backup containing the database plus all WordPress files and marked it for manual deletion only. The task reached 13% and spent an extended period dumping the large `hzj_frmt_form_views` Forminator analytics table; no completed backup has yet been claimed or verified.
- Started Google Drive authorization. Google is waiting at the account chooser between Todd Bailey and the Blue Nova Marketing account. Recommended the agency-owned Blue Nova Marketing account so the archive is not tied to an individual account; Todd must confirm/select the intended Drive account before storage configuration can finish.
- Next steps: complete the Google account/permission screen, confirm the backup job completes without error, verify the archive is listed and restorable, then place or send a verified copy to the restricted client project folder in Google Drive before any rebuild changes.

## 2026-08-03 - Cloudways pre-build restore point completed

- Stopped the WPvivid backup route after its background requests repeatedly failed on the staging server: the first full backup stalled/failed during the database dump, and later attempts could not persist WPvivid's timeout-reduction settings or complete the optional size calculation.
- Temporarily deactivated Wordfence on staging to test whether it was blocking WPvivid, preserved all Wordfence data, confirmed the same failure continued, and reactivated Wordfence immediately. Live Wordfence and the live site were never changed.
- Switched to Cloudways' native application-level on-demand backup for the staging application. Cloudways completed a files-and-database restore point dated `3rd August, 2026, 17:12:05 UTC`, which is listed as the latest backup and is available in the application's Restore selector.
- The WordPress build can proceed against staging without waiting for WPvivid or Google Drive authorization. WPvivid did not produce a verified portable archive, so no Google Drive copy should be claimed as complete. A separate portable archive can be revisited later without blocking Phase One assembly.

## 2026-08-03 - Final Pages 1 and 6 approval email drafted

- Created an unsent Gmail reply draft in Rebekah’s existing “Updated Website Mock-Ups Ready for Approval” thread, addressed to `rebekahspureliving@gmail.com`.
- The draft states that every other review-hub page is already approved and asks Rebekah to review only the revised Home and Clarkston Store pages.
- Included direct links to Homepage v3.1.18 and Clarkston Store v3.1.17, summarized the responsive shipping, sharper private-label product photo and corrected Clarkston photo placement, and attached `Homepage Mobile Shipping Comparison.png` showing the verified 360px, 390px and enlarged-text layouts.
- The draft explains that approval of Pages 1 and 6 completes page-design review and allows the project to move into WordPress assembly and final technical QA before launch. The email was not sent; draft ID `r-3455697600427916120` is awaiting Todd’s review.

## 2026-08-03 - Homepage private-label photo crop refinement v3.1.19

- Created `third-mockup-v3.1.19.html` from v3.1.18 so the prior approved responsive revision remains available for rollback.
- Kept the authentic 2000×1500 Rebekah’s private-label supplement photo and its original labels intact; no generative image editing was used.
- Added a dedicated clipped photo frame and applied a subtle 0.65-degree clockwise correction, modest enlargement and downward shift. This visually levels the display, removes most of the carpet at the bottom and keeps the bottle tops visible.
- Advanced the Round 3.1 review-hub Home card and canonical Client Review Timeline to v3.1.19, published the version to GitHub Pages, and verified the final crop at 1440px desktop and 390px mobile with no horizontal overflow or browser errors.
- The first rendered pass was level but retained more carpet than requested. Tightened the final crop to an 11% enlargement with a 23–28px downward shift, leaving only a narrow lower edge while preserving the bottle tops.
- Pending follow-up: once Todd approves the v3.1.19 crop, replace the existing attached Gmail approval draft with a new draft that links directly to v3.1.19. Gmail does not allow in-place body updates on drafts that already contain attachments, so the current unsent draft still references v3.1.18 and should not be sent.

## 2026-08-03 - Homepage product photo measured leveling v3.1.20

- Todd's wider-browser review showed that the 0.65-degree correction in v3.1.19 was still visibly tilted. Image analysis measured the photo's lower shelf/carpet reference line at approximately 2.1 degrees high on the right.
- Created `third-mockup-v3.1.20.html` and applied the full 2.1-degree clockwise correction while preserving the authentic product labels, the tighter crop, the visible bottle tops and all previously approved homepage behavior.
- Advanced the Round 3.1 review-hub Home card and canonical Client Review Timeline to v3.1.20, published commit `81ef324`, and verified the live page at 1440px desktop and 390px mobile with no horizontal overflow or browser errors.
- Pending follow-up: replace the existing unsent Gmail approval draft with a new v3.1.20 link only after Todd approves this corrected crop.

## 2026-08-03 - Homepage bottle-row guide correction v3.1.21

- Todd supplied a desktop screenshot with horizontal guides through the top and bottom bottle rows, revealing a small remaining rise toward the right after v3.1.20.
- Created `third-mockup-v3.1.21.html` and increased the clockwise correction from 2.1 to 2.4 degrees. The authentic photo, product labels, crop, bottle-top visibility and approved homepage behavior remain unchanged.
- Advanced the Round 3.1 review-hub Home card and canonical Client Review Timeline to v3.1.21, published commit `589ddfa`, and verified the live page at desktop and 390px mobile sizes with no horizontal overflow or browser errors.
- Pending follow-up: replace the existing unsent Gmail approval draft with a new v3.1.21 link only after Todd approves this final alignment.

## 2026-08-03 - Final Homepage v3.1.21 approval email drafted

- Re-read Rebekah's latest reply in the existing website-approval thread. She has approved Clarkston and every other page; Homepage Page 1 is the only remaining approval.
- Created a new unsent Gmail reply draft addressed to `rebekahspureliving@gmail.com` with the direct Homepage v3.1.21 review link and the final desktop alignment proof attached.
- The draft explains that the existing clear private-label photo was straightened and recropped, confirms desktop and phone checks, and asks Rebekah to approve Page 1 so the project can move into WordPress assembly and final technical QA. Draft ID: `r7214542929347960024`.

## 2026-08-03 - Homepage product photograph geometry correction v3.1.22

- Todd correctly identified that v3.1.21 remained visibly crooked. The prior approach rotated the entire photograph and could not make bottle tops and bottoms share horizontal planes because the individual bottle rows had inconsistent perspective and placement.
- Used the image-editing workflow to rectify the bottle geometry itself: lower bottle bases, lower cap tops, upper bottle bases and upper cap tops were aligned to independent horizontal guides while retaining the amber bottles, Rebekah's branding, product names and store setting.
- Saved the new project asset as `output/homepage/private-label-bottles-leveled-v1.png`, created Homepage v3.1.22, removed page-level rotation, and advanced the Round 3.1 review-hub Home card and canonical Client Review Timeline.
- Published commit `653edb9`. Verified the exact 439×500 desktop webpage crop against three visible horizontal guides through the upper cap tops, shared middle plane and lower bottle bases. Verified the 390px phone layout has no horizontal overflow or browser errors.
- The existing Gmail approval draft still links to v3.1.21 and remains unsent. Do not send or replace it until Todd has reviewed v3.1.22; replacing the attached draft requires deleting the old draft and creating a new one.

## 2026-08-03 - Homepage fuller corrected-photo presentation v3.1.23

- Todd approved the geometrically leveled private-label product image and requested that the mockup show as much of the photograph as possible.
- Created Homepage v3.1.23 with a responsive 4:3 photo frame matching the source image, no image zoom and no rotation. Widened the desktop image column, reduced the arch corner crop, and retained a proportional 4:3 frame on phones so the bottle group and surrounding store context remain visible.
- Published commit `17991b1`, advanced the Round 3.1 review-hub Home card and canonical Client Review Timeline to v3.1.23, and verified the live page at 1440px desktop and 390px phone widths. The rendered image frame stays 4:3, uses the corrected asset with no rotation or scaling transform, has no horizontal overflow and produced no browser errors.
- The existing Gmail approval draft still links to v3.1.21 and remains unsent. Replacing its attached version requires deleting the old draft and creating a new one, so wait for Todd's explicit approval before replacing it with the v3.1.23 link.

## 2026-08-03 - Page 6 client approval confirmed

- Todd confirmed that Page 6, the Clarkston Location page, is client approved.
- Updated `Page Inventory!E10` in the canonical Google Sheet from `Awaiting client approval` to `Approved`; the WordPress Build Status remains `Not started` until staging assembly begins.
- Page 1, the Homepage, is the only remaining page-design approval before WordPress staging assembly begins.

## 2026-08-03 - Final Homepage and Phase One design approval confirmed

- Todd confirmed client approval of the current Homepage, completing the outstanding Page 1 approval.
- All 20 review pages are now client approved. Updated `Page Inventory!E5:E24` and `Client Review Timeline!H5:H24` to `Approved`, and updated the review-timeline summary to show that WordPress staging assembly may begin.
- WordPress Build Status remains `Not started`; the next project milestone is assembling the approved designs on the backed-up Cloudways staging application while leaving live unchanged.
- The unsent Homepage approval-request Gmail draft is now obsolete and must not be sent; approval has already been received.

## 2026-08-03 - Staging privacy check

- Confirmed that the Cloudways staging site can be reviewed from any computer or mobile device using its web URL; WordPress editing still requires the staging administrator login.
- An anonymous request to the staging URL returned `HTTP 200`, so the application is currently publicly reachable by anyone who has or discovers the URL. It is not yet password-private.
- The response includes `X-Robots-Tag: noindex, nofollow`, so search-engine indexing is blocked, but `noindex` is not an access-control mechanism.
- Before WordPress assembly, enable Cloudways application password protection/HTTP Basic Auth and share the separate review username and password only with authorized reviewers. Also keep staging email, payment and webhook activity isolated from live services.

## 2026-08-03 - Homepage v3.1.23 client approval email prepared

- Prepared a concise client-facing reply for Rebekah with the live Homepage v3.1.23 link. The email explains that the existing private-label photo was geometrically leveled, reframed to show as much of the image as possible, and checked on desktop and phone layouts.
- The draft notes that a replacement photo is no longer required unless Rebekah still prefers a different image, and asks her to approve Page 1 so page-design review can close and WordPress assembly can begin.
- The existing Gmail draft remains unsent and still links to v3.1.21. It was not changed or deleted; replacing that attached draft requires Todd's explicit approval.

## 2026-08-03 - WordPress staging build authorized and local deployment package started

- Todd authorized Codex to assemble the complete approved website on the Cloudways staging application, with Todd reviewing the result after the build. Live remains out of scope for changes.
- Confirmed that the 21 existing published blog posts will be retained. A dynamic WordPress archive and single-post template now form part of the custom theme foundation, so the approved design will apply automatically to existing and future posts.
- Began an Elementor-compatible custom theme package based directly on the 20 approved mockups, plus the approved global header/footer, Our Team, blog-post and 404 designs. Elementor Free remains available for page editing; the custom theme supplies the global and dynamic template functions that otherwise require Elementor Pro.
- Added a staging-only guard plugin package that blocks outgoing WordPress email, Forminator delivery, WooCommerce payments, WooCommerce webhook delivery and indexing when the host is a Cloudways staging domain.
- Social sections will use Rebekah's real Instagram and TikTok feeds. The visual placeholders remain available during assembly; the account owner will authorize each feed through OAuth near the end, without sharing social passwords.
- Pending: deploy and activate the theme and staging guard on staging, enable Cloudways application password protection after Todd's explicit confirmation, install/configure the free social-feed plugins, connect SEOPress Free + Todd's SEOPress PRO license, and complete responsive/content/link/form safety QA.

## 2026-08-03 - Staging deployment access decision pending

- WordPress removed the original nested staging-guard plugin file but retained its empty top-level directory, preventing a corrected package with the same slug from uploading. Repackaged the unchanged guard code under the clean `blue-nova-staging-guard` slug; it is ready for deployment.
- Browser-driven WordPress admin navigation proved slow and unreliable for repeated package uploads. Todd proposed using a WordPress Application Password.
- A WordPress Application Password is appropriate for REST-managed pages, posts, media and build verification, but core REST access does not replace theme/plugin file deployment or WP-CLI activation. The preferred full-build access is a dedicated staging-only Cloudways Application Credential with SSH/SFTP enabled, ideally using a project-specific SSH key; REST credentials can be added afterward for content operations.
- Live remains untouched. WooPayments remains in Safe Mode on staging and its production connection was not transferred or replaced.

## 2026-08-03 - WordPress staging foundation deployed and verified

- Deployed and activated the custom `Rebekah's Health & Nutrition 2026` theme and the Blue Nova staging guard on the Cloudways staging application only. Live and the existing WooCommerce product, order, customer, coupon and payment data were not changed.
- The staging guard is actively blocking outgoing WordPress email, Forminator delivery, WooCommerce payments, webhook delivery and search indexing while the site is being built.
- Installed and activated official Elementor Free 4.2.0. Increased effective staging PHP memory from 128 MB to 512 MB after the original limit caused a recoverable activation failure; independently verified the new limit from inside WordPress.
- Installed and activated the current official free Smash Balloon social-feed foundations: Instagram Feed 6.11.3 and TikTok Feed 1.6.1. Rebekah or the account owner still needs to authorize each owned account through OAuth before the approved homepage placeholders are replaced with live feeds; no social passwords are needed.
- Cleaned the approved homepage's social-feed handoff without changing its appearance: removed the hidden duplicate TikTok markup, replaced twelve temporary dead `#` card links with the correct Instagram or TikTok profile destination, and normalized the social separators/play symbols to safe HTML entities. The cards will receive individual live-post destinations after OAuth and feed configuration.
- Assembled all 20 approved page designs on staging, retained the 21 existing published blog posts under the new dynamic archive/single templates, retained seven existing event records and added the approved Events archive presentation. Resolved the legacy Lapeer media/page slug collision without changing the media file URL.
- Verified the approved routes return HTTP 200 without WordPress critical errors, confirmed the first existing blog post uses the new single-post template, and corrected the mobile header so the 390 px layout has no horizontal overflow.
- Updated `Page Inventory!F5:F24` in the canonical Google Sheet from `Not started` to the validated dropdown value `In progress`. Keep the pages in progress until social authorization, SEOPress Free + Todd's SEOPress PRO license, form/link/content proofing and final responsive review are complete.
- Pending client-facing steps: Todd may review the staging URL from any computer now; arrange a short authorization session with the Instagram/TikTok account owner later. Staging remains noindex but is not yet protected by an HTTP review password.

## 2026-08-03 - SEOPress PRO staging activation and Yoast migration completed

- Installed and activated SEOPress Free + SEOPress PRO 10.1 on the Cloudways staging application and verified that Todd's existing PRO license is active. The license key was not written to the project notes or retained in deployment files.
- Deactivated Yoast SEO 28.1 on staging to prevent duplicate SEO output, but kept the inactive plugin and all original Yoast database fields in place as a temporary rollback source. Live WordPress was not changed.
- Ran SEOPress's bundled Yoast migration after the staging build was assembled. Verified parity for all populated source fields: 98/98 meta descriptions, 31/31 focus-keyword records, 1/1 custom title and 46/46 primary-category assignments. Yoast settings and taxonomy metadata were also passed through the official importer.
- Verified a real product page outputs the migrated description, canonical URL, Open Graph tags and Twitter tags from SEOPress, with no Yoast-generated markup. The staging guard continues to return `noindex, nofollow`, so the Cloudways application remains excluded from indexing.
- Removed the one-time migration helper after verification. Keep domain-sensitive SEO work for the launch window: replace staging-domain canonicals where needed, finish approved page-level titles/descriptions, validate redirects and sitemap URLs, connect analytics/Search Console, submit the production sitemap and only then remove staging noindex. Yoast may be deleted after the production-domain SEO verification and rollback window.

## 2026-08-03 - Approved Homepage embedded sections restored on staging

- Found and corrected a theme-compiler defect that treated a JavaScript asset's version query string as part of its local filename. That omission prevented six approved Homepage sections from loading on staging: guidance pathways, nationwide shipping, events, journal, newsletter signup and practitioner brands.
- Added a dedicated WordPress hydration asset for those six sections, retained their approved mockup styling and replaced mockup-only links with real WordPress routes for products, practitioners, events, locations, blog and practitioner-brand shopping pages. Patched the compiler so a later theme rebuild will not repeat the omission.
- Disabled the existing third-party live-chat script on the Cloudways staging domain through the staging guard. This prevents real chat activity and keeps the chat window from covering the approved hero during proofing; it does not disable live-domain chat.
- Verified staging at desktop size: all six section mounts contain their expected content, no temporary `#` links remain, the page has no chat iframe and the homepage asset loads successfully. Also verified the actual 390 px mobile layout in an isolated frame: the responsive header/menu, hero copy, calls to action, proof card and first approved pathway section fit without clipping.
- Live WordPress was not changed. Homepage remains `In progress` until live Instagram/TikTok authorization and the remaining sitewide page-by-page QA are complete.

## 2026-08-03 - Events and practitioner templates connected to retained WordPress data

- Replaced the Classes & Events archive's four hard-coded mockup cards with the actual upcoming records from The Events Calendar while preserving the approved archive design. Four events are currently upcoming as of August 3, 2026; existing historical records remain accessible through a real Past view.
- Made the archive search, store and event-type controls filter the server-rendered events. Removed both dead `#` actions, linked the signup action to the real footer connection area and corrected the archive's SEOPress title/description and social metadata.
- Rebuilt the event-detail template around each retained event's title, image, description, date, time, venue, address, organizer, cost and venue phone. Existing event content remains authoritative; registration/ticketing was not invented or enabled. Related cards now link to other real upcoming events.
- Replaced the practitioner archive's frozen 25-entry JavaScript directory with all 28 currently published `medical-practicioner` records. The design now reads the existing service taxonomy and the established practitioner metadata fields for full name, practice/center, description and contact information, and every card opens its real WordPress profile.
- Rebuilt practitioner profiles to present those existing metadata fields in the approved profile design. Current phone, email, address and external website details are shown only when present in that record; no appointment or service capability was invented. A temporary read-only staging schema audit used to identify the existing fields was removed immediately after verification.
- Re-ran the structural staging check across Home, Our Story, Our Team, Locations and all four stores, Events, Blog, In-Store Products, Practitioners, Contact, legal pages and all four external-shopping/referral pages. All 21 routes return HTTP 200, contain exactly one H1, use the expected theme template, have no critical error, no dead `#` links, no mockup `.html` links, no staging chat widget and retain staging `noindex`.
- Live WordPress was not changed. Events and Practitioners remain `In progress` pending final responsive visual proof and launch-domain SEO/integration QA.

## 2026-08-03 - Existing contact form connected to the approved Contact page

- Removed the inert mockup form and its public “mockup only” notice from the staging Contact page.
- Embedded the existing published Forminator `contact-us` form (ID 1064) in the approved form-card design and added theme-scoped styling for its inputs, textarea, validation messages and submit button.
- Verified the page now renders one real Forminator form with name, email, phone, subject, message, spam protection and submission controls; the old dead `action="#"` form is gone. The staging guard still blocks delivery, so no real inquiry or email can be sent during proofing.
- Pending launch QA: update the existing message placeholder from its older business-inquiry wording if desired, confirm notification recipients/routing, complete consent/spam review and run a controlled end-to-end delivery test only after the production domain and mail service are ready.

## 2026-08-03 - Contact and newsletter mockup blockers removed

- Removed the mockup-only JavaScript that intercepted Contact and Homepage newsletter submissions. Both approved layouts now retain Forminator's real submission behavior instead of showing a prototype response or silently stopping the form.
- Updated the existing Contact form's visible message prompt to `How can we help?` and its button to `Send my message`, matching the approved design language without changing its saved field structure.
- Connected the existing published newsletter form (Forminator ID 313) to the approved Homepage newsletter section. It currently retains its established Email and Phone Number fields; the approved preferred-store choice remains a build follow-up because that field does not yet exist in the saved form.
- Re-ran structural verification across all 21 approved routes after deployment. Every route returns HTTP 200, has exactly one H1, uses the expected template, contains no dead `#` or mockup `.html` links, shows no staging chat widget and retains `noindex`.
- Staging's safety guard continues to block outbound email and other live integrations, so form delivery will be tested only after notification routing and the production mail/domain configuration are ready. Live WordPress was not changed.

## 2026-08-03 - Three-width responsive overflow sweep completed

- Added a repeatable staging-only responsive verifier and checked all 21 approved routes at 390 px phone, 768 px tablet and 1440 px desktop widths (63 route/viewport combinations total).
- Found one 10 px desktop overflow on Our Story. An old page-mockup `.footer-links` selector was overriding the shared WordPress footer and pushing the Disclaimer link beyond the viewport.
- Scoped that legacy rule to the mockup footer, deployed the correction to staging and reverified Our Story at all three widths with zero overflow. The remaining route/viewport checks also completed without overflow, critical errors or H1-count regressions.
- Live WordPress was not changed. This is structural responsive QA; page-by-page visual comparison and client proofing remain part of the in-progress build.

## 2026-08-04 - Forms, navigation, SEO and referral disclosures advanced on staging

- Added the approved `Preferred store` choice as a real saved Forminator field on newsletter form 313, with Lapeer, Grand Blanc, Clarkston and Lake Orion options. Backed up the original Forminator form metadata in WordPress before the one-time update and removed the temporary update/audit helpers immediately afterward.
- Corrected Forminator's generated style overrides so Homepage newsletter and Contact inputs are visible and aligned at desktop and mobile sizes. Verified the newsletter button remains inside its form at desktop, and both forms have zero mobile horizontal overflow at 390 px.
- Replaced the Contact form's legacy `Tell us about your business` and `Submit` wording with `How can we help?` and `Send my message`, using delayed-render-safe JavaScript without intercepting submission. Removed the public implementation-status note and replaced it with a concise privacy notice.
- The existing reCAPTCHA key does not recognize the Cloudways staging domain. Hid the invalid-domain badge on the two form pages and added Google's required Privacy Policy/Terms disclosure. Staging delivery remains blocked; production reCAPTCHA keys and end-to-end mail routing still require launch-domain verification.
- Completed the approved Our Story consolidation: `/meet-the-owner/` now returns a 301 redirect to `/our-story/`, the Homepage links directly to Our Story, and all four Homepage location-card actions are real links to their store pages.
- Added intentional SEOPress titles, meta descriptions and social descriptions for the Phase One page set. Added Organization schema on Home and verified `HealthAndBeautyBusiness` address, phone and opening-hours schema on all four location pages. Staging remains `noindex` until launch.
- Rechecked the Fullscript, Designs for Health, LifeWave and EllieMD destinations on August 4, 2026; all four returned HTTP 200 at the URLs used on staging. Replaced internal pre-publication notes with visitor-facing external-site/referral disclosures, added `rel="sponsored"` where needed and removed the unverified Fullscript `355+ brands` claim.
- Re-ran the 21-route structural audit after these changes. All routes return HTTP 200, contain exactly one H1, use the expected theme template, contain no dead `#` or mockup `.html` links, show no staging chat widget and retain `noindex`. Live WordPress was not changed.
- Remaining integration-dependent work: Instagram/TikTok account-owner OAuth, production-domain reCAPTCHA/mail delivery, final production-domain canonical/sitemap checks and removal of staging-only protections at launch.

## 2026-08-04 - Complete staging site ready for Todd's consolidated review

- Finished the Phase One staging build and changed all 20 canonical `Page Inventory` build statuses from `In progress` to the validated `Ready for review` dropdown value. Live WordPress and retained WooCommerce data were not changed.
- Completed a final crawl of 21 primary routes, 45 retained dynamic blog/event/practitioner pages, 66 internal links and 76 images with zero failures. Repeated the full 390 px phone, 768 px tablet and 1440 px desktop sweep across all 21 routes (63 combinations) with zero overflow, H1 or critical-error failures.
- Replaced the four store pages' non-submitting mockup signup forms with the real Forminator newsletter form, preselecting the correct preferred store on each page. Verified the mobile navigation/dropdowns, blog and product filters, practitioner search and representative browser consoles without failures.
- Added dynamic event/practitioner SEO output, hardened new-tab links, corrected obsolete Signature Line product links, removed remaining public implementation placeholders and clearly labeled the legal pages as drafts requiring qualified legal review.
- Activated Breeze on staging and purged Cloudways/Varnish so the bare review URL serves the current theme. Deferred the large Homepage hero video behind an optimized poster, lazy-loaded noncritical Homepage images and added font/resource hints. A fresh PageSpeed lab run was unavailable because the API quota was exhausted; current production CrUX still shows slow server/paint performance, so production performance must be rechecked after cutover.
- Remaining launch-only work: Instagram and TikTok account-owner OAuth for live updating feeds, legal approval, production-domain reCAPTCHA and mail-delivery testing, final production SEOPress canonicals/sitemap/indexing checks, analytics/Search Console verification and removal of staging guards. WooCommerce remains deferred to Phase Two.

## 2026-08-04 - Approved global header and footer enforced on staging

- Todd correctly reported that the header and footer looked different between pages. The site already used one shared WordPress `header.php` and `footer.php`; the inconsistency came from legacy page-mockup CSS overriding those shared components on pages including Our Team, Events, Refund & Returns, Lapeer and In-Store Products.
- Added a final, ID-scoped global component stylesheet so page-specific styles can no longer restyle the sitewide header or footer, while retaining the responsive desktop, tablet and mobile accordion behavior.
- Corrected an initial reference-selection mistake: the separate Header/Footer v1.3 study was exploratory and included a footer Call To Order link, while the approved final Homepage footer did not. Removed that link from the global footer; the approved header Call To Order button remains.
- Deployed only to Cloudways staging and purged Breeze. Verified all 21 primary routes contain the same shared header/footer markup and current global stylesheet. Computed desktop header/footer geometry and colors match across representative formerly conflicting pages, with no browser console warnings or errors.
- Live WordPress was not changed. Todd can review the corrected global header and footer anywhere on staging; use a hard refresh if an older tab was already open.

## 2026-08-04 - Full approved-mockup visual audit completed

- Re-read the canonical Page Inventory, page prompts and Templates & Global scope, then compared all 21 primary staging pages directly with their current approved mockups at 390 px phone, 768 px tablet and 1440 px desktop widths. The audit checked direct section structure, H1s, section colors, display modes, responsive padding, section geometry and horizontal overflow rather than relying only on HTTP or DOM-presence checks.
- Every primary page retained the approved section order, typography/color system and responsive treatment with zero horizontal overflow. Static page bodies matched their source mockups exactly or within normal one-pixel browser rendering variance. Larger height differences were confined to intentional WordPress substitutions: real Forminator forms, current posts, upcoming event records, current practitioner records and finalized visitor-facing partner/legal disclosure text.
- Confirmed that the correct approved global source is Header/Footer v1.11. Updated staging from the earlier tablet treatment to the exact v1.11 behavior: original desktop header, borderless four-tab tablet footer, phone accordions and no footer Call To Order link. Verified the component at 390, 768 and 1440 px, including a working tablet Explore panel, and confirmed the header Call To Order button remains.
- Audited the reusable Blog Post, Event Detail, Practitioner Profile and 404 treatments at the same three widths. Restored the approved practitioner-profile informational disclaimer that the dynamic WordPress template had omitted; no practitioner data was changed. The blog/event/profile templates continue to use the retained WordPress record content inside the approved visual systems.
- Deployed the corrections only to Cloudways staging. Verified all 21 primary routes now serve theme version 1.0.9 with the shared global header/footer markup, tablet footer controls, exactly one H1, no footer Call To Order link and no critical-error output. Representative browser consoles were clean. Live WordPress remains untouched.

## 2026-08-04 - Homepage private-label hero video edited and connected on staging

- Reviewed Rebekah's supplied `Front page vidio-1.m4v` source. The finished private-label bottles did not appear until approximately 46 seconds, despite Rebekah's request that the private-label products be featured closer to the beginning.
- Created a 35.96-second silent hero loop that opens with the finished Rebekah's bottles, then continues through the farm, ingredient, label-printing and bottling sequence. The ending labeling footage flows naturally back into the opening finished-product reveal when the HTML video loops.
- Removed the audio track, resized the web delivery copy to 960×540, enabled MP4 fast-start metadata and compressed it from the 69.6 MB source to 1.77 MB so it fits staging's 2 MB WordPress Media Library limit and is materially lighter for homepage delivery.
- Uploaded the optimized MP4 and its 92.8 KB matching poster image to the staging WordPress Media Library. Verified both public files return HTTP 200 with year-long cache headers and the video supports byte-range requests.
- Updated the staging Homepage template to use the WordPress uploads URLs for the new video and poster while retaining muted inline looping, deferred loading and the approved hero design. Purged Breeze and verified the normal cached Homepage response references both Media Library files without PHP errors.
- Live WordPress was not changed. Client-facing next step: review the new hero opening and loop on staging; after approval, retain these Media Library assets when staging is migrated to production.

## 2026-08-04 - Homepage hero changed to full circular source sequence

- At Todd's direction, replaced the 35.96-second highlight montage with a 58.64-second circular edit that preserves the complete supplied video: source 46 seconds through the original ending, followed by source 0 through 46 seconds.
- The circular edit keeps the requested opening at approximately 46 seconds and creates a continuous source-order loop from the final pre-46-second frame back into the opening 46-second frame.
- Kept the hero silent, 960x540, H.264 and fast-start enabled. Two-pass compression produced a 1,936,102-byte file that remains below staging's 2 MB Media Library limit.
- Uploaded `rebekahs-homepage-hero-loop-full-v2-media.mp4` to staging Media Library (attachment 1478922), updated the Homepage template, purged Breeze and verified the normal cached Homepage references only the new video. The public MP4 returns HTTP 200, year-long cache headers and byte-range support.
- The previous shorter MP4 remains in the staging Media Library as a recoverable fallback but is no longer referenced by the Homepage. Live WordPress was not changed. Client-facing next step: review the full circular loop on staging.

## 2026-08-04 - Gritty hero encode replaced with HD delivery copy

- Todd reported that the 1.85 MiB full-loop encode looked gritty. The issue was the extreme 260 kb/s bitrate required to fit the staging WordPress 2 MB Media Library upload ceiling, not the supplied source footage.
- Re-encoded the same 58.64-second circular sequence from the original 1080p source at 1280x720, H.264 High Profile Level 3.1, 23.98 fps, CRF 26, approximately 1.52 Mb/s, silent and fast-start enabled. The resulting 11,174,106-byte file is substantially sharper while remaining reasonable for deferred hero-video delivery.
- Uploaded `rebekahs-homepage-hero-loop-full-v3-hd.mp4` directly to the staging uploads directory through the existing authorized SFTP access, bypassing the Media Library's 2 MB form limit without using YouTube or an external host.
- Updated the staging Homepage to reference the HD file, purged Breeze and Cloudways Varnish, and verified the normal cached Homepage contains the v3 URL only. The public MP4 returns HTTP 200 with year-long cache headers and byte-range support.
- Removed the temporary one-time cache-purge helper immediately after use and verified it returns HTTP 404. Live WordPress was not changed; the lower-quality versions remain available only as staging fallbacks.

## 2026-08-04 - Correct longer hero source, loop timing and immediate playback

- Todd reported that the HD hero still looked gritty, remained on the poster after page load/refresh, and wrapped to the beginning after only about 10 seconds.
- Confirmed the earlier source `Front page vidio-1.m4v` is actually 58.68 seconds, not 1:15. Starting it at 46 seconds necessarily left only 12.68 seconds before the first wrap. Located the intended longer `Front Page video-2.m4v`, which is 2:08.46 and contains additional manufacturing and finished-product footage.
- Rebuilt the circular sequence from the longer source as 46 seconds through 2:08.46, then 0 through 46 seconds. The resulting video is 2:08.46 total and now plays approximately 82.46 seconds before its first wrap to the source beginning.
- Encoded the replacement at the source's full 1920x1080 resolution, H.264 High Profile Level 4.1, 23.98 fps, CRF 22 and approximately 6.14 Mb/s, with silent audio, two-second keyframe spacing and MP4 fast-start metadata. The file is 98,679,874 bytes and preserves substantially more source detail than the earlier 720p encodes.
- Removed the Homepage's intentional four-second deferred-video timer and interaction requirement. The video now has a direct `src`, `autoplay`, `muted`, `loop`, `playsinline` and `preload="auto"`, so the browser begins fetching and playing it immediately on load or refresh.
- Uploaded `rebekahs-homepage-hero-loop-long-v4-hd.mp4` directly to staging, updated the Homepage, purged Breeze and Cloudways Varnish, and verified the normal cached HTML contains the v4 source only with no `data-video-src`. The MP4 returns HTTP 200, supports byte ranges, and delivered the first 1 MiB range with HTTP 206 in approximately 1.05 seconds during verification.
- Removed the temporary cache-purge helper immediately after use and verified it returns HTTP 404. Live WordPress was not changed; earlier staging encodes remain recoverable fallbacks but are not referenced.

## 2026-08-04 - Practitioner directory reconciled to approved 25 listings

- Todd confirmed that the public practitioner directory should match the approved 25-listing mockup. The three listings omitted during mockup approval are IV Lounge, Kimberly Cabe, FNP-BC, and Troy Farwell, D.N.M. MS, MH, RAP, RAH, HHP.
- Added a reversible theme-level display filter for those three names. Their published WordPress records and direct profile data remain intact for rollback or a future decision; no database content was deleted or changed.
- Deployed only to Cloudways staging and cleared the site/Varnish cache. The normal review URL reports and renders exactly 25 cards; none of the three omitted names appears as a card, and the now-unused IV Lounge area-of-practice filter was removed. All three retained direct profile URLs still return HTTP 200. The canonical planning sheet still contains the older 28-profile scope note and should be reconciled if the team wants the tracker updated.

## 2026-08-05 - Header switches to menu before desktop navigation compresses

- Todd approved changing the global responsive header so it switches to the menu at 1380px and below instead of shrinking the desktop navigation and action text to 11.5-12px.
- Retained the approved full desktop header above 1380px, the existing navigation labels and destinations, and the approved footer. Practitioners remains inside the About dropdown, and no separate Home navigation item was added.
- Increased the compact-header logo to 220px and kept the opened menu and mobile action text at 14px for readability. Deployed only to Cloudways staging. Verified at a compact desktop viewport that the menu replaces the squeezed navigation, opens correctly at 14px, and causes no horizontal overflow; verified at 1440px that the full desktop navigation and actions return with no overflow. Live was not changed.

## 2026-08-05 - Stay Connected buttons routed to the newsletter form

- Corrected the desktop and compact/mobile global-header Stay Connected buttons so they point to the actual Homepage newsletter signup section (`/#connect`) instead of the footer social-links anchor.
- Corrected the Classes & Events closing Stay Connected CTA to use the same Homepage signup destination.
- Deployed only to Cloudways staging, purged Breeze, and click-tested the header action from the Homepage and an interior page plus the Events CTA. All three land at `/#connect` with the live Forminator signup form visible. Live was not changed.

## 2026-08-05 - Global header restored to approved mockup values

- Todd identified that the desktop WordPress header still used the compressed v1.11 study values instead of the approved Header/Footer Mockup v1.5 code.
- Restored the exact approved desktop measurements and spacing: 92px header, 230px logo, 14px navigation and action labels, the original logo-to-navigation margin, and right-aligned action group.
- Retained Todd's later approval to switch to the menu at 1380px and below rather than compress the desktop navigation. Restored the approved 78px/215px phone header values as well.
- Deployed only to Cloudways staging and purged Breeze. Verified the public page loads `global-chrome.css?ver=1.0.15` and computes the approved desktop values: 92px header, 230px logo, 14px navigation and buttons, 44px minimum button height, approved responsive spacing, and no header overlap or horizontal overflow. Live was not changed.

## 2026-08-05 - Branded favicon added

- The staging clone had no WordPress Site Icon output, and the only unrelated local favicon was a blue placeholder that did not belong to Rebekah's.
- Derived a square favicon from the leaf-and-Source emblem in the established Rebekah's logo, using the site's green palette and warm-white background. Generated 32px browser, 180px Apple touch, and 512px site-icon assets.
- Added a theme-level fallback that outputs the branded icons sitewide only when WordPress does not already have a Site Icon configured, preventing duplicate icon tags if one is configured later.
- Deployed only to Cloudways staging, purged Breeze, and verified the public Homepage emits the 32px, 180px, and 512px icon tags with theme version 1.0.16. Confirmed the browser loaded the 32x32 PNG at its correct natural dimensions. Live was not changed.

## 2026-08-05 - Removed oversized desktop header spacer

- Todd identified the large flexible gap between Wellness Source Blog and the header CTA buttons.
- Replaced the action group's `margin-left: auto` spacer with a fixed 28px gap so the navigation and actions remain visually grouped.
- Deployed only to Cloudways staging, purged Breeze, and verified the public page loads theme version 1.0.17 with a measured 28px nav-to-action gap. Live was not changed.

## 2026-08-05 - Desktop navigation centered with actions right-aligned

- Todd requested that the primary desktop navigation be centered in the full header while the Call To Order and Stay Connected buttons return to the right edge as in the prior approved headers.
- Positioned the desktop navigation independently at the header's horizontal center and restored automatic right alignment for the action group. Added an explicit transform reset inside the existing 1380px compact-menu breakpoint.
- Deployed only to Cloudways staging, purged Breeze, and verified theme version 1.0.18 at the annotated 1514px review width: navigation center delta is 0px, buttons align to the right header padding, clearance is approximately 122px, and there is no horizontal overflow. Verified the desktop layout still has positive clearance immediately above the breakpoint and switches to the compact menu below 1380px. Live was not changed.

## 2026-08-05 - Global header and footer architecture reconfirmed

- Todd confirmed that no alternate site headers or footers should exist; the newly approved components must remain global.
- Audited all WordPress PHP templates. The theme contains exactly one site header in `header.php` and one site footer in `footer.php`, and every page, post, archive, event, practitioner, and 404 entry template uses them through `get_header()` and `get_footer()`.
- Page-level `<header>` elements that remain are semantic content wrappers for heroes or section headings, not alternate site navigation. No staging or live deployment was required.

## 2026-08-05 - Homepage hero moved to the 24-second opening at original quality

- Todd approved the improved clarity and requested that the complete 2:08.46 source video begin at the 24-second mark, then continue through the original ending and from the original beginning back to 24 seconds before repeating.
- Created the circular sequence from the original 1920x1080 H.264 video at the nearest clean source keyframe, 24.315958 seconds. The first source wrap now occurs roughly 1 minute 44 seconds after page load; the complete loop remains approximately 2:08.5.
- Preserved the original encoded video stream without recompression and removed audio, avoiding the added grit seen in the earlier highly compressed delivery copies. Added a matching poster from the new starting frame.
- Retained direct `src`, `autoplay`, `muted`, `loop`, `playsinline` and `preload="auto"` behavior for immediate playback. Uploaded the video and matching poster directly to staging, purged Cloudways Varnish and verified the public browser was actively playing the 1920x1080 video with no media error. Live WordPress remains unchanged.

## 2026-08-05 - Homepage hero version reconfirmed

- Corrected the handoff description, which had mistakenly summarized the superseded 35.96-second v1 edit. Reconfirmed that staging and the local Homepage template reference the final `rebekahs-homepage-hero-loop-24s-v5-original.mp4` edit and its v5 poster—not v1 or v4.
- The active v5 is the complete approximately 2:08 source reordered to open at the clean 24.315958-second keyframe, retain original 1920x1080 video quality without recompression, continue through the end, then play the beginning through 24 seconds before looping. Both active public assets return HTTP 200. Live WordPress remains unchanged.

## 2026-08-05 - Homepage restored to the supplied one-minute video

- Todd clarified that the intended Homepage source is `Front page vidio-1.m4v`, not the later two-minute `Front Page video-2.m4v`. Replaced the two-minute v5 reference on staging with the existing 58.64-second circular edit `rebekahs-homepage-hero-loop-full-v3-hd.mp4` and its matching product-opening poster.
- The active one-minute edit is silent, 1280x720 H.264, approximately 1.52 Mb/s, begins with the finished private-label product footage at about source second 46, continues through the source ending, then plays source 0 through 46 before looping.
- Purged Breeze and verified the normal cached Homepage references the one-minute file and matching poster, contains no reference to the two-minute v5 file, and renders without PHP errors. Live WordPress remains unchanged.

## 2026-08-05 - Practitioner cards restored to approved expandable behavior

- Todd identified that the WordPress practitioner directory had replaced the approved v2.7.0 in-card expansion with arrows linking to separate profile pages. Restored the approved `View full listing +` interaction on all 25 current directory cards.
- Each card now expands in place to show the existing About and Contact details available in the practitioner records, changes to `Hide full listing −` while open, and collapses again without leaving the directory. Existing profile records and URLs were retained, but the directory cards no longer send visitors to them.
- Added one sitewide rule for all current Google Maps directions links so every `Get Directions` action on Contact, Locations, Lapeer, Grand Blanc, Clarkston and Lake Orion opens in a new tab with `noopener noreferrer`.
- Deployed theme version 1.0.19 to Cloudways staging and purged Breeze. Verified all 25 practitioner controls and panels are present, no former profile-arrow links remain, expansion/collapse works with correct ARIA state, existing About/Contact content renders, and all 16 current directions links have the required new-tab attributes. Live WordPress was not changed.
- Follow-up sitewide audit checked the complete theme plus published WordPress pages, posts, products, practitioner records, events, testimonials and venue/organizer content. No additional currently rendered `Get Directions` or Google Maps direction links exist outside those 16 links. The new-tab treatment is global, so any future Google Maps direction link added through the theme will receive the same behavior automatically.

## 2026-08-05 - Location newsletter forms repaired on staging

- Todd reported that the newsletter form was visually broken on the four individual location pages and that a red error appeared at the bottom-right of the viewport.
- Traced the form issue to Forminator's saved flat-design CSS overriding the approved location styling with transparent white-on-white inputs, a narrow button and an incorrect row-grid selector. Added stronger shared form styling so Email and Phone appear side by side on desktop, the selected store and button span the card, controls have readable colors and borders, and the layout collapses to one column on phones.
- Confirmed the red message was Google reCAPTCHA rejecting the temporary Cloudways hostname. The location forms now include the required reCAPTCHA disclosure and hide the badge; staging submissions are intercepted locally so they cannot send email or trigger the invalid-domain challenge. This staging-only block automatically stops applying on the permanent domain, where the reCAPTCHA key/domain authorization must be confirmed before launch.
- Deployed theme version 1.0.21 only to Cloudways staging, purged Breeze, and verified all four location pages show the correct selected store, visible fields, full-width gold button, disclosure and no visible reCAPTCHA error. Live WordPress was not changed.

## 2026-08-05 - Newsletter delivery and integration audit

- Audited the cloned staging newsletter configuration and compared it with the publicly rendered live form without changing either site. The live Homepage uses the same Forminator Newsletter Form ID 313; its current public fields are Email and Phone, while staging adds the approved required preferred-store field for Lapeer, Grand Blanc, Clarkston and Lake Orion.
- The staging clone contains approximately 600 tracked Forminator newsletter submissions. Form 313 stores submissions in WordPress and has one unconditional admin notification routed to `rebekahspureliving@gmail.com`; it has no store-based email routing and no subscriber confirmation/autoresponder notification.
- Forminator reports no connected third-party application globally or on form 313. There is therefore no active Mailchimp, Constant Contact, MailerLite, HubSpot, ActiveCampaign or webhook list integration in the cloned WordPress configuration.
- WP Mail SMTP is configured to use SendGrid for transactional WordPress email delivery. This is delivery infrastructure, not a newsletter contact-list/autoresponder connection, so it does not automatically add Forminator submissions to a marketing audience or segment them by store.
- Pending launch decision: either retain the current workflow (WordPress submissions plus Gmail notification) or obtain the client's authorization for their actual email-marketing account and map Email, Phone and Preferred Store to an audience/custom field or store tags. Reconfirm the live-domain reCAPTCHA authorization and run one controlled end-to-end submission after cutover.

## 2026-08-05 - Contact form configuration audit

- Audited published Forminator Contact Us form ID 1064 on Cloudways staging without submitting or changing it. The public form renders Name, Email, Phone, Subject and How can we help fields plus reCAPTCHA; Name, Email and Phone are required, while Subject and Message are currently optional.
- The form has one unconditional admin notification to `rebekahspureliving@gmail.com`. Its subject identifies the submission ID and form name, and its body includes all submitted fields. Delivery uses the existing SendGrid-backed WP Mail SMTP configuration.
- The notification's Reply-To field is blank, so replying to the notification will not automatically address the visitor's submitted email. There is also no visitor acknowledgement/autoresponder email and no connected third-party integration.
- Successful submissions display the inline message, `Thank you for contacting us, we will be in touch shortly.` AJAX submission and inline validation are enabled; the form includes reCAPTCHA, but Forminator honeypot protection is not enabled.
- Pending before launch: decide whether Message should be required, map Reply-To to the submitted email, optionally add a visitor acknowledgement, confirm the permanent-domain reCAPTCHA authorization, and run a controlled end-to-end delivery test after staging protections are removed during cutover.

## 2026-08-05 - Contact form launch improvements in progress

- Todd approved all recommended Contact Us form improvements on staging. Updated Forminator form 1064 so the admin notification Reply-To uses `{email-1}`, enabled Forminator honeypot protection, and added a `Visitor Confirmation` notification addressed to `{email-1}` with a branded acknowledgement message. Published the form changes; staging delivery remains intentionally blocked, so no email was sent.
- Rechecked the uncached public form markup after publishing. The Message field still rendered without Forminator's required marker even though the editor displayed the Required state, so that item is not being treated as complete yet.
- Browser automation was then blocked by an open Chrome extension panel before the Message setting could be toggled and republished for a clean persistence check. Pending: dismiss the open Chrome extension UI, explicitly toggle Message Optional then Required, publish, verify uncached markup, and confirm the saved notification details. Permanent-domain reCAPTCHA and end-to-end delivery testing remain launch tasks.

## 2026-08-05 - Contact form launch improvements completed on staging

- After Todd dismissed the browser extension panel, explicitly toggled the Message field from Optional back to Required, applied it, republished Forminator form 1064 and purged the staging Breeze cache.
- Verified the freshly loaded Forminator editor's serialized saved configuration rather than relying only on visual controls. It confirms `textarea-1` has `required: 1`, honeypot protection is enabled, and the form has exactly two notifications.
- Confirmed the Admin Email notification still goes to `rebekahspureliving@gmail.com` and now uses `{email-1}` as Reply-To. Confirmed the new Visitor Confirmation notification is addressed to `{email-1}` with the approved acknowledgement subject/body.
- No staging submission or email was sent because the staging guard remains active. The remaining launch-only work is to authorize/verify reCAPTCHA on the permanent domain and perform one controlled end-to-end submission that confirms both the admin and visitor emails arrive.

## 2026-08-05 - Staging WordPress page inventory cleaned

- Reconciled the WordPress Pages inventory against the approved 21-page site scope, including the later-approved Our Team page.
- Moved 11 obsolete build records to the recoverable Trash: the empty `Bricks #1476362` draft, the superseded Meet the Owner page, and nine duplicate Clarkston, Grand Blanc and Lake Orion location pages. No live-site content was changed.
- The staging inventory now contains 25 published pages, zero drafts and one private system page. The published set consists of the 21 approved site pages plus the four WooCommerce-assigned Shop, Cart, Checkout and My Account pages retained for Phase Two.
- Preserved the private Smart Coupons Terms page because WooCommerce Smart Coupons uses it during coupon printing. It is not publicly published.
- Trash now contains 13 recoverable records in total, including the 11 moved during this cleanup and two records that were already in Trash. Nothing was permanently deleted.

## 2026-08-05 - Events freshness and blog pagination audit

- Compared the current live and staging Events Calendar feeds. Live has one nearer upcoming event missing from staging: Shockwave Therapy Pop-Up Clinic on August 19, 2026 from 10:00 AM to 2:30 PM at the Lapeer store. The live record was modified August 5 after the staging clone. Staging currently begins with the November 7 Lapeer health fair and contains three upcoming records versus four on live.
- Diagnosed the blog archive count mismatch Todd identified. The approved static mockup contained one featured article plus eight grid cards, and the dynamic template copied that into a nine-post query by skipping the first queried post only on page one.
- The current dynamic logic leaves an incomplete 3-column row on page one and shows the first queried post both as the feature and again in the grid on later pagination pages. Recommended correction: show the featured article only on page one, query it separately, and paginate nine non-featured grid cards on every archive page.
- No staging content or template changes were made during this diagnostic audit.

## 2026-08-05 - Store review placeholder warnings removed

- Todd authorized treating the four displayed location-page reviews as genuine customer feedback.
- Retained each approved review quote and replaced the visitor-facing internal placeholder/permission warning with the neutral attribution `Rebekah's customer`; no customer names or review platforms were invented.
- Updated the Lapeer, Grand Blanc, Clarkston and Lake Orion templates locally and on Cloudways staging only, then purged the Breeze cache.
- Verified the normal public URL for all four staging store pages contains the new attribution and none contains the former design-placeholder, source-verification or permission warning. Live WordPress was not changed.

## 2026-08-05 - Client staging-review email prepared

- Prepared a client-facing email inviting Rebekah's team to review the Cloudways staging site on desktop, tablet and phone.
- The draft identifies the current review scope as design, copy, store information, practitioners, events, blog content and navigation; it asks for one consolidated list of revisions.
- The draft accurately states that staging is blocked from search indexing but should not be publicly shared. It also explains that final form delivery, social-feed authorization and permanent-domain checks are launch tasks, while onsite WooCommerce remains Phase Two.
- Pending internal items before or during client review: copy the August 19 Shockwave Therapy event added to live after the clone, and correct the blog archive so the featured article appears only on page one with a consistent nine-card grid and no later-page duplication.

## 2026-08-05 - Client staging-review email drafted in Gmail

- Created an unsent Gmail draft to Rebekah at `rebekahspureliving@gmail.com` with the Cloudways staging review link and a concise desktop, tablet and phone review checklist.
- Removed all mention of Phase Two and online ordering at Todd's direction.
- The message explains that the staging URL is blocked from search indexing but should not be shared publicly, and that final launch integrations will be checked after approval.
- Next step: Todd reviews and sends the Gmail draft when ready.

## 2026-08-06 - Domain registration and social authorization clarified

- Verified `rebekahspureliving.com` is registered through GoDaddy and uses GoDaddy's Domains By Proxy privacy service, so the actual individual or company registrant is not publicly disclosed. The domain currently uses Cloudflare nameservers and is registered through December 2029.
- Confirmed the planned Instagram and TikTok live-feed connection is account-owner OAuth through the already-installed Smash Balloon plugins. Rebekah or the social-account owner should authorize each account in a supervised browser session without emailing or sharing either social password.
- Recommended completing authorization on the permanent production domain near launch, because social connection tokens can require reconnection after a staging-to-production domain change. Blue Nova can handle feed configuration and placement after the account owner completes the authorization prompts.
- Todd confirmed Rebekah is technically comfortable and will receive self-service instructions for the Instagram and TikTok authorizations. For GoDaddy, invite `BlueNovaMarketing@gmail.com` with the least-privilege `Domains Only` level, Management Access enabled and Transfer Access disabled; restrict access to `rebekahspureliving.com` if the account contains other domains. Blue Nova may initiate the GoDaddy delegate request instead if the exact name and email address on Rebekah's GoDaddy account are confirmed.
- The domain's current authoritative nameservers are Cloudflare, so GoDaddy access covers registrar settings, ownership/contact review, renewal and nameserver control, but the active DNS records are managed in Cloudflare. Separate Cloudflare access will be needed for a production DNS cutover unless the nameservers are intentionally changed.

## 2026-08-07 - GitHub repository consolidated and organized

- Fast-forwarded the two already-pushed `agent/publish-all-project-updates` commits onto local `main`, preserving the latest staging theme fixes, favicon assets and project notes.
- Removed all tracked local MP4 copies, generated WordPress release ZIPs, FFmpeg/Python dependency folders, temporary FFmpeg archives and the downloaded `yt-dlp` executable. The active WordPress theme continues to load its hero video from the verified staging uploads directory.
- Updated the archived HTML mockups to use the verified staging-hosted hero video rather than a local repository video, and expanded `.gitignore` so future video formats, local media tooling and generated release archives are not accidentally committed.
- Permanently removed the obsolete local `codex/publish-three-pages` branch and the GitHub Desktop auto-stash containing 7,707 dependency files and five unneeded local videos. The consolidation sequence keeps the merged update branch only until the validated `main` push is confirmed, then removes that redundant branch.
## 2026-08-10 - Homepage client feedback reviewed before implementation

- Reviewed the client’s four Homepage requests without changing staging or the theme. Confirmed the approved Homepage scope permits only specifically requested revisions and no redesign.
- Verified the requested Rebekah title phrase is hard-coded in the Homepage story section. The Community & Education cards, Instagram/TikTok previews, and Wellness Source previews are also currently static rather than automatically populated.
- Confirmed the three event cards and three Wellness Source cards do not link individually. All six Instagram previews lead to the Instagram profile, and all six TikTok previews lead to the TikTok profile.
- Recommended preserving the approved layouts while making events pull the next three upcoming event records, Wellness Source pull the latest three published posts, and each generated card link to its corresponding detail page. For social content, prefer authorized dynamic feeds with per-post links; use four network-level boxes only if account authorization or reliable per-post feeds are not practical.
- Pending: walk through and approve the exact Homepage change set with Todd before implementing anything.

## 2026-08-10 - Approved Homepage client revisions implemented on staging

- Todd approved the four Homepage requests and authorized staging implementation with no broader redesign. Updated Rebekah’s title to “Holistic Health Practitioner, Wellness Educator and Business Owner.”
- Replaced the static Community & Education cards with the next three upcoming Events Calendar records. Each card now uses the event image, date, venue, title and excerpt and links to its corresponding event page, so the section updates automatically as events change.
- Replaced the static Wellness Source previews with the latest three published posts. Each article row now updates automatically and the full row links to its corresponding article.
- Because the installed Instagram and TikTok feed plugins are not yet connected to the account-owner OAuth sources, implemented the client-approved four-box fallback linking to the verified TikTok, YouTube, Instagram and Facebook profiles. This removes the misleading repeated video previews.
- Deployed only the Homepage template, its scoped Homepage CSS, and the theme cache version to the Cloudways staging site through the signed-in in-app browser. Verified all three event cards, all three article links, all four social destinations, loaded images, visible responsive layouts and linked destination pages; no PHP errors or 404s were found.
- Pending client-facing next step: review the revised staging Homepage. Account-owner OAuth can still be completed later if the client wants live per-post Instagram and TikTok feeds instead of the four social boxes.

## 2026-08-10 - Homepage social decision corrected to the client’s first choice

- Todd clarified that the client’s first preference should be used whenever it is technically possible. The intended launch solution is therefore real Instagram and TikTok feeds whose displayed items link to their individual posts or videos.
- Restored the original Instagram and TikTok preview-section design on staging and removed the temporary four-network social boxes and their CSS. The title update, automatic upcoming-event cards and automatic latest-article links remain in place.
- Verified staging now shows six Instagram previews and six TikTok previews in the original layouts, with no four-box fallback, while the three dynamic event cards and three dynamic article cards still render correctly.
- Client-facing launch explanation: the individual-post feeds will be connected on the permanent domain after the social-account owner authorizes Instagram and TikTok through the installed WordPress feed plugins. Waiting until the permanent domain avoids an unnecessary staging-to-production reconnection.

### 2026-08-10 — Rebekah's Signature Line page mockup v1.1.0

- Created `signature-line-mockup-v1.1.0.html` as a new, isolated page mockup so concurrent homepage and WordPress work remains untouched.
- Used the approved homepage/Our Story design system and reshaped the supplied long-form copy into a premium editorial journey: origin, collection, sourcing, testing, manufacturing standards, amber-glass packaging, promise and four-store guidance.
- Kept the page non-ecommerce and educational: no prices, cart or checkout; careful product/testing qualifiers and a medical-information disclaimer are included.
- Located and reviewed the client-provided Signature Line photos already in the shared project folder. Integrated the four relevant product images across the collection gallery and amber-glass packaging section, retained the shared hero product image, and intentionally excluded an unrelated family portrait from the product page.
- Working SEO direction: `/rebekahs-signature-line/`, title `Rebekah's Signature Line | Herbal Supplements in Michigan`, and a CollectionPage schema draft. The canonical planning sheet does not yet contain a dedicated Signature Line row, so final URL/navigation placement should be confirmed before WordPress assembly.
- Client-facing next step: review the story flow and final client-photo crops before implementation approval.

### 2026-08-10 — Signature Line editorial and client-verbatim versions

- Preserved `signature-line-mockup-v1.1.0.html` as the editorial adaptation with condensed/reorganized copy, added headings, captions, guidance and SEO elements.
- Created `signature-line-client-verbatim-mockup-v1.0.0.html` as a separate premium-layout option using the client's supplied page content verbatim and in the original sequence. No added visible marketing headings, captions, CTAs, pull quotes, guidance or disclaimers were introduced.
- Verified the normalized visible page text against the client's source attachment with an exact match. Functional accessibility text, image alt text and document metadata remain outside the client-visible marketing copy.
- Both versions use the approved Signature Line product images and exclude `1 (2).jpg` and `victoria photo.jpg`.
- Client-facing next step: compare the editorial and verbatim versions and select which copy treatment should move forward.

## 2026-08-10 - Our Story client copy revisions implemented on staging

- Updated only the three client-requested copy areas on the staging Our Story page: the opening Our Story paragraph, the three-paragraph Meet Rebekah Spencer section and the two-paragraph Rooted Beyond the Stores section.
- Preserved all existing headings, images, buttons, layout and unrelated content; no redesign or broader website changes were made.
- Verified the exact approved copy in both the saved theme source and the rendered staging page. Confirmed the expected paragraph structure, existing page elements and absence of PHP errors.
- At Todd's direction, normalized both paragraphs in the Rooted Beyond the Stores text block to the same 16px size and 26.4px line height; no other page typography or layout was changed.
- Client-facing next step: review the revised Our Story page on staging and continue through the remaining page-by-page revision list.

## 2026-08-10 - Mark and Victoria added to Our Team on staging

- Added Mark as Head Buyer and Victoria as Wellness Coach to the Lapeer section of the Our Team page, using the client-supplied biographies verbatim inside the existing expandable-card pattern.
- Retrieved and reviewed the two authorized shared-folder photos, uploaded them to the staging Media Library and applied focused crops that keep Mark and Victoria visible while preserving their family-photo context.
- Updated the page's existing team-story counts from 10 to 12 and the Lapeer jump-link count from 3 to 5; no other team members or sections were changed.
- Centered the Mark and Victoria second row within the desktop Lapeer grid so the remaining horizontal space is balanced equally on both sides; the first row and tablet/mobile layouts remain unchanged.
- Verified 12 total cards, five Lapeer cards, exact full biography copy, loaded images, working expand/collapse controls and responsive desktop, tablet and mobile layouts with no horizontal overflow or PHP errors.
- The canonical planning sheet currently has no dedicated Our Team row, so this implementation follows Todd's newer explicit client-revision instructions while preserving the approved page design.
- Client-facing next step: review Mark and Victoria's photo crops and card placement on the staging Our Team page.

### 2026-08-10 — Signature Line verbatim visual revision v1.1.0

- Created `signature-line-client-verbatim-mockup-v1.1.0.html` while preserving the prior v1.0.0 review version.
- Kept all client-provided Signature Line body copy unchanged; a normalized comparison against v1.0.0 confirmed the same 8,036 characters. The newsletter is a visually separate sitewide module using the approved four-store signup wording, not an alteration to the page narrative.
- Replaced the abstract leaf treatment with an on-scroll plant-growth animation followed by a gentle sway; visitors who prefer reduced motion see the finished plant without animation.
- Added a darkened farm background to “From Farm to Finished Product” using a new 10-second, muted 1280×720 loop cut from the beginning of the client’s original one-minute farm video. Added a matching poster fallback and preserved strong text contrast.
- Continued using only the approved product imagery. Confirmed that neither `1 (2).jpg` nor `victoria photo.jpg` is referenced.
- Pending implementation detail: connect the newsletter form to the site’s live email platform/list during WordPress assembly; the mockup currently demonstrates the approved layout without submitting data.
- Client-facing next step: review the plant motion, farm-video intensity and newsletter placement before the verbatim design is assembled in WordPress.

### 2026-08-10 — Signature Line plant and field correction v1.2.0

- Superseded the v1.1.0 plant and farm treatments after Todd’s review; retained v1.1.0 only as revision history and created `signature-line-client-verbatim-mockup-v1.2.0.html` for the corrected direction.
- Rebuilt the plant as an animated two-sided SVG: the stem grows from a soil mound, six branches draw outward, eight alternating leaves unfurl, and the full plant continues a gentle sway. The animation remains active in the in-app preview so its movement is visible during review.
- Removed every video element, MP4 reference and video-derived poster from v1.2.0. Deleted the temporary optimized farm loop and poster assets; they can be recreated from the original source if ever needed, but they are no longer part of this direction.
- Generated and saved three original, text-free, logo-free green crop-field background options under `output/signature-line/`. Option 1 is applied temporarily in v1.2.0 while Todd chooses among Options 1–3.
- Reverified that Rebekah’s 8,036-character body copy is unchanged from the verbatim baseline and that neither excluded photo is referenced.
- Client-facing next step: Todd selects Field Option 1, 2 or 3; apply the selected field image and prepare that asset for final web delivery.

### 2026-08-10 — Signature Line field background selected

- Todd selected Field Option 1, the straight green crop rows, as the final background for “From Farm to Finished Product.”
- Confirmed `signature-line-client-verbatim-mockup-v1.2.0.html` references the project-local `output/signature-line/farm-field-option-1.png`; it contains no video or MP4 reference.
- Client-facing next step: retain this field selection when assembling the approved verbatim page in WordPress.

### 2026-08-10 — Signature Line SOURCE plant direction v1.3.0

- Created `signature-line-client-verbatim-mockup-v1.3.0.html` from the approved verbatim v1.2.0 direction.
- Replaced the generic soil mound with a logo-inspired green seed-pod base outlined in gold and labeled `SOURCE` in white, based on Todd’s selected detail from Rebekah’s logo.
- Sequenced the animation so the SOURCE base appears first, the plant grows directly from its center, six branches extend, eight two-sided leaves unfurl and the finished plant continues a gentle living sway.
- Retained selected Field Option 1 and confirmed the page still contains Rebekah’s unchanged 8,036-character body copy, no video and no excluded-photo references.
- Client-facing next step: review the SOURCE base styling and plant-growth sequence as the likely final animation direction.

### 2026-08-10 — Signature Line direct logo treatment v1.4.0

- Created `signature-line-client-verbatim-mockup-v1.4.0.html` following Todd’s clarified direction to use the logo idea directly rather than expanding it.
- Retained the green SOURCE base, shifted `SOURCE` to the left as in Rebekah’s logo and replaced the full branching plant with one slowly growing stem and exactly two leaves: dark green on the left and light green on the right.
- Browser verification confirmed two logo leaves, the completed slow growth sequence, no console errors, no video and no excluded-photo references.
- Rebekah’s 8,036-character verbatim body copy and selected Field Option 1 remain unchanged.
- Client-facing next step: review v1.4.0 as the simplified final SOURCE animation direction.

### 2026-08-10 — Signature Line logo-proportion correction v1.5.0

- Corrected the v1.4.0 animation after acknowledging that its tall stalk and separated leaves did not visually match the compact sprout in Rebekah’s logo.
- Created `signature-line-client-verbatim-mockup-v1.5.0.html` with a short stem, two smaller leaves clustered at the top, the dark leaf angled upper-left, the light leaf angled upper-right and the stem emerging just to the right of the left-shifted `SOURCE` word.
- Kept the slow growth sequence and verified the finished two-leaf sprout visually in the browser with no console errors.
- Rebekah’s verbatim body copy, Field Option 1, newsletter and image exclusions remain unchanged.
- Client-facing next step: review v1.5.0 for direct visual fidelity to the logo sprout.

### 2026-08-10 — Signature Line reference-directed sequence v1.6.0

- Reviewed the client-provided `rebekahs_source_growth_animation.mp4` only as a timing and motion reference; it was not embedded or reused as a page asset.
- Created `signature-line-client-verbatim-mockup-v1.6.0.html` with a clean inline SVG sequence: the green partial-circle crescent appears, `SOURCE` reveals from left to right, the curved stem then grows from the crescent’s right side and two leaves expand at the top.
- Preserved the useful direction of the reference while removing its black video block, ghosted frames and playback dependency.
- Browser verification confirmed the completed word reveal, stem path and two leaves with no console errors, video elements or MP4 references.
- Rebekah’s verbatim body copy, selected Field Option 1, newsletter and excluded-image decisions remain unchanged.
- Client-facing next step: review v1.6.0 for animation timing and the crescent/stem proportions.

## 2026-08-10 - Practitioner guidance section replaced on staging

- Replaced the entire "Before You Contact a Provider" guidance section with the client-approved "Finding the Right Practitioner" heading and five supplied paragraphs.
- Removed the separate prior disclaimer because the new approved copy already includes the verification and non-endorsement language; it now appears once in the intended section.
- Left all practitioner records, profile details, search/filter controls and other page content unchanged. Verified the 25 currently rendered practitioner cards remain present.
- Verified the exact approved wording on staging, no old guidance text, no horizontal overflow and no PHP errors.
- Client-facing next step: review the revised Practitioners section on staging and continue with the next page revision.

## 2026-08-10 - Location-page event links corrected on staging

- Audited the Events sections on the Grand Blanc, Clarkston and Lake Orion location pages against the approved main Classes & Events archive at `/events/`.
- Corrected two Grand Blanc links that pointed to an old individual event and the Locations hub; adjusted the misleading third-link label to "See classes & events."
- Corrected all three Lake Orion event links that pointed to the location-category URL so they now open the main Classes & Events page.
- Confirmed Clarkston's three event links already targeted the correct archive and left its working source unchanged.
- Verified all nine rendered links now resolve to `/events/`, the Classes & Events archive loads correctly, and the three location pages have no PHP errors or horizontal overflow.
- Follow-up audit: checked the fourth location page, Lapeer. Its three full-card event links already pointed to `/events/`; a direct click-through loaded the correct Classes & Events archive, so no Lapeer source change was needed.
- Client-facing next step: review the corrected event links on the three staging location pages and continue with the next page revision.

## 2026-08-10 - In-Store Products client fixes implemented on staging

- Replaced the actual hero stock image containing a visible Curology bottle with a verified photo from inside the Lake Orion store showing stocked supplement and wellness shelves.
- Corrected an initial misidentification by restoring the unchanged Clean Beauty & Body Care category image; the client’s Curology comment applied to the hero, not this category tile.
- Replaced the crooked Private Label category photo with the existing leveled version of the same Rebekah's bottle display; no additional image reconstruction was performed.
- Corrected the Featured Brands grid collision with the site-header `.brand` style and made its tracks shrink safely, so all six brands fit without being clipped.
- Verified the hero contains no Curology image or reference, the replacement images load, all six brand names and descriptors are fully visible at desktop and phone widths, and the page has no horizontal overflow or PHP errors.
- Client-facing next step: review the corrected hero, leveled Private Label image and Featured Brands section on staging.

## 2026-08-10 - In-Store Products duplicate-image correction

- Client identified that the Herbs & Natural Remedies card reused the exact same Victoria education photo shown again in the lower guidance section. Kept the Victoria photo in the guidance section and replaced the Herbs card with a different verified Lake Orion store/product-shelf image.
- Also found that the corrected hero reused the Practitioner Brands card image. Replaced the hero with a separate wide Lake Orion store interior so every photographic placement on the page now uses a unique source image.
- Preserved the approved page structure, copy, product fixes and responsive Featured Brands correction; no unrelated page changes were made.
- Client-facing next step: review the unique hero and Herbs & Natural Remedies images on staging.

## 2026-08-10 - Signature Line SOURCE animation v1.7.0

- Created `signature-line-client-verbatim-mockup-v1.7.0.html` from the client-verbatim direction and implemented Todd's precise SOURCE animation corrections.
- Removed the internal white highlight, flattened the top edge of the green SOURCE base so it reads as ground, and separated the plant paths so no stem is visible before the growth sequence begins.
- The animation now reveals `SOURCE` from left to right, grows the stem and two branches, opens exactly three leaves, makes the top leaf approximately twice the size of the bottom leaf, holds the completed plant for one second and then loops.
- Browser QA verified the clean opening state, completed three-leaf state, loop reset and absence of console errors. Rebekah's 8,036-character verbatim body copy remains an exact match to v1.0.0; Field Option 1 remains selected and no video, MP4 or excluded-photo reference is present.
- Client-facing next step: review v1.7.0 as the corrected SOURCE animation direction before WordPress assembly.

## 2026-08-10 - Footer social links expanded on staging

- Added YouTube and Rebekah's Lapeer Facebook page to the global footer beside the existing Instagram and TikTok icons.
- Used the official destinations already linked from the current live site: `https://www.youtube.com/user/happygirl732/videos` and `https://www.facebook.com/RebekahsLapeer`.
- Kept the existing footer layout and icon treatment; no other footer content or site sections were changed.
- Client-facing next step: review the four footer social icons on desktop and phone.

## 2026-08-10 - Contact Us delivery reverified for launch

- Rechecked published Forminator Contact Us form ID 1064. Its unconditional Admin Email notification is addressed exactly to `rebekahspureliving@gmail.com`, includes all submitted fields, and uses the visitor's email field as Reply-To.
- Confirmed WP Mail SMTP is configured with SendGrid and has an API key saved; no form layout, fields, notification recipients or unrelated site settings were changed during this check.
- Confirmed the apparent staging failure is intentional: the Rebekah's Staging Guard blocks Forminator delivery and short-circuits all `wp_mail` calls on the Cloudways staging hostname. The guard automatically stops applying when the site is on the permanent domain.
- Client-facing launch step: after the permanent-domain switch, submit one real Contact Us test, verify it arrives at `rebekahspureliving@gmail.com`, verify the visitor confirmation arrives, and check spam/email logs if either message is delayed. Keep the staging guard enabled until launch.

## 2026-08-10 - Homepage Practitioner Brands quick link corrected

- Changed only the `preparePathway()` override in `assets/js/pages/home-sections.js` so the homepage Practitioner Brands quick-link retains `#practitioners` instead of opening the separate `/practitioners/` directory.
- Applied the same one-line correction to the Cloudways staging theme. JavaScript syntax validation passed.
- Browser verification confirmed the quick-link keeps the visitor on `/`, changes the hash to `#practitioners`, and scrolls the Practitioner Brands section to the viewport. The intentional header and footer Practitioners navigation links remain routed to `/practitioners/`.
- Client-facing next step: no further action is required for this link; continue with the next requested revision.

## 2026-08-10 - Signature Line navigation and homepage-link decision

- Confirmed the approved Signature Line direction exists in the project as the v1.9.0 client-verbatim mockup, with the planned URL `/rebekahs-signature-line/`.
- Checked Cloudways staging and WordPress Pages: that URL currently returns the custom 404, and no published, draft or private WordPress page matching "Signature" exists yet. The homepage CTA was therefore not changed to a broken destination.
- Recommended publishing the new page at `/rebekahs-signature-line/`, then changing the homepage "Explore the Signature Line" CTA to that URL.
- Recommended against adding another crowded top-level header item. Preferred navigation treatment: convert `In-Store Products` into a dropdown containing `Browse In-Store Products` and `Rebekah's Signature Line`, and add the Signature Line page beneath Explore in the footer. Leave the separate Practitioners and other navigation routes unchanged.
- Client-facing next step: Todd approves the dropdown/footer recommendation and authorizes WordPress assembly of the approved Signature Line page; then publish it before activating the new homepage link.

## 2026-08-10 - Signature Line simplified safeguards animation v1.8.0

- Preserved `signature-line-client-verbatim-mockup-v1.7.0.html` unchanged as the approved SOURCE-growth backup and created the separate experimental `signature-line-client-verbatim-mockup-v1.8.0.html`.
- Replaced the SOURCE plant treatment in the Heavy-Metal and Pesticide Testing section with a simpler botanical safeguards emblem: a green shield arrives, one leaf appears, a check mark draws and a subtle gold testing sweep repeats through the shield while the two background rings breathe gently.
- Removed the initial decorative `QUALITY CHECKED` wording so the visual adds no new client-facing claim or body copy.
- Browser QA verified the entrance, completed emblem, moving scan and absence of console errors. Rebekah's 8,036-character verbatim body copy remains an exact match to v1.0.0; the page still contains no video, MP4 or excluded-photo reference.
- Client-facing next step: compare the calm v1.8.0 safeguards emblem against the preserved v1.7.0 SOURCE-growth backup and choose the preferred direction.

## 2026-08-10 - Signature Line safeguards emblem refinement v1.9.0

- Preserved v1.8.0 as a backup and created `signature-line-client-verbatim-mockup-v1.9.0.html` with Todd's three requested emblem refinements.
- Removed the vein line from the leaf, removed the animated horizontal testing sweep entirely, and replaced the lower downward-chevron treatment with one clearly proportioned check mark drawn directly across the centered leaf.
- Browser QA confirmed zero leaf-vein and scan-line elements, one centered animated check, and no console errors.
- Rebekah's 8,036-character verbatim body copy remains an exact match to v1.0.0; no video, MP4 or excluded-photo reference was introduced.
- Client-facing next step: review v1.9.0 as the cleaned final safeguards-emblem direction.

## 2026-08-10 - Signature Line enlarged looping emblem v1.10.0

- Preserved v1.9.0 and created `signature-line-client-verbatim-mockup-v1.10.0.html` with Todd's requested sizing and loop refinements.
- Enlarged the complete shield emblem by exactly 25%, redrew the leaf as a more natural asymmetric pointed shape, and increased the centered check to an 11px bold stroke while scaling it with the emblem.
- The shield, leaf and check entrance now loops after the 1.74-second draw sequence completes and holds the completed emblem for exactly 1.5 seconds before restarting.
- Browser QA verified the 1.25 scale transform, revised leaf path, bold centered check, loop reset and absence of console errors.
- Rebekah's 8,036-character verbatim body copy remains an exact match to v1.0.0; no video, MP4 or excluded-photo reference was introduced.
- Client-facing next step: review v1.10.0 as the near-final safeguards-emblem animation.

## 2026-08-10 - Signature Line reference-shaped leaf and check v1.11.0

- Preserved v1.10.0 and created `signature-line-client-verbatim-mockup-v1.11.0.html` from Todd's supplied leaf and check references.
- Enlarged the leaf another exact 25%, mirrored its pointed blade toward the shield's upper-left corner and kept the leaf completely vein-free.
- Replaced the rounded stroked check with a larger solid angular cream check shaped like the supplied reference while retaining the approved page colors.
- Retimed the entrance to complete in 1.67 seconds and retained the exact 1.5-second completed-state pause before the animation loops.
- Browser QA verified the leaf scale and direction, solid fill-only check, zero vein elements, loop reset and absence of console errors.
- Rebekah's 8,036-character verbatim body copy remains an exact match to v1.0.0; no video, MP4 or excluded-photo reference was introduced.
- Client-facing next step: review v1.11.0 as the refined reference-matched emblem direction.

## 2026-08-10 - Signature Line handwritten check refinement v1.12.0

- Preserved v1.11.0 and created `signature-line-client-verbatim-mockup-v1.12.0.html` for Todd's handwritten-check and leaf-shape refinement.
- Redrew the leaf as a broad upper-left-pointing blade with a small lower-right stem to better match the supplied reference while retaining the prior 25% scale and keeping it completely vein-free.
- Moved the check into the lower half of the shield so it no longer covers the leaf. The check remains solid cream with a pointed upper tip, but is now revealed through an animated mask that draws the short descending stroke and then the long rising stroke like a person writing it.
- Retimed the entrance to finish at 1.9 seconds and retained the exact 1.5-second completed-state pause before looping.
- Browser QA captured the check mid-draw, verified the final separated leaf/check placement and found no console errors.
- Rebekah's 8,036-character verbatim body copy remains an exact match to v1.0.0; no video, MP4 or excluded-photo reference was introduced.
- Client-facing next step: review v1.12.0 as the refined hand-drawn emblem direction.

## 2026-08-10 - Signature Line exact reference-shape correction v1.13.0

- Preserved v1.12.0 and created `signature-line-client-verbatim-mockup-v1.13.0.html` after Todd clarified that the supplied reference silhouettes should be followed directly and that the check's upper end should be blunt, not pointed.
- Rebuilt the vein-free leaf as the reference's broad oval blade with a sharp upper-left tip and lower-right stem, then positioned it cleanly in the shield's upper portion.
- Reproportioned the solid cream check to match the supplied heavy angular reference, used a blunt slanted upper cap, and separated it beneath the leaf while preserving the established handwritten mask reveal.
- Kept the existing 1.9-second draw sequence and exact 1.5-second completed-state pause unchanged.
- Browser QA captured the check mid-write and complete, verified the separated reference-shaped artwork and found no console errors.
- Rebekah's 8,036-character verbatim body copy remains an exact match to v1.0.0; no video, MP4 or excluded-photo reference was introduced.
- Client-facing next step: review v1.13.0 as the corrected reference-matched emblem.

## 2026-08-10 - Signature Line supplied glossy artwork direction v1.14.0

- Preserved v1.13.0 unchanged and created `signature-line-client-verbatim-mockup-v1.14.0.html` from Todd's exact ChatGPT-supplied glossy shield, leaf and checkmark reference.
- Used the supplied artwork as the edit target rather than a loose style reference. Removed every water droplet, retained the glossy emerald shield, realistic curved leaf, layered warm-gold rim and oversized ivory check, and harmonized the colors with the approved Rebekah's website palette.
- Replaced the prior flat SVG approximation with the finished rendered artwork in `output/signature-line/quality-shield-brand-v1.png`. Matched the visual container to the artwork's native 2:3 portrait ratio so no pasted-in rectangular canvas edge remains.
- Added a restrained looping scale/fade entrance and gentle settling motion, followed by the established 1.5-second completed-state pause. No scan line, extra symbol, added text or new claim was introduced.
- Browser QA confirmed the 1024x1536 artwork loads, the animation is active, and the page has no console errors. Rebekah's client-verbatim body copy remains an exact match to v1.0.0; no video, MP4 or excluded-photo reference was introduced.
- Client-facing next step: review v1.14.0 as the exact supplied-artwork direction and confirm it as the safeguards-section visual for WordPress assembly.

## 2026-08-10 - Signature Line client-photo safeguards replacement v1.15.0

- Preserved v1.14.0 unchanged as the generated-shield backup and created `signature-line-client-verbatim-mockup-v1.15.0.html` in response to Todd's request to replace the Heavy-Metal and Pesticide Testing image.
- Selected the unused client-provided `private-label-bottles-leveled-v1 (1).png` from the shared folder and copied it into the project as `output/signature-line/client-photo-05-private-label-bottles.png`; file-hash verification confirms the project copy is identical to the client's original.
- Reworked the visual container from the shield's portrait format to the photo's native 4:3 ratio, retained the restrained scale/fade entrance and 1.5-second loop pause, and added descriptive alternative text.
- Browser QA confirmed the 1448x1086 client photo loads cleanly, the animation remains active and there are no console errors. Rebekah's client-verbatim body copy remains an exact match to v1.0.0; no video, MP4, `1 (2).jpg` or Victoria photo was introduced.
- Client-facing next step: review v1.15.0 as the authentic client-photo direction for the safeguards section.

## 2026-08-10 - Signature Line duplicate-image correction v1.16.0

- Corrected v1.15.0 after verifying that its safeguards photo was byte-for-byte identical to the hero image already used at the top of the same page.
- Created `signature-line-client-verbatim-mockup-v1.16.0.html` and replaced the duplicate with a genuinely new 4:3 laboratory image showing gloved hands pipetting a botanical sample beside dried plant material and a fresh leaf.
- The new image contains no supplement bottles, product packaging, shields, checkmarks, farms, faces, text, labels or logos, so it does not repeat any other page visual and directly supports the Heavy-Metal and Pesticide Testing content.
- Automated file-hash verification confirms that all six images used in v1.16.0 are unique. Browser QA confirmed the 1448x1086 image loads, the restrained animation remains active and there are no console errors.
- Rebekah's client-verbatim body copy remains an exact match to v1.0.0, and the excluded `1 (2).jpg` and Victoria photo remain unused.
- Client-facing next step: review v1.16.0 as the corrected no-duplicate-image version.

## 2026-08-10 - Signature Line client microscope photo v1.17.0

- Preserved v1.16.0 and created `signature-line-client-verbatim-mockup-v1.17.0.html` using Todd's newly supplied `lab.jpg` exactly as provided, copied into the project as `output/signature-line/client-lab-microscope.jpg`.
- Removed the prior fade, pop-out and scale animation completely. The image remains at a fixed size in a clean 3:2 frame and uses only a slow 12-second left-to-right crop pan for restrained movement.
- Removed the earlier JavaScript animation loop because the new movement is handled directly on the image without changing its dimensions.
- File-hash verification confirms the project asset matches the client's source, all six page images remain unique, and Rebekah's client-verbatim body copy remains unchanged.
- Browser QA confirmed the 847x491 photo loads, the `lab-camera-pan` animation is active, the computed transform is `none`, and there are no console errors.
- Client-facing next step: review v1.17.0 as the client-photo laboratory direction with fixed-size motion.

## 2026-08-10 - Signature Line fully static microscope photo v1.18.0

- Corrected v1.17.0 after Todd clarified that the supplied microscope photo should have no movement at all.
- Created `signature-line-client-verbatim-mockup-v1.18.0.html` and removed the crop pan and its keyframes completely. The image now has no animation, fade, pop, zoom, scaling, translation or object-position movement.
- Browser QA verified computed `animation-name: none`, `animation-duration: 0s`, `transform: none`, centered object position and no console errors.
- The exact client-supplied `lab.jpg` remains in use, and the client-verbatim page copy remains unchanged.
- Client-facing next step: review v1.18.0 as the fully static client-photo version.

## 2026-08-10 - Signature Line v1.18.0 approved

- Todd approved `signature-line-client-verbatim-mockup-v1.18.0.html` as the selected Signature Line direction.
- Approved safeguards treatment: the exact client-supplied microscope `lab.jpg`, displayed as a completely static image with no animation, movement, pan, fade, pop, zoom or scaling.
- Use v1.18.0 as the source version for future WordPress assembly; keep earlier mockups only as historical backups.

## 2026-08-10 - Signature Line v1.18.0 assembled on staging

- Assembled and published the approved v1.18.0 design at `https://wordpress-1651482-6565113.cloudwaysapps.com/rebekahs-signature-line/` using the approved client-verbatim copy and imagery.
- Updated only the homepage `Explore the Signature Line` button so it now opens the new Signature Line page instead of the general In-Store Products page; browser testing confirmed the button reaches the correct page.
- Retained the live Forminator newsletter form 313 in the page's newsletter section rather than the mockup-only placeholder form.
- Front-end QA confirmed HTTP 200, the correct SEO title and H1, all page images loading, no horizontal desktop overflow, and the microscope image remaining completely static (`animation-name: none`, `animation-duration: 0s`, `transform: none`, centered object position).
- Did not change the header or footer navigation. Recommended next-step navigation treatment remains approval-gated: make `In-Store Products` a dropdown containing `Browse In-Store Products` and `Rebekah's Signature Line`, and optionally add the Signature Line page under the footer's Explore links.

## 2026-08-10 - In-Store Products navigation dropdown approved and added

- Todd approved converting the existing `In-Store Products` header link into a dropdown.
- Added exactly two dropdown destinations: `Browse In-Store Products` linking to `/in-store-products/` and `Rebekah's Signature Line` linking to `/rebekahs-signature-line/`.
- Left every other header navigation item unchanged and did not add a separate top-level Signature Line item.
- In-app browser QA confirmed the dropdown opens with the correct expanded state, both destinations load their intended pages, the desktop header retains ample spacing and the page has no horizontal overflow.

## 2026-08-10 - Signature Line added to footer Explore links

- Todd approved adding `Rebekah's Signature Line` under the footer's `Explore` section.
- Added the link immediately after `In-Store Products` in both the standard footer Explore list and the tablet footer Explore panel, pointing to `/rebekahs-signature-line/`.
- Left every other footer link and footer section unchanged.
- In-app browser QA confirmed the visible desktop link renders cleanly, opens the correct Signature Line page, the tablet counterpart is present, and the page has no horizontal overflow.

## 2026-08-10 - Client email revision audit and response preparation

- Reviewed all four emails received from Rebekah since Sunday in the connected Gmail thread: the consolidated nine-part revision list, the Practitioner Brands link follow-up, the Monday inline Private Label product photo and her thank-you response.
- Verified the Monday inline image is byte-for-byte identical to `output/signature-line/client-photo-01.jpg`, which is already published on the approved Signature Line page; it was not missed.
- Reconciled every requested revision against the staging implementation and client notes, then ran a fresh HTTP/content spot-check across Home, Our Story, Our Team, Practitioners, all four location pages, In-Store Products, Signature Line and Contact Us. Every checked page returned HTTP 200 and contained its expected revised content; the In-Store Products page contained no Curology reference.
- No omitted client revision was found. The only remaining launch-only work is authorizing the Instagram and TikTok accounts on the permanent domain so feed items can link to individual posts, plus performing the controlled live Contact Us delivery test after the staging email guard no longer applies.
- Prepared a client-facing reply summarizing each completed item, explaining the two launch-only integrations and requesting final staging review/approval before cutover.
## 2026-08-10 - Detailed client completion email redrafted

- Reworked the client-facing completion reply to follow Rebekah's August 9 revision email line by line across all nine sections, plus the later Practitioner Brands quick-link correction.
- The revised reply explains what was changed, how the dynamic homepage sections work, and which two items require final live-domain authorization/testing: individual Instagram/TikTok feed links and a real Contact Us delivery test.
- Confirmed that no email has been sent or saved as a Gmail draft; Todd will review the detailed wording first.
## 2026-08-10 - Detailed completion reply saved as Gmail draft

- Saved the full line-by-line client response as an unsent reply draft in the existing Rebekah website review email thread.
- The draft follows the established “Your feedback / What we did” format and includes all nine revision sections, the Practitioner Brands follow-up, and the two launch-only tasks.
- Todd will review, edit if needed, and send the draft himself; Codex did not send it.

## 2026-08-10 - Project time audit

- Audited the available Rebekah project task history from July 15 through August 10, cross-checked phase boundaries against Git commit dates, and excluded guardian/subagent duplicate transcripts, overnight gaps, and other long idle periods.
- Used a conservative active-session method: merged overlapping work and counted short review/direction gaps of up to 15 minutes so parallel tasks were not double-counted.
- Best documented estimates aligned to the requested billing phases: pre-build planning 5.0 hours; all page design and mockups through approval 29.75 hours; WordPress build and technical assembly 6.5 hours; post-build revisions and QA 8.5 hours. Combined documented total: 49.75 hours.
- The 29.75-hour mockup figure consists of about 19.0 hours of initial page design/mockup production plus 10.75 hours of pre-build mockup revision rounds. It includes the complete Rebekah's Signature Line design process through approved v1.18.0 and the related ChatGPT image/logo-animation chats. Planning plus all mockups totals 34.75 hours.
- Audit limitation: the July 13 client meeting and any earlier ChatGPT Work planning completed before the initial HTML was brought into Codex are referenced in the history but do not have usable duration records in the accessible task logs, so they are not included in the 49.75-hour total.
- Client-facing next step: use the four rounded line items for time reporting and add any separately documented July 13 meeting or off-app planning time if it needs to be billed.

## 2026-08-11 - Hosting ownership, migration and website-care plan

- Prepared `HOSTING-AND-WEBSITE-CARE-PLAN.md` for the client hosting conversation, recommending migration from GoDaddy hosting to Cloudways and a client-owned, Blue Nova-managed arrangement.
- Defined three transparent choices: client-owned Cloudways plus a Blue Nova care plan; a single bundled hosting/management invoice from Blue Nova with a future transfer promise; or client-managed hosting with hourly support.
- Proposed discussion pricing of $275/month for the client-owned care plan, $349/month for the bundled plan, and $150/hour for on-demand or overage work. Cloudways/add-on estimates must be reconfirmed at selection because platform pricing and usage can change.
- Defined the two-hour monthly scope to include tested WordPress/plugin/theme updates, backups and health checks, malware monitoring/support coordination, cache/performance checks, minor fixes and a short monthly report. New pages, ecommerce implementation, major features, paid licenses and substantial incident remediation remain separately quoted.
- Clarified that the client should always own the domain. The safer launch path is to keep registration/DNS at GoDaddy temporarily, point only the web records to Cloudways, preserve all email-related DNS, keep the old host active for 7–14 days, and handle any registrar transfer after stabilization.
- Described Cloudways protection accurately as malware protection rather than antivirus and avoided promising immunity from compromise. Blue Nova's positive hacked-site support experience may be presented as agency experience, not a universal guarantee.
- Client-facing next step: review/approve the proposed pricing, then present the three choices and obtain decisions on hosting ownership/billing, monthly care, malware protection and the later domain-registrar preference.

## 2026-08-11 - Website-care price revised to six-month billing

- Todd set the website-management price at $117 per month, paid every six months. The authoritative management invoice is therefore $702 per six-month service period; this supersedes the earlier $275/month and $349/month discussion figures.
- Updated the hosting plan so the recommended client-owned option bills Rebekah's $702 every six months for Blue Nova management while Rebekah's pays Cloudways directly.
- For the single-invoice option, the $702 six-month management fee remains visible and Cloudways hosting/add-ons are passed through separately at actual cost rather than hidden inside an all-inclusive price.
- Preserved the limit as six distinct monthly allowances of up to two hours each. Hours are not pooled or rolled over, and work beyond a month's two-hour allowance requires approval at the separately stated hourly rate.
- Added proposed six-month billing terms covering advance invoicing, renewal notice, extra-work billing and the service agreement details that still need to be finalized.

## 2026-08-11 - Monthly managed backup added to care plan

- Todd confirmed that Blue Nova will provide one managed website backup each month as part of the $117/month care plan.
- Updated the plan to include monthly backup completion/restoration verification and an additional restore point before material or higher-risk maintenance changes when appropriate.
- Cloudways backup storage remains a hosting expense, and a separate long-term off-platform archive is not included unless expressly added.
- Added an ecommerce safeguard: the monthly backup schedule is for the present informational site and must be increased before active ecommerce begins so orders, customers and inventory are protected more frequently.

## 2026-08-11 - Blue Nova-hosted option repriced

- Todd clarified that the $117/month rate applies only to management when the client pays Cloudways directly; it is too low when Blue Nova assumes the hosting and Malware Protection expense.
- Repriced the fully managed Blue Nova-hosted option at $197/month, invoiced as $1,182 every six months.
- The bundled price includes Cloudways hosting, Malware Protection, one managed backup each month, website management and up to two hours of work in each individual service month.
- Included up to $70/month of normal Cloudways infrastructure, malware and backup-storage cost. Server scaling, abnormal usage or new paid services require client approval and a pricing adjustment.
- The management-only client-owned option remains $117/month, invoiced as $702 every six months.

## 2026-08-11 - Additional-page fee and hosting email drafted

- Reconciled the original 14–17-page planning range with the expanded final build and the client's written acknowledgement that the later-requested Rebekah's Signature Line page would require additional compensation.
- Todd set the client-facing charge for the later-requested Signature Line page at a reduced friend rate of **$450**. The email does not show a higher reference price or include an itemized defense of the page work.
- Updated the existing unsent Gmail draft and retitled it `Website and Next Steps`. The draft notes the original 14–17-page planning range, explains that the site grew beyond it, and presents $450 as the additional Signature Line page charge.
- The revised draft asks Rebekah to accept the newly resent Blue Nova GoDaddy access invitation and requests a Zoom call to discuss hosting, ongoing website management, SEO and ecommerce plans.
- No email was sent. Pending: Todd reviews and sends the Gmail draft when ready.

## 2026-08-11 - Website next-steps draft restored

- Rechecked Gmail after Todd was unsure whether the approved client email had been sent. Confirmed there was no sent message with the subject `Website and Next Steps` and the earlier draft was no longer present.
- Recreated `Website and Next Steps` as a new unsent Gmail draft to Rebekah using the approved wording: a $450 friend-rate charge for the additional Signature Line page, acceptance of the newly resent GoDaddy access invitation, and a Zoom call about hosting, ongoing website management, SEO and ecommerce plans.
- No email was sent. Client-facing next step: Todd reviews the restored draft and sends it when ready.

## 2026-08-11 - Pre-update backup check initiated

- Treated the approved Cloudways staging build as the protected baseline and made no plugin, theme, content or live-site changes.
- Verified the staging dashboard currently reports seven plugin updates: Breeze, CookieYes, Elementor, Feeds for TikTok, SEOPress PRO, Smash Balloon Instagram Feed and WooPayments.
- Confirmed WPvivid remains active, but a new full backup reproduced the previously documented stall on the large `hzj_frmt_form_views` Forminator analytics table. Canceled the incomplete job cleanly after confirming it produced no completed backup record.
- Opened the proven Cloudways application-level backup workflow for application `6565113`, but browser control is paused because another Chrome extension panel is open. No new Cloudways backup has been claimed as complete yet.
- Pending: dismiss the Chrome extension panel, create a fresh Cloudways files-and-database on-demand restore point, verify it appears in the Restore selector with the current timestamp, and only then plan/test the seven plugin updates. The public live site remains untouched.

## 2026-08-11 - Current Cloudways pre-update restore point completed

- Created a fresh Cloudways application-level on-demand backup for Rebekah's application `6565113`; Cloudways states this backup includes both application files and the database.
- Verified completion in the application's Restore selector. The new restore point is listed as `11th August, 2026, 16:18:26 UTC (Latest)` and is available to the restore workflow.
- No WordPress plugins, themes, content, settings or public live-site data were changed. The seven staging plugin updates remain pending for a separately tested maintenance pass.
- Client-facing next step: confirm whether to proceed with the seven staging plugin updates, using this Cloudways restore point as the rollback baseline and checking the approved site after each higher-risk update group.

## 2026-08-11 - Staging plugin maintenance completed

- Updated the Cloudways staging plugins against the verified `11th August, 2026, 16:18:26 UTC` restore point. Final versions: Breeze 2.5.13, CookieYes 3.5.4 (remains inactive), Elementor 4.2.2, Smash Balloon TikTok Feed 1.7.0, SEOPress PRO 10.1.1, Smash Balloon Instagram Feed 6.12.0 and WooPayments 11.0.0. Wordfence had already completed its queued update to 9.0.0.
- Verified all eight inspected plugin rows show no remaining update state. WordPress core 7.0.3 and the installed themes were already current.
- Ran the required WooCommerce Smart Coupons database update; WooCommerce reported the database update completed successfully.
- Purged the Breeze staging cache after maintenance.
- Rechecked the Homepage, Contact Us, Events, Lake Orion location and Rebekah's Signature Line pages. Each returned the expected page title and H1, forms remained present, and no website-origin browser console errors were found.
- Opened the WooPayments admin overview after the 11.0.0 update. It loaded without website-origin errors and remains connected in staging Safe Mode; no production payment connection, live data or public site was altered.
- Client-facing next step: continue using staging for final approval and launch preparation. Keep the verified Cloudways restore point until the updated build has completed the normal stabilization window.

## 2026-08-11 - Cloudways capacity and ecommerce readiness review

- Reviewed the live Cloudways server and application monitoring for server `1651482` and Rebekah's application `6565113`; no hosting settings, services or plan were changed.
- Current plan is DigitalOcean Basic Premium at 2 GB RAM, 1 vCPU, 50 GB disk and 2 TB bandwidth for $28/month. The next 4 GB Premium step provides 2 vCPU, 50 GB disk and 4 TB bandwidth for $54/month, a $26/month increase.
- Current server snapshot showed 1.07 GB used out of 1.92 GB RAM, 11.87-11.89 GB used out of 49.01 GB disk, and 5,037 MB of month-to-date bandwidth. The Rebekah application itself showed approximately 152-160 MB memory, 2.09 GB disk and 1.54 GB bandwidth.
- The one-month free-memory chart stayed approximately in the 928-1,227 MB range, averaging about 1.08 GB free. RAM is therefore not currently near exhaustion and the present informational/staging workload has substantial memory headroom.
- Cloudways' CPU surfaces were inconsistent: the summary briefly reported 87-92% server-wide CPU and flagged the server as problematic, while the application row remained about 1-6% CPU and the detailed historical chart showed much lighter usage. Treat this as a yellow flag requiring another normal-period trend check, not proof of sustained application saturation.
- The one-month auto-healing chart was not completely clean: it showed one earlier four-restart interval and two isolated one-restart intervals. These should be correlated with backups, deployments or maintenance before attributing them to ordinary visitor traffic.
- Recommendation: keep the 2 GB plan during remaining Phase One work and Phase Two development/testing, but budget the 4 GB / 2-vCPU plan for public ecommerce launch. A very small, low-traffic WooCommerce launch may technically fit on 2 GB, but uncached cart/checkout requests, scheduled actions, payments, inventory/catalog work and promotions reduce the present safety margin; the 4 GB plan is the safer production baseline.
- Upgrade trigger if launch remains on 2 GB temporarily: sustained free memory below roughly 300-500 MB, repeated auto-healing restarts outside maintenance windows, CPU repeatedly above 80%, checkout/admin slowness, or an ecommerce load test that drives PHP/MySQL queues or response times materially upward.
- Ecommerce planning follow-up: define product count, variation count, expected traffic/order peaks, search/filtering, inventory/POS integrations and payment/shipping plugins, then run a realistic uncached checkout/load test before launch. Increase backup frequency beyond the current monthly managed-backup schedule before accepting live orders.

## 2026-08-15 - iContact and Textedly signup automation review

- Reviewed the website signup architecture and the connected Textedly account without changing service settings or transmitting test subscriber data. The approved theme currently reuses Forminator newsletter form 313 across the Homepage, four location pages and Signature Line page; the saved workflow stores entries in WordPress and emails the administrator, with no third-party integration connected.
- Confirmed Textedly's Zapier and WordPress integrations are already active. Textedly's WordPress integration is Zapier-based rather than a separate direct form connector, and Zapier supports the required Textedly `Add New Subscriber` action with phone, keyword, first name, last name and email mapping.
- Confirmed the primary active Textedly keyword is `HEALTHY123`, with approximately 23,000 contacts. Five older `HEALTHY` keywords also exist; their intended store/campaign ownership should be confirmed before any store-based routing is created.
- Recommended retaining Forminator as the single public form and using a Forminator webhook/Zapier workflow to fan out authorized submissions to iContact and Textedly. The form should use separate, unchecked email and SMS consent choices; a phone number alone must never enroll someone in Textedly. SMS disclosure should state the sender, recurring message purpose/frequency, message/data rates, STOP/HELP instructions, no-purchase condition and links to Privacy Policy and Terms.
- Recommended retaining consent evidence with the submission: channel choice, disclosure version, timestamp, source page and preferred store. For Textedly, route only explicit SMS opt-ins and map them to the approved keyword; for iContact, create/update the contact and subscribe only explicit email opt-ins to the approved list.
- No iContact, Textedly, Zapier or WordPress production settings were changed. Pending: confirm whether Rebekah's already has a Zapier account, identify the iContact list and any store custom fields, confirm whether `HEALTHY123` is the correct website/SMS destination or whether store-specific keywords are desired, then build and test the automation on staging with a controlled test contact before launch.

### Zapier ownership clarification

- Todd confirmed that he does not use Zapier; the activated Textedly Zapier/WordPress app likely belongs to a previous designer and should not be treated as an available client-owned automation account.
- Textedly's current help documentation routes custom automation through Zapier, while its public feature page labels the standalone API/SMS gateway as coming soon. Do not build against the visible Zapier token or an undocumented endpoint.
- Revised no-Zapier recommendation: use the official iContact WordPress form for email enrollment and Textedly's active native Web Forms feature for compliant SMS enrollment, presented together in one branded Stay Connected section but submitted independently. This removes manual Textedly entry without introducing a new automation subscription.
- A true single-submit fan-out without Zapier should wait until Textedly confirms supported direct API access for this account. Pending alternative: ask Textedly support whether this account has production REST API access for adding an opted-in subscriber to a keyword, including endpoint, authentication, consent requirements and welcome-message behavior.

### Canonical signup-form decision

- Todd confirmed that the website should use the same signup form on every page and that the immediate concern is backend automation, not revising the form's wording.
- Retain Forminator form 313 as the single canonical form already reused across the Homepage, location pages and Signature Line page. Do not implement separate iContact and Textedly public forms or page-specific form variants.
- Attach any approved iContact/Textedly routing once at the canonical form/integration layer so all placements inherit it. The remaining technical dependency is a supported Textedly destination: a client-owned automation account or confirmed direct API access; without one of those, WordPress cannot automatically fan one submission out to both services.
- Follow-up verification found no supported Forminator-to-Textedly plugin, webhook destination or published subscriber REST endpoint. Textedly's official WordPress integration explicitly runs through Zapier, and its current product page still marks the standalone API/SMS gateway as coming soon. Therefore, the direct no-Zapier implementation path is to obtain account-specific API access from Textedly support and then build one small WordPress connector on Forminator form 313; if Textedly cannot provide that access, a client-owned automation service is required for automatic fan-out.
- With Todd's approval, sent Textedly support a product request asking whether this account has production REST API access to add an opted-in Forminator subscriber to keyword `HEALTHY123`, requesting the supported endpoint, authentication documentation and confirmation of welcome-message behavior. Textedly created a product-assistance ticket and stated that a product specialist normally replies within a few hours through the in-account conversation and the account email. No account settings or subscriber records were changed.
- Sent a detailed follow-up explaining the intended one-submit architecture: canonical Forminator form 313 remains reused sitewide; WordPress routes email subscribers to iContact and explicit SMS opt-ins to Textedly `HEALTHY123`; no Zapier account or second Textedly webform should be required. Requested the production endpoint/method, authentication, sample payload, upsert behavior, preferred-store field/tag support, response codes, rate limits, test environment, welcome-message behavior and protection against re-enrolling previously opted-out numbers.

## 2026-08-15 - Production DNS cutover staged

- Inventoried all 27 authoritative Cloudflare DNS records for `rebekahspureliving.com` and confirmed the Cloudways application target is `159.203.135.212`.
- Confirmed the apex and `www` domains were already attached to Cloudways, then promoted `rebekahspureliving.com` from an alias to the primary application domain. Cloudways initiated its WordPress database search-and-replace and now presents the production domain as the application URL.
- Updated the still-authoritative Cloudflare apex A record from the prior host to `159.203.135.212`. The request now reaches Cloudways, but Cloudflare correctly reports a temporary 526 until an origin certificate is installed.
- Entered Rebekah's delegated GoDaddy account and created DNS template `Rebekahs Pure Living DNS Cutover 2026-08-15` (template ID `201432148`). The template contains 26 validated records: the Cloudways A record; all 16 Cloudflare CNAMEs including `www`; the three Zoho MX records and their original priorities; SPF, DMARC, Zoho DKIM, iContact verification and both Google verification TXT records.
- One Cloudflare record was intentionally not duplicated because it is structurally invalid at GoDaddy: a TXT record at `icontact.k1._domainkey` conflicts with the valid CNAME at that same hostname. The iContact DKIM CNAME is preserved, as is the separate `icontact.k2._domainkey` CNAME.
- Pending client-safe cutover steps: install a Let's Encrypt certificate for the apex and `www`, apply the GoDaddy template (which warns that it replaces all non-system GoDaddy zone records), switch the registrar from the two Cloudflare nameservers to GoDaddy defaults, then verify the website, redirects, certificate, Zoho MX/SPF/DKIM/DMARC and the remaining service records after propagation.

### Cutover progress after approval

- Installed a Let's Encrypt certificate in Cloudways for the apex and `www`; Cloudways reports expiration on November 13, 2026 and auto-renewal is enabled.
- Verified the production homepage loads normally over HTTPS with the approved content and that `www` redirects to `https://rebekahspureliving.com/`.
- Attempted to apply the verified GoDaddy DNS template before the nameserver change, but GoDaddy correctly refused because the zone still uses external Cloudflare nameservers.
- Started the switch to GoDaddy default nameservers. GoDaddy sent a six-digit identity-verification code to the account holder's masked phone number and is waiting for human verification. No nameserver change has completed yet; Cloudflare remains authoritative and the website remains available.
- Immediate next step: Todd or the account holder enters the GoDaddy verification code in the open in-app browser and confirms when complete. Codex can then finish the nameserver switch, apply template `201432148`, and run the full web and Zoho email DNS verification.

### Nameserver verification completed; template verification pending

- After Todd completed the first GoDaddy SMS verification, GoDaddy changed the registrar to its default nameservers: `ns73.domaincontrol.com` and `ns74.domaincontrol.com`.
- Immediately initiated application of verified template `201432148`. GoDaddy required a second SMS identity verification specifically for the destructive template replacement, and the verification dialog is open in the in-app browser.
- The template has not yet been applied. Because nameserver propagation has started, the second code should be completed promptly so all Cloudways web and Zoho mail records become authoritative at GoDaddy before resolvers switch away from Cloudflare.

### Production DNS cutover completed and verified

- Completed GoDaddy identity verification and applied DNS template `201432148` to the live GoDaddy zone. GoDaddy now shows 29 total records: the 26 migrated records plus its required two NS records and SOA record.
- Confirmed the GoDaddy authoritative servers `ns73.domaincontrol.com` and `ns74.domaincontrol.com` answer with the Cloudways apex A record `159.203.135.212`, all three Zoho MX records at priorities 10/20/50, the Zoho SPF record, the full Zoho DKIM key, and the original DMARC quarantine policy/reporting address.
- Audited all three GoDaddy DNS record pages. The remaining Cloudflare CNAMEs and TXT verification records are present, including `www`, email client aliases, both iContact DKIM CNAMEs, the iContact verification token, and both Google site-verification tokens. The structurally conflicting TXT value at `icontact.k1._domainkey` remains intentionally omitted in favor of the valid CNAME.
- Rechecked the still-cached Cloudflare authoritative path during propagation and confirmed its Zoho MX, SPF, DKIM and DMARC values match GoDaddy. Google and Cloudflare public recursive resolvers still returned the old Cloudflare nameservers at the time of verification, which is expected until the cached NS TTL expires; both DNS paths are therefore safe for email during the transition.
- Verified the Cloudways origin directly over HTTPS: the apex returns HTTP 200 with title `Michigan Health & Nutrition Stores | Rebekah's`, and `www` returns HTTP 301 to the apex. The Let's Encrypt certificate remains installed with auto-renewal enabled.
- Client-facing next step: allow registrar/nameserver propagation to complete. Recheck public NS, web and mail resolution after the old six-hour resolver cache has expired and again within 24-48 hours; no additional client action is currently required.

## 2026-08-15 - Post-launch website, chatbot and client-test review

- Rechecked the complete Phase One production page set plus Rebekah's Signature Line, the `www` hostname and the legacy Meet the Owner URL. The homepage, Our Story, Locations, all four store pages, In-Store Products, Practitioners, Contact, Privacy, Refunds, Terms, Disclaimer and Signature Line returned HTTP 200 with the expected page titles and H1s. `www` resolved to the apex correctly.
- The Events and Blog archives initially failed to return within a 45-second cold-cache check. Their server-side requests continued generating, Breeze/Cloudways cached the completed pages, and isolated follow-up checks then returned HTTP 200 in about half a second. This is a performance watch item: public cached delivery is currently fast, but uncached PHP/archive generation and public sitemap/REST requests were abnormally slow and should be diagnosed separately rather than treated as a DNS failure.
- Found one live URL defect: `/meet-the-owner/` returns the branded 404 page instead of the planned 301 redirect to `/our-story/`. Pending: restore and verify that redirect.
- Confirmed the visible chat bubble is injected sitewide by the active custom plugin `Inbox Pro Web Chat Widget` version 1.0.0, which loads the GoHighLevel/LeadConnector webchat client from `cdn.apigateway.co`. The plugin was already documented as active in the read-only pre-design inventory dated July 20, 2026, so it was not installed or activated during the August 15 launch work. WordPress does not expose an activation user/time for this plugin and no dedicated activity-log plugin is present, so the person who originally activated it cannot be attributed reliably from current evidence.
- Created an unsent Gmail draft to `rebekahspureliving@gmail.com` with subject `Website Is Live — Please Review`. The draft asks Rebekah to test the primary pages, navigation, phone/map/external links, contact and newsletter forms, inbound/outbound business email, apex/`www`, Wi-Fi/cellular and desktop/mobile; it recommends checks now, again in about six hours and tomorrow, and asks whether the chat bubble should remain active. No email was sent.
- Pending client/owner decisions and follow-up: decide whether to leave the chatbot active; restore the Meet the Owner redirect; investigate cold-cache PHP/archive/sitemap performance; complete real human contact/newsletter delivery tests and Zoho incoming/outgoing email tests during the 48-hour DNS observation period.

## 2026-08-15 - Production CPU saturation incident diagnosis

- Investigated the post-cutover outage read-only. Public DNS is correct: GoDaddy is authoritative and the apex resolves consistently to Cloudways `159.203.135.212`; the incident is not nameserver propagation or an email-DNS failure.
- Cloudways reported the server as `Problematic`, with CPU at 100% and 1.56 GB of 1.92 GB RAM in use. All core services were running. MySQL showed only short sleeping connections and no application cron was currently running.
- The PHP monitor showed ten `php-fpm` workers for the Rebekah application stuck for approximately 14-36 minutes. Each worker was consuming about 7-8% CPU and 9-10% memory, exhausting the 1-vCPU/2-GB server's PHP capacity and explaining why uncached pages and `/wp-admin/` would not load.
- Cloudways slow-page and error logs identify the main trigger as outside crawlers requesting expensive legacy Events Calendar archive, tag, date and iCal URLs. Examples include deep `/events/list/page/...` paths and `tribe-bar-date`/`ical=1` queries taking 60-139 seconds before upstream timeout. WP-Cron and Breeze cache-check requests also became slow after the worker pool was saturated.
- The Varnish/server purge did not originate the incident because the outage and long-lived PHP processes predated it, but clearing the page cache removed the remaining cached protection and forced more requests through the exhausted PHP pool. Do not purge or repeatedly refresh while the incident is active.
- Immediate recommended recovery: restart PHP-FPM only to terminate the wedged workers, then verify CPU, RAM, homepage, Events and `/wp-admin/`. MySQL does not currently need a restart. This state-changing recovery was not performed during the diagnostic review and awaits Todd's approval.
- Stabilization follow-up after recovery: restrict or redirect pathological legacy Events Calendar URL patterns (especially deep date/tag/list and iCal permutations), review The Events Calendar/Event Tickets and SEOPress interaction, review WP-Cron behavior, and consider temporarily disabling the Events Calendar stack if load immediately returns. Because the GoDaddy cutover removed Cloudflare's proxy/cache/WAF layer, origin-side bot/rate-limit protection is now more important. The 2-GB/1-vCPU plan also remains below the previously recommended public ecommerce baseline.

### PHP-FPM recovery attempt and immediate recurrence

- With Todd's approval, restarted PHP-FPM only through Cloudways. Cloudways confirmed the restart; no other service, DNS, SSL, email or database setting was changed.
- Immediately after restart, application memory dropped from roughly 2 GB to 128 MB and application CPU dropped from 67.61% to 4.80%. The public cached homepage returned HTTP 200 in 0.78 seconds.
- The PHP monitor initially showed only one new worker at 0% CPU and 1.17% memory, confirming the original wedged workers were terminated. However, uncached `/wp-json/` and `/wp-login.php` checks still failed to respond within 20-25 seconds, and within about two minutes the PHP pool again filled with ten workers consuming 7-10% CPU and about 9.5-11.4% memory each.
- The refreshed slow-page report again showed Events Calendar query permutations (`tribe-bar-date`, `post_type=tribe_events`, day/past views and iCal exports) taking approximately 55-75 seconds, alongside delayed WP-Cron and Action Scheduler requests. The PHP restart was therefore a successful temporary recovery but not sufficient containment.
- Immediate decision required: either temporarily deactivate The Events Calendar/Event Tickets stack and restart PHP-FPM again, or add narrow origin web rules that reject the pathological legacy Events Calendar archive/query patterns before PHP. Do not perform another standalone purge or restart without containment because the pool is immediately re-saturated.

## 2026-08-15 - Client social-feed connection handoff prepared

- Confirmed the installed social-feed stack is Smash Balloon Instagram Feed 6.12.0 and Smash Balloon TikTok Feed 1.7.0. Both plugins are already installed; the remaining account-owner step is OAuth authorization for Instagram `@rebekahs_health_and_nutrition` and TikTok `@rebekahspureliving` on the permanent production domain.
- Created `REBEKAH-SOCIAL-FEED-CONNECTION-INSTRUCTIONS.md` as a concise self-service handoff. Rebekah connects each owned account and saves `Homepage Instagram` and `Homepage TikTok`; she is explicitly told not to share passwords, embed a shortcode, or edit the custom Homepage.
- Blue Nova's follow-up is to replace the static Homepage previews with the saved feeds, preserve the approved six-item responsive presentation, confirm individual-post links, and test scheduled refresh, WordPress cron and Breeze/Cloudways caching. New items update on a schedule rather than instantly, and TikTok pinned order may differ because pinned placement is not exposed through its feed API.
- Screenshots are optional for the first pass. Rebekah can send one safe confirmation screenshot from each plugin's All Feeds screen; create annotated step screenshots only if her live interface differs or authorization fails.

## 2026-08-15 - Social-feed launch handoff and automatic replacement prepared

- Saved a new unsent Gmail draft to Rebekah with subject `Connect Instagram and TikTok to the Website`. It gives concise WordPress/OAuth steps for Instagram `@rebekahs_health_and_nutrition` and TikTok `@rebekahspureliving`, tells her not to share passwords or edit/embed on the Homepage, and asks her to confirm when the two named feeds are saved. No email was sent.
- Prepared theme version 1.0.31 so the existing Instagram and TikTok sections render Smash Balloon feed ID 1 for each platform. Each platform switches independently from its approved six-card fallback to real posts only after live feed items are detected; this prevents empty public sections before authorization, avoids duplicate placeholder/live cards afterward, and retains a safe fallback if a source later expires.
- Added dedicated component PHP, CSS and JavaScript for the live-feed handoff, responsive 6/3/2-column presentation, six-item limit and duplicate plugin-header/button suppression. Updated the theme build script so future mockup regeneration continues using the component instead of restoring hard-coded social sections.
- Verified the component JavaScript parses successfully, the theme-builder replacement pattern matches exactly once, and the live production Homepage currently returns its expected H1 with six Instagram and six TikTok fallback cards and no connected live items. WordPress admin is accessible again.
- Packaged `tmp/social-feed-deploy/rebekahs-2026-v1.0.31.zip` and opened the authenticated production Theme Upload screen. The public theme replacement has not yet been submitted because it is the final production-changing action; after approval, upload/replace the active theme, purge cache once, and verify both fallback sections before Rebekah authorizes the sources.

### Social-feed deployment intentionally held

- Todd directed that the Instagram/TikTok feed-ready theme deployment must wait while SEOPress configuration and website-performance adjustments are underway.
- Keep the version 1.0.31 package staged locally and keep the Rebekah connection email as an unsent Gmail draft. Do not deploy the theme update or send the client instructions until Todd confirms the SEO/performance work is complete and the site is ready for the social-feed change.

## 2026-08-15 - Events Calendar incident containment completed

- With Todd's approval, temporarily deactivated the active `Event Tickets` and `The Events Calendar` plugins in production. No Events Calendar Pro or Event Tickets Plus add-on was active. No DNS, email, SSL, WooCommerce, MySQL or other plugin settings were changed.
- Restarted PHP-FPM after deactivation to clear the already-wedged worker pool. Follow-up PHP monitoring showed only two short-lived workers rather than the prior ten workers stuck for 40-2,000+ seconds, and the pool did not immediately refill during the observation period.
- External verification after containment: the homepage returned HTTP 200 in about 0.53 seconds, `/wp-login.php` returned 200 in about 1.36 seconds, `/wp-json/` returned 200 in about 2.17 seconds, and the pathological legacy Events Calendar test URL returned 404 in about 0.52 seconds instead of timing out after 60+ seconds.
- Cloudways' top-level server card still displayed `Problematic` and 100% CPU at its 18:00 UTC update, but the current application row showed 0.00 CPU and approximately 20 MB application memory. Treat the server card as a lagging interval/peak indicator; the current request and process checks show that containment is working.
- The public `/events/` archive may continue to return an old cached HTTP 200 temporarily, but Events Calendar functionality is intentionally unavailable while the two plugins are inactive and may change or return 404 after cache expiry. Do not purge Varnish/Breeze while observing recovery.
- Pending remediation before reactivation: test the Events Calendar stack on staging, update/audit its configuration and interaction with SEOPress, and add narrow origin-side bot/rate-limit rules for abusive deep event archive/date/iCal patterns. Consider restoring Cloudflare proxy protection or using equivalent Cloudways/WAF controls; retain the earlier 4 GB / 2-vCPU production recommendation.

## 2026-08-15 - Production plugin provenance and admin-speed triage

- Compared Todd's production plugin screenshots with the July 20 pre-redesign inventory. Admin and Site Enhancements, Event Tickets, Extendify, Import and export users and customers, Site Kit by Google, The Events Calendar, Wordfence Security and WordPress Importer were already active before Blue Nova began the redesign; they were not installed for the August launch.
- WPvivid is the exception among the highlighted plugins: Blue Nova installed it on the Cloudways staging clone on August 3 for a portable backup attempt. The attempt was abandoned after repeated failures and no verified WPvivid archive was produced; the active plugin subsequently came across with the staging promotion.
- The new theme intentionally integrated existing Events Calendar data for its events archive and homepage cards, so The Events Calendar became a retained dependency even though it predated the redesign. Event Tickets itself is not required for ordinary event display unless RSVP/ticket features are being used. Both remain temporarily inactive after the production saturation incident.
- The Blue Nova Staging Guard is safe to leave active on production: its code exits immediately unless the hostname contains `cloudwaysapps.com` or WordPress explicitly declares the environment as staging. It is not suppressing live email, forms or payments on `rebekahspureliving.com` and is not a meaningful admin-performance suspect.
- Current Cloudways application row showed approximately 0.00 application CPU and 11 MB application memory, while the top-level 100% CPU/Problematic card remained a lagging server interval indicator. The public site remained responsive. Admin-only slowness is therefore more consistent with WordPress dashboard plugin hooks/external API calls and the unusually large active-plugin stack than with continuing application saturation at the moment checked.
- Highest-confidence low-value admin-load candidates for a controlled deactivation test are unconfigured Site Kit, Extendify, WordPress Importer, Import and export users and customers, and the now-unused WPvivid plugin. Wordfence predates the redesign and can add admin/database overhead, but it is currently useful origin security after moving off Cloudflare proxying; tune/test it separately rather than removing it casually. Do not deactivate ASE until its enabled modules are inventoried.
- Pending: authenticate to the live dashboard, keep the dashboard tab available to Todd, record baseline timings for Dashboard and Plugins, and run any approved one-at-a-time deactivation test with rollback and timing verification. No plugin state was changed during this triage.

## 2026-08-15 - Production plugin cleanup and admin-speed recovery

- With Todd's approval, performed a reversible production plugin cleanup. No plugin files, settings, products, customers, orders, coupons or other database records were deleted.
- Deactivated five low-value admin plugins: unconfigured Site Kit by Google, Extendify WordPress Onboarding and AI Assistant, WordPress Importer, Import and export users and customers, and WPvivid Backup Plugin. WPvivid had been installed by Blue Nova on staging for the abandoned portable-backup attempt; the other four predated the redesign.
- The first cleanup pass reduced active plugins from 26 to 21 but did not resolve admin latency; fresh Dashboard and Plugins navigations still measured approximately 6.8 and 7.5 seconds respectively.
- Based on the authoritative Phase One scope (no onsite cart or checkout; WooCommerce retained only for Phase Two), deactivated the complete dormant commerce stack in dependency order: Google for WooCommerce, PDF Invoices & Packing Slips for WooCommerce, Pledged Plugins PCI Gateway for NMI and WooCommerce, WooCommerce Smart Coupons, WooCommerce Tax, WooCommerce.com Update Manager, WooPayments and WooCommerce core. All Phase Two commerce data remains preserved for future reactivation.
- After commerce deactivation, active plugins dropped to 13. Fresh WordPress Dashboard and Plugins navigations improved to approximately 2.2 and 2.7 seconds, a roughly 65-67% reduction in measured admin load time.
- Final retained active set: ASE, ACF, Blue Nova Staging Guard, Breeze, Elementor, Forminator, Inbox Pro Web Chat Widget, SEOPress, SEOPress PRO, Smash Balloon Instagram Feed, Smash Balloon TikTok Feed, Wordfence and WP Mail SMTP. Events Calendar and Event Tickets remain temporarily inactive from the earlier incident containment.
- Public regression checks passed: homepage, In-Store Products, Contact, Blog, Fullscript, Designs for Health, LifeWave, WordPress login and REST API all returned HTTP 200. Warm homepage checks returned in approximately 0.51-0.59 seconds; the other tested pages/endpoints returned in approximately 1.0-2.1 seconds.
- The authenticated Plugins dashboard tab was left open for Todd. Recommended next step: observe Cloudways/PHP behavior without purging caches, then audit ASE modules and Wordfence scan/live-traffic settings only if admin latency rises again. Reactivate the WooCommerce stack in a controlled staging-tested sequence for Phase Two.

## 2026-08-15 - Events Calendar safely restored with traffic guard

- Todd confirmed that Rebekah's does not need RSVP or ticket-sale functionality. Event Tickets therefore remains inactive; The Events Calendar is the only calendar-stack plugin restored.
- Created and installed `Blue Nova Events Traffic Guard` v1.0.0. The guard rejects known worker-exhaustion routes before The Events Calendar performs its expensive archive query: deep event pagination at page 3 or higher, iCal/outlook-iCal exports, and `tribe-bar-date` requests more than 366 days old or more than 730 days in the future. Normal Events, individual event, page-2 and useful-date requests remain available.
- Verified the guard before and after calendar reactivation. The previously pathological deep/old-date and iCal requests now return HTTP 410 in approximately 0.43-0.52 seconds with an `X-Blue-Nova-Events-Guard` reason header. A legitimate November 7, 2026 date view returned HTTP 200 in approximately 2.48 seconds, page 2 returned 200 in approximately 1.77 seconds, and page 3 returned 410 in approximately 0.44 seconds.
- Reactivated The Events Calendar only. The public Events archive returned HTTP 200 in approximately 1.64 seconds, a real individual event returned 200 in approximately 1.72 seconds, the homepage remained about 0.59 seconds, and `/wp-json/` returned 200 in approximately 1.81 seconds. Visual browser verification confirmed the approved Events page title/H1 and three current event links.
- Post-reactivation Cloudways PHP monitoring remained stable: the original ten long-running workers did not return. Successive observations showed two short-lived workers, then one worker at 12.94% CPU, and finally one worker at 0% CPU / 0.72% memory / 5 seconds duration.
- WordPress admin remained materially improved after the earlier cleanup. With the calendar restored, the Plugins page measured approximately 1.69 seconds; repeated Dashboard measurements varied between approximately 1.74 and 4.79 seconds, still below the prior 6.8-7.5-second state.
- Installation recovery note: the first ZIP attempt produced an inactive nested duplicate that WordPress could list but not activate. A corrected single-file package installed and activated successfully. One inactive duplicate `Blue Nova Events Traffic Guard` entry remains in the Plugins list and should be deleted after Todd confirms the cleanup deletion; the active guard must remain.
- The authenticated Plugins dashboard remains open for Todd. Do not reactivate Event Tickets or purge Breeze/Varnish during the immediate observation period.

## 2026-08-15 - Events traffic guard duplicate removed

- With Todd's explicit approval, deleted only the broken inactive nested duplicate of `Blue Nova Events Traffic Guard`. The working active copy remains installed and active; the Plugins inventory changed from 36 total / 21 inactive to 35 total / 20 inactive.
- Confirmed The Events Calendar remains active and Event Tickets remains inactive, consistent with the decision not to use RSVP or ticket-sale functionality.
- Final public checks passed: `/events/` returned HTTP 200, while an iCal export request returned HTTP 410 with `X-Blue-Nova-Events-Guard: calendar-export`, confirming the guard still contains the abusive request pattern after cleanup.
- Left the authenticated WordPress Plugins tab open and signed in for Todd.

## 2026-08-15 - Object Cache Pro restored and Breeze audited

- Established a performance baseline before the cache review. PageSpeed lab results were mobile 37 (FCP 5.2s, LCP 17.9s, TBT 900ms, CLS 0.018) and desktop 58 (FCP 0.8s, LCP 1.8s, TBT 570ms, CLS 0.204). CrUX did not return usable field data. The primary remaining frontend issue is the roughly 20 MB homepage payload, including an estimated 4.3-4.7 MB of image-delivery savings and about 0.9 MB of unused JavaScript; this is separate from server-side caching.
- Verified Cloudways Redis and Varnish services are running. Cloudways Application Settings already had Object Cache Pro enabled, and WordPress had a valid Object Cache Pro drop-in, but the main plugin was inactive after the staging-to-production migration. Activated the main plugin to bring WordPress back in sync with the Cloudways setting.
- Confirmed Object Cache Pro reports `Connected`, a `Valid` drop-in, and approximately 61 MB used of its 196 MB Redis cache allowance. No competing object-cache plugin is installed.
- Audited Breeze 2.5.13. Its safe production configuration was already correct: page cache, gzip, browser cache, 1440-minute TTL, preload-on-hover, and automatic Varnish purging are enabled; mobile-specific cache, logged-in-user cache, cache warmup, file combining/minification/delay, and CDN are disabled. No speculative Breeze settings were changed because Cloudways recommends staging tests for CSS/JS optimization and cache warmup is inappropriate immediately after the recent worker-saturation incident.
- Plugin activation caused the expected one-time cache clear. The cold homepage and Events archive took approximately 6.64s and 4.10s, then returned to HTTP 200 Varnish/Breeze hits at approximately 0.56s and 0.53s. An uncached REST request returned HTTP 200 in 1.75s. The Events guard still returned HTTP 410 with its calendar-export header.
- Authenticated admin content remained responsive: Dashboard DOM content was ready in approximately 1.52s and Plugins in approximately 2.86s. Cloudways' final PHP monitor showed no currently running PHP processes, so the earlier ten-worker saturation did not recur.
- Left the authenticated WordPress Plugins tab open and signed in. Recommended next performance task is a separate homepage asset optimization pass (images, LCP discovery, unused JavaScript and desktop layout shift), tested on staging; do not enable Breeze's one-click/minify/delay presets directly on production.

## 2026-08-15 - SEOPress production cleanup and redirect repair

- Audited SEOPress Free/PRO against the canonical Phase One inventory and live output. Verified homepage, location, Events and practitioner URLs have valid titles, descriptions, canonicals and index/follow directives; public homepage, Lapeer, Events and Practitioners checks returned HTTP 200 in approximately 0.51-0.57 seconds.
- Added Medical Practitioners and Events to the XML sitemap while leaving internal Elementor elements and testimonials excluded. A fresh sitemap response now includes `medical-practicioner-sitemap1.xml` and `tribe_events-sitemap1.xml` alongside posts, pages and categories.
- Created and externally verified eight exact 301 redirects for deleted duplicate location URLs: Lake Orion `-3/-4` to `/locations/lake-orion/`, Grand Blanc `-2/-3/-4` to `/locations/grand-blanc/`, and Clarkston `-2/-3/-4` to `/locations/clarkston/`. Dismissed the now-resolved SEOPress redirect notices.
- Removed duplicate homepage Organization schema by setting SEOPress Knowledge Graph entity type to None; retained SEOPress WebSite schema and the approved theme Organization entity used by all four location schemas. Fresh output contains one WebSite and one Organization entity.
- Disabled nine unused/unconfigured SEOPress modules: Dublin Core, WooCommerce SEO, Structured Data Types, Instant Indexing, Local Business, Analytics, Site Audit, AI and Google Search Console. Retained Titles & Metas, Image SEO/Advanced, Social Networks, Redirections, robots.txt, llms.txt, Breadcrumbs and XML/HTML Sitemaps. Confirmed the saved dashboard state is 8 of 21 modules enabled.
- Set the approved Rebekah brand portrait already bundled with the live theme as the default Open Graph and X share image, enabled Open Graph fallback for X, and selected `summary_large_image`. Fresh public output now includes both `og:image` and `twitter:image`.
- Applied `follow, noindex` to the dormant WooCommerce utility pages `/shop/`, `/cart/`, `/checkout/` and `/my-account/`, consistent with the Phase One no-onsite-ecommerce scope. Verified all four are excluded from the page sitemap while the real `/our-team/` page remains indexed and included.
- The SEOPress toolbar may still show eight generic recommendations. These are not eight unresolved failures: they include false-positive global-description warnings despite valid live output, Google Business/Search Console prompts, RSS/posts-per-page suggestions, a rating request and the setup-wizard promotion. No GA4 or Search Console service-account credentials were available, so those integrations remain intentionally off pending the correct account/property connection.
- Left the authenticated WordPress Plugins tab open and signed in. No cache purge was performed during this cleanup.
- Dismissed The Events Calendar's expected slug-conflict notice on the Plugins screen. No slug was changed: the approved `/events/` route intentionally uses the calendar archive in place of the legacy "Classes & Events" WordPress page.
- Dismissed Elementor's "Go Pro, Go Limitless" conversion banner on the Plugins screen and confirmed it remained gone after reloading. This was a promotional Elementor Pro upsell, not a site error or required upgrade; Elementor itself remains active.

## 2026-08-15 - Inactive production plugins removed

- Removed nine confirmed-unused inactive plugins from production: Cloudways Partner Hub, CookieYes, Event Tickets, Extendify, Import and export users and customers, Site Kit by Google, WordPress Importer, WPCode Lite, and WPvivid Backup Plugin. This reduced the installed inventory from 36 to 27 plugins and the inactive inventory from 20 to 11.
- Preserved the complete inactive WooCommerce/Phase Two stack: WooCommerce, Google for WooCommerce, PDF Invoices & Packing Slips, Pledged Plugins NMI Gateway, Smart Coupons, WooCommerce Tax, WooCommerce.com Update Manager, and WooPayments. Also retained inactive Simple CAPTCHA with Cloudflare Turnstile for planned form protection.
- Preserved all 16 active production plugins, including The Events Calendar, both SEOPress plugins, Wordfence, Object Cache Pro, Breeze, Forminator, the social feeds, and the working Blue Nova guard plugins.
- Corrected the earlier duplicate-cleanup record: WordPress visually reports the two inactive nested guard copies as deleted, but both reappear after a full reload, indicating a filesystem ownership/permission issue. They remain inactive and do not affect runtime behavior; the working single-copy Events Traffic Guard and Blue Nova Staging Guard remain active.
- Post-cleanup public regression checks passed: homepage and Events returned HTTP 200 in about 0.55-0.56 seconds, Contact returned HTTP 200 in about 3.12 seconds, and the protected Events iCal route returned HTTP 410 with `X-Blue-Nova-Events-Guard: calendar-export`.
- Left the authenticated WordPress Plugins tab open and signed in for Todd. No Breeze/Varnish purge or active-plugin change was made.

## 2026-08-15 - Client Events and Blog Posts guide created

- Created an editable Word guide and matching client-ready PDF for Rebecca covering the live WordPress workflows for creating, previewing, publishing, scheduling, updating and canceling Events and Blog Posts.
- Used screenshots from the current production dashboard and documented the actual Events fields, America/Detroit time zone, four location categories, venues/organizers, featured images, post blocks, categories, excerpts and basic SEOPress checks.
- Included 16:9 / 1600 x 900 featured-image guidance, mobile/desktop preview checks, publishing checklists and clear boundaries around themes, plugins, caching, DNS, WooCommerce and sitewide SEO.
- Explicitly excluded social-feed instructions because Todd already has that guide. Also documented that Event Tickets/RSVP functionality is intentionally not part of the current setup and should not be installed from the calendar upsell.
- The final PDF is eight pages and passed full visual page review. The editable DOCX passed the document accessibility audit with zero findings; LibreOffice was unavailable for DOCX raster QA, so the visually verified PDF is the preferred client-facing version.
- No public content was created or published. Closed the temporary documentation tab and left the authenticated Plugins tab open and signed in for Todd.

## 2026-08-15 - Rebecca content-guide email drafted

- Created an unsent Gmail draft to Rebekah at her established business email with the subject `Website Content Guide – Events & Blog Posts`.
- Attached the visually verified PDF guide and explained that it is a reference for creating, previewing, publishing, scheduling, updating and canceling Events and Blog Posts.
- Clarified that 16:9 landscape is the preferred featured-image format for consistent event/blog cards, not a requirement for every image inside an article.
- Noted that Instagram/TikTok instructions are separate, advised saving uncertain changes as Drafts, and reinforced that themes, plugins, caching, WooCommerce and sitewide settings should not be changed without assistance.
- The draft remains unsent for Todd to review and send.

## 2026-08-15 - Production Instagram and TikTok feed handoff deployed

- Published the Homepage social-feed handoff on production after Todd confirmed the SEOPress/performance work was finished. The normal WordPress theme ZIP upload exceeded the site's server upload limit, so the reviewed Homepage template was updated through the authenticated WordPress theme editor without replacing unrelated theme files.
- Both installed Smash Balloon integrations are present in the live template: Instagram uses feed ID 1 and TikTok uses feed ID 1. Each platform switches independently to its live posts only after that plugin renders real feed items; connecting one account will not hide the other platform's fallback.
- Until Rebekah completes each plugin's connection/setup wizard, the approved six Instagram cards and six TikTok cards remain visible. The empty live containers and plugin setup messages remain hidden, so no placeholder section was removed prematurely.
- Purged Breeze and Varnish once after publishing. Rechecked the normal public Homepage: HTTP rendering remained healthy with exactly one H1, two feed switches, six Instagram fallback cards, six TikTok fallback cards and no WordPress critical error.
- QA watch: the signed-in admin view logs a TikTok plugin MutationObserver warning while feed ID 1 does not exist. This does not disrupt the visible fallback; recheck the console after Rebekah creates/connects TikTok feed 1 and confirm the warning clears when real posts render.
- Rebekah's Gmail message remains an unsent draft addressed to `rebekahspureliving@gmail.com` with instructions for both Instagram and TikTok. Todd should review and send it when ready; after Rebekah replies that both connections are complete, verify six current posts/videos render automatically and that the two fallbacks hide independently.

## 2026-08-15 - Rebekah social-feed email draft body corrected

- Corrected the existing unsent Gmail draft after Todd found that only its recipient and subject had saved. Added the complete client-facing body with separate Instagram and TikTok connection steps, the exact account handles, the WordPress login link, Feed 1 guidance, automatic-update behavior, placeholder behavior, password-safety guidance and the post-connection verification request.
- Read the saved Gmail message back from the mailbox and confirmed it now contains a non-empty HTML body. The recipient remains `rebekahspureliving@gmail.com`, the subject remains `Connect Instagram and TikTok to the Website`, and the draft was not sent.

## 2026-08-16 - Social-feed setup correction drafted for Rebekah

- Read Rebekah's reply in the existing social-feed email thread. She stopped at the Instagram instructions because she could not find `Add New` or `User Timeline` after opening the main Instagram Feed menu.
- Confirmed against current Smash Balloon documentation that the missing navigation step is `Instagram Feed > All Feeds > Add New`; `User Timeline` appears only after that. Confirmed the parallel TikTok path is `TikTok Feeds > All Feeds > Add New > Add Source`, and TikTok does not present the Instagram-specific `User Timeline` choice.
- Created and read back an unsent reply draft in the same Gmail thread. It apologizes for the omitted submenu, gives the corrected steps for both plugins, and asks Rebekah for a screenshot of the left WordPress menu only if `All Feeds` is still absent. The draft was not sent.
- The shared `Cody WP Logins` sheet currently identifies this client by the former Cloudways application hostname rather than the production domain, so no stored credential was entered into the production login form during this read-only diagnosis. Update or verify that login record before relying on it for future production-admin access.

## 2026-08-17 - TikTok Unauthorized diagnosis corrected and site-side reset completed

- Read the latest social-feed email thread. Rebekah confirmed the Instagram source connected successfully but TikTok returned `Error: Unauthorized`; Todd had already told her he would investigate.
- Corrected the earlier diagnosis: the TikTok plugin had never been connected on staging or the former domain, so a prior-domain social connection was not established as the cause. The previously created reply draft was not sent and should not be used.
- Inspected the live WordPress plugin directly. Smash Balloon TikTok Feed is current at version 1.7.1, has zero connected TikTok sources and zero feeds, and its authorization handoff successfully reaches Smash Balloon's connection service and the official TikTok login page using the live `rebekahspureliving.com` callback.
- Cleared the TikTok plugin's own connection cache on the live site and retested the authorization handoff. No Breeze, Varnish, browser or other site cache was cleared, and no client action was requested for cache handling.
- The remaining unavoidable client step is TikTok account authentication and read-only authorization. Rebekah should first sign in to `tiktok.com` in the same browser, then use `TikTok Feeds > Settings > Add Source > Connect with TikTok` and approve the requested profile/video permissions. If TikTok still returns `Unauthorized` after the site-side reset, capture the page/URL immediately after TikTok authorization for a precise account-side or OAuth callback diagnosis.
- Confirmed from the current TikTok Display API and Smash Balloon requirements that no Business or Creator account is required. A Personal, Creator or Business account can connect if the user can log into and authorize that exact TikTok profile. Only public videos are available to the feed; a private/no-public-video account would produce missing feed content rather than the connection-stage `Unauthorized` message.
- Completed a full connection-path audit. Smash Balloon maintains a website-registration token independently of connected TikTok sources, so that token can be created on staging and migrated even when no TikTok account was ever connected. The plugin cache reset cleared the old site-registration token; the live settings page now shows a newly present registration token and no registration error for `https://rebekahspureliving.com`.
- Ruled out the principal live-site causes: Site URL and Home URL are identical HTTPS production URLs; TikTok plugin 1.7.1 is current; Rebekah's WordPress account is an Administrator; PHP cURL, JSON, SSL streams and secure external HTTP requests pass; REST, loopbacks and the Authorization header pass; no relevant Wordfence block was recorded for Rebekah's connection session; the production authorization link reaches Smash Balloon and TikTok with the expected live callback and requested scopes; and Smash Balloon's TikTok relay health endpoint returned HTTP 200 during the audit.
- Remaining possible causes are on the TikTok/OAuth side: wrong or stale TikTok web session, login using a different TikTok identity than `@rebekahspureliving`, an unfinished TikTok one-time security verification, denial/partial approval/revocation of required permissions, TikTok age or regional ineligibility for third-party authorization, an account restriction, invalid/expired/reused authorization grant, TikTok/Smash Balloon client or scope error, or a temporary TikTok/connector failure. The public `@rebekahspureliving` profile resolves and shows profile metrics with no private-account notice, although TikTok's logged-out web view currently returned a generic `Something went wrong` while loading its video area.
- A screenshot or copied URL from the actual failed page immediately after pressing TikTok Authorize is the minimum remaining evidence needed to distinguish TikTok rejection, Smash Balloon token exchange, and WordPress callback processing. Do not ask the client to clear WordPress, hosting or browser caches as a blanket step.
- Completed a controlled connection test with Todd's TikTok account. The same live plugin flow connected successfully and displayed `TikTok account connected successfully!`, proving the production website, plugin registration, Smash Balloon relay, TikTok authorization client, requested scopes and WordPress callback can complete normally. No feed was created or published, and the optional Smash Balloon promotional-email checkbox was opted out before authorization.
- Removed Todd's test source immediately afterward. WordPress confirmed `Source Deleted!`; Manage Sources returned to zero connected TikTok accounts, and the test account had been used in zero feeds. Rebekah's failure is therefore specific to her TikTok login/account/authorization attempt rather than a general production-site or plugin failure.
- At Todd's request, cleared the Smash Balloon TikTok plugin's own cache after the temporary test; WordPress confirmed `Cleared all Caches`. Reopened the production Add Source handoff to refresh/verify the live site registration, confirmed it still reached Smash Balloon normally, returned to WordPress, and reconfirmed zero connected TikTok sources. No Breeze, Varnish, Object Cache Pro or unrelated website cache was cleared.
- Logged Todd's TikTok account out of the in-app browser. TikTok's current web settings do not expose third-party app revocation; TikTok's official process requires the mobile app: Profile > Menu > Settings and privacy > Security & permissions > Apps and services permissions > select Smash Balloon/TikTok Feeds > Remove access. This is the only remaining action needed to ensure the test authorization is revoked at TikTok, beyond the already deleted WordPress source.
- Created an unsent reply draft in Rebekah's existing `Connect Instagram and TikTok to the Website` Gmail thread. It explains that Todd's email/password plus verification-code test connected successfully, identifies TikTok login/authorization rather than the website or account type as the likely failure point, confirms the plugin cache/registration cleanup, gives the corrected Settings > Add Source connection path followed by All Feeds > Add New, notes that public videos refresh daily in Lite, and requests an exact error-page screenshot if `Unauthorized` recurs. The draft remains unsent for Todd's review.
- Read Rebekah's August 17 follow-up: she said the retry worked but connected an incorrect/blank TikTok identity because she was not signed into the intended account; two minutes later she reported finding the delete control. Todd replied that he would disconnect and reset it.
- Verified the live WordPress state instead of assuming her deletion completed: TikTok Manage Sources now shows no connected source, and All Feeds shows only the empty first-use setup screen with no saved TikTok feed. Cleared the Smash Balloon TikTok plugin cache and confirmed `Cleared all Caches`, then reopened the live Add Source handoff to refresh/verify registration and confirmed it still reaches Smash Balloon normally. Returned to WordPress and reconfirmed zero sources and zero feeds, leaving a clean retry state. No email reply or draft was created during this cleanup.
- Client retry requirement: before clicking Connect with TikTok, Rebekah must sign into the actual `@rebekahspureliving` TikTok identity (preferably through `Use phone / email / username` with the account's established credentials), verify the displayed TikTok account before approving access, then create and save Feed 1 after the correct source returns to WordPress.
- After Todd reported that Rebekah retried and connected a second incorrect TikTok identity, performed another read-only live audit. By the time of the check, Manage Sources again showed no connected account and All Feeds again showed the empty first-use setup screen with no saved feed, indicating the second incorrect source had already been removed. No settings or connections were changed during this audit.
- Verified the public Homepage after the second failed identity attempt. The TikTok section still displays the approved six fallback cards and `@rebekahspureliving`; no videos or profile content from either incorrect TikTok identity are publicly visible. Todd plans to walk Rebekah through the correct login and authorization during a screen share tomorrow.
- Read Rebekah's latest follow-up identifying the repeatedly selected TikTok identity as the account associated with `rebekahniman@yahoo.com`; she asked whether Todd could log into her computer during the next call. Updated Todd's existing unsent reply draft in the same Gmail thread instead of creating a duplicate. The revised draft says she can share her screen during tomorrow's Zoom, Todd will guide her through signing out of the wrong TikTok identity, signing into and verifying the correct account, and connecting/saving the feed; it states that remote access and password sharing are unnecessary, suggests pausing screen sharing while entering her password, confirms the website is currently clean, and apologizes for the trouble. The draft remains unsent for Todd's review.

## 2026-08-15 - Final launch performance and handoff audit

- Completed a read-only production launch audit after the updated theme was installed. The canonical HTTP and `www` variants redirect to `https://rebekahspureliving.com/`, the Homepage, XML sitemap and robots file return HTTP 200, and an intentionally nonexistent URL correctly returns 404. The Homepage is being served from Cloudways/Breeze/Varnish cache; no hosting upgrade is justified by the current checks.
- Current PageSpeed lab scores are mobile 44 and desktop 50. Mobile LCP is 25.9 seconds; desktop LCP is 1.8 seconds but TBT is 1.12 seconds and CLS is 0.204. Accessibility is 97, Best Practices 96, and mobile SEO 100. The desktop robots warning conflicts with the live, syntactically valid robots response and should be retested rather than prompting an immediate robots change.
- The principal speed issue is the Homepage payload, not DNS or server capacity. Production currently loads an approximately 11.2 MB autoplay/preload hero video, two Homepage PNGs totaling approximately 4.4 MB, a mismatched approximately 1.2 MB hero-background preload, and an approximately 620 KB Homepage JavaScript file containing an embedded Base64 copy of the old mockup document. PageSpeed estimates about 2.5-4.7 MB of image-delivery savings and approximately 0.9 MB of unused JavaScript.
- Recommended staging-tested performance pass: use the 93 KB hero poster as the first paint/LCP asset; do not preload the video on mobile and defer video loading/playback; remove the unused hero-background preload; convert/resize large PNGs to WebP/AVIF with responsive `srcset` and explicit dimensions; replace the Base64 mockup hydration architecture with direct PHP/component markup; then rerun mobile and desktop PageSpeed before production deployment.
- The live page recorded one TikTok/plugin MutationObserver console error while feed ID 1 is not connected. The approved Instagram and TikTok fallback cards remain visible and functional. Recheck the console and confirm six current posts/videos after Rebekah completes both Smash Balloon connection wizards.
- The Blue Nova Google account currently has no matching Search Console property or GA4 property for `rebekahspureliving.com`. Establish/share the correct Search Console and GA4 properties, submit/verify `https://rebekahspureliving.com/sitemaps.xml`, and confirm production measurement before considering analytics/SEO handoff complete.
- Current CrUX field data covers July 17-August 13 and therefore largely predates the August 15 launch/theme update. It shows poor historical LCP/TTFB but should not be used to judge the new theme until enough post-launch data accumulates.
- Clean-handoff follow-ups: verify a daily Cloudways backup and restore point; run controlled submissions through each public form and confirm WP Mail SMTP delivery/reply-to behavior; confirm domain auto-renew, lock and MFA; enable uptime/SSL/resource alerts; review production admin users and MFA; and commit/tag the canonical v1.0.31 theme source before archiving temporary deployment artifacts. No live settings or files were changed during this audit.

## 2026-08-15 - GA4, Tag Manager and Search Console setup started

- Created the GA4 property `Rebekah's Health & Nutrition - GA4` inside the existing Blue Nova Marketing Analytics account. Configured Detroit reporting time, USD, Health industry, medium business size, and the lead-generation plus traffic-measurement objectives.
- Created the web data stream `Rebekah's Health & Nutrition Website` for `https://rebekahspureliving.com` with enhanced measurement enabled. The stream uses measurement ID `G-K6X8N8TE1R`.
- Created the web container `Rebekah's Health & Nutrition` inside the existing Blue Nova Marketing Agency Tag Manager account. Container ID is `GTM-MQHBBWCG`.
- Added an unpublished `GA4 - Google tag` workspace draft using the new measurement ID and the Initialization - All Pages trigger. It has not been submitted/published and is not collecting production data yet.
- Created the Search Console domain property for `rebekahspureliving.com` and generated its DNS TXT verification record. Verification remains pending because the GoDaddy session expired and the authorized login sheet does not contain an exact GoDaddy credential for this hostname.
- Prepared canonical theme v1.0.32 locally with the standard GTM head loader and `wp_body_open` no-JavaScript fallback. The live theme has not been edited and remains on v1.0.31. Local PHP CLI was unavailable for syntax linting; verify through the WordPress editor/staging deployment before production activation.
- Pending user action/approval: sign into the open GoDaddy tab; approve adding the Search Console TXT record; and approve deployment/publishing of the GTM container, which will begin sending visitor page-view and engagement telemetry to Google Analytics. After activation, verify live GA4 Realtime/Tag Assistant, complete Search Console ownership, submit `https://rebekahspureliving.com/sitemaps.xml`, and link Search Console to GA4.

## 2026-08-15 - Analytics, Search Console, access and backup handoff completed

- Added the Google Search Console verification TXT record at GoDaddy and confirmed it resolves publicly. Search Console now shows the `rebekahspureliving.com` domain property as verified. The legacy verified owner `rebekahspureliving@gmail.com` was already present and was not duplicated.
- Submitted `https://rebekahspureliving.com/sitemaps.xml` in Search Console. Google accepted the submission; its first table refresh temporarily showed `Couldn't fetch`, while an independent production check returned HTTP 200 with XML content. Recheck processing status after Google recrawls it.
- Confirmed the newly created GA4 property is `properties/549929359` (`Rebekah's Health & Nutrition - GA4`) under Blue Nova Marketing. Added `rebekahspureliving@gmail.com` as Property Administrator. Linked the verified Search Console domain property to the production web stream successfully.
- Added the standard GTM loader to the live custom theme and advanced the live/local theme constant to v1.0.32. Published GTM container `GTM-MQHBBWCG` as Version 2, `Initial GA4 production launch`, with Google tag `G-K6X8N8TE1R` on Initialization - All Pages. A live browser check loaded both the GTM container script and the GA4 Google tag script.
- Invited `rebekahspureliving@gmail.com` to the Rebekah GTM container with Publish permission. This is the highest container-scoped role; account-level Administrator was intentionally not granted because it would expose unrelated Blue Nova client containers. Invitation acceptance remains pending with Rebecca.
- Verified Cloudways server backups are scheduled every day at 06:50 UTC with one-week retention. Started and completed a fresh on-demand server backup for the 2 GB Rebekah server, covering the production application and database.
- Confirmed there are two public backend forms: Contact Us (Forminator 1064) and Newsletter (Forminator 313, reused across the Homepage and location pages). Contact Us sends an admin notice to Rebecca plus a visitor confirmation; Newsletter sends an admin notice to Rebecca. The Contact Us admin notification currently shows a blank Reply-To field. Attempts to select the visitor `{email-1}` token in the current Forminator builder did not persist after publish/reload, so actual received-message headers must be checked during the controlled submission and the Reply-To setting remains a follow-up if the live message does not default correctly.
- Prepared one controlled Contact Us submission and one Newsletter submission with clearly labeled Blue Nova QA data, but did not click either submit button. Form submission, mailbox delivery and WP Mail SMTP log verification remain pending Todd's action-time approval because the tests will create real submissions and send email notifications.

## 2026-08-15 - Production form submissions and delivery verified

- With Todd's explicit approval, submitted the two prepared live production tests. Contact Us returned its success confirmation and saved entry 740 with the Blue Nova QA name, email, phone, subject and message. Newsletter returned its success confirmation and saved entry 741 with the Blue Nova launch alias, phone and Lapeer preference.
- Rebecca received both administrator notifications at `rebekahspureliving@gmail.com` and forwarded them to Todd with positive acknowledgements, confirming real delivery of the Contact Us and Newsletter notifications rather than only successful WordPress processing.
- Ran one additional clearly labeled Contact Us mailbox test to the connected Blue Nova mailbox because the original visitor-confirmation address was not accessible in this session. The visitor confirmation arrived in the inbox from the site's SendGrid-backed WP Mail SMTP connection with the expected subject and body.
- WP Mail SMTP Lite is installed, so WordPress does not retain the detailed email/header log promised by the Pro edition. The received visitor confirmation showed `Reply-To: noreply@rebekahspureliving.com`, which is appropriate for an automated acknowledgement.
- The administrator notification's Reply-To remains unresolved: its Forminator notification setting is blank, and Rebecca's ordinary forwarded copy did not preserve the original Reply-To header. Do not claim visitor-directed Reply-To is verified. A future form configuration pass should set the Contact Us administrator notification Reply-To to the submitted email field and then verify by replying from Rebecca's received message.
- Delivery authentication watch item: the received visitor confirmation passed SendGrid SPF and DKIM but failed DMARC alignment because the visible From domain is `igdsolutions.com` while SendGrid authenticated `sendgrid.net`. The message reached the inbox, but sender-domain authentication/alignment should be corrected when the site mail identity is cleaned up.

## 2026-08-15 - Monday performance-cleanup reminders scheduled

- Scheduled a private Slack reminder for Todd for Monday, August 17, 2026 at 9:00 AM Pacific covering the three deferred staging-quality items: image conversion/resizing with explicit dimensions and responsive sources, hero-video compression without changing autoplay/mobile behavior, and replacement of the embedded Base64 mockup with normal PHP theme components.
- Scheduled the matching reminder directly in the Blue Nova Marketing Gmail account for Monday, August 17, 2026 at 9:00 AM Pacific, addressed only to Todd at his Blue Nova mailbox. The email includes the 4–6 hour estimate for the mockup refactor plus staging and PageSpeed verification requirements. Removed the temporary Codex email automation so Todd receives only the one scheduled Gmail message.
- Recorded the project-process lesson: these asset and architecture issues should have been identified and corrected during staging. Their post-launch remediation is avoidable extra work and must be completed in staging before a controlled production deployment.

## 2026-08-16 - Blog-content preservation verified after launch incident

- Confirmed the August 15 resource-saturation incident was caused by expensive legacy Events Calendar archive/date/iCal requests and the resulting PHP worker exhaustion, not by normal WordPress blog posts.
- No blog post was deleted, unpublished, renamed, redirected or noindexed during incident containment or plugin cleanup. The temporary containment only deactivated The Events Calendar and Event Tickets; the calendar was later restored behind the Blue Nova Events Traffic Guard, while unused Event Tickets was removed without deleting event or blog records.
- Compared the original pre-redesign inventory with live production: both contain exactly 21 published blog posts. The live WordPress REST inventory returns all 21 as `publish`, and all 21 canonical post URLs are present in the SEOPress post sitemap. Zero published posts are missing from the sitemap.
- SEO-related production changes were limited to exact redirects for deleted duplicate location pages, noindexing dormant WooCommerce utility pages, sitemap inclusion for Events and Medical Practitioners, and schema/module cleanup. None of those changes removed or deindexed a blog article.

## 2026-08-16 - GA4 and GTM business-event audit

- Confirmed the production GA4 property is receiving traffic. For August 15-16 it recorded 89 page views, 42 session starts, 24 scroll events, four generic click events and two form-start events.
- Inspected the live GTM container `GTM-MQHBBWCG`. It currently contains only the baseline `GA4 - Google tag` firing on Initialization - All Pages; no custom triggers or GA4 event tags have been configured for phone clicks, successful form submissions, newsletter signups, directions, event actions, affiliate shopping links, email clicks or social links.
- GA4 currently shows zero key events. It has not recorded a `form_submit` event despite recording `form_start`, and no dedicated phone-call or click-to-call event is present. Baseline analytics is working, but business-action/conversion reporting is not yet complete.
- Recommended follow-up is a staging/preview-tested GTM measurement pass: track `tel:` clicks as `click_to_call`; fire Contact and Newsletter events only after Forminator success; add location directions, event, affiliate-shop and email-link events with useful parameters; publish the tested container version; and mark only the genuinely important actions as GA4 key events.
- A GA4 telephone-link event reports intent to call, not whether a call was answered or its duration. True call reporting should be added through Google Ads website call conversions/forwarding numbers or a dedicated call-tracking service if paid advertising or call attribution warrants it.
- Google Search Console setup and its GA4 link are already complete. Google Business Profile ownership/configuration for all four locations could not be verified through the connected agency hub because its account-list operation returned a server error; this remains a handoff check rather than a confirmed gap.
- No separate Google Cloud project is needed for normal GA4, GTM, Search Console or Business Profile use. Create one only if the business later needs BigQuery raw-data export, server-side tagging, API/service-account automation or a custom OAuth integration. Google Ads linking, call conversions and location assets should be configured only when an Ads account/campaign is in scope; Merchant Center can wait for a real online-commerce phase.

## 2026-08-16 - Business Profile UTM attribution and Looker Studio setup

- Confirmed Todd's Blue Nova Workspace account can manage all four Rebekah Google Business Profiles through Google's Search-based profile editor. The older Business Profile Manager list under `bluenovamarketing@gmail.com` did not display the profiles, but the authorized `tbailey@bluenovainc.com` account showed `You manage this Business Profile` for Lapeer, Grand Blanc, Clarkston and Lake Orion.
- Replaced each profile's outdated generic or legacy website destination with its matching production location page and a consistent location-specific attribution scheme: `utm_source=google`, `utm_medium=organic`, `utm_campaign=gbp`, plus `utm_content=lapeer`, `grand_blanc`, `clarkston` or `lake_orion`. Google accepted all four edits and showed them as pending review, normally up to ten minutes.
- The previous profile destinations were materially outdated: Lapeer still pointed to the obsolete HTTP `/home/` URL; Grand Blanc pointed to the generic HTTP homepage; and Clarkston/Lake Orion pointed to the generic HTTP homepage. The new URLs improve both visitor landing-page relevance and GA4 location attribution.
- Todd corrected the intended Looker Studio owner to the agency-owned `bluenovamarketing@gmail.com` account. The attempted Todd Workspace route was abandoned; no Data Studio profile or terms were completed under `tbailey@bluenovainc.com`.
- Completed the Looker Studio report `Rebekah's Health & Nutrition — Website Performance` under `bluenovamarketing@gmail.com` and connected the production GA4 property `Rebekah's Health & Nutrition - GA4` (`549929359`) using owner credentials. The initial dashboard includes traffic KPIs, a date-range control, a traffic trend, and a source/medium acquisition table that can expose the new GBP UTM attribution after Google approves the profile edits and visits occur. Report: `https://datastudio.google.com/u/0/reporting/a7e75aad-6b90-4f6c-ab03-15301335d87a/page/9ia6F/edit`.
- The report does not yet claim phone calls, successful forms or other leads because the GTM container currently has only baseline GA4 measurement and GA4 still has zero key events. Add the staging-tested conversion-event tags before treating the report as a complete lead/call dashboard.
- No Google Ads setup, linking or advertising work was performed. The intended report remains GA4-only and should initially show traffic, engagement, source/medium, GBP location attribution and the business events once GTM conversion tracking is implemented.

## 2026-08-16 - Client notice drafted for GBP location-page updates

- Created an unsent Gmail draft to Rebecca explaining that all four Google Business Profile website buttons were proactively changed from generic or outdated destinations to their matching production location pages.
- The draft explains the customer-experience, local relevance, local SEO and attribution rationale without promising a ranking increase, notes that Google may take time to review the edits, and offers to restore the previous homepage links if Rebecca prefers.
- Draft subject: `Proactive Google Business Profile location-page update`. It remains in Todd's Gmail drafts for review and was not sent.

## 2026-08-17 - LifeWave and partner-page navigation reply drafted

- Confirmed that the live Fullscript, Designs for Health, LifeWave, and Peptides & Injectables landing pages are published but are not included in the main header or footer navigation; their current discovery path is the practitioner-brands section near the bottom of the Homepage or their direct URLs.
- Replaced the earlier draft with an unsent reply in Rebekah's existing `Proactive Google Business Profile location-page update` thread. The revised draft explains that LifeWave is absent from both the main navigation and footer, directs her to the Homepage's `Practitioner Brands` shortcut and `Trusted practitioner brands, available online` section, and includes the direct LifeWave URL. It contains no WordPress editing instructions.
- No website navigation changes were made and the email was not sent.

## 2026-08-17 - Chatbot discussion reply drafted

- Reviewed Rebekah's email `New Chat box`. She reported that the current chatbot appears to have been running without appropriate oversight since May, that she has no conversation history from that period, and that the service costs $1,600 per year; she is open to replacing it.
- Created an unsent reply draft in the same Gmail thread proposing a conversation tomorrow to understand exactly what has happened, what she dislikes about the current chatbot, and align on requirements before recommending a solution. The draft asks what time works best for her.
- No chatbot vendor, technical change, purchase, cancellation, or meeting was committed. Next step is Todd's review/send of the draft and a requirements conversation with Rebekah.

## 2026-08-17 - Existing chatbot integration audited read-only

- Inspected the live WordPress installation and visitor-facing widget without changing any plugin, file, setting, conversation, or HighLevel data. The only chat-related active WordPress plugin is `Inbox Pro Web Chat Widget` version 1.0.0; the official LeadConnector WordPress plugin is not installed.
- Read the complete custom plugin source in the WordPress editor without editing it. It is a single PHP file that enqueues HighLevel's remotely hosted `cdn.apigateway.co` web-chat SDK on every public page and passes the hard-coded widget ID `76ea6245-b57e-11f0-92f1-ded7a3fff3de`. It contains no settings page, chatbot prompt, training material, contact storage, history, routing, workflow, analytics, or local failover logic.
- Verified the widget is currently loading and opening on the production Homepage without chat-specific browser errors. Its visitor-facing identity is `Health & Nutrition Expert`; the greeting says visitors can ask questions immediately, the header promises an immediate response, and the footer identifies `The Birney Directive` as the chat provider.
- HighLevel's current architecture keeps widget configuration, Conversation AI deployment/mode, bot instructions and knowledge, assignments, workflows, contact records, chat history, inactivity handling, and other operational controls in the associated HighLevel sub-account rather than this WordPress loader. The connected HighLevel API is not authorized for the needed scope, so those remote settings and the missing history cannot be independently reviewed from Blue Nova's current access.
- Practical conclusion: leaving the plugin active will keep loading the vendor-controlled widget sitewide; it is not dormant. Deactivating or removing it would stop the widget from loading but would not expose or recover remote HighLevel history. Before recommending a replacement or change, obtain vendor/sub-account access or a configuration export and clarify ownership, retention, routing, AI mode, escalation, and cancellation/transfer terms with Rebekah and The Birney Directive.

## 2026-08-17 - Phase Two ecommerce readiness consolidated

- Consolidated the local Phase Two/WooCommerce record, the canonical Phase One Sheet, the current custom-theme baseline, and the earlier Rebekah Health ChatGPT project discussions about Revel, Kosmos eSync, product responsibilities, storefront scope, and timing.
- Created `PHASE-TWO-ECOMMERCE-READINESS.md` as the working pre-kickoff record for a 25-product pilot. It defines the recommended Revel → Kosmos eSync → WooCommerce architecture, one-location fulfillment recommendation, client account/access requirements, product-data checklist, shipping/tax/payment/policy decisions, field ownership, build order, acceptance tests, and scope boundaries.
- Reconciled superseded planning: older Bricks-based recommendations are obsolete because production now uses the custom Rebekah's theme with Elementor Free; the theme declares WooCommerce support but still needs Phase Two store templates. WooCommerce and its retained extensions remain inactive, the four system pages remain preserved/noindexed, and the July audit found no active Revel/Kosmos REST integration.
- Recommended using one designated Revel establishment for initial online inventory and fulfillment, testing one product/action first per Kosmos guidance, then synchronizing a representative 25-product sample before any full catalog release.
- Current working estimate for a clean, ready 25-product pilot is 45–72 Blue Nova hours and approximately 3–5 elapsed weeks. The pilot should be time-tracked by workstream and used to estimate full-catalog exceptions rather than multiplying the product count blindly.
- Client-facing next step: Rebekah selects the fulfillment location and responsible staff, prepares the 25 approved products in Revel with complete customer-facing data, confirms shipping/tax/payment and policy decisions, and makes Revel/Kosmos/gateway/carrier access available before kickoff.

## 2026-08-17 - Ecommerce pilot requirements narrowed

- Clarified that Blue Nova does not need employee names before the technical pilot; Rebekah only needs to confirm internally that product maintenance and shipping-only order fulfillment are covered.
- Confirmed PayPal as the intended pilot payment method and removed any requirement for a separate shipping-label account before testing. The client supplies shipping rules and handling expectations through Rebekah.
- Clarified that Kosmos eSync is the middleware between Revel and WooCommerce; Blue Nova will verify the account/plan and present any cost for Rebekah's approval rather than asking her to configure it.
- Product facts and approved assets may be gathered from official manufacturer or supplier sources, with Rebekah approving accuracy and permitted use. Blue Nova will not contact her accountant or other advisers; Rebekah supplies approved tax settings directly.
- Corrected the pilot estimate: 8–14 hours for the 25-product data/sync proof, or 18–30 hours/about 1–2 weeks for a launch-ready shipping-only pilot. The prior 45–72-hour figure had improperly combined the pilot with the custom storefront build.

## 2026-08-17 - Pilot access, cost, and availability constraints finalized

- Reduced the access request to staging, the selected Revel establishment/integration access, a client-approved Kosmos account, secure PayPal authorization, and a dedicated WooCommerce REST key only when Kosmos requires it. No staff roster, accountant contact, carrier/label account, multi-location access, or pickup configuration is required.
- Verified current public pilot software pricing: WooCommerce and the official PayPal Payments extension are free; Kosmos Warmup is $49 month-to-month or $39/month billed annually, includes one store/location and seven actions, and advertises a 14-day trial. Optional custom mapping starts at $150. No other paid plugin is currently required.
- Recorded Todd's blackout dates for proposal and implementation planning: unavailable August 25; August 26 except morning Eastern; August 28–30; September 10–13; September 17–20; and all weekends.
- Recommended August 31–September 9 as the first controlled pilot window if every prerequisite is ready, otherwise start September 21 or later. Do not start the Kosmos trial or leave the pilot in an active handoff/testing state across a blackout.

## 2026-08-17 - Shipping, policy, PayPal, and mapping assumptions corrected

- Confirmed every pilot order will ship from one Revel/fulfillment location to the customer's checkout address. If the customer-facing shipping charge must be calculated from that address, Blue Nova needs the origin address, carrier choice, and accurate product/package weights and dimensions.
- Current official WooCommerce USPS, UPS, and FedEx live-rate extensions are each listed at $109/year. This cost applies only if live carrier rates are required; flat-rate/free shipping can use WooCommerce's built-in settings.
- The approved Refund & Returns Policy already covers its 30-day return window, product condition, proof of purchase, refund method, return-shipping deduction, and nonrefundable shipping. Rebekah only needs to confirm it applies to onsite WooCommerce orders and provide the return address plus cancellation, damage, and lost-package handling not covered by the preserved wording.
- WooCommerce's prior presence does not by itself prove PayPal remains connected. The preserved stack documents WooPayments and NMI, while staging WooPayments was in Safe Mode and its production connection was not transferred. Blue Nova will audit and test the payment configuration first; Rebekah is involved only if PayPal must be authorized or reconnected.
- Clarified that the WooCommerce REST API key is for Kosmos, not PayPal, and Blue Nova can create it during setup. PayPal credentials are not required for that step. If ongoing PayPal dashboard work later becomes necessary, request restricted delegated access rather than the client's primary password.
- Standard Kosmos mapping is expected to cover the pilot. Custom mapping is excluded unless the one-product test proves a specific gap. Kosmos currently says custom mapping setup starts at $150; obtain written confirmation that this is a one-time setup charge before approval.
- Proposal cost clarification: the approximately **$158 initial estimate** combines **$49 for one month of Kosmos eSync**—covering Revel ↔ WooCommerce product, inventory, and order synchronization—with **$109/year for one optional live-rate carrier extension** (USPS, UPS, or FedEx) that calculates checkout shipping from the customer's address and package details. The $109 carrier extension is not needed if Rebekah chooses flat-rate or free shipping.

## 2026-08-17 - Owner-effort rule for ecommerce setup

- Todd directed that Blue Nova should remove technical setup work from Rebekah's plate wherever possible. Blue Nova will handle the PayPal audit, configuration, connection, and testing using existing authorized access and involve Rebekah only for an unavoidable owner-only login, MFA, identity check, or approval, prepared as one brief authorization rather than a task list.
- Proposal language should state simply that custom mapping is not expected. The $150-starting setup cost is only an unlikely contingency if the one-product test proves standard Revel mapping insufficient; Blue Nova will obtain and present the exact quote only if that occurs.

## 2026-08-17 - Current client asks refined for Kosmos, PayPal, and product content

- Removed WordPress/Cloudways access from the client request because Blue Nova already has it. Return-address, cancellation, damage, and lost-package clarification is a pre-launch policy follow-up, not a prerequisite for the 25-product synchronization test.
- Rebekah must create and own the Kosmos eSync account with her business/billing details and give Blue Nova configuration access. Explain that Kosmos keeps Revel and WooCommerce products, inventory, and orders synchronized, reducing manual stock maintenance and overselling risk.
- Verified from PayPal's official Business-account guidance that a primary owner can add secondary users with unique logins and selected privileges. Request a dedicated Blue Nova secondary user with only website-integration, transaction-view, and test-refund permissions; do not request the owner's password or financial-withdrawal/bank-ownership privileges.
- Product images and descriptions are not assumed to transfer reliably through Revel/Kosmos. Blue Nova will test one product and the 25-product set, gather authorized manufacturer/supplier content where available, and give Rebekah one consolidated list of only the missing or approval-required items. She may need to supply content that is unavailable from the integration or authorized sources.

## 2026-08-17 - Complete Revel preparation instructions restored

- Todd correctly flagged that “select 25 products” was incomplete. The client-facing checklist must state every required Revel preparation step for each pilot product: designate it for the one fulfillment location, keep it active, enable `Display on online and 3rd party`, verify unique SKU/barcode, customer-facing name, price, nonnegative location inventory, category, brand, weight, shippability, and complete matrix/variation data. Rebekah then provides the final 25 SKU/product list.
- Blue Nova will test one fully prepared Revel product before synchronizing the other 24. Product content gaps are audited after the integration test rather than assumed.
- Removed “restricted” from the PayPal access request. Ask for a dedicated Blue Nova secondary PayPal user with the access needed to manage the website integration, view transactions, and process refunds; do not request the primary owner's password.
- Kosmos cost is not a new discussion. Rebekah has previously been told about it. Give a simple pre-signup reminder that the pilot plan is currently $49 month-to-month and advertises a 14-day trial; she creates/owns the account and then gives Blue Nova configuration access.

## 2026-08-17 - Revel category, shipping weight, access, and PayPal details clarified

- Rebekah only needs to keep each pilot product in the normal Revel category the store already uses and correct obvious duplicates/misspellings. Blue Nova handles WooCommerce category mapping and website organization after the test; she does not create website categories in Revel.
- Product weight is required only for live address-dependent carrier calculations. Package dimensions are needed only when required by the selected carrier method. If flat-rate/free shipping is chosen, weight is not a pilot prerequisite.
- Removed broad product-by-product “legal shippability” confirmation. Routine products are assumed shippable; Rebekah only flags known special-handling or nonmailable exceptions.
- Rebekah does not need to create a separate 25-product list. She marks the intended products `Display on online and 3rd party`; Blue Nova pulls the marked set through Revel/Kosmos, creates the SKU/name list, and sends it back for confirmation.
- Revel administrator/integration access and access to the designated fulfillment establishment should normally be one grant, not two credentials. Confirm that the granted Revel user can see that establishment.
- PayPal secondary-user access should cover website/API integration and relevant account settings, transaction viewing, refunds, and permission to contact PayPal Customer Service for account troubleshooting. Exact PayPal permission labels may vary. Blue Nova does not need the primary owner's password.

## 2026-08-17 - Shipping choice and complete PayPal access requirement finalized

- The proposal/client instructions must explicitly present two shipping options before asking for weights: live address-dependent USPS/UPS/FedEx rates require accurate product/package weights and one $109/year carrier extension; flat-rate/free shipping uses WooCommerce's built-in $0 settings and does not require product weights for the pilot. Current discussion points toward live carrier rates, but Rebekah makes the final choice.
- Todd rejected uncertain PayPal wording because repeated permission requests delay the project. Request a dedicated Blue Nova secondary PayPal user with full ecommerce/payment operational access for the pilot: `API Activation & Authorization`, relevant profile/account settings, balance and transaction viewing, refunds, disputes/chargebacks, Customer Service authorization, and any permission specifically labeled for checkout, payment integrations, or API/developer access.
- PayPal's official developer documentation specifically identifies `API Activation & Authorization` as the permission used when a developer is helping complete an account implementation. A separate report-only user is not required for this pilot, and Blue Nova will not request the owner's primary password.

## 2026-08-17 - Definitive Phase Two ecommerce master checklist created

- Created `PHASE-TWO-MASTER-CHECKLIST.md` as the authoritative client/proposal/implementation checklist. It consolidates all current decisions, access requirements, exact Revel product preparation, Kosmos and PayPal setup, shipping alternatives, content-gap workflow, Blue Nova build responsibilities, pilot acceptance tests, launch-only follow-ups, costs, timing, and Todd's availability constraints.
- The client-side scope is limited to decisions, account ownership/access, Revel product readiness, approvals, and any content still missing after Blue Nova's audit. Blue Nova owns the technical setup, legacy-product reconciliation, synchronization, payments, shipping configuration, storefront work, QA, and handoff.
- Corrected the remaining acceptance-test wording so Blue Nova verifies tax against client-supplied rules approved by Rebekah; Blue Nova does not contact outside advisers.

## 2026-08-17 - Phase Two client-requirements email drafted

- Created an unsent Gmail draft to Rebekah with the subject `Phase Two online store: what we need before the 25-product test`.
- The eighth-grade-level draft explains the required order of work: select the one fulfillment location and shipping method, prepare the 25 Revel products including `Display on online and 3rd party`, create the client-owned Kosmos account, add Blue Nova as a PayPal secondary user with full ecommerce/payment operational access, and confirm remaining store settings.
- The draft explains Blue Nova's responsibilities, the one-product-first process, content-gap workflow, 2–5-business-day synchronization proof, 1–2-week launch-ready operational pilot, and the August 31–September 9 or September 21-or-later working windows. It remains unsent for Todd's review.
- Included all current Kosmos choices: $49 month-to-month, $39 per month billed annually ($468/year), and the advertised 14-day trial. Also included the conditional $109/year live-rate carrier extension and approximately $158 initial live-shipping software estimate.
- Updated `PHASE-TWO-MASTER-CHECKLIST.md` so the annual Kosmos option appears in the authoritative cost and signup sections.

## 2026-08-17 - Phase Two email rebuilt with account links and cost framing

- The earlier Phase Two Gmail draft was no longer present, so a fresh unsent draft was created to Rebekah with the same subject: `Phase Two online store: what we need before the 25-product test`.
- Added direct official links for Kosmos plans/account signup, PayPal Manage Users and PayPal's user-management instructions, plus the USPS, UPS and FedEx live-rate extensions. The email tells Rebekah not to purchase a carrier extension until the carrier/method is confirmed.
- Clarified that the 25-product pilot is paid Blue Nova setup/testing work whose cost will be included in the Phase Two proposal before work begins. The final full-catalog price and schedule will be calculated after the pilot from actual time, product issues, content gaps and integration results.
- Preserved the known software pricing: Kosmos at $49 month-to-month or $39/month billed annually ($468/year), advertised 14-day trial, and optional $109/year live-rate carrier extension. The draft remains unsent for Todd's annotation and review.
- Updated the master checklist with the account-creation links and the distinction between pilot setup cost and post-pilot full-catalog pricing.

## 2026-08-17 - Phase Two email timing language revised

- Removed specific August 31–September 9 and September 21 start-window language from the unsent Phase Two client draft.
- The replacement explains that Todd will personally manage the pilot and will not hand it off, will take planned time off during the next four weeks, and that a few weeks may be limited to about two working days while most remain normal.
- The email now sets the expectation that Phase Two will not be a rushed full-speed build; careful, consistent setup and testing take priority over speed. No specific start date was promised, and the draft remains unsent.
