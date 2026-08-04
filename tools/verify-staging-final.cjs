const base = new URL(process.env.QA_BASE || 'https://wordpress-1651482-6565113.cloudwaysapps.com');

const seedPaths = [
  '/', '/our-story/', '/our-team/', '/locations/', '/locations/lapeer/',
  '/locations/grand-blanc/', '/locations/clarkston/', '/locations/lake-orion/',
  '/events/', '/blog/', '/in-store-products/', '/practitioners/', '/contact-us/',
  '/privacy-policy/', '/refund_returns/', '/terms-conditions/', '/disclaimer/',
  '/shop-fullscript/', '/shop-designs-for-health/', '/shop-lifewave/',
  '/peptides-injectables/'
];

const affiliateHosts = new Set([
  'us.fullscript.com', 'www.designsforhealth.com', 'lifewave.com', 'elliemd.com'
]);

const decode = (value = '') => value
  .replace(/&amp;/gi, '&').replace(/&#0*39;|&apos;/gi, "'")
  .replace(/&quot;/gi, '"').replace(/&lt;/gi, '<').replace(/&gt;/gi, '>')
  .replace(/&#(\d+);/g, (_, n) => String.fromCodePoint(Number(n)))
  .replace(/&#x([0-9a-f]+);/gi, (_, n) => String.fromCodePoint(parseInt(n, 16)));

const stripTags = (value = '') => decode(value.replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim());
const attr = (tag, name) => {
  const match = tag.match(new RegExp(`\\b${name}\\s*=\\s*(["'])([\\s\\S]*?)\\1`, 'i'));
  return match ? decode(match[2].trim()) : '';
};
const first = (html, regex) => (html.match(regex) || [,''])[1] || '';
const allTags = (html, tag) => [...html.matchAll(new RegExp(`<${tag}\\b[^>]*>`, 'gi'))].map((m) => m[0]);

async function fetchWithTimeout(url, options = {}, timeout = 25000) {
  const controller = new AbortController();
  const timer = setTimeout(() => controller.abort(), timeout);
  try {
    return await fetch(url, { ...options, signal: controller.signal, headers: { 'User-Agent': 'Blue-Nova-Final-Staging-QA/1.0', ...(options.headers || {}) } });
  } finally {
    clearTimeout(timer);
  }
}

async function getHtml(url) {
  for (let attempt = 1; attempt <= 3; attempt++) {
    try {
      const response = await fetchWithTimeout(url, { redirect: 'follow' }, 35000);
      const contentType = response.headers.get('content-type') || '';
      const html = contentType.includes('text/html') ? await response.text() : '';
      return { url: url.href || String(url), finalUrl: response.url, status: response.status, contentType, html, error: '' };
    } catch (error) {
      if (attempt === 3) return { url: url.href || String(url), finalUrl: '', status: 0, contentType: '', html: '', error: error.message };
    }
  }
}

async function mapLimit(items, limit, worker) {
  const results = new Array(items.length);
  let next = 0;
  async function run() {
    while (next < items.length) {
      const index = next++;
      results[index] = await worker(items[index], index);
    }
  }
  await Promise.all(Array.from({ length: Math.min(limit, items.length) }, run));
  return results;
}

function normalizeLink(raw, from) {
  if (!raw || /^(#|mailto:|tel:|sms:|javascript:|data:)/i.test(raw)) return null;
  try {
    const url = new URL(raw, from);
    if (url.origin !== base.origin) return null;
    if (/\/(wp-admin|wp-login\.php|wp-json)(\/|$)/i.test(url.pathname)) return null;
	if (/\.xml$/i.test(url.pathname)) return null;
    url.hash = '';
    url.search = '';
    return url.href;
  } catch (_) {
    return null;
  }
}

function inspectPage(result, { seed = false } = {}) {
  const issues = [];
  const html = result.html;
  const path = new URL(result.finalUrl || result.url).pathname;
  const title = stripTags(first(html, /<title\b[^>]*>([\s\S]*?)<\/title>/i));
  const h1s = [...html.matchAll(/<h1\b[^>]*>([\s\S]*?)<\/h1>/gi)].map((m) => stripTags(m[1]));
  const metaTags = allTags(html, 'meta');
  const linkTags = allTags(html, 'link');
  const description = attr(metaTags.find((tag) => attr(tag, 'name').toLowerCase() === 'description') || '', 'content');
  const robots = attr(metaTags.find((tag) => attr(tag, 'name').toLowerCase() === 'robots') || '', 'content').toLowerCase();
  const ogTitle = attr(metaTags.find((tag) => attr(tag, 'property').toLowerCase() === 'og:title') || '', 'content');
  const ogDescription = attr(metaTags.find((tag) => attr(tag, 'property').toLowerCase() === 'og:description') || '', 'content');
  const canonical = attr(linkTags.find((tag) => attr(tag, 'rel').toLowerCase().split(/\s+/).includes('canonical')) || '', 'href');

  if (result.status !== 200) issues.push(`HTTP ${result.status || result.error}`);
  if (!title) issues.push('missing title');
  if (seed && title.length > 65) issues.push(`title too long (${title.length})`);
  if (h1s.length !== 1) issues.push(`${h1s.length} H1 elements`);
  if (seed && !description) issues.push('missing meta description');
  if (seed && description && (description.length < 110 || description.length > 170)) issues.push(`meta description length ${description.length}`);
  if (seed && !ogTitle) issues.push('missing og:title');
  if (seed && !ogDescription) issues.push('missing og:description');
  if (seed && !canonical) issues.push('missing canonical');
  if (seed && !robots.includes('noindex')) issues.push('staging is not noindex');
  if (/Fatal error|There has been a critical error/i.test(html)) issues.push('WordPress critical error');
  if (/href\s*=\s*["']#["']/i.test(html)) issues.push('dead # link');
  const internalHtmlLink = allTags(html, 'a').some((tag) => {
    const href = attr(tag, 'href');
    try {
      const url = new URL(href, result.finalUrl || result.url);
      return url.origin === base.origin && /\.html$/i.test(url.pathname);
    } catch (_) {
      return false;
    }
  });
  if (internalHtmlLink) issues.push('mockup HTML link');
  if (/web-chat-widget-script|webchat-client/i.test(html)) issues.push('live chat loaded on staging');
  if (/lorem ipsum|before publication|will be configured|replace this|todo:/i.test(stripTags(html))) issues.push('draft/internal placeholder text');

  for (const script of [...html.matchAll(/<script\b[^>]*type=["']application\/ld\+json["'][^>]*>([\s\S]*?)<\/script>/gi)]) {
    try { JSON.parse(decode(script[1]).trim()); } catch (_) { issues.push('invalid JSON-LD'); }
  }

  const anchors = allTags(html, 'a');
  for (const tag of anchors) {
    const href = attr(tag, 'href');
    const target = attr(tag, 'target').toLowerCase();
    const rel = attr(tag, 'rel').toLowerCase().split(/\s+/).filter(Boolean);
    let parsed;
    try { parsed = new URL(href, result.finalUrl || result.url); } catch (_) { continue; }
    if (target === '_blank' && (!rel.includes('noopener') || !rel.includes('noreferrer'))) {
      issues.push(`target=_blank lacks noopener/noreferrer: ${href}`);
    }
    if (affiliateHosts.has(parsed.hostname.toLowerCase()) && !rel.includes('sponsored')) {
      issues.push(`affiliate link lacks sponsored rel: ${href}`);
    }
  }

  if (seed) {
    const imageTags = allTags(html, 'img');
    for (const tag of imageTags) {
      const alt = attr(tag, 'alt');
      const role = attr(tag, 'role').toLowerCase();
      const ariaHidden = attr(tag, 'aria-hidden').toLowerCase();
      if (!/\balt\s*=/i.test(tag) && role !== 'presentation' && ariaHidden !== 'true') {
        issues.push(`image missing alt attribute: ${attr(tag, 'src').slice(0, 100)}`);
      }
      if (alt.length > 180) issues.push(`overlong image alt (${alt.length})`);
    }
  }

  return { path, title, titleLength: title.length, h1: h1s[0] || '', descriptionLength: description.length, canonical, issues: [...new Set(issues)] };
}

(async () => {
  const seedUrls = seedPaths.map((path) => new URL(path, base));
  const seedResults = await mapLimit(seedUrls, 6, getHtml);
  const discovered = new Set(seedPaths.map((path) => new URL(path, base).href));
  const imageUrls = new Set();

  for (const result of seedResults) {
    for (const tag of allTags(result.html, 'a')) {
      const link = normalizeLink(attr(tag, 'href'), result.finalUrl || result.url);
      if (link) discovered.add(link);
    }
    for (const tag of allTags(result.html, 'img')) {
      const raw = attr(tag, 'src');
      if (!raw || raw.startsWith('data:')) continue;
      try { imageUrls.add(new URL(raw, result.finalUrl || result.url).href); } catch (_) {}
    }
  }

  const linkUrls = [...discovered].sort().map((value) => new URL(value));
  const linkResults = await mapLimit(linkUrls, 8, getHtml);
  const linkFailures = linkResults.filter((r) => r.status < 200 || r.status >= 400 || r.error).map((r) => ({ url: r.url, status: r.status, error: r.error }));

  const seedReports = seedResults.map((result) => inspectPage(result, { seed: true }));
  const dynamicCandidates = linkResults.filter((result) => result.status === 200 && result.html && !seedPaths.includes(new URL(result.finalUrl || result.url).pathname));
  const dynamicReports = dynamicCandidates.map((result) => inspectPage(result)).filter((report) => report.issues.length);

  const imageResults = await mapLimit([...imageUrls].map((value) => new URL(value)), 8, async (url) => {
    try {
      let response = await fetchWithTimeout(url, { method: 'HEAD', redirect: 'follow' }, 20000);
      if (response.status === 405 || response.status === 403) response = await fetchWithTimeout(url, { method: 'GET', redirect: 'follow', headers: { Range: 'bytes=0-0' } }, 20000);
      return { url: url.href, status: response.status, contentType: response.headers.get('content-type') || '' };
    } catch (error) { return { url: url.href, status: 0, error: error.message }; }
  });
  const imageFailures = imageResults.filter((r) => r.status < 200 || r.status >= 400);

  const missing = await getHtml(new URL(`/final-qa-page-that-does-not-exist-${Date.now()}/`, base));
  const missingH1s = [...missing.html.matchAll(/<h1\b[^>]*>([\s\S]*?)<\/h1>/gi)];
  const redirectResponse = await fetchWithTimeout(new URL('/meet-the-owner/', base), { redirect: 'manual' });
  const redirectLocation = redirectResponse.headers.get('location') || '';
  const redirectOkay = [301, 302, 307, 308].includes(redirectResponse.status) && new URL(redirectLocation, base).pathname === '/our-story/';

  const failures = {
    seedPages: seedReports.filter((report) => report.issues.length),
    dynamicPages: dynamicReports,
    internalLinks: linkFailures,
    images: imageFailures,
    missingPage: missing.status === 404 && missingH1s.length === 1 ? [] : [{ status: missing.status, h1Count: missingH1s.length }],
    legacyRedirect: redirectOkay ? [] : [{ status: redirectResponse.status, location: redirectLocation }]
  };
  const failureCount = Object.values(failures).reduce((sum, group) => sum + group.length, 0);
  const summary = {
    seedPages: seedResults.length,
    internalLinksChecked: linkResults.length,
    dynamicHtmlPagesInspected: dynamicCandidates.length,
    imagesChecked: imageResults.length,
    failureCount,
    failures,
    seedReports
  };
  console.log(JSON.stringify(summary, null, 2));
  if (failureCount) process.exitCode = 1;
})();
