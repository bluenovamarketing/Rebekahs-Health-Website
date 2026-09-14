# Phase Two Ecommerce Master Checklist

Prepared: 2026-08-17  
Scope: One fulfillment location, shipping only, Revel → Kosmos eSync → WooCommerce, likely Fiserv/Clover payments connected later, and a 25-product pilot.

This is the authoritative responsibility checklist for the client email, proposal, setup, pilot, and handoff.

## 1. Rebekah's required decisions and approvals

### September 14: Todd's 48-hour staging priority override

- Staging-only slowness is acceptable during controlled work; production availability on the shared server remains protected. Do not make a paid resize or shared-service restart without explicit approval.
- Batch independent checks into each controlled run. The older one-product-first wording is not a requirement to test one field at a time or to repeat broad imports for every field. A representative product set may exercise multiple cases together; preserve per-product baselines so results remain attributable.
- Immediate order: reconcile active replay 27285; validate/deploy staging presentation protection; complete the pilot identifier/source map; run a small inventory proof checking quantity, stock status, barcode match, no duplicates, and continued presentation preservation together.
- Use a targeted Woo REST test of the protection hook before another Kosmos replay. Do not manipulate live Revel products merely to trigger tests. Keep read/write direction established, automatic schedules off and production unchanged.
- Hold the one-unit buffer, approval email, production scheduling, launch polish and paid/payment decisions for their later gates. Do not let them block current catalog and integration work.
- A 48-hour work window is the prioritization target, not evidence that missing source content, vendor responses or go-live acceptance are complete. Escalate only a concrete missing decision/access/risk requiring Todd; continue unaffected work.
- Next combined matrix: protected title; protected populated long description; intentionally empty approved description; short-description preservation; unknown/unapproved barcode passthrough; unchanged barcode identity; price still updateable; inventory still updateable; no duplicate creation. Use distinct baselines for incompatible cases and log exact SKU/product IDs. Do not infer unseen field results from overall task Done.
- Add zero-stock/non-purchasable and replenishment/in-stock checks to the next compatible staging batch. Keep these separate from the deferred one-unit buffer. September 14 Kosmos reply confirms unchecking online eligibility stops stock/price sync and does not remove existing Woo products; Mark reports no other connected service. Normal sold-out products can remain visible if tested inventory/backorder behavior prevents buying. Deliberate withdrawal needs a separate hide workflow. No paid customization or production Revel edits authorized.

- [x] Confirm the single Revel establishment/store that will supply inventory and ship every online order: **Clarkston**.
- [x] Confirm the fulfillment origin address and normal order-handling time: **7093 Suite B, Dixie Highway, Clarkston, MI 48346; two business days to pack and ship**.
- [x] Mark supplied an attached Revel export identified as the 25 selected pilot products. Blue Nova completed the offline row audit on September 6, 2026: the file contains exactly 25 active, online-enabled products with unique barcodes, names, prices, categories, and `Updated` status. Every SKU and product-description cell is blank, and the export does not contain inventory quantity, product timestamp, weight, dimensions, images, or variation-detail fields. The controlled first synchronization must prove those missing fields and the actual identifier mapping before the other 24 products are allowed through.
- [x] Choose one shipping approach:
  - [x] **Live address-dependent rates:** **USPS selected**. Accurate product weights are required. Blue Nova will choose and test the packing method; Mark/Rebekah's team only needs to flag unusually large or bulky items. Test the free Octolize USPS live-rate plugin first and approve a paid packing upgrade only if representative carts prove it is needed.
  - [ ] **Flat-rate/free shipping:** approve the fixed charge and/or free-shipping threshold; no carrier extension or product weight is required for the pilot calculation.
