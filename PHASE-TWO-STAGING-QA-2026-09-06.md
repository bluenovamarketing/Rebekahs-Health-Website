# Rebekah’s Phase Two Ecommerce — Staging Implementation and QA Record

Date: 2026-09-06
Environment: Cloudways staging only
Current deployment: Blue Nova Phase Two staging installer v1.6 plus in-place staging correction pass v1.9
Result: Approved storefront structure implemented; staging-only operational configuration is in progress while the Kosmos/Revel access gate and staging-runtime stability are resolved

## Exact approved sources

| System | Approved version | Staging result |
| --- | --- | --- |
| 01 Header + Footer Ecommerce Add-On | v1.5 | Implemented and responsive behavior verified |
| 02 Main Homepage Ecommerce Integration | v1.9 | Implemented; every store entry links to `/shop/` |
| 03 Shop Homepage + Product Catalog | v1.9 | Implemented at `/shop/` |
| 04 Product Page Templates | v1.7 | Implemented for simple and variable-product presentation |
| 05 Purchase Path | v1.6 | Cart, checkout, and order-confirmation structures implemented |
| 06 Customer Account System | v1.6 | Account routes, navigation, login, reset, addresses, and order-history structures implemented |
| 07 Store States + Components | v1.5 | Empty, no-results, no-image, out-of-stock, loading, and validation treatments implemented |

Systems 02–07 remain page-body-only approval sources. On WordPress staging they correctly inherit the separately approved System 01 global header/footer. Review-only labels, annotations, version badges, and explanatory outlines are intentionally absent from the website.

## Four-layer verification

### 1. Approved-version and code-source check

- Confirmed each WordPress implementation maps to the exact approved version listed above.
- Confirmed `/shop/` is the one store homepage/catalog route; no duplicate `/online-store/` landing page was introduced.
- Confirmed the Online Store utility entry and homepage Shop Online action link directly to `/shop/`, not to a section anchor.
- Superseded staging installers v1.1–v1.5 are inactive. Installer v1.6 remains the only active Phase Two installer; the September 6 v1.9 correction was applied directly to the same approved theme files and preserved as a separate local rollback/install package.

### 2. Page and component check

- Homepage: approved Shop Online hero action, three shopping-path cards, and real-product-driven featured area are present; the empty catalog uses the approved preparation message.
- Shop: introduction, wellness-goal discovery, search, sort, filter controls, products/result count, pagination foundation, trust/help content, and empty/no-results handling are present.
- Product: gallery/fallback, price, stock status, facts, variations, add-to-cart controls, cautions/directions treatment, shipping note, and related-products foundation are present.
- Cart/checkout/order confirmation: approved three-step purchase path, empty-cart state, customer/order sections, validation treatment, and thank-you structure are present.
- Account: guest/account entry, sign-in, password reset, dashboard, orders, addresses, and account navigation are present without customer-facing two-factor authentication.
- No broken images or horizontal page overflow were found on the verified routes.

### September 6 staging correction pass v1.7

- Corrected the missing-photo product state so the fallback occupies the full approved gallery width instead of the narrow thumbnail column.
- Isolated the old unpublished `TEST PRODUCT` record from client review: the obsolete “Finn” sentence, legacy price, and purchase controls no longer render. The record now shows a neutral private staging preview explaining that verified catalog content will replace it.
- Removed Downloads from the customer account navigation because this is a physical-product store. Direct requests to `/my-account/downloads/` now return to the account dashboard.
- Corrected the address book so Billing and Shipping render as equal adjacent cards on wider layouts and one clean column on phones.
- Restored the exact approved proportional wellness-goal artwork treatment (`400% auto` with the approved quarter positions) instead of vertically stretching the source strip.
- Purged both Breeze static cache and Cloudways Varnish after the correction.
- Rechecked the exact staging URLs in the Codex in-app browser. At the active 1024-pixel tablet viewport, all four goal cards use the approved proportional crop, address cards are equal and aligned, the no-image fallback equals its gallery width, Downloads is absent, the legacy sentence is absent, and horizontal overflow is zero.

