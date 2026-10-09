# RHN Catalog Presentation (Staging)

Current local candidate: **0.8.4**. The installed staging version must be verified after upload; do not infer deployment from this repository.

Version 0.8.1 makes repeated Sheet withdrawals idempotent: products already marked by the Sheet as Draft/Hidden are verified and skipped instead of being saved again. This keeps the expanded Clarkston catalog within the staging sync window while preserving the same final website state.

Version 0.8.2 treats WooCommerce variations correctly during withdrawal verification. A variation is held offline by its Draft status and inherits catalog visibility from its parent; the verifier no longer expects the unsupported standalone `hidden` visibility value from variation rows.

Version 0.8.3 extends the exact-SKU identity lookup to both WooCommerce product and product-variation records. The exact-one-match safeguard remains in place, so a duplicate SKU still fails closed.

Version 0.8.4 keeps the independent four-hour Sheet catalog writer away from Kosmos's 00:00/12:00 UTC inventory windows. A legacy catalog event inside the 15-minute collision window is cleared and re-anchored to the next 02:30/06:30/10:30 UTC slot; safe existing schedules are preserved.

This plugin keeps Kosmos responsible for POS-owned commerce data and gives Blue Nova a controlled source for website-owned catalog data.

## Ownership boundary

Kosmos/Revel may continue updating:

- exact SKU/barcode identity;
- price;
- stock quantity/status;
- other operational POS fields already proven in the existing connector.

The approved catalog registry may apply:

- customer-facing product name;
- long and short descriptions;
- existing website brand and category terms;
- packaged shipping weight with unit conversion;
- featured and gallery images from approved Google Drive or Dropbox sources;
- ingredients, allergens, Supplement Facts, directions, and warnings;
- SEOPress title and description.

The plugin never writes to Revel. It does not create terms or change SKU, price, stock, orders, customers, payments or refunds. On the exact guarded staging installation only, the hidden export maps the client's `Information Status = Complete` plus `Website Action = Publish` choice to `Approved`, which changes the matching product to Published/visible. No separate Blue Nova approval is required. The client's explicit `Remove from Website` action changes it to Draft/hidden; it never deletes a product. A later completed Publish choice restores Published/visible.

## Google Sheet intake

`google-sheet-sync.php` reads the `Catalog!A:W` range through a Google service identity. The identity requests Sheets edit scope only because `google-sheet-discovery.php` must append newly discovered Clarkston Revel products; Drive remains read-only. The client-facing Sheet calculates the hidden export status: `Complete + Publish` becomes `Approved`; `Remove from Website` remains the explicit withdrawal instruction; all other combinations remain `Not Approved`. Approved rows update website-owned fields and become Published/visible on guarded staging. Ordinary Working or undecided rows do not alter an existing product. Preview validates without writing. Apply updates the private registry and the matching guarded staging products. When an approved row has no separate `Source / Evidence` entry, the derived registry records the exact client-owned `Catalog` row as its provenance; this does not edit the Sheet or approve an unfinished row.

`google-sheet-discovery.php` does not rebuild or replace the client Sheet. It preserves all existing rows and cells, accepts only exact SKU/barcode and source-name identities observed on the guarded staging WooCommerce product API, and appends missing products at the bottom of `Product Review`. Newly discovered products begin as `Working + Keep Off Website`, so they remain unavailable until the client explicitly changes them to `Publish`. A separate one-time legacy source map contains 343 current staging WooCommerce product IDs/SKUs that were independently matched by exact barcode to a direct Clarkston Revel export. Those existing products append as `Working + Remove from Website`, matching the agreed treatment for products that already exist on the website. Before use, the plugin rechecks every mapped WooCommerce ID and SKU, then performs the same atomic append and read-back verification. It extends the hidden `Catalog` formulas one-for-one and may refresh only column D (`Revel Product Name — READ ONLY`) for an existing exact barcode. Duplicate identities fail closed. The write path is disabled until the Google service identity has edit access and `rhn_catalog_discovery_write_enabled` is explicitly set to `yes`.

Every new product received from the Kosmos/Revel product route is forced to Draft/hidden until the Sheet approves it. Existing products keep their current status during ordinary price or inventory updates. The plugin never sends a request to Revel.

The derived import layer preserves every client-entered Sheet cell. It accepts combined client values such as `4.5oz` by separating the numeric weight and unit only in memory. When both weight fields are blank, it may supply the four client-approved future defaults from an unambiguous package-size value: 60-count `6 oz`, 90-count `7 oz`, 1-fl-oz liquid `4.5 oz`, and 2-fl-oz liquid `5.5 oz`. Rebekah's exact Quercetin with Bromelain SKU `733739430700` has its separately confirmed `6 oz` fallback. Existing values always win, and every other unmatched size, including other 120-count products, remains blank rather than being guessed.

The permanent client-owned source is Rebekah's `Inventory sheet` (spreadsheet ID `18wuB-kfgMfKbWGV6qgHClXDjagtPPJP7XdkHlxQhF-s`). Rebekah's client-owned `Ecom media` folder is Drive folder ID `1AxIgYQFeo5ZUyFjU-CMpl8nAQ8NVpnxc`. Do not replace the authenticated setup with a public-link CSV fallback; the client-owned Sheet and media folder are intended to remain restricted.

The authenticated CSV reader accepts up to 5,000 product rows and an 8 MiB response. This accommodates the full Clarkston review catalog while retaining a fail-closed size boundary.