- [x] Confirm the principal shipping region: **continental United States only** (no Alaska, Hawaii, or international shipping).
- [x] Do not allow shipping to PO boxes. Blue Nova will enforce this with checkout address validation rather than expecting the carrier-rate plugin to block it.
- [x] Mark confirmed on 2026-09-08 that none of the 25 selected products has a shipping restriction or known special-handling limitation.
- [x] Rebekah instructed Blue Nova to treat the pilot supplements as food/tax-exempt in Michigan. This is a client-supplied configuration instruction, not Blue Nova tax/legal advice; verify test-order totals before launch.
- [x] Approve the pilot operating defaults: **backorders off; one-unit inventory safety buffer unless the tested sync supports removing it; low-stock notice at two units; guest checkout allowed; optional customer accounts created without staff approval; no customer 2FA; staff/admin backend accounts protected separately; coupons, gift certificates, store credit, and loyalty excluded; product reviews off**.
- [x] Production customer-facing online-order communications use **clarkstonpurchaser@rebekahspureliving.com**. For controlled staging email tests, use Mark's **lapeerpurchaser@rebekahspureliving.com** address and exclude the Clarkston inbox to avoid confusing store staff. The earlier staging configuration still lists both Rebekah and Clarkston for administrator notices; reconcile that saved recipient list with Mark's newer instruction before delivery testing or launch.
- [ ] When the merchant account is ready, confirm that the Fiserv product is **Clover Ecommerce/Clover Payments for WooCommerce** or identify the actual gateway. This does not block product preparation, Kosmos/Revel synchronization work, store construction, shipping setup, or non-payment checkout configuration.

## 2. Accounts and access Rebekah must provide

- [x] Rebekah sent a Revel Management Console invitation for Blue Nova's separate `tbailey@bluenovainc.com` user. On 2026-09-02, Blue Nova verified that this user reaches the **Rebekah's - Clarkston** Management Console and can open Products, Inventory, and Settings without a permission error. The credential is stored only in the private `Cody WP Logins` Google Sheet. The specific Kosmos/API handshake will still be proven during the controlled one-product connection test.
- [x] Revel released the API credentials directly to Kosmos under case **#03058403** after Todd's September 9 email authorized Mark to complete the Partner Connect request. Revel identified the service as **Partner Connect Unlimited at $25/month per location** and stated that billing will not begin until **October 1, 2026**. Blue Nova did not view, copy, or store the Revel API secret. The live September 14 Action configuration and completed transaction log now prove the connection uses establishment `3 Rebekah's - Clarkston` and the tested direction reads Revel products and creates WooCommerce staging products; cancellation terms remain separately unverified.
- [x] Rebekah created and owns the Kosmos eSync account and began its advertised 14-day trial. A read-only in-app dashboard review on 2026-09-09 showed the account still accessible with zero Actions; My Account displayed only an `upgrade your plan` link and no active paid-plan name, renewal date, invoice, or charge. Todd's Gmail also contains no Kosmos receipt or payment confirmation. The separate Kosmos billing portal requires its own login, so actual card-charge status remains unverified.
- [x] Kosmos pricing was disclosed before signup: $49 month-to-month, or $39 per month billed annually ($468/year).
- [ ] Use the official Kosmos plans/signup page: <https://kosmoscentral.com/esync-cloud-pricing-monthly>.
- [x] Restored usable Blue Nova access to the client-owned Kosmos dashboard on 2026-09-14. With Todd's action-time confirmation, Blue Nova entered Rebekah's emailed replacement credential only into `kosmosesync.com`; the login succeeded. Todd then explicitly instructed Blue Nova to replace the rejected older password in the private `Cody WP Logins` sheet. The exact Kosmos password cell was updated and verified against Rebekah's email without displaying the credential or saving it in project files.
- [x] Kosmos support confirmed in ticket **#461373** that the eSync dashboard supports only one login email. Freshdesk support profiles may have multiple users, but those do not create separate eSync dashboard access. Keep Rebekah's email as the client-owned eSync login and use the securely stored shared credential; do not transfer the dashboard email to Blue Nova unless Rebekah explicitly requests an ownership change.
- [x] Activated a separate Blue Nova **Kosmos Freshdesk support profile** for `bluenovamarketing@gmail.com` on 2026-09-03, selected a unique support-only password, verified the login, stored it on its own row in the private `Cody WP Logins` register, and emailed the login details to Todd. This account is only for submitting and tracking support tickets; it does not grant a second eSync dashboard login or affect synchronization.
- [ ] Ask Kosmos to associate the Blue Nova Freshdesk profile with Rebekah's company/ticket history if shared visibility is desired. The newly activated profile currently shows no tickets.
- [x] Added a separate, fully populated Rebekah/Revel row to the private `Cody WP Logins` Google Sheet for the Blue Nova invited username and Clarkston console URL; access was last verified on 2026-09-02.
- [x] Reverified the separate Blue Nova Revel Management Console user on 2026-09-14. The live console header explicitly shows only **Rebekah's - Clarkston**, and the selected test product `REBEKAH'S NAC 1000mg` is active with Revel product ID `46236`, barcode `733739401854`, price `$27.00`, and **Display on online and 3rd party applications** enabled. This proves the human admin session and the NAC record are in Clarkston; it does **not** prove that the separate production API credential Revel issued directly to Kosmos is limited to Clarkston.
- [x] Prove the establishment scope of the Revel API connection used by Kosmos. The saved Action exposes and selects establishment `3 Rebekah's - Clarkston`; the source API requests recorded in the completed transaction log also carry `establishment=3`.
- [ ] Complete a verified NAC-specific test. Todd approved a broader reversible staging-only first run because Kosmos has no exact product selector. The September 1 cutoff created 245 Clarkston products but excluded NAC barcode `733739401854`. Restore staging administrator access and quarantine the published imports before choosing another cutoff or method.
- [ ] After the Fiserv/Clover merchant account is active, provide Blue Nova the integration access needed to connect and test it. For Clover Payments for WooCommerce, this normally means the merchant ID plus sandbox/production public and private tokens supplied through a secure method. Do not email the owner's primary password or API secrets.
- [ ] Payment rates, monthly fees, merchant contracts, deposit terms, and chargeback pricing are decisions between Rebekah and Fiserv. Rebekah received the Authorize.Net DocuSign packet but, on 2026-09-14, declined to sign the three-year agreement until Fiserv answers her written questions about the true card-not-present rate, pass-through/authorization/AVS/PCI fees, termination fee, recurring charges, and premium-card pricing. Blue Nova only needs the final gateway name, an active account, the supported WooCommerce integration, and secure integration access after those terms are resolved.
- [ ] An existing USPS business account and account number have been confirmed. Blue Nova still needs usable USPS Business/Developer Portal access, or unavoidable owner approval/MFA, to create and authorize the REST API app and obtain its Consumer Key and Consumer Secret. The account number alone is not sufficient. Blue Nova performs the setup and does not ask Rebekah to email the Consumer Secret.
- [ ] If necessary product information exists only inside a manufacturer/supplier portal, provide authorized access or supply the missing material after Blue Nova's audit.

Blue Nova retains Cloudways/application recovery access, but the exact current staging WordPress administrator row in the private register was rejected once after the import. Recover that staging-only login through Todd/Cloudways; do not ask Rebekah for her primary password. Do not request the owner's primary merchant-account password.

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

**Read-only safety note (2026-09-14):** Blue Nova attempted to prepare a Revel Advanced Product export containing only `Id` and `Display on online and 3rd party applications` so the live flags could be audited without editing products. Revel warned that exporting would irreversibly assign a barcode/SKU to every product missing one. The export was canceled. No export ran and no Revel barcode, SKU, product, or setting changed.

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
- [x] Rebekah approved exact Shop/Catalog v1.9 on September 3, 2026. Todd authorized the staging-readiness work, and the new same-server staging application was verified on September 6, 2026.
- [x] Complete the local pre-staging packet, rollback plan, one-product acceptance test, mockups, and dormant implementation scaffold without changing Cloudways, WordPress, Revel, or Kosmos.
- [x] Create the new same-server Cloudways staging clone. Cloudways application `Staging-rebekahspureliving.com` was verified on September 6, 2026, and a fresh application-level files-and-database restore point completed at `2026-09-06 12:30:43 UTC`.
- [x] Prevent staging indexing, customer emails, and accidental live payment/order side effects. WordPress search visibility is set to discourage indexing, and Blue Nova Staging Guard v1.1 is active to block mail, payment, webhook, indexing, and customer-facing scheduled actions. Todd controls Cloudways password protection and will enable it only when he wants it on; Codex must not change that switch without his explicit instruction.
- [x] Retain the current 2 GB/1-vCPU server for controlled WooCommerce activation and the one-product Revel/Kosmos proof. Recheck CPU, free memory, restarts, PHP/MySQL responsiveness, and uncached checkout/admin speed under the real workload; upgrade to 4 GB/2 vCPU only if the measured thresholds below show it is needed.
- [ ] Complete the separate Phase One homepage performance/asset cleanup before public launch. It is not part of the approved ecommerce mockup match and is not a prerequisite for the controlled one-product synchronization proof.
- [x] Apply the client-approved global ecommerce chrome to staging without rebuilding the Phase One header/footer: Online Store utility row, search, account, cart count, and fifth footer accordion. The final staging pass is installer v1.6, which corrected the tablet breakpoint, five equal footer tabs, compact accordion panel, two-row sticky header, and menu offsets to match approved System 01 v1.5 exactly.
- [x] Apply Main Homepage Ecommerce Integration v1.9 to staging: direct `/shop/` hero button, approved shopping-path section, optimized responsive imagery, and a product grid that populates from WooCommerce after catalog connection. Staging installer v1.2 created a separate backup before writing and was verified on September 6, 2026.
- [x] Activate WooCommerce core and implement the remaining approved page-body systems in the custom theme: Shop/Catalog v1.9, Product Pages v1.7, Purchase Path v1.6, Customer Account v1.6, and Store States/Components v1.5. Their structure and responsive states are on staging; data-driven refinement remains part of the one-real-product proof.
- [x] Apply Todd's September 6 staging correction pass v1.7: full-width missing-photo gallery fallback; neutral isolation of the obsolete unpublished TEST PRODUCT preview; no customer Downloads route; equal Billing/Shipping address cards; and proportional wellness-goal artwork matching approved Shop/Catalog v1.9. Breeze and Varnish caches were purged after verification.
- [x] Inventory and isolate the legacy WooCommerce catalog. Staging contained 61 recoverable legacy product records, not the earlier 35/36 estimate; all 61 are Draft, none are published, and none were deleted. Duplicate/collision reconciliation resumes only after the first Revel identifier is observed.
- [x] Confirm the retained Shop, Cart, Checkout, and My Account system pages. WooCommerce core is active and all four canonical staging routes render their approved Phase Two bodies with the staging guard active.
- [x] Activate WooCommerce core only for the selected pilot workflow.
- [x] Keep unnecessary commerce plugins inactive. WooPayments, the legacy NMI gateway, optional WooCommerce extensions, and superseded Phase Two installers v1.1-v1.5 remain inactive; only staging installer v1.6 remains active.
- [x] Audit the preserved WooPayments and Pledged Plugins NMI configuration on staging. Both gateway providers remain inactive, saved gateway settings were neutralized where applicable, and the active staging guard blocks payment side effects. Neither proves the final Phase Two processor.
- [ ] Install and activate only the official/approved WooCommerce gateway for the merchant product Rebekah selects through Fiserv.
- [x] Created the dedicated staging-only WooCommerce REST API key for `Kosmos eSync — Cloudways Staging` on 2026-09-06. The credential was transferred directly into Kosmos, was not copied into project files, and the one-time secret display was closed after verification.
- [x] Seed the approved WooCommerce browsing structure with 21 wellness categories and 9 brand terms. Final field ownership and Revel-to-WooCommerce mapping remain evidence from the first controlled product pull, not assumptions made in advance.
- [x] Complete and re-verify basic store settings: Clarkston origin; USD; ounces/inches; stock management; low-stock threshold of two; backorders off; guest checkout; optional customer accounts; approved inventory recipients; all four administrator-facing WooCommerce email recipients; tax and coupons disabled; zero webhooks; routine-only scheduled actions; 61 Draft/zero published legacy products; zero active payment methods; and zero active shipping methods. Delivery and transaction tests remain later gated work.
- [ ] Configure and test two-step verification for the limited WordPress staff/administrator accounts only. Keep it outside the customer My Account flow, document recovery and escalation, and confirm whether the approved backend security tool adds any cost.

