# Rebekah's Hosting and Website Care Plan

Prepared August 11, 2026

## Recommended direction

Recommend moving the live website from GoDaddy hosting to Cloudways and using a **client-owned, Blue Nova-managed** arrangement:

- Rebekah's owns the Cloudways account, domain registration, and payment method.
- Rebekah's pays Cloudways directly for hosting and selected add-ons.
- Blue Nova receives its own restricted team access—no shared passwords—and manages the website under a monthly care plan.
- The domain remains registered in Rebekah's name. It can stay registered at GoDaddy initially while its DNS records are pointed to Cloudways. A registrar transfer, if desired, should happen after the new site is stable rather than during launch.

This model gives the client full ownership and billing visibility while allowing Blue Nova to handle the technical work. It also creates a clean handoff if the business ever changes providers or agencies.

## Why Cloudways

Cloudways is a better operational fit for this site than conventional GoDaddy hosting because it provides:

- Managed cloud infrastructure with server resources that can be increased as traffic and ecommerce needs grow.
- Staging, server-level caching, free SSL, automated/on-demand backups, firewall and monitoring tools.
- 24/7 support by chat and ticket. In Blue Nova's experience, Cloudways support has been responsive and especially helpful when sites have been compromised.
- An optional Malware Protection add-on with continuous scanning, proactive defense, scan history and automated cleanup. Cloudways currently lists it from **$4 per application per month**.
- A WordPress vulnerability scanner, bot protection, IP controls and other platform-level security features.
- A practical path to WooCommerce: the server can be scaled as the store, checkout traffic, product catalog and integrations grow.

Use the term **malware protection**, not “virus protection.” It materially reduces risk and can automate cleanup, but it is not a guarantee against every compromise and does not replace backups, tested updates, strong passwords, MFA, limited user access or incident response.

The project already has a functioning Cloudways staging application, a verified Cloudways restore point, and a completed staging build. That substantially reduces the uncertainty of the platform choice.

## Three ownership and service choices

### Option 1 — Client-owned hosting with Blue Nova care (recommended)

**Ownership and billing**

- Rebekah's owns and pays for Cloudways directly.
- Rebekah's always owns the domain.
- Blue Nova is invited as a Cloudways team member with only the access needed to manage the server/application and contact support.
- Rebekah's pays Blue Nova separately for the website care plan.

**Recommended price**

- **$117 per month to Blue Nova** for website care, including up to two hours of work in each service month.
- The management fee is invoiced every six months at **$702 per six-month service period**, preferably at the beginning of the period.
- Cloudways and add-ons are paid directly by Rebekah's. A realistic present-day platform allowance is approximately **$55–$70 per month**, depending on server size, backup storage and selected add-ons.
- Equivalent combined operating cost: approximately **$172–$187 per month**, before tax, overages, email services or unusual infrastructure needs, although Blue Nova's $702 management fee is paid every six months rather than monthly.

**Why it is recommended**

The client controls the asset and vendor relationship; Blue Nova controls the day-to-day technical care. There is no markup confusion, and the client can see the actual hosting invoice.

### Option 2 — Fully managed and billed by Blue Nova

**Ownership and billing**

- Blue Nova houses the server/application in its Cloudways account and pays Cloudways.
- Rebekah's pays one six-month invoice to Blue Nova covering both hosting and management.
- The domain still remains registered to Rebekah's; domain ownership should never be bundled into the agency account.

**Recommended price**

- **$197 per month, invoiced as $1,182 every six months.**
- This bundled price includes Blue Nova management, Cloudways hosting, Malware Protection, one managed backup each month and up to two hours of work in each service month.
- The price includes up to **$70 per month** of normal Cloudways infrastructure, malware and backup-storage costs. A server upgrade, abnormal bandwidth/storage use or new paid service requires client approval and a price adjustment.

**Portability promise**

If the client later wants direct ownership, Blue Nova will transfer the Cloudways server/application to the client's paid Cloudways account after outstanding invoices are settled. Cloudways supports full server transfers between paid accounts, including control and future billing.

**Tradeoff**

This is the simplest invoice for the client, but Blue Nova carries billing, administrative and vendor risk. The monthly price should therefore be higher than the client-owned arrangement.

### Option 3 — Client-managed hosting with on-demand Blue Nova help

**Ownership and billing**

- Rebekah's owns, pays for and administers Cloudways.
- Blue Nova completes the launch and provides help only when requested.

**Recommended price**

- Cloudways and add-ons paid directly by Rebekah's: approximately **$55–$70 per month** for the likely configuration.
- Blue Nova work billed at **$150 per hour**, one-hour minimum, subject to availability.

**Tradeoff**

This has the lowest fixed monthly cost but puts update decisions, monitoring, backup checks and first response on the client. Small maintenance items may be delayed, and emergency work is less predictable. It is not the recommended choice for a business preparing to add ecommerce.

