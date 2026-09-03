# Rebekah's Phase Two Ecommerce Mockup Inventory

Updated: 2026-09-01
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
2. **Main homepage ecommerce integration** — keep `/` as the existing website homepage, preserve every current page component, and add only clear ecommerce entry points and a focused shopping section.
3. **Shop homepage + product catalog** — use `/shop/` as both the store homepage and reusable catalog system, with store introduction, wellness-goal discovery, Clarkston fulfillment/help guidance, product cards, search, sort, desktop/mobile filters, pagination, breadcrumbs, and empty/no-results behavior. Product-category, brand, collection, and product-search archives reuse the catalog pattern. Do not create a duplicate `/online-store/` landing page.
4. **Product-page templates** — create one simple-product example and one variation-product example. Include gallery, product facts, price/stock placeholders, quantity/add-to-cart, cautions, shipping/handling note, and related products.
5. **Purchase path** — create a cohesive cart, checkout, and order-confirmation prototype. These may be a single linked local prototype rather than three unrelated polished pages.
6. **Account system** — login/register, password reset, account dashboard, addresses, order history, and order-detail states.
7. **Store states and components** — no results, no products in a filter, out of stock, unavailable variation, missing image, cart empty, coupon excluded, validation/payment error placeholders, loading, and mobile filter drawer. Complete desktop, tablet, phone, keyboard, focus, label, contrast, reduced-motion, and message review across all seven systems.
8. **Client approval** — approve the complete seven-system set before any WordPress implementation or application to the 25-product pilot.

## Mockup version-control rule

- Each of the seven systems has its own revision sequence beginning at `v1.1`.
- Each reviewed revision is preserved as a separate file and advances to `v1.2`, `v1.3`, and so on.
- The local review hub points only to the newest reviewable version; earlier versions remain available as superseded history.
- Internal approval and client approval are separate statuses tied to an exact version.
- `PHASE-TWO-MOCKUP-VERSION-REGISTER.md` is the authoritative local revision log and must be updated with every revision or approval change.

## Decisions already established

- Clarkston is the only fulfillment location for the pilot.
- Allow two business days to pack and ship.
- Continental United States only.
- USPS live rates were selected for eventual implementation, but no rate promise or free-shipping threshold belongs in the mockups.
- Product reviews, coupons, gift certificates, store credit, and loyalty are excluded unless later approved.
- Guest checkout remains available. Optional customer accounts are created without staff approval and use the standard WooCommerce email, password, and recovery flow. Customer 2FA is not planned. Staff/administrator backend protection is configured separately during staging and does not appear in the customer experience.
- Backorders off and a one-unit inventory buffer are proposed operational defaults, still subject to testing and approval.
- Todd approved the final page architecture on 2026-08-31: `/` remains the main website homepage with ecommerce integration, `/shop/` is the store homepage and catalog, and there is no separate `/online-store/` landing page.

## Provisional items that must not be presented as final