Blue Nova can complete the adaptable store foundation and generic non-payment checkout work before the merchant account is active. Product synchronization requires Revel and Kosmos access; live USPS testing requires the carrier extension/account connection; payment-specific end-to-end tests require Fiserv/Clover access.

## 6. Kosmos/Revel synchronization responsibilities

- [x] Connected only the Cloudways staging WooCommerce account inside the client-owned Kosmos account on 2026-09-06. Kosmos recognizes `Rebekah's WooCommerce — Cloudways Staging`. Mark's September 11 screenshot shows that staging connection and the new Revel connection selected in the Create Action builder; Mark expressly said he had not started anything, and the screenshot shows no completed or running Action.
- [x] Selected the existing `REBEKAH'S NAC 1000mg` as the representative first product after Mark reported a fresh restock on 2026-09-08. Do not use the earlier `REBEKAH'S Energy 90 Caps` candidate because the client may discontinue it. Verify the NAC item's current Clarkston price and inventory read-only before any authorized import.
- [x] Verify that the Revel connection used by Kosmos is limited to the approved **Clarkston** establishment. On 2026-09-14 the saved standard-product Action exposed and selected `3 Rebekah's - Clarkston`; the completed transaction log independently shows each Revel source request carrying `establishment=3`.
- [x] Configure only the required one-way product Action for the initial staging proof. Created `Clarkston staging - Revel products to WooCommerce - manual pilot`; no WooCommerce-to-Revel order, customer, inventory, refund, or other writeback Action exists.
- [ ] Complete the selected NAC product proof. The first manual Product run completed without transport errors but its September 1 cutoff returned 245 Clarkston products and excluded NAC barcode `733739401854`. Staging administrator access is working; select a controlled cutoff/method that includes NAC, and contain the broad imports before client review or launch.
- [ ] Verify the online/third-party flag controls website eligibility.
- [ ] Verify SKU/barcode, name, price, inventory, category, weight, and variation mapping.
- [x] Prove one-way Clarkston inventory quantity transfer for an actually selected standard item. The September 14 manual Standard Inventory Task matched barcode to Woo SKU and verified `HERPHA SMOKER ASSIST 10Z` (`090900000019`) at Revel `2.0000` → Woo quantity `2` / public `2 in stock`. The broader 250-record batch produced only 7 successful updates, 233 expected no-match results, and 10 Woo lookup HTTP 503 responses, so clean incremental reliability and the approved one-unit buffer remain open before scheduling.
- [ ] Determine image/description transfer and overwrite behavior.
- [x] Confirm the sync schedule and review error/log visibility. The Task has no automatic schedule selected and ran manually. The final activity status is `Done`; all 245 Revel category lookups returned GET 200 and all 245 WooCommerce product creations returned POST 201, with no error-like log entries.
- [ ] Synchronize and reconcile all 25 pilot products.
- [ ] Confirm a WooCommerce order transfers to Revel.
- [ ] Confirm the order reduces inventory only at the designated fulfillment establishment.
- [ ] Confirm no custom mapping is required. Standard Revel fields are expected to work; the $150-starting mapping setup is only an unlikely exception requiring a separate quote and approval.

