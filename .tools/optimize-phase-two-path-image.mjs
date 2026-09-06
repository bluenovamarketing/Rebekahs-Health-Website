import sharp from 'file:///C:/Users/todda/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/node_modules/sharp/dist/index.mjs';

const source = 'assets/phase-two-homepage/ecommerce-shopping-paths-v1.1.png';
const destination = 'wordpress/theme/rebekahs-2026/assets/img/ecommerce-shopping-paths-v1.1.webp';

const metadata = await sharp(source).metadata();
const width = Math.min(metadata.width || 1800, 1800);

await sharp(source)
  .resize({ width, withoutEnlargement: true })
  .webp({ quality: 84, effort: 6 })
  .toFile(destination);

console.log(JSON.stringify({ sourceWidth: metadata.width, sourceHeight: metadata.height, outputWidth: width }));
