# Rebekah’s Phase Two Ecommerce — Pre-Staging Execution and Rollback Runbook

Version: v1.1  
Prepared: 2026-09-02  
Status: Local preparation complete; execution not authorized

## Purpose

This runbook defines the order of operations for creating and using a protected Phase Two staging environment without risking production content, customer email, payments, orders, inventory, or search visibility.

## Non-negotiable boundary

This document is a plan only. No Cloudways, staging, live website, WordPress, Revel, Kosmos, USPS, payment-gateway, purchase, or paid-service action occurs until Todd explicitly authorizes that phase.

## Gate 0 — client design approval

- [ ] Rebekah approves Header + Footer Ecommerce Add-On v1.5.
- [ ] Rebekah approves Main Homepage Ecommerce Integration v1.9.
- [ ] Rebekah approves Shop Homepage + Product Catalog v1.8.
- [ ] Rebekah approves Product Page Templates v1.7.
- [ ] Rebekah approves Purchase Path v1.6.
- [ ] Rebekah approves Customer Account System v1.6.
- [ ] Rebekah approves Store States + Components v1.5.
- [ ] Any requested revision is saved as the next independent version and reapproved before implementation.

## Gate 1 — prerequisites and working window

- [ ] Confirm the exact build window and who is available for escalation.
- [ ] Verify Revel access reaches the Clarkston establishment and supports the required integration actions.
- [ ] Verify Kosmos account access and record the exact remaining trial/subscription window.
- [ ] Identify one representative product that is Active, belongs to Clarkston, and is enabled for online/third-party display.
- [ ] Confirm its identifier, name, price, Clarkston inventory, category, and whether it has variations.
- [ ] Record known non-mailable or special-handling products; “none known” is an acceptable explicit answer.
- [ ] Confirm USPS Developer/API access when live-rate testing is scheduled. It is not required for the first product-creation proof.
- [ ] Confirm the actual Clover/Fiserv gateway when payment testing is scheduled. It is not required for product synchronization or non-payment storefront work.
- [ ] Todd explicitly authorizes the Cloudways/server and staging actions.

Stop if any prerequisite needed for the planned session is missing. Do not consume a short trial window while access, client availability, or the representative product is unresolved.

## Gate 2 — production safety before cloning

1. Record the production application, server plan, PHP version, database version, active theme, and active plugin list.
2. Record current public checks for the homepage, shop-related routes, forms, events, blog, and four store-location pages.
3. Schedule the work outside known blackout periods and avoid leaving a live integration handoff unattended over a weekend.
4. Increase the server to 4 GB RAM / 2 vCPU immediately before clone and commerce work if that remains the approved capacity plan.
5. Create a fresh production restore point and record its exact timestamp.
6. Confirm the restore point is visible and eligible for restore before continuing.

Rollback trigger: stop and restore or reverse the last controlled step if production availability, forms, checkout-independent site behavior, PHP workers, database health, or resource headroom materially regresses.

## Gate 3 — protected staging creation

1. Create a new same-server staging clone from the fresh production state.
2. Require authentication before public access.
3. Set WordPress search-engine visibility to discourage indexing.
4. Add an application-level `noindex, nofollow` response/meta safeguard.
5. Block outgoing customer and administrator transactional email; route test mail only to approved Blue Nova test recipients when email testing begins.
6. Keep all payment gateways disabled or in verified sandbox/test mode.
7. Disable real carrier-label purchase and real fulfillment actions.
8. Disable scheduled synchronization until the manual one-product test is ready.
9. Confirm the staging URL cannot be mistaken for production in the admin bar and page source.
10. Verify no analytics, ad, webhook, or feed endpoint can record staging traffic as production activity.

Required evidence:

- [ ] Protected staging URL recorded privately.
- [ ] Authentication verified in a logged-out session.
- [ ] `noindex` verified in the anonymous response.
- [ ] Outgoing email capture/block verified.
- [ ] Payment and fulfillment side effects disabled.
- [ ] Staging banner/environment marker visible to administrators.

## Gate 4 — baseline cleanup and catalog isolation

1. Complete the deferred homepage asset/PHP cleanup on staging and run the existing Phase One regression checks.
2. Export the legacy WooCommerce product records for reference.
3. Follow the clean-start plan; quarantine legacy products without deleting them.
4. Confirm Shop, Cart, Checkout, and My Account system pages exist once and point to the intended routes.
5. Record the commerce plugin baseline.
6. Activate WooCommerce and only the extensions required for the immediate test.
7. Keep WooPayments, NMI, coupons, loyalty, reviews, pickup, and unrelated commerce add-ons disabled unless a later approved requirement specifically needs them.

## Gate 5 — connection and one-product proof

1. Create the dedicated WooCommerce REST/API user and key only when Kosmos setup requires it.
2. Store credentials only in the approved secret-management location; never in this repository, screenshots, notes, chat, or email.
3. Connect the client-owned Revel and WooCommerce endpoints inside Kosmos.
4. Keep automated/bulk schedules off.
5. Run one action and one representative product.
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

