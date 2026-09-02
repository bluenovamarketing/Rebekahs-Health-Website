import fs from "node:fs/promises";

const sourcePath = "purchase-path-mockup-v1.3.html";
const targetPath = "purchase-path-mockup-v1.4.html";

let html = await fs.readFile(sourcePath, "utf8");

const replaceOnce = (from, to, label) => {
  if (!html.includes(from)) throw new Error(`Missing expected ${label}`);
  html = html.replace(from, to);
};

replaceOnce(
  '<meta name="description" content="Page-body-only local Phase Two ecommerce review mockup for Rebekah\'s Health & Nutrition.">',
  '<meta name="description" content="Page-body-only Phase Two ecommerce purchase-flow review for Rebekah\'s Health & Nutrition.">',
  "meta description",
);
replaceOnce(
  "<title>Purchase Path Body v1.3 | Rebekah's Health & Nutrition</title>",
  "<title>Purchase Path Body v1.4 | Rebekah's Health & Nutrition</title>",
  "document title",
);
replaceOnce(
  ".demo-feedback{margin-top:12px}.demo-feedback[hidden]{display:none!important}",
  ".flow-feedback{margin-top:12px}.flow-feedback[hidden]{display:none!important}",
  "unused demo feedback class",
);
replaceOnce(
  ".screen-panel[hidden]{display:none!important}",
  ".screen-panel[hidden]{display:none!important}.screen-panel:focus{outline:2px solid rgba(214,163,58,.38);outline-offset:4px;border-radius:18px}",
  "focused purchase panel styling",
);
replaceOnce(
  "Purchase Path · v1.3 · Page Body Only · Internal Review · Local Only",
  "Purchase Path · v1.4 · Page Body Only · Internal Review · Local Only",
  "version chip",
);
replaceOnce(
  "<p class=\"page-kicker\">System 05 · Connected prototype</p>",
  "<p class=\"page-kicker\">Secure online checkout</p>",
  "page kicker",
);
replaceOnce(
  "The full purchase journey in one approval mockup, including empty, validation, and confirmation states.",
  "The complete purchase journey, including cart recovery, checkout validation, order review, and confirmation.",
  "page introduction",
);
replaceOnce(
  '<div class="notice"><span>ⓘ</span><div><strong>Local prototype only:</strong> no payment, customer data, inventory, tax, shipping quote, or order is processed.</div></div>',
  '<div class="notice"><span>ⓘ</span><div><strong>Review note:</strong> this preview does not collect payment, submit customer data, or place an order.</div></div>',
  "review notice",
);
replaceOnce("Preview Empty Cart", "Show Empty Cart", "empty cart control");
replaceOnce("No items are in this demo cart.", "No items are in your cart.", "empty cart summary");
replaceOnce("Place Demo Order", "Place Order", "place order button");
replaceOnce(
  '<span class="mock-badge">Prototype confirmation</span>',
  '<span class="mock-badge">Order confirmed</span>',
  "confirmation badge",
);
replaceOnce(
  "A receipt would be sent to the customer after a real order. This local mockup does not collect payment, submit data, or create an order.",
  "Your receipt will be emailed to the address provided at checkout.",
  "confirmation message",
);
replaceOnce(
  '<div><strong>Demo order</strong>RPL-DEMO-1042</div><div><strong>Fulfillment</strong>Ships from Clarkston</div><div><strong>Packing</strong>Allow two business days</div>',
  '<div><strong>Order number</strong>RPL-1042</div><div><strong>Order status</strong>Confirmed</div><div><strong>Need help?</strong>(248) 843-2011</div>',
  "confirmation details",
);
replaceOnce("Back to Cart Demo", "Back to Cart", "back to cart button");
replaceOnce(
  "toggleEmpty.textContent=show?'Restore Filled Cart':'Preview Empty Cart'",
  "toggleEmpty.textContent=show?'Restore Cart':'Show Empty Cart'",
  "empty cart interaction labels",
);

if (/clarkston|\bdemo\b|prototype|pilot/i.test(html)) {
  throw new Error("Clarkston, demo, prototype, or pilot wording remains in v1.4");
}
if ((html.match(/class="screen-panel"/g) || []).length !== 3) throw new Error("Cart, checkout, and confirmation panels must remain");
if ((html.match(/class="journey-step"/g) || []).length !== 3) throw new Error("All three journey controls must remain");
if (!html.includes('id="checkout-form"') || !html.includes('id="toggle-empty"') || !html.includes('id="confirmation-screen"')) {
  throw new Error("A required purchase-flow interaction was lost");
}
if (/<header\b|<footer\b/i.test(html)) throw new Error("Page-body mockup must not include global chrome");

await fs.writeFile(targetPath, html, "utf8");
console.log(`Built ${targetPath}`);