- Which of Rebekah's approved brand and wellness-category choices are enabled for the first 25 products, each product's mapping to those choices, and the final collection and related-product rules.
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
- [x] `online-store-header-footer-add-on-v1.5.html` is the current client-approved review file and uses the actual current theme header/footer implementation as its base—not the superseded v1.9 study. Its second row contains only Online Store, product search, My Account, and Cart/count; the fifth Online Store footer section extends the approved four-section system. Header and footer enter their tablet states together at 1040px. In the local review, the header, tablet, and footer Online Store links open the preserved Shop Homepage + Product Catalog v1.8 preview; production will use `/shop/`. Earlier v1.1 through v1.4 review iterations and the pre-rule `v1.0.0` filename remain preserved as history.
- [x] Header + Footer Ecommerce Add-On v1.5 was approved internally by Todd and approved by Rebekah on September 2, 2026. Its predecessor v1.4 remains preserved as superseded history. If revised, the next version is v1.6 and requires new exact-version approval.
- [x] `main-homepage-ecommerce-integration-v1.9.html` is the current client-approved page-body-only version. It was approved internally by Todd and approved by Rebekah on September 2, 2026. It preserves every visual element and the v1.8 removal of the decorative shopping-path numbers, while correcting the Shop Online hero button so the local review opens the separate Shop Homepage + Product Catalog preview; production will use `/shop/`. Rebekah's email restated that required destination, which this exact version already satisfies. Global header, menu/navigation bar, store utility bar, and footer remain excluded. v1.8 is superseded without approval, and v1.7 remains preserved as earlier approved history. Any later revision advances to v1.10.
- [x] `shop-catalog-template-v1.9.html` is the current page-body-only client-requested revision. Todd approved this exact version internally on September 3, 2026; Rebekah's exact-version confirmation remains pending. It preserves the approved v1.8 catalog and replaces the former product-form and price filters with exactly two compact expandable groups: Brands and Wellness Categories. Both desktop filters and the mobile drawer contain Rebekah's nine brand choices and 21 wellness categories, synchronized checkbox state, combined search/filter behavior, counts, clear/reset recovery, and representative local filtering. v1.8 is preserved as the client-reviewed version superseded by this requested revision. Real Revel/Kosmos product data, images, inventory, pricing, enabled filter options, and per-product taxonomy mapping remain separate implementation-validation gates. Any later design revision advances to v1.10.
- [x] `product-page-templates-v1.7.html` is the current page-body-only version and was approved internally by Todd on August 31, 2026 and approved by Rebekah on September 2, 2026. It preserves the complete simple and variation product system, three-view gallery behavior, approved sitewide missing-photo treatment, quantity and variation controls, related products, and the friendly phone-help line beside Add to Cart. It removes the repeated Shipping & Help card and gives both examples compact Product Details plus a collapsed Directions & Warnings disclosure that appears only when verified packaging, manufacturer/supplier materials, or approved catalog data exists; otherwise the entire disclosure is omitted. Real product media, connected catalog data, and verified directions/warnings content remain separate Revel/Kosmos and content-validation gates. Any later design revision advances to v1.8. v1.1 through v1.6 are preserved as superseded history.
- [x] `purchase-path-mockup-v1.6.html` is the current page-body-only version and was approved internally by Todd on August 31, 2026 and approved by Rebekah on September 2, 2026. It includes the connected cart, approved missing-photo cart treatment, empty-cart recovery, guest-checkout fields, validation, order review, customer-facing confirmation, and working representative interactions. It contains no Clarkston, pilot, demo, prototype, or visible local-mockup wording and does not process payment, submit customer data, calculate live shipping or tax, send email, alter inventory, or place an order. Connected payment, shipping, tax, email, order, and Revel/Kosmos validation remain separate gates. Any later design revision advances to v1.7. v1.1 through v1.5 are preserved as superseded history.
- [x] `customer-account-system-v1.6.html` is the current page-body-only version and was approved internally by Todd on September 1, 2026 and approved by Rebekah on September 2, 2026. It preserves guest checkout, automatic optional account creation, password recovery, dashboard, addresses, order history, order-detail states, and representative interactions while removing the unapproved customer 2FA screen and add-on dependency. Guest checkout is explicitly located in the checkout flow because it creates no account. Standard My Account remains part of WooCommerce core; staff/administrator backend protection is a separate staging task and is not customer-facing. Any later design change advances to v1.7. v1.1 through v1.5 are preserved as superseded history.
- [x] `store-states-components-v1.5.html` is the current page-body-only version and was approved internally by Todd on September 1, 2026 and approved by Rebekah on September 2, 2026. It covers no search results, empty filter combinations, out of stock, unavailable variations, the approved sitewide green-and-honey “Product photo coming soon” rule, loading, empty cart, checkout validation, payment failure, the mobile filter drawer, and the responsive/accessibility checklist. Provider-specific and connected-service messages remain implementation-validation inputs. Any later design change advances to v1.6. v1.1 through v1.4 are preserved as superseded history.
- [x] All seven exact current systems are internally approved. Systems 01, 02, and 04–07 are client-approved; Shop Homepage + Product Catalog v1.9 now awaits only Rebekah's exact-version confirmation. No design approval authorizes staging, WordPress implementation, integrations, purchases, or live-site changes.
