# Staging catalog presentation rule — implementation candidate

Status: installed and active on staging only (version 0.2.1). PHP syntax, 22 isolated policy checks and 12 intake checks PASS. Controlled internal WooCommerce wc/v1 update: eight assertions PASS and original product fields/metadata restored with readback. Bulk-intake UI rejection, valid preview, persistence and registry rollback also verified. No real approved-copy registry populated; automated content sourcing is not implemented.

## September 14 combined staging proof

One-shot admin test at Tools → Controlled staging API proof uses fixed Woo 1479193 / SKU 090900000019. A durable baseline option prevents repeated execution and enables recovery. It temporarily supplies synthetic copy through a request-local registry filter, dispatches a real internal PUT through `/wc/v1/products/1479193`, checks persisted values, removes the filter and restores the baseline in `finally`. No external HTTP, Revel or credentials are involved. This tests WordPress routing/controller/hooks/persistence, NOT Kosmos's network transport, scheduling or API-key authentication.

Passed: HTTP 200; title; sanitized long description; short description; exact barcode; incoming price 20; incoming stock 1 with management/backorders rules; raw incoming description retained separately. Restoration verified original fields and metadata. Fresh canonical frontend verified original HERPHA SMOKER ASSIST 10Z, $19, 2 in stock and Add to cart; no synthetic copy visible.

Bulk intake at Tools → Staging catalog copy intake rejected `stock_quantity`, previewed one synthetic test entry, saved/read back its registry, then restored/read back the previous registry. Synthetic entry is no longer active. No product was created by intake. Authentication/nonce denial paths have not been adversarially tested. Approved source material is still required; a source string is not proof of content accuracy or license.

Latest packages v0.2.0 and v0.2.1 retain the installed directory identity below. v0.2.1 includes the intake and one-shot testing/recovery modules. Do not run the test again: its baseline/report are retained and repeated execution is blocked.

Deployment verified in WordPress Plugins: RHN Catalog Presentation (Staging), version 0.1.0, Deactivate control present. Uploaded artifact `../rhn-catalog-presentation-v0.1.0.zip`, SHA-256 `1F19485B9D4C036122108E85A71A212F7705E931CF0A6E91D5AF31ED3B7F1FC3`. WordPress installed it at `rhn-catalog-presentation-v0.1.0/rhn-catalog-presentation.php`. Keep that installed directory identity for future upgrades to avoid a duplicate active plugin. Only the plugin PHP file was packaged; no test fixtures, credentials, or product records were uploaded. Rollback is Deactivate; no activation migration or automatic product writes exist.

Executed `tests/policy-test.php`: all 22 combined checks passed, including supported/unsupported routes, whitespace-only source rejection, and preservation of incoming changed price and zero stock. Plugin PHP lint also passed. Used a temporary portable PHP 8.2 runtime from the official php.net Windows release manifest, verified against its SHA-256 before execution; no server or system configuration changed. Earlier runtime-access blocker is resolved. WordPress stubs test branching/field isolation, not real WooCommerce persistence or HTML sanitization. Actual staging REST integration still must pass before this is called deployed or automated.

Purpose: after an authoritative catalog source supplies approved copy in bulk, automatically reapply that copy on matching WooCommerce product API writes. Do not freeze raw imported names/descriptions globally or require staff to repair each sync manually.

The private WordPress option `rhn_catalog_presentation_overrides` is an associative array keyed by exact barcode strings (preserve leading zeroes). Each entry requires `approved => true` and a nonempty `source` reference, plus any approved `name`, `description`, `short_description`. No catalog data or test markers are bundled.

`registry-import.php` is included and deployed. Twelve isolated registry checks and PHP lint passed. It accepts a list of records containing exact string `sku`, boolean `approved`, source reference and at least one of the three copy fields. Unknown/operational fields, duplicates and missing provenance fail. Apply merges by SKU, saves the prior registry, verifies persistence, and does not edit product records. Restore previous registry is available and was tested. Source references are provenance assertions, not automatically verified source content.

Safety: exact staging-home-host check; no network calls; no Revel access; no price, SKU, category, publication, inventory, order or payment changes. Unknown or unapproved barcodes pass through. Empty approved descriptions are permitted; empty titles are ignored. Imported presentation fields are retained in private product metadata. Default behavior is inert without a registry. Both wc/v1 and v2/v3 hooks are registered because the actual Kosmos log uses wc/v1.

Deployment gates:

1. Finish and reconcile the current unmodified connector replay first.
2. PHP lint and isolated tests with the actual WooCommerce version; verify raw source metadata, exact barcode identity, HTML sanitization, unapproved/no-match passthrough, intentional empty descriptions, production no-op, and continued price/stock updates.
3. Validate source references and bulk-populate only a controlled staging test record. Do not invent approved customer copy from empty fields.
4. Prove a single staging product API update using the observed wc/v1 route and compare fields before/after. No need to reprocess 245 products to test this hook itself.
5. Test batch routes, variations, source SKU changes and admin/bulk-authoring workflows separately before expanding support. These are not claimed supported by this candidate.
6. Implement/verify the reviewed bulk importer and provenance/approval update process before calling the content pipeline automated.

Rollback: deactivate this standalone plugin; no operational data requires restoration. Existing approved presentation values remain until another edit/sync. Restore from recorded test baseline if needed. No automatic rollback or deletions.

Hook reference: https://woocommerce.github.io/code-reference/files/woocommerce-includes-rest-api-controllers-version1-class-wc-rest-products-v1-controller.html
