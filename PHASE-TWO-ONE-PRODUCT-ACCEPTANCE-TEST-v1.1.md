# Rebekah’s Phase Two Ecommerce — One-Product Synchronization Acceptance Test

Version: v1.1  
Prepared: 2026-09-02  
Status: Product transport proved on a broad population; exact inventory quantity proved on one selected barcode; NAC-specific acceptance, clean incremental reliability, overwrite protection, retirement, and safety-buffer behavior remain open; all Revel writes still require separate action-time approval

## Purpose

Prove one representative Revel product through Revel → Kosmos eSync → WooCommerce before the remaining 24 pilot products are synchronized. This is an internal QA step, not a separate client design-approval gate.

## Test rule

Record actual source and destination values after the connection. Do not pre-fill speculative field mappings. A difference is not automatically a failure if the result is intentional, documented, and owned by the correct system.

**Standing Revel safeguard (added 2026-09-06):** Revel is the client's live production POS and inventory system. Blue Nova/Codex must not change any Revel product, price, inventory, category, online flag, user, permission, API credential, integration, order, customer, refund, or setting unless Todd and Codex first discuss that exact action and Todd explicitly approves it at action time. The initial proof is limited to a verified read-only Revel-to-staging-WooCommerce product import. If Kosmos cannot guarantee that direction, stop before running it.

## Test product selection

Prefer one ordinary, shippable product with a unique barcode/SKU, normal inventory, a current selling price, a normal category, and enough data to observe transfer behavior. If variations are in the pilot, test a variation product separately after the simple-product proof passes.

Selected candidate: `REBEKAH'S NAC 1000mg`, chosen by Mark on 2026-09-08 because it was freshly restocked. Do not use the earlier `REBEKAH'S Energy 90 Caps` candidate because the store may discontinue it. Mark also confirmed that none of the 25 products has a shipping restriction. Confirm NAC's current identifier, Clarkston price, inventory, type, and online/third-party flag read-only at execution.

| Test identity | Record at execution |
| --- | --- |
| Revel product name | `REBEKAH'S NAC 1000mg` (selected; verify unchanged at execution) |
| Revel product ID | `46236` (read-only live verification, 2026-09-14) |
| SKU / barcode / UPC | SKU blank; barcode `733739401854` (read-only live verification, 2026-09-14) |
| Clarkston inventory before test | Not captured; no Revel value was changed |
| Product type | Simple / variation |
| Online/third-party flag before test | Enabled (read-only live verification, 2026-09-14) |
| Kosmos action name | `Clarkston staging - Revel products to WooCommerce - manual pilot` |
| WooCommerce product ID/URL | No WooCommerce product with SKU/barcode `733739401854` was returned after the run; NAC was not in the imported set |
| Test started | 2026-09-14 12:11:28 EDT |
| Test completed | 2026-09-14 12:43:37 EDT |
| Operator | Blue Nova / Codex |

## A. Preflight

| # | Check | Expected result | Actual/evidence | Result |
| --- | --- | --- | --- | --- |
| A1 | Staging protection | Authenticated, noindex, email/payment side effects blocked | Staging Guard remains active for noindex, email, payment, and webhook isolation. The Cloudways staging hostname is still publicly reachable because HTTP password protection is not enabled. | Conditional |
| A2 | Correct Revel location | Product belongs to Clarkston | Kosmos Action configuration explicitly selected establishment `3 Rebekah's - Clarkston`. The completed transaction log's Revel GET requests include `establishment=3`. | Passed |
| A3 | Product status | Active | NAC is Active in the existing 25-product Revel export | Passed |
| A4 | Online eligibility | Display on online and 3rd party enabled | Enabled on the live NAC product record | Passed |
| A5 | Unique identifier | SKU/barcode is present and not duplicated in staging | Kosmos was configured to map Revel `barcode` to WooCommerce SKU. The exact NAC barcode `733739401854` returned no product after the run, so NAC-specific collision/creation could not be proven. | Not reached for NAC |
| A6 | Bulk schedules | Disabled before manual test | Task schedule remained optional/unselected; the `Every day` radio was unchecked. Run was started manually. | Passed |
| A7 | Legacy collision | No active legacy WooCommerce product can claim the same identifier | The 61 legacy records remain Draft. The run created 245 new published staging products, but not NAC. | Conditional |

