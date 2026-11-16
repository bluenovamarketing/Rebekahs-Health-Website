# Brand Sync Project Notes

Status: In progress  
Last updated: 2026-11-16

## Verified behavior

- The client-facing Brand field is free text, not a dropdown.
- Website category fields remain controlled dropdowns.
- The current missing brand example was entered in its existing product row without changing the row's approval or website-action status.

## Approved-product brand behavior

The catalog processor source now:

1. reuses an exact existing brand;
2. reuses a normalized slug match so capitalization or punctuation does not create a duplicate;
3. rejects a one-character near-match for review instead of silently creating a likely typo;
4. creates a genuinely missing brand only while applying an approved product; and
5. never auto-creates product categories.

This does not change the existing approval gate or any SKU, price, stock, product status, or visibility field.

## Verification remaining

- Run the isolated brand-term test.
- Deploy the updated live processor source.
- Read back the installed version.
- Confirm the new brand exists only once in the live brand taxonomy.
- Confirm a normal approved-product cycle still respects all approval rules.
