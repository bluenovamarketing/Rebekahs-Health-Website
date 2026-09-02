# Rebekah’s Phase Two Ecommerce — Legacy Catalog Clean-Start Plan

Version: v1.1  
Prepared: 2026-09-02  
Status: Local plan complete; staging execution not authorized

## Decision

Treat the Revel-selected 25-product pilot as a clean catalog start. Do not blindly map the approximately 35 visible / 36 recorded legacy WooCommerce products to the new pilot. Preserve the old records for reference, prevent collisions, and decide item by item only after the first synchronization shows the real identifiers and behavior.

## Safety rules

- Perform this work only on protected staging.
- Export and record the legacy catalog before changing status or identifiers.
- Quarantine; do not permanently delete.
- Do not edit production product records.
- Do not let a legacy product remain purchasable, indexed, or discoverable during the pilot.
- Do not reuse or fabricate SKUs to force a match.
- Do not merge products until the identifier, data owner, and sync behavior are proven.

## Staging procedure

1. Export all legacy products with ID, status, type, name, slug, SKU, price, stock, category, image references, and modified date.
2. Record the visible product count and database/export count; investigate the known 35/36 discrepancy.
3. Add a staging-only audit classification to each legacy product:
   - `Legacy — quarantine`
   - `Possible pilot match`
   - `Reference only`
   - `System/test artifact`
4. Set all legacy products to non-public status on staging before connecting Kosmos.
5. Remove legacy products from menus, featured areas, related-product rules, search results, feeds, and sitemaps on staging.
6. Check for duplicate SKUs/barcodes, blank SKUs, shared slugs, orphan variations, and product IDs referenced by old shortcodes or page builders.
7. Run the one-product test with no active legacy product capable of claiming its identifier.
8. After the proof, compare the imported Revel identifier against the quarantined export.
9. Preserve imported Revel/Kosmos records as the operational pilot records.
10. Copy only approved website presentation material from a legacy record when it is accurate, permitted, and protected from later sync overwrite.

## Match classifications after the first pull

| Classification | Condition | Action |
| --- | --- | --- |
| No match | No legacy record shares the verified identifier | Keep the new synchronized product; legacy record remains quarantined |
| Exact operational match | Verified unique identifier and product/variation identity match | Decide whether to preserve the synchronized record or intentionally map according to Kosmos behavior; document the choice |
| Name-only similarity | Similar name but identifier missing or different | Treat as no match until Rebekah/Mark confirms; never merge automatically |
| Duplicate identifier | Multiple legacy records or variations share the identifier | Stop that product; correct the collision before syncing it |
| Legacy content source | Old record has useful approved images/copy but is not the operational record | Copy only permitted presentation fields after overwrite behavior is known |
| Obsolete/test item | Record is clearly old, test, or unsupported | Keep quarantined through acceptance; archive/delete only under a separately approved cleanup step |

## Required reconciliation outputs

- Legacy export retained outside the public web root.
- 35/36 discrepancy explained.
- Duplicate and blank identifier report.
- List of old product URLs that could require redirects if previously public/indexed.
- Final 25 synchronized product IDs, identifiers, slugs, and statuses.
- Exception list showing every legacy content reuse decision.
- Written confirmation that no legacy product remains accidentally purchasable.

## Rollback

If the connection attaches to, overwrites, or duplicates an unexpected legacy record:

1. Stop Kosmos schedules and the affected action.
2. Preserve the Kosmos and WooCommerce logs.
3. Do not manually “fix” multiple records before capturing evidence.
4. Restore the staging checkpoint or reimport the legacy export if necessary.
5. Correct the collision rule using the observed identifier behavior.
6. Repeat only the single-product test.

