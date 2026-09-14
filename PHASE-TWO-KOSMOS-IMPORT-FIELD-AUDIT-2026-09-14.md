# Phase Two Kosmos Import Field Audit — September 14, 2026

Canonical field-ownership and 25-product mapping record: `PHASE-TWO-REVEL-KOSMOS-WOOCOMMERCE-DATA-MAP-2026-09-14.md`.

## Outcome

The first manual Clarkston Revel-to-staging-WooCommerce run successfully created 245 products. The broad result is usable integration evidence, not a failed or damaged catalog. It proved the one-way connection, establishment scope, product creation, identifier mapping, price transfer, and logging.

The run did not include any of the 25 products in the approved pilot export, including `REBEKAH'S NAC 1000mg`. The existing 245 products can still be used to finish the field-mapping and presentation tests; NAC remains a separate product-specific acceptance item.

No Revel record was changed. The Kosmos Task remains manual with no automatic schedule, and no WooCommerce-to-Revel Action exists.

## Exact public staging results

| Check | Result | Interpretation |
| --- | ---: | --- |
| Published products created/visible through Store API | 245 | All records are publicly reachable on the noindex staging hostname |
| Unique WooCommerce product IDs | 245 | No duplicate product records in this run |
| Unique WooCommerce SKUs | 245 | Every imported barcode became a unique WooCommerce SKU |
| Blank SKUs | 0 | Identifier transfer succeeded for every record |
| Duplicate SKU groups | 0 | No identifier collisions detected |
| Duplicate name groups | 0 | No exact duplicate names detected |
| Blank names | 0 | Every record received a name |
| POS-style all-cap names | 243 | Almost every name needs customer-facing cleanup |
| Prices present | 245 | Every record received a nonzero price |
| Price range | $1.50–$66.00 | No zero-price product was published |
| Products with a long description | 1 | The only description is `1 fl oz`; 244 are blank |
| Products with a short description | 0 | All need content |
| Products with images | 0 | All need approved product imagery |
| Products assigned a WooCommerce category | 2 | Only the two Beekeeper's products mapped to `Immune Support`; 243 are uncategorized |
| Products with tags | 0 | Website merchandising metadata did not transfer |
| Products with brands | 0 | Brand/manufacturer did not transfer into WooCommerce brands |
| Products with attributes or variations | 0 | All 245 were created as simple products |
| Products in stock | 0 | This product Action did not populate sellable inventory |
| Products marked purchasable | 245 | They are valid WooCommerce products, but out-of-stock status prevents purchase |
| Products with weight | 0 | Shipping weight must come from another verified source or be added in WooCommerce |
| Products with dimensions | 0 | Package dimensions did not transfer |

## Observed field ownership and mapping

| Field | What happened | Working rule |
| --- | --- | --- |
| Product name | Revel name transferred to WooCommerce | Treat Revel as the operational source; clean customer-facing title in WooCommerce only after overwrite behavior is tested |
| Stable identifier | Revel barcode transferred to WooCommerce SKU | Keep barcode-to-SKU mapping; it is unique across all 245 records |
| Price | Revel selling price transferred | Treat Revel as the operational price source unless the client approves another rule |
| Inventory | No sellable inventory transferred; all products are out of stock | A separate one-way inventory Action or another documented inventory step is required before checkout testing |
| POS category | Kosmos attempted category lookup | Exact WooCommerce category matches can map; otherwise the product remains uncategorized |
| Website category/collection | Mostly absent | Build and maintain approved customer-facing taxonomy in WooCommerce after overwrite behavior is known |
| Description | Blank for 244; one record received only `1 fl oz` | Do not rely on Revel for customer-ready copy; use approved WooCommerce content |
| Short description | Not transferred | Create in WooCommerce |
| Images | Not transferred | Source and approve images separately, then test whether a later sync preserves them |
| Brand | Not transferred | Create/assign in WooCommerce if needed for filters and merchandising |
| Attributes/variations | Not transferred | Add only when the approved product structure requires them |
| Weight/dimensions | Not transferred | Collect verified shipping data before live-rate and label tests |

