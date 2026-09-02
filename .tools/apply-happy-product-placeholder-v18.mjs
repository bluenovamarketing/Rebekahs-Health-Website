import fs from 'node:fs';

const source = 'shop-catalog-template-v1.7.html';
const target = 'shop-catalog-template-v1.8.html';
let html = fs.readFileSync(source, 'utf8');

const oldCss = '.placeholder-art{min-height:220px;display:grid;place-items:center;overflow:hidden;border-radius:16px;background:linear-gradient(145deg,#f2f6ed,#fbf2df);color:var(--pine)}.botanical-placeholder{display:grid;place-items:center;gap:11px;padding:18px;text-align:center}.botanical-mark{position:relative;width:68px;height:58px;display:block}.botanical-mark:before,.botanical-mark:after{content:"";position:absolute;top:2px;width:29px;height:40px;border:2px solid #52785f;background:rgba(255,255,255,.62)}.botanical-mark:before{left:7px;border-radius:100% 0 100% 0;transform:rotate(-16deg)}.botanical-mark:after{right:7px;border-radius:0 100% 0 100%;transform:rotate(16deg)}.botanical-mark>span{position:absolute;left:50%;bottom:2px;height:40px;border-left:2px solid #52785f;transform:translateX(-50%)}.botanical-label{max-width:150px;color:#425f50;font-size:12px;font-weight:700;letter-spacing:.02em;line-height:1.25}';
const newCss = '.placeholder-art{min-height:220px;display:grid;place-items:center;overflow:hidden;border-radius:16px;background:radial-gradient(circle at 20% 18%,rgba(214,163,58,.22) 0 9%,transparent 10%),radial-gradient(circle at 84% 78%,rgba(169,195,160,.32) 0 12%,transparent 13%),linear-gradient(145deg,#f4f8ef,#fff4dc);color:var(--pine)}.photo-placeholder{position:relative;display:grid;place-items:center;gap:9px;min-width:170px;padding:18px 22px;text-align:center}.photo-placeholder:before,.photo-placeholder:after{content:"";position:absolute;border-radius:50%;background:#d6a33a}.photo-placeholder:before{width:9px;height:9px;left:14px;top:22px;opacity:.82}.photo-placeholder:after{width:6px;height:6px;right:18px;bottom:34px;opacity:.58}.placeholder-brand-mark{width:92px;height:92px;display:grid;place-items:center;border:2px solid rgba(214,163,58,.58);border-radius:50%;background:rgba(255,255,255,.94);box-shadow:0 14px 28px rgba(23,76,60,.14);transform:rotate(-2deg)}.placeholder-brand-mark img{width:72px;height:66px;display:block;object-fit:contain}.placeholder-label{max-width:170px;color:var(--pine);font-family:Georgia,serif;font-size:18px;font-weight:700;line-height:1.12}.placeholder-kicker{color:var(--berry);font-size:10px;font-weight:800;letter-spacing:.13em;text-transform:uppercase}';

const cssCount = html.split(oldCss).length - 1;
if (cssCount !== 1) throw new Error(`Expected one v1.7 placeholder CSS block, found ${cssCount}`);
html = html.replace(oldCss, newCss);

const oldMarkup = '<div class="placeholder-art" role="img" aria-label="Product image not available"><div class="botanical-placeholder"><span class="botanical-mark" aria-hidden="true"><span></span></span><span class="botanical-label">Product image not available</span></div></div>';
const newMarkup = '<div class="placeholder-art" role="img" aria-label="Product photo coming soon"><div class="photo-placeholder"><span class="placeholder-brand-mark" aria-hidden="true"><img src="output/brand-kit/assets/logos/rebekahs-mark.svg" alt=""></span><span class="placeholder-kicker">Finishing touch</span><span class="placeholder-label">Product photo<br>coming soon</span></div></div>';
const markupCount = html.split(oldMarkup).length - 1;
if (markupCount !== 6) throw new Error(`Expected six v1.7 product placeholders, found ${markupCount}`);
html = html.split(oldMarkup).join(newMarkup);
html = html.split('v1.7').join('v1.8');

fs.writeFileSync(target, html);
console.log(`${target}: created with ${markupCount} happy brand-mark placeholders`);
