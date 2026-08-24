# Unsent Gmail Draft — Phase Two Revel Preparation and Access

Status: Draft only; not sent  
To: Rebekah — `rebekahspureliving@gmail.com`  
CC: Mark Cobleigh — `lapeerpurchaser@rebekahspureliving.com`  
Subject: `Re: Phase Two online store: what we still need before setup`

Hi Rebekah and Mark,

Thank you for the information you have already provided. We have the Clarkston fulfillment address, USPS shipping choice, two-business-day handling time, and continental-U.S.-only shipping area.

Below are the remaining setup items and questions we need answered.

## 1. Mark: prepare the 25 products in Revel

Please use the Revel Management Console:

1. Sign in and select the **Clarkston establishment/location** at the top of the screen.
2. Open **Products**, then the product list.
3. Choose 25 products that represent what you expect to sell online. If products with sizes, flavors, or other choices will be sold online, include at least one of those in the test group.
4. Open each product using its edit or pencil control.
5. Confirm the following information for every product:
   - **Active** is turned on.
   - The product has a clear, customer-friendly **Product Name**.
   - The product has a unique **SKU or barcode**. Do not use the same identifier for two products or variations.
   - The current **selling price** is correct.
   - The normal Revel **category** is correct.
   - The **brand or manufacturer** is entered where Revel provides that field.
   - Inventory tracking and the current **Clarkston inventory amount** are correct and not below zero.
   - Under **Third Party Preferences**, turn on **Display on online and 3rd party applications**.
   - Enter the accurate packaged **product weight** for USPS shipping. If you do not see a normal weight field, please do not turn on “Sold by Weight” just to create one. Let us know, and we will check the correct field after access is provided.
   - For a product with sizes, flavors, or other variations, confirm the parent product name and every option's SKU/barcode, price, and Clarkston inventory.
   - Flag anything unusually large, long, bulky, or already known to require special shipping.
6. Save each product. After the edits are complete, use Revel's **Push Changes** control if it appears in your Management Console.

Please do not spend time creating new descriptions, collecting images, or measuring every possible shipping box. Blue Nova will first test what Revel and Kosmos transfer. We will gather available manufacturer information and send one combined list of any missing descriptions, images, label information, product dimensions, or other details.

You also do not need to create a spreadsheet or special online menu right now. The **Display on online and 3rd party applications** setting is what Kosmos requires for product export. Blue Nova will pull the eligible items and send the final 25-product list to you for approval.

Kosmos provides its product requirements and field screenshots here:
https://help.kosmosesync.com/index.php/knowledge-base/integrating-revel-systems-pos-products-with-woocommerce/

## 2. Provide Blue Nova full Revel access for Clarkston

Please invite `tbailey@bluenovainc.com` as a Revel administrator/integration user for the Clarkston establishment.

The access must allow Blue Nova to view and manage:

- Products, categories, classes, and product groups
- Product names, SKUs/barcodes, pricing, third-party preferences, and variations
- Inventory and product weights
- Custom menus and online-ordering settings
- Reports and order history
- Integrations/API settings needed for Kosmos

The easiest option is full administrator access for the Clarkston establishment. This prevents delays caused by having to request another permission later. Please use an invitation or user-access feature; do not email anyone's existing Revel password.

## 3. Rebekah: create the Kosmos eSync account

Kosmos eSync connects Revel and WooCommerce. It moves product information, prices, inventory, and online orders between the two systems so your staff does not have to update both systems manually.

For the 25-product pilot, we recommend starting with the **Warmup month-to-month plan at $49 per month**. It includes one location, one online store, up to seven sync actions, and up to 450 orders per month. If the pilot works as expected, you can decide later whether to change to annual billing at $39 per month, billed as $468 per year.

Create the business-owned account here:
https://kosmoscentral.com/esync-cloud-pricing-monthly

After the account is created, please give Blue Nova the access needed to configure the Revel and WooCommerce connections. We do not expect custom mapping. If the first product test proves that custom work is necessary, we will explain it and obtain approval before any additional cost.

## 4. Rebekah: purchase the USPS extension

Because the website will show live USPS rates based on the customer's address and the products in the cart, please purchase the official **USPS Shipping Method for WooCommerce** using the business's WooCommerce.com account.

The one-year license is currently **$109**:
https://woocommerce.com/products/usps-shipping-method/

Please let us know when the purchase is complete and provide Blue Nova access to the WooCommerce.com account or extension license so we can install, connect, and test it. Your existing USPS account will be connected during configuration.

## 5. Please answer the remaining store questions

Please reply **approved** if all of the recommended settings below work. If something should be different, list the item and the change.

1. **USPS services:** Offer USPS Ground Advantage and USPS Priority Mail during the pilot.
2. **PO boxes:** Allow USPS delivery to PO boxes.
3. **Shipping promotion:** Do not offer a free-shipping threshold during the pilot. Charge the live USPS rate returned at checkout.
4. **Handling charge:** Do not add a separate handling or packaging fee during the pilot.
5. **Backorders:** Turn backorders off. Customers cannot order an item when available inventory reaches zero.
6. **Inventory buffer:** Hold back one unit online. If Revel reports five available, the website initially offers four. We can remove the buffer if testing proves inventory updates immediately.
7. **Low-stock notice:** Send a low-stock email when an item reaches two available units.
8. **Guest checkout:** Allow customers to order without creating an account.
9. **Customer accounts:** Let customers create an optional account for order history and faster future checkout.
10. **Coupons and existing store programs:** Leave coupons, gift certificates, store credit, and loyalty points out of the 25-product pilot.
11. **Product reviews:** Keep customer product reviews turned off during the pilot.
12. **Order notifications:** What email address should receive new-order, failed-payment, refund, cancellation, and low-stock notices?
13. **Taxes:** Blue Nova will review the existing WooCommerce tax setup and show you test order totals before launch. Please identify any of the 25 products that you already know are tax-exempt or taxed differently from the others.

No PayPal action is needed. No Fiserv/Clover action is needed until the merchant account is ready and we reach credit-card testing.

## What Blue Nova will do

Blue Nova will prepare the storefront designs, connect Revel, Kosmos, WooCommerce, and USPS, test one prepared product first, and confirm how its name, SKU, price, inventory, category, weight, images, description, and variations transfer. After that test passes, we will synchronize and review the remainder of the 25-product pilot.

We will send you one clear list of anything still missing and provide the completed pilot for approval before it is made public.

Thank you,

Todd Bailey  
Blue Nova Marketing  
(844) 625-6500
