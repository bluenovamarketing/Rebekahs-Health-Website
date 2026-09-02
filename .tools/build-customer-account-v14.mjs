import fs from "node:fs/promises";

const sourcePath = "customer-account-system-v1.3.html";
const targetPath = "customer-account-system-v1.4.html";

let html = await fs.readFile(sourcePath, "utf8");

const replaceOnce = (from, to, label) => {
  if (from instanceof RegExp) {
    if (!from.test(html)) throw new Error(`Missing expected ${label}`);
    html = html.replace(from, to);
    return;
  }
  if (!html.includes(from)) throw new Error(`Missing expected ${label}`);
  html = html.replace(from, to);
};

replaceOnce(
  '<meta name="description" content="Page-body-only local Phase Two ecommerce review mockup for Rebekah\'s Health & Nutrition.">',
  '<meta name="description" content="Page-body-only Phase Two customer account review for Rebekah\'s Health & Nutrition.">',
  "meta description",
);
replaceOnce(
  "<title>Customer Account System Body v1.3 | Rebekah's Health & Nutrition</title>",
  "<title>Customer Account System Body v1.4 | Rebekah's Health & Nutrition</title>",
  "document title",
);
replaceOnce(
  ".account-panel{scroll-margin-top:20px}",
  ".account-panel{scroll-margin-top:20px}.expectation-grid{margin:24px 0;display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}.expectation-card{padding:20px;border:1px solid rgba(23,76,60,.13);border-radius:17px;background:#fff;box-shadow:0 12px 28px rgba(23,76,60,.06)}.expectation-card span{display:block;margin-bottom:8px;color:var(--berry);font-size:11px;font-weight:800;letter-spacing:.09em;text-transform:uppercase}.expectation-card h2{margin:0 0 8px;color:var(--pine);font-family:Georgia,serif;font-size:24px;font-weight:500}.expectation-card p{margin:0;color:#5f7269;font-size:14px;line-height:1.55}",
  "expectation card styles",
);
replaceOnce(
  "@media(max-width:860px){.account-layout{grid-template-columns:1fr}",
  "@media(max-width:860px){.expectation-grid{grid-template-columns:1fr}.account-layout{grid-template-columns:1fr}",
  "responsive expectation layout",
);
replaceOnce(
  ".demo-feedback{margin-top:12px}.demo-feedback[hidden]{display:none!important}",
  ".preview-feedback{margin-top:12px}.preview-feedback[hidden]{display:none!important}",
  "preview feedback class",
);
replaceOnce(
  "Customer Account System · v1.3 · Page Body Only · Internal Review · Local Only",
  "Customer Account System · v1.4 · Page Body Only · Internal Review · Local Only",
  "version chip",
);
replaceOnce(
  '<p class="page-kicker">System 06 · Reusable account shell</p>',
  '<p class="page-kicker">Customer account access</p>',
  "page kicker",
);
replaceOnce(
  "Login, optional registration, recovery, dashboard, addresses, orders, and order detail within one consistent account pattern.",
  "Guest checkout stays available. Customers may create an account for saved addresses, order history, and convenient self-service access.",
  "page introduction",
);
replaceOnce(
  '<div class="review-scope"><div><strong>Review now</strong>Approve guest access, optional-account presentation, recovery, dashboard, address editing, order history, and order-detail organization.</div><div><strong>Confirm after connection</strong>Authentication, email delivery, password policy, privacy/consent wording, customer data fields, order sync, and retention rules wait for the final WordPress and commerce configuration.</div></div>',
  '<div class="review-scope"><div><strong>Review now</strong>Approve the customer experience: guest checkout, optional self-service account creation, sign-in, recovery, dashboard, addresses, orders, and order detail. Staff never approve individual customer accounts.</div><div><strong>Confirm during connection</strong>Email delivery, privacy and consent wording, retention rules, order syncing, and password security will be configured and tested before launch.</div></div><section class="expectation-grid" aria-label="Account system expectations"><article class="expectation-card"><span>Customer approval</span><h2>Automatic, not manual</h2><p>Customers create their own accounts and receive the normal email and password setup flow. Rebekah\'s staff will not approve accounts one by one.</p></article><article class="expectation-card"><span>Expected software cost</span><h2>No separate account plugin planned</h2><p>Guest checkout and the standard My Account features are included with WooCommerce core. Any advanced paid extension would require a separate quote and approval.</p></article><article class="expectation-card"><span>Client management</span><h2>Low routine workload</h2><p>Customers handle sign-in, password resets, and addresses. Staff help with occasional access or order questions and receive focused training plus a written handoff checklist.</p></article></section>',
  "review and expectation guidance",
);
replaceOnce(
  '<div class="notice"><span>ⓘ</span><div><strong>Privacy boundary:</strong> all account screens are proposed as noindex. This mockup does not store or submit personal information.</div></div>',
  '<div class="notice"><span>ⓘ</span><div><strong>Privacy boundary:</strong> account screens will be set to noindex. The forms in this review do not submit personal information.</div></div>',
  "privacy boundary",
);
replaceOnce(
  "Customers can check out as guests; creating an account is optional and remains subject to approval.",
  "Customers can check out without an account. After checkout, they may create an optional account to save addresses and view order history.",
  "guest checkout explanation",
);
replaceOnce(
  '<div class="auth-block"><h3>Create an Optional Account</h3>',
  '<div class="auth-block"><h3>Create an Optional Account</h3><p class="muted">No staff approval is required. The customer completes the normal email and password setup.</p>',
  "automatic account creation explanation",
);
replaceOnce(/data-demo-feedback/g, "data-preview-feedback", "preview feedback attributes");
replaceOnce(/demo-feedback/g, "preview-feedback", "preview feedback classes");
replaceOnce(/dataset\.demoFeedback/g, "dataset.previewFeedback", "preview feedback script");
replaceOnce("Demo only — no credentials were submitted.", "Preview only — no credentials were submitted.", "sign-in feedback");
replaceOnce("Demo only — no account was created.", "Preview only — no account was created.", "registration feedback");
replaceOnce("Welcome back, Demo Customer", "Welcome back", "dashboard greeting");
replaceOnce("Account screens use demo data only. No customer record exists.", "Sample information is shown for review. No customer record exists.", "dashboard review note");
replaceOnce("Demo order history with clear status and detail access.", "Order history with clear status and detail access.", "order history description");
replaceOnce(/RPL-DEMO-/g, "RPL-", "sample order numbers");
replaceOnce("Placed August 30, 2026 · Processing · Demo data", "Placed August 30, 2026 · Processing", "order detail description");
replaceOnce("Demo Customer<br>123 Example Street<br>Clarkston, MI 48346", "Sample Customer<br>123 Example Street<br>Sample City, MI 48346", "sample address");
replaceOnce("<h3>Edit Address Demo</h3>", "<h3>Edit Address</h3>", "address editor title");
replaceOnce("This local form demonstrates the proposed editing pattern and stores nothing.", "This form previews the address-editing pattern and stores nothing.", "address editor description");
replaceOnce('value="Clarkston"', 'value="Sample City"', "address city");
replaceOnce("Save Demo Address", "Save Address", "address save button");
replaceOnce("Demo saved locally for this preview only.", "Address update previewed. No information was stored.", "address feedback");

if (/clarkston|\bdemo\b|prototype|pilot/i.test(html)) {
  throw new Error("Clarkston, demo, prototype, or pilot wording remains in v1.4");
}
if ((html.match(/class="account-panel section-card"/g) || []).length !== 6) throw new Error("All six account panels must remain");
if ((html.match(/data-account="account-/g) || []).length < 6) throw new Error("Account navigation or interactions were lost");
if (!html.includes("guest checkout") || !html.includes("Staff never approve individual customer accounts")) {
  throw new Error("Required guest checkout and automatic account guidance is missing");
}
if (/<header\b|<footer\b/i.test(html)) throw new Error("Page-body mockup must not include global chrome");

await fs.writeFile(targetPath, html, "utf8");
console.log(`Built ${targetPath}`);
