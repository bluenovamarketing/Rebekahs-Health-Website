# Phase Two Revel → Kosmos → WooCommerce Data Map

Prepared: September 14, 2026  
Status: Authoritative working map for the 25-product pilot  
Scope: Clarkston Revel establishment `3` → Kosmos eSync → protected staging WooCommerce

## Operating rule

### Superseding vendor clarification — September 14 replies

The side-conversation inbox review supplied these replies to the main task at Todd's request. They supersede earlier statements that Mark/Kosmos answers are still pending; no new production or paid-work authority follows from them.

- Mark, 15:46 EDT: Kosmos appears to be the only Revel-connected service, so he expects no cross-service conflict. This is Mark's operational confirmation, not an independent integration inventory. Source: https://mail.google.com/mail/#all/1a0a175116c312c3
- James at Kosmos, 15:53 EDT, ticket 461388: unchecking Display on online and 3rd party applications stops ALL syncing, including stock and prices. Inactive/deleted Revel products remain online; the standard integration does not delete/remove them. Rechecking allows syncing to resume when qualifying updates occur. Product details and inventory have separate change triggers. Custom removal needs consultation and a quote; none authorized. Source: https://mail.google.com/mail/#all/1a0a17bb7b706419
- **Ordinary sold out:** keep the online checkbox enabled. Test that a qualifying inventory update sends zero, managed-stock Woo with backorders disabled becomes non-purchasable Out of stock, and replenishment restores availability. Product may remain visible. Todd indicated this may meet normal operational needs. This is expected behavior, NOT a passed test, and does not itself require custom unpublishing.
- **Deliberately withdrawn from online sale while still stocked in-store:** a separate Woo hide/unpublish process is required. Do not conflate this with ordinary sold-out stock or assume the paid custom-removal option is required.
- Clearing the flag before zero reaches Woo risks leaving stale purchasable stock. Do not edit live Revel flags/inventory for a test without exact action-time approval. Stage zero/replenishment tests using controlled Woo-side simulation or an already occurring, verified Revel change; distinguish simulated Woo behavior from actual end-to-end transport evidence.
- The general vendor answer about resuming sync does not guarantee preservation of enriched website copy. Our observed title/long-description overwrite remains valid.

### September 14 staging follow-through

Latest reconciliation continuation supersedes stale implementation wording below: fresh Woo Products UI reports 306 total = 245 published imports + 61 drafts. Exact all-status search for NAC barcode 733739401854 returns No products found. None of the 25 pilot barcodes occurs in completed replay 27285 request URLs; that is replay evidence, NOT a complete current Woo inventory of all25. Kosmos login restored from saved credential sheet without changing it. Product Action27772 readback confirms establishment3, cutoff2026-09-01T12:05:00, barcode matching, Use POS category; exposed form has no SKU/product-ID inclusion filter. No settings saved or tasks run. Need deterministic pilot inclusion: documented connector selection/backfill method or controlled exact-pilot staging bootstrap followed by transport reconciliation. Do not broaden the date blindly or modify Revel timestamps/flags to force inclusion. Separate inventory reliability proof remains open.

- Replay task27285 completed with245 distinct successful product updates and no unexpected HTTP errors. Title/long-description overwrite and short-description survival were confirmed; markers were cleaned. The actual25-product pilot remains a separate inclusion/reconciliation gap.
- `wordpress/plugins/rhn-catalog-presentation/` v0.2.1 is installed on staging. It protects approved source-referenced copy while leaving operational fields alone. All34 local checks and eight controlled internal wc/v1 checks passed, with baseline restoration. Bulk intake save/rollback passed; real approved source data remains missing. This is not an automated manufacturer-feed or complete production solution.
- Todd deferred the one-unit buffer implementation/test and Rebekah's approval email to later pre-launch work. Do not put those ahead of current catalog, content-protection and sync-reliability work. Production scheduling still requires eventual acceptance.
- Read-only staging diagnostics: Wordfence security-only Live Traffic has no requests to report; Woo logs show no September 14 fatal-errors file. These do not establish the cause of the prior inventory 503s or prove no server faults. Woo status reports 512 MB WordPress memory, PHP 8.2.33, 30-second execution limit, and WordPress environment type `production` despite the exact staging hostname and active staging guard. Do not raise limits, disable security, or infer a needed paid upgrade from these values alone. Next diagnostic source is timestamp-matched Cloudways web/PHP/server logs.

