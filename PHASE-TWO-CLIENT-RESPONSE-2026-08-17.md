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
- Rebekah's team does not need to predict which box is used for different cart quantities. Blue Nova will configure and test the USPS packing method. They only need to flag unusually large or bulky products; Blue Nova will request dimensions for a specific exception if testing proves they are needed.
- The WooCommerce USPS live-rate extension is currently $109 per year and is expected for the pilot, subject to approval before purchase.
- Blue Nova must securely connect the USPS account/API during setup. No USPS account number or credential should be stored in this project record.
- WooCommerce shipping zones must exclude Alaska, Hawaii, and international destinations.

## Still needed from Rebekah or her team before testing

- Prepare the intended 25 products in Revel at the Clarkston establishment:
  - Active.
  - `Display on online and 3rd party` enabled.
  - Unique SKU/barcode, customer-facing name, correct price, and current Clarkston inventory.
  - Normal Revel category and brand/manufacturer where available.
  - Accurate product weight for live USPS rates, entered by Mark/Rebekah's Revel team.
  - Complete variation data for any matrix/variation products.
- Revel administrator/integration access that can see the Clarkston establishment.
- Create the client-owned Kosmos eSync account and provide Blue Nova configuration access.
- Later, after the merchant account is ready, confirm the exact Fiserv/Clover gateway and provide Blue Nova secure integration access. This does not block the non-payment setup and synchronization work.
- Confirm the operational email address for order and store notices.
- Supply the tax settings Blue Nova should implement.
- Confirm whether PO boxes are allowed. USPS normally serves PO boxes, but the website rule must be explicit.
- Confirm which USPS services should appear at checkout, such as Ground Advantage and Priority Mail.
- Approve the recommended operating defaults in one response: backorders off, one-unit inventory buffer unless the tested sync is immediate, guest checkout allowed, optional customer accounts, and coupons/gift certificates/store credit/loyalty excluded unless requested.
- Create/purchase the client-owned Kosmos plan and $109/year USPS extension when Blue Nova confirms it is time. No separate Blue Nova pilot fee has been defined in the current record.

## Blue Nova next steps after prerequisites are ready

- Audit the inactive WooPayments and NMI gateway configuration on staging, then configure only the gateway selected through Fiserv.
- Connect Revel, Kosmos eSync, WooCommerce, and the selected Clarkston establishment.
- Test one fully prepared product before importing the other 24.
- Determine what product descriptions and images transfer; gather authorized supplier/manufacturer content where possible and send one consolidated missing-content list.
- Configure continental-U.S.-only USPS live rates and test representative addresses, weights, packages, payments, orders, refunds, emails, and inventory changes.
- Use the pilot's recorded time and exceptions to prepare the full-catalog price and schedule.

## Not required again

- WordPress or Cloudways access; Blue Nova already has both.
- A separate spreadsheet of the 25 products; Blue Nova will pull products marked for online/third-party display and return a SKU/name list for approval.
- PayPal access; Rebekah has said PayPal will not be used.
- Rebekah's primary Clover/merchant-account password; Blue Nova should receive vendor-issued integration access through a secure method when payment setup begins.
- Employee names or contact with an accountant or other outside adviser.
