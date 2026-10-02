# RHN Catalog Presentation (Staging)

Current local candidate: **0.5.2**. The installed staging version must be verified after upload; do not infer deployment from this repository.

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

The plugin never writes to Revel. It does not create terms or change SKU, price, stock, orders, customers, payments or refunds. On the exact guarded staging installation only, a row that passes both approval gates as `Approved for Test` or `Approved` changes the matching product to Published/visible. The explicit Sheet status `Remove from Website` changes it to Draft/hidden; it never deletes a product. Re-approval restores Published/visible.

## Google Sheet intake

`google-sheet-sync.php` reads the `Catalog!A:W` range through a Google service identity with read-only Sheets and Drive scopes. Rows whose Status is `Approved for Test` or `Approved` update website-owned fields and become Published/visible on guarded staging. The exact status `Remove from Website` is the only withdrawal instruction; ordinary working, review, or unapproved states do not alter an existing product. Preview validates without writing. Apply updates the private registry and the matching guarded staging products.

The permanent client-owned source is Rebekah's `Inventory sheet` (spreadsheet ID `18wuB-kfgMfKbWGV6qgHClXDjagtPPJP7XdkHlxQhF-s`). Rebekah's client-owned `Ecom media` folder is Drive folder ID `1AxIgYQFeo5ZUyFjU-CMpl8nAQ8NVpnxc`. Do not replace the authenticated setup with a public-link CSV fallback; the client-owned Sheet and media folder are intended to remain restricted.

`Photos Uploaded?` is a one-time intake trigger. The plugin records the exact Drive folder fingerprint and matched file IDs for each SKU, so an unchanged four-hour run does not repeat the work. Exact SKU/barcode filenames and unmistakable full product-name filenames may be attached to approved staging products; ambiguous files remain unassigned and appear in the intake digest. Existing Sheet image URLs and later client edits are never overwritten. Email delivery is disabled by default on staging; the digest remains visible to administrators until authenticated customer-facing mail is intentionally enabled.

Credentials are not part of this plugin, repository, WordPress content, or Sheet. Staging may use either protected constants or the encrypted connector settings already installed. The constant-based alternative is:

```php
define( 'RHN_CATALOG_GOOGLE_CREDENTIALS_PATH', '/protected/path/catalog-reader.json' );
define( 'RHN_CATALOG_GOOGLE_SHEET_ID', '18wuB-kfgMfKbWGV6qgHClXDjagtPPJP7XdkHlxQhF-s' );
define( 'RHN_CATALOG_GOOGLE_SHEET_RANGE', 'Catalog!A:W' );
```

The authenticated Drive path supports restricted source images. Public Google Drive and Dropbox links remain supported for the initial controlled test. Imported images are stored in the WordPress media library; the storefront does not hotlink Drive or Dropbox.

## Admin workflow

1. `Tools → Google Sheet catalog intake`: preview, then apply approved rows to the registry.
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
- Google Sheet parsing tests cover approval, skipping and explicit withdrawal handling.

These isolated checks do not replace WordPress/WooCommerce staging verification. Version 0.5.2 must be visually verified on the four exact Draft/hidden staging product previews before it is treated as the customer-facing product-information pattern.

## Rollback

Deactivate the standalone plugin and clear its scheduled event if permanently removing it. Registry updates retain a previous snapshot. Staging product changes keep durable first-change baselines in `rhn_catalog_sheet_product_baselines`; visual examples also retain their existing preview baselines until Todd explicitly authorizes restoration.

WooCommerce hook references:

- https://woocommerce.github.io/code-reference/files/woocommerce-includes-rest-api-controllers-version1-class-wc-rest-products-v1-controller.html
- https://woocommerce.github.io/code-reference/files/woocommerce-includes-rest-api-controllers-version3-class-wc-rest-crud-controller.html
