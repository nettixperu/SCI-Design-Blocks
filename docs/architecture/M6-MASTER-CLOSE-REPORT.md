# SCI Design Blocks — M6 Master Close Report

**Assessment date:** 2026-10-13<br>
**Branch:** `feature/sci-hero-patterns-css-compat`<br>
**HEAD:** `e078f9f2ab9572c57bb4d9c536267c1ec4f73c12`<br>
**Candidate:** 1.5.0-rc.3<br>
**Stable baseline:** 1.4.0, published and frozen<br>
**Disposition:** **TECHNICAL NO-GO; M6 remains ACTIVE.** Release is not authorized.

This report consolidates checks that could be performed without changing the approved scope. It does not replace historical milestone reports or claim a release certification where a required environment or interactive check was unavailable.

## Executive summary

RC.3's package integrity, source/package manifests, clean WordPress installation, 1.4.0-to-RC.3 upgrade, inventory, database integrity and HTTP rendering passed on WordPress 7.1.3 / PHP 8.5.7. The upgrade fixture preserved all 21 pages and the aggregate content SHA-256. Regression checks passed for 22 patterns, 83 icons, seven Block Styles and three CSS assets.

The mandatory PHP 8.3 runtime gate remains **NOT VERIFIED**. The Homebrew keg-only PHP 8.3.35 formula is present but its binary cannot start because the required `aspell` library is absent. Installing that dependency was rejected by the environment's risk control because it might update shared Homebrew dependencies. No attempt was made to bypass that restriction. PHP 8.5.7 evidence is not a substitute.

RC.3 was installed and exercised on the QA fixtures under PHP 8.5.7, not PHP 8.3. Browser editor interactions, full responsive coverage and manual accessibility checks also remain open. Therefore M6 is not closed and there is no technical GO or release recommendation.

## Baseline and change control

- Git baseline checked: branch and HEAD above; existing modified and untracked project files were preserved. No `main` or stable release files were changed.
- `git diff --check`: PASS.
- No commit, push, merge, tag, GitHub release or publication was made.
- Product scope remains two added Core patterns; no added patterns, custom SCI blocks, frontend JavaScript or runtime dependencies.
- Historical reports were not rewritten. PHP 7.4 mentions in historical reports and stable 1.4.0 documentation remain historical/current for 1.4.0 only. The approved PO change sets the 1.5.0 minimum to PHP 8.3.

## Environment and PHP 8.3

Host: macOS 15.6, Apple Silicon arm64. WordPress QA runtime: 7.1.3; MariaDB QA instance: 13.0.2; WP-CLI: 2.12.0. The system PHP remains `${HOMEBREW_PREFIX}/bin/php` 8.5.7. The existing Homebrew 8.5 installation was not linked over, reconfigured or restarted.

Homebrew's official `php@8.3` formula (8.3.35_1, keg-only) was installed at `${PHP83_PREFIX}` without linking it or changing `PATH`. A normal Homebrew install proposed upgrading 22 shared dependencies; that operation was cancelled before those upgrades. Installing only the PHP formula then failed its post-install/start check because `${HOMEBREW_PREFIX}/opt/aspell/lib/libaspell.15.dylib` is missing. The separate `aspell` install was rejected by the environment risk control due to potential shared dependency changes. No PHP-FPM service was started and no global PHP configuration changed.

| Gate | Evidence | State | Blocker / next action |
|---|---|---|---|
| M6-01 PHP 8.3 runtime | PHP 8.3.35 binary cannot launch; PHP 8.5.7 remains functional | **NOT VERIFIED — BLOCKING** | Obtain approval for a dependency-safe isolated runtime or a compatible isolated VM/container; execute plugin load and regression under actual PHP 8.3. |
| PHP 8.5.7 regression runtime | Lint, tests, WP-CLI activation and site execution | PASS for PHP 8.5.7 only | Does not close the declared minimum. |

## RC.3 artifact and equivalence

