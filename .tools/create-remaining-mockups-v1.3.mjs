import { readFile, writeFile } from 'node:fs/promises';

const root = process.cwd();

function replaceExact(source, from, to, label) {
  if (!source.includes(from)) throw new Error(`Missing ${label}`);
  return source.replace(from, to);
}

const commonCss = `.review-scope{margin:24px 0;padding:18px;display:grid;grid-template-columns:1fr 1fr;gap:14px;border:1px solid rgba(23,76,60,.13);border-radius:18px;background:rgba(255,255,255,.86);box-shadow:0 12px 28px rgba(23,76,60,.06)}.review-scope div{padding:14px;border-radius:12px;background:#f3f7f1;color:#5c7066;font-size:13px;line-height:1.5}.review-scope div:last-child{background:#fff7e5}.review-scope strong{display:block;margin-bottom:4px;color:var(--pine);font-size:13px}.btn:disabled{cursor:not-allowed;opacity:.52}.demo-feedback{margin-top:12px}.demo-feedback[hidden]{display:none!important}@media(max-width:720px){.review-scope{grid-template-columns:1fr}}`;

const scope = (now, later) => `<div class="review-scope"><div><strong>Review now</strong>${now}</div><div><strong>Confirm after connection</strong>${later}</div></div>`;

async function buildProduct() {
  let html = await readFile(`${root}/product-page-templates-v1.2.html`, 'utf8');
  html = html.replaceAll('v1.2', 'v1.3');
  html = replaceExact(html, '.divider{height:1px;background:rgba(23,76,60,.12)}', `.divider{height:1px;background:rgba(23,76,60,.12)}${commonCss}`, 'product common CSS hook');
  html = replaceExact(html, '.mini-product p{margin:0;color:#62756c;font-size:12px}', '.mini-product p{margin:0;color:#62756c;font-size:12px}.thumb{padding:6px;color:#5f7269;font-size:10px;font-weight:700;line-height:1.2}.main-image{position:relative}.image-caption{position:absolute;left:16px;bottom:16px;padding:7px 10px;border-radius:999px;background:rgba(255,255,255,.9);color:#63766d;font-size:11px;font-weight:700}.variation-options input:disabled+span{opacity:.65}', 'product enhancement CSS hook');
  html = replaceExact(html,
    '<p class="page-lede">One shared product-page system shown with a simple product and a selectable variation product.</p><div class="screen-tabs"',
    `<p class="page-lede">One shared product-page system shown with a simple product and a selectable variation product.</p>${scope('Approve the shared layout, gallery behavior, simple/variation controls, quantity, cautions, fulfillment note, and related-product placement.', 'Real product photos, descriptions, supplement facts, prices, variations, stock language, weights, and related-product rules wait for Revel/Kosmos data and client content approval.')}<div class="screen-tabs"`,
    'product review scope');
  html = html
    .replace('<button class="thumb" type="button" aria-current="true" aria-label="Front image"></button><button class="thumb" type="button" aria-current="false" aria-label="Supplement facts image"></button><button class="thumb" type="button" aria-current="false" aria-label="Ingredient image"></button>', '<button class="thumb" type="button" aria-current="true" aria-label="Front image" data-caption="Front label preview">Front</button><button class="thumb" type="button" aria-current="false" aria-label="Supplement facts image" data-caption="Supplement facts preview">Facts</button><button class="thumb" type="button" aria-current="false" aria-label="Ingredient image" data-caption="Ingredients preview">Ingredients</button>')
    .replace('<div class="placeholder-art main-image" aria-label="Product image placeholder"><div class="bottle" aria-hidden="true"></div></div>', '<div class="placeholder-art main-image" aria-label="Product image placeholder"><div class="bottle" aria-hidden="true"></div><span class="image-caption">Front label preview</span></div>')
    .replace('<button class="thumb" type="button" aria-current="true" aria-label="Front image"></button><button class="thumb" type="button" aria-current="false" aria-label="Back image"></button>', '<button class="thumb" type="button" aria-current="true" aria-label="Front image" data-caption="Front label preview">Front</button><button class="thumb" type="button" aria-current="false" aria-label="Back image" data-caption="Back label preview">Back</button>')
    .replace('<div class="placeholder-art main-image"><div class="bottle" aria-hidden="true"></div></div>', '<div class="placeholder-art main-image"><div class="bottle" aria-hidden="true"></div><span class="image-caption">Front label preview</span></div>')
    .replace('<label><input type="radio" name="count" value="$27.50 · 60 Count">60 Count — $27.50</label><label><input type="radio" name="count" value="$43.00 · 120 Count">120 Count — $43.00</label><label><input type="radio" name="count" value="Unavailable · 180 Count" disabled>180 Count — unavailable</label>', '<label><input type="radio" name="count" value="$27.50 · 60 Count"><span>60 Count — $27.50</span></label><label><input type="radio" name="count" value="$43.00 · 120 Count"><span>120 Count — $43.00</span></label><label><input type="radio" name="count" value="Unavailable · 180 Count" disabled><span>180 Count — unavailable</span></label>')
    .replace('<button class="btn add-demo" type="button">Add to Cart</button></div><p class="meta-line">Online orders ship from Clarkston', '<button class="btn add-demo" id="variation-add" type="button" disabled>Add to Cart</button></div><p class="meta-line">Online orders ship from Clarkston');
  html = replaceExact(html,
    "document.querySelectorAll('.thumb').forEach(thumb=>thumb.addEventListener('click',()=>{thumb.closest('.gallery').querySelectorAll('.thumb').forEach(item=>item.setAttribute('aria-current','false'));thumb.setAttribute('aria-current','true')}));",
    "document.querySelectorAll('.thumb').forEach(thumb=>thumb.addEventListener('click',()=>{const gallery=thumb.closest('.gallery');gallery.querySelectorAll('.thumb').forEach(item=>item.setAttribute('aria-current','false'));thumb.setAttribute('aria-current','true');gallery.querySelector('.image-caption').textContent=thumb.dataset.caption}));",
    'product gallery behavior');
  html = replaceExact(html,
    "document.querySelectorAll('input[name=\"count\"]').forEach(input=>input.addEventListener('change',()=>{document.querySelector('#variation-price').textContent=input.value;document.querySelector('#variation-status').textContent='Selected option is ready to add in this prototype.'}));document.querySelectorAll('.add-demo').forEach(button=>button.addEventListener('click',()=>{button.textContent='Added in Demo';setTimeout(()=>button.textContent='Add to Cart',1500)}));",
    "document.querySelectorAll('input[name=\"count\"]').forEach(input=>input.addEventListener('change',()=>{document.querySelector('#variation-price').textContent=input.value;document.querySelector('#variation-status').textContent='Selected option is ready to add in this prototype.';document.querySelector('#variation-add').disabled=false}));document.querySelectorAll('.add-demo').forEach(button=>button.addEventListener('click',()=>{if(button.disabled)return;button.textContent='Added in Demo';setTimeout(()=>button.textContent='Add to Cart',1500)}));",
    'product variation behavior');
  await writeFile(`${root}/product-page-templates-v1.3.html`, html);
}

