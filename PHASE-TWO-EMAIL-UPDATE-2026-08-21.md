# Phase Two Email Review Update

Reviewed: August 21, 2026  
Purpose: Consolidate the Phase Two information received after the original August 17 checklist and identify what is still required.

## Confirmed information

- Rebekah has approved the current Phase One website revisions and is ready to look ahead to Phase Two.
- Clarkston is the sole pilot fulfillment location.
- Shipping origin: 7093 Suite B, Dixie Highway, Clarkston, MI 48346.
- Normal handling time: two days.
- Shipping area: continental United States only.
- Carrier: USPS using live address-dependent checkout rates.
- An existing USPS business account is available; its account number is retained only in the project's private, git-ignored access record.
- Mark Cobleigh is Rebekah's head purchaser and client-side Revel contact. He manages Revel product groups, classes, categories, and backend changes and has confirmed he will make changes needed for the website/Revel handshake.
- Blue Nova will start with 25 products and test one fully prepared product before synchronizing the remaining 24.

## Payment-processing correction

- PayPal will not be used.
- WooCommerce core provides the website store and checkout and cannot process cards by itself.
- WooPayments is a separate, legitimate payment service built by Woo that can accept cards from the WooCommerce dashboard; Stripe powers its underlying processing. It should remain inactive if Rebekah uses Fiserv/Clover.
- Todd introduced Rebekah to Andrew Droz at Fiserv on August 19 to discuss online merchant services.
- Andrew offered to review options with Rebekah. The email thread does not show that Rebekah selected a product, completed an application, or received an approved merchant account.
- The first Andrew email included a `Referral Partnership Boarding Application` addressed to Todd. That attachment appears related to the referral relationship, not proof of Rebekah's merchant account or gateway selection.
- The existing WordPress stack retains inactive WooPayments and Pledged Plugins NMI Gateway plugins. Their presence reflects the prior site configuration and does not establish the Phase Two processor.
- Rebekah will probably use Fiserv with a Clover-based merchant account. Clover has an official free WooCommerce payment plugin with sandbox support, refunds, voids, and tokenized iframe collection. Blue Nova should keep this as the likely integration while waiting for the active account and exact product confirmation.

## Payment information needed later

Payment processing does not need to be connected before the rest of the store is prepared. When Rebekah's account is ready, Blue Nova only needs:

- Confirmation that the product is Clover Ecommerce/Clover Payments for WooCommerce, or the exact alternative gateway name.
- An active merchant account.
- Secure integration access. Clover normally requires a merchant ID and sandbox/production public and private tokens.
- Any gateway-specific setup instructions required by Fiserv.

Blue Nova does not need to review Rebekah's processing rates, monthly fees, merchant contract, funding schedule, or chargeback pricing. Those are between Rebekah and Fiserv.

Do not ask Rebekah to email a primary password, API secret, or card/bank information. Blue Nova should use delegated access and the vendor's secure connection flow.

## Other items still required

- The intended 25 products must be prepared in Revel at Clarkston and enabled with `Display on online and 3rd party`. Mark is the identified person for these Revel changes.
- Revel administrator/integration access that includes Clarkston.
- Client-owned Kosmos eSync account and Blue Nova configuration access.
- Accurate product weights entered in Revel by Mark/Rebekah's team. They do not need to create multi-item box rules. Blue Nova will configure and test USPS packing automatically and will ask only for dimensions of a specific unusually large or bulky product if testing requires them.
- Approved USPS services to display at checkout and a PO-box decision.
- Operational email address for order and store notices.
- Client-supplied tax settings.
- One approval of the recommended operating defaults: backorders off, one-unit inventory buffer unless the tested sync is immediate, guest checkout allowed with optional accounts, and coupons/gift certificates/store credit/loyalty excluded unless requested.
- Rebekah must create/purchase the client-owned Kosmos plan and $109/year USPS extension when Blue Nova says the setup is ready. No separate Blue Nova pilot fee has been defined in the current record.

## Work Blue Nova can complete before payment processing

- Prepare protected staging and reactivate only the required WooCommerce components.
- Reconcile the old WooCommerce product records.
- Build the shop, product, cart, checkout, account, and confirmation interfaces.
- Connect Revel, Kosmos, and WooCommerce after the pending accounts/access are supplied.
- Test one product, then the remaining 24.
- Configure USPS shipping, tax, inventory behavior, customer accounts, and order emails after the remaining operating settings are approved.
- Audit and complete product content.

Only the Clover/gateway connection and payment success/failure/refund tests must wait for the merchant account.

## No longer needed

- PayPal access; Rebekah will not be using PayPal.
- Blue Nova review of Fiserv rates, monthly fees, or merchant contracts.
- Another WordPress or Cloudways access request; Blue Nova already has both.
- A separate 25-product spreadsheet; Blue Nova will pull the products marked in Revel and return the SKU/name list for approval.