### September 6 staging correction pass v1.8

- Matched the product-photo fallback more closely to the approved component: the centered circular brand mark is substantially larger, the label is prominent, and the finishing-touch and front-image labels are restored.
- Left-aligned both address action links with their corresponding Billing and Shipping headings while preserving equal adjacent cards on wider layouts and the single-column phone treatment.
- Quarantined every one of the 61 pre-connection WooCommerce product records on staging. They remain recoverable Draft records, but are explicitly marked as legacy, excluded from storefront visibility, and forced non-purchasable even if an old record is previewed.
- Replaced legacy product titles, categories, prices, descriptions, and purchase controls with neutral staging-preview content on authenticated legacy-product previews. No old ecommerce copy is presented as part of the new store.
- Purged Breeze static cache and Cloudways Varnish, then opened the Address, neutral Product Preview, and Shop pages in separate Codex in-app browser tabs. The address links share the exact left edge of their headings, the old cart notice is gone, the product preview contains no old test title, “Finn” copy, price, or Add to cart control, and Shop reports zero products available.

### September 6 staging correction pass v1.9

- Todd's direct comparison against the exact client-approved `product-page-templates-v1.7.html` correctly found that v1.8 still used an incomplete reconstruction of the missing-photo gallery. It had enlarged only the center mark and did not include the approved thumbnail rail, plant-mark SVG, second background circle, or complete decorative hierarchy.
- Replaced that entire no-image branch with the approved v1.7 structure: three 76-pixel Front/Facts/Ingredients controls, the 500-pixel minimum main stage, both proportional background circles, both gold dots, the exact inline plant mark, 112-pixel circular badge, 23-pixel two-line label, Finishing Touch kicker, and live Front/Facts/Ingredients image-view label.
- Verified the three controls change their current state, visible view label, and accessible main-image label. At proportional desktop widths the approved reference and staging now use the same 76-pixel thumbnail width, 116-pixel rendered badge diameter including border, 23-pixel label, three controls, and 500-pixel minimum stage.
- Purged Breeze static cache and Cloudways Varnish and reopened both the corrected staging product and exact approved v1.7 reference in separate Codex in-app browser tabs. The staging URL remains a real authenticated WordPress product-template preview; neutral product data is intentional until a verified Revel product is connected.

### 3. Responsive and interaction check

- Desktop: Phase One header remains centered; approved action buttons remain present; ecommerce utility row and six-part footer layout (intro plus five link groups) render correctly.
- Tablet: the main menu opens below both header rows at the correct offset; its items remain in the intended vertical order; Search, My Account, and Cart retain their text labels; the footer shows five equal adjacent tabs; only one compact panel opens at a time.
- Phone: store utilities reduce to compact icons; the menu opens below both header rows in the intended order; all five footer groups begin collapsed and behave as single-open accordions.
- Direct checks passed at desktop, 768-pixel tablet, and 390-pixel phone widths.

### 4. Data and staging-safety check

- WooCommerce core is active.
- All 61 legacy WooCommerce product records are preserved as Draft; zero legacy products are published and none were deleted.
- 21 approved wellness categories and 9 brand terms are seeded.
- WooPayments, the legacy NMI gateway, and unnecessary WooCommerce extensions remain inactive.
- No PayPal, Stripe, Fiserv/Clover, or other payment gateway is active.
- Blue Nova Staging Guard v1.1 remains active and blocks email, payments, webhooks, indexing, fulfillment side effects, and customer-facing scheduled actions.
- WordPress staging remains set to discourage search indexing.
- One staging-only WooCommerce REST API key exists for the previously approved Kosmos staging connection. Kosmos still has zero Actions; no Revel credential, Revel connection, webhook, product synchronization, order synchronization, or connector schedule was created.
- No Cloudways password-protection setting, hosting plan, live website, production database, or staging-to-live deployment was changed during this implementation pass.

## Deliberately deferred until real integration evidence exists

