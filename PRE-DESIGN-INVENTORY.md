# Rebekah's Health and Nutrition — Pre-Design Inventory

Audit date: 2026-07-20  
Environment: Cloudways staging application  
Scope: Read-only inventory. No plugins, themes, URLs, content, or Cloudways settings were changed.

## Executive summary

- The current site uses the Bricks theme and six published Bricks templates. The approved Phase One build will not use Bricks, so these are reference-only and must not be removed until the replacement site is ready.
- The active plugin stack still includes WooCommerce and eight commerce-related extensions even though e-commerce is deferred to Phase Two.
- The server-level Cloudways/Varnish page cache is functioning. The homepage returned `X-Cache: HIT` and `Cache-Provider: CLOUDWAYS-CACHE-DC`.
- The Breeze and Object Cache Pro WordPress plugins are currently inactive. Redis/Object Cache Pro status therefore needs to be verified in Cloudways before assuming persistent object caching is available.
- The staging site is correctly blocked from indexing with `X-Robots-Tag: noindex, nofollow`, and WordPress has “Discourage search engines” enabled.
- WordPress Site Health reports 0 critical issues and 0 recommended improvements.
- Permalinks use `/%postname%/`.
- Cached homepage TTFB measured about 0.46–0.53 seconds. A cache-bypassed request measured about 3.59 seconds TTFB, indicating meaningful uncached PHP/database/plugin overhead.

## Content inventory

| Content type | Published count | Notes |
|---|---:|---|
| Pages | 13 | Includes four WooCommerce system pages |
| Blog posts | 21 | Preserve slugs, dates, authors, categories, featured images, and SEO metadata |
| Medical practitioners | 28 | Custom post type using ACF data |
| Testimonials | 4 | Custom post type |
| Products | 36 | Preserve for Phase Two; do not delete |
| Current REST-visible events | 10 | The event sitemap contains a much larger historical archive |
| Event venues | 4 | Preserve |
| Event organizers | 1 | Preserve |
| Media attachments | 220 | Inventory only; image selection/compression waits for design approval |

### Existing pages

- Home — `/`
- Blog — `/blog/`
- Contact Us — `/contact-us/`
- Meet the Owner — `/meet-the-owner/`
- Locations — `/locations/`
- Practitioners — `/practitioners/`
- Our Story — `/our-story/`
- Privacy Policy — `/privacy-policy/`
- Refund and Returns Policy — `/refund_returns/`
- Shop — `/shop/`
- Cart — `/cart/`
- Checkout — `/checkout/`
- My Account — `/my-account/`

No keep/merge/redirect decisions have been assigned. Those decisions wait for design and information-architecture approval.

## Current navigation inventory

### Main Navigation Menu

- Home
- Our Story
- Blog
- Events
- Shop
  - Shop 355+ Brands at Fullscript
  - Shop Designs for Health
  - Shop Peptides & Injectables
  - Rebekah's Exclusives
    - Allergy/Cold/Flu
    - Cleanse/Detox
    - Immune
    - Magnesium
    - Mushrooms
    - Pain and inflammation
    - Stress
    - Weight Management
- Practitioners
  - Shop 355+ Brands at Fullscript
  - Shop Designs for Health
  - Shop LifeWave
- Locations

### Footer Menu

- Home
- Shop
- Meet the Owner
- Practitioners
- Contact Us

This is documentation only. The future sitemap and navigation wait for design approval.

## Current forms

Forminator contains two forms:

- Form ID 1064 — Contact Us
- Form ID 313 — Newsletter Form

Both forms must be preserved until their notification recipients, integrations, consent language, spam controls, and successful delivery are documented and retested.

## ACF inventory

One field group is present: **Medical Practicioner fields**.

Fields:

- Medical Service
- Medical Center
- Medical Practitioner Full Name
- Medical Practitioner Description
- Medical Practitioner Contact Info
- New Field

The generic “New Field” entry needs review before migration. No field was deleted or renamed.

## Bricks reference inventory

Published Bricks templates:

- Blog Post Archive — Bricks
- WooCommerce Single Product — Bricks
- Shop & Product Categories — Bricks
- Footer — Bricks
- Main content — Bricks
- Header — Bricks

