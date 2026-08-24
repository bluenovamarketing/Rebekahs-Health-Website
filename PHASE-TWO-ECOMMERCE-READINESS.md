# Phase Two Ecommerce Readiness and 25-Product Pilot

Prepared: 2026-08-17  
Client: Rebekah's Health & Nutrition

## Decision summary

Start Phase Two on a protected staging copy and use a controlled 25-product pilot before releasing a larger catalog.

The planned operating model is:

- **Revel** controls whether a product is eligible for online sale and owns operational data such as SKU/barcode, price, inventory, weight, status, and primary operational category.
- **Kosmos eSync** transfers approved product, price, inventory, order, and customer data between Revel and WooCommerce.
- **WooCommerce/WordPress** controls the storefront, checkout, product presentation, SEO, featured products, collections, and other online merchandising.

The working rule for staff is: **Revel controls whether the business sells it; WordPress controls how the website presents and promotes it.**

## Current verified baseline

- Phase One intentionally excluded onsite ecommerce. The canonical planning sheet still states that ecommerce is deferred.
- The current custom theme declares WooCommerce support, but Phase Two store templates still need to be designed and built. Older July recommendations to build the store with Bricks are obsolete; the production site now uses the custom Rebekah's theme with Elementor Free.
- The WooCommerce Shop, Cart, Checkout, and My Account pages were retained for Phase Two. They are currently noindexed and excluded from the sitemap.
- The pre-redesign inventory recorded 36 WooCommerce product records. Earlier ChatGPT research saw 35 products visible in the old shop. Those records must be reconciled before the pilot so they do not create duplicates.
- The complete commerce stack is preserved but inactive: WooCommerce, Google for WooCommerce, PDF Invoices & Packing Slips, Pledged Plugins NMI Gateway, Smart Coupons, WooCommerce Tax, WooCommerce.com Update Manager, and WooPayments.
- The July audit found no Revel/Kosmos REST API key or other evidence of an active Revel-to-WooCommerce synchronization. Reverify this at kickoff before creating any new key or connection.
- Current Cloudways capacity is 2 GB RAM / 1 vCPU. It is adequate for development and controlled testing. Budget the 4 GB / 2-vCPU plan for public ecommerce launch unless realistic load and checkout testing proves the smaller plan has adequate headroom.
- Cloudways daily backups and a recent on-demand backup are in place. Before live orders are accepted, approve an ecommerce-appropriate database backup frequency and recovery plan that limits loss of orders, customers, and inventory changes.

## Client kickoff gate: what Rebekah must have ready

Do not begin the integration build until the following items are complete or explicitly approved as deferred.

### 1. Business ownership and workflow

- [ ] Rebekah confirms internally that someone will maintain Revel product data and someone will monitor and fulfill online orders. Blue Nova does not need staff names before the technical pilot unless those people need access or training.
- [ ] Select **one Revel establishment/store as the sole online inventory and fulfillment location**. Every pilot order ships from this location.
- [ ] Confirm where orders will be packed, which staff will fulfill them, normal fulfillment days, order cutoff time, and the promised handling time.
- [x] The pilot is **shipping only**. Local pickup, multi-location inventory, and store-specific availability are outside the pilot.
- [ ] Decide whether backorders are prohibited, allowed globally, or allowed only for selected products.
- [ ] Decide whether an inventory safety buffer is needed to reduce overselling between sync runs.

### 2. Required account access

- [ ] Revel administrator or integration-level access is available to the authorized setup team.
- [ ] Rebekah can identify the selected fulfillment establishment inside Revel.
- [ ] Blue Nova verifies whether a Kosmos eSync account already exists and presents the required plan/cost to Rebekah for approval. Kosmos is the middleware that transfers product, inventory, and order information between Revel and WooCommerce; Rebekah does not need to configure it herself.
- [ ] A dedicated Revel/WooCommerce integration user can be created if Kosmos requires one.
- [ ] Keep **Fiserv/Clover** as the likely later payment integration. WooCommerce core cannot process cards by itself; WooPayments could process cards through its Stripe partnership, but it is not planned because Rebekah will not use PayPal and will probably use Fiserv/Clover. Payment activation does not block the non-payment build or Revel/Kosmos test.
- [ ] Rebekah confirms the shipping carrier/method, fulfillment origin address, package types, and handling expectations. The customer supplies the destination address at checkout. If checkout must calculate a live address-dependent rate, configure the chosen carrier integration; a carrier account is needed only if its rates or credentials will be used.
- [ ] Rebekah confirms the tax settings Blue Nova should implement. Blue Nova communicates only with Rebekah and does not contact her accountant or other advisers.
- [ ] An operational email address is approved for new-order, failed-payment, cancellation, refund, low-stock, and customer-service notifications.