## 7. Shipping, tax, payment, and order configuration

- [x] Configure the client-approved shipping regions and exclusions. The staging zone is named `Continental United States` and contains the 48 contiguous states plus the District of Columbia; Alaska, Hawaii, territories, and international destinations are excluded. No shipping method is active while USPS access and testing remain pending.
- [x] Disable the inherited Free Shipping / `FREESHIP100` and Priority Flat Rate methods on staging. No shipping method remains active while the approved USPS setup is pending.
- [ ] For live USPS rates, first test the free Octolize **Shipping Live Rates for USPS for WooCommerce** plugin on protected staging. Limit it to Ground Advantage and Priority Mail and validate representative one-, two-, and three-product carts. Upgrade to Octolize PRO only if automatic multi-box/custom-box packing is required; do not purchase the official $109 WooCommerce USPS extension.
- [x] Add checkout validation that rejects PO-box delivery addresses; staging installer v1.10 added server-side validation for the current WooCommerce checkout and the Store API/Checkout Blocks path. Common PO-box formats and nonmatching street-address controls passed the local matcher test. Repeat the customer-facing rejection test after the first authorized product import makes checkout available.
- [ ] If flat/free shipping is selected, configure the approved rate and threshold using WooCommerce's built-in $0 settings.
- [ ] Test shipping calculations with multiple representative customer addresses and cart weights.
- [x] Configure the tax rules supplied and approved by Rebekah. Tax calculation is disabled for the current Michigan tax-exempt pilot; the inherited 6% Michigan row remains dormant and recoverable. Reconfirm totals after the first authorized product import.
- [ ] Connect the selected payment gateway and configure sandbox/test mode before any live transaction.
- [ ] Test successful payment, failed payment, cancellation where supported, and refund behavior.
- [ ] Confirm customer and administrator order emails are delivered and accurate.
- [ ] Confirm out-of-stock, backorder, and inventory-buffer behavior.

