# Rebekah’s Phase Two Ecommerce — Pre-Staging Execution and Rollback Runbook

Version: v1.1  
Prepared: 2026-09-02  
Status: Staging build complete; WooCommerce core active; approved Phase Two systems QA-verified; connection pending

## Purpose

This runbook defines the order of operations for creating and using a protected Phase Two staging environment without risking production content, customer email, payments, orders, inventory, or search visibility.

## Non-negotiable boundary

Todd authorized the same-server staging application, WooCommerce core activation, legacy-catalog isolation, approved storefront implementation, and staging QA. The live website, Revel/Kosmos connection, USPS/payment configuration, purchases, subscriptions, paid-service changes, Cloudways password-protection changes, and staging-to-live deployment remain outside the completed staging-build scope.

## Gate 0 — client design approval

- [x] Rebekah approves Header + Footer Ecommerce Add-On v1.5.
- [x] Rebekah approves Main Homepage Ecommerce Integration v1.9.
- [x] Rebekah approves Shop Homepage + Product Catalog v1.9.
- [x] Rebekah approves Product Page Templates v1.7.
- [x] Rebekah approves Purchase Path v1.6.
- [x] Rebekah approves Customer Account System v1.6.
- [x] Rebekah approves Store States + Components v1.5.
- [x] Any requested revision is saved as the next independent version and reapproved before implementation.

## Gate 1 — prerequisites and working window

- [ ] Confirm the exact build window and who is available for escalation.
- [ ] Verify Revel access reaches the Clarkston establishment and supports the required integration actions.
- [ ] Verify Kosmos account access and record the exact remaining trial/subscription window.
- [ ] Identify one representative product that is Active, belongs to Clarkston, and is enabled for online/third-party display.
- [ ] Confirm its identifier, name, price, Clarkston inventory, category, and whether it has variations.
- [ ] Record known non-mailable or special-handling products; “none known” is an acceptable explicit answer.
- [ ] Confirm USPS Developer/API access when live-rate testing is scheduled. It is not required for the first product-creation proof.
- [ ] Confirm the actual Clover/Fiserv gateway when payment testing is scheduled. It is not required for product synchronization or non-payment storefront work.
- [x] Todd explicitly authorized the Cloudways/server and staging-build actions completed September 6, 2026.

Stop if any prerequisite needed for the planned session is missing. Do not consume a short trial window while access, client availability, or the representative product is unresolved.

## Gate 2 — production safety before cloning

1. Record the production application, server plan, PHP version, database version, active theme, and active plugin list.
2. Record current public checks for the homepage, shop-related routes, forms, events, blog, and four store-location pages.
3. Schedule the work outside known blackout periods and avoid leaving a live integration handoff unattended over a weekend.
4. Retain the current 2 GB RAM / 1-vCPU plan for the controlled WooCommerce activation and one-product connection proof. Scale to 4 GB / 2 vCPU only if the measured workload proves it is needed or Todd separately approves it.
5. Create a fresh production restore point and record its exact timestamp.
6. Confirm the restore point is visible and eligible for restore before continuing.

Rollback trigger: stop and restore or reverse the last controlled step if production availability, forms, checkout-independent site behavior, PHP workers, database health, or resource headroom materially regresses.

## Gate 3 — protected staging creation

1. Create a new same-server staging clone from the fresh production state.
2. Leave Cloudways password protection under Todd's control. Do not enable or disable it without his explicit instruction.
3. Set WordPress search-engine visibility to discourage indexing.
4. Add an application-level `noindex, nofollow` response/meta safeguard.
5. Block outgoing customer and administrator transactional email; route test mail only to approved Blue Nova test recipients when email testing begins.
6. Keep all payment gateways disabled or in verified sandbox/test mode.
7. Disable real carrier-label purchase and real fulfillment actions.
8. Disable scheduled synchronization until the manual one-product test is ready.
9. Confirm the staging URL cannot be mistaken for production in the admin bar and page source.
10. Verify no analytics, ad, webhook, or feed endpoint can record staging traffic as production activity.

Required evidence:

- [x] Staging URL recorded privately.
- [ ] Cloudways password protection is intentionally outside this checklist; Todd controls the switch.
- [x] WordPress search visibility is set to discourage indexing and the active staging guard adds an application-level noindex safeguard.
- [x] Outgoing email is blocked by the active Blue Nova Staging Guard v1.1.
- [x] Payment, webhook, scheduled-action, and fulfillment side effects are blocked while the staging guard is active; WooCommerce core is active, but every payment-gateway provider remains inactive.
- [x] The administrator toolbar shows the staging noindex status.

## Gate 4 — baseline cleanup and catalog isolation

1. Preserve the separate deferred Phase One homepage performance/asset cleanup for prelaunch QA. It is not part of the approved ecommerce mockup parity decision and does not block the one-product synchronization proof.
2. Export the legacy WooCommerce product records for reference.
3. Follow the clean-start plan; quarantine legacy products without deleting them.
4. Confirm Shop, Cart, Checkout, and My Account system pages exist once and point to the intended routes.
5. Record the commerce plugin baseline.
6. Activate WooCommerce and only the extensions required for the immediate test.
7. Keep WooPayments, NMI, coupons, loyalty, reviews, pickup, and unrelated commerce add-ons disabled unless a later approved requirement specifically needs them.

