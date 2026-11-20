# SEO Chat Starter Prompts

Create each chat **inside the existing Rebekah's Health Website project** and use **This computer / Local** so it opens in the current project folder and automatically discovers the project-level `AGENTS.md`.

Do not create a separate Codex project or attach only the `seo` subfolder. The website files, canonical project instructions, and client history are necessary context for accurate SEO work.

## 1. Strategy and baseline audit

Suggested chat title: **Rebekah SEO — Strategy & Audit**

Starter prompt:

> Work on Rebekah's SEO strategy and baseline audit. Start with `seo/README.md` and `seo/SEO-MASTER-TRACKER.md`, then follow the project-level `AGENTS.md`. Keep this task read-only unless I explicitly approve implementation. Use public evidence, the active project, PageSpeed Insights for mobile and desktop when performance is in scope, current CrUX data, and connected Search Console evidence when available. Save SEO outputs inside `seo/` and update `CLIENT-NOTES.md` after meaningful client work.

## 2. Content and on-page SEO

Suggested chat title: **Rebekah SEO — Content & On-Page**

Starter prompt:

> Work on Rebekah's content and on-page SEO. Start with `seo/README.md`, the keyword map, and the canonical planning sheet. For every page-specific task, read the page row and its `Copy/Paste Prompt` before starting. Keep recommendations and drafts in `seo/content-plans/`; do not publish or change the live website unless I explicitly request implementation. Update the SEO change log and `CLIENT-NOTES.md` after meaningful implemented work.

## 3. Search Console and indexing

Suggested chat title: **Rebekah SEO — Search Console & Indexing**

Starter prompt:

> Review Rebekah's Search Console, indexing, sitemap, query, and page-performance evidence. Start with `seo/README.md` and the project-level `AGENTS.md`. Use the connected Google Search Console evidence when available, distinguish observed facts from recommendations, and do not request indexing or change site settings unless I explicitly authorize it. Save findings under `seo/audits/` and update the master tracker and `CLIENT-NOTES.md` after meaningful work.

## 4. Monthly reporting

Suggested chat title: **Rebekah SEO — Monthly Reporting**

Starter prompt:

> Prepare Rebekah's SEO reporting using the verified baseline, current Search Console evidence, implemented-change log, and prior reports. Start with `seo/README.md` and `seo/monthly-reports/MONTHLY-REPORT-TEMPLATE.md`. Clearly separate completed work, measured results, interpretation, and proposed next actions. Do not claim causation without evidence. Save the report in `seo/monthly-reports/` and update `CLIENT-NOTES.md` after meaningful client work.

## Chat settings checklist

- Project: **Rebekah's Health Website**
- Work in: **This computer / Local**
- Primary folder: `C:\Users\todda\Blue Nova Projects\Rebekahs Health Website`
- Worktree: leave off for research and reporting; use one only when a coding task needs isolated file changes
- Model: the normal project default is fine unless a task has a special requirement
- First instruction: identify the specific SEO workstream and whether the task is audit-only or includes implementation
