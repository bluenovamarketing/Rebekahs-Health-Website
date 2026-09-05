# Phase Two Ecommerce Master Checklist

Prepared: 2026-08-17  
Scope: One fulfillment location, shipping only, Revel → Kosmos eSync → WooCommerce, likely Fiserv/Clover payments connected later, and a 25-product pilot.

This is the authoritative responsibility checklist for the client email, proposal, setup, pilot, and handoff.

## 1. Rebekah's required decisions and approvals

- [x] Confirm the single Revel establishment/store that will supply inventory and ship every online order: **Clarkston**.
- [x] Confirm the fulfillment origin address and normal order-handling time: **7093 Suite B, Dixie Highway, Clarkston, MI 48346; two business days to pack and ship**.
- [x] Mark supplied an attached Revel export identified as the 25 selected pilot products. Blue Nova still needs to audit the spreadsheet rows and confirm the final SKU/name list after the first synchronization test.
- [ ] Choose one shipping approach:
  - [x] **Live address-dependent rates:** **USPS selected**. Accurate product weights are required. Blue Nova will choose and test the packing method; Mark/Rebekah's team only needs to flag unusually large or bulky items. Test the free Octolize USPS live-rate plugin first and approve a paid packing upgrade only if representative carts prove it is needed.
  - [ ] **Flat-rate/free shipping:** approve the fixed charge and/or free-shipping threshold; no carrier extension or product weight is required for the pilot calculation.
- [x] Confirm the principal shipping region: **continental United States only** (no Alaska, Hawaii, or international shipping).
- [x] Do not allow shipping to PO boxes. Blue Nova will enforce this with checkout address validation rather than expecting the carrier-rate plugin to block it.
- [ ] Confirm any known products that require special handling or that the store already knows cannot be mailed. Routine products are assumed shippable.
- [x] Rebekah instructed Blue Nova to treat the pilot supplements as food/tax-exempt in Michigan. This is a client-supplied configuration instruction, not Blue Nova tax/legal advice; verify test-order totals before launch.
- [x] Approve the pilot operating defaults: **backorders off; one-unit inventory safety buffer unless the tested sync supports removing it; low-stock notice at two units; guest checkout allowed; optional customer accounts created without staff approval; no customer 2FA; staff/admin backend accounts protected separately; coupons, gift certificates, store credit, and loyalty excluded; product reviews off**.
- [x] Send applicable store/order notices to both **rebekahspureliving@gmail.com** and **clarkstonpurchaser@rebekahspureliving.com**.
- [ ] When the merchant account is ready, confirm that the Fiserv product is **Clover Ecommerce/Clover Payments for WooCommerce** or identify the actual gateway. This does not block product preparation, Kosmos/Revel synchronization work, store construction, shipping setup, or non-payment checkout configuration.

## 2. Accounts and access Rebekah must provide

- [x] Rebekah sent a Revel Management Console invitation for Blue Nova's separate `tbailey@bluenovainc.com` user. On 2026-09-02, Blue Nova verified that this user reaches the **Rebekah's - Clarkston** Management Console and can open Products, Inventory, and Settings without a permission error. The credential is stored only in the private `Cody WP Logins` Google Sheet. The specific Kosmos/API handshake will still be proven during the controlled one-product connection test.
- [x] Rebekah created and owns the Kosmos eSync account; its 14-day trial is active.
- [x] Kosmos pricing was disclosed before signup: $49 month-to-month, or $39 per month billed annually ($468/year).
- [ ] Use the official Kosmos plans/signup page: <https://kosmoscentral.com/esync-cloud-pricing-monthly>.
- [x] Rebekah supplied Kosmos sign-in information. Blue Nova successfully signed in on 2026-09-02 and reached the Kosmos dashboard; no synchronization Actions have been created. This is Rebekah's client-owned account shared with Blue Nova, not a separate Blue Nova user. The credential is stored only in the private `Cody WP Logins` Google Sheet on its own Rebekah/Kosmos row and is not copied into project files.
- [x] Kosmos support confirmed in ticket **#461373** that the eSync dashboard supports only one login email. Freshdesk support profiles may have multiple users, but those do not create separate eSync dashboard access. Keep Rebekah's email as the client-owned eSync login and use the securely stored shared credential; do not transfer the dashboard email to Blue Nova unless Rebekah explicitly requests an ownership change.
- [x] Activated a separate Blue Nova **Kosmos Freshdesk support profile** for `bluenovamarketing@gmail.com` on 2026-09-03, selected a unique support-only password, verified the login, stored it on its own row in the private `Cody WP Logins` register, and emailed the login details to Todd. This account is only for submitting and tracking support tickets; it does not grant a second eSync dashboard login or affect synchronization.
- [ ] Ask Kosmos to associate the Blue Nova Freshdesk profile with Rebekah's company/ticket history if shared visibility is desired. The newly activated profile currently shows no tickets.
- [x] Added a separate, fully populated Rebekah/Revel row to the private `Cody WP Logins` Google Sheet for the Blue Nova invited username and Clarkston console URL; access was last verified on 2026-09-02.
- [ ] After the Fiserv/Clover merchant account is active, provide Blue Nova the integration access needed to connect and test it. For Clover Payments for WooCommerce, this normally means the merchant ID plus sandbox/production public and private tokens supplied through a secure method. Do not email the owner's primary password or API secrets.
- [ ] Payment rates, monthly fees, merchant contracts, deposit terms, and chargeback pricing are decisions between Rebekah and Fiserv. Blue Nova only needs the final gateway name, an active account, the supported WooCommerce integration, and secure integration access.
- [ ] An existing USPS business account and account number have been confirmed. Blue Nova still needs usable USPS Business/Developer Portal access, or unavoidable owner approval/MFA, to create and authorize the REST API app and obtain its Consumer Key and Consumer Secret. The account number alone is not sufficient. Blue Nova performs the setup and does not ask Rebekah to email the Consumer Secret.
- [ ] If necessary product information exists only inside a manufacturer/supplier portal, provide authorized access or supply the missing material after Blue Nova's audit.

