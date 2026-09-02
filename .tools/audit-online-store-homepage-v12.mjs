import fs from 'node:fs';

const v11 = fs.readFileSync('online-store-homepage-mockup-v1.1.html', 'utf8');
const v12 = fs.readFileSync('online-store-homepage-mockup-v1.2.html', 'utf8');
const count = (source, pattern) => (source.match(pattern) || []).length;
const componentCounts = (source) => ({
  sections: count(source, /<section\b/g),
  categories: count(source, /class="category-card"/g),
  products: count(source, /class="product-card"/g),
  shopPaths: count(source, /class="shop-path"/g),
  helpCards: count(source, /class="help-card(?:\s|\")/g),
});

const result = {
  v11: componentCounts(v11),
  v12: {
    ...componentCounts(v12),
    headers: count(v12, /<header\b/g),
    footers: count(v12, /<footer\b/g),
    mains: count(v12, /<main\b/g),
    h1s: count(v12, /<h1\b/g),
  },
};

const countsMatch = JSON.stringify(result.v11) === JSON.stringify(componentCounts(v12));
const bodyOnly = result.v12.headers === 0 && result.v12.footers === 0 && result.v12.mains === 1;
console.log(JSON.stringify({ ...result, countsMatch, bodyOnly }, null, 2));
if (!countsMatch || !bodyOnly || result.v12.h1s !== 1) process.exitCode = 1;