Bricks is the active theme. These templates should remain available as content/layout references until the replacement theme is activated and equivalent global/site templates are verified.

## Plugin inventory

### Active

- Admin and Site Enhancements (ASE) 8.9.0
- Advanced Custom Fields 6.8.6
- Event Tickets 5.29.0.1
- Extendify WordPress Onboarding and AI Assistant 3.1.3
- Forminator 1.55.1
- Google for WooCommerce 3.7.3
- Import and export users and customers 2.4.2
- Inbox Pro Web Chat Widget 1.0.0
- PDF Invoices & Packing Slips for WooCommerce 5.15.2
- Pledged Plugins PCI Gateway for NMI and WooCommerce 1.2.11
- Site Kit by Google 1.183.0
- The Events Calendar 6.17.0
- WooCommerce 10.9.4
- WooCommerce Smart Coupons 9.79.0
- WooCommerce Tax 3.6.9
- WooCommerce.com Update Manager 1.0.3
- WooPayments 10.6.0
- Wordfence Security 8.2.2
- WordPress Importer 0.9.5
- WP Mail SMTP 4.9.0
- Yoast SEO 28.0

### Inactive

- Breeze 2.5.9
- Cloudways Partner Hub 4.0.0
- CookieYes 3.5.3
- Object Cache Pro 1.25.5
- Simple Cloudflare Turnstile 1.41.1
- WPCode Lite 2.3.7

No WPCode snippet migration is currently required because WPCode Lite is inactive. This does not rule out custom code stored in the theme, ASE, mu-plugins, or another location.

## Taxonomy inventory and cleanup flags

- Blog categories: 4 total — Blog (18), Q&A (2), Recipe (2), Uncategorized (0).
- WordPress tags: 227 total. Many are single-use, duplicate, misspelled, or event-specific. Cleanup decisions wait for SEO/content review.
- Medical service terms: 17.
- Product categories: 13.
- Product tags: 65, including several unused terms.
- Event location categories: Clarkston, Grand Blanc, Lake Orion, and Lapeer.

No taxonomy terms were edited.

## Performance baseline

| Test | TTFB | Total transfer time | HTML bytes |
|---|---:|---:|---:|
| Cached run 1 | 0.457 s | 0.700 s | 129,253 |
| Cached run 2 | 0.484 s | 0.729 s | 129,253 |
| Cached run 3 | 0.529 s | 0.777 s | 129,253 |
| Cache-bypassed request | 3.585 s | 3.884 s | 128,991 |

Interpretation: server-level page caching masks a slow uncached application response. Before scaling the server, verify Redis/Object Cache Pro, Cron Optimizer, PHP version, PHP workers, slow PHP requests, database load, and the effect of the active WooCommerce stack.

## Cloudways items requiring account-level verification

These could not be confirmed solely from WordPress and public response headers:

- On-demand and scheduled backup configuration
- Redis service status
- Object Cache Pro toggle/status
- Cron Optimizer status
- Current PHP version
- PHP worker and PHP-FPM configuration
- Cloudflare integration/status
- Bot protection and firewall configuration
- CPU, RAM, disk, database, and PHP monitoring history
- New Relic/APM availability

No Cloudways setting should be changed until the account is inspected and a backup is confirmed.

## Deferred until design approval

- Future sitemap and navigation
- Page keep/merge/redirect decisions
- SEO redirect worksheet
- Image selection, cropping, resizing, and compression
- Replacement-theme global styles
- Header, footer, page, post, practitioner, testimonial, and event template construction
- Removal of Bricks
- Deactivation of WooCommerce and commerce add-ons

## Next decision session

When the design is approved, use this inventory to decide:

1. Replacement theme/builder and the exact all-free Phase One stack.
2. Approved page architecture and navigation.
3. One-to-one URL dispositions.
4. Whether Phase One includes event registration.
5. Whether practitioners and testimonials remain structured content types.
6. Whether chat, newsletter, and contact forms remain and where submissions go.
7. How Phase Two commerce data and URLs will be preserved while WooCommerce is inactive.