Completed evidence on September 6, 2026:

- [x] Exported and preserved the legacy catalog inventory; 61 product records are Draft, zero are published, and none were deleted.
- [x] Confirmed the canonical Shop, Cart, Checkout, and My Account routes.
- [x] Activated WooCommerce core only and kept WooPayments, NMI, and unnecessary commerce extensions inactive.
- [x] Implemented approved Systems 01–07 through staging installer v1.6 and verified desktop, tablet, and phone behavior.
- [x] Seeded 21 approved wellness categories and 9 brand terms for the one-product proof.
- [x] Recorded the detailed verification in `PHASE-TWO-STAGING-QA-2026-09-06.md`.

## Gate 5 — connection and one-product proof

**Standing Revel rule (added 2026-09-06):** Revel is Rebekah's live production POS and inventory system and is read-only by default. No Revel-side product, inventory, price, category, online flag, user, permission, credential, integration, order, customer, refund, or setting change is authorized until Todd and Codex discuss that exact action and Todd explicitly approves it at action time. General permission to begin the staging integration is not permission to write to Revel.

1. Create the dedicated WooCommerce REST/API user and key only when Kosmos setup requires it. Do not create or change a Revel user, API credential, integration, or authorization without the separate Revel approval above.
2. Store credentials only in the approved secret-management location; never in this repository, screenshots, notes, chat, or email.
3. Inspect existing Kosmos/Revel connection state read-only. Connect the client-owned Revel and WooCommerce endpoints inside Kosmos only after verifying that this step itself does not change Revel; otherwise stop for Todd's approval.
4. Keep automated/bulk schedules and every WooCommerce-to-Revel writeback action off.
5. Run one representative-product Revel-to-staging-WooCommerce read/import only after its direction and Revel-side effects are proven read-only.
6. Complete `PHASE-TWO-ONE-PRODUCT-ACCEPTANCE-TEST-v1.1.md` using observed values.
7. If the proof fails, pause. Correct one cause at a time and rerun only the failed portion or the single product.
8. Do not synchronize the remaining 24 products until the acceptance result is Pass.

## Gate 6 — pilot synchronization and storefront application

1. Pull the remaining products enabled for online/third-party display.
2. Reconcile the received set against the intended 25; investigate extra, missing, and duplicate records before publishing.
3. Measure actual field transfer and overwrite behavior.
4. Establish field ownership from evidence, not assumptions.
5. Apply the internally and client-approved storefront system to the real product types.
6. Audit images, descriptions, ingredients, warnings, facts, directions, variations, weights, and shipping restrictions.
7. Produce one consolidated content-gap list for Rebekah.
8. Keep all pilot products private or otherwise unavailable to public shoppers until final approval.

## Gate 7 — operational configuration and acceptance

- [ ] Clarkston fulfillment origin and two-business-day handling time.
- [ ] Continental United States only; Alaska, Hawaii, territories, and international destinations excluded.
- [ ] PO-box validation.
- [ ] USPS Ground Advantage and Priority Mail rate testing with representative addresses and one-, two-, and three-product carts.
- [ ] Backorders off, low-stock threshold two, and one-unit safety buffer unless testing approves removal.
- [ ] Guest checkout and optional accounts; no customer 2FA.
- [ ] Staff/admin backend security tested separately.
- [ ] Tax configuration follows Rebekah’s supplied instruction and test totals are approved.
- [ ] Order notices reach both approved operational recipients.
- [ ] Payment success, failure, void/cancellation where supported, and refund tested in sandbox/test mode.
- [ ] WooCommerce orders transfer to Revel and reduce inventory at Clarkston only.
- [ ] Responsive, accessibility, SEO, analytics, performance, email, backup, and restore checks pass.
- [ ] Rebekah approves the final 25-product list, content, policies, and customer journey.

## Production deployment rule

Do not overwrite the entire production database with the staging database. Preserve production posts, events, media, forms, users, and records created after the clone. Deploy the approved code and only carefully selected data/configuration changes after a short editorial freeze and fresh backups.

## Rollback levels

| Level | Trigger | Response |
| --- | --- | --- |
| 1 — configuration | A plugin, setting, template, or test produces an isolated staging defect | Disable/revert the last change and retest |
| 2 — integration | Wrong product, duplicate record, overwrite, order-routing, or inventory behavior | Stop schedules, disconnect the affected action, preserve logs, restore the staging checkpoint if needed |
| 3 — production impact | Production availability, forms, data, or performance is affected by shared resources or deployment | Stop work, reverse the server/configuration change or restore from the verified production restore point |
| 4 — transactional | A real payment, order, email, label, inventory adjustment, or customer record is created unexpectedly | Stop all integrations, preserve evidence, notify Todd immediately, reconcile the external system before resuming |

## Completion record

The runbook is complete only when each applicable gate is signed with date, operator, evidence location, result, and unresolved exception. A passed design review does not substitute for a passed integration or launch gate.