- One representative Revel product must establish the actual SKU/barcode mapping, source-field ownership, category behavior, images/descriptions transfer, overwrite behavior, inventory, weight, and variations.
- The exact Fiserv/Clover WooCommerce product and merchant access must be confirmed before any payment plugin or gateway is enabled.
- USPS/shipping, tax, order email, payment, refund, and Revel order-return tests require their respective approved operational decisions and credentials.
- Product/category SEO output, analytics events, realistic uncached checkout load, and transaction-aware backup frequency are final prelaunch gates.
- The separate deferred Phase One homepage performance/asset cleanup remains non-blocking technical work and is not part of the approved ecommerce mockup parity decision.

## September 6 inherited-settings correction pass

- The earlier product-isolation and presentation QA did not constitute a complete WooCommerce settings audit. A later page-by-page review found inherited values that should have been caught before connection readiness was reported.
- Corrected the inherited Lapeer store origin to the approved Clarkston address, disabled coupons globally, replaced the inherited inventory-notification recipient with Rebekah's approved recipients, retained stock management and the approved low-stock threshold of two, kept backorders off, and disabled unnecessary backorder notifications.
- Disabled both inherited shipping methods: Free Shipping tied to `FREESHIP100` and Priority Flat Rate. No shipping method is currently active; approved USPS Ground Advantage / Priority Mail configuration remains a separate test gate.
- Verified from WooCommerce Payments that every displayed gateway is off or not installed: WooPayments, PayPal, bank transfer, checks, cash on delivery, Stripe, Square, Visa Acceptance Solutions, and Airwallex. The installed legacy NMI gateway plugin is inactive. Blue Nova Staging Guard remains active as a second block on payments and webhooks.
- Verified the ecommerce plugin inventory: WooCommerce core, the current Phase Two v1.6 installer, and Blue Nova Staging Guard are active; WooPayments, NMI, Smart Coupons, WooCommerce Tax, Google for WooCommerce, PDF invoices, WooCommerce.com Update Manager, and superseded Phase Two installers are inactive.
- The pass paused when the staging host stopped answering and WordPress reported that saving was disabled until reconnection. Tax tables/options, WooCommerce emails, webhook records, scheduled actions, and the final orders/customers/coupons inventory remain unverified. No Revel or live-site action occurred.
- Independent HTTP checks isolated the outage: the production website continued returning HTTP 200 and a static CSS file on the staging hostname also returned HTTP 200, while the staging homepage, WordPress login, REST endpoint, and admin/PHP requests timed out without response. This is a staging dynamic-runtime/application problem, not an external-browser or DNS issue. No server service was restarted and no hosting plan was changed without separate authorization.

## September 6 pre-integration configuration and source-file audit

- Corrected the inherited WooCommerce store origin to the approved Clarkston address and retained USD.
- Changed product measurement units from kilograms/centimeters to ounces/inches.
- Enabled stock management, retained the approved low-stock threshold of two, kept backorders off, and disabled backorder notifications.
- Disabled coupons, product reviews, and all inherited shipping methods. No payment gateway is active.
- Preserved guest checkout and optional customer accounts, and enabled the password-setup-link workflow for newly created accounts.
- Replaced the inherited inventory-notification recipient with Rebekah's two approved operational addresses.
- Configured the shipping zone as the 48 contiguous states plus the District of Columbia, excluding Alaska, Hawaii, territories, and international destinations. USPS rates and PO-box blocking remain separate open gates.
- Audited `client-inputs/phase-two/Product_Export_Establishment_3 (70)_results.xlsx` read-only. It contains exactly 25 rows; all 25 have a product name, nonnegative price, unique barcode, category, `Active = Yes`, `Display on online and 3rd party = Yes`, and `Status = Updated`.
- Every SKU and product-description cell is blank. The file does not supply inventory quantity, product timestamp, weight, dimensions, images, or variation detail. Those fields cannot be assumed or corrected in Revel by Blue Nova; the controlled one-product proof must show what Kosmos actually reads.
- Selected `REBEKAH'S Energy 90 Caps` as the initial September 6 candidate. **Superseded on September 8:** Mark selected the freshly restocked `REBEKAH'S NAC 1000mg` and said Energy may be discontinued. NAC is the current candidate; selection does not authorize a sync or any Revel change.
- Updated the New Order administrator recipient to both approved addresses. The remaining Cancelled Order, Failed Order, and Payment Gateway Enabled recipient cleanup could not be completed or verified after the staging WordPress runtime returned `Connection lost` and repeated gateway timeouts.
- An independent hostname check immediately afterward returned HTTP 200 from the live website while staging timed out without response bytes. The client-facing website remained available.
- Standard tax-rate contents, USPS, PO-box validation, outbound email delivery, and transactional tests remain unverified and must not be marked complete.
- Kosmos ticket `#461376` remains `Being Processed`; no response has been posted and no Revel connection, Action, schedule, or synchronization was created.

