# Corrective review diff — 2026-10-28

Local-only scope. No production/staging plugin, Sheet, product, schedule, credential, or provider state was changed.

## Safety corrections

| Area | Before review | Corrected local scaffold |
|---|---|---|
| Page completeness | Rejected only `accounted > row_count`; missing rows could finalize | Requires exact count, unique integer row provenance, and full bounded-row coverage |
| Content integrity | Chunk hash covered SKU/action summaries, not client fields | Payload hash covers complete parsed records plus canonical hashes of every raw source row |
| Durable resume | Manifest only; no chunk or cursor store | Immutable versioned chunks, readback verification, compare-and-swap manifest, lock, action cursor |
| Concurrency | Caller-supplied state only | Single-owner expiring lock and CAS updates; active second worker is refused |
| Action safety | No cursor or action-time guard | One action per invocation, current-source verification, deterministic action ID, verified receipt before cursor advance |
| Enablement | Helper loader only | Both runtime entry points refuse production use unless `RHN_FULL_RANGE_CATALOG_ENABLED === true`; no hooks or schedules are registered |

## Files

- `full-range-catalog.php`: schema 2 manifest, exact provenance/accounting, complete payload/source hashes, payload validation, final accounting.
- `full-range-catalog-runtime.php`: disabled runtime, bounded source adapter contract, WordPress option-store candidate, durable preparation and action cursor.
- `full-range-catalog-adapters.php`: account-shaped read-only Drive/Sheets source and existing-reconcile bridge, both disabled and transport-injectable.
- `full-range-catalog.patch`: exact inert loader diff for all three files.
- `tests/full-range-catalog-test.php`: retained and adjusted 16 strict-helper assertions.
- `tests/full-range-runtime-test.php`: 44 real-parser/durable-store assertions.
- `tests/full-range-adapters-test.php`: 12 mocked-account assertions invoking the actual production parser and reconcile planner.
- `README.md` and `../FULL-RANGE-CATALOG-LIMITS-REPAIR-REPORT-2026-10-28.md`: status corrected to not deployment-ready and remaining live adapters listed explicitly.

## Verification

- PHP 8.5.11 lint: all helper/runtime/test files pass.
- Original suite: `PASS: 16 assertions; 14,032-row fixture, 1,205 approved rows, 37 explicit withdrawals.`
- Runtime suite: `PASS: 44 runtime assertions; durable 14,032-row real-parser snapshot, field hashes, resume, drift, lock, and cursor guards.`
- Adapter suite: `PASS: 12 adapter assertions; mocked Google version/ranges and actual parser/reconcile planning with no external writes.`
- `git apply --check full-range-catalog.patch` passed against an isolated copy of the reviewed v2.1.1 loader.

## Still required before any deployment

The account-specific Google and Woo adapters are implemented locally but not connected to any hook, schedule, or live environment. They still need live read-only scope/version/range validation, target WordPress storage review, staging no-write evidence with actual Woo state, and independent deployment review. Upstream unattended Clarkston source completeness remains unresolved.

## Read-only target evidence

- Exact staging identity returned HTTP 200 and the matching WordPress REST home/name; staging responses remain noindex/nofollow.
- Cloudways account is SFTP-only (`Shell access is disabled !`), so no server-side read-only PHP/WP-CLI execution channel is available without uploading a helper.
- RHN Catalog Presentation (Staging) v0.8.6, RHN Catalog Secure Connector v0.2.0, Object Cache Pro drop-in v1.25.5, and the `object-cache-pro` directory are present.
- Installed staging token code requests Sheets plus Drive read-only scope; actual token issuance/Drive metadata response is still unverified.
- Public fallback plus the installed parser verified the current Catalog extent as 3,046 data rows: 29 Approved, 589 Remove from Website, and 2,428 Not Approved. This is not a 14,032-row live Catalog.
- No target file, option, product, schedule, credential, or external record was changed.

## Admin-page precision and diagnostic status

- `staging-readonly-diagnostic.php` is a local-only, inert source artifact. It was not uploaded or executed.
- The denied request used `tools.php?page=rhn-kosmos-firewall`. Deployed code registers `add_management_page(..., 'manage_woocommerce', 'rhn-kosmos-operational-firewall', ...)`, so the registered route is `tools.php?page=rhn-kosmos-operational-firewall`.
- The wrong-slug denial does not prove that the saved user lacks `manage_woocommerce`, `manage_options`, or an administrator role. No permitted authenticated staging Profile view is available in this task, so role/capability remains unverified.
- No remote helper exists and no cleanup is pending. Service-account Drive metadata/bounded reads, effective object-cache runtime state, and the actual Woo no-write plan remain known not executed.