## Cloudways sizing and estimated platform cost

Cloudways pricing is pay-as-you-go and can change. Confirm the selected configuration in the account before the proposal is signed.

- **2 GB DigitalOcean Standard:** currently listed at about **$24/month**. This may be suitable for the present informational site after launch testing.
- **4 GB DigitalOcean Standard:** currently listed at about **$46/month**. This is the more conservative starting target when active WooCommerce/ecommerce is introduced; Cloudways currently includes Object Cache Pro on 4 GB servers and above.
- **Malware Protection:** from **$4/application/month**.
- **Cloudflare Enterprise add-on:** from **$4.99/domain/month**, optional and subject to post-launch performance/security needs.
- **Backups and overages:** variable; retain a reasonable monthly allowance rather than promising an exact all-in infrastructure number.

Do not scale merely because ecommerce is planned. Establish a post-launch baseline first, then re-evaluate CPU, memory, database load, traffic, cart/checkout behavior and third-party integrations before ecommerce launch.

## What the monthly care plan includes

The care plan includes up to two hours per calendar month for:

- WordPress core, theme and plugin updates, performed with an appropriate backup and test/QA process.
- One Blue Nova-managed website backup each service month, with confirmation that the backup completed and is available for restoration.
- An additional restore point before a material update or other higher-risk maintenance change when appropriate.
- Verifying SSL, uptime, basic security alerts and Cloudways application health.
- Malware Protection monitoring and coordination with Cloudways support when a threat is detected.
- Cache management and routine performance checks.
- Minor content edits, link corrections, form adjustments, display fixes and small configuration changes.
- Troubleshooting ordinary WordPress, plugin or hosting issues.
- A short monthly summary of work completed, issues found and recommendations.

## Boundaries that need to be explicit

- Each six-month term contains six separate monthly allowances of up to two hours each. The hours are not pooled: unused time does not roll into a later month.
- Work beyond two hours requires approval and is billed at **$150 per hour**.
- New pages, redesigns, custom features, ecommerce implementation, product entry, integrations, copywriting, SEO campaigns and major accessibility/performance projects are quoted separately.
- Premium plugin or service licenses, email hosting/delivery, CDN fees, unusual storage/bandwidth, paid incident-forensics work and infrastructure upgrades are separate unless expressly listed.
- The included monthly backup may use Cloudways' native backup system or another approved backup method. Cloudways backup-storage charges remain a hosting expense. A separate long-term off-platform archive is not included unless expressly added.
- A monthly backup is suitable for the current informational site but not sufficient protection for an active ecommerce database. Before ecommerce launches, approve a more frequent schedule for orders, customers, inventory and payment-related site data; any added storage or service cost is separate.
- Malware cleanup performed automatically by Cloudways is covered by the platform add-on. Blue Nova investigation, restoration, hardening or repair uses included care time first; substantial remediation is separately approved and billed.
- The care plan reduces risk but cannot promise uninterrupted service, immunity from hacking or recovery from every third-party failure.
- Define normal business-hours response expectations in the service agreement. After-hours emergency response should be best-effort and separately billable unless a higher support tier is purchased.

## Six-month billing terms

- When the client pays Cloudways directly, management is **$117 per month** and invoiced as **$702 every six months**.
- When Blue Nova supplies and pays for hosting and Malware Protection, the bundled plan is **$197 per month** and invoiced as **$1,182 every six months**.
- Invoice at the beginning of each six-month service period so management coverage is active before work is requested.
- The invoice covers six consecutive service months, with a maximum of two included hours in each individual month.
- Extra work is invoiced separately at the approved hourly rate and is not deferred until the next six-month renewal.
- Send a renewal notice before the next six-month invoice and require notice before the renewal date if the client does not want to continue.
- The final service agreement should state the start date, payment due date, late-payment handling, renewal process and what happens to support coverage if the invoice is unpaid.

## Domain and DNS plan

The domain and the hosting are separate assets. Cloudways does not act as the normal domain registrar and does not provide standard authoritative nameservers on its core platform.

Recommended launch sequence:

1. Keep the domain registered to Rebekah's and confirm renewal, registrant email, access and MFA before launch.
2. Keep GoDaddy registration/DNS in place for the cutover unless there is a specific technical problem.
3. Inventory every DNS record first, especially Microsoft 365/Google Workspace email, verification, SPF, DKIM and DMARC records.
4. Lower the relevant web-record TTL in advance when practical.
5. Complete final content/database synchronization, backups and Cloudways testing.
6. Point only the required website records (`@` and `www`) to Cloudways; do not disturb email records.
7. Install/verify SSL, canonical URLs, forms, analytics, Search Console, ecommerce data and critical redirects.
8. Keep the old GoDaddy hosting active for at least 7–14 days after verified cutover; then cancel only the hosting product, not the domain or email service.
9. If the client wants to leave GoDaddy entirely, transfer the domain registrar in a later, separate change window after the site is stable.

