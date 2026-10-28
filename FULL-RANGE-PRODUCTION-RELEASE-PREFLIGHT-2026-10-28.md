# Full-range catalog production release preflight — 2026-10-28

## Verdict

**Production remains NO-GO.** The local release candidate is now built from the exact deployed v2.1.1 baseline and passes its full local test suite, but no package has been installed or enabled. A whole-package replacement/rollback rehearsal and a real, isolated staging write-path test still must pass after the running Kosmos probe reaches a terminal state.

## Exact active production layout

Authoritative deployment record: `C:\Users\todda\Documents\Codex\2026-10-27\task\RHN-LIVE-CATALOG-PROCESSOR-DEPLOYMENT.md`.

- MU-plugin loader: `public_html/wp-content/mu-plugins/rhn-live-catalog-processor.php`
- Loader target: `public_html/wp-content/mu-plugins/rhn-live-catalog-processor/rhn-live-catalog-processor.php`
- Active package directory: `public_html/wp-content/mu-plugins/rhn-live-catalog-processor/`
- Current production baseline: v2.1.1, one loader plus eight package files.
- Release candidate: one unchanged loader plus twelve package files (the eight baseline files plus four new full-range modules).

The loader remains byte-for-byte unchanged at SHA-256 `B6746C7C05F51D68615E119A9B983898C8E5B08848F4561039096C52FD9D7ACE`.

## Corrected package provenance

The first isolated package accidentally carried an older `google-sheet-discovery.php` from the staging-era package. It lacked current v2.1.1 queue continuation, retry, normalized-heading, and production queue-only behavior. That file was rejected and replaced locally with the exact current v2.1.1 file at SHA-256 `6788A669001335CC10897B73F1930DB654F472070E485A9EF5FE1D3EEDF48B5B`.

The release candidate is now assembled at:

`C:\Users\todda\Documents\Codex\2026-10-27\task-4\full-range-catalog-patch\release-package`

### Complete diff from v2.1.1

- Unchanged: loader, `core.php`, `google-sheet-discovery.php`, `google-sheet-sync.php`, `photo-intake.php`, `registry-import.php`.
- Modified: `catalog-fields.php` (7 additions, 1 deletion), `production-scheduler.php` (9 additions, 3 deletions), `rhn-live-catalog-processor.php` (4 additions).
- New: `full-range-catalog.php` (321 lines), `full-range-catalog-runtime.php` (484 lines), `full-range-catalog-adapters.php` (241 lines), `full-range-catalog-integration.php` (191 lines).
- Net package diff: 1,257 insertions and 4 deletions across seven changed/new files.

The production-only artifact no longer contains the temporary staging preparation page, its admin hook, or its staging context bypass. The temporary four-file staging validator remains a separate, already-removed diagnostic artifact and is not part of the release package.

## Local verification

All 13 release PHP files passed PHP 8.5.12 syntax checks. The complete current test set passed:

- adversarial cycle regression: 14 assertions
- disabled-feature rollback: pass
- source/reconcile adapters: 12 assertions
- full 14,032-row catalog fixture: 16 assertions
- durable runtime/resume/drift/lock guards: 44 assertions
- scheduler integration: 21 assertions, 1,462 callbacks, 14,032 rows, 1,242 terminal actions
- production artifact excludes staging page: 5 assertions
- intrinsic temporary-loader safety: 10 assertions
- staging preparation store: 8 assertions
- staging source planner: 5 assertions
- atomic WordPress store/shards: 1,219 assertions

The existing four-hour hooks are the only scheduler entrypoints. When the full-range engine owns a cycle and is enabled, the existing start, short-job, and continuation callbacks dispatch into it. When disabled, the existing v2.1.1 behavior remains the rollback/default path. No new recurring schedule is added.

## Why the staging v0.8.6 plugin cannot be replaced for testing

The current staging site uses the separate active package `public_html/wp-content/plugins/rhn-catalog-presentation/rhn-catalog-presentation.php`, version 0.8.6, guarded to the Cloudways staging host. The reviewed packages have 60 global function-name collisions. Installing the production package beside it would fatal; replacing it would disable approved staging discovery/firewall/catalog workflows and would not faithfully exercise production rollback. The production host guard must remain production-only.

## Safest bounded staging write-path rehearsal after the Kosmos probe finishes

A faithful test is feasible without replacing v0.8.6 or changing the production host guard, but only as one isolated authenticated request:

1. Install a temporary, uniquely prefixed MU harness on the exact staging host only. It is inert unless a high-entropy one-time POST token is valid.
2. For that single request only, filter the in-memory active-plugin list so v0.8.6 does not load in that request; the database active-plugin option remains unchanged. All normal storefront, REST, probe, and staff requests continue loading v0.8.6.
3. Register one `admin-post` handler. At execution, require exact staging host, HTTPS, `manage_options`, a WordPress nonce, the one-time token, and a completed Kosmos probe. Refuse any other request.
4. Load the exact release package only inside the handler, after WordPress `init` has already fired. This prevents the package's `init` schedule-maintenance callback from running and adds no recurring event.
5. Define the two full-range feature constants only inside this one PHP request. They are process-local, not `wp-config.php`, options, schedules, or persistent global flags. If even request-local constants are disallowed, the guarded production adapter cannot truthfully be exercised and this test must not run.
6. Preflight that `posts`, `postmeta`, `term_relationships`, `term_taxonomy`, and `options` are transactional InnoDB tables. Select only exact, existing staging managed products; do not create a product.
7. Snapshot exact raw product rows, product meta, term relationships, relevant baseline/receipt options, and the affected registry shard. Record hashes and protected price/stock/backorder fields.
8. Start one database transaction, invoke the actual `rhn_full_range_existing_reconcile_adapter(true)` for a bounded approved update and an explicit withdrawal, and verify action receipt, registry-shard persistence, status/content behavior, and unchanged protected operational fields.
9. Roll back the transaction unconditionally in `finally`, delete only affected Redis/object-cache keys, reload from the database, and require exact pre-test hashes for every captured row/option plus exact protected-field equality. Any mismatch is a failed rehearsal and blocks cleanup completion.
10. Delete the one-time token and temporary harness, verify the route is absent, verify v0.8.6 is still active, and verify the regular schedules/firewall hashes are unchanged.

This design exercises the actual production adapter and shard write path while keeping the production host guard unchanged. It avoids co-loading the colliding packages, makes no persistent flag or schedule change, and preserves existing staging workflows outside the single authenticated request. It must not run until the Kosmos probe is terminal because the probe may be writing the same staging catalog.

## Whole-package replacement and rollback rehearsal

After the probe is terminal and before production deployment:

1. Download and hash the complete current production loader/package and active cycle/feature-state metadata.
2. Create a sibling staged directory from the corrected 13-file release candidate; lint and manifest-read it on the server without loading it.
3. Confirm no active short job/continuation lock and both full-range flags absent/off.
4. Atomically rename the current package directory to a timestamped backup and the staged directory to the active path. Do not edit the loader.
5. With flags still off, load normal public/admin requests and prove the v2.1.1 default scheduler, firewall, discovery callbacks, 29-product public state, and Energy 404 are unchanged.
6. Roll back immediately by atomically restoring the original package directory and verify all original hashes and callbacks. This is the required replacement/rollback rehearsal.
7. Only after that rollback passes should the corrected package be reinstalled flags-off for the isolated staging write-path test. Enabling preparation/application remains a separate later gate.

## Remaining approvals and access

- Wait for the running Kosmos event to terminate; do not stop or rerun it.
- Explicit approval is still required to install the temporary request-scoped harness and perform the bounded transactional staging write/rollback test.
- Existing staging SFTP and the already verified `Cody` `manage_options` access are sufficient; no new credential, provider contact, or schedule is needed.
- Production replacement/rollback rehearsal and any production installation require separate explicit authorization after staging evidence is reviewed.
- Applying the full-range flags in production remains unapproved.

## Current Kosmos probe checkpoint

At `2026-10-28T06:00:07Z`, event `6b9495a4-0807-4d00-9928-fdf629da61a3` remained **In progress**, with 900 records prepared, 1,844 report logs, and no visible task exception. Its saved cutoff is `2026-10-09T12:03:00`; the exact eligible source count for that minute is 1,316. The 900-prepared result therefore remains valid evidence of source-side truncation, but the probe is not terminal yet.

## Hardened local staging-write harness — 06:18 UTC update

The request-scoped adapter harness is now implemented locally at:

`C:\Users\todda\Documents\Codex\2026-10-27\task-4\full-range-catalog-patch\staging-write-harness`

Nothing from this directory has been installed remotely. The harness now fails closed unless all of these gates pass:

- exact HTTPS Cloudways staging host, exact `admin-post` action, `manage_options`, WordPress nonce, and one-use token hash;
- one-use filesystem consumption marker created before validation, so failures cannot reuse the same token;
- exact audited 12-file package manifest and whole-package global-symbol collision scan;
- exact Kosmos probe event recorded `Done` within the prior hour;
- no catalog/full-range lock, no active legacy/full-range cycle, and no related due/running cron or Action Scheduler job within ten minutes;
- separately captured and reviewed product-save callback fingerprint;
- all touched WordPress, Woo lookup, option, and Action Scheduler tables present as InnoDB;
- exactly two distinct existing managed simple products: one narrow reversible approved text/meta update and one real explicit withdrawal;
- database transaction active before snapshots, deterministic `FOR UPDATE` locks for target product/meta/terms/lookup/options/shards, two stable raw reads, and two stable Woo reads;
- successful dry plans requiring `planned_update` for both actions;
- outbound WordPress HTTP, email, Woo webhooks, WP-Cron scheduling, and Action Scheduler creation blocked during the write section;
- transaction remains active before and after the actual production adapter calls;
- unconditional rollback in `finally`, precise Redis/object-cache invalidation, two stable post-rollback raw reads, exact raw storage hash equality, and normal Woo API equality for SKU, price, stock, stock status, backorders, status, visibility, and text.

Media, weight, category, brand, and attribute fields are rejected by configuration. Product creation is absent. The test is explicitly adapter-only validation because it calls the internal adapter after the normal `init` phase; it is not scheduler or production-host end-to-end proof.

Local PHP lint passed for all three harness PHP files. `staging-write-harness-hardening-test.php` passed 42 assertions covering nonterminal probe refusal, forbidden field refusal, exact explicit withdrawal structure, global symbol scanning, transaction/lock/rollback surfaces, outbound-effect blocks, no schedule/product/media creation, and required dry-update outcomes.

Current local hashes:

- harness: `5B5982A9D387BEFEF02B40550A0F1B0CDE091E4C5FEA99AEFFEC93A5A0728651`
- config template: `678CC5393DBB23ED0738107E182F8AF14CAC357996DFD332C3506632FA342D5A`
- pure validation library: `C3A5F225D4708A68C5C691F6862A00DC3E3F3AB6D45E4D25E06B220E549120FF`
- hardening test: `EC26001613F13B6484AB3D31684389D0BC31F0EBF6333BBD2746D7AA9E459AF8`

### Concrete remaining feasibility blockers

1. The Kosmos probe is still running. At `2026-10-28T06:18:36Z`, it remained **In progress**, with 900 prepared records and 2,045 report logs; no visible exception was shown.
2. A separate read-only request must capture the exact product-save hook inventory while the colliding v0.8.6 plugin is omitted for that request. Every callback must be reviewed before its SHA-256 fingerprint is placed in the one-time run config.
3. Standard WordPress HTTP, mail, webhook, cron, and Action Scheduler side effects are blocked. Arbitrary third-party callbacks could still use direct sockets or filesystem writes outside those APIs. Complete isolation cannot be claimed until the callback inventory is inspected and confirms no such path. If it cannot be proven, the write rehearsal should not run.
4. Exact representative managed product IDs/SKUs must be selected only after two stable read-only observations and must not be products touched by the probe.
5. The very large Action Scheduler tables are included in the raw rollback fingerprint. Their actual size and a bounded safe fingerprinting cost must be measured read-only before execution; an unbounded memory/time scan is not acceptable.

The harness is therefore locally prepared but remains **NO-GO for staging execution** pending those gates and explicit approval.

## Terminal Kosmos pagination result and read-only staging readiness — 07:20 UTC

The isolated Kosmos evidence task remains preserved and unscheduled. It was not rerun or reconfigured:

- task `27404`, action `27926`, event `6b9495a4-0807-4d00-9928-fdf629da61a3`;
- saved cutoff `2026-10-09T12:03:00Z`;
- exact Clarkston eligible-source count under the same Kosmos filters: **1,316**;
- Kosmos prepared exactly **900** records and then stopped preparing more;
- therefore **416 already-eligible records were not prepared**;
- terminal status **Done** at `2026-10-28T06:50:57Z`.

The Kosmos UI does not expose authoritative terminal destination success/error totals. Its log download produced no download event or file in the in-app browser, so the exact processed/error split remains unavailable. The bounded staging firewall view of the latest 100 destination requests showed 98 successful quarantine creations, one duplicate-create `409`, and one successful price-only update. The duplicate-create and subsequent update are the same identity, so those 100 requests represent 99 source identities, not 100 distinct products.

This proves a pagination/continuation defect **inside the already eligible set**. The 1,316 count already applies establishment 3, `display_online=true`, `active=true`, standard-item `attribute_type=0`, and the configured updated-after cutoff. It does not measure offline, inactive, nonstandard, older-than-cutoff, invalid, or otherwise excluded Clarkston products. Fixing the 900 ceiling therefore remains necessary but is not sufficient for complete 14,028-active-product Clarkston coverage.