Revel owns operational retail data. WooCommerce owns the customer-facing website presentation. Kosmos moves only the fields assigned to its one-way Actions. The Revel barcode is the stable cross-system key for this pilot.

Do not use Primary Vendor, Vendor Item ID, product cost, reorder tax, or the POS category as a substitute for a different website field. Do not enable any WooCommerce-to-Revel Action, automatic schedule, order transfer, customer transfer, refund transfer, or inventory writeback without separate discussion and Todd's explicit action-time approval.

## Required ongoing automation model

Todd's operating requirement is continuous catalog automation, not manual entry for every new or discontinued product. After the controlled pilot proves the rules, the production design must provide:

1. A scheduled Revel → WooCommerce Standard Product Action that creates and updates qualifying online products by barcode, including Revel-owned name/source data and regular price.
2. A separate scheduled Revel → WooCommerce Standard Inventory Action that updates Clarkston quantity and stock status by the same barcode key.
3. A WooCommerce-side rules layer that automatically normalizes known title patterns, maps approved Revel category/class combinations to website categories and brands, assigns safe defaults, protects Woo-owned enrichment from blank source values, and records the last successful processing result.
4. An exception queue, rather than routine manual entry, for a genuinely missing or ambiguous source value. A new product may publish automatically only when its required identifier, price, inventory, approved classification, shipping data, and authoritative customer content satisfy the publication rules; otherwise it remains Draft and identifies the exact missing source.
5. An automated retirement rule for products that are no longer eligible online. Kosmos's documented eligibility filter does not prove that clearing Revel's online/third-party flag hides an existing WooCommerce product, so this requires either a successful connector disable test or a companion reconciliation job that compares the current Revel-eligible set and safely drafts products no longer present. Do not assume absence from an incremental feed means deletion until this is proven.

The automation can move, normalize, classify, preserve, and retire known data. It cannot truthfully generate missing ingredients, allergens, Supplement Facts, directions, warnings, product photography, or packaged shipping weight. Those facts must exist once in an authorized source—Revel, a manufacturer/supplier feed, approved label assets, or a controlled catalog intake record—after which the WooCommerce rules layer can ingest and reuse them automatically.

## Required Actions and direction

| Action | Direction | Purpose | Current state | Rule |
| --- | --- | --- | --- | --- |
| Standard Product | Revel → WooCommerce | Create/update simple products and regular price | Existing manual Action proved transport | Manual only until field overwrite tests pass |
| Standard Inventory | Revel → WooCommerce | Update Clarkston quantity for existing barcode-matched products | Manual one-way proof completed September 14; exact quantity and stock status passed for an actually selected barcode | Keep manual until load errors, safety-buffer behavior, and a smaller incremental window are resolved; match barcode to Woo SKU |
| Category | Revel → WooCommerce | Create/match operational categories | Not suitable as the final website taxonomy by itself | Do not bulk-create raw POS categories into the approved storefront without a controlled mapping |
| Matrix Product | Revel → WooCommerce | Create/update true Revel matrix products | Not required for the current 25 based on available records | Use only for a verified Revel matrix; do not combine separate simple products merely for presentation |
| Matrix Inventory | Revel → WooCommerce | Update true matrix-variation quantities | Not required for current pilot | Use only after a matrix product proof |
| Orders/customers/refunds | WooCommerce → Revel | Operational order processing | Disabled and outside this map | Separate approval and acceptance test required |

## Authoritative field ownership and mapping