## 8. Storefront build responsibilities

Using the current custom Rebekah theme—not Bricks—Blue Nova must build/refine:

- [x] Prepare and internally approve the complete seven-system responsive ecommerce design set before full implementation: global commerce-navigation changes; main-homepage ecommerce integration; one reusable shop/category/archive template; simple and variation product-page examples; purchase-path wireframes/prototypes; the account system; and important product/error states with responsive/accessibility review.
- [x] Complete exact-version approval tracking before WordPress implementation. Rebekah approved systems 01, 02, and 04–07 on September 2, 2026; Todd approved the client-requested Shop Homepage + Product Catalog v1.9 filter revision on September 3, 2026. Rebekah confirmed exact v1.9 by direct email reply on September 3, 2026. All seven exact systems are client-approved. A later design change requires the next version and a new approval for that system.
- [x] Internally approve the exact Customer Account System v1.6: guest checkout, automatic optional accounts, sign-in, password reset, dashboard, addresses, orders, no customer 2FA, and separately managed staff/admin backend security. Rebekah approved this exact version on September 2, 2026.
- [x] Preserve the approved Phase One header, footer, typography, color, and spacing system. Only add the Online Shop, cart/count, account access, and required store/policy links; do not redesign the full global chrome. The client-approved System 01 v1.5 additions are implemented on staging through final installer v1.6.

- [x] Shop/catalog page.
- [x] Online Store homepage at the canonical `/shop/` route; no duplicate `/online-store/` landing page.
- [x] Product-category and brand browsing structure; live term/product validation remains part of the real-product proof.
- [x] Search, empty-catalog, and no-results behavior.
- [x] Product cards, including the approved missing-photo state.
- [x] Product-detail template.
- [x] Variation-selection presentation where applicable; actual variation data must be validated after a representative variable product synchronizes.
- [x] Cart.
- [x] Checkout structure and validation presentation; payment/shipping transaction tests remain gated.
- [x] My Account, optional account creation, sign-in, password reset, addresses, and order history.
- [x] Order-confirmation/thank-you template; a real test order remains gated.
- [x] Store navigation, filters, featured-products structure, wellness-goal/seasonal collections, and related-products structure as approved.
- [x] Responsive desktop, tablet, and mobile behavior, including approved System 01 menu and footer-accordion behavior.
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

- [x] Confirm the existing approved Refund & Returns Policy applies to onsite WooCommerce orders. Mark approved the recommended continuation of the current 30-day/unopened/original-packaging/proof-of-purchase rules on 2026-09-08 with Rebekah copied.
- [x] Record the client decisions for return destination/contact, cancellation, damaged or missing items, delivered-but-not-received review, return postage, opened/final-sale restrictions, and pilot shipping exceptions. Mark approved every recommendation on 2026-09-08 with Rebekah copied and then confirmed none of the 25 products has a shipping restriction; Policy Review Packet v1.2 contains the decisions.
- [ ] Finalize ecommerce Shipping Policy, Terms, Privacy Policy, and Disclaimer changes required by accounts, payments, analytics, and processors. Policy Review Packet v1.2 is prepared locally; it remains unpublished pending client decisions and qualified legal review.
- [ ] Train the people Rebekah chooses to maintain Revel and fulfill online orders; Blue Nova does not need employee names before training.
- [x] Create staging on the existing 2 GB/1-vCPU server and retain that plan through controlled WooCommerce activation and the one-product synchronization proof. Upgrade to 4 GB/2 vCPU only if CPU repeatedly exceeds roughly 80%, free memory falls below roughly 300–500 MB, auto-healing restarts recur outside maintenance, or uncached checkout/admin response materially degrades. Reassess again before public launch.
- [ ] Use actual pilot time, errors, and content gaps to estimate the full-catalog rollout.