Do not email or place API keys, gateway credentials, secrets, or payment information in project files. Create connection credentials only when the selected vendor's setup flow requires them.

#### Lean pilot access handoff

Only the following access should be requested for the pilot:

- [x] Blue Nova already has WordPress/Cloudways staging access. Do not ask Rebekah for it again.
- One Revel administrator/integration access grant that includes the designated fulfillment establishment. This is not a separate location login unless Revel has intentionally limited that user's establishment permissions.
- Rebekah has previously been told about Kosmos. Before signup, remind her that the pilot plan is currently $49 month-to-month and that Kosmos advertises a 14-day trial. She creates and owns the account with her business and billing information, then gives Blue Nova configuration access. Kosmos automatically moves approved products, prices, inventory, and orders between Revel and WooCommerce so staff do not have to maintain online inventory manually and the website is less likely to sell stock the store no longer has.
- After the final merchant product is selected, request delegated merchant/developer access that allows Blue Nova to configure the official WooCommerce integration, use sandbox/test mode, view transactions, run void/refund tests, review disputes, and contact gateway support. Do not request or share the primary owner's password or place API secrets in project files.
- A dedicated WooCommerce REST API user/key created by Blue Nova only when Kosmos setup requires it. This key belongs to WooCommerce/Kosmos and is separate from whichever payment gateway is selected.

No carrier/label account, employee roster, accountant contact, multi-location access, or pickup configuration is required for the shipping-only pilot.

### 3. Commerce rules Rebekah must decide

- [ ] Regions the store is willing to serve at launch; each customer's delivery address is entered during checkout.
- [ ] Shipping method/carrier. If the amount should depend on the customer's address, use live carrier rates calculated from the one fulfillment origin plus product/package weight and dimensions.
- [ ] Handling time and customer-facing delivery expectations.
- [ ] Package sizes and packing process.
- [ ] Products that cannot be shipped or require special handling.
- [ ] Whether PO boxes, Alaska/Hawaii, and international shipping are supported.
- [ ] Client-approved states and product types for tax collection.
- [ ] Client-approved treatment of tax on shipping charges.
- [ ] Before public launch—not before the 25-product synchronization test—confirm that the existing approved Refund & Returns Policy applies to onsite WooCommerce orders. Its preserved wording already covers the 30-day window, unopened/original-condition requirement, proof of purchase, refund method, return-shipping deduction, and nonrefundable shipping. Any return-address, cancellation, damaged-item, or lost-package clarification is a launch-policy follow-up, not a current access prerequisite.
- [ ] Customer-service contact and expected response time.
- [ ] Guest checkout versus required/optional customer accounts.
- [ ] Coupon, gift-certificate, store-credit, and loyalty requirements for the pilot.

Blue Nova can configure the rules Rebekah approves but should not decide tax obligations or provide legal/accounting advice. Any outside advice is obtained by Rebekah and relayed to Blue Nova by her.

#### Shipping choice to present before the pilot

Rebekah must be shown both options and their consequences before choosing:

1. **Live address-dependent USPS rates — confirmed.** Checkout calculates shipping using the fulfillment origin, customer's delivery address, cart weight, and the configured packing method. Mark/Rebekah's team enters accurate product weights and flags unusual bulky items. Blue Nova configures and tests weight-based/standard packing for ordinary products and requests dimensions only for specific exceptions. The client does not create quantity-to-box rules.
2. **Flat-rate and/or free shipping.** Rebekah sets a fixed charge and/or free-shipping threshold. WooCommerce's built-in settings cost $0, and product weight is not required for the pilot shipping calculation.

Product weights are required because live USPS rates were selected. Do not ask the client to predict box selection for multi-item carts; WooCommerce's USPS extension can group items automatically. Blue Nova owns packing-method configuration and cart testing.

### 4. Policies and customer-facing approvals

- [ ] Approve ecommerce-specific Shipping Policy content.
- [ ] Update/approve Refund & Returns language for onsite WooCommerce purchases; Phase One's preserved policy was not rewritten for the new store.
- [ ] Update/approve Terms, Privacy Policy, and Disclaimer for accounts, checkout, payments, order emails, analytics, and third-party processors.
- [ ] Confirm all supplement, ingredient, warning, and wellness statements are accurate and legally supportable.
- [ ] Confirm the customer-service, business, and return-address details that will appear during checkout and in order emails.

Material legal wording should receive qualified legal review.

## The 25-product pilot package

Rebekah should choose a representative sample rather than the 25 easiest products. Selecting the products is not enough: each selected product must also be prepared and approved for online synchronization in Revel.

