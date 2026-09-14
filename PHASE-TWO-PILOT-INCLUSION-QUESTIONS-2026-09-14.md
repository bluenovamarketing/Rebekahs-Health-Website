# Exact pilot inclusion — unsent support draft

**HOLD — not approved to send.** Todd clarified that Mark's spreadsheet is not proof of edits/imports into Revel. Reconcile actual source identities and current Woo records before deciding whether these questions are necessary. No requirement to restrict every diagnostic run to exactly 25 is implied by the spreadsheet.

Hi Kosmos Support,

We have completed the WooCommerce copy-protection proof and are reconciling the actual 25-product Clarkston pilot.

Our standard Product Action is Revel to staging WooCommerce, establishment 3, matching Revel barcode to Woo SKU. Its exposed filters show an updated-after date but no barcode or product-ID allowlist. The existing September 1 run selected 245 other products; our pilot NAC barcode 733739401854 is absent from WooCommerce.

Please give us the exact supported way to backfill only the 25 specified barcodes without changing live Revel products, flags, timestamps, inventory, or enabling writeback:

1. Is there a barcode/product-ID allowlist or targeted resync available outside the exposed Action settings?
2. If the 25 Woo products are initially created as Draft using the supplied Revel export and exact barcode SKUs, will subsequent standard Product and Inventory Actions adopt/update them without duplicates? Which metadata, if any, must exist?
3. How do we initialize inventory for those existing older products without requesting a full-history inventory run or waiting for a natural stock change?
4. Does the 250-record inventory selection reflect a page size or a cap? How is pagination handled, and are failed Woo lookups retried before the incremental watermark advances?

Please answer for the standard integration and identify any proposed paid work separately before doing it. We are not authorizing fees or configuration changes through this request.

Thank you,
Todd

Status: local draft only, not emailed or submitted. No full-history run, Revel edit, fee, or connector configuration change performed.