## 11. Current cost assumptions

- WooCommerce core: **$0**.
- WooPayments: a real optional WooCommerce payment service powered underneath by Stripe, but **not planned** if Rebekah uses Fiserv/Clover.
- Likely Fiserv/Clover gateway: Clover's official WooCommerce plugin is currently **$0**. Rebekah handles her merchant-processing agreement directly with Fiserv.
- Kosmos: **$49 month-to-month**, or **$39 per month billed annually ($468/year)**. The client has already started the advertised 14-day trial; verify its exact expiration date in the account.
- Revel Partner Connect Unlimited: **$25/month per location** under Revel case **#03058403**. Revel processed the request, released the API information directly to Kosmos, and stated billing begins **October 1, 2026**. Confirm the charged location count and cancellation terms from the client account/order record before launch.
- USPS live-rate pilot plugin: **$0** for Octolize's free version. Its current PRO version is **£58/year** if realistic cart testing shows that automatic multi-product/custom-box packing is required.
- Alternative all-in-one USPS option: PluginHive is **$99/year** and includes rates, labels, tracking, and multi-package rules, but it is not the recommended first pilot because the current USPS product is newer and its product page has no customer reviews.
- Current client-owned integration software for the initial test: Kosmos began with an advertised 14-day trial, but its current conversion/billing status is unverified; Revel Partner Connect access has been released and its $25/month-per-location billing is scheduled to begin October 1, 2026; the recommended USPS pilot plugin remains $0. No paid USPS shipping-plugin license is approved by this checklist.
- The known recurring connector total would be **$74/month** with Kosmos month-to-month plus one Revel location, or a **$64/month equivalent** with Kosmos billed annually plus one Revel location. These are planning totals only; verify the Kosmos plan, trial status, Revel location count, taxes, order form, and cancellation terms before approval.
- Custom mapping: **not expected**. If the one-product test proves otherwise, Kosmos says setup starts at $150; obtain an exact quote before approval.
- Postage, packaging, merchant-processing arrangements, optional hosting increases, and out-of-scope content work are separate.

## 12. Working estimate

- Read-only connection review and authorization setup: **2–4 Blue Nova hours**, normally **one business day**, excluding any wait for Todd, Rebekah, or Kosmos support. Every persistent connection and every Action direction is reviewed before it is created or enabled.
- One-product Revel-to-staging-WooCommerce proof: **4–8 Blue Nova hours**, normally **1–2 business days** after one already-prepared Revel product is identified. Blue Nova/Codex does not edit the product, price, inventory, online flag, timestamp, category, or any other Revel field; any required Revel correction belongs to Rebekah's staff and adds client-wait time.
- Remaining 24-product pilot synchronization and reconciliation: **12–20 Blue Nova hours**, normally **3–5 business days** after the first product passes. Extra, missing, duplicate, stale, or incomplete records pause the run for one-at-a-time resolution rather than being bulk-fixed in Revel.
- Operational shipping, tax, email, account, checkout, accessibility, responsive, and performance QA: **12–20 Blue Nova hours**, normally **3–5 additional business days** after product data is stable and the required client decisions/access are available.
- Payment-gateway configuration, sandbox transaction/refund testing, final staging acceptance, cutover preparation, and launch QA: **8–16 Blue Nova hours**, normally **3–5 additional business days** after the final Fiserv/Clover product, delegated access, and test method are confirmed.
- Safe remaining calendar expectation: approximately **2–3 weeks of active work**, and potentially **3–4 calendar weeks** when client approvals, Revel data preparation, Kosmos support, shipping access, or merchant onboarding create waits. This is a planning range, not a promise to rush through gates.
- Custom storefront design/build is a separate Phase Two scope and should not be described as part of the 25-product synchronization test.

No separate Blue Nova “pilot fee” has been defined or approved in the current record. Blue Nova's Phase Two pricing is handled separately by Todd and is not a client access prerequisite. The final full-catalog cost and schedule will be calculated from the test's actual time, product exceptions, content gaps, and integration results rather than guessed in advance. No schedule pressure overrides the Revel read-only rule or the one-step-at-a-time approval gates.

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