| Source field or behavior | Kosmos / transformation rule | WooCommerce destination | Owner after launch | Current evidence and acceptance rule |
| --- | --- | --- | --- | --- |
| Revel establishment | Restrict every Action to establishment `3 Rebekah's - Clarkston` | Integration scope only | Revel/Kosmos | Already proven in Action settings and transaction logs |
| Revel product ID | Preserve as an internal reference when available | Protected product meta, not SKU or public copy | Revel | Store only for diagnostics; never use as the customer-facing identifier |
| Barcode/UPC | Map Revel barcode to Woo SKU | `sku`; optionally mirror to Woo global unique ID after a controlled test | Revel | Canonical key for this pilot; all 25 barcodes are unique and resolve to one live Revel record |
| Revel SKU | Do not replace the barcode-based Woo SKU during this pilot | Optional protected internal meta | Revel | Live Revel SKU is blank for 23 products; Energy uses its barcode and Ear Clear uses `4005401` |
| Vendor Item ID | Do not map to Woo SKU, brand, title, or public content | Optional protected purchasing meta only | Revel | Supplier purchasing reference; it is not a reliable website identifier |
| Product name | Initial product Action supplies the raw title | Woo product name | Revel initially; WooCommerce after approved cleanup | Import first, normalize capitalization/spacing in Woo, then prove a later product sync does not erase the approved title |
| Product description | Import only when Revel has a real description; never allow a blank source to erase approved content | Woo long description | WooCommerce/Rebekah after initial proof | All 25 live Revel descriptions are blank; customer-ready copy must be sourced and approved |
| Product Class | Apply an explicit translation table; do not treat every class as a brand automatically | Woo product brand or protected source meta | WooCommerce taxonomy | For these 25 only, `Private Label` maps to approved brand `Rebekah’s Private Label` |
| Product brand | Use only when populated and normalized | Woo product brand | Revel when reliable; otherwise WooCommerce | Representative live private-label record is blank; the website must use the explicit pilot brand mapping |
| Manufacturer | Do not infer from Primary Vendor | Woo product brand/manufacturer meta only when verified | WooCommerce/Rebekah | Manufacturer is blank in all 25 live records |
| Primary Vendor / vendor ID | Do not expose or map as public brand | No public destination; protected purchasing meta only if needed | Revel | All 25 use `Rebekahs Private Label`; this is supplier data, not a universal brand rule |
| Revel category and subcategory | Retain as source classification and use a controlled per-product translation | Woo product categories | WooCommerce/Rebekah | Exact-name matching works only when the Woo category already exists; most approved website names differ from POS names |
| Product price | Map to regular price | `regular_price` | Revel | All 25 live prices match the workbook; a product update must update price without duplicating the item |
| Product cost / ingredient cost | Do not send to the storefront | No core Woo public field | Revel | Private operational data; never display it. Do not confuse it with Product Inventory's current cost per inventory unit |
| MSRP | Do not treat as a sale price | No destination unless a later merchandising rule is approved | Unassigned | Workbook MSRP equals regular price for all 25 and no independent live MSRP field has been proven |
| Sale price / sale dates | No automatic rule yet | `sale_price` and sale dates | WooCommerce/Rebekah unless a Revel promotion workflow is approved | Keep blank until promotion ownership is decided and tested |
| Active | Required eligibility condition, not a direct publish instruction | Contributes to controlled product status | Revel | All 25 are Active; Active alone must not publish an unapproved website product |
| Display on online and 3rd party applications | Required eligibility gate | Contributes to controlled catalog eligibility | Revel | All 25 workbook rows are enabled; live representatives are enabled. Clearing it is not proven to unpublish an existing Woo product |
| `product_time_stamp` | Product Action processes records newer than the selected boundary | Determines create/update population | Revel/Kosmos | Use the smallest verified date window. It is not a 25-row inclusion list |
| Product status and catalog visibility | Kosmos currently creates products as published; contain this on staging | `status`, `catalog_visibility` | WooCommerce | New/changed products should remain Draft or quarantined until mapped content passes; publish is a separate approval gate |
| Track in inventory | Use with the separate Inventory Action | `manage_stock = true` | Revel/WooCommerce | Enabled for all 25 live records |
| Clarkston on-hand quantity | Standard Inventory Action sends quantity, matched by barcode/Woo SKU | `stock_quantity` | Revel | Verified on selected barcode `090900000019`: Revel `2.0000` → Woo quantity `2`; the approved one-unit website safety buffer is not yet implemented |
| Stock status | Derive from synchronized quantity and backorder rule | `stock_status` | WooCommerce rule fed by Revel | Quantity above zero → in stock; zero → out of stock; no backorders for the pilot unless separately approved |
| Inventory timestamp | Inventory Action processes changed inventory records | Determines quantity-update population | Revel/Kosmos | Product sync and inventory sync are separate Actions and separate timestamps |
| Low-stock threshold | Do not import a zero threshold blindly | Woo global/product low-stock setting | WooCommerce/Rebekah | All 25 Revel thresholds are `0`; use an approved website notification threshold instead |
| Negative-sale restriction | Do not translate directly into backorders | Woo backorder policy | WooCommerce/Rebekah | Revel restriction is off for all 25; safer pilot rule is still `backorders = no` |
| UOM / stock unit | Normalize to individual units | Internal stock unit | Revel | All 25 use `Unit`; Woo quantities should be whole sellable units |
| Reorder unit, quantity, price, PAR, tax on reorder | Do not transfer | No Woo customer-facing destination | Revel | Purchasing controls remain in Revel |
| Revel active taxes / tax class | Do not map until customer-sales-tax treatment is approved | Woo `tax_status` and `tax_class` | WooCommerce/Rebekah/accounting | Current products show `Untaxed @ 0.0%`; `Tax on reorder = No` is purchasing tax and must never set customer sales tax |
| Product weight and unit | Standard Product Action may transfer a populated value; convert into Woo's configured unit | Woo `weight` | Revel if maintained; otherwise WooCommerce/Rebekah | Workbook has no weight field and the representative live record is blank. Verified package weight is required before USPS live-rate acceptance |
| Dimensions | No proven standard source mapping | Woo length/width/height for identified exceptions | WooCommerce/Rebekah | Collect only for bulky or exceptional packages unless the carrier configuration requires more |
| Revel image | Import only when ownership and overwrite behavior are proven | Woo featured/gallery images | WooCommerce/Rebekah after proof | Broad import produced no images and the representative live record has none. Use the approved placeholder until approved photography exists |
| Short description | No Revel source field has been proven | Woo short description | WooCommerce/Rebekah | Write approved concise copy; protect it from later syncs |
| Ingredients/allergens | Do not infer from name, vendor, or category | Woo product content/attributes | WooCommerce/Rebekah | Use packaging or an authorized manufacturer/supplier source; client approval required |
| Supplement Facts | No current source field | Woo gallery/documented content | WooCommerce/Rebekah | Use a readable approved label image or verified structured data |
| Directions/warnings/restrictions | No current source field | Optional collapsed Directions & Warnings section and checkout controls when applicable | WooCommerce/Rebekah | Publish only verified product-specific content; otherwise omit the section |
| Form/count/size | Parse only as a staging aid; verify against label/source | Woo attributes or customer-facing details | WooCommerce/Rebekah | Do not create variations solely from similar names; keep separate barcode records separate unless Revel identifies a true matrix |
| Tags, collections, featured status, related products | No automatic POS mapping | Woo merchandising fields | WooCommerce/Blue Nova | Configure after approved taxonomy and content are present |
| SEO title, meta description, slug, canonical, schema | No connector mapping | WordPress/SEOPress/Woo schema | WooCommerce/Blue Nova | Create after titles and descriptions are approved; never source SEO from raw POS fields automatically |

