# Rebekah's Phase Two Ecommerce Mockup Inventory

Prepared: 2026-08-24  
Mode: Local mockup work only

## Hard boundary

- No Cloudways work.
- No staging or live-site work.
- No server upgrade.
- No paid services, trials, extensions, or purchases.
- No WooCommerce, Revel, Kosmos, USPS, tax, or payment configuration.

## Authority and visual direction

The canonical Phase One planning sheet explicitly excludes ecommerce. Its approved visual direction still applies: FINAL Client Homepage Mockup v1.21 (`third-mockup.html`), Our Story v1.1.6 (`story-mockup-v1.1.6.html`), and the approved Phase One header/footer system.

The current Phase Two scope comes from the user's 2026-08-24 instruction, `PHASE-TWO-MASTER-CHECKLIST.md`, `PHASE-TWO-ECOMMERCE-READINESS.md`, `PHASE-TWO-CURRENT-EXECUTION-BOUNDARIES-2026-08-21.md`, and prior project-task decisions.

## Existing local work recovered

| Local artifact | Reusable direction | Needs correction before approval |
| --- | --- | --- |
| `online-store-homepage/index.html` | Strong store-homepage composition, wellness-goal discovery, category storytelling, local-help emphasis | Uses alternate global chrome; contains some claims and categories that must remain provisional until the pilot product set is known |
| `supplements-category/index.html` | Strong category hero, product-grid rhythm, desktop filter layout, responsive concept | Uses alternate global chrome; assumes a free-shipping threshold, product reviews, wishlist behavior, stock/content details, and taxonomy that are not approved |
| `wordpress/theme/rebekahs-2026/header.php`, `footer.php`, `assets/css/chrome.css`, and `assets/css/global-chrome.css` | Authoritative current approved/live header and footer markup, content, social links, desktop centering, sizing, and responsive behavior | Extend only with Online Store, search, account, cart/count, and one fifth store footer section |
| `header-footer-mockup-v1.11.html` | Closest standalone approved header/footer study and useful local comparison source | Older than the current theme implementation; verify against the theme before reuse |

## Build and approval order

This follows the same system-first workflow used in Phase One: approve reusable foundations before building sibling screens.

1. **Online Store header + footer** — extend the approved header, responsive menu, announcement bar, account/cart/search access, and footer. Do not redesign the full global system.
2. **Online Store homepage** — create the actual ecommerce landing page with store introduction, Shop All path, category/brand/wellness-goal discovery, featured products, and product-help guidance. Reconcile the recovered early store-home concept into the approved Phase One chrome.
3. **Shop/catalog template** — create one reusable archive system with product cards, search, sort, desktop/mobile filters, pagination, breadcrumbs, and empty/no-results behavior. Product-category, brand, collection, and product-search archives reuse this template.
4. **Product-page templates** — create one simple-product example and one variation-product example. Include gallery, product facts, price/stock placeholders, quantity/add-to-cart, cautions, shipping/handling note, and related products.
5. **Purchase path** — create a cohesive cart, checkout, and order-confirmation prototype. These may be a single linked local prototype rather than three unrelated polished pages.
6. **Account system** — login/register, password reset, account dashboard, addresses, order history, and order-detail states.
7. **Store states and components** — no results, no products in a filter, out of stock, unavailable variation, missing image, cart empty, coupon excluded, validation/payment error placeholders, loading, and mobile filter drawer. Complete desktop, tablet, phone, keyboard, focus, label, contrast, reduced-motion, and message review across all seven systems.
8. **Client approval** — approve the complete seven-system set before any WordPress implementation or application to the 25-product pilot.

## Decisions already established

- Clarkston is the only fulfillment location for the pilot.
- Allow two business days to pack and ship.
- Continental United States only.
- USPS live rates were selected for eventual implementation, but no rate promise or free-shipping threshold belongs in the mockups.
- Product reviews, coupons, gift certificates, store credit, and loyalty are excluded unless later approved.
- Guest checkout with optional accounts is the proposed default, still subject to client approval.
- Backorders off and a one-unit inventory buffer are proposed operational defaults, still subject to testing and approval.

## Provisional items that must not be presented as final

- Exact WooCommerce categories, brands, health goals, collections, filters, and related-product rules.
- Product names, prices, images, claims, ratings, stock counts, variation labels, and shipping exceptions.
- Free-shipping thresholds, handling fees, delivery estimates, tax treatment, and PO-box rules.
- Payment-provider branding and payment-error behavior.
- The notification email address.

## Current status

- [x] Project files, canonical sheet, and relevant prior tasks reviewed.
- [x] `phase-two-ecommerce-mockup-sheet.html` created as the single local Phase Two review hub. It tracks seven reusable approval systems, the complete downstream phase plan, and nothing-missed coverage while separating policy, order-email, and commerce-rule approvals from visual templates.
- [x] `Rebekahs-Phase-Two-Ecommerce-Workbook.xlsx` expanded into the seven-tab local master tracker: approval set, templates/global work, product/content requirements, decisions/access, implementation plan, QA/launch gates, and scope/costs.
- [x] Existing local ecommerce concepts audited.
- [x] Mockup order and guardrails reconstructed.
- [x] Header/footer v1.0.0 created locally and superseded after Todd's first review.
- [x] Header/footer v1.1.0 restored the Phase One order but remained incorrect at tablet width and used unclear internal naming.
- [x] Header/footer v1.2.0 was rejected because its three shop links disappeared on tablet/phone and it replaced the approved four-section expandable footer.
- [x] Header/footer v1.3.0 was rejected because it still treated the ecommerce navigation and footer as a redesign instead of a literal addition to the approved components.
- [x] `online-store-header-footer-add-on-v1.0.0.html` is the current review file and now uses the actual current theme header/footer implementation as its base—not the superseded v1.9 study. Its second row contains only Online Store, product search, My Account, and Cart/count; the fifth Online Store footer section extends the approved four-section system. Header and footer now enter their tablet states together at 1040px.
- [ ] Approved Header + Footer Ecommerce Add-On v1.0.0 reviewed by Todd.
- [ ] Online Store homepage reconciled.
- [ ] Shop/catalog template reconciled.
- [ ] Product-page templates created.
- [ ] Purchase path created.
- [ ] Account system created.
- [ ] Search and edge states created.
