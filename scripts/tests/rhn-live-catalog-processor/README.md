# Full-range catalog repair preparation

Status: corrected local integration scaffold; **not deployment-ready**. Nothing in this directory has been deployed, loaded by WordPress, connected to Google, or used to change a product or spreadsheet.

`full-range-catalog.patch` is the loader diff for `full-range-catalog.php`, `full-range-catalog-runtime.php`, and `full-range-catalog-adapters.php`. Loading these files changes no behavior: they register no hooks or schedules, and their gated entry points refuse to run unless `RHN_FULL_RANGE_CATALOG_ENABLED` is explicitly defined as boolean `true`. Product application has a second gate, `RHN_FULL_RANGE_CATALOG_APPLY_ENABLED`. Both constants are absent from the package, so reading and applying are disabled by default.

## What the current code actually limits

- Production reads `Catalog!A:W` through the Google Sheets JSON values API. The 5,000-data-row limit in `rhn_catalog_csv_rows()` is not on that production path.
- Staging still uses the CSV parser only as a public-sheet fallback when private Google credentials are absent. Its 5,000-row / 8 MiB guard therefore belongs to that staging-only fallback.
- Both production and staging collect every Approved or Approved for Test row and then call `rhn_catalog_parse_registry()` once. That strict parser intentionally rejects more than 1,000 approved records or more than 2 MiB of JSON. This is the active full-range approval limit.
- The production cycle refetches and reparses the entire Catalog tab and queries WooCommerce for every actionable SKU before bounding each write job to one product. The write transaction is bounded, but planning work is not cursor-based.

The corrected helper preserves the strict parser guards per chunk. It requires exact row provenance/accounting, binds each immutable chunk hash to the canonical complete parsed payload (including all client fields) and a hash of every raw bounded source row, refuses incomplete or version-drifted scans, detects duplicate actionable SKUs across pages, preserves exact text SKUs, and treats only explicit `Remove from Website` rows as withdrawals.

The runtime adds an injectable bounded reader, durable versioned chunks, compare-and-swap manifests, a single-owner lock, and a one-action cursor with deterministic action IDs and verified receipts. Chunk and manifest options have a 4 MiB fail-closed ceiling and use non-autoloaded WordPress options. The account adapter uses the existing Sheet identity/token helper, Drive metadata version, Sheets grid metadata, bounded `Catalog` value ranges, and action-time exact-row verification. The reconcile adapter supplies one action at a time to the existing guarded `rhn_live_catalog_reconcile()` path. Both remain deliberately unconnected from hooks/schedules. The included WordPress option adapter is a candidate storage adapter, not production approval.

## Test evidence

`tests/full-range-catalog-test.php` retains the original 16 helper assertions. `tests/full-range-runtime-test.php` adds 44 assertions using the current production Sheet parser and a durable temporary disk store:

- 1,205 approved records, proving the current monolithic 1,000-record failure and guarded chunk success.
- A payload above 2 MiB, proving the existing limit still fails closed per chunk.
- 37 explicit withdrawals and 12,790 skipped/review-only rows, proving missing/inactive/unapproved rows do not become removals.
- 29 pages at 500 rows, process recreation during durable resume, deterministic replay, source-version drift refusal, active-worker lock refusal, and leading-zero preservation.
- Exact preservation and hashing of every supported client field through the real Sheet parser.
- Refusal of under-accounted pages, short bounded reads, incomplete finalization, unverified actions, and source drift before action.
- Durable one-SKU action cursor progression; an unverified result leaves the cursor unchanged.

`tests/full-range-adapters-test.php` adds 12 assertions with mocked Google transport and Woo product state while invoking the actual production Sheet parser and reconciliation planner:

- Small-fixture per-action outcomes exactly match the current full-sheet plan.
- Only explicit `Remove from Website` rows become removal actions.
- Deterministic verified receipts prevent a simulated retry from applying twice.
- An exact-row staff edit and a Drive-version change both refuse action.
- A full 14,032-row / 1,205-approved / 37-withdrawal Google fixture prepares in ranges of at most 500 rows; the old monolithic parser still refuses it.

Run with the portable project PHP:

```powershell
& 'C:\Users\todda\Blue Nova Projects\Rebekahs Health Website\.tools\php-8.5.11\php.exe' '.\full-range-catalog-patch\tests\full-range-catalog-test.php' 'C:\Users\todda\Documents\Codex\2026-10-27\task\rhn-live-catalog-processor'
& 'C:\Users\todda\Blue Nova Projects\Rebekahs Health Website\.tools\php-8.5.11\php.exe' '.\full-range-catalog-patch\tests\full-range-runtime-test.php' 'C:\Users\todda\Documents\Codex\2026-10-27\task\rhn-live-catalog-processor'
& 'C:\Users\todda\Blue Nova Projects\Rebekahs Health Website\.tools\php-8.5.11\php.exe' '.\full-range-catalog-patch\tests\full-range-adapters-test.php' 'C:\Users\todda\Documents\Codex\2026-10-27\task\rhn-live-catalog-processor'
```

## Production integration sequence

The reader/store/cursor sequence and account-shaped Google/Woo adapters are implemented and tested locally with injected transports/state, but they are intentionally not wired to a hook, schedule, or live environment.

1. Read Drive metadata version before the scan.
2. Read bounded `Catalog` ranges in pages no larger than 500 rows. Persist each parsed chunk separately and verify it by readback; do not merge a large registry into the existing single option.
3. Re-read Drive metadata after the last page. If the version changed, discard the incomplete prepared snapshot and restart. Do not mutate WooCommerce from a mixed-version snapshot.
4. Finalize only a complete manifest with the declared row count, contiguous page chain, unique actionable SKUs, and existing parser validation on every approved chunk.
5. Process the finalized snapshot with a separate action cursor. Keep the existing per-SKU publication gates, transaction receipt, rollback, exclusion, and one-product write bound.
6. Before each mutation, re-read the exact SKU from the current Sheet or verify the finalized source version has not changed. Preserve the current action-time staff decision.
7. Treat missing, inactive, or source-absent identities as review findings. Only an explicit `Remove from Website` Sheet status may create a withdrawal action.
8. Keep previous chunk set and manifest until the new run completes. Rollback is selecting the previous verified manifest; product rollback remains the current per-product transaction path.

Before enablement, live-validate that the existing service-account scope returns Drive `version`/`modifiedTime`, confirm bounded row discovery against the actual Catalog grid, and test action-time exact-row reads. Then review the WordPress storage adapter against the target database/object cache, run staging no-write reconciliation against actual Woo state, and obtain separate production deployment authorization. Local mocked tests are not live verification.

The action callback must be idempotent for its deterministic action ID. A crash after a remote product mutation but before receipt persistence can cause that same action ID to be retried; the existing exact-SKU reconciliation should verify the resulting state rather than blindly repeating a mutation.

## Workbook evidence and separate changes

Read-only Google metadata on 2026-10-28 showed:

- `Product Review` grid: 14,037 rows. Representative category cells at rows 14,035-14,037 retain strict list validation.
- `Catalog` grid: only 3,047 rows. Its last formula row maps to Product Review row 3,051. Rows after that are absent, not merely blank formulas.
- Representative Product Review status/publication cells at rows 274-276, 3,049-3,051, and 14,036-14,037 have values but no ordinary cell-level validation. Native table column behavior is separate from `CellData.dataValidation`.
- Prior native-structure inspection established that `ClientProductReviewTable` and the barcode/name protected range end at row 275. Extending the table conflicts with redundant banding object `62121477`; that exact atomic formatting/table change was denied and was not retried.
- Vendor helper write `Sheet1!A3012:B14033` was denied by the workspace risk check. The existing 3,010 partial rows are isolated and unused. It must not be retried as part of this processor repair.

These are separate, scoped future operations:

1. Extend Catalog formulas from row 3,048 through the Product Review data range, after a fresh row-count read and formula-pattern test.
2. With explicit approval, atomically replace redundant banding `62121477`, extend the native table/protected barcode-name ranges, and verify the same visible alternating style.
3. Decide whether status/publication controls should be native table controls or explicit cell validation, then test at first, middle, and last rows.
4. Leave the denied vendor helper expansion out unless separately reauthorized with its exact hidden-data scope.