## Approved website category translation for the 25-product pilot

These are the working website categories. They do not change Revel. Exact source-to-target matches can be applied automatically; the transformed and product-specific choices require Rebekah's approval before final publication because they affect how products are represented to customers.

| Revel category | Default Woo category | Exception rule |
| --- | --- | --- |
| Energy & Vitality | Energy & Fatigue | Direct translation |
| Immune Support | Immune Support | Congest-Eeze → Respiratory & Sinus Support; other named exceptions require approval |
| Brain & Cognitive | Brain, Focus & Memory | Direct translation |
| Stress & Sleep | Stress & Sleep | Direct match |
| Heart & Cardiovascular | Heart & Cholesterol Support | Direct translation |
| Greens & Superfoods | Pending category approval | Do not force Spirulina into an unsupported health claim; retain Draft until approved |
| Liver & Detox | Liver & Detox Support | Parasite → Cleanse Support |
| Urinary & Kidney | Kidney & Urinary Support | Direct translation |
| Vitamins & Minerals | Product-specific | B12/Folic/B6 → Energy & Fatigue; Iodine/Kelp → Thyroid & Adrenal Support |
| Herbs & Botanicals | Product-specific | Ginkgo → Brain, Focus & Memory |

## Barcode-by-barcode pilot map

All products map to Woo brand `Rebekah’s Private Label`. The category values below are website targets only; they do not authorize a Revel category change. On-hand quantities are a September 14 read-only snapshot and must be refreshed through the controlled Inventory Action.