Blue Nova already has WordPress and Cloudways staging access. Do not ask Rebekah for those again. Do not request the owner's primary merchant-account password.

## 3. Revel preparation for each of the 25 products

For every selected pilot product, Rebekah or her Revel staff must:

- [ ] Confirm the product belongs to the designated fulfillment establishment.
- [ ] Set the product to **Active**.
- [ ] Enable **Display on online and 3rd party**.
- [ ] Confirm a unique SKU and/or barcode/UPC; do not reuse identifiers across products or variations.
- [ ] Replace register shorthand with a customer-friendly product name.
- [ ] Confirm the current selling price.
- [ ] Confirm the current, nonnegative inventory quantity at the fulfillment location.
- [ ] Keep the item in the normal Revel category the store already uses and correct obvious duplicate or misspelled categories. Blue Nova handles WooCommerce category mapping.
- [ ] Enter the brand/manufacturer where Revel supports it.
- [ ] **Mark/Rebekah's Revel team enters the accurate product weight in Revel** and flags unusually large, long, or bulky products. They do not create rules for one-, two-, or three-product boxes. Blue Nova will start with USPS weight-based/standard packing for ordinary pilot products, test multi-item carts, and request dimensions only for specific exceptions that cannot be rated accurately.
- [ ] Mark reported that he could not find a standard product-weight field. Blue Nova must locate the correct Revel/Kosmos/WooCommerce field during the one-product test. A conservative baseline weight may be used for staging-only USPS comparisons, but not for public checkout until representative rates prove it accurate. Do not enable Revel `Sold by Weight` merely to create a shipping weight.
- [ ] For matrix/variation products, confirm the parent name, option names, SKUs/barcodes, prices, and inventory for every variation.
- [ ] Flag only known shipping/special-handling exceptions.

Rebekah does not need to create a separate spreadsheet. Blue Nova will pull the products marked for online/third-party display, reconcile the intended 25, produce the SKU/name confirmation list, and send it to Rebekah for approval.

Mark Cobleigh, Rebekah's head purchaser, has confirmed that he manages Revel product groups, classes, categories, and backend changes. He is the identified client-side Revel contact for preparing the products and correcting fields needed for the website handshake.

## 4. Product content process

- [ ] Blue Nova tests one product before assuming which images/descriptions Revel and Kosmos transfer or overwrite.
- [ ] Blue Nova synchronizes the 25-product set after the first product passes.
- [ ] Blue Nova audits each product for:
  - [ ] Primary and gallery images.
  - [ ] Customer-facing description.
  - [ ] Ingredients and allergen information where applicable.
  - [ ] Directions and serving information.
  - [ ] Supplement Facts or other label details.
  - [ ] Warnings and known shipping restrictions.
- [ ] Blue Nova gathers available information and approved assets from authorized manufacturer/supplier sources.
- [ ] Blue Nova sends Rebekah one consolidated list containing only missing information, unavailable assets, and approval questions.
- [ ] Rebekah supplies or approves items that cannot be obtained from Revel or authorized sources.
- [ ] Blue Nova does not polish WooCommerce descriptions/images until overwrite behavior is proven.

