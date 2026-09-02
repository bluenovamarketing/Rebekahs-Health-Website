import fs from 'node:fs';

const source = fs.readFileSync('third-mockup-v3.1.23.html', 'utf8');
const output = fs.readFileSync('online-store-homepage-mockup-v1.3.html', 'utf8');
const count = (text, pattern) => (text.match(pattern) || []).length;
const sourceComponents = [...source.matchAll(/data-mock-section="([^"]+)"/g)].map(match => match[1]);
const outputComponents = [...output.matchAll(/data-mock-section="([^"]+)"/g)].map(match => match[1]);
const retained = sourceComponents.every(component => outputComponents.includes(component));
const result = {
  sourceFile: 'third-mockup-v3.1.23.html',
  sourceComponents,
  outputComponents,
  retained,
  headers: count(output, /<header\b/g),
  footers: count(output, /<footer\b/g),
  storeNavs: count(output, /class="store-nav"/g),
  mains: count(output, /<main\b/g),
  h1s: count(output, /<h1\b/g),
  products: count(output, /class="phase-product"/g),
};
console.log(JSON.stringify(result, null, 2));
if (!retained || result.headers || result.footers || result.storeNavs || result.mains !== 1 || result.h1s !== 1 || result.products !== 4) process.exitCode = 1;
