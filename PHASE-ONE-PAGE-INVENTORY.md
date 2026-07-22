# Rebekah's Health & Nutrition - Phase One Page Inventory

Updated: 2026-07-22  
Scope: Non-ecommerce website only. WooCommerce shop, product, cart, checkout, and account screens are excluded.

## Recommended primary pages

| # | Page | Proposed URL | Source / reason | Current status |
|---:|---|---|---|---|
| 1 | Home | `/` | Existing page and approved client-final homepage mockup v1.21 | Design complete; WordPress implementation remains |
| 2 | Our Story / Meet Rebekah | `/our-story/` | Existing Our Story plus Meet the Owner; client requested Meet Rebekah | Design complete; WordPress implementation and redirect from `/meet-the-owner/` remain |
| 3 | Locations | `/locations/` | Existing page; client wants all four stores featured | Needs redesign/build as a store directory |
| 4 | Lapeer Location | `/locations/lapeer/` | One of four stores | Confirmed; new detail page needed |
| 5 | Grand Blanc Location | `/locations/grand-blanc/` | One of four stores | Confirmed; new detail page needed |
| 6 | Clarkston Location | `/locations/clarkston/` | One of four stores | Confirmed; new detail page needed |
| 7 | Lake Orion Location | `/locations/lake-orion/` | One of four stores | Confirmed; new detail page needed |
| 8 | Classes & Events | `/events/` | Explicit client priority; current site already has event content | Needs archive redesign/build |
| 9 | Wellness Source Blog | `/blog/` | Existing page and explicit client priority | Minimal archive design only: heading/introduction, category or search controls if useful, and the post grid |
| 10 | In-Store Products | `/in-store-products/` | Client's requested non-ecommerce alternative | New discovery page: store categories, featured brands, Signature Line, and visit/call actions; no prices, cart, or checkout |
| 11 | Practitioners | `/practitioners/` | Existing page with 28 structured practitioner records | Required visual and usability refresh of the existing archive and profile system; preserve its current information and functionality only |
| 12 | Contact Us | `/contact-us/` | Existing page and existing contact form | Needs redesign/build and form retesting |
| 13 | Privacy Policy | `/privacy-policy/` | Existing legal page | Reuse current policy; fit into the new design or quickly revise during the build |
| 14 | Refund & Returns Policy | `/refund_returns/` | Existing policy will remain in use | Preserve content; fit into the new legal-page template |
| 15 | Terms & Conditions | `/terms-conditions/` | Needed legal/website-use policy | New page and starter template needed; final content should be professionally reviewed |
| 16 | Disclaimer | `/disclaimer/` | Needed for wellness and educational content | New page and starter template needed; final content should be professionally reviewed |

## Proposed affiliate shopping pages

These are not ecommerce pages on Rebekah's website. Each page would briefly explain the partner offering, who it is for, how ordering works, and then send the visitor to the approved external affiliate destination for the actual transaction.

| Page | Proposed URL | Current evidence | Recommended treatment |
|---|---|---|---|
| Shop Fullscript | `/shop-fullscript/` | Current navigation promotes `Shop 355+ Brands at Fullscript` | Short branded landing page with disclosures and external shopping CTA |
| Shop Designs for Health | `/shop-designs-for-health/` | Current navigation promotes Designs for Health | Short branded landing page with disclosures and external shopping CTA |
| Shop LifeWave | `/shop-lifewave/` | Current Practitioners menu promotes LifeWave | Short branded landing page with disclosures and external shopping CTA |
| Peptides & Injectables | `/peptides-injectables/` | Current Shop menu promotes Peptides & Injectables; approved homepage includes `Shop Injections` | Separate informational/referral page with especially careful claims, eligibility language, disclosures, and external CTA |

If all four pages are approved, add them to the primary-page count. A lighter alternative is one `Shop Trusted Partners` hub containing four partner panels and external links, which would add only one page.

## Reusable content templates to build

These are not additional navigation pages, but they are separate design/build work:

| Template | Applies to | Work needed |
|---|---|---|
| Blog post template | 21 existing posts | Build once; preserve post URLs, authors, dates, categories, images, and SEO data |
| Event detail template | Existing and future events | Build once; confirm whether registration/ticketing is included in Phase One |
| Location detail template | Four store pages | Build once and populate with store-specific address, phone, hours, map, photos, events, and signup routing |
| Practitioner profile template | 28 existing practitioner records | Required. Make current profiles attractive, understandable, consistent, responsive, and usable while preserving existing information and functionality only |

### Practitioner profile scope guardrails

- Do not invent or add services, appointment capabilities, practitioner-event relationships, contact methods, or other functionality.
- Show related classes or events only when that relationship already exists in the current data.
- Use appointment language or an appointment CTA only when the existing profile explicitly supports appointments.
- Otherwise, preserve the profile's current contact information or omit the CTA.
- The goal is presentation and usability improvement to the system's current extent, not expansion of the practitioner offering.

## Global components, not standalone pages

- Header and navigation
- Footer
- Newsletter and text signup with preferred-store selection
- Instagram and TikTok feeds, subject to plugin setup and account authorization
- Sitewide calls to order, visit stores, and view events
- Search, 404 page, and basic accessibility/SEO states

## Existing pages excluded from the Phase One build

- `/shop/`
- `/cart/`
- `/checkout/`
- `/my-account/`
- WooCommerce product and product-category pages

Commerce content and data should be preserved for the later ecommerce phase even though these screens are not being redesigned now.

## Decisions still needed

1. Choose four separate affiliate landing pages or one combined `Shop Trusted Partners` page.
2. Confirm the exact affiliate URLs, account ownership, required disclosures, and whether Peptides & Injectables is an affiliate, referral, or direct-contact program.
3. Confirm whether Rebekah's Signature Line belongs inside In-Store Products or deserves its own landing page. The homepage already features it, so a separate page is optional rather than required.
4. Confirm whether event registration/ticketing is part of Phase One or whether the site only promotes events.
5. Confirm the main navigation label `Meet Rebekah` versus `Our Story`; either label should point to `/our-story/`.
6. Have the new Terms & Conditions and Disclaimer, plus any substantive Privacy Policy revisions, reviewed by a qualified legal professional before launch.

## Working build count

- **16 confirmed primary pages**, including the four location detail pages and four legal/policy pages.
- Home and Our Story designs are complete; the Blog archive needs only a minimal treatment.
- Add **4 pages** if each affiliate receives a landing page, producing a total of **20 primary pages**. Alternatively, one combined affiliate hub produces a total of **17 primary pages**.
- **Exactly 4 reusable content templates are required:** blog post, event detail, location detail, and practitioner profile.