## First-run scope outcome

Todd explicitly approved proceeding with a broader reversible staging-only scope rather than treating the inability to isolate NAC as a blocker. The saved Action proved the Revel API location scope: Kosmos exposed and selected establishment `3 Rebekah's - Clarkston`.

The manual Task used a September 1, 2026 cutoff and barcode-to-SKU matching. Kosmos found 245 qualifying Clarkston records, not only the 25-product client packet. It completed 245 category lookups (`GET 200`) and 245 product creations (`POST 201`) with no logged errors. This did not write to Revel, but it created 245 published products on the publicly reachable, noindex staging hostname.

NAC barcode `733739401854` was not present in the WooCommerce Store API after completion, so the cutoff did not include the selected proof product. Blue Nova staging WordPress administrator access has since been verified working through the `Cody` account. Do not run a broader timestamp pull until the 245 imported products are recoverably quarantined and a controlled way to include NAC is chosen. Do not change Revel online flags or any other Revel data as a workaround without separate action-time authorization.

## B. Product creation and field observation

### Broad-run mapping evidence (separate from the NAC-specific result)

The broader approved staging run provides useful field evidence even though NAC was not included. Across all 245 imported records, Revel barcode mapped cleanly to unique WooCommerce SKU and every record received a name and nonzero price. All records were created as simple, purchasable products but remained out of stock. Customer-facing fields were largely absent: zero images, zero short descriptions, zero brands, zero tags, zero attributes/variations, zero weights, zero dimensions, and only one minimal long description (`1 fl oz`). Two exact category matches reached `Immune Support`; the other 243 records are uncategorized. Exact barcode comparison against the 25-row pilot workbook found no matches.

Read-only source comparison for Revel product ID `18872` (`HRBPHRM NERVOUS SYSTEM 1OZ`) confirmed that name, description `1 fl oz`, barcode `090900000026`, and `$19.00` price transferred correctly. The Revel SKU and manufacturer are blank; the source classification is `AdditionalItems > AdditionalItems` / `Herb Pharm`; Active and Track in inventory are enabled. WooCommerce has no image, brand, short description, website category, or stock. This proves customer-ready copy was not dropped in transit; it was absent or minimal at the Revel source, while inventory remains outside the product-creation action.

This evidence allows field and overwrite testing to continue on one imported product now that staging WordPress access is verified. It does not change the NAC-specific result below.

| # | Field/behavior | Revel before | WooCommerce after | Expected result | Result |
| --- | --- | --- | --- | --- | --- |
| B1 | Product identity | NAC ID `46236` | No NAC product returned by exact barcode/SKU query | One product created or intentionally matched; no duplicate | Failed for NAC |
| B2 | Name | `REBEKAH'S NAC 1000mg` | Not created | Customer-appropriate value or documented cleanup requirement | Not reached |
| B3 | SKU/barcode/UPC | SKU blank; barcode `733739401854` | Exact WooCommerce SKU query returned `[]` | Stable unique identifier retained | Failed for NAC |
| B4 | Price | `$27.00` | Not created | Matches Revel | Not reached |
| B5 | Clarkston inventory | Not captured | Not created | Matches the selected fulfillment location | Not reached |
| B6 | Stock status | Not captured | Not created | Consistent with inventory/backorder rules | Not reached |
| B7 | Operational category | Immune Support | Not created | Transfer/mapping behavior documented | Not reached |
| B8 | Brand/manufacturer | Private Label product class; manufacturer blank in visible Revel fields | Not created | Transfer behavior documented | Not reached |
| B9 | Weight | No standard product-weight value observed | Not created | Transfer behavior and source field documented; no Sold by Weight workaround | Not reached |
| B10 | Images | Not observed | Not created | Transfer/absence/overwrite behavior documented | Not reached |
| B11 | Description | Blank in visible Revel field | Not created | Transfer/absence/overwrite behavior documented | Not reached |
| B12 | Variations, if applicable |  |  | Options, identifiers, price, and inventory remain distinct | Not applicable |

## C. Update and overwrite proof

