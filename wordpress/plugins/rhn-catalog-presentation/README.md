# RHN Catalog Presentation (Staging)

Current local candidate: **0.4.5**. The installed staging version must be verified after upload; do not infer deployment from this repository.

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

The plugin never writes to Revel. It does not create terms, publish products, change SKU, price, stock, product status/visibility, orders, customers, payments, refunds, or connector schedules.

## Google Sheet intake

`google-sheet-sync.php` reads the `Catalog!A:W` range through a Google service identity with read-only Sheets and Drive scopes. Only rows whose Status is `Approved for Test` or `Approved` are accepted. Preview validates the source without writing. Apply updates only the private WordPress registry; product records change later through an explicitly supported WooCommerce API write or the controlled NAC proof.

Credentials are not part of this plugin, repository, WordPress content, or Sheet. Staging configuration must define:

```php
define( 'RHN_CATALOG_GOOGLE_CREDENTIALS_PATH', '/protected/path/catalog-reader.json' );
define( 'RHN_CATALOG_GOOGLE_SHEET_ID', 'spreadsheet-id' );
define( 'RHN_CATALOG_GOOGLE_SHEET_RANGE', 'Catalog!A:W' );
```

The authenticated Drive path supports restricted source images. Public Google Drive and Dropbox links remain supported for the initial controlled test. Imported images are stored in the WordPress media library; the storefront does not hotlink Drive or Dropbox.

## Admin workflow

1. `Tools → Google Sheet catalog intake`: preview, then apply approved rows to the registry.
2. `Tools → NAC catalog workflow proof`: fixed staging product 1479647 / SKU 733739401854 only. The test runs through WooCommerce `wc/v1`, verifies mapped fields and protected commerce fields, and restores the full product baseline.
3. `Tools → 25-product staging proof`: fixed 25-product pilot only. It processes one product per AJAX request, forces Draft/hidden during the proof, verifies every mapped field and protected commerce field, and restores that product before moving to the next one.
4. Imported test attachments are retained and detached for review. They are not permanently deleted automatically.
5. `Tools → Two visual catalog examples`: applies or restores persistent Draft/hidden NAC and Quercetin examples so the actual staging product template can be reviewed with test images and all approved presentation fields. It also renders a staging-only Product information panel for brand, category, packaged weight, ingredients, allergens and Supplement Facts.
6. The older `Controlled staging API proof` is historical and already completed; do not rerun it.

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
- 9 Google Sheet parsing/approval checks pass.

These isolated checks do not replace WordPress/WooCommerce staging verification. Version 0.4.5 must be visually verified on the two exact Draft/hidden staging product previews before it is treated as the customer-facing product-information pattern.

## Rollback

Deactivate the standalone plugin. Registry updates retain a previous snapshot for restoration. The NAC proof restores the product baseline automatically and also exposes a recovery action using the saved baseline. No activation migration or automatic bulk product update exists.

WooCommerce hook references:

- https://woocommerce.github.io/code-reference/files/woocommerce-includes-rest-api-controllers-version1-class-wc-rest-products-v1-controller.html
- https://woocommerce.github.io/code-reference/files/woocommerce-includes-rest-api-controllers-version3-class-wc-rest-crud-controller.html
