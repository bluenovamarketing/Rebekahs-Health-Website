# Rebekah’s Phase Two Ecommerce — Local Readiness Index

Version: v1.1  
Prepared: 2026-09-02  
Status: Client-approved design set implemented and QA-verified on staging; WooCommerce and Revel connections are present in Kosmos, but no Action has been created and usable Kosmos dashboard access must be restored

## Fixed boundary

This index began as a local-only planning package. Todd later authorized the same-server staging application, WooCommerce core activation, approved storefront implementation, and safe staging preparation. It does not authorize live-site changes, Revel/Kosmos connections, payment-gateway activation, purchases, subscriptions, paid-service activation, Cloudways password-protection changes, or a staging-to-live push.

## Current position

- All seven current ecommerce mockup systems are approved internally by Todd and approved by Rebekah.
- The exact approved versions are frozen in the review hub and version register.
- All seven approved ecommerce systems are implemented on staging through versioned backup-and-rollback installers. Installer v1.6 remains the active rollback installer; in-place staging correction pass v1.9 restores the complete approved Product Page v1.7 three-view no-image gallery and retains the corrected account layout, proportional catalog artwork, and explicit quarantine of every pre-connection product record without changing any approved mockup version. The build still maps to Header/Footer v1.5, Homepage v1.9, Shop/Catalog v1.9, Product Pages v1.7, Purchase Path v1.6, Customer Account v1.6, and Store States v1.5.
- WordPress search visibility and the Blue Nova staging guard are active; Cloudways password protection remains solely under Todd's control.
- WooCommerce core is active, but every payment-gateway provider remains inactive and blocked by the staging guard.
- Clarkston is the sole pilot fulfillment location.
- The pilot contains 25 selected Revel products.
- The first integration proof uses one representative product; the remaining 24 follow only after that proof passes.
- The 61 legacy WooCommerce products are preserved as Draft, with zero published products and no deletions. They will not be treated as the new catalog.

## Local execution package

1. [Pre-staging execution and rollback runbook](PHASE-TWO-PRE-STAGING-EXECUTION-RUNBOOK-v1.1.md)
2. [One-product synchronization acceptance test](PHASE-TWO-ONE-PRODUCT-ACCEPTANCE-TEST-v1.1.md)
3. [Legacy catalog clean-start plan](PHASE-TWO-LEGACY-CATALOG-CLEAN-START-PLAN-v1.1.md)
4. [Ecommerce policy decision draft](PHASE-TWO-ECOMMERCE-POLICY-DECISION-DRAFT-v1.1.md)
5. [Local WooCommerce scaffold notes](wordpress/theme/rebekahs-2026/PHASE-TWO-ECOMMERCE-SCAFFOLD-v1.1.md)
6. [Master checklist](PHASE-TWO-MASTER-CHECKLIST.md)
7. [Readiness record](PHASE-TWO-ECOMMERCE-READINESS.md)
8. [Mockup review hub](phase-two-ecommerce-mockup-sheet.html)
9. [September 6 staging implementation and QA record](PHASE-TWO-STAGING-QA-2026-09-06.md)

## Work order

| Order | Work | Can happen now? | Completion evidence |
| --- | --- | --- | --- |
| 1 | Freeze the exact client-approved mockups | Complete | Review hub and version register show exact versions |
| 2 | Prepare runbook, acceptance test, clean-start plan, policy decisions, and local scaffold | Complete | This package and local theme files |
| 3 | Verify Revel permissions, Kosmos access/trial timing, one prepared product, and safe work window | Partly complete; Revel access and the NAC test product are confirmed, but usable Kosmos dashboard access, current billing status, Clarkston-only connection scope, and safe Action behavior still require verification | Access/gate record marked verified |
| 4 | Create restore point and same-server staging; establish measured capacity decision | Complete; retain 2 GB for the controlled activation and one-product proof, then scale only if measured thresholds are reached | Cloudways restore point, staging, and capacity record |
| 5 | Quarantine legacy catalog, activate WooCommerce core only, and implement all approved storefront systems | Complete on staging | 61 explicitly quarantined Draft legacy products, zero published products, inactive gateways, staging installer v1.6 plus correction pass v1.9, and QA record |
| 6 | Run one-product Revel → Kosmos → WooCommerce proof | No; connection required | Completed one-product acceptance test |
| 7 | Synchronize and reconcile the remaining 24 pilot products | No; proof must pass first | Final 25-product reconciliation list |
| 8 | Refine the approved storefront against real products and configure operations | After the one-product proof | Staging acceptance evidence |
| 9 | Complete client approval, staff training, launch, and stabilization | No; all gates required | Signed launch record and post-launch checks |

## Next gate

The staging build gate has been completed. The next controlled gate is the one-product Revel → Kosmos → WooCommerce proof on the current 2 GB server. It opens only when:

- Usable access to the client-owned Kosmos dashboard is restored.
- The Revel connection already visible in Kosmos is verified as Clarkston-only, with a manual Revel-to-staging direction and no WooCommerce-to-Revel writeback or automatic schedule.
- Kosmos billing/subscription status is reconfirmed.
- At least one representative product is Active and enabled for online/third-party display in Revel.
- Todd starts the connection work window. A paid 4 GB scale-up is not a prerequisite and requires separate approval only if measured load proves it is needed.
