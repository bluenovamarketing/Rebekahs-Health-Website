import fs from 'node:fs';

const sourcePath = 'third-mockup-v3.1.23.html';
const outputPath = 'online-store-homepage-mockup-v1.3.html';
const source = fs.readFileSync(sourcePath, 'utf8');

const sourceMain = source.match(/  <main id="main">[\s\S]*?\n  <\/main>/)?.[0];
if (!sourceMain) throw new Error('The approved live-homepage source has no main body.');

const storeSection = `
    <section class="phase-two-shop section" id="online-store" data-mock-section="3b" aria-labelledby="online-store-title">
      <div class="phase-version">Phase Two · Homepage v1.3 · Page body only</div>
      <div class="phase-shop-wrap">
        <div class="phase-shop-heading">
          <div><span class="kicker">Rebekah's Online Store</span><h2 id="online-store-title">Shop a focused collection with the guidance you already trust.</h2></div>
          <div><p>A first online collection of Rebekah's private-label supplements, fulfilled by the Clarkston team with clear handling and shipping expectations.</p><div class="phase-shop-actions"><a class="pill phase-shop-primary" href="#phase-featured">Shop Pilot Products</a><a class="text-link" href="#phase-categories">Browse by category →</a></div></div>
        </div>
        <div class="phase-shop-paths" id="phase-categories" aria-label="Online shopping paths">
          <a href="#phase-featured"><span>01</span><strong>Pilot Collection</strong><small>Start with the initial online assortment.</small></a>
          <a href="#phase-featured"><span>02</span><strong>Shop by Category</strong><small>Browse energy, immune, stress and brain support.</small></a>
          <a href="tel:12488432011"><span>03</span><strong>Ask Clarkston</strong><small>Call (248) 843-2011 before ordering.</small></a>
        </div>
        <div class="phase-featured-head" id="phase-featured"><div><span class="kicker">Featured pilot products</span><h3>Rebekah's private-label favorites.</h3></div><p>Representative names and prices come from the pilot product export. Final photography and product-page details remain pending.</p></div>
        <div class="phase-product-grid">
          <article class="phase-product"><div class="phase-bottle"><span>Rebekah's</span><strong>Energy</strong><small>90 capsules</small></div><p>Energy &amp; Vitality</p><h4>Rebekah's Energy, 90 Caps</h4><b>$24.00</b><button type="button" data-mock-add>Add to cart</button></article>
          <article class="phase-product"><div class="phase-bottle"><span>Rebekah's</span><strong>NAC</strong><small>1000 mg</small></div><p>Immune Support</p><h4>Rebekah's NAC 1000 mg</h4><b>$27.00</b><button type="button" data-mock-add>Add to cart</button></article>
          <article class="phase-product"><div class="phase-bottle"><span>Rebekah's</span><strong>L-Theanine</strong><small>90 count</small></div><p>Stress &amp; Sleep</p><h4>Rebekah's L-Theanine, 90 Ct</h4><b>$20.50</b><button type="button" data-mock-add>Add to cart</button></article>
          <article class="phase-product"><div class="phase-bottle"><span>Rebekah's</span><strong>Lion's Mane</strong><small>60 count</small></div><p>Brain &amp; Cognitive</p><h4>Rebekah's Lion's Mane COG, 60 Ct</h4><b>$21.50</b><button type="button" data-mock-add>Add to cart</button></article>
        </div>
        <p class="phase-product-note">Mockup note: product photography, descriptions, weights, inventory and final taxonomy still require source-data confirmation.</p>
      </div>
    </section>
`;

