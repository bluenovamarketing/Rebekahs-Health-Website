import { readFile } from 'node:fs/promises';

const baseUrl = 'https://wordpress-1651482-6655800.cloudwaysapps.com';
const username = process.env.REBEKAHS_WP_USERNAME;
const password = process.env.REBEKAHS_WP_LOGIN_PASSWORD;

if (!username || !password) {
  throw new Error('WordPress credentials were not supplied.');
}

const cookieJar = new Map();

function saveCookies(response) {
  const values = typeof response.headers.getSetCookie === 'function'
    ? response.headers.getSetCookie()
    : [response.headers.get('set-cookie')].filter(Boolean);

  for (const value of values) {
    const pair = value.split(';', 1)[0];
    const separator = pair.indexOf('=');
    if (separator > 0) {
      cookieJar.set(pair.slice(0, separator), pair.slice(separator + 1));
    }
  }
}

function cookieHeader() {
  return [...cookieJar.entries()].map(([name, value]) => `${name}=${value}`).join('; ');
}

async function request(url, options = {}, redirects = 8) {
  const headers = new Headers(options.headers || {});
  headers.set('user-agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/140 Safari/537.36');
  headers.set('accept', headers.get('accept') || 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8');
  if (cookieJar.size) {
    headers.set('cookie', cookieHeader());
  }

  const response = await fetch(url, { ...options, headers, redirect: 'manual' });
  saveCookies(response);

  if (redirects > 0 && [301, 302, 303, 307, 308].includes(response.status)) {
    const location = response.headers.get('location');
    if (location) {
      const nextUrl = new URL(location, url).href;
      const nextOptions = response.status === 307 || response.status === 308
        ? options
        : { method: 'GET' };
      return request(nextUrl, nextOptions, redirects - 1);
    }
  }

  return response;
}

function decodeHtml(value) {
  return value.replaceAll('&amp;', '&');
}

await request(`${baseUrl}/wp-login.php`);
const loginBody = new URLSearchParams({
  log: username,
  pwd: password,
  'wp-submit': 'Log In',
  redirect_to: `${baseUrl}/wp-admin/`,
  testcookie: '1',
});
await request(`${baseUrl}/wp-login.php`, {
  method: 'POST',
  headers: { 'content-type': 'application/x-www-form-urlencoded' },
  body: loginBody,
});

const dashboard = await request(`${baseUrl}/wp-admin/`);
const dashboardHtml = await dashboard.text();
if (!dashboard.ok || !dashboardHtml.includes('id="adminmenu"')) {
  throw new Error('WordPress authentication failed.');
}
console.log('AUTH_OK');

const uploadPage = await request(`${baseUrl}/wp-admin/plugin-install.php?tab=upload`);
const uploadHtml = await uploadPage.text();
const nonceMatch = uploadHtml.match(/name="_wpnonce"\s+value="([^"]+)"/i);
if (!nonceMatch) {
  throw new Error('Could not find the WordPress plugin-upload nonce.');
}

const zipPath = new URL('../wordpress/dist/blue-nova-phase-two-staging-installer-v1.6.zip', import.meta.url);
const zipBytes = await readFile(zipPath);
const form = new FormData();
form.set('_wpnonce', nonceMatch[1]);
form.set('_wp_http_referer', '/wp-admin/plugin-install.php?tab=upload');
form.set('pluginzip', new Blob([zipBytes], { type: 'application/zip' }), 'blue-nova-phase-two-staging-installer-v1.6.zip');
form.set('install-plugin-submit', 'Install Now');

const uploadResult = await request(`${baseUrl}/wp-admin/update.php?action=upload-plugin`, {
  method: 'POST',
  headers: {
    origin: baseUrl,
    referer: `${baseUrl}/wp-admin/plugin-install.php?tab=upload`,
  },
  body: form,
});
const resultHtml = await uploadResult.text();
if (!uploadResult.ok || resultHtml.includes('Installation failed') || resultHtml.includes('Destination folder already exists')) {
  const title = resultHtml.match(/<title>([^<]+)<\/title>/i)?.[1]?.trim() || 'No HTML title';
  const message = resultHtml
    .replace(/<script[\s\S]*?<\/script>/gi, ' ')
    .replace(/<style[\s\S]*?<\/style>/gi, ' ')
    .replace(/<[^>]+>/g, ' ')
    .replace(/&rsaquo;/g, '>')
    .replace(/&[a-z0-9#]+;/gi, ' ')
    .replace(/\s+/g, ' ')
    .trim()
    .slice(0, 800);
  console.error(`UPLOAD_RESPONSE ${uploadResult.status} ${title}`);
  console.error(`UPLOAD_MESSAGE ${message}`);
  throw new Error(`Plugin upload failed with HTTP ${uploadResult.status}.`);
}

const activationMatch = resultHtml.match(/href="([^"]*action=activate[^"]*)"[^>]*>Activate Plugin</i);
if (!activationMatch) {
  throw new Error('Plugin uploaded, but WordPress did not provide an activation link.');
}
console.log('UPLOAD_OK');

const activationUrl = new URL(decodeHtml(activationMatch[1]), `${baseUrl}/wp-admin/`).href;
const activationResult = await request(activationUrl);
const activationHtml = await activationResult.text();
if (!activationResult.ok || activationHtml.includes('Plugin could not be activated')) {
  throw new Error(`Plugin activation failed with HTTP ${activationResult.status}.`);
}

console.log('ACTIVATION_OK');