async function buildPurchase() {
  let html = await readFile(`${root}/purchase-path-mockup-v1.2.html`, 'utf8');
  html = html.replaceAll('v1.2', 'v1.3');
  html = replaceExact(html, '.divider{height:1px;background:rgba(23,76,60,.12)}', `.divider{height:1px;background:rgba(23,76,60,.12)}${commonCss}`, 'purchase common CSS hook');
  html = replaceExact(html,
    '<p class="page-lede">The full purchase journey in one approval mockup, including empty, validation, and confirmation states.</p><div class="notice">',
    `<p class="page-lede">The full purchase journey in one approval mockup, including empty, validation, and confirmation states.</p>${scope('Approve the cart, empty-cart recovery, guest-checkout fields, validation, order review, and confirmation hierarchy.', 'Live shipping rates, tax, payment fields, wallet options, fraud rules, provider messages, emails, and final order totals wait for approved services and testing.')}<div class="notice">`,
    'purchase review scope');
  html = html
    .replace('<aside class="summary-card section-card"><h2>Order Summary</h2>', '<aside class="summary-card section-card" id="cart-summary"><h2>Order Summary</h2><p class="status-note" id="empty-summary-note" hidden>No items are in this demo cart.</p><div id="filled-summary">')
    .replace('<button class="btn go-step" data-step="checkout-screen" type="button">Proceed to Checkout</button><p class="status-note">No coupons, gift cards, loyalty, or store credit in the approved scope.</p></aside>', '<button class="btn go-step" id="checkout-continue" data-step="checkout-screen" type="button">Proceed to Checkout</button><p class="status-note">No coupons, gift cards, loyalty, or store credit in the approved scope.</p></div></aside>')
    .replace('id="cart-screen" role="tabpanel"', 'id="cart-screen" role="tabpanel" tabindex="-1"')
    .replace('id="checkout-screen" role="tabpanel" hidden', 'id="checkout-screen" role="tabpanel" tabindex="-1" hidden')
    .replace('id="confirmation-screen" role="tabpanel" hidden', 'id="confirmation-screen" role="tabpanel" tabindex="-1" hidden');
  html = replaceExact(html,
    "const cartItems=document.querySelector('#cart-items'),emptyCart=document.querySelector('#empty-cart'),toggleEmpty=document.querySelector('#toggle-empty');toggleEmpty.addEventListener('click',()=>{const show=emptyCart.hidden;emptyCart.hidden=!show;cartItems.hidden=show;toggleEmpty.textContent=show?'Restore Filled Cart':'Preview Empty Cart'});",
    "const cartItems=document.querySelector('#cart-items'),emptyCart=document.querySelector('#empty-cart'),toggleEmpty=document.querySelector('#toggle-empty'),filledSummary=document.querySelector('#filled-summary'),emptySummaryNote=document.querySelector('#empty-summary-note'),checkoutContinue=document.querySelector('#checkout-continue');toggleEmpty.addEventListener('click',()=>{const show=emptyCart.hidden;emptyCart.hidden=!show;cartItems.hidden=show;filledSummary.hidden=show;emptySummaryNote.hidden=!show;checkoutContinue.disabled=show;toggleEmpty.textContent=show?'Restore Filled Cart':'Preview Empty Cart'});",
    'purchase empty-cart behavior');
  await writeFile(`${root}/purchase-path-mockup-v1.3.html`, html);
}