## September 6 completed inherited-settings and clean-start audit

- After the authorized PHP-FPM restart and 45-minute stability check, resumed the exact unfinished WooCommerce audit in the Codex in-app browser. Staging remained responsive and the live website was not changed.
- Disabled WooCommerce tax calculation globally on staging so the client-supplied Michigan tax-exempt pilot rule cannot be overridden by the inherited `MI State Tax` rate. The inherited 6% row was retained dormant and recoverable rather than deleted. Final tax behavior still requires order-total testing after the real pilot product is imported.
- Verified coupons remain globally disabled. WooCommerce therefore does not expose the legacy coupon post-type screen or allow coupon entry in the cart/checkout; no coupon record was deleted.
- Verified all four administrator-facing WooCommerce email recipients. New Order, Cancelled Order, Failed Order, and Payment Gateway Enabled now use only `rebekahspureliving@gmail.com` and `clarkstonpurchaser@rebekahspureliving.com`. Customer-facing templates were left unchanged, and Staging Guard continues to block delivery.
- Verified zero WooCommerce webhooks. The sole REST API key is the expected `Kosmos eSync — Cloudways Staging` key assigned to Cody with read/write access to staging WooCommerce; it was not displayed, changed, revoked, or used to run an Action.
- Audited Action Scheduler: 5,236 historical records (4,901 complete, 307 failed, 23 pending, and 5 cancelled). The 23 pending rows are routine WordPress/WooCommerce/Forminator/WP Mail SMTP/Action Scheduler maintenance plus one inert WooPayments setup-sync row while WooPayments is inactive. Targeted searches returned zero Kosmos, Revel, Clover, NMI, or PayPal actions. No scheduler record was run, cancelled, or deleted.
- Inventoried 85 historical cloned WooCommerce orders: 73 completed, 3 cancelled, and 9 failed. They were preserved as historical client records and not altered. WordPress has zero registered Customer-role users; the five registered users are administrators.
- Reconfirmed all 61 inherited products are Draft and zero are published; the public Shop reports `0 products available`. All displayed online/offline payment methods remain disabled or uninstalled, the NMI and WooPayments plugins remain inactive, and both inherited shipping methods explicitly show `Enabled: No`.
- Rechecked exact public staging routes. Shop, Cart, and My Account returned HTTP 200 anonymously; Checkout safely redirected to the empty Cart and returned HTTP 200. In the browser, Shop showed zero products, Cart showed the approved empty state, and Checkout exposed no payment or shipping workflow.
- No Revel/Kosmos Action, sync, live-site edit, payment activation, USPS setup, paid service, hosting resize, password-protection change, data deletion, or staging-to-live deployment occurred.

## Next controlled step

### September 6 staging validation patch v1.10 and policy preparation