- ZIP: `${SCI_QA_ROOT}/release-candidates/sci-design-blocks-1.5.0-rc.3-m6.zip`
- SHA-256: `9b7ffdbeca8f11ca0771bbe0b61b6d6ac6e8471cf011ec611a371a75c743cdd7`
- Package manifest: `RC-FILE-MANIFEST-rc.3.sha256` — 105 entries; verified against extracted ZIP.
- Source manifest: `SOURCE-FILE-MANIFEST-rc.3.sha256` — 105 entries; verified against repository source.
- `unzip -t`: PASS. ZIP has 112 entries including directory records; no absolute/traversal paths or symlinks found.
- RC.2 SHA-256 remains `f8d5d7c65cb17b1645d407324d73f8c44c8cb1b4b1dbeb8977af23ef007ef477`. Stable 1.4.0 artifact SHA-256 remains `1d8e81f2da031413a10ae5551152c1597bfda8f8a90a9d5ace2e27ef3ed68d93`.
- Extracted RC.2 and RC.3 packages differ only in `sci-design-blocks.php` (candidate version and `Requires PHP` header). Pattern markup, CSS, icons and other packaged functional files are byte-identical. This supports limited reuse of RC.2 functional evidence; it does not mean the RC.3 editor was interactively tested.

## Installation, upgrade and WordPress runtime

Two new fixtures were created under `sci-qa-main/fixtures/`, each with a distinct schema and restricted MariaDB account. Existing RC.2 fixtures were not used or altered.

| Gate | Environment and evidence | State | Limitation |
|---|---|---|---|
| M6-02 clean RC.3 install | Fresh WordPress 7.1.3, PHP 8.5.7, MariaDB 13.0.2, TT5 1.5. RC.3 installed from its ZIP and activated. WP-CLI reported 22 SCI patterns, 83 icons, 7 styles and 3 CSS; database check passed. | **PASS WITH LIMITATIONS** | Not run on PHP 8.3; editor not authenticated/interactively tested. |
| M6-03 1.4.0 → RC.3 | Separate WordPress 7.1.3 / PHP 8.5.7 / MariaDB fixture. Stable ZIP SHA verified before install. Twenty pattern pages were created from the 1.4.0 registry (21 pages total including the home page). After upgrade RC.3 was active, 22 patterns registered, DB check passed, and content hash remained `f3a839da9929d30a5160ec238210f0808ccdddd93685a1c073ee0a16a55e5235`. | **PASS WITH LIMITATIONS** | Does not substitute for PHP 8.3 or hosted/browser upgrade coverage. |
| Frontend and assets | In fresh RC.3 fixture, `/`, both Hero pages, `/wp-login.php`, and all three CSS URLs returned HTTP 200. Loopback WP-CLI server was stopped after the check. | PASS for HTTP availability | HTTP 200 is not visual or editor validation. |
| Fatal/load checks | WP-CLI loaded RC.3 on both fixtures; version and inventory queries succeeded; DB checks passed. | PASS on PHP 8.5.7 | No exact-minimum PHP evidence. |

## Gutenberg, responsive and accessibility

| Gate | Evidence | State | Next action |
|---|---|---|---|
| Hero Cover/Split insertion, editing, save/reopen | Product Owner reported prior RC.2 interactive QA. RC.2/RC.3 package comparison shows only plugin header metadata differs; markup and functional assets are identical. | **REUSED RC.2 PO evidence; RC.3 not interactively tested** | Confirm RC.3 in the editor after exact-minimum runtime is available; verify image selector, CTA, save/reopen and no invalid-block warning. |
| Gutenberg JS validation | PHP/Core parser regression passes, including explicit saved markup for Hero Split's empty `core/image`; no RC.3 browser/editor JS validation performed. | **NOT VERIFIED — BLOCKING** | Open both patterns in Gutenberg and verify edit/save/reopen, image controls and browser console. |
| Responsive 1440/768/390/320 | Static Core structure and historical partial PO checks only; no complete current RC.3 viewport matrix or captures. | **NOT VERIFIED — BLOCKING** | Test Heroes, Accordion, Tabs, Pricing, Comparison and CTA at all four widths; record Firefox/theme and evidence. |
| Accessibility | Static markup uses Core headings, links, buttons and image controls; no complete keyboard/focus/contrast/alt/tap-target/manual assistive-technology assessment. | **NOT VERIFIED — BLOCKING** | Complete scoped manual and automated checks; do not claim full WCAG conformance. |

Firefox was running with unrelated user tabs. The available browser-automation surface could not attach to Firefox or provide an isolated in-app browser, so no editor action or responsive screenshot was attempted in the user's existing browser session.

## Inventory, Block Styles, regression and performance

Full regression ran on PHP 8.5.7, using WordPress 7.1.3 fixtures where required:

- PHP lint: PASS for all repository PHP files.
- PHP registration/parser suite: PASS — 22 SCI patterns, one SCI collection, 83 icons, seven Core Block Styles, including six Editorial Query, five Pricing/Comparison and two Hero patterns.
- Hero Split empty Image serialization test: PASS; explicit `<figure><img alt=""/></figure>` markup is retained.
- Self-closing review: PASS — 38 reviewed instances across 22 patterns are limited to reviewed no-save/dynamic Core blocks; no self-closing `core/image` remains.
- Tabs parser/serializer checks: PASS for all four variants and nested content.
- Python SVG/CSS checks: PASS — 83 approved safe SVG sources; Accordion 1,076 bytes, Tabs 2,202 bytes, Editorial Image 580 bytes; no other CSS or frontend JS.
- `git diff --check`: PASS.
- Runtime inventory in fresh RC.3 fixture: 22 patterns, 83 icons, 7 styles, 3 CSS.
- No additional runtime dependencies, custom SCI blocks or SCI frontend JavaScript found. Type-level CSS loading remains the approved contract; no new performance benchmark was run.

## Security, no-lock-in and open evidence

The earlier static audit reviewed 144 paths and recorded zero reportable findings. This close reviewed the RC.3-only package delta (plugin header metadata) and rechecked archive paths/symlinks and runtime/dependency inventory; no new executable feature or external resource was added. This is incremental evidence, not a new full security audit or a claim of absolute security.

Historical M5 QA demonstrated Core content persistence with SCI deactivated for its fixtures, and the new upgrade fixture confirms serialized page content was preserved across 1.4.0 → RC.3. A current RC.3 browser session with selected Hero image and plugin deactivation was not performed. No-lock-in is therefore **PASS WITH LIMITATIONS** for existing evidence; that specific end-to-end editor flow remains open.

## Release readiness matrix

| Requirement | State | Blocking | Owner / next action |
|---|---|---:|---|
| PHP 8.3 runtime PASS | NOT VERIFIED | Yes | Engineering: obtain dependency-safe isolated PHP 8.3 and run runtime suite. |
| Clean RC.3 install / activation | PASS WITH LIMITATIONS (8.5.7) | Yes, exact minimum | Engineering: repeat on PHP 8.3. |
| Upgrade 1.4.0 → RC.3 | PASS WITH LIMITATIONS (8.5.7) | Yes, exact minimum | Engineering: repeat runtime gate on PHP 8.3. |
| 22 patterns / 83 icons / 7 styles / 3 CSS | PASS | No | No action. |
| Gutenberg interactive / JS validation on RC.3 | NOT VERIFIED | Yes | PO/QA: browser validation and captured outcome. |
| Responsive matrix | NOT VERIFIED | Yes | PO/QA: four viewports across required components. |
| Accessibility checklist | NOT VERIFIED | Yes | PO/QA: keyboard, focus, contrast, semantics and targets. |
| Block Styles runtime/registration | PASS WITH LIMITATIONS | Visual part yes | Existing TT5/TT21 evidence reusable; complete visual/editor checks. |
| Security incremental review | PASS WITH LIMITATIONS | No new blocker | Preserve prior audit; review only future deltas. |
| ZIP / SHA / manifests | PASS | No | Preserve RC.3 identity; do not overwrite. |
| Commit / push / tag / release | NOT AUTHORIZED | Yes | No action without separate PO approval. |

## Final decision

**M6 ACTIVE / TECHNICAL NO-GO / RELEASE NO-GO.** The blocking minimum-runtime, Gutenberg, responsive and accessibility gates prevent certification. RC.3 remains a QA candidate only. No publication procedure is recommended until every mandatory technical gate passes; Product Owner release approval remains separate even then.

### Release procedure status

Not prepared because the conditions for Technical GO are not met and publication is explicitly unauthorized. When M6 gates pass, a proposal may identify the reviewed source commit, version/tag, verified ZIP and SHA-256, release notes, rollback and approval point; none of those actions are taken here.

### Reproduction locations

- Clean fixture: `${SCI_QA_SITE_DIR}/fixtures/m6-rc3-clean-2026-10-13` (schema/user `${QA_CLEAN_DB_SCHEMA}`).
- Upgrade fixture: `${SCI_QA_SITE_DIR}/fixtures/m6-rc3-upgrade-2026-10-13` (schema/user `${QA_UPGRADE_DB_SCHEMA}`).
- ZIP and manifests: `${SCI_QA_ROOT}/release-candidates/`.
- QA credentials are in macOS Keychain; this report intentionally contains no secrets.