September 14 follow-on protection proof: RHN Catalog Presentation v0.2.1 active on staging. Internal wc/v1 PUT on Woo 1479193 passed eight persisted assertions for protected title, long/short copy, HTML sanitization, exact SKU, incoming price/stock, raw source copy and HTTP response (sanitization combined with long-copy assertion). Baseline fields/metadata restored and read back; fresh canonical page shows original title, $19, 2 in stock/Add to cart. This closes the controlled Woo API hook/persistence proof, not external Kosmos authentication/transport or full catalog readiness. Bulk intake preview/apply/restore tested with synthetic non-product registry entry and restored; real approved content not yet loaded.

Use a safe, reversible staging-only value. Do not change Revel or any production/customer-facing data. A Revel-owned field update may be observed only after separate discussion and explicit action-time approval, or when Rebekah's staff makes the change themselves.

| # | Action | Expected result | Actual/evidence | Result |
| --- | --- | --- | --- | --- |
| C1 | Observe one separately approved/client-performed Revel-owned field update and resync | WooCommerce updates once, without duplicate creation; Blue Nova/Codex does not edit Revel without separate approval |  | Deferred pending separate approval |
| C2 | Change a WordPress presentation field and resync | Outcome proves whether Kosmos preserves or overwrites it | September 14 replay 27285 completed 16:52:29 EDT, approximately 80m27s. All 245 distinct product updates succeeded with no unexpected HTTP errors. Woo 1479181 PUT 200 sequence 479: title and long description reverted to Revel; short-description marker survived. Original three fields restored and verified. | Overwrite behavior proved for this sample; title/long-description protection still required. Short description survived this run only; not a universal guarantee. |
| C3 | Observe separately approved/client-performed online/third-party disabling and resync | Product visibility/eligibility changes as intended without data loss; Blue Nova/Codex does not edit Revel without separate approval |  | Deferred pending separate approval |
| C4 | Observe separately approved/client-performed re-enabling and resync | Product returns to the intended staging state |  | Deferred pending separate approval |
| C5 | Review Kosmos logs | Action, timing, warnings, and errors are visible | Completed transaction log: 245 Revel-qualified records, 245 category lookups at GET 200, 245 WooCommerce creations at POST 201, zero error-like entries | Passed |

## D. Inventory and order proof

WooCommerce-to-Revel order and inventory-write tests remain deferred until Todd separately discusses and explicitly authorizes them. The one-way Revel-to-staging-WooCommerce Standard Inventory proof below does not write to Revel.

| # | Check | Expected result | Actual/evidence | Result |
| --- | --- | --- | --- | --- |
| D0 | One-way standard inventory transfer | A selected barcode-matched product receives the exact Clarkston on-hand quantity and correct Woo stock state | September 14 manual inventory Task selected 250 updated Revel records. Seven existing Woo products updated successfully. Verified `HERPHA SMOKER ASSIST 10Z` (`090900000019`): Revel `2.0000` → Woo quantity `2`, public `2 in stock`. Ten other Woo searches returned HTTP 503, so a smaller clean rerun is still required before scheduling. | Quantity mapping passed; batch reliability and one-unit buffer pending |
| D1 | Place one staging test order | One WooCommerce order is created with no real payment |  | Not run |
| D2 | Order transfer | The intended order reaches Revel once |  | Not run |
| D3 | Location routing | Order is assigned to Clarkston only |  | Not run |
| D4 | Inventory reduction | Correct quantity is deducted once at Clarkston only |  | Not run |
| D5 | Duplicate protection | Retrying/reviewing does not create a second order or second deduction |  | Not run |
| D6 | Cancellation/refund behavior | Observed behavior is documented; no unsupported promise is made |  | Not run |

## E. Decision record after observation

The complete field-level ownership, transformation, missing-data, and barcode-by-barcode pilot decisions are maintained in `PHASE-TWO-REVEL-KOSMOS-WOOCOMMERCE-DATA-MAP-2026-09-14.md`. This table is the one-product test summary; it must be updated with actual overwrite results after the next manual Product and Inventory proofs.