## Migration and handoff plan

1. **Decision:** client selects one of the three ownership/service options.
2. **Account setup:** create or confirm the paid Cloudways owner account and payment method; configure individual team access and MFA.
3. **Infrastructure:** confirm the staging and intended live application/server relationship, backups, malware protection, monitoring, server region and initial capacity.
4. **Pre-launch QA:** complete the existing launch checklist, including legal approval, live-domain social OAuth, reCAPTCHA/mail, SEO/canonicals/sitemap, analytics, Search Console and WooCommerce data preservation.
5. **Cutover:** freeze or synchronize changing data, take final backups, map the live domain, change DNS, install SSL and purge caches.
6. **Verification:** test desktop/mobile pages, forms, email, login, redirects, media, tracking, performance and security; monitor logs and DNS propagation.
7. **Stabilization:** leave the old host active for 7–14 days, correct any launch issues, then authorize cancellation of the old hosting only.
8. **Ongoing care:** begin the selected care plan, establish the monthly reporting date and issue the appropriate six-month invoice: $702 for management only or $1,182 for Blue Nova-provided hosting and management.
9. **Ecommerce checkpoint:** before Phase Two launch, run load/resource and checkout testing and approve any server increase or new paid services.

## Suggested meeting flow

1. **Start with the business goal:** reliable hosting now, with a clean path to ecommerce.
2. **Explain why Blue Nova recommends Cloudways:** proven staging environment, better management tools, scalable resources, backups, security tooling and strong support experience.
3. **Clarify ownership:** the client owns the domain in every option; hosting ownership and billing are the choice.
4. **Present the three options:** recommend the client-owned/Blue Nova-managed model, then explain the one-invoice and self-managed alternatives.
5. **Explain protection honestly:** malware scanning and automated cleanup are valuable, but maintenance, backups and access security still matter.
6. **Review scope and pricing:** explain the $702 six-month management-only option, the $1,182 six-month hosting-and-management option, separate monthly hour limits and what requires a separate quote.
7. **Get a decision:** ownership model, Cloudways payer, monthly care plan, malware add-on and domain-registration preference.
8. **Confirm next step:** send the final service agreement and launch/cutover authorization.

## Client-friendly summary language

> We recommend that your live website move from GoDaddy hosting to Cloudways. We have already built and tested the new site in a Cloudways staging environment, and we have had very good experiences with Cloudways' support—especially when helping sites affected by malware. Cloudways gives us better backups, security tools, staging and a cleaner way to increase resources when ecommerce is added.
>
> Our preferred setup is for Rebekah's to own and pay for the Cloudways account directly, while adding Blue Nova as a team member to manage it. You keep full control of the hosting and domain, and we handle updates, monitoring, one managed backup every month, malware alerts and small website fixes. Management is $117 per month, paid as $702 every six months, and includes up to two hours in each individual service month. If you prefer Blue Nova to supply and pay for Cloudways hosting and Malware Protection, the combined plan is $197 per month, paid as $1,182 every six months. You may also manage the hosting internally and request hourly help when needed.
>
> Whichever option you choose, the domain remains yours. We can keep it registered at GoDaddy while pointing the website to Cloudways, then discuss moving the domain registration later. That keeps the website launch and the registrar transfer from creating risk at the same time.

## Questions to resolve with the client

- Do they prefer direct control and separate Cloudways billing, or one invoice from Blue Nova?
- Who should be the legal owner and primary billing contact on the Cloudways account?
- Do they select the $702 six-month management-only plan or the $1,182 six-month Blue Nova-hosted plan, with a separate two-hour/non-rollover limit for each service month?
- Do they approve Malware Protection from launch?
- Do they want to keep the domain registered at GoDaddy temporarily or consider a later registrar transfer?
- Is business email tied to GoDaddy or another provider? This must be confirmed before any DNS or cancellation work.
- Who can authorize launch, hosting cancellation, infrastructure upgrades and emergency work?

## Current-source references

- [Cloudways pricing and included features](https://www.cloudways.com/en/pricing.php)
- [Cloudways Malware Protection](https://support.cloudways.com/en/articles/9166541-how-to-use-malware-protection-to-protect-your-applications)
- [Cloudways team-member access](https://support.cloudways.com/en/articles/5119753-how-to-create-and-update-team-members)
- [Cloudways server ownership transfer](https://support.cloudways.com/en/articles/5119878-how-to-transfer-a-server-to-another-cloudways-account)
- [Cloudways DNS/name-server guidance](https://support.cloudways.com/en/articles/5134095-does-cloudways-offer-name-servers-dns-resolution)
- [Cloudways WordPress onboarding and cutover guidance](https://support.cloudways.com/en/articles/12866625-complete-wordpress-onboarding-guide-for-cloudways)
