# Rebekah’s Phase Two Ecommerce — Local Theme Scaffold

Version: v1.1  
Prepared: 2026-09-02  
Status: Dormant local scaffold; not approved for deployment or activation

## Boundary

These files are local preparation only. The Phase One theme continues to operate unchanged because the module is not loaded and its activation constant is not defined. Do not activate it on production.

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

## Intentionally not finalized

- No header.php or footer.php change has been made while client approval is pending.
- No homepage ecommerce entry has been inserted.
- No taxonomy, wellness-goal, brand, or category URL has been guessed.
- No WooCommerce template override has been added before the active WooCommerce version and real staging markup are known.
- No product image, description, direction, warning, or shipping field ownership has been assumed.
- No checkout, tax, shipping, payment, email, or account setting has been changed.

## Staging activation sequence

After exact client approval and protected staging are ready:

1. Confirm the active WooCommerce version and inspect its current template diagnostics.
2. Define `RHN_PHASE_TWO_ECOMMERCE_ENABLED` as `true` in the staging configuration.
3. Load `inc/phase-two-ecommerce.php` from `functions.php` on the Phase Two branch.
4. Insert `rhn_phase_two_store_utility_row()` immediately after the approved Phase One header.
5. Insert the matching fifth tablet tab/panel and call `rhn_phase_two_store_footer_group()` in the footer grid.
6. Change the desktop footer grid from four navigation columns to five while preserving the approved Phase One intro column.
7. Validate the real WooCommerce markup before adding or modifying any template override.
8. Test desktop, tablet, and phone widths plus keyboard, focus, reduced-motion, empty/error, and cart-count behavior.

## Verification before deployment

- PHP syntax and WordPress coding safety pass.
- JavaScript syntax pass.
- No undefined WooCommerce calls when WooCommerce is inactive.
- Header and footer remain unchanged when the feature flag is absent or false.
- Shop, product, cart, checkout, account, and thank-you pages match the exact approved systems when the flag is enabled on staging.
- All links use real WordPress/WooCommerce routes, not in-page review anchors.