| # | Revel ID | Barcode → Woo SKU | Live Revel SKU | Live product name | Price | On hand | Revel category | Woo website category |
| ---: | ---: | --- | --- | --- | ---: | ---: | --- | --- |
| 1 | 20075 | `733739433268` | `733739433268` | REBEKAH'S Energy 90 Caps | $24.00 | 0 | Energy & Vitality | Energy & Fatigue; hold pending discontinuation confirmation |
| 2 | 46236 | `733739401854` | blank | REBEKAH'S NAC 1000mg | $27.00 | 7 | Immune Support | Immune Support |
| 3 | 51579 | `733739430700` | blank | REBEKAH'S QUERCETIN W/BROMELAIN 120 CAP | $34.00 | 9 | Immune Support | Immune Support |
| 4 | 63683 | `733739423900` | blank | REBEKAHS MAGTEIN 90CT | $55.00 | 4 | Brain & Cognitive | Brain, Focus & Memory |
| 5 | 72920 | `788332231308` | blank | REBEKAH'S L-THEANINE 90CT | $20.50 | 5 | Stress & Sleep | Stress & Sleep |
| 6 | 72928 | `788332174704` | blank | REBEKAH'S RED YEAST RICE WITH COQ10 90CT | $33.50 | 4 | Heart & Cardiovascular | Heart & Cholesterol Support |
| 7 | 72933 | `788332198601` | blank | REBEKAH'S SPIRULINA 90CT | $18.50 | 2 | Greens & Superfoods | Pending category approval |
| 8 | 72950 | `788332226502` | blank | REBEKAH'S BLACK CUMIN SEED OIL 90 VGC | $26.50 | 5 | Immune Support | Immune Support |
| 9 | 72952 | `788332128905` | blank | REBEKAH'S CONGEST-EEZE 60CT | $34.50 | 5 | Immune Support | Respiratory & Sinus Support, pending approval |
| 10 | 72957 | `788332129209` | blank | REBEKAH'S LIVER DETOX WITH MILK THISTLE 60CT | $35.00 | 2 | Liver & Detox | Liver & Detox Support |
| 11 | 72958 | `788332083815` | blank | REBEKAH'S OREGANO OIL STANDARDIZED 60CT | $27.50 | 6 | Immune Support | Immune Support |
| 12 | 72959 | `788332083921` | blank | REBEKAH'S OREGANO OIL STANDARDIZED120CT | $43.00 | 4 | Immune Support | Immune Support |
| 13 | 72966 | `788332037313` | blank | REBEKAH'S ASHWAGANDHA 1OZ | $16.50 | 6 | Stress & Sleep | Stress & Sleep |
| 14 | 73047 | `788332230226` | blank | REBEKAH'S STONE BREAKER 2OZ | $32.00 | 3 | Urinary & Kidney | Kidney & Urinary Support |
| 15 | 73074 | `788332167812` | blank | REBEKAH'S B12 W/ FOLIC ACID & VITAMIN B6 1OZ | $15.00 | 8 | Vitamins & Minerals | Energy & Fatigue, pending approval |
| 16 | 73090 | `788332166921` | blank | REBEKAH'S IODINE W/ KELP 2OZ | $12.50 | 2 | Vitamins & Minerals | Thyroid & Adrenal Support, pending approval |
| 17 | 73341 | `788332227806` | blank | REBEKAH'S BRAIN MUSHROOM SUPPORT COG 60CT | $21.50 | 7 | Brain & Cognitive | Brain, Focus & Memory |
| 18 | 73344 | `788332217302` | blank | REBEKAH'S CRANBERRY + D-MANNOSE 60CT | $26.50 | 5 | Urinary & Kidney | Kidney & Urinary Support |
| 19 | 73350 | `788332155000` | blank | REBEKAH'S GINKGO 90CT | $20.00 | 2 | Herbs & Botanicals | Brain, Focus & Memory, pending approval |
| 20 | 73356 | `788332227509` | blank | REBEKAH'S LION'S MANE COG 60CT | $21.50 | 7 | Brain & Cognitive | Brain, Focus & Memory |
| 21 | 73358 | `788332155703` | blank | REBEKAH'S MILK THISTLE STANDARDIZED 90CT | $23.00 | 7 | Liver & Detox | Liver & Detox Support |
| 22 | 73404 | `788332051418` | blank | REBEKAH'S WILD OREGANO OIL 70% 1OZ | $36.00 | 5 | Immune Support | Immune Support |
| 23 | 73405 | `788332230110` | blank | REBEKAH'S ORGANIC OREGANO OIL 80% 1OZ | $41.00 | 3 | Immune Support | Immune Support |
| 24 | 73432 | `788332048517` | blank | REBEKAH'S PARASITE 1OZ | $17.00 | 3 | Liver & Detox | Cleanse Support, pending approval |
| 25 | 73767 | `788332054013` | `4005401` | REBEKAH'S EAR CLEAR OIL 1OZ | $17.00 | 5 | Immune Support | Immune Support; confirm whether a more precise approved category is desired |