### Exact Revel instructions for each of the 25 products

For every pilot product, Rebekah or her Revel staff must:

- [ ] Open the product in Revel and confirm it belongs to the one designated online fulfillment location.
- [ ] Set the product to **Active**.
- [ ] Enable **Display on online and 3rd party** so Kosmos is permitted to send it to WooCommerce.
- [ ] Confirm the product has a unique SKU and/or barcode/UPC. Do not reuse a SKU assigned to another product or variation.
- [ ] Replace register shorthand with a customer-friendly product name.
- [ ] Confirm the current selling price.
- [ ] Confirm the current inventory quantity at the designated fulfillment location; do not use a negative quantity.
- [ ] Leave the product in the normal Revel category the store already uses and correct obvious duplicate or misspelled categories. Rebekah does not need to create website categories in Revel; Blue Nova reviews the imported category set and maps/organizes WooCommerce categories after the first test.
- [ ] Enter the brand/manufacturer where Revel supports it.
- [ ] Enter an accurate product weight because the confirmed live USPS rate uses cart weight. Flag unusually large, long, or bulky products. Do not require the client to define multi-item box rules; Blue Nova configures and tests automatic packing and requests dimensions only for identified exceptions.
- [ ] For variations or matrix items, confirm the parent name, option names, SKUs/barcodes, prices, and inventory for every variation. The matrix parent name must be consistent.
- [ ] Only flag known shipping exceptions, such as an item the store already knows requires special handling or cannot be mailed. Routine products are assumed shippable; no product-by-product legal review is requested.
- [ ] Mark exactly the intended pilot products with `Display on online and 3rd party`. Blue Nova will pull the marked products from Revel/Kosmos, create the final 25-product SKU/name list, and send it to Rebekah for confirmation. She does not need to prepare a separate spreadsheet or list.

Blue Nova will first test one prepared product. After its Revel fields, mapping, overwrite behavior, and online result pass, Blue Nova will synchronize the remaining 24.

The 25-product sample should cover:

- At least five intended store categories.
- Multiple brands, including several Rebekah's/private-label products.
- Normal in-stock products plus at least one out-of-stock case.
- Simple products and at least one matrix/variation product if variations will be sold online.
- Different description lengths and product-detail needs.
- Products with different weights/package sizes if shipping rates depend on them.
- Any expected special tax or shipping class.

Every pilot product must ultimately have the following information. Do not require Rebekah to assemble it all before the first test: first synchronize one product and then the 25-product set to learn which fields Revel/Kosmos actually transfers and whether later syncs overwrite WooCommerce content. Blue Nova gathers what is available from authorized manufacturer/supplier pages or feeds, then gives Rebekah one consolidated list of only the missing or approval-required descriptions, images, label details, and restrictions. Images and descriptions are not assumed to transfer reliably.

- [ ] Customer-friendly product name, not register shorthand.
- [ ] Unique SKU and/or barcode/UPC.
- [ ] Correct active status.
- [ ] `Display on online and 3rd party` enabled only for pilot products approved for the website.
- [ ] Correct price.
- [ ] Correct inventory at the selected fulfillment location.
- [ ] Correct normalized category with no duplicate/spelling-variant category names.
- [ ] Brand/manufacturer.
- [ ] Product weight and dimensions when required for shipping.
- [ ] Variation/matrix names, options, SKUs, prices, and inventory when applicable.
- [ ] High-quality primary image and any additional approved images.
- [ ] Customer-facing description.
- [ ] Ingredients and allergen information where applicable.
- [ ] Directions/serving information.
- [ ] Supplement Facts or other required label information.
- [ ] Warnings, restrictions, and shipping eligibility.

Rebekah is responsible for approving the accuracy and permitted use of the final product data and may need to supply content that is unavailable from Revel or authorized manufacturer/supplier sources. Blue Nova performs the initial audit and sourcing so she receives one focused gap list rather than a broad content-assignment checklist. New photography, extensive image editing, original copywriting, full-catalog cleanup, and manual bulk entry should be quoted separately if requested.

## Field ownership to confirm during the pilot

| Field | Recommended system of record |
|---|---|
| Online-sale eligibility | Revel |
| Product name | Revel |
| SKU / barcode / UPC | Revel |
| Price | Revel |
| Inventory and stock status | Revel |
| Weight | Revel |
| Primary operational category | Revel + Kosmos mapping |
| Product image | Decide after overwrite/transfer testing |
| Basic and detailed descriptions | Decide after overwrite/transfer testing |
| SEO title and meta description | WordPress |
| Featured, staff-pick, bestseller status | WordPress |
| Health-goal and seasonal collections | WordPress |
| Homepage placement and display order | WordPress |
| Related products | WordPress |
| Synchronization schedule and logs | Kosmos eSync |

