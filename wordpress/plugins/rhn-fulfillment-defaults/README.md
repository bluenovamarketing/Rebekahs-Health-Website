# RHN Fulfillment Defaults

Current version: 1.0.5

Adds reversible staff defaults to WooCommerce Shipping's order-label modal:

- defaults **Residential address** to checked for each newly opened label modal;
- adds approved Small Box (`7.25 × 7.25 × 6.5 in`, `6 oz` tare) and Large Box (`12.25 × 12 × 8.5 in`, `12 oz` tare) shortcut buttons;
- adds the chosen box tare to WooCommerce Shipping's product-weight total;
- selects USPS Priority Mail when that rate is first displayed;
- leaves every default editable by staff.

The helper never requests rates, purchases postage, marks an order complete, changes a customer record, or sends a notification.

Staff must still confirm the actual packed box and scale weight before purchasing a label. Uncheck **Residential address** for a business destination and choose a different service when the shipment requires it.