Current snapshot: 24 of 25 have positive Clarkston on-hand inventory; Energy is zero. Total on hand across the 25 is 116 units. These values are evidence for the first inventory reconciliation, not fixed catalog content.

## Current missing-data and cleanup queue

Latest implementation: staging RHN Catalog Presentation v0.2.1 protects explicitly source-referenced, approved barcode-keyed name/description/short_description values on supported Woo product REST writes. Eight controlled internal wc/v1 assertions and baseline restoration passed; 34 isolated local checks passed. Intake validation/apply/rollback tested and restored. No real approved content loaded; missing descriptions/photos/labels remain genuinely missing. This is not an automatic manufacturer-feed connection or full catalog enrichment service. No Revel writes, schedules or new bulk replay enabled.

| Gap | Current state | Correct handling |
| --- | --- | --- |
| Customer descriptions | Blank in all 25 live Revel records and workbook rows | Source approved copy from packaging or authorized manufacturer/supplier materials, then obtain Rebekah approval |
| Manufacturer | Blank in all 25 live Revel records | Do not substitute Primary Vendor; use approved `Rebekah’s Private Label` brand mapping for this pilot |
| Product brand field | Representative live Revel record is blank | Apply the explicit pilot brand translation in WooCommerce |
| Product images | No image fields in workbook; broad import produced none; representative live record is blank | Use approved placeholder, then add approved product photography and alt text in WooCommerce |
| Weight | No workbook field; representative live record is blank | Collect verified packaged shipping weight before live USPS-rate acceptance |
| Dimensions | Not supplied | Request only for products that are unusually bulky or fail representative packing/rate tests |
| Ingredients, allergens, Supplement Facts, directions, warnings | Not supplied | Source from labels or authorized product materials; never invent |
| Customer-friendly capitalization and spacing | POS-style names | Normalize in WooCommerce after the overwrite test; retain barcode as the immutable match key |
| Woo category for Spirulina | No approved exact equivalent | Keep Draft or in a controlled internal source category until Rebekah approves the customer-facing category |
| Website category exceptions | Five product-specific proposals above | Obtain one consolidated Rebekah approval before publication |
| Inventory transfer | Manual Standard Inventory proof completed; 7 of 250 selected records matched existing Woo products and updated, 233 had no Woo match, and 10 Woo lookups returned HTTP 503 | Repeat with a small current incremental window after stabilizing Woo/API response; do not schedule until a clean run and safety-buffer test pass |

## September 14 Standard Inventory proof result