## Pilot comparison

The client-supplied pilot workbook contains 25 active, online/third-party-enabled private-label products in `Sheet1!A2:V26`. Exact comparison by barcode found **0 of 25** in the 245-product WooCommerce result. The missing set includes NAC barcode `733739401854`.

Plain-English filter explanation: the Kosmos Task was told to import Clarkston products whose Revel record had been updated after September 1, 2026 at 12:05 PM. It was not restricted to the 25 rows in the spreadsheet and did not use that spreadsheet as its import list. Revel returned 245 products meeting that date rule, while none of the approved 25 happened to be in that returned group. The date rule explains the unexpected population; it does not mean the connector failed.

## Practical next work

1. Blue Nova staging WordPress administrator access is verified working through the `Cody` account. This access is Blue Nova's responsibility, not a client deliverable from Mark or Rebekah.
2. Change the 245 imported products to Draft/quarantine, or use the existing Blue Nova-controlled staging protection, so the working catalog is not publicly browsable while it is incomplete. Do not delete the records; they are useful evidence and can be restored.
3. Use one of the imported records to complete the field/overwrite test immediately. This avoids waiting for NAC just to learn how names, categories, descriptions, images, and WooCommerce-only merchandising behave.
4. Keep the Kosmos product Task manual and leave every WooCommerce-to-Revel Action absent.
5. Configure and test inventory as a separate one-way Revel-to-WooCommerce step only after the representative product and product visibility are controlled.
6. For the approved 25-product launch set, either identify a verified Kosmos cutoff/filter that includes those exact records or create a controlled WooCommerce catalog from the supplied workbook and then reconcile identifiers against Revel.
7. Add customer-ready titles, descriptions, images, website categories, brands/filters, and verified shipping weights/dimensions before product acceptance.

## Verified Revel-to-WooCommerce eligibility and trigger rules

Kosmos's current Revel-to-WooCommerce documentation distinguishes **eligibility**, **change detection**, and **execution**:

1. **Eligibility gate:** a product must be Active and have Revel's `Display on online and 3rd party applications` setting enabled. For product creation it also needs a Revel SKU or barcode, name, category, price of zero or more, and inventory value of zero or more. Description and weight are optional. Having inventory by itself does not make a product eligible, and zero inventory does not exclude it.
2. **Product-information trigger:** a qualifying record needs a `product_time_stamp` newer than the Action's date/time boundary. Revel product-information changes such as price or other supported product edits update this timestamp. This trigger creates or updates the listing and price; it does not update inventory.
3. **Inventory trigger:** a qualifying existing product needs a new `inventory_time_stamp`. Revel updates this when inventory changes through an in-store sale, manual inventory adjustment, received purchase order, or a supported online order inserted into Revel. A separate Standard or Matrix Inventory Action sends that quantity to WooCommerce, matched through Revel SKU/barcode to WooCommerce SKU.
4. **Execution trigger:** Kosmos performs the pull only when the containing Task is run manually or by an enabled schedule. Current project Tasks remain manual.

Revel custom Online Menus are **not** the product-export filter. Kosmos documents that those menus control which items may be included in Revel web orders, but do not control which items its product Actions export to the shopping cart.

Operational launch rule: Rebekah's staff select the intended online catalog with the product-level `Display on online and 3rd party applications` setting. Blue Nova/Kosmos then process only qualifying Clarkston records within the chosen timestamp window. Product Class, Primary Vendor, stock quantity alone, and inclusion on a custom Online Menu are not product-export gates.

Removal caveat: Kosmos's published documentation says a product without the online/third-party setting no longer meets product or inventory Action criteria, but it does not state that clearing the setting automatically drafts, hides, or deletes an existing WooCommerce product. Treat online removal as a separate acceptance test and, until proven, explicitly hide/draft the WooCommerce item rather than assuming the connector removes it.

