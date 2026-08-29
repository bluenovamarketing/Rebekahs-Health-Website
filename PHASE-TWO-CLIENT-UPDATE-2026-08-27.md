# Phase Two Client Update — August 27, 2026

This record consolidates the latest Phase Two email replies. No email was sent and no account, website, or plugin setting was changed during this review.

## Confirmed from Rebekah

- Kosmos eSync has been created and the 14-day trial has started.
- Rebekah sent the information needed to access the client-owned Kosmos account. Credentials are intentionally not copied into this project record.
- A Revel Management Console invitation was sent to Blue Nova, the invitation was accepted, and a password was created. The direct console address is `https://rebekahs.revelup.com/`; successful console access still needs to be verified.
- Rebekah approved copying Mark on detailed Phase Two/Revel instructions while keeping Rebekah as the primary project contact and approval point.
- Fulfillment remains one location: Clarkston.
- Shipping remains continental United States only, using live USPS rates with a two-business-day handling time.
- Offer USPS Ground Advantage and Priority Mail.
- Do not accept PO-box shipping addresses.
- Do not offer a free-shipping threshold or add a handling/packaging fee during the pilot.
- Backorders are off.
- Hold back one unit of Revel inventory from online availability unless synchronization testing supports removing the buffer.
- Send a low-stock notice when inventory reaches two units.
- Allow guest checkout and optional customer accounts.
- Exclude coupons, gift certificates, store credit, and loyalty from the pilot.
- Keep product reviews off during the pilot.
- Send applicable store/order notices to both `rebekahspureliving@gmail.com` and `clarkstonpurchaser@rebekahspureliving.com`.
- Rebekah instructed Blue Nova to treat the pilot supplements as food/tax-exempt in Michigan. This is a client-supplied configuration instruction, not a tax or legal determination by Blue Nova; test orders must confirm the resulting totals before launch.
- PayPal is not part of Phase Two. Fiserv/Clover remains the expected later card-processing connection.

## Shipping-plugin decision

Do not purchase the official $109/year WooCommerce USPS Shipping Method extension. Rebekah raised a reasonable concern about its 2.7/5 review history even though the current release supports the USPS REST API.

Recommended pilot path:

1. Install the free **Shipping Live Rates for USPS for WooCommerce** plugin by Octolize on protected staging when Phase Two staging is ready.
2. Connect the client-owned USPS account/API, limit checkout choices to Ground Advantage and Priority Mail, and test one-, two-, and three-product carts against expected USPS charges.
3. Use Blue Nova checkout validation to reject PO-box addresses. This is separate from the carrier-rate plugin and should not require another paid plugin.
4. If the free plugin's combined-weight/default-package method is accurate for the representative pilot carts, keep it for the pilot at $0.
5. If realistic multi-item carts need automatic selection among different box sizes, upgrade to Octolize USPS Live Rates PRO. Its current listed price is £58/year and it adds weight/volume-based multi-product box packing and custom boxes.

The paid PluginHive USPS plugin is a secondary option at $99/year. It combines live checkout rates, label printing, tracking, and multi-package rules, but it is newer and its product page currently has no customer reviews. It is not the preferred first test.

Octolize calculates what the customer sees at checkout; it does not print postage labels. If labels are needed later, the free WooCommerce Shipping service can purchase/print USPS labels and add tracking after an order is placed. It does not replace the live-rate plugin because it does not show live rates at checkout.

## What Blue Nova can do next

- Verify the accepted Revel invitation reaches the Clarkston Management Console and provides the required product/integration permissions.
- Use the existing Kosmos account to connect Revel and WooCommerce. Blue Nova creates the WooCommerce Consumer Key and Consumer Secret requested by Kosmos; Rebekah does not need to generate them.
- Use the existing USPS Business account to create and authorize a USPS Developer Portal app, then enter its Consumer Key and Consumer Secret into the shipping plugin. The USPS account number by itself is not enough for the current REST API. Blue Nova should do this if account access is available and involve Rebekah only for unavoidable login approval or MFA.
- Confirm at least one Revel product is active, assigned to Clarkston, enabled for online/third-party display, and has a usable SKU, price, inventory, category, brand, and weight.
- Prepare protected Phase Two staging only when the work window is ready, then test one Revel product before the remaining 24.
- Install and test Octolize's free USPS live-rate plugin before recommending a paid shipping license.
- Configure the approved inventory, checkout, account, email, tax, review, and promotion settings on staging.

## Still pending

- Verify Revel console access and permissions; ask Rebekah or Mark only if the accepted invitation does not provide the required access.
- Obtain usable access to the existing USPS Business/Developer Portal account, or an owner-approved way for Blue Nova to create the required REST API app and credentials. Do not request that Rebekah email the Consumer Secret.
- Confirm one representative product is ready in Revel, followed by the remaining 24.
- Complete the one-product Kosmos synchronization test and determine whether images/descriptions transfer or are overwritten.
- Determine through representative cart testing whether the free USPS plugin is accurate enough or whether Octolize PRO is needed for automatic box packing.
- Connect and test Fiserv/Clover only after the merchant account and exact WooCommerce gateway are ready.