const storeCss = `
  <style id="phase-two-homepage-store-styles">
    .phase-two-shop{position:relative;padding:clamp(72px,8vw,112px) max(24px,calc((100vw - 1180px)/2));overflow:hidden;background:linear-gradient(150deg,#f7f3e8,#edf4e9);color:#26342e}
    .phase-two-shop::before{content:"";position:absolute;right:-180px;top:-220px;width:520px;height:520px;border:1px solid rgba(23,76,60,.1);border-radius:50%}
    .phase-version{position:absolute;right:20px;top:18px;padding:8px 11px;border:1px solid rgba(23,76,60,.12);border-radius:999px;background:rgba(255,255,255,.86);color:#60736a;font:700 10px/1 "DM Sans",sans-serif;letter-spacing:.08em;text-transform:uppercase}
    .phase-shop-wrap{position:relative;z-index:1;max-width:1180px;margin:auto}
    .phase-shop-heading{display:grid;grid-template-columns:1.15fr .85fr;gap:clamp(40px,7vw,90px);align-items:end}.phase-shop-heading h2{max-width:720px;margin:14px 0 0;color:#174c3c}.phase-shop-heading p{margin:0;color:#5c7066;font-size:17px;line-height:1.7}.phase-shop-actions{margin-top:24px;display:flex;align-items:center;flex-wrap:wrap;gap:18px}.phase-shop-primary{background:#174c3c!important;color:#fff!important}
    .phase-shop-paths{margin-top:46px;display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}.phase-shop-paths a{min-height:154px;padding:25px;display:flex;flex-direction:column;border:1px solid rgba(23,76,60,.12);border-radius:20px;background:#fff;box-shadow:0 12px 30px rgba(23,76,60,.07);transition:transform .2s ease,box-shadow .2s ease}.phase-shop-paths a:hover{transform:translateY(-4px);box-shadow:0 18px 38px rgba(23,76,60,.12)}.phase-shop-paths span{color:#8e4b61;font-size:11px;font-weight:800;letter-spacing:.12em}.phase-shop-paths strong{margin:22px 0 7px;color:#174c3c;font-family:"Fraunces",serif;font-size:24px}.phase-shop-paths small{color:#667970;font-size:13px;line-height:1.5}
    .phase-featured-head{margin:70px 0 28px;display:flex;align-items:end;justify-content:space-between;gap:32px}.phase-featured-head h3{margin:12px 0 0;color:#174c3c;font-size:clamp(31px,4vw,48px)}.phase-featured-head>p{max-width:430px;margin:0;color:#65776e;font-size:13px;line-height:1.6}
    .phase-product-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:15px}.phase-product{padding:22px;display:flex;flex-direction:column;border:1px solid rgba(23,76,60,.12);border-radius:20px;background:#fff}.phase-product p{margin:20px 0 7px;color:#3f7d50;font-size:10px;font-weight:800;letter-spacing:.1em;text-transform:uppercase}.phase-product h4{min-height:52px;margin:0;color:#174c3c;font-family:"Fraunces",serif;font-size:21px;line-height:1.2}.phase-product>b{margin:14px 0;font-size:17px}.phase-product button{min-height:42px;margin-top:auto;border:0;border-radius:999px;background:#174c3c;color:#fff;font-weight:800;cursor:pointer}.phase-product button:hover,.phase-product button:focus-visible{background:#3f7d50}.phase-bottle{width:96px;height:148px;margin:15px auto 8px;padding:52px 8px 12px;display:flex;flex-direction:column;align-items:center;border-radius:16px 16px 12px 12px;background:linear-gradient(90deg,#70431f,#9e682d 50%,#683b1a);box-shadow:inset 10px 0 15px rgba(255,255,255,.12),0 16px 25px rgba(68,43,21,.2);color:#174c3c;position:relative}.phase-bottle::before{content:"";position:absolute;left:9px;right:9px;top:-20px;height:28px;border-radius:7px;background:repeating-linear-gradient(90deg,#f1eee5 0 4px,#d9d4c8 4px 6px)}.phase-bottle::after{content:"";position:absolute;left:0;right:0;top:42px;height:76px;background:#f7f4ea;border-top:5px solid #174c3c;border-bottom:3px solid #d6a33a}.phase-bottle span,.phase-bottle strong,.phase-bottle small{position:relative;z-index:1;text-align:center}.phase-bottle span{font-size:8px;font-weight:800;text-transform:uppercase}.phase-bottle strong{margin-top:5px;font-size:12px}.phase-bottle small{margin-top:4px;font-size:7px}.phase-product-note{margin:20px 0 0;color:#6e8077;font-size:12px;line-height:1.55}
    @media(max-width:900px){.phase-shop-heading{grid-template-columns:1fr}.phase-shop-paths{grid-template-columns:1fr 1fr}.phase-shop-paths a:first-child{grid-column:1/-1}.phase-product-grid{grid-template-columns:1fr 1fr}.phase-featured-head{align-items:start;flex-direction:column}}
    @media(max-width:600px){.phase-two-shop{padding:72px 20px}.phase-version{right:10px;top:10px;max-width:190px;text-align:center}.phase-shop-paths,.phase-product-grid{grid-template-columns:1fr}.phase-shop-paths a:first-child{grid-column:auto}.phase-featured-head{margin-top:54px}.phase-product h4{min-height:0}}
  </style>
`;

let output = source
  .replace(/<!-- Homepage v3\.1\.23:[^>]*-->/, '<!-- Online Store Homepage v1.3: actual live homepage body preserved; Phase Two commerce section added; no global chrome. -->')
  .replace('<title>Rebekah\'s Health & Nutrition | Client Feedback Mockup v3.1.23</title>', '<title>Online Store Homepage v1.3 | Rebekah\'s Health & Nutrition</title>')
  .replace('</head>', `${storeCss}</head>`)
  .replace('<div class="buttons"><a class="pill honey" href="#locations">Find Your Store</a><a class="pill glass" href="#events">Explore Classes & Events</a></div>', '<div class="buttons"><a class="pill honey" href="#locations">Find Your Store</a><a class="pill glass" href="#events">Explore Classes & Events</a><a class="pill glass" href="#online-store">Shop Online</a></div>')
  .replace('    <div class="original-section-mount mount-pathway" id="pathway" data-source-section="pathway" data-mock-section="3" aria-label="Expert guidance pathways"></div>', '    <div class="original-section-mount mount-pathway" id="pathway" data-source-section="pathway" data-mock-section="3" aria-label="Expert guidance pathways"></div>\n' + storeSection)
  .replace('</body>', `  <script>document.querySelectorAll('[data-mock-add]').forEach(button=>button.addEventListener('click',()=>{button.textContent='Added';button.setAttribute('aria-pressed','true')}));</script>\n</body>`);

if (/<header\b|<footer\b|class="site-header"|class="store-nav"/.test(output)) {
  throw new Error('Global header, menu, or footer leaked into the page-only mockup.');
}

fs.writeFileSync(outputPath, output, 'utf8');
console.log(`Created ${outputPath} from ${sourcePath}.`);
