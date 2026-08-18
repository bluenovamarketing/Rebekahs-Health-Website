# Phase Two Ecommerce Master Checklist

Prepared: 2026-08-17  
Scope: One fulfillment location, shipping only, PayPal, Revel → Kosmos eSync → WooCommerce, and a 25-product pilot.

This is the authoritative responsibility checklist for the client email, proposal, setup, pilot, and handoff.

## 1. Rebekah's required decisions and approvals

- [x] Confirm the single Revel establishment/store that will supply inventory and ship every online order: **Clarkston**.
- [x] Confirm the fulfillment origin address and normal order-handling time: **7093 Suite B, Dixie Highway, Clarkston, MI 48346; two business days to pack and ship**.
- [ ] Choose the 25 representative pilot products in Revel.
- [ ] Choose one shipping approach:
  - [x] **Live address-dependent rates:** **USPS selected**. Accurate product weights and applicable package dimensions are required. The current USPS extension cost is $109/year; purchase remains subject to the Phase Two approval.
  - [ ] **Flat-rate/free shipping:** approve the fixed charge and/or free-shipping threshold; no carrier extension or product weight is required for the pilot calculation.
- [x] Confirm the principal shipping region: **continental United States only** (no Alaska, Hawaii, or international shipping).
- [ ] Confirm whether USPS delivery to PO boxes will be allowed.
- [ ] Confirm any known products that require special handling or that the store already knows cannot be mailed. Routine products are assumed shippable.
- [ ] Confirm the tax settings Blue Nova should implement. Blue Nova communicates only with Rebekah and does not contact outside advisers.
- [ ] Confirm whether backorders are prohibited or allowed. Recommended pilot default: prohibited.
- [ ] Confirm whether an inventory safety buffer should be used to reduce overselling between sync runs.
- [ ] Confirm guest checkout/account preference. Recommended pilot default: guest checkout allowed, optional accounts available.
- [ ] Confirm whether coupons, gift certificates, store credit, and loyalty are excluded from the pilot. Recommended pilot default: exclude unless specifically required.
- [ ] Confirm the operational email address for orders, failed payments, refunds, cancellations, low-stock notices, and customer-service messages.
- [ ] Approve the pilot costs before purchase.

## 2. Accounts and access Rebekah must provide

- [ ] Provide one Revel administrator/integration access grant that can see the selected fulfillment establishment. A separate location login is unnecessary unless Revel permissions are establishment-limited.
- [ ] Create and own the Kosmos eSync account using Rebekah's business and billing information.
- [ ] Before Kosmos signup, acknowledge the current cost reminder: $49 month-to-month, or $39 per month billed annually ($468/year); Kosmos also advertises a 14-day trial. Rebekah has previously been informed of this service.
- [ ] Use the official Kosmos plans/signup page: <https://kosmoscentral.com/esync-cloud-pricing-monthly>.
- [ ] Give Blue Nova the account access needed to configure Kosmos after signup.
- [ ] Add Blue Nova as a PayPal Business secondary user with full ecommerce/payment operational access:
  - [ ] API Activation & Authorization.
  - [ ] Online checkout, payment-integration, API, and developer access shown in the account.
  - [ ] View/edit the relevant profile and account settings.
  - [ ] View balance and transactions.
  - [ ] Authorization and settlement access.
  - [ ] Issue refunds.
  - [ ] Manage disputes and chargebacks.
  - [ ] Contact PayPal Customer Service about the account.
- [ ] Use PayPal's Manage Users page at <https://www.paypal.com/businessmanage/account/accountAccess>; instructions are at <https://www.paypal.com/us/cshelp/article/how-do-i-manage-users-on-my-business-account-help274>.
- [ ] An existing USPS business account has been confirmed. Provide Blue Nova the secure USPS account/API access or complete any unavoidable owner-only approval when the live-rate extension is configured. Blue Nova performs the configuration; the USPS account number is not stored in this project record.
- [ ] If necessary product information exists only inside a manufacturer/supplier portal, provide authorized access or supply the missing material after Blue Nova's audit.

Blue Nova already has WordPress and Cloudways staging access. Do not ask Rebekah for those again. Do not request her primary PayPal password.

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
- [ ] If live carrier rates are selected, enter the accurate product weight and provide package dimensions when required.
- [ ] For matrix/variation products, confirm the parent name, option names, SKUs/barcodes, prices, and inventory for every variation.
- [ ] Flag only known shipping/special-handling exceptions.

Rebekah does not need to create a separate spreadsheet. Blue Nova will pull the products marked for online/third-party display, reconcile the intended 25, produce the SKU/name confirmation list, and send it to Rebekah for approval.

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