- Installed and activated the versioned `Blue Nova Phase Two Staging Installer v1.10` through the Codex in-app browser. The installer restricted itself to the exact Cloudways staging hostname and active Rebekah theme, backed up the existing ecommerce PHP file, and copied only the checkout-validation update.
- Added server-side rejection of PO-box delivery addresses to the current WooCommerce shortcode checkout and the WooCommerce Store API/Checkout Blocks order path. The rule checks the actual delivery address: Shipping when a separate shipping address is used, otherwise Billing.
- Tested the address matcher against `PO Box`, `P.O. Box`, `P O Box`, `Post Office Box`, and an address-line suffix; all were rejected. Ordinary street, Boxwood Lane, Post Office Road, and apartment examples remained allowed.
- WordPress activated the patch without a PHP fatal error and displayed the v1.10 completion record. Full customer-facing checkout rejection will be repeated after the first authorized product import because the current clean catalog correctly leaves checkout empty and redirects to Cart.
- Prepared `PHASE-TWO-ECOMMERCE-POLICY-DECISION-DRAFT-v1.2.md` locally as a review packet covering shipping, returns, cancellations, damaged/missing packages, onsite terms, ecommerce privacy data, and product disclaimers. It preserves all unresolved items as explicit client decisions and is not published or approved legal copy.
- No Revel connection or write, Kosmos Action, product import, payment or shipping-rate activation, paid service, live-site change, Cloudways resize, password-protection change, or staging-to-live deployment occurred.

The staging clean-start audit and PO-box implementation are complete. Wait for Kosmos ticket `#461376`, review its written answer, then discuss the exact Revel authorization and one-way test configuration with Todd. Do not connect Revel, create an Action, schedule a sync, or pull the pilot product until Todd gives separate action-time approval.

## September 9 email-gate update

- Mark confirmed that none of the 25 products has a shipping restriction.
- Revel case #03058403 requires Partner Connect Unlimited at $25/month per location, signed documents/order form, and client authorization for Revel to give Kosmos production Management Console credentials. Credentials would be released directly to Kosmos.

## September 14 connection-readiness update

- Revel stated that Partner Connect case #03058403 would be processed within one to two days and that the client would not be billed until October 1, 2026. Mark reported on September 9 that Revel released the API information to Kosmos.
- Mark's September 11 screenshot was superseded by live September 14 verification. The saved Kosmos standard-product Action explicitly selected establishment `3 Rebekah's - Clarkston`; the completed source log also carries `establishment=3` on every Revel product request.
- Kosmos still exposes no exact product selector. Todd approved a reversible staging-only timestamp test. The September 1 cutoff found 245 qualifying Clarkston products and excluded the intended `REBEKAH'S NAC 1000mg` product.
- Mark reported that Rebekah asked him to take over coordination of the WooCommerce/Revel connection. He does not need to add Blue Nova as a WooCommerce.com collaborator for the staging proof: Blue Nova already has staging WordPress/Cloudways access, and the dedicated staging WooCommerce REST connection is already present in Kosmos.
- Read-only public checks on September 14 returned HTTP 200 for the Phase Two staging homepage and `/shop/`. A separate anonymous request to the WordPress REST root timed out and remains a preflight watch item before relying on connector calls; no service restart or hosting change was made.
- Created one one-way Revel-to-staging-WooCommerce product Action and one manual Task. No WooCommerce-to-Revel Action exists, no automatic schedule is selected, and no Revel record or setting was changed.
- Rebekah's replacement Kosmos credential was saved in the private register and the dashboard login succeeds. The separate exact staging WordPress administrator row was rejected on one attempt after the import; Blue Nova stopped without retrying or resetting it.
- The fee, order form, location count, production-credential scope, and cancellation terms remain unapproved. Revel's email does not confirm that the first Action can be manual and one-way from Revel to staging WooCommerce with every schedule and WooCommerce-to-Revel writeback disabled.
- The manual Task completed with status `Done`: 245 category lookups returned GET 200 and 245 WooCommerce product creations returned POST 201, with no error-like transaction-log entries. The public WooCommerce Store API now returns 245 published products; NAC barcode `733739401854` returns no match. Staging Guard still blocks payment, email, webhook, and indexing side effects, but the Cloudways hostname is not HTTP-password protected.
- Do not run a broader timestamp import or enable any WooCommerce-to-Revel writeback. First restore staging WordPress administrator access and change the 245 imported products to Draft/quarantine without deleting them, then complete the NAC-specific mapping and inventory proof.
