import { readFile, writeFile } from 'node:fs/promises';

const sourcePath = 'main-homepage-ecommerce-integration-v1.5.html';
const outputPath = 'main-homepage-ecommerce-integration-v1.6.html';
let html = await readFile(sourcePath, 'utf8');

function replaceExact(from, to, label) {
  if (!html.includes(from)) throw new Error(`Missing ${label}`);
  html = html.replace(from, to);
}

html = html.replaceAll('v1.5', 'v1.6');

replaceExact(
  '.phase-shop-heading{display:grid;grid-template-columns:1.15fr .85fr;gap:clamp(40px,7vw,90px);align-items:end}.phase-shop-heading h2{max-width:720px;margin:14px 0 0;color:#174c3c}.phase-shop-heading p{margin:0;color:#5c7066;font-size:17px;line-height:1.7}.phase-shop-actions{margin-top:24px;display:flex;align-items:center;flex-wrap:wrap;gap:18px}.phase-shop-primary{background:#174c3c!important;color:#fff!important}',
  '.phase-shop-heading{display:grid;grid-template-columns:1.15fr .85fr;gap:clamp(40px,7vw,90px);align-items:end}.phase-shop-heading h2{max-width:720px;margin:14px 0 0;color:#174c3c}.phase-shop-cta{display:flex;align-items:flex-end;justify-content:flex-start}.phase-shop-actions{display:flex;align-items:center;flex-wrap:wrap;gap:18px}.phase-shop-primary{background:#174c3c!important;color:#fff!important}',
  'shop heading CSS'
);

replaceExact(
  '.phase-shop-paths{margin-top:46px;display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}.phase-shop-paths a{min-height:154px;padding:25px;display:flex;flex-direction:column;border:1px solid rgba(23,76,60,.12);border-radius:20px;background:#fff;box-shadow:0 12px 30px rgba(23,76,60,.07);transition:transform .2s ease,box-shadow .2s ease}.phase-shop-paths a:hover{transform:translateY(-4px);box-shadow:0 18px 38px rgba(23,76,60,.12)}.phase-shop-paths span{color:#8e4b61;font-size:11px;font-weight:800;letter-spacing:.12em}.phase-shop-paths strong{margin:22px 0 7px;color:#174c3c;font-family:"Fraunces",serif;font-size:24px}.phase-shop-paths small{color:#667970;font-size:13px;line-height:1.5}',
  '.phase-shop-paths{margin-top:46px;display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}.phase-shop-paths a{min-height:296px;padding:0;display:flex;flex-direction:column;overflow:hidden;border:1px solid rgba(23,76,60,.12);border-radius:20px;background:#fff;box-shadow:0 12px 30px rgba(23,76,60,.07);transition:transform .2s ease,box-shadow .2s ease}.phase-shop-paths a:hover{transform:translateY(-4px);box-shadow:0 18px 38px rgba(23,76,60,.12)}.phase-path-image{height:148px;flex:0 0 148px;background-image:url("assets/phase-two-homepage/ecommerce-shopping-paths-v1.1.png");background-repeat:no-repeat;background-size:300% 100%}.phase-shop-paths a:nth-child(1) .phase-path-image{background-position:left center}.phase-shop-paths a:nth-child(2) .phase-path-image{background-position:center center}.phase-shop-paths a:nth-child(3) .phase-path-image{background-position:right center}.phase-path-copy{padding:20px 23px 22px;display:flex;flex:1;flex-direction:column}.phase-path-number{color:#8e4b61;font-size:11px;font-weight:800;letter-spacing:.12em}.phase-shop-paths strong{margin:13px 0 7px;color:#174c3c;font-family:"Fraunces",serif;font-size:24px}.phase-shop-paths small{color:#667970;font-size:13px;line-height:1.5}',
  'shopping path card CSS'
);

replaceExact(
  '<div><span class="kicker">Rebekah\'s Online Store</span><h2 id="online-store-title">Shop a focused collection with the guidance you already trust.</h2></div>\n          <div><p>A first online collection of Rebekah\'s private-label supplements, fulfilled by the Clarkston team with clear handling and shipping expectations.</p><div class="phase-shop-actions"><a class="pill phase-shop-primary" href="#phase-featured">Shop Pilot Products</a><a class="text-link" href="#phase-categories">Browse by category →</a></div></div>',
  '<div><span class="kicker">Rebekah\'s Online Store</span><h2 id="online-store-title">Shop wellness essentials with the guidance you already trust.</h2></div>\n          <div class="phase-shop-cta"><div class="phase-shop-actions"><a class="pill phase-shop-primary" href="#phase-featured">Shop All Products</a><a class="text-link" href="#phase-categories">Browse by category →</a></div></div>',
  'shopping section heading and CTA'
);

replaceExact(
  '<a href="#phase-featured"><span>01</span><strong>Pilot Collection</strong><small>Start with the initial online assortment.</small></a>\n          <a href="#phase-featured"><span>02</span><strong>Shop by Category</strong><small>Browse energy, immune, stress and brain support.</small></a>\n          <a href="tel:12488432011"><span>03</span><strong>Ask Clarkston</strong><small>Call (248) 843-2011 before ordering.</small></a>',
  '<a href="#phase-featured"><span class="phase-path-image" aria-hidden="true"></span><span class="phase-path-copy"><span class="phase-path-number">01</span><strong>Shop All Products</strong><small>Explore supplements, natural products and everyday wellness essentials.</small></span></a>\n          <a href="#phase-featured"><span class="phase-path-image" aria-hidden="true"></span><span class="phase-path-copy"><span class="phase-path-number">02</span><strong>Shop by Category</strong><small>Browse vitamins, herbs, healthy foods, body care and more.</small></span></a>\n          <a href="tel:12488432011"><span class="phase-path-image" aria-hidden="true"></span><span class="phase-path-copy"><span class="phase-path-number">03</span><strong>Ask Rebekah\'s Team</strong><small>Call (248) 843-2011 for friendly product guidance.</small></span></a>',
  'shopping path content'
);

replaceExact(
  '<div class="phase-featured-head" id="phase-featured"><div><span class="kicker">Featured pilot products</span><h3>Rebekah\'s private-label favorites.</h3></div><p>Representative names and prices come from the pilot product export. Final photography and product-page details remain pending.</p></div>',
  '<div class="phase-featured-head" id="phase-featured"><div><span class="kicker">Featured Products</span><h3>Wellness favorites, thoughtfully selected.</h3></div></div>',
  'featured products heading'
);

replaceExact(
  '        <p class="phase-product-note">Mockup note: product photography, descriptions, weights, inventory and final taxonomy still require source-data confirmation.</p>\n',
  '',
  'mockup product note'
);

if (/pilot|fulfilled by the Clarkston|handling and shipping|Ask Clarkston|private-label favorites|Representative names|Mockup note/i.test(html.slice(html.indexOf('<section class="phase-two-shop'), html.indexOf('</section>', html.indexOf('<section class="phase-two-shop'))))) {
  throw new Error('A removed ecommerce-section phrase remains.');
}

await writeFile(outputPath, html);
console.log(`Created ${outputPath} from ${sourcePath}; v1.5 remains unchanged.`);