- [ ] Confirm the working window and do not start the Kosmos 14-day trial until access, decisions, and product preparation are ready.
- [ ] Use August 31–September 9, 2026 only if the prerequisites are ready; otherwise begin September 21 or later. Do not leave an active test/handoff across Todd's blackout dates or weekends.
- [ ] Create a fresh Cloudways restore point and work on protected staging—not the live store.
- [ ] Prevent staging indexing, customer emails, and accidental live payment/order side effects.
- [ ] Complete the deferred homepage asset/PHP cleanup needed for a stable Phase Two baseline.
- [ ] Reconcile the 35 visible/36 recorded legacy WooCommerce products and prevent duplicate pilot products.
- [ ] Confirm the retained Shop, Cart, Checkout, and My Account system pages.
- [ ] Reactivate WooCommerce and only the extensions required for the selected pilot workflow.
- [ ] Keep unnecessary commerce plugins inactive.
- [ ] Audit the existing WooCommerce payment configuration and determine whether the old PayPal connection remains usable.
- [ ] Install/activate the official free PayPal Payments extension if required.
- [ ] Create the dedicated WooCommerce REST API user/key when Kosmos requires it.
- [ ] Create/reconcile WooCommerce categories before synchronization and map Revel operational categories to the website structure.
- [ ] Configure basic store settings: address, currency, units, inventory behavior, account/guest checkout, and order emails.

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
- [ ] If live rates are selected, purchase/install one approved $109/year USPS/UPS/FedEx extension and configure origin, package data, services, and customer-facing labels.
- [ ] If flat/free shipping is selected, configure the approved rate and threshold using WooCommerce's built-in $0 settings.
- [ ] Test shipping calculations with multiple representative customer addresses and cart weights.
- [ ] Configure the tax rules supplied and approved by Rebekah.
- [ ] Connect PayPal and configure sandbox/test mode before any live transaction.
- [ ] Test successful payment, failed payment, cancellation where supported, and refund behavior.
- [ ] Confirm customer and administrator order emails are delivered and accurate.
- [ ] Confirm out-of-stock, backorder, and inventory-buffer behavior.

## 8. Storefront build responsibilities

Using the current custom Rebekah theme—not Bricks—Blue Nova must build/refine:

- [ ] Shop/catalog page.
- [ ] Product-category and brand browsing.
- [ ] Search and no-results behavior.
- [ ] Product cards.
- [ ] Product-detail template.
- [ ] Variation selection where applicable.
- [ ] Cart.
- [ ] Checkout.
- [ ] My Account, password reset, and order history.
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
- [ ] PayPal success, failure, and refund tests pass.
- [ ] WooCommerce order reaches Revel correctly.
- [ ] Inventory decreases only at the one fulfillment location.
- [ ] Customer/admin emails arrive with correct order and business information.
- [ ] Guest/account checkout and password reset work.
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
- [ ] Review hosting capacity and increase from the current test-sized environment only if launch testing justifies it.
- [ ] Use actual pilot time, errors, and content gaps to estimate the full-catalog rollout.

## 11. Current cost assumptions

- WooCommerce core: **$0**.
- Official PayPal Payments extension: **$0**; PayPal transaction fees remain separate.
- Kosmos: **$49 month-to-month**, or **$39 per month billed annually ($468/year)**; the advertised 14-day trial may be used when the complete test window is ready.
- Live-rate carrier extension: **$109/year for one USPS, UPS, or FedEx extension**, only if live address-dependent shipping is selected.
- Likely initial software cost with live rates: **approximately $158** ($49 Kosmos month + $109 carrier extension).
- Flat/free shipping pilot software cost: **$49 for one Kosmos month**, potentially $0 during the trial.
- Custom mapping: **not expected**. If the one-product test proves otherwise, Kosmos says setup starts at $150; obtain an exact quote before approval.
- Postage, packaging, PayPal transaction fees, optional hosting increases, and out-of-scope content work are separate.

## 12. Working estimate

- 25-product data/synchronization proof: **8–14 Blue Nova hours**, normally **2–5 business days**.
- Launch-ready shipping-only operational pilot: **18–30 Blue Nova hours**, approximately **1–2 weeks**.
- Custom storefront design/build is a separate Phase Two scope and should not be described as part of the 25-product synchronization test.

The 25-product pilot has a separate paid Blue Nova setup/testing cost that must be included in the Phase Two proposal before work begins. The final full-catalog cost and schedule will be calculated from the pilot's actual time, product exceptions, content gaps, and integration results rather than guessed in advance.