async function buildAccount() {
  let html = await readFile(`${root}/customer-account-system-v1.2.html`, 'utf8');
  html = html.replaceAll('v1.2', 'v1.3');
  html = replaceExact(html, '.divider{height:1px;background:rgba(23,76,60,.12)}', `.divider{height:1px;background:rgba(23,76,60,.12)}${commonCss}`, 'account common CSS hook');
  html = replaceExact(html, '.detail-item strong{color:var(--pine)}', '.detail-item strong{color:var(--pine)}.address-editor{margin-top:18px;padding:18px;border:1px solid rgba(23,76,60,.13);border-radius:14px;background:#f8faf6}.address-editor .form-stack{margin-top:14px}.account-panel{scroll-margin-top:20px}', 'account enhancement CSS hook');
  html = replaceExact(html,
    '<p class="page-lede">Login, optional registration, recovery, dashboard, addresses, orders, and order detail within one consistent account pattern.</p><div class="notice">',
    `<p class="page-lede">Login, optional registration, recovery, dashboard, addresses, orders, and order detail within one consistent account pattern.</p>${scope('Approve guest access, optional-account presentation, recovery, dashboard, address editing, order history, and order-detail organization.', 'Authentication, email delivery, password policy, privacy/consent wording, customer data fields, order sync, and retention rules wait for the final WordPress and commerce configuration.')}<div class="notice">`,
    'account review scope');
  html = html
    .replace('<button class="btn" type="button">Sign In</button>', '<button class="btn" type="button" data-demo-feedback="sign-in-feedback">Sign In</button><p class="status-note demo-feedback" id="sign-in-feedback" hidden>Demo only — no credentials were submitted.</p>')
    .replace('<button class="btn secondary" type="button">Create Account</button>', '<button class="btn secondary" type="button" data-demo-feedback="register-feedback">Create Account</button><p class="status-note demo-feedback" id="register-feedback" hidden>Demo only — no account was created.</p>')
    .replaceAll('<button class="btn secondary" type="button">Edit Address</button>', '<button class="btn secondary address-edit" type="button">Edit Address</button>')
    .replace('</div></div></section><section class="account-panel section-card" id="account-reset" hidden>', '</div><div class="address-editor" id="address-editor" hidden><h3>Edit Address Demo</h3><p class="muted">This local form demonstrates the proposed editing pattern and stores nothing.</p><div class="form-stack"><div class="field"><label for="address-line">Street address</label><input id="address-line" value="123 Example Street"></div><div class="field"><label for="address-city">City</label><input id="address-city" value="Clarkston"></div><div class="flow-actions"><button class="btn" id="address-save" type="button">Save Demo Address</button><button class="btn secondary" id="address-cancel" type="button">Cancel</button></div><p class="status-note demo-feedback" id="address-feedback" hidden>Demo saved locally for this preview only.</p></div></div></div></section><section class="account-panel section-card" id="account-reset" hidden>')
    .replace('<button class="btn" type="button">Send Reset Link</button>', '<button class="btn" type="button" data-demo-feedback="reset-feedback">Send Reset Link</button><p class="status-note demo-feedback" id="reset-feedback" hidden>If this were a real account, a generic confirmation would appear without revealing account status.</p>');
  html = replaceExact(html,
    "const accountButtons=[...document.querySelectorAll('[data-account]')],accountPanels=[...document.querySelectorAll('.account-panel')];function showAccount(id){accountPanels.forEach(panel=>panel.hidden=panel.id!==id);document.querySelectorAll('.account-nav button').forEach(button=>button.setAttribute('aria-selected',String(button.dataset.account===id)));document.querySelector('#'+id)?.scrollIntoView({behavior:'smooth',block:'start'})}accountButtons.forEach(button=>button.addEventListener('click',()=>showAccount(button.dataset.account)));",
    "const accountButtons=[...document.querySelectorAll('[data-account]')],accountPanels=[...document.querySelectorAll('.account-panel')];function showAccount(id){accountPanels.forEach(panel=>panel.hidden=panel.id!==id);document.querySelectorAll('.account-nav button').forEach(button=>button.setAttribute('aria-selected',String(button.dataset.account===id)));document.querySelector('#'+id)?.scrollIntoView({behavior:'smooth',block:'start'})}accountButtons.forEach(button=>button.addEventListener('click',()=>showAccount(button.dataset.account)));document.querySelectorAll('[data-demo-feedback]').forEach(button=>button.addEventListener('click',()=>{document.querySelector('#'+button.dataset.demoFeedback).hidden=false}));const addressEditor=document.querySelector('#address-editor');document.querySelectorAll('.address-edit').forEach(button=>button.addEventListener('click',()=>{addressEditor.hidden=false;addressEditor.scrollIntoView({behavior:'smooth',block:'nearest'})}));document.querySelector('#address-cancel').addEventListener('click',()=>addressEditor.hidden=true);document.querySelector('#address-save').addEventListener('click',()=>document.querySelector('#address-feedback').hidden=false);",
    'account demo behaviors');
  await writeFile(`${root}/customer-account-system-v1.3.html`, html);
}

