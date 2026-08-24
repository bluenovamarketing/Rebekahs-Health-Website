# Phase Two: What Can Start Now

Reviewed: 2026-08-21

## Verified current position

- The former Cloudways staging application was promoted to the production domain. Its old Cloudways URL redirects to the live website, so Phase Two does **not** currently have an independent staging workspace.
- The current Cloudways server is recorded as DigitalOcean Premium with 2 GB RAM and 1 vCPU. A new staging application on that server will share its CPU, memory, disk, and services with production.
- Blue Nova has WordPress and Cloudways access.
- Revel integration access, a client-owned Kosmos account/access, the USPS live-rate extension, and the Fiserv/Clover merchant integration are not yet available.
- WooCommerce and retained legacy commerce data exist, but the production commerce stack is intentionally inactive.

## Work Blue Nova can do now without hosting changes

- Build local responsive ecommerce mockups and adaptable storefront components in the current custom theme using controlled sample data:
  - Shop/catalog layout.
  - Product-card component.
  - Product-detail layout.
  - Cart, checkout, account, and thank-you styling.
  - Store navigation, search, filter controls, responsive layout, and empty/error states.
- Prepare a proposed field-mapping worksheet for review once Revel access and the first test product arrive.

Do not create the new staging clone or increase the Cloudways server merely to hold an idle Phase Two environment. Wait until Revel integration access, Kosmos account access, at least one prepared online-enabled product, and a usable Blue Nova working window are ready.

## Ecommerce design approval set

Phase One intentionally excluded the WooCommerce shop, product, cart, checkout, and account screens. The approved site header, footer, typography, colors, spacing, and general visual system should be reused; Phase Two does not need another full-site redesign.

Before full storefront implementation, Blue Nova should prepare one compact responsive ecommerce approval set:

1. **Global commerce navigation:** the existing header/footer with the proposed Online Shop entry, cart/count, account access, and required store-policy links. This is a focused modification, not a new header/footer design.
2. **Shop/category system:** one desktop and one mobile catalog view showing the category heading, product grid, product card, search, sorting, filters, pagination/load-more behavior, and empty/no-results state. Product-category, brand, and collection archives should reuse this system rather than receive separate visual designs.
3. **Product detail:** responsive simple-product and variation-product examples showing images, price, stock status, quantity, add-to-cart, product information, warnings/label information, shipping note, and related products.
4. **Purchase path:** compact cart, checkout, order-confirmation, account/login, and password-reset wireframes or staging prototypes. These do not each require a separate polished static mockup if they follow the approved system.
5. **State/component sheet:** sale/out-of-stock/backorder presentation if applicable, missing-image fallback, validation/error messages, loading state, and mobile filter drawer.

The first approval version can use controlled representative sample data. Final card fields, taxonomy labels, filters, variations, and related-product rules must be adjusted after Revel/Kosmos exposes the first prepared product and representative pilot data. Approve the design system before styling all 25 products.

## Work that can be scaffolded but not finalized

- Product cards can be built, but their final fields and edge cases depend on what Revel/Kosmos actually supplies.
- Category, brand, attribute, filter, collection, and related-product structures can be proposed, but cannot be finalized until the 25 products and Revel categories are visible.
- Guest checkout, optional accounts, order emails, backorders, and the inventory buffer are native WooCommerce settings and can be entered as provisional staging defaults after client approval. Inventory behavior still must be validated against Kosmos/Revel.
- Generic checkout can be styled and tested, but payment, shipping-rate, tax, inventory, order-transfer, refund, and failure-path acceptance tests remain incomplete.

## Work blocked by missing access or decisions

- **Revel access and prepared products:** verified product/category/field mapping, online-product eligibility, weights, prices, inventory, variations, and the intended 25-product list.
- **Kosmos account/access:** the first-product handshake, product/inventory synchronization, overwrite behavior, error logs, WooCommerce-to-Revel orders, and location-specific inventory reduction.
- **USPS extension/account connection:** live address-dependent rates and representative multi-item shipment tests.
- **Fiserv/Clover access:** card authorization, successful/failed payments, voids, refunds, and production payment configuration.
- **Client approval:** tax treatment, PO-box delivery, operational email, guest/account choice, backorders, inventory buffer, and whether coupons, gift certificates, store credit, or loyalty are included.

## Staging and server recommendation

1. **Do not upgrade or create staging yet.** There is no productive integration work to perform until Revel and Kosmos access plus a prepared test product are available, and an idle environment provides no benefit.
2. When the prerequisites and a workable build window are ready, scale CPU/RAM from 2 GB/1 vCPU to 4 GB/2 vCPU immediately before creating the clone. The live application previously saturated the 2 GB server, and clone creation plus same-server staging activity could compete with production.
3. Take a fresh production restore point and create the staging clone on the existing Cloudways server. Cloudways Flexible does not charge an extra application fee for staging on an existing server; the added cost is the temporary server-size increase.
4. Protect staging, audit WooCommerce, build and test there, and keep 4 GB through launch and at least the first 30 days of ecommerce stabilization.
5. Review actual CPU, free memory, PHP workers, database load, and checkout response time after the stabilization period. If usage is consistently low, Cloudways/DigitalOcean permits returning to the original server size when only CPU/RAM was scaled. Do not increase storage as part of the temporary scale if easy reversal is important.

A separate staging server would isolate production more completely, but it would add a second server charge. It is not necessary for the 25-product pilot if the shared server is scaled and heavy work is scheduled carefully.

## Keeping production blogs and events during the build

- Clone the current production site at Phase Two kickoff so all current posts, events, media, forms, and settings are included.
- Track posts, events, and media added or changed on production after the clone date.
- Before launch, take fresh backups and use a short editorial freeze.
- Move the production content changes into staging selectively, complete final QA, and then deploy the Phase Two code and carefully selected database changes.
- Do **not** overwrite the entire production database with the staging database. Cloudways Push/Pull can move files and selected database tables, but it is not an automatic two-way WordPress content merge. A full overwrite could discard newer posts, events, form records, users, or other live data.

## Practical start boundary

Blue Nova can prepare local storefront mockups and code scaffolding now without changing hosting. Do not start the protected staging clone or server upgrade until Revel and Kosmos access, one prepared online-enabled product, and a usable working window are available. The first product synchronization is conditional on those prerequisites; it is not currently available. Final checkout acceptance cannot occur until USPS and Fiserv/Clover are connected.
