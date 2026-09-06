# Rebekah’s Phase Two Ecommerce — Local Theme Scaffold

Version: v1.1  
Prepared: 2026-09-02  
Status: Activated on Cloudways staging only; WooCommerce and external integrations remain inactive

## Boundary

The guarded module is enabled only on the verified Cloudways staging hostname. The Phase One visual system remains the foundation, and the live website has not been changed. Do not activate this scaffold on production before final deployment approval.

## Files

- `inc/phase-two-ecommerce.php` — guarded WooCommerce component functions and integration hooks.
- `assets/css/components/phase-two-commerce.css` — responsive styles scoped to ecommerce contexts and explicit component classes.
- `assets/js/components/phase-two-commerce.js` — keyboard-accessible mobile filter drawer behavior.

## What is prepared

- The second-row Online Store utility navigation with Shop, Search, My Account, and live cart count.
- The fifth Online Store footer group.
- A product-photo fallback using the approved Rebekah’s brand mark and “Product photo coming soon” wording.
- Product-help telephone treatment.
- Directions & Warnings disclosure that appears only when verified content exists.
- Responsive WooCommerce catalog-card styling.
- Accessible mobile filter-drawer open, close, Escape, focus-return, and focus-containment behavior.
- Cart-count fragment refresh after AJAX add to cart.
- Client-approved Main Homepage Ecommerce Integration v1.9: direct Shop Online hero entry, three shopping paths, and a featured-product area that reads real WooCommerce products when the catalog becomes available.

## Intentionally not finalized

- The approved header/footer additions and homepage ecommerce integration are present on staging only.
- No taxonomy, wellness-goal, brand, or category URL has been guessed.
- No WooCommerce template override has been added before the active WooCommerce version and real staging markup are known.
- No product image, description, direction, warning, or shipping field ownership has been assumed.
- No checkout, tax, shipping, payment, email, or account setting has been changed.

## Staging activation sequence

Completed staging activation steps:

1. Confirmed WooCommerce 11.1.0 is installed but inactive.
2. Enabled `RHN_PHASE_TWO_ECOMMERCE_ENABLED` only after checking the Cloudways staging hostname.
3. Loaded `inc/phase-two-ecommerce.php` from `functions.php`.
4. Inserted `rhn_phase_two_store_utility_row()` immediately after the approved Phase One header.
5. Added the fifth tablet tab/panel and matching Online Store footer group.
6. Expanded the desktop footer grid while preserving the approved Phase One intro column.
7. Applied Main Homepage Ecommerce Integration v1.9 without review-only labels or fake product records.
8. Verified the staging page, links, responsive tablet presentation, no-WooCommerce fallback, and absence of a PHP fatal.

Still pending: validate the real WooCommerce markup, product states, cart, checkout, account, keyboard/focus behavior, and all phone widths after WooCommerce activation and the one-product connection proof.

## Verification before deployment

- PHP syntax and WordPress coding safety pass.
- JavaScript syntax pass.
- No undefined WooCommerce calls when WooCommerce is inactive.
- Header and footer remain unchanged when the feature flag is absent or false.
- Shop, product, cart, checkout, account, and thank-you pages match the exact approved systems when the flag is enabled on staging.
- All links use real WordPress/WooCommerce routes, not in-page review anchors.
