import fs from 'node:fs';

const sourcePath = 'online-store-homepage-mockup-v1.1.html';
const outputPath = 'online-store-homepage-mockup-v1.2.html';
const source = fs.readFileSync(sourcePath, 'utf8');

const cssMatch = source.match(/    \/\* Phase Two Online Store homepage body\.[\s\S]*?(?=\n  <\/style>)/);
const mainMatch = source.match(/  <main class="storefront" id="main">[\s\S]*?\n  <\/main>/);

if (!cssMatch || !mainMatch) {
  throw new Error('Could not extract the existing Online Store page body from v1.1.');
}

const bodyCss = cssMatch[0]
  .replace('/* Phase Two Online Store homepage body. The shared chrome above remains unchanged. */', '/* Phase Two Online Store homepage page-body styles, reused from v1.1. */');
const bodyMarkup = mainMatch[0].replace(
  'Phase Two · Online Store Homepage v1.1 · Local Only',
  'Phase Two · Online Store Homepage v1.2 · Page Body Only · Local Mockup',
);

const output = `<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="description" content="Page-body-only mockup for the Rebekah's Health & Nutrition Online Store homepage.">
  <meta name="robots" content="noindex,nofollow">
  <title>Online Store Homepage Body v1.2 | Rebekah's Health & Nutrition</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
  <style>
    :root{--pine:#174c3c;--leaf:#3f7d50;--cream:#f7f3e8;--berry:#8e4b61;--ink:#26342e;--sans:"DM Sans",Arial,sans-serif}
    *{box-sizing:border-box}
    html{min-width:320px;scroll-behavior:smooth}
    body{min-height:100vh;margin:0;overflow-x:clip;color:var(--ink);background:var(--cream);font-family:var(--sans)}
    a{color:inherit;text-decoration:none}
    img{display:block;width:100%}
    button{font:inherit}
    :where(a,button,input,select,textarea,summary):focus-visible{outline:3px solid #d6a33a;outline-offset:3px}

${bodyCss}
  </style>
</head>
<body>
${bodyMarkup}
  <script>
    document.querySelectorAll('[data-mock-add]').forEach((button) => {
      button.addEventListener('click', () => {
        button.textContent = 'Added';
        button.setAttribute('aria-pressed', 'true');
      });
    });
  </script>
</body>
</html>
`;

if (/<header\b|<footer\b|header-shell|site-header|site-footer/.test(output)) {
  throw new Error('The extracted body-only mockup still contains global chrome.');
}

fs.writeFileSync(outputPath, output, 'utf8');
console.log(`Created ${outputPath} by reusing the v1.1 page body.`);