Do not improve product images or descriptions in WooCommerce until testing confirms whether a later Revel/Kosmos synchronization would overwrite those fields.

## Recommended build and test sequence

1. Clone the current production site to staging, protect it from indexing/email/payment side effects, and create a fresh restore point.
2. Complete the deferred homepage asset/PHP refactor so Phase Two is built on the cleaned baseline.
3. Reconcile the 35/36 legacy WooCommerce product records and decide which, if any, belong in the pilot.
4. Reactivate WooCommerce and only the extensions actually required for the chosen workflow, one controlled group at a time.
5. Build the store structure: categories, subcategories, brand structure, attributes, navigation, filters, and health-goal/seasonal collections.
6. Establish the Revel and WooCommerce connections in Kosmos. Follow Kosmos's documented recommendation to test **one action and one product first**.
7. Confirm field mapping and overwrite behavior, then synchronize the full 25-product sample.
8. Build and refine the Shop, category, search, product-card, product-detail, cart, checkout, account, and order-confirmation experiences using those real products.
9. Configure the selected payment gateway, tax method, shipping zones/rates, labels/packages, order emails, inventory rules, and customer accounts.
10. Complete end-to-end staging tests, obtain client approval, train staff, and only then plan a controlled production launch.

Do not import or expose the full catalog until the pilot passes.

## Pilot acceptance tests

The pilot is successful only when all applicable items pass:

- [ ] A simple Revel product creates/updates the correct WooCommerce product.
- [ ] A matrix/variation product creates and updates correctly, if variations are in scope.
- [ ] Product status and the Revel online/third-party flag control online eligibility as intended.
- [ ] SKU/barcode, price, category, and inventory match the selected Revel location.
- [ ] Images and descriptions transfer or remain protected according to the approved field-ownership rules.
- [ ] Out-of-stock and backorder behavior matches the approved policy.
- [ ] A WooCommerce order transfers to Revel correctly.
- [ ] The order reduces inventory at the designated fulfillment location only.
- [ ] Cancellation/refund behavior is documented and tested.
- [ ] Payment success, failure, and test refunds work in the chosen gateway's test mode.
- [ ] Tax calculations match the client-supplied rules approved by Rebekah.
- [ ] Shipping methods, rates, packages, labels, and restricted destinations behave correctly.
- [ ] Admin and customer emails are delivered and contain correct business details.
- [ ] Guest/account checkout, password reset, privacy, consent, and order history behave correctly.
- [ ] Product, category, cart, and checkout layouts pass desktop, tablet, and mobile QA.
- [ ] Structured data, canonicals, sitemap inclusion, indexation controls, analytics, and business-event tracking are verified.
- [ ] Uncached cart/checkout and a realistic load test pass without PHP/MySQL queueing or unacceptable response times.
- [ ] Backup and restore procedures are appropriate for live transactional data.

## Working time estimate for the 25-product pilot

The earlier 45–72-hour estimate combined the custom storefront build with the integration pilot and was too high when described as merely testing 25 products. Use these two scopes instead, assuming the 25 products are clean and access is ready:

| Scope | Estimated Blue Nova time | Expected elapsed time |
|---|---:|---:|
| 25-product data/synchronization proof: connect, map, sync, and review the sample | **8–14 hours** | **2–5 business days** |
| Launch-ready shipping-only pilot: the above plus the selected payment gateway, shipping/tax rules, order flow, emails, fixes, and handoff | **18–30 hours** | **about 1–2 weeks** |

Custom shop/product/cart/checkout design and full-site visual refinement are separate Phase Two build work, not part of the 25-product synchronization test. Vendor support delays, incomplete product data, or unexpected mapping/variation problems can extend the calendar. Multi-location fulfillment and local pickup are excluded.

### Todd's availability and pilot scheduling

Do not start a live trial, active synchronization run, payment test, or pilot handoff immediately before or during Todd's unavailable periods:

- August 25: unavailable.
- August 26: available only in the morning Eastern time.
- August 28–30: unavailable.
- September 10–13: unavailable.
- September 17–20: unavailable.
- Weekends: unavailable.

The first practical pilot window is **Monday, August 31 through Wednesday, September 9, 2026**, provided access, decisions, and all 25 products are ready beforehand. Finish or place the store back into a known safe staging state by September 9. If prerequisites or vendor support make that window uncertain, start on or after **Monday, September 21**. Do not activate Kosmos's 14-day trial until the chosen working window is confirmed so trial time is not lost during a blackout.

