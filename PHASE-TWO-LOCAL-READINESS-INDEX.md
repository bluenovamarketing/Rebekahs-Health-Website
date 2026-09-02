# Rebekah’s Phase Two Ecommerce — Local Readiness Index

Version: v1.1  
Prepared: 2026-09-02  
Status: Ready for internal use while exact mockups await Rebekah’s approval

## Fixed boundary

Local work only. This package does not authorize or perform Cloudways changes, staging creation, live website changes, WordPress deployment, Revel or Kosmos connections, purchases, subscriptions, or paid-service activation.

## Current position

- All seven current ecommerce mockup systems are approved internally by Todd.
- All seven exact versions are awaiting Rebekah’s approval.
- The design set is frozen until client feedback arrives.
- Clarkston is the sole pilot fulfillment location.
- The pilot contains 25 selected Revel products.
- The first integration proof uses one representative product; the remaining 24 follow only after that proof passes.
- Legacy WooCommerce products will not be treated as the new catalog. They will be quarantined and reconciled on protected staging before pilot synchronization.

## Local execution package

1. [Pre-staging execution and rollback runbook](PHASE-TWO-PRE-STAGING-EXECUTION-RUNBOOK-v1.1.md)
2. [One-product synchronization acceptance test](PHASE-TWO-ONE-PRODUCT-ACCEPTANCE-TEST-v1.1.md)
3. [Legacy catalog clean-start plan](PHASE-TWO-LEGACY-CATALOG-CLEAN-START-PLAN-v1.1.md)
4. [Ecommerce policy decision draft](PHASE-TWO-ECOMMERCE-POLICY-DECISION-DRAFT-v1.1.md)
5. [Local WooCommerce scaffold notes](wordpress/theme/rebekahs-2026/PHASE-TWO-ECOMMERCE-SCAFFOLD-v1.1.md)
6. [Master checklist](PHASE-TWO-MASTER-CHECKLIST.md)
7. [Readiness record](PHASE-TWO-ECOMMERCE-READINESS.md)
8. [Mockup review hub](phase-two-ecommerce-mockup-sheet.html)

## Work order

| Order | Work | Can happen now? | Completion evidence |
| --- | --- | --- | --- |
| 1 | Freeze internally approved mockups and wait for exact client approval | Yes; frozen | Review hub and version register show exact versions |
| 2 | Prepare runbook, acceptance test, clean-start plan, policy decisions, and local scaffold | Yes; local only | This package and local theme files |
| 3 | Verify Revel permissions, Kosmos access/trial timing, one prepared product, and safe work window | No; requires authorized external access | Access/gate record marked verified |
| 4 | Scale server, create restore point, and create protected staging | No; requires Todd’s explicit authorization | Cloudways restore point and protected staging URL |
| 5 | Quarantine legacy catalog and activate only the required commerce stack | No; staging only | Catalog audit and plugin baseline |
| 6 | Run one-product Revel → Kosmos → WooCommerce proof | No; connection required | Completed one-product acceptance test |
| 7 | Synchronize and reconcile the remaining 24 pilot products | No; proof must pass first | Final 25-product reconciliation list |
| 8 | Apply approved storefront to real products and configure operations | No; staging and product data required | Staging acceptance evidence |
| 9 | Complete client approval, staff training, launch, and stabilization | No; all gates required | Signed launch record and post-launch checks |

## Next gate

Do not create staging merely because these local materials are ready. The staging gate opens only when:

- Rebekah approves the seven exact mockup versions.
- Revel access can be verified for the Clarkston establishment and required integration functions.
- Kosmos access and the usable trial/subscription window are confirmed.
- At least one representative product is Active and enabled for online/third-party display in Revel.
- Todd approves a working window and the Cloudways/server actions.