## Decision

The 245-product import does **not** have to be deleted or treated as a major failure. It can be quarantined and used as the mapping test population. Staging WordPress administrator access is now working, so the next gate is Todd's approval of the exact reversible quarantine and representative-product test actions—not a client access request.

Read-only WordPress inspection of `HRBPHRM NERVOUS SYSTEM 1OZ` confirmed the transferred fields: raw POS title, barcode `090900000026` as SKU, regular price `$19.00`, simple-product type, out-of-stock status, `Uncategorized`, no brand, no image, no short description, and only `1 fl oz` in the long-description field. The client-supplied 25-row pilot workbook likewise contains blank product-description and SKU cells for every row. The missing customer-ready content is therefore a source/catalog-enrichment gap, not evidence that Kosmos discarded supplied descriptions.

Read-only inspection of Revel product ID `18872` confirmed the same source values: category path `AdditionalItems > AdditionalItems`, product class `Herb Pharm`, raw name `HRBPHRM NERVOUS SYSTEM 1OZ`, description `1 fl oz`, barcode `090900000026`, blank Revel SKU, cost `$9.49`, price `$19.00`, blank manufacturer, Active enabled, and Track in inventory enabled. The WooCommerce record therefore matches the available Revel name, description, barcode, and selling price. Category did not map because the broad Revel category/classification does not match the approved website taxonomy. Stock stayed zero because inventory synchronization is a separate controlled action from product creation.

Description clarification: the approved 25-row spreadsheet has no description in any of its 25 description cells. Among the 245 imported WooCommerce records, 244 have no long description and one has only `1 fl oz`; all 245 have no short description. `HRBPHRM` is text in the raw register product name, not a populated manufacturer value. In Revel this representative product is classified under `AdditionalItems > AdditionalItems` and its Product Class is `Herb Pharm`; in WooCommerce it landed in `Uncategorized` with no brand.

Inventory clarification: `Track in inventory` is a Revel on/off setting that says Revel should maintain inventory for the product. It is not the stock quantity. WooCommerce received quantity `0` and shows the product as Out of stock. The completed Kosmos Task was a product-creation Task, not an inventory Task, so no Revel on-hand quantity was transferred or renamed. A separate read-only-safe, one-way Revel-to-WooCommerce inventory test is required to prove the on-hand quantity mapping.

## Live Revel comparison of the 25-row pilot workbook

Read-only barcode searches in Clarkston Revel found all 25 workbook products. Every workbook barcode resolves to exactly one corresponding Revel product, and all 25 product names match after normalizing repeated spaces; 21 are exact character-for-character matches and four differ only because the workbook contains a doubled space. All 25 also match Revel's visible product class (`Private Label`), primary vendor (`Rebekahs Private Label`), inventory unit (`Unit`), and selling price.

The workbook is therefore based on the actual Revel product records, not a separate set of titles Mark invented for the website. The added `Status` and `Remarks` columns function as working-sheet tracking fields; `Status` is `Updated` for all 25 and `Remarks` is blank for all 25.

A full read-only inspection of `REBEKAH'S NAC 1000mg` (Revel product ID `46236`) confirmed the workbook values currently shown in the live product record: category `Immune Support`, subcategory `Immune Support`, product class `Private Label`, blank description, barcode `733739401854`, blank SKU, product cost `$14.43`, selling price `$27.00`, primary vendor `Rebekahs Private Label`, vendor item ID `100185`, default reorder quantity `0`, default reorder price `$14.43`, and reorder-to-PAR enabled. No value was edited or saved.

Do not confuse Revel's Product Inventory list column `Cost / Inv Unit` with the editable product-detail `Cost`/default reorder price recorded in the workbook. For seven of the 25 products the inventory-list cost differs from the workbook product cost; NAC is `$11.81` in the inventory list but `$14.43` in the product record and workbook. That is a field-definition difference, not evidence that the workbook is incorrect.
