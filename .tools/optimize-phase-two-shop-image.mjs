import sharp from 'file:///C:/Users/todda/.cache/codex-runtimes/codex-primary-runtime/dependencies/node/node_modules/sharp/dist/index.mjs';

const source = 'assets/phase-two-shop/wellness-goal-card-strip-v1.1.png';
const destination = 'wordpress/theme/rebekahs-2026/assets/img/wellness-goal-card-strip-v1.1.webp';
const metadata = await sharp(source).metadata();

await sharp(source)
  .resize({ width: Math.min(metadata.width || 1774, 1774), withoutEnlargement: true })
  .webp({ quality: 84, effort: 6 })
  .toFile(destination);

console.log(JSON.stringify({ width: metadata.width, height: metadata.height }));