- Created the one-way Action `Clarkston staging - Revel inventory to WooCommerce - manual pilot` and the unscheduled Task `Clarkston staging manual inventory sync`.
- The saved Action is restricted to Revel establishment `3 Rebekah's - Clarkston`, processes inventory records updated after `2026-09-01T00:00:00`, and matches Revel barcode to WooCommerce SKU. It exposes no quantity-offset or safety-buffer control.
- The manual Task finished with Kosmos status `Done`, event group `27284`, event ID `086620f6-e41a-4372-9cca-ff74256be1c5`, 250 selected inventory records, and 513 report entries.
- The final log contains 7 successful WooCommerce inventory updates (`PUT 200`), 233 barcode searches where no matching Woo product existed, and 10 consecutive WooCommerce searches that returned HTTP `503`. Kosmos still labeled the overall Task `Done`, so final status alone is not sufficient acceptance evidence.
- Verified one item that the log proves was updated: `HERPHA SMOKER ASSIST 10Z`, barcode/SKU `090900000019`, showed `2.0000` units in live Clarkston Revel and quantity `2`, `2 in stock`, and purchasable maximum `2` in staging WooCommerce after the run. Exact quantity transfer therefore works for a barcode-matched selected record.
- The earlier representative `HRBPHRM NERVOUS SYSTEM 1OZ` (`090900000026`) was not in the 250-record inventory timestamp population and has no update entry in the final log. Its staging quantity `1` / Out of stock cannot be credited to this run and is not a valid comparison for this Action.
- The approved one-unit inventory safety buffer is not yet implemented: the verified `2`-unit product is currently offered as `2 in stock`. Buffer behavior remains a separate WooCommerce-side acceptance test.
- No Revel record or setting changed, no WooCommerce-to-Revel Action exists, and no Kosmos schedule was enabled.

## Overwrite and publication safeguards

1. Use the barcode-based Woo SKU for every create and update. Reject duplicates or missing barcodes before a bulk run.
2. Product Actions may update Revel-owned title/price/source fields. Inventory Actions alone update quantity.
3. A blank Revel description, image, weight, brand, or manufacturer must never erase a completed WooCommerce field.
4. Keep raw imports Draft/quarantined until identifier, price, category, stock, image, description, weight, and status are reconciled.
5. Test one Woo-only title/description/category/image change, rerun the manual Product Action without editing Revel, and document exactly what is preserved or overwritten.
6. Test one manual Inventory Action and require the Woo quantity to equal the live Clarkston quantity for the same barcode.
7. Do not assume disabling Revel's online/third-party flag hides an existing Woo product. Test the disable path separately; until proven, explicitly set the Woo product to Draft/hidden.
8. Do not enrich the remaining products until the overwrite test proves the work will survive later synchronization.
9. Do not publish product claims, category implications, directions, warnings, ingredients, label content, or photography without an approved source and Rebekah's approval.

## Evidence sources

### Source reconciliation correction — September 14, approximately 18:05 EDT

**Completed export follow-through, approximately 18:10 EDT:** browser recovered; native CSV downloaded and retained as `client-inputs/phase-two/woocommerce-staging-export-2026-09-14-1810.csv`. This supersedes the pending-export bullet below.