New photography, extensive image editing, original copywriting, full-catalog cleanup, and manual bulk entry are outside the pilot unless separately approved.

## 5. Blue Nova setup responsibilities

- [x] The client started the Kosmos 14-day trial before technical testing began. Blue Nova verified the client-owned Kosmos dashboard login on 2026-09-02; confirm the remaining trial window immediately before creating the first synchronization Action.
- [ ] Rebekah approved exact Shop/Catalog v1.9 on September 3, 2026. Begin the infrastructure and connection sequence only after Todd authorizes the infrastructure work and confirms the safe working window. Do not leave an active test/handoff across Todd's blackout dates or weekends.
- [x] Complete the local pre-staging packet, rollback plan, one-product acceptance test, mockups, and dormant implementation scaffold without changing Cloudways, WordPress, Revel, or Kosmos.
- [ ] After the exact client approval and Todd's start authorization, perform the locked infrastructure order: increase CPU/RAM to 4 GB/2 vCPU, take a fresh production restore point, and create a protected same-server Cloudways staging clone. The former staging application was promoted to production and is no longer independent.
- [ ] Prevent staging indexing, customer emails, and accidental live payment/order side effects.
- [ ] Complete the deferred homepage asset/PHP cleanup needed for a stable Phase Two baseline.
- [ ] Reconcile the 35 visible/36 recorded legacy WooCommerce products and prevent duplicate pilot products.
- [ ] Confirm the retained Shop, Cart, Checkout, and My Account system pages.
- [ ] Reactivate WooCommerce and only the extensions required for the selected pilot workflow.
- [ ] Keep unnecessary commerce plugins inactive.
- [ ] Audit the preserved WooPayments and Pledged Plugins NMI configuration on staging. Both plugins are currently inactive and neither proves which processor should be used for Phase Two.
- [ ] Install and activate only the official/approved WooCommerce gateway for the merchant product Rebekah selects through Fiserv.
- [ ] Create the dedicated WooCommerce REST API user/key when Kosmos requires it.
- [ ] Draft the proposed WooCommerce category/field structure before access. Finalize and verify the Revel-to-WooCommerce mapping only after Revel access, Kosmos access, and one prepared test product are available.
- [ ] Configure basic store settings: address, currency, units, inventory behavior, guest checkout, optional customer accounts, and order emails.
- [ ] Configure and test two-step verification for the limited WordPress staff/administrator accounts only. Keep it outside the customer My Account flow, document recovery and escalation, and confirm whether the approved backend security tool adds any cost.

Blue Nova can complete the adaptable store foundation and generic non-payment checkout work before the merchant account is active. Product synchronization requires Revel and Kosmos access; live USPS testing requires the carrier extension/account connection; payment-specific end-to-end tests require Fiserv/Clover access.

## 6. Kosmos/Revel synchronization responsibilities

- [ ] Connect the selected Revel establishment and WooCommerce inside the client-owned Kosmos account.
- [ ] Configure only the required actions for products/prices/inventory and WooCommerce orders/customers.
- [ ] Test one action and one fully prepared product first.
- [ ] Verify the online/third-party flag controls website eligibility.
- [ ] Verify SKU/barcode, name, price, inventory, category, weight, and variation mapping.
- [ ] Determine image/description transfer and overwrite behavior.
- [ ] Confirm the sync schedule and review error/log visibility.
- [ ] Synchronize and reconcile all 25 pilot products.
- [ ] Confirm a WooCommerce order transfers to Revel.
- [ ] Confirm the order reduces inventory only at the designated fulfillment establishment.
- [ ] Confirm no custom mapping is required. Standard Revel fields are expected to work; the $150-starting mapping setup is only an unlikely exception requiring a separate quote and approval.

## 7. Shipping, tax, payment, and order configuration

- [ ] Configure the client-approved shipping regions and exclusions.
- [ ] For live USPS rates, first test the free Octolize **Shipping Live Rates for USPS for WooCommerce** plugin on protected staging. Limit it to Ground Advantage and Priority Mail and validate representative one-, two-, and three-product carts. Upgrade to Octolize PRO only if automatic multi-box/custom-box packing is required; do not purchase the official $109 WooCommerce USPS extension.
- [ ] Add checkout validation that rejects PO-box delivery addresses; this is separate from carrier-rate calculation and should not require another paid plugin.
- [ ] If flat/free shipping is selected, configure the approved rate and threshold using WooCommerce's built-in $0 settings.
- [ ] Test shipping calculations with multiple representative customer addresses and cart weights.
- [ ] Configure the tax rules supplied and approved by Rebekah.
- [ ] Connect the selected payment gateway and configure sandbox/test mode before any live transaction.
- [ ] Test successful payment, failed payment, cancellation where supported, and refund behavior.
- [ ] Confirm customer and administrator order emails are delivered and accurate.
- [ ] Confirm out-of-stock, backorder, and inventory-buffer behavior.

