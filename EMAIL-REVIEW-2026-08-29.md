# Client Email Review — August 29, 2026

Reviewed the newest Rebekah/Mark messages through August 29. No reply, draft, account change, or website change was made.

## New Phase Two information

### Mark supplied the pilot-product list

- Mark attached `Product_Export_Establishment_3 (70)_results.xlsx` and stated that it contains the 25 products selected for the pilot.
- The attachment is confirmed received but its rows still need a field-by-field audit. Gmail identified it as a generic binary attachment, so it could not be opened through the mail connector during this review.
- Mark could not find a product-weight field in Revel.
- He said most intended products should be similar in weight and that heavier products such as protein powders and gallon-size liquid supplements/topicals probably will not be offered online.
- Mark asked whether one baseline weight can be used.

Decision: a conservative default weight may be used only for an internal staging calculation test. It should not be treated as final production data until Blue Nova compares representative USPS quotes. Accurate individual weights are still preferred for live customer charges. After Revel access is verified, Blue Nova must determine whether weight exists in another Revel field, whether Kosmos transfers it, or whether weight should be maintained in WooCommerce. Do not ask Mark to enable `Sold by Weight` merely to create a shipping weight.

### Kosmos support response

- Rebekah forwarded Kosmos's response from Alex Skorohodov.
- Kosmos offered setup help through its Help/Support system and offered a 30-minute Google Meet.
- The response did not explain how to add Blue Nova as a separate Kosmos user and did not configure Revel or WooCommerce.
- Blue Nova should first use the access already supplied and create the WooCommerce REST Consumer Key and Consumer Secret requested by Kosmos. If the connection or actions are unclear, Blue Nova—not Rebekah—should open the support ticket or schedule the setup meeting.

## Separate live-site issue

Rebekah forwarded a Contact Us submission and explained that she has received two inquiries but cannot tell which store the customer is asking about.

Required correction:

- Add a required **Preferred store/location** field to the Contact Us form.
- Recommended choices: Lapeer, Grand Blanc, Clarkston, Lake Orion, General question / Not sure.
- Include the selected location clearly in the notification email Rebekah receives.
- Verify the form on desktop and mobile and submit one test for each notification path before closing the issue.

This is a Phase One/live-site correction, not a Phase Two ecommerce prerequisite. It should be handled separately so it does not get lost inside the ecommerce work.

## Remaining actions

1. Inspect the 25-product spreadsheet and confirm product names, identifiers, categories, prices, and whether it contains the exact intended 25 rows.
2. Verify Revel console access and permissions.
3. Locate the correct shipping-weight ownership/field through the one-product Revel/Kosmos test.
4. Use a placeholder baseline weight only on staging and only long enough to compare representative USPS calculations; do not launch with an unvalidated blanket weight.
5. Connect WooCommerce to Kosmos; contact Kosmos support directly if setup blocks Blue Nova.
6. Correct and test the live Contact Us form's missing store-location question.

