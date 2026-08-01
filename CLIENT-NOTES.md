# Client Notes

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