The inspected Kosmos Product action/task UI exposes establishment, barcode-to-SKU matching, POS-category behavior, and the updated-after cutoff. It exposes no page-size, offset, continuation, maximum-record, or pagination control. There is no visible self-service field that can raise the 900 ceiling. This is an observation of the current UI only; no configuration was changed.

### Read-only staging readiness evidence

- WooCommerce Action Scheduler reports 8,153 total actions: 7,754 complete, 374 failed, 24 pending, one canceled, and no running/in-progress bucket. Searches for `rhn_` and `catalog` returned no actions, so none of the 24 globally pending actions are identified by the UI as catalog/full-range work.
- Catalog Sheet automation health shows last attempt and last success `2026-10-28 02:31:56`, blank last error, zero consecutive failures, and counts 29 approved / 589 withdrawn / 13,414 skipped. The next regular staging cycle is shown as `2026-10-28T10:30:00Z`. No run was triggered.
- Existing admin diagnostics do **not** expose the current catalog/full-range lock option, lease owner, or cycle token. Absence of an active lock is therefore not proven and the write rehearsal remains blocked on an exact read-only lock check.
- WooCommerce System Status generated `2026-10-28T07:16:44Z` confirms Action Scheduler uses `ActionScheduler_DBStore`; `hzj_actionscheduler_actions` is 4.06 MB data / 4.64 MB index, `hzj_actionscheduler_logs` 2.08 MB / 2.56 MB, `hzj_actionscheduler_claims` and `hzj_actionscheduler_groups` each 0.02 MB / 0.02 MB. Relevant product/option tables shown are InnoDB: `hzj_options` 12.36 MB / 0.17 MB, `hzj_posts` 6.45 MB / 1.02 MB, `hzj_postmeta` 21.50 MB / 6.97 MB, `hzj_term_relationships` 1.52 MB / 0.41 MB, and `hzj_wc_product_meta_lookup` 0.34 MB / 0.83 MB. These sizes support bounded keyed snapshots; they do not justify whole-table fingerprints.
- The fixed 25-product proof identifies managed products and shows their protected SKU/price/stock/backorder fields were restored after the September proof. Two concrete simple-product candidates are Quercetin, product `1479648`, SKU `733739430700`, and L-Theanine, product `1479650`, SKU `788332231308`. Current read-only pages show both as published simple products. They were last edited at 02:30 EDT (`06:30Z`) during the Kosmos probe, so neither is yet cleared as an untouched test target. Each requires two stable post-terminal raw/Woo reads and final overlap checks before any future harness run.
- The current admin pages do not expose the loaded product-save callback inventory or hook priorities. Capturing the exact callback fingerprint would require the already-designed request-scoped read-only introspection step. No helper was installed and no code was executed in this readiness pass.

### Concrete next step and gates

The next safe step is a narrowly scoped, read-only staging preflight that proves no active catalog lock/cycle, captures and reviews the exact product-save callback fingerprint, and performs two stable post-terminal reads of the selected managed simple products. Only after those checks pass should the separately approved transactional adapter-only rehearsal be considered. The rehearsal itself is still not authorized by this read-only pass; production replacement, feature flags, schedules, live data, and any Kosmos rerun remain out of scope.

## Resumed staging-safety work — 13:56 UTC

Todd's later instruction authorizes completion of the safe staging repair validation; the earlier statement that a generic additional approval was still needed is superseded. The rehearsal nevertheless remains unrun because a concrete workspace security control denied the only missing read-only introspection step.

### Exact denied action

- **Target:** `public_html/wp-content/mu-plugins/rhn-full-range-readiness-introspection.php` on the exact Cloudways staging host.
- **Action:** upload the locally linted, exact-host/admin-only temporary read-only helper through the already authorized staging SFTP account.
- **Exact denial reason:** `uploader/persistent MU-plugin install considered unacceptable risk under prior no-helper/no-file-change restriction`.
- **Outcome:** known not executed. Read-only SFTP verification showed the target absent; the MU directory still contained only `0-worker.php` and `blue-nova-staging-bootstrap.php`.
- **Handling:** no retry, alternate uploader, WordPress editor, SSH bypass, or other workaround was attempted.

### Safe work completed after the denial

