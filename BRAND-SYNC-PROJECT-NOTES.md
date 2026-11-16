# Brand Sync Source Status

Status: Superseded by the verified release source
Last updated: 2026-11-16

The earlier draft implementation is retained in repository history. The current
source of record is the catalog processor code under
`wordpress/mu-plugins/rhn-live-catalog-processor/`.

This repository change synchronizes those two source files only. It does not
deploy code or change any runtime configuration.

Verified source hashes:

- `catalog-fields.php`: `A34E89CD6E04AD98DF2BB4C5F4CD5C852A14A84B5F2F18A94F268B98A5182F45`
- `production-scheduler.php`: `2C4154BCE7ADDD2B60D136824E4FE9A8523705ABDD5448687F3D60084447EC52`

The catalog-presentation plugin and its isolated brand test are staging
candidates. Their one-character near-match rule is not production behavior.
