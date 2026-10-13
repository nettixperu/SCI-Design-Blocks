# M6 QA & Release Candidate Certification Report

**Project:** SCI Design Blocks<br>
**Branch:** `feature/sci-hero-patterns-css-compat`<br>
**Source HEAD:** `e078f9f2ab9572c57bb4d9c536267c1ec4f73c12` (M5/M6 changes remain uncommitted)<br>
**Stable baseline:** SCI Design Blocks 1.4.0 (frozen)<br>
**M5 disposition:** Product Owner accepted PASS WITH LIMITATIONS; gates transferred to M6<br>
**M6 disposition:** QA and RC preparation completed with limitations; **NO-GO for production publication**<br>
**Commit/push/merge/tag/GitHub release:** not performed and remain unauthorized

## A. Executive summary

M6 completed the available code, static, WordPress-minimum, CSS-delivery, installation/upgrade, no-lock-in, security/performance, and RC packaging work. The implementation was exercised on the official WordPress 7.1 archive and the separate WordPress 7.1.3 QA site. The 1.4.0 published ZIP was downloaded read-only and its documented SHA-256 matched exactly. An isolated RC ZIP was built and installed/upgraded locally.

Publication is **NO-GO** because the mandatory PHP 7.4 runtime, Gutenberg interactive/browser, viewport/responsive, accessibility, and Product Owner independent QA gates are still unverified. The prepared RC is a test artifact, not a release or a recommendation to publish without those gates and separate PO approval.

## B. Code review & regression report

Reviewed all M5 source changes against `SDD-ASTRA-MIGRATION.md`: the three existing style modules only add the approved `wp_enqueue_block_style()` association; the Hero registration is isolated in `includes/hero-patterns.php`; the two static markup files use Core blocks. No changes were found to public Block Style names, stable plugin header version, Core requirements, or prior pattern content. No Astra/Spectra hooks, UAGB classes, `--ast-*` tokens, custom SCI block, frontend SCI JavaScript, new runtime dependency, required external resource, endpoint, database table, or command execution was introduced in the M5/M6 implementation files.

The working tree contains the M5 implementation and associated documentation/test changes plus this M6 report. It is intentionally uncommitted. `git diff --check` passed. No tracked source outside the approved product, tests, and documentation scope was identified.

## C. WordPress/PHP compatibility matrix

| Target | Evidence | Result |
| --- | --- | --- |
| WordPress 7.1 official archive (reports version `7.1`) | Editorial, Pricing/Comparison/Hero, and Tabs parser/registration/round-trip tests; isolated clean installation and upgrade; runtime registry counts; `wp_enqueue_block_style()` present in Core | PASS for tested code paths on this archive; not represented as a full browser certification |
| WordPress 7.1.3 | Full existing parser suite and local QA site; TT5/TT21 server-output matrix; plugin-off checks from M5 | PASS for these paths |
| PHP 8.5.7 | PHP lint; WP-CLI, clean install/upgrade and local runtime checks | PASS for exercised paths |
| PHP 7.4 | Runtime not available; no installation performed | **NOT VERIFIED** |

The installed `wp_enqueue_block_style()` implementation is present in WordPress 7.1 and its source documents availability since 5.9. The published WordPress archive is available from the [official WordPress release archive](https://wordpress.org/download/releases/). The WordPress 7.1 result closes the Core parser/API/installation checks run here, but it does not close PHP 7.4 or interactive editor/visual compatibility.

Static review found the project PHP syntax in use is compatible with PHP 7.4 constructs (typed returns, closures, variadics, null coalescing, and arrow functions); this is a source review, not a substitute for running PHP 7.4.

## D. Block Styles CSS certification

The runtime registry on WordPress 7.1 registered all seven original identifiers: Accordion `sci-minimal` and `sci-bordered`; Tabs `sci-underline`, `sci-pills`, `sci-connected`, and `sci-filled`; Featured Image `sci-hover-zoom`. The three existing CSS assets remain unchanged. On both Twenty Twenty-Five and Twenty Twenty-One, exact `<link>` tag counts were checked for QA pages 2, 62–68, 79–81, and 86:

- No relevant block (page 2): zero SCI stylesheet links.
- Accordion variants and plain Accordion control (pages 62, 63, 79): one Accordion stylesheet link each.
- Tabs variants and plain Tabs control (pages 64–67, 80): one Tabs stylesheet link each.
- Hover Zoom and plain Featured Image control (pages 68, 81): one Editorial Image stylesheet link each.
- Mixed duplicate-style fixture (page 86): one link per stylesheet, no duplicates.

The three assets each returned HTTP 200. Their unchanged sizes are Accordion 1,076 bytes, Tabs 2,202 bytes, and Editorial Image 580 bytes. The CSS contract is **type-level delivery**, so a plain Core block of a relevant type receives its sheet even without an SCI variation. These are registration, server-output, link-count, and HTTP checks; computed browser styles and editor-side style application remain unverified.

## E. Hero patterns certification

WordPress 7.1 isolated runtime and the static parser tests confirmed both registered names: `sci-design-blocks/hero-cover` and `sci-design-blocks/hero-split`. Both are in the SCI pattern category and contain only Core blocks. Cover uses the Core Cover block, an editable H2, copy and Core Buttons with local example fragments; no remote/default image URL is embedded. Split uses Core Columns, editable text/buttons and an empty Core Image placeholder with mobile stacking. No SCI CSS or JavaScript is required. Existing QA fixtures 89 and 90 were retained; M5 also checked their Core parse/serialize and persistence with SCI inactive.

These checks do not validate editor insertion controls, changing the cover media/overlay, image alt text entry, heading-level interaction, or resulting visual semantics in a live editor. Those remain manual/browser checks.

## F. Responsive & accessibility report

**PARTIAL; NOT CERTIFIED.** The local Hero Cover frontend fixture was loaded in Firefox at 320 px and 768 px. The Hero section itself was visually inspected at 320 px; at 768 px the screenshot showed the site navigation at the top fold, so that is not counted as a Hero visual result. Hero Split, the full four-viewport matrix (1440/768/390/320), all styles, editor insertion/edit/save/reopen, computed styles, keyboard/focus, contrast, screen-reader order, and accessibility automation were not completed. No WCAG conformance claim is made. Product Owner visual, editor, responsive, and accessibility QA remains mandatory.

## G. Installation, upgrade & no-lock-in report

Used isolated temporary WordPress 7.1 sites with separate SQLite databases; the established QA database/site was not used for installation or update and remains intact.

- **Published baseline integrity:** downloaded the 1.4.0 GitHub release ZIP read-only; SHA-256 matched the repository's published value `1d8e81f2da031413a10ae5551152c1597bfda8f8a90a9d5ace2e27ef3ed68d93`.
- **Clean install:** installed and activated the candidate ZIP. WP-CLI reported version `1.5.0-rc.1`; runtime registry reported 22 SCI patterns, 83 icons, and seven SCI styles. Frontend returned HTTP 200; `/wp-admin/` returned the expected unauthenticated redirect (302), not an editor test.
- **Upgrade:** installed the exact published 1.4.0 ZIP in a second isolated site, created a Core paragraph persistence fixture, then replaced it with the candidate ZIP using WP-CLI. Candidate remained active at `1.5.0-rc.1`, and the saved block content was unchanged.
- **Plugin-off persistence:** deactivated the candidate in that isolated site; the saved Core paragraph remained intact; reactivated SCI afterward. M5 previously exercised the Hero fixtures 89/90 with SCI disabled on the separate QA site.
- **Database:** `PRAGMA integrity_check` returned `ok` for both isolated site databases and for the main QA database.

The test site used SQLite Integration, not MariaDB. Plugin upload through the browser, authenticated editor interactions, rollback in a hosting control panel, and external-host upgrade behavior are not certified. WordPress CLI setup emitted a local sendmail permission warning, but installation completed; this did not originate from SCI.

## H. Security, performance & maintainability report

The formal Codex Security repository scan is now complete (scan `dd350624-1f92-4d48-a63d-5a0925d7f6df`; report: `${CODEX_STATE}/plugins/codex-security/scans/<scan-id>/report.md`). It covered all 144 current worktree inventory paths, reported zero findings, and closed its review coverage. The prior M6 preflight blocker was resolved in an isolated `${TMPDIR}/sci-m6-security-venv` with `tomli` 2.5.0; no global Python or product runtime dependency was changed. This is an offline source audit, not a production pentest or live-deployment assessment. All eight repository test commands passed, as did PHP lint. PHP 7.4 runtime compatibility remains NOT VERIFIED.

The implementation keeps style registration close to its existing modules and markup in `patterns/`; three CSS files and all 83 SVG sources remain unchanged. No comparative performance benchmark was made, so no speed improvement is claimed. Conditional delivery, no duplicate printed links, asset sizes, and lack of new JavaScript/dependencies are the performance evidence available.

## I. Release Candidate ZIP & traceability

An isolated, runtime-only ZIP was prepared outside the repository:

- Candidate: `${TMPDIR}/sci-design-blocks-m6-rc1/sci-design-blocks-1.5.0-rc.1-m6.zip`
- Internal preview header only: `1.5.0-rc.1` (temporary packaging copy; no decision about the public version)
- ZIP SHA-256: `0cd716511c863f0086821128fda592b830ca42614a4e96e0c8244814b4a83ed0`
- Entry manifest: `${TMPDIR}/sci-design-blocks-m6-rc1/RC-FILE-MANIFEST.sha256` (105 entries; manifest SHA-256 `d6b7fad91ec346dc7e6c530714146a1ec710b60216a6d4e9f3a93fb80b8b8b28`)
- Source file manifest: `${TMPDIR}/sci-design-blocks-m6-rc1/SOURCE-FILE-MANIFEST.sha256` (same 105 source paths; SHA-256 `4afd2beb27c6488e70ba33bd5d26d9d488a4d5c3e95ec58a42d99b443bb0f51b`)
- ZIP test: `unzip -t` passed; 105 files; approximately 68 KiB compressed. No `.git`, tests, docs, cache, vendor, log, or temporary files are included.

The only difference between the source and packaged runtime trees is the plugin header version in the isolated packaging copy, from repository `1.4.0` to preview label `1.5.0-rc.1`, to permit WordPress's update path test. Product source remains at 1.4.0. The archive was created with `zip -X`; timestamps were not normalized, so bit-for-bit reproducibility is **not** claimed. Rebuild by recopying exactly `LICENSE`, `COPYING.md`, `THIRD-PARTY-NOTICES.md`, `sci-design-blocks.php`, and runtime directories `assets`, `includes`, `icons`, `patterns`; change only the staged header to the same preview string; regenerate sorted SHA-256 manifests; archive the top-level `sci-design-blocks/` directory with `zip -X -r`; then test and hash the ZIP.

The RC is a local candidate only. Do not upload it to GitHub, WordPress.org, or a production site. The 1.4.0 release ZIP is intact and its verified checksum is recorded above.

## J. Product Owner independent QA instructions

M6-09 is **PENDING PO VALIDATION**. This local report does not replace it. For the independent test, use a disposable/staging WordPress site and record exact WordPress/PHP versions, active theme, browser, viewport and results. Do not test on production.

1. Verify the candidate ZIP hash above before installation; retain the current plugin ZIP and a site/database backup for rollback.
2. Install the candidate through **Plugins → Add New Plugin → Upload Plugin** on staging; activate it and confirm version `1.5.0-rc.1`, 22 SCI patterns, 83 icons and seven styles.
3. Insert Hero Cover and Hero Split into drafts. Change heading text/level, cover image and overlay, split image and alt text, CTA labels/targets; save, reopen and confirm no recovery warning or console error.
4. Render at 1440, 768, 390 and 320 px. Check clipping, wrapping, stack order, image crop, spacing, contrast, focus, keyboard navigation, tap targets, Tabs and Accordion operation.
5. Test Accordion standard/Minimal/Bordered; Tabs standard and all four SCI variants; Featured Image standard/Hover Zoom. Check one expected stylesheet per relevant block type and none on a page with no relevant types. Repeat with Twenty Twenty-Five and Twenty Twenty-One if available.
6. Deactivate SCI and verify inserted headings, text, images and links remain usable Core content (the optional SCI appearance may disappear). Reactivate and check the content again.
7. For upgrade validation, use a separate staging copy currently on the exact stable 1.4.0 release, create a backup and content fixture, then update only that copy to the candidate. Confirm content and rollback path. Never overwrite the published 1.4.0 ZIP.
8. Return **PASS**, **PASS WITH OBSERVATIONS**, **FAIL**, or **NOT EXECUTED**, with screenshots/notes and any browser/version details. Publication remains unauthorized until explicit PO approval.

Rollback: on the same staging site, restore the recorded database/files backup, or reinstall the verified 1.4.0 ZIP after deactivating/removing the candidate. Confirm the active version and representative content. Do not delete production content or alter the GitHub release.

## K. Release readiness report

| Gate | Status |
| --- | --- |
| Baseline, branch and diff review | PASS WITH LIMITATIONS (uncommitted M5 tree reviewed) |
| Static regression, lint, CSS/SVG, parser/serializer | PASS |
| Inventory: 22 patterns / 83 icons / 7 styles / 3 CSS | PASS |
| WordPress 7.1 archive and 7.1.3 test paths | PASS for recorded paths; browser coverage remains open |
| PHP 7.4 runtime | NOT VERIFIED — release blocker |
| CSS delivery, TT5/TT21, no duplicates, no-type control | PASS for server-output/link matrix; visual application open |
| Hero editor and interactive controls | NOT VERIFIED — release blocker |
| Responsive visual QA | PARTIAL: Hero Cover visible at 320; at 768 only top navigation observed; required matrix remains open |
| Accessibility manual/automated review | NOT VERIFIED — release blocker; no WCAG claim |
| Clean install / 1.4.0 ZIP upgrade / Core content persistence | PASS in isolated WP 7.1 + PHP 8.5.7 + SQLite |
| Formal source security audit | PASS for repository snapshot: 144 paths, 0 findings; deployment/runtime scope excluded |
| Performance | Static asset/dependency review only; no benchmark |
| RC archive, SHA-256, manifests | PASS; local preview artifact only |
| Product Owner independent QA | PENDING PO VALIDATION — release blocker |
| Production release authorization | NOT AUTHORIZED |

**Final M6 decision: NO-GO for publication.** The RC is ready for independent PO testing, subject to the stated limitations. Do not infer release approval from M5 acceptance or M6 RC preparation. No commit, push, merge, tag, or release has been made.

## M6-FINAL follow-up — 2026-10-12

The RC and both manifests were preserved outside the repository at `${SCI_QA_ROOT}/release-candidates/`. The copied ZIP still hashes to `0cd716511c863f0086821128fda592b830ca42614a4e96e0c8244814b4a83ed0`; the RC and source manifest hashes remain `d6b7fad91ec346dc7e6c530714146a1ec710b60216a6d4e9f3a93fb80b8b8b28` and `4afd2beb27c6488e70ba33bd5d26d9d488a4d5c3e95ec58a42d99b443bb0f51b` respectively. No existing files were overwritten.

See [M6-FINAL report](M6-FINAL-REPORT.md) and [Product Owner QA checklist](PO-QA-CHECKLIST.md) for the consolidated readiness matrix and handoff. This addendum supersedes the earlier statement in section H that the formal audit was blocked. The M6 release decision remains **NO-GO** until the open compatibility, editor, responsive, accessibility, and PO gates are closed; publication remains unauthorized.