## 8. Storefront build responsibilities

Using the current custom Rebekah theme—not Bricks—Blue Nova must build/refine:

- [x] Prepare and internally approve the complete seven-system responsive ecommerce design set before full implementation: global commerce-navigation changes; main-homepage ecommerce integration; one reusable shop/category/archive template; simple and variation product-page examples; purchase-path wireframes/prototypes; the account system; and important product/error states with responsive/accessibility review.
- [x] Complete exact-version approval tracking before WordPress implementation. Rebekah approved systems 01, 02, and 04–07 on September 2, 2026; Todd approved the client-requested Shop Homepage + Product Catalog v1.9 filter revision on September 3, 2026. Rebekah confirmed exact v1.9 by direct email reply on September 3, 2026. All seven exact systems are client-approved. A later design change requires the next version and a new approval for that system.
- [x] Internally approve the exact Customer Account System v1.6: guest checkout, automatic optional accounts, sign-in, password reset, dashboard, addresses, orders, no customer 2FA, and separately managed staff/admin backend security. Rebekah approved this exact version on September 2, 2026.
- [ ] Preserve the approved Phase One header, footer, typography, color, and spacing system. Only add the Online Shop, cart/count, account access, and required store/policy links; do not redesign the full global chrome.

- [ ] Shop/catalog page.
- [ ] Online Store homepage.
- [ ] Product-category and brand browsing.
- [ ] Search and no-results behavior.
- [ ] Product cards.
- [ ] Product-detail template.
- [ ] Variation selection where applicable.
- [ ] Cart.
- [ ] Checkout.
- [ ] My Account, optional account creation, sign-in, password reset, addresses, and order history.
- [ ] Order-confirmation/thank-you experience.
- [ ] Store navigation, filters, featured products, health-goal/seasonal collections, and related products as approved.
- [ ] Responsive desktop, tablet, and mobile behavior.
- [ ] Product/category SEO titles, metadata, canonicals, structured data, sitemap/indexation rules, and analytics/business-event tracking.

Custom visual storefront design is separate from the 8–14-hour synchronization proof and must be identified separately in the Phase Two proposal.

## 9. Pilot acceptance tests

Do not expose the full catalog until every applicable test passes:

- [ ] Simple product creation/update passes.
- [ ] Matrix/variation product creation/update passes if variations are in scope.
- [ ] Active and online/third-party status works correctly.
- [ ] SKU, price, category, and fulfillment-location inventory match Revel.
- [ ] Images/descriptions follow the approved transfer/overwrite rules.
- [ ] Out-of-stock/backorder behavior is correct.
- [ ] Customer checkout calculates the correct shipping and client-approved tax.
- [ ] Selected-gateway success, failure, authorization/capture where applicable, void, and refund tests pass.
- [ ] WooCommerce order reaches Revel correctly.
- [ ] Inventory decreases only at the one fulfillment location.
- [ ] Customer/admin emails arrive with correct order and business information.
- [ ] Guest checkout works without creating an account; optional customer-account creation, sign-in, password reset, addresses, and order history all work. Staff/administrator backend 2FA is verified separately and never appears in the customer flow.
- [ ] Shop, product, cart, and checkout pass desktop/tablet/mobile QA.
- [ ] Privacy/consent, indexation, SEO markup, and analytics events work.
- [ ] Uncached cart/checkout and realistic load testing pass.
- [ ] Transactional backup and restore procedures are verified.
- [ ] Rebekah approves the final 25-product list, remaining content, and customer journey.

## 10. Launch follow-ups—not prerequisites for the synchronization test

- [ ] Confirm the existing approved Refund & Returns Policy applies to onsite WooCommerce orders.
- [ ] Add any needed return-address, cancellation, damaged-order, and lost-package clarification before public launch.
- [ ] Finalize ecommerce Shipping Policy, Terms, Privacy Policy, and Disclaimer changes required by accounts, payments, analytics, and processors.
- [ ] Train the people Rebekah chooses to maintain Revel and fulfill online orders; Blue Nova does not need employee names before training.
- [ ] Create staging on the existing server for light setup, then increase CPU/RAM from 2 GB/1 vCPU to 4 GB/2 vCPU before full commerce reactivation, synchronization/import work, or realistic load testing. Keep 4 GB through launch and the first 30 days, then reassess actual resource and checkout metrics before considering a CPU/RAM-only downgrade.
- [ ] Use actual pilot time, errors, and content gaps to estimate the full-catalog rollout.