async function buildStates() {
  let html = await readFile(`${root}/store-states-components-v1.2.html`, 'utf8');
  html = html.replaceAll('v1.2', 'v1.3');
  html = replaceExact(html, '.divider{height:1px;background:rgba(23,76,60,.12)}', `.divider{height:1px;background:rgba(23,76,60,.12)}${commonCss}`, 'states common CSS hook');
  html = replaceExact(html,
    '<p class="page-lede">Reusable recovery patterns for empty, unavailable, loading, missing, and error conditions across the store.</p><div class="states-grid">',
    `<p class="page-lede">Reusable recovery patterns for empty, unavailable, loading, missing, and error conditions across the store.</p>${scope('Approve the visual hierarchy, recovery actions, missing/loading treatments, mobile filter drawer, and accessibility/responsive checklist.', 'Exact inventory, shipping, tax, payment-provider, authentication, and integration-error messages wait for the connected services and real test responses.')}<div class="states-grid">`,
    'states review scope');
  html = html
    .replace('<div class="drawer-backdrop" id="state-drawer" hidden><aside class="filter-drawer">', '<div class="drawer-backdrop" id="state-drawer" hidden><aside class="filter-drawer" role="dialog" aria-modal="true" aria-labelledby="drawer-title">')
    .replace('<h2 class="section-heading">Filter Products</h2>', '<h2 class="section-heading" id="drawer-title">Filter Products</h2>');
  html = replaceExact(html,
    "const stateDrawer=document.querySelector('#state-drawer');function toggleStateDrawer(open){stateDrawer.hidden=!open;stateDrawer.classList.toggle('open',open);document.body.style.overflow=open?'hidden':''}document.querySelector('#state-drawer-open').addEventListener('click',()=>toggleStateDrawer(true));document.querySelector('#state-drawer-close').addEventListener('click',()=>toggleStateDrawer(false));document.querySelector('#state-drawer-apply').addEventListener('click',()=>toggleStateDrawer(false));stateDrawer.addEventListener('click',event=>{if(event.target===stateDrawer)toggleStateDrawer(false)});",
    "const stateDrawer=document.querySelector('#state-drawer'),drawerOpen=document.querySelector('#state-drawer-open'),drawerClose=document.querySelector('#state-drawer-close');function toggleStateDrawer(open){stateDrawer.hidden=!open;stateDrawer.classList.toggle('open',open);document.body.style.overflow=open?'hidden':'';(open?drawerClose:drawerOpen).focus()}drawerOpen.addEventListener('click',()=>toggleStateDrawer(true));drawerClose.addEventListener('click',()=>toggleStateDrawer(false));document.querySelector('#state-drawer-apply').addEventListener('click',()=>toggleStateDrawer(false));stateDrawer.addEventListener('click',event=>{if(event.target===stateDrawer)toggleStateDrawer(false)});document.addEventListener('keydown',event=>{if(event.key==='Escape'&&!stateDrawer.hidden)toggleStateDrawer(false)});",
    'states drawer behavior');
  await writeFile(`${root}/store-states-components-v1.3.html`, html);
}

await buildProduct();
await buildPurchase();
await buildAccount();
await buildStates();
console.log('Created four v1.3 mockups without modifying v1.2 history.');