`Photos Uploaded?` is a one-time intake trigger. The plugin records the exact Drive folder fingerprint and matched file IDs for each SKU. A successful match is not revisited on later four-hour runs simply because unrelated files were added elsewhere in the shared folder. A prior no-match is retried only after the folder changes, allowing a newly uploaded correctly named image to be detected. Exact SKU/barcode filenames and unmistakable full product-name filenames may be attached to approved staging products; ambiguous files remain unassigned and appear in the intake digest. Existing Sheet image URLs and later client edits are never overwritten. Email delivery is disabled by default on staging; the digest remains visible to administrators until authenticated customer-facing mail is intentionally enabled.

Credentials are not part of this plugin, repository, WordPress content, or Sheet. Staging may use either protected constants or the encrypted connector settings already installed. The constant-based alternative is:

```php
define( 'RHN_CATALOG_GOOGLE_CREDENTIALS_PATH', '/protected/path/catalog-reader.json' );
define( 'RHN_CATALOG_GOOGLE_SHEET_ID', '18wuB-kfgMfKbWGV6qgHClXDjagtPPJP7XdkHlxQhF-s' );
define( 'RHN_CATALOG_GOOGLE_SHEET_RANGE', 'Catalog!A:W' );
```

The authenticated Drive path supports restricted source images. Public Google Drive and Dropbox links remain supported for the initial controlled test. Imported images are stored in the WordPress media library; the storefront does not hotlink Drive or Dropbox.

## Admin workflow

1. `Tools → Google Sheet catalog intake`: the separate shipping-weight preview/apply changes only the exact-SKU WooCommerce weight for approved rows, records a rollback baseline, reads the value back, and verifies that copy, images, categories, price, inventory, status and visibility stayed unchanged. The full catalog preview/apply remains a separate publishing workflow.
2. `Tools → NAC catalog workflow proof`: fixed staging product 1479647 / SKU 733739401854 only. The test runs through WooCommerce `wc/v1`, verifies mapped fields and protected commerce fields, and restores the full product baseline.
3. `Tools → 25-product staging proof`: fixed 25-product pilot only. It processes one product per AJAX request, forces Draft/hidden during the proof, verifies every mapped field and protected commerce field, and restores that product before moving to the next one.
4. Imported test attachments are retained and detached for review. They are not permanently deleted automatically.
5. `Tools → Four visual catalog examples`: applies or restores persistent Draft/hidden NAC, Parasite, Energy and Brain Mushroom Support examples. The set covers featured image, gallery/group image, liquid content and no-image states. It also renders the staging-only Product information panel.
6. `Tools → Catalog Sheet automation`: shows last attempt/success, consecutive failures and the next run; can run a safe manual sync or a no-write simulated failure.
7. The older `Controlled staging API proof` is historical and already completed; do not rerun it.

## Four-hour automation and failure behavior

The guarded staging site schedules a Sheet sync every four hours. It uses a single-run lock so overlapping runs do not write concurrently. If Google or the Sheet cannot be read, the registry and WooCommerce products remain unchanged and the next scheduled run retries. Two consecutive failures display an administrator warning; three or more display a critical administrator notice. Staging sends no alert email. The health counter retains the exact consecutive-failure count, so longer outages such as six, eight or ten failed runs remain visible.

The JSON intake at `Tools → Staging catalog copy intake` remains as a controlled fallback. It uses the same strict registry validation and supports recoverable registry snapshots.

## Safety gates

- Exact staging hostname guard: `wordpress-1651482-6655800.cloudwaysapps.com`.
- Approved rows require exact string SKU, boolean approval, and nonempty provenance.
- Unknown fields and duplicate SKUs fail closed.
- Categories and brands must already exist; the plugin does not invent them.
- Image hosts, types, and size are restricted.
- The NAC proof requires staging webhook blocking, a fixed product ID/SKU, administrator permissions, a nonce, a durable baseline, and a single-run lock.
- The 25-product proof uses an immutable SKU allowlist, exact-one-product identity checks, administrator permissions, AJAX nonces, a single-product lock, and a durable latest-product baseline.
- Production is inert.

## Local verification

Portable official PHP 8.5.11 NTS x64 was downloaded to the Windows temporary directory and verified against the php.net SHA-256 before use. No PHP runtime was installed system-wide.

Current checks:

- every plugin PHP file passes `php -l`;
- 17 strict registry checks pass;
- 8 registry permission/nonce/no-write checks pass;
- 33 field-ownership, route-policy, SEOPress, wc/v1 after-hook, and fixed-batch checks pass;
- Google Sheet parsing tests cover approval, skipping, explicit withdrawal handling, non-destructive combined-weight normalization, safe future defaults and unmatched 120-count no-guess behavior.
- 23 isolated discovery checks cover exact barcode identity, leading zeroes, append-only row construction, hidden formula extension, read-only source-name reconciliation, the verified 343-record legacy map, legacy withdrawal defaults and duplicate fail-closed behavior.

These isolated checks do not replace WordPress/WooCommerce staging verification. Version 0.5.2 must be visually verified on the four exact Draft/hidden staging product previews before it is treated as the customer-facing product-information pattern.

## Rollback

Deactivate the standalone plugin and clear its scheduled event if permanently removing it. Registry updates retain a previous snapshot. Staging product changes keep durable first-change baselines in `rhn_catalog_sheet_product_baselines`; visual examples also retain their existing preview baselines until Todd explicitly authorizes restoration.

WooCommerce hook references:

- https://woocommerce.github.io/code-reference/files/woocommerce-includes-rest-api-controllers-version1-class-wc-rest-products-v1-controller.html
- https://woocommerce.github.io/code-reference/files/woocommerce-includes-rest-api-controllers-version3-class-wc-rest-crud-controller.html