## 11. Current cost assumptions

- WooCommerce core: **$0**.
- WooPayments: a real optional WooCommerce payment service powered underneath by Stripe, but **not planned** if Rebekah uses Fiserv/Clover.
- Likely Fiserv/Clover gateway: Clover's official WooCommerce plugin is currently **$0**. Rebekah handles her merchant-processing agreement directly with Fiserv.
- Kosmos: **$49 month-to-month**, or **$39 per month billed annually ($468/year)**. The client has already started the advertised 14-day trial; verify its exact expiration date in the account.
- USPS live-rate pilot plugin: **$0** for Octolize's free version. Its current PRO version is **£58/year** if realistic cart testing shows that automatic multi-product/custom-box packing is required.
- Alternative all-in-one USPS option: PluginHive is **$99/year** and includes rates, labels, tracking, and multi-package rules, but it is not the recommended first pilot because the current USPS product is newer and its product page has no customer reviews.
- Current client-owned integration software for the initial test: Kosmos is already in its advertised 14-day trial, and the recommended USPS pilot plugin is $0. Any paid shipping license will be selected only after representative cart testing.
- If the pilot can be completed during Kosmos's active trial and the free Octolize plugin rates accurately, initial integration-software cost may be $0. Otherwise, Kosmos continues at the client-selected monthly or annual rate and any paid carrier upgrade requires approval.
- Custom mapping: **not expected**. If the one-product test proves otherwise, Kosmos says setup starts at $150; obtain an exact quote before approval.
- Postage, packaging, merchant-processing arrangements, optional hosting increases, and out-of-scope content work are separate.

## 12. Working estimate

- 25-product data/synchronization proof: **8–14 Blue Nova hours**, normally **2–5 business days**.
- Launch-ready shipping-only operational pilot: **18–30 Blue Nova hours**, approximately **1–2 weeks**.
- Custom storefront design/build is a separate Phase Two scope and should not be described as part of the 25-product synchronization test.

No separate Blue Nova “pilot fee” has been defined or approved in the current record. Blue Nova's Phase Two pricing is handled separately by Todd and is not a client access prerequisite. The final full-catalog cost and schedule will be calculated from the test's actual time, product exceptions, content gaps, and integration results rather than guessed in advance.

## 13. Local readiness package completed September 2, 2026

- [x] Preserve all reviewed ecommerce mockup versions as immutable history. All seven exact current systems are client-approved; Shop/Catalog v1.8 is preserved as client-reviewed history and v1.9 contains Rebekah's requested filter revision.
- [x] Create `PHASE-TWO-PRE-STAGING-EXECUTION-RUNBOOK-v1.1.md` with staging safeguards, evidence gates, selective-deployment rule, and four rollback levels.
- [x] Create `PHASE-TWO-ONE-PRODUCT-ACCEPTANCE-TEST-v1.1.md` with preflight, field-observation, overwrite, order, inventory, and pass/fail records. Actual mapping remains an observed output of the authorized first pull.
- [x] Create `PHASE-TWO-LEGACY-CATALOG-CLEAN-START-PLAN-v1.1.md` to quarantine rather than delete the old WooCommerce catalog and prevent identifier collisions.
- [x] Create `PHASE-TWO-ECOMMERCE-POLICY-DECISION-DRAFT-v1.1.md` separating confirmed operating rules from focused client decisions and legal-review items.
- [x] Create a dormant local Phase Two WooCommerce scaffold with guarded PHP, scoped responsive CSS, an accessible filter drawer, store utility/footer renderers, the approved missing-photo treatment, and verified-content-only Directions & Warnings disclosure.
- [x] Add a `Pre-Staging Packet` tab to the Phase Two planning workbook and update the Implementation Plan and approval tracking to show all seven exact systems as client-approved, including Shop/Catalog v1.9 confirmed September 3, 2026.
- [x] Verify the dormant local scaffold at desktop, tablet, and phone widths with no horizontal overflow; verify filter-drawer focus/close behavior and the product disclosure interaction.
- [ ] Re-run PHP lint in the staging/runtime environment before activation; PHP CLI is not installed in the current local shell. JavaScript syntax passed locally.

These completed items do not authorize implementation. No Cloudways, staging, live website, WordPress deployment, Revel/Kosmos connection, purchase, subscription, or paid-service action occurred.