The 25-product pilot should be time-tracked by workstream. After completion, use the actual average cleanup/sync/QA time per product and the catalog's exception rate to estimate the full rollout. Do not extrapolate by multiplying 25 blindly: reusable templates and connection setup are one-time costs, while missing images, duplicate SKUs, descriptions, variations, and mapping exceptions scale with the catalog.

## Costs that require separate client approval

- Kosmos eSync subscription and any extra action or mapping fees.
- Merchant-processing rates and contract costs are handled directly between Rebekah and Fiserv and are not a Blue Nova project requirement.
- Postage, labels, and packaging used to fulfill actual orders.
- Hosting increase if approved for launch.
- Premium WooCommerce extensions only if a requirement later proves they are necessary; none is currently required for the lean pilot.
- Product photography, editing, copywriting, data cleanup, bulk entry, and full-catalog review.
- Increased backup/storage or other transactional-data protection.
- Ongoing integration monitoring and ecommerce support after the included launch period.

### Current lean-pilot software cost

Verified on 2026-08-17:

| Item | Pilot cost | Notes |
|---|---:|---|
| WooCommerce core | **$0** | Already retained in the project; reactivate on staging only when ready. |
| Likely Clover Payments for WooCommerce gateway | **$0 plugin** | Connect only after Rebekah's Fiserv/Clover merchant account is active. Merchant rates and contract terms are outside Blue Nova's scope. |
| Built-in WooCommerce shipping and manual tax settings | **$0** | Adequate for a defined shipping-only pilot; no paid label plugin is required. |
| Kosmos eSync Warmup | **$49 month-to-month** or **$39/month billed annually** | Includes 7 actions, up to 450 monthly orders, unlimited SKUs, one location, and one online store. This appears sufficient for the lean pilot, pending confirmation of the final action count. A 14-day trial is advertised. |
| Official live-rate carrier extension, only if checkout rates must depend on the customer's address | **Currently $109/year per carrier** | USPS, UPS, and FedEx official WooCommerce extensions are each currently listed at this price. Select one carrier before purchase. This is not needed for flat-rate or free shipping. |
| Custom data mapping | **Not expected or budgeted** | Standard Revel fields should work without it. Mention only as an unlikely exception; if testing proves it is necessary, Kosmos says setup starts at $150 and Blue Nova will bring the exact quote to Rebekah for approval. |

For the test, prefer the Kosmos free trial if the entire working window is ready, or one $49 monthly period rather than an annual commitment. Rebekah creates and purchases the Kosmos account and the $109/year USPS extension when Blue Nova confirms the test window is ready. Do not purchase an extra tax, product-import, payment, or checkout plugin unless testing identifies a specific need. Clover's official gateway can be connected later when the merchant account is active.

## Sources reconciled

- Canonical Google Sheet: `Rebekah's Health & Nutrition - Phase One Page Inventory`.
- Local project records: `CLIENT-NOTES.md`, `PRE-DESIGN-INVENTORY.md`, `HOSTING-AND-WEBSITE-CARE-PLAN.md`, the current WordPress theme, and `online-store-homepage/index.html`.
- Recovered Rebekah Health ChatGPT project discussions: `Revel POS API Integration`, `Website Evaluation Feedback`, `Website Proposal Phases`, `product page`, `Phase Two Ecommerce Mockup`, and `Rebekah Project Timetable Adjustments`.
- Kosmos eSync: [Revel-to-WooCommerce product requirements](https://help.kosmosesync.com/index.php/knowledge-base/integrating-revel-systems-pos-products-with-woocommerce/), [Revel/WooCommerce integration](https://kosmoscentral.com/integrations/connect-revel-systems-woocommerce), and [current public pricing](https://kosmoscentral.com/esync-cloud-pricing).
- Fiserv/Clover compatibility reference, without assuming Clover is the final choice: [Clover Payments for WooCommerce](https://docs.clover.com/dev/docs/woocommerce) and [Clover Ecommerce integration types](https://docs.clover.com/dev/docs/ecommerce-integration-types).
- Official WooCommerce live-rate carrier extensions: [USPS](https://woocommerce.com/products/usps-shipping-method/), [UPS](https://woocommerce.com/products/ups-shipping-method/), and [FedEx](https://woocommerce.com/products/fedex-shipping-module/).
- WooCommerce: [tax setup](https://woocommerce.com/document/setting-up-taxes-in-woocommerce/), [WooCommerce Tax](https://woocommerce.com/document/woocommerce-shipping-and-tax/woocommerce-tax/), and [WooCommerce Shipping](https://woocommerce.com/document/woocommerce-shipping/).
