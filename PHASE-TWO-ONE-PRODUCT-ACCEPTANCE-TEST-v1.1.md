# Rebekah’s Phase Two Ecommerce — One-Product Synchronization Acceptance Test

Version: v1.1  
Prepared: 2026-09-02  
Status: Local test definition complete; connection not authorized

## Purpose

Prove one representative Revel product through Revel → Kosmos eSync → WooCommerce before the remaining 24 pilot products are synchronized. This is an internal QA step, not a separate client design-approval gate.

## Test rule

Record actual source and destination values after the connection. Do not pre-fill speculative field mappings. A difference is not automatically a failure if the result is intentional, documented, and owned by the correct system.

## Test product selection

Prefer one ordinary, shippable product with a unique barcode/SKU, normal inventory, a current selling price, a normal category, and enough data to observe transfer behavior. If variations are in the pilot, test a variation product separately after the simple-product proof passes.

| Test identity | Record at execution |
| --- | --- |
| Revel product name |  |
| Revel product ID |  |
| SKU / barcode / UPC |  |
| Clarkston inventory before test |  |
| Product type | Simple / variation |
| Online/third-party flag before test |  |
| Kosmos action name |  |
| WooCommerce product ID/URL |  |
| Test started |  |
| Test completed |  |
| Operator |  |

## A. Preflight

| # | Check | Expected result | Actual/evidence | Result |
| --- | --- | --- | --- | --- |
| A1 | Staging protection | Authenticated, noindex, email/payment side effects blocked |  | Not run |
| A2 | Correct Revel location | Product belongs to Clarkston |  | Not run |
| A3 | Product status | Active |  | Not run |
| A4 | Online eligibility | Display on online and 3rd party enabled |  | Not run |
| A5 | Unique identifier | SKU/barcode is present and not duplicated in staging |  | Not run |
| A6 | Bulk schedules | Disabled before manual test |  | Not run |
| A7 | Legacy collision | No active legacy WooCommerce product can claim the same identifier |  | Not run |

## B. Product creation and field observation

| # | Field/behavior | Revel before | WooCommerce after | Expected result | Result |
| --- | --- | --- | --- | --- | --- |
| B1 | Product identity |  |  | One product created or intentionally matched; no duplicate | Not run |
| B2 | Name |  |  | Customer-appropriate value or documented cleanup requirement | Not run |
| B3 | SKU/barcode/UPC |  |  | Stable unique identifier retained | Not run |
| B4 | Price |  |  | Matches Revel | Not run |
| B5 | Clarkston inventory |  |  | Matches the selected fulfillment location | Not run |
| B6 | Stock status |  |  | Consistent with inventory/backorder rules | Not run |
| B7 | Operational category |  |  | Transfer/mapping behavior documented | Not run |
| B8 | Brand/manufacturer |  |  | Transfer behavior documented | Not run |
| B9 | Weight |  |  | Transfer behavior and source field documented; no Sold by Weight workaround | Not run |
| B10 | Images |  |  | Transfer/absence/overwrite behavior documented | Not run |
| B11 | Description |  |  | Transfer/absence/overwrite behavior documented | Not run |
| B12 | Variations, if applicable |  |  | Options, identifiers, price, and inventory remain distinct | Not applicable |

## C. Update and overwrite proof

Use a safe, reversible staging test value. Do not change production or customer-facing data.

| # | Action | Expected result | Actual/evidence | Result |
| --- | --- | --- | --- | --- |
| C1 | Change one Revel-owned field and resync | WooCommerce updates once, without duplicate creation |  | Not run |
| C2 | Change a WordPress presentation field and resync | Outcome proves whether Kosmos preserves or overwrites it |  | Not run |
| C3 | Disable online/third-party eligibility and resync | Product visibility/eligibility changes as intended without data loss |  | Not run |
| C4 | Re-enable eligibility and resync | Product returns to the intended staging state |  | Not run |
| C5 | Review Kosmos logs | Action, timing, warnings, and errors are visible |  | Not run |

## D. Inventory and order proof

Run only after product creation/update behavior passes and the staging safeguards are reconfirmed.

| # | Check | Expected result | Actual/evidence | Result |
| --- | --- | --- | --- | --- |
| D1 | Place one staging test order | One WooCommerce order is created with no real payment |  | Not run |
| D2 | Order transfer | The intended order reaches Revel once |  | Not run |
| D3 | Location routing | Order is assigned to Clarkston only |  | Not run |
| D4 | Inventory reduction | Correct quantity is deducted once at Clarkston only |  | Not run |
| D5 | Duplicate protection | Retrying/reviewing does not create a second order or second deduction |  | Not run |
| D6 | Cancellation/refund behavior | Observed behavior is documented; no unsupported promise is made |  | Not run |

## E. Decision record after observation

| Field or behavior | Confirmed owner | Overwritten on sync? | Cleanup rule for remaining 24 | Evidence |
| --- | --- | --- | --- | --- |
| Online eligibility |  |  |  |  |
| Name |  |  |  |  |
| Identifier |  |  |  |  |
| Price |  |  |  |  |
| Inventory |  |  |  |  |
| Weight |  |  |  |  |
| Operational category |  |  |  |  |
| Images |  |  |  |  |
| Descriptions |  |  |  |  |
| Website categories/collections |  |  |  |  |
| SEO and merchandising |  |  |  |  |

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

Final result: Not run / Pass / Conditional pass / Fail  
Approved to synchronize remaining 24: Yes / No  
Reviewer:  
Date:  
Exceptions:

