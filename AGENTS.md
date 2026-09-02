# Rebekah's Health & Nutrition Project Instructions

## Canonical planning sheet

The native Google Sheet below is the canonical page inventory, page-specific prompt library, status tracker, SEO/content scope, template list, and recommended workflow for this project:

https://docs.google.com/spreadsheets/d/13zutb5DxPIvqX4yv30QSBmSVyc7UoiTCIeCoqAKF7hU/edit?usp=sharing

For every page-specific design or build task in this workspace:

- Read the applicable page row and its `Copy/Paste Prompt` before starting.
- Treat the Sheet's current decisions and scope limitations as authoritative unless the user gives a newer instruction.
- Use FINAL Client Homepage Mockup v1.21 (`third-mockup.html`) and Our Story v1.1.6 (`story-mockup-v1.1.6.html`) as the approved visual direction.
- Handle page-specific SEO and relevant original content within the page task.
- Defer technical sitewide SEO and performance QA until the pages are assembled in WordPress.
- After meaningful client work, update `CLIENT-NOTES.md` with what was done, decisions, pending follow-ups, and client-facing next steps.

## Live website verification

After every live website change, do not treat an editor preview, admin confirmation, staging page, cache-busted URL, or authenticated browser view as sufficient verification.

Before reporting completion:

- Purge every applicable page, plugin, server, CDN, and Varnish cache.
- Load the exact canonical public URL the client will use, without a cache-busting query parameter.
- Verify the changed content or control is visibly rendered and usable in the public page, not merely present in hidden markup.
- Check the public page at desktop and mobile widths for layout regressions and horizontal overflow.
- When practical, verify the anonymous response independently of the signed-in WordPress session.
- If the client reports that a change is not visible, assume the verification was incomplete, investigate immediately, and do not ask the client to troubleshoot until server-side and browser-cache causes have been checked.

## Mockup versioning and approval tracking

For every current or future page, template, component system, or ecommerce mockup in this workspace:

- Start that mockup's review sequence at `v1.1`.
- Increment the minor number for every revision shown for review: `v1.2`, `v1.3`, and so on. After `v1.9`, continue to `v1.10`; never recycle or renumber a version.
- When a mockup already has numbered review history, continue from its highest existing revision instead of restarting at `v1.1`.
- Version each mockup independently. A revision to one page does not change another page's version.
- Save every reviewed revision as a separate versioned file. Never overwrite or delete an earlier reviewed revision merely because a newer version exists.
- Use filenames in the form `<page-or-system-slug>-v1.1.html` and display the same version inside the mockup or its review card.
- Keep the Phase Two review hub linked to only the newest reviewable version while preserving earlier files as superseded history.
- Record each version in `PHASE-TWO-MOCKUP-VERSION-REGISTER.md`, including the date, revision summary, internal approval status, client approval status, and superseded/current state.
- Treat internal approval and client approval as separate gates. Client approval applies to one exact version; later changes require a new version and new approval.
- Update the version register, review hub, planning workbook, and `CLIENT-NOTES.md` whenever a revision is created or an approval status changes.

## Phase Two ecommerce mockup architecture

- System 01 (`Header + Footer Ecommerce Add-On`) is the only approval mockup that displays the global header, main menu, ecommerce utility row, and footer.
- Systems 02–07 are page-body-only approval mockups. Do not include or simulate the global header, menu bar, ecommerce utility row, or footer in those files.
- Page-body mockups inherit the separately approved global chrome conceptually; they must not duplicate it inside each page review file.
- When revising a page-body mockup, preserve its page content and interactions while keeping all global chrome absent at desktop, tablet, and phone widths.
