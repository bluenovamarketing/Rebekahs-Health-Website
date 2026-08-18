# Phase Two Client Response Record

Date received: August 17, 2026  
Source: Rebekah's email reply to `Phase Two online store: what we need before the 25-product test`

This record saves the operational decisions from the reply. It intentionally does not store the USPS account number or any login credentials.

## Confirmed by Rebekah

- One fulfillment location: Clarkston.
- Shipping origin: 7093 Suite B, Dixie Highway, Clarkston, MI 48346.
- Normal handling time: two days to pack and ship an order.
- Shipping area: continental United States only.
- Carrier: USPS.
- An existing USPS business account is available.
- Shipping method: live rates calculated from the customer's address and shipment details.

## What these decisions require

- The pilot products need accurate individual weights.
- Package dimensions and packaging rules must be provided when required by the selected USPS services.
- The WooCommerce USPS live-rate extension is currently $109 per year and is expected for the pilot, subject to approval before purchase.
- Blue Nova must securely connect the USPS account/API during setup. No USPS account number or credential should be stored in this project record.
- WooCommerce shipping zones must exclude Alaska, Hawaii, and international destinations.

## Still needed from Rebekah or her team before testing

- Prepare the intended 25 products in Revel at the Clarkston establishment:
  - Active.
  - `Display on online and 3rd party` enabled.
  - Unique SKU/barcode, customer-facing name, correct price, and current Clarkston inventory.
  - Normal Revel category and brand/manufacturer where available.
  - Accurate product weight for live USPS rates.
  - Complete variation data for any matrix/variation products.
- Revel administrator/integration access that can see the Clarkston establishment.
- Create the client-owned Kosmos eSync account and provide Blue Nova configuration access.
- Add Blue Nova as a PayPal Business secondary user with the ecommerce/payment permissions listed in the master checklist.
- Confirm the operational email address for order and store notices.
- Supply the tax settings Blue Nova should implement.
- Confirm whether PO boxes are allowed. USPS normally serves PO boxes, but the website rule must be explicit.
- Confirm which USPS services should appear at checkout, such as Ground Advantage and Priority Mail.
- Confirm whether backorders are disabled, whether an inventory safety buffer is wanted, and whether guest checkout is allowed. Recommended pilot defaults: no backorders, a small safety buffer if sync is not immediate, and guest checkout allowed.
- Confirm that coupons, gift certificates, store credit, and loyalty features are excluded from the pilot unless one is required.
- Approve the pilot scope, Blue Nova setup/testing fee, Kosmos plan, and $109/year USPS extension before purchases or work begin.

## Blue Nova next steps after prerequisites are ready

- Audit the current WooCommerce/PayPal setup on staging.
- Connect Revel, Kosmos eSync, WooCommerce, and the selected Clarkston establishment.
- Test one fully prepared product before importing the other 24.
- Determine what product descriptions and images transfer; gather authorized supplier/manufacturer content where possible and send one consolidated missing-content list.
- Configure continental-U.S.-only USPS live rates and test representative addresses, weights, packages, payments, orders, refunds, emails, and inventory changes.
- Use the pilot's recorded time and exceptions to prepare the full-catalog price and schedule.

## Not required again

- WordPress or Cloudways access; Blue Nova already has both.
- A separate spreadsheet of the 25 products; Blue Nova will pull products marked for online/third-party display and return a SKU/name list for approval.
- Rebekah's primary PayPal password.
- Employee names or contact with an accountant or other outside adviser.

