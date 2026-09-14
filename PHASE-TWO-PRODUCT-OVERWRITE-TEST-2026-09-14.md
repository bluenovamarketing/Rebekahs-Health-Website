# Product overwrite test — September 14, 2026

Status: DONE, final report reconciled. 245 distinct existing products updated, zero unexpected HTTP errors. Completed 16:52:29 EDT; approximately 80 minutes 27 seconds from initial running observation at 15:32:02. Final dashboard reports 988 entries; downloaded JSON contains 984 entries (do not equate report counters with products). Target PUT 200 sequence 479 confirmed. Title and long-description markers overwritten; short-description marker preserved. Original target fields restored and verified by reloading the editor.

## Follow-on staging-only stock simulation baseline

Final frontend restock readback: fresh canonical product tab 35 displayed 2 in stock and Add to cart present. Combined with earlier zero-state Out of stock/no Add to cart and persisted admin 2, the Woo-only product-page zero/restock display test is complete. No cart submission or Revel zero/restock transport test is claimed. Duplicate in-stock text appears twice in the page DOM and is a separate presentation QA item, not an inventory mismatch.

Subsequent recovery supersedes the uncertainty below: editor tab 34 showed persisted 0; refreshed canonical product tab 32 rendered Out of stock with no single_add_to_cart_button. Restored quantity 2 and reloaded editor; DOM readback confirms 2, managed stock true, backorders no. Zero-state product-page behavior is observed; admin restoration verified. Frontend after restoration and actual cart/server-side rejection remain pending. This is a Woo-only simulation, not proof of zero/restock transport from Revel. Do NOT restart the zero simulation merely to repeat completed observations.

Latest continuation: fresh in-app editor confirmed original quantity 2, stock management enabled, backorders disallowed. Woo displayed a concurrent/stale-edit warning explicitly reporting 2 units; prior restoration is therefore verified in admin. A new staging-only zero simulation was then attempted; setValue/click/observation ended with target closed. Fresh public tab 33 and editor tab 34 also timed out. Current saved quantity after that attempt is UNKNOWN. Inspect/restore 2 before any further writes; do not keep repeating zero simulations until readback is reliable. Public sold-out/restock behavior remains unverified. No new connector replay was launched.

Product 1479193, barcode 090900000019, HERPHA SMOKER ASSIST 10Z. Before simulation: manage stock enabled, quantity 2, backorders no, stock status instock, regular price 19. Permalink https://wordpress-1651482-6655800.cloudwaysapps.com/product/herpha-smoker-assist-10z/. Temporarily test quantity zero then restore 2; no Revel edits. This verifies Woo purchase behavior, not actual zero-stock transport from Revel. Simulation results pending.

URGENT NEXT ACTION: zero was entered into staging quantity and Update clicked. Browser control then failed (target/dispatch/focus timeouts), including a fresh recovery tab. Saved zero state could NOT be verified, nor could restoration be completed. Product MAY currently have quantity zero. Before any additional test, regain in-app access, inspect product 1479193 and restore original quantity 2, manage stock enabled, backorders no, stock status instock; verify saved state and canonical product page. No live/Revel inventory was edited. Do not mark zero/restock acceptance passed. Independent public HTTP read also failed to connect from the tool environment; this is not proof that production is down.

## Scope and safety

- Todd authorized the one-way Revel-to-staging WooCommerce overwrite test, including reprocessing the existing 245-product population.
- No production Revel data changes or Woo-to-Revel writebacks authorized or performed. No automatic schedule enabled.
- Existing Action 27772: Clarkston staging - Revel products to WooCommerce - manual pilot. Establishment 3 (Clarkston); barcode matching; Use POS category; configured date 2026-09-01T12:05:00 (activity UI displays September 1 at 08:05 EDT).

## Saved Woo-only test fields

Woo product 1479181; Revel product 18872; SKU/barcode 090900000026.

| Field | Original | Test value saved before run |
| --- | --- | --- |
| Title | HRBPHRM NERVOUS SYSTEM 1OZ | HRBPHRM NERVOUS SYSTEM 1OZ — Staging sync test |
| Long description | 1 fl oz | Original text followed by a blank line and: Staging synchronization test: WooCommerce description preservation marker RHN-20260914. |
| Short description | Empty | Staging synchronization test: WooCommerce short-description preservation marker RHN-20260914. |

Original price $19. No deliberate stock, price, category, image, slug or Revel edit. Staging permalink: https://wordpress-1651482-6655800.cloudwaysapps.com/product/hrbphrm-nervous-system-1oz/

## Execution evidence

1. Existing task 27283 rerun completed at 15:29:33 EDT with ZERO records, event f1ee9eaf-02e3-45b4-970f-c2cefd70dec8. It used its persisted incremental timestamp September 14 07:54:50 EDT, despite the Action displaying the original September 1 boundary. This is not an overwrite-test result.
2. Created a separate unscheduled task using the same single Product Action: `Clarkston staging product overwrite test - 2026-09-14`, task 27285. Original task retained; no concurrent original task was running.
3. Confirmed new task **In progress**, 245 selected products, 8 log entries initially, last-sync display September 14 15:32:02 EDT. Event ID: `0fc5f75f-367f-4da4-a8ca-6868b24ec5e4`. Boundary displayed September 1 08:05 EDT.

## Next session

### Recovery update

Reopened staging independently after Todd's correction. Fresh editor confirmed quantity 0, managed stock true, backorders no, price 19. Entered quantity 2 and clicked Update. Subsequent reload, fresh public-product tab, and direct read-only endpoint checks could not complete reliably. Restoration was submitted but is NOT yet verified persisted. Next action remains read back product 1479193; if still zero, restore 2 and verify. Do not repeat writes blindly, ask Todd to perform ordinary browser reopening, or claim zero/restock test passed.

### Interim evidence at 16:17 EDT

- Task remains In progress at 16:17:34, about 45 minutes 32 seconds after initial running timestamp; 657 log entries. Not a final duration.
- Refreshed staging product 1479181: title reset to `HRBPHRM NERVOUS SYSTEM 1OZ`; long description reset to `1 fl oz`; short-description marker remains (HTML paragraph wrapper added/present). Title and long-description preservation fails for this sample; short description currently survives. Final batch reconciliation pending.
- Downloaded partial log: 384 GET 200, 136 POST 400, 135 PUT 200. Sample POST 400 entries explicitly say `Item already exists. Preparing to update item`; these are update-path responses, not evidence of website downtime. More requests per existing-product update help explain why replay can take longer than initial creation. No 503 appears among this partial log's method counts; previous inventory 503 errors remain a separate unresolved issue.

- Check task 27285 activity and full final log; verify target SKU was processed and inspect request failures even if dashboard says Done.
- Compare all three fields against saved markers; document actual preservation/overwrite behavior, including blank versus populated source fields.
- Restore the target's original title, long description and empty short description after comparison; verify saved result. Cleanup is pending.
- Update acceptance test C2 and canonical data map. Keep this replay task unscheduled; do not rerun it reflexively.
- Cloud task continues while laptop sleeps; local checks resume only when the computer is awake. No background monitoring promised.

Reference for task-owned timestamp and cloud execution: https://help.kosmosesync.com/index.php/knowledge-base/kosmos-esync-bulk-migrations/