| Field or behavior | Confirmed owner | Overwritten on sync? | Cleanup rule for remaining 24 | Evidence |
| --- | --- | --- | --- | --- |
| Online eligibility | Revel | Existing-product removal/hiding is not yet proven | Require Active + display online/third-party for import; keep Woo publication status separately controlled | All 25 pilot records qualify in Revel; Kosmos official eligibility criteria |
| Name | Revel initially; Woo after approved cleanup | Pending one-product overwrite proof | Normalize customer-facing title only after proving it survives subsequent Product Actions | Broad run imported raw Revel names |
| Identifier | Revel barcode | Barcode-to-Woo-SKU creation passed; update behavior still to be reconfirmed | Keep the barcode as the immutable match key; do not replace it with Revel SKU or Vendor Item ID | 245/245 broad imports received a unique SKU; all 25 pilot barcodes resolve uniquely in Revel |
| Price | Revel | Creation passed; update behavior pending | Map to Woo regular price and reconcile against live Revel | Broad-run prices transferred; all 25 workbook prices match live Revel |
| Inventory | Revel Clarkston on-hand through a separate Inventory Action | Exact quantity transfer passed for a selected barcode-matched product; scheduling, clean-batch reliability, and safety buffer pending | Keep barcode/SKU matching and prohibit Woo-to-Revel inventory writeback; repeat with a small timestamp window before scheduling | `090900000019` transferred `2.0000` Revel units to Woo quantity `2`; the wider run had 7 PUT 200 updates, 233 no-match results, and 10 Woo GET 503 responses |
| Weight | Revel only if reliably maintained; otherwise Woo/Rebekah | Not tested; current source is missing | Collect verified packaged shipping weight before live USPS-rate acceptance and protect Woo enrichment from blank source values | Pilot workbook has no weight field; representative live record is blank |
| Operational category | Revel | Initial exact-name association partially observed | Preserve as source classification; translate per product into the approved website taxonomy | Two broad imports matched `Immune Support`; the remaining raw categories did not match existing Woo categories |
| Images | Woo/Rebekah after source and overwrite proof | Not tested; no source image observed | Use approved placeholder, then approved photography; prove blank source cannot erase it | Broad import delivered zero images; representative live record has none |
| Descriptions | Woo/Rebekah after initial source observation | Not tested; all pilot sources are blank | Create only from packaging or an authorized source, approve it, then prove blank Revel values cannot erase it | All 25 pilot descriptions are blank in both workbook and live Revel |
| Website categories/collections | Woo/Rebekah | Not a direct POS mapping | Apply the approved per-product translation; keep unresolved items Draft until approved | Canonical data map records every pilot product and all category exceptions |
| SEO and merchandising | Woo/Blue Nova with Rebekah approval | No connector mapping expected; verify in overwrite test | Build only after titles, content, categories, and images are approved | Woo product fields support these values; no Revel source was proven |

## Pass criteria

The test passes only when:

- Exactly one intended WooCommerce product represents the Revel product.
- The identifier is stable and collision-free.
- Price and Clarkston inventory match Revel.
- Online eligibility behaves as intended.
- Update and overwrite behavior is known for every field that could destroy later content work.
- Kosmos logs are accessible and usable.
- If the order action is in scope, one test order transfers once and reduces Clarkston inventory once.
- Every exception has an owner and a safe rule for the remaining 24 products.

Final result: **Fail for the NAC-specific acceptance test; pass for one-way Clarkston transport and logging**

Approved to synchronize remaining 24: **No—first quarantine the 245 broad-import records and complete NAC-specific validation**

Reviewer: Blue Nova / Codex

Date: 2026-09-14

Exceptions: The September 1 timestamp selected 245 recently updated Clarkston products but excluded NAC. All 245 were created successfully and published on the noindex staging hostname. A prior stored-credential attempt was rejected once, but Blue Nova's `Cody` administrator session is now verified working. Read-only inspection of `HRBPHRM NERVOUS SYSTEM 1OZ` confirmed a raw title, barcode-based SKU, `$19.00` price, out-of-stock status, no image/brand/short description, and only `1 fl oz` as the long description.

Working clarification: the 245 records are usable mapping evidence and do not need to be deleted. They may be recoverably quarantined and one representative record may be used to complete WooCommerce-side field/overwrite testing before NAC is imported. The restriction above applies to declaring the NAC-specific acceptance test passed and proceeding as if the approved 25-product set has been reconciled; it does not block useful staging work on the existing imported records.