- Downloaded the currently active staging catalog plugin read-only and confirmed its visible catalog hooks are limited to its REST discovery, scheduled catalog events, firewall filters, and admin/AJAX diagnostic surfaces. This is useful static evidence but cannot prove callbacks dynamically registered by the other active plugins.
- Re-read the Catalog Sheet automation page. Current health is last attempt/success `2026-10-28 06:31:21` (site time), no error, zero consecutive failures, counts 29 approved / 589 withdrawn / 13,414 skipped, and next scheduled `2026-10-28 10:30:00 EDT`. No run was triggered.
- Re-read the two existing managed simple-product candidates. Quercetin `1479648` / SKU `733739430700` remains published, simple, price 34, stock 6; L-Theanine `1479650` / SKU `788332231308` remains published, simple, price 20.5, stock 5. Both now show last edited `October 28, 2026 at 6:30 am`, consistent with the regular catalog cycle rather than the completed Kosmos probe. L-Theanine was stable across an immediate page reload. This confirms candidates can be used only inside a preflight window that also proves the catalog cycle is idle; they are not permanently untouched fixtures.

### Rollback harness corrections

The local rollback harness was hardened further and remains uninstalled:

- Removed the complete-history scans of all Action Scheduler action and log rows.
- Replaced them with global count/max-ID markers plus at most 100 related catalog action rows and 200 related log rows.
- Added bounded count/max-ID markers for Action Scheduler claims and groups.
- Added a fail-closed product-callback corridor. Before any transaction, every callback on the relevant product/meta/term hooks must resolve to WordPress core, WooCommerce, the signed harness, or the exact audited release package. The corridor is checked again after the signed release loads so newly registered release callbacks are also constrained. Any unknown or third-party callback blocks the rehearsal before writes.
- Retained the separately reviewed callback fingerprint requirement, exact host/admin/nonce/one-use-token gates, transaction/row locks, dry plan, outbound HTTP/email/webhook/scheduler blocks, unconditional rollback, raw database hashes, precise cache invalidation, and normal Woo readback.

Updated local hashes:

- harness: `93CC424B0E8E3EB3012FFA1B0CCC5005470416CA2A6E03F28CEE3CF12449E612`
- hardening test: `36B4894B85F5652F5B1DE9F6EC48E3AC8FE057E4DCE7623B6541B33E73FC9854`

All current local suites pass: 14 adversarial-cycle assertions, disabled rollback, 12 adapter assertions, 16 full-catalog assertions, 44 runtime assertions, 21 scheduler assertions across 1,462 callbacks / 14,032 rows / 1,242 terminal actions, 5 production-artifact assertions, 10 intrinsic-loader assertions, 8 preparation-store assertions, 5 source-planner assertions, **55 staging-harness assertions**, and 1,219 atomic-store/shard assertions.

### Current release decision

- **Local full-range engine:** passes the full local suite.
- **Transactional staging adapter rehearsal:** **BLOCKED / not run** because the exact dynamic callback fingerprint and lock/cycle state could not be collected after the helper upload denial. Running without that evidence would defeat the safety controls.
- **Production deployment:** **NO-GO** until the staging rehearsal and independent review pass. Production remains unchanged.

This catalog processor work is downstream capacity and publication control. It does not solve upstream completeness by creating thousands of hidden Woo products. The private Inventory Sheet should retain all 14,028 active Clarkston identities; Woo should receive only specifically approved website products while the established price/stock path remains operational. No historical bulk Woo-shell creation was performed or proposed as success criteria.

## Shutdown readiness — 14:00 UTC

- All local package, test, and report artifacts are saved under `C:\Users\todda\Documents\Codex\2026-10-27\task-4`.
- No active Git, SFTP, SSH, PHP, Node, or Python transfer/test process was visible. The only PowerShell process observed was the short-lived read-only inventory command itself.
- No upload, deployment, harness execution, or remote test is in progress. The Kosmos pagination probe is already terminal and server-side schedules do not depend on the desktop.
- Remote MU-plugin readback after the denied upload showed only the pre-existing `0-worker.php` and `blue-nova-staging-bootstrap.php`; the temporary introspection helper is absent.
- The denied action was upload of local `full-range-catalog-patch\staging-readiness-introspection.php` to staging `public_html/wp-content/mu-plugins/rhn-full-range-readiness-introspection.php`. Original reviewer text recorded for the denial: `uploader/persistent MU-plugin install considered unacceptable risk under prior no-helper/no-file-change restriction`. It was known not executed and was not retried.
- Allowed next step is not a workaround: either a security-approved, exact-target, temporary staging upload with mandatory removal/readback, or a pre-existing authenticated read-only diagnostic surface that exposes the same lock/cycle/callback data. The existing denial must not be bypassed.

The computer is ready to restart. Browser login sessions may be lost, but no unsaved or in-flight operation depends on them. Do not delete the Documents/Codex task folder while clearing caches.