- Export has320 rows:306 parent/simple products plus14 variations. Parent/simple statuses reconcile exactly to245 published and61 drafts; variations account for14 additional draft export rows. No evidence of another catalog growth event.
- Exact barcode-to-Woo-SKU join across all25 yields ONE match: legacy draft Energy, WooID1108, SKU733739433268, current website price23.99 versus workbook/Revel24. Other24 have no exact SKU match. This is not equivalent to24 definitely absent products: legacy records can lack a SKU.
- Legacy draft NAC exists as WooID1167, name `NAC -N-acetyl-l-cysteine`, empty SKU, price26.99, populated description/short description and an image URL. It is a candidate for the pilot NAC, NOT a verified identical formulation/pack size. Do not assign the pilot barcode or merge automatically until label/strength/count identity is verified.
- Energy's existing description includes directions, warnings and Supplement Facts. Consequently the earlier general statement that such content is unavailable anywhere is too broad: it is absent from the supplied workbook/current Revel descriptions but some exists in legacy Woo content. Legacy age, formulation and label accuracy must be checked before reuse; do not republish old claims by assumption.
- 60 of61 legacy parent drafts have a nonempty long description;46 export rows have image URLs. They are a potential reusable content source, not approved current product-label evidence. Legacy NAC text explicitly lists serving size1 tablet and1,000mg, strengthening that identity candidate, but pack count/current barcode are not yet verified.
- Among245 published imports:244 have empty long descriptions;245 have empty Images;238 have blank Stock,2 have explicit0, and5 have positive quantities. Blank stock is unknown/unmanaged in this export, not proven zero. All320 export rows lack weight in the configured ounce field. Inventory reconciliation and package weights remain separate unfinished tasks.
- Next implementation sequence: resolve legacy identity matches before creating duplicates; preserve existing copy using the proven protection layer; initialize and verify inventory for matched records; only then apply source-backed content/category rules. No new broad connector run is needed merely to discover this data.

- Todd clarified that the 25-row spreadsheet is a supplied catalog file, not proof that Mark entered changes into Revel. Its `Status = Updated` cells and export-style filename do not establish a successful live import or a Revel modification timestamp.
- Reopened the actual XLSX read-only and compared all 25 rows against this document's previously recorded Revel IDs: 25 unique barcode matches; names agree after whitespace normalization, prices and recorded categories agree. All 25 spreadsheet descriptions are empty. Spreadsheet SKU is empty for all 25, unlike recorded live Energy and Ear Clear SKUs. The barcode remains the join key, not the spreadsheet SKU.
- Fresh direct Revel checks: NAC ID46236 has barcode733739401854, name REBEKAH'S NAC 1000mg, price27, Immune Support category, Private Label class, empty SKU and description. Ear Clear ID73767 has barcode788332054013, price17, Immune Support category, Private Label class, empty description, but SKU4005401. Both match the recorded identities; no values were saved. Other 23 remain prior live observations, not newly reverified in this pass.
- These checks prove identifiable products, not who edited Revel or when. Do not require a Revel write to make the spreadsheet count appear in a sync. Treat connector inclusion, current Woo presence, and missing customer content as separate questions.
- Current Woo list again shows306 total /245 published /61 drafts. Full native export was attempted to reconcile all25 in one pass, but in-app browser control timed out on the export navigation and fresh-tab recovery. No CSV received or full Woo match result claimed. NAC's previously verified absence remains the only exact current Woo absence established here.
- Added read-only `scripts/compare-pilot-source.py` to repeat workbook/map reconciliation and optionally compare one native Woo export by exact SKU, reporting duplicate/missing matches. No workbook, product, stock, live-site or connector setting is changed by the script.
- Local support draft is on HOLD; no email sent. Continue with the native Woo export/current catalog reconciliation when browser control responds, without restarting completed copy-protection or zero/restock tests.

- Client pilot workbook: `client-inputs/phase-two/Product_Export_Establishment_3 (70)_results.xlsx`, `Sheet1!A1:V26`.
- Read-only live Clarkston Revel product detail and inventory records for all 25 barcodes, inspected September 14, 2026. No Revel value was saved or changed.
- Completed manual Kosmos Action logs and the 245-product staging field audit in `PHASE-TWO-KOSMOS-IMPORT-FIELD-AUDIT-2026-09-14.md`.
- Kosmos official Revel/WooCommerce product and inventory criteria: https://help.kosmosesync.com/index.php/knowledge-base/integrating-revel-systems-pos-products-with-woocommerce/
- WooCommerce REST product fields: https://woocommerce.github.io/woocommerce-rest-api-docs/#products

## Next controlled proof

Use one already imported staging product to establish Product Action overwrite behavior without changing Revel. Then repeat the Standard Inventory proof with a small current timestamp window, require zero Woo/API errors, and implement/test the approved one-unit website safety buffer. Do not enable schedules or any WooCommerce-to-Revel Action. After those results are clean, resolve the exact-25 product inclusion method, apply the same rules to the 25-product set, and send Rebekah one consolidated approval/gap list.
