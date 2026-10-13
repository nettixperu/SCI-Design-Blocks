# SCI Design Blocks — M6-FINAL Release Readiness

**Status:** NO-GO for stable release; candidate remains eligible for independent PO QA.<br>
**Baseline:** 1.4.0, frozen. **Candidate:** 1.5.0-rc.1 (local QA artifact only).<br>
**Branch/HEAD:** `feature/sci-hero-patterns-css-compat` / `e078f9f2ab9572c57bb4d9c536267c1ec4f73c12`.<br>
**Publication:** not authorized; no commit, push, merge, tag, or release was created.

## Summary

The technical checks possible on this MacBook are complete. The inventory remains **22 patterns, 83 icons, seven Block Styles, three CSS assets, no custom SCI blocks, no frontend JavaScript, and no runtime dependencies**. PHP lint and all eight existing static tests pass on PHP 8.5.7. WordPress 7.1 and 7.1.3 paths, clean install, upgrade from the exact 1.4.0 artifact, CSS server-output matrix, and no-lock-in checks are recorded in the prior M6 evidence. The formal repository security scan reviewed all 144 current worktree inventory paths and reported zero findings.

The release gates that block a GO remain PHP 7.4 runtime compatibility, Gutenberg editor interactions, the complete responsive matrix, accessibility review, and independent Product Owner QA. The Hero Cover fixture was loaded in Firefox at 320 px and 768 px; the Hero itself was visually inspected at 320 px, while the 768 px screenshot showed only the site navigation at the top fold. This is a limited spot-check and does not close those gates. No WCAG or full visual certification is claimed.

## Compatibility and environment

| Area | Evidence and result |
| --- | --- |
| PHP 7.4 | No `php7.4`/`php74` binary, Homebrew PHP 7.4 formula, Docker, or Podman was present. PHP 8.5.7 is installed. Source syntax/API review found no PHP 8-only syntax or functions in product PHP, but that is a **STATIC COMPATIBILITY CHECK**, not a PHP 7.4 runtime pass. |
| WordPress | Prior isolated checks used the official 7.1 archive and WordPress 7.1.3; only those recorded paths are certified. |
| macOS QA tools | PHP 8.5.7, WP-CLI 2.12.0, Python 3.9.6, Composer, Homebrew and SQLite CLI available. No PHP 7.4 runtime or container engine available. |
| Security-audit tooling | A temporary virtualenv at `${TMPDIR}/sci-m6-security-venv` used `tomli` 2.5.0 to satisfy the scanner preflight. Python and product dependencies were not modified globally. |
| Visual QA | Firefox native browser reached local Hero Cover frontend at `http://${QA_HTTP_HOST}:${QA_HTTP_PORT}/?page_id=89`; spot-checks at 320/768 px only. Editor was not opened or changed. |
| QA site restoration | No QA-site files, plugin activation, theme, content, or database were changed by this M6-FINAL pass. Prior M6 record says Twenty Twenty-Five and SCI 1.4.0 were active and SQLite integrity was `ok`. A final read-only WP-CLI theme/plugin/database recheck failed in the SQLite adapter with “Cannot escape data without an active database connection”; therefore current end-of-pass DB/theme/plugin state is **not re-verified**. No repair or restart was attempted. |

### PHP 7.4 assessment

The required minimum is stated in `sci-design-blocks.php` and `SECURITY.md`. Current Mac checks: `php -v` reports PHP 8.5.7; `command -v php7.4` and `php74` return no executable; Homebrew has only PHP 8.5.7; Docker/Podman are absent. The source uses PHP constructs available by 7.4 (including typed/void returns, closures, variadics, and null coalescing); searches found no PHP 8-only constructs such as union types, `match`, nullsafe calls, promoted properties, `str_contains`, `str_starts_with`, or `str_ends_with`. Product APIs exercised are PHP core and WordPress APIs; WordPress 7.1 API checks were recorded separately. This does not prove runtime compatibility. Since PHP 7.4 is end-of-support and a trusted isolated runtime was not locally available, no uncertain binary/source build was forced. **Gate: NOT VERIFIED / blocker.**

## Security audit

- Formal Codex Security Standard scan: **complete, 144/144 paths, zero findings**. Scope: current worktree snapshot, offline source review; production/network testing excluded.
- Report: `${CODEX_STATE}/plugins/codex-security/scans/<scan-id>/report.md`.
- Review covered PHP registration and path selection; all registered pattern markup; icon manifest and 83 SVGs; three CSS assets; tests/docs for secret exposure and unsafe side effects; RC package integrity.
- This is not evidence about deployment configuration, hosting filesystem permissions, WordPress administrator roles, live dependency state, or production exploitability. No service was contacted and no credentials were used.

## Regression and inventory

| Check | Result |
| --- | --- |
| PHP lint (`find . -type f -name '*.php' ... php -l`) | PASS on PHP 8.5.7 |
| `php tests/check-icon-library.php` | PASS — 22 patterns, 83 icons, seven styles |
| `php tests/check-editorial-query-patterns.php <QA-site>` | PASS — six editorial patterns; WordPress 7.1.3 |
| `php tests/check-pricing-comparison-patterns.php <QA-site>` | PASS — seven pricing/comparison/hero additions; 22 total; WordPress 7.1.3 |
| `php tests/check-tabs-roundtrip.php <QA-site>` | PASS — four style round-trips; WordPress 7.1.3 |
| `python3 tests/check-icon-svg.py` | PASS — 83 sources/manifest entries |
| Accordion / Tabs / Editorial Image CSS Python checks | PASS — 1,076 / 2,202 / 580 bytes; scoped rules and selectors verified |
| `git diff --check` | PASS |
| WordPress/CSS matrix and clean-install/upgrade evidence | PASS for the specific versions and fixtures recorded in `M6-CERTIFICATION-REPORT.md`; not a browser/editor pass |
| No-lock-in | PASS for recorded Core parser/serializer and plugin-off persistence fixtures |

No runtime dependency, custom block, or SCI frontend JavaScript was added. Total CSS is 3,858 bytes; RC ZIP is 69,608 bytes (about 68 KiB). No comparative performance benchmark was run.

## Release Candidate integrity and traceability

The original temporary artifact remains unchanged. It was copied without overwriting files to:

`${SCI_QA_ROOT}/release-candidates/`

| Artifact | SHA-256 |
| --- | --- |
| `sci-design-blocks-1.5.0-rc.1-m6.zip` | `0cd716511c863f0086821128fda592b830ca42614a4e96e0c8244814b4a83ed0` |
| `RC-FILE-MANIFEST.sha256` | `d6b7fad91ec346dc7e6c530714146a1ec710b60216a6d4e9f3a93fb80b8b8b28` |
| `SOURCE-FILE-MANIFEST.sha256` | `4afd2beb27c6488e70ba33bd5d26d9d488a4d5c3e95ec58a42d99b443bb0f51b` |

Validation: ZIP checksum matches the expected hash before and after copy; `unzip -t` passes; 105 packaged files exactly match the RC file manifest; all source manifest entries match package bytes; zero missing/extra/mismatched files; no symlinks, absolute/traversal paths, QA tooling, or secret-like filenames. The source and package manifests are preserved beside the RC. The stable 1.4.0 artifact and repository history were not changed.

## Release readiness matrix

| Gate | Evidence | State | Blocking | Owner / next action |
| --- | --- | --- | --- | --- |
| PHP 7.4 | Static syntax/API review only; no runtime | NOT VERIFIED | Yes | Engineering: arrange trusted isolated PHP 7.4 runtime and run lint/load/registration smoke |
| WordPress 7.1 | Official 7.1 archive and 7.1.3 tests/install paths | PASS for tested paths | No | PO: record exact staging version |
| Security audit | Standard scan, all 144 paths, 0 findings | PASS (static/offline scope) | No | CTO: accept scope limitation; no production test implied |
| Static regression | PHP lint, four PHP tests, four Python tests, CSS/SVG checks | PASS | No | None |
| CSS delivery | TT5/TT21 link matrix, no-type/mixed controls, HTTP 200, no duplicates | PASS for server output | No | PO: inspect computed styles visually |
| 22 patterns / 83 icons / 7 styles | Registry and static checks | PASS | No | PO: independently confirm candidate UI |
| Editor | Insert/edit/save/reopen/recovery-warning/console checks | NOT VERIFIED | Yes | PO: use checklist on disposable staging |
| Responsive | Hero Cover visible at 320; 768 view showed top navigation only; 1440/390 and other patterns incomplete | PARTIAL | Yes | PO: run full matrix on both Heroes and styles |
| Accessibility | Static native-block review only; no keyboard/contrast/screen-reader test | NOT VERIFIED | Yes | PO: perform checklist; do not claim WCAG conformance |
| Clean install | Isolated WP 7.1 + PHP 8.5.7 + SQLite | PASS for tested setup | No | PO: independent staging validation |
| Upgrade from 1.4.0 | Exact published baseline ZIP, isolated upgrade and content persistence | PASS for tested setup | No | PO: independently test and rollback |
| No-lock-in | Core serialization and plugin-off fixture checks | PASS for recorded fixtures | No | PO: spot-check representative content |
| PO independent QA | Not yet supplied | NOT EXECUTED | Yes | PO: execute checklist and report classification |
| Release approval | Explicit PO authorization absent | NOT AUTHORIZED | Yes | PO: separate written release decision required |

### GO / NO-GO

**Recommendation: NO-GO for stable release.** Mandatory release gates remain unexecuted or partial, and PO approval has not been given. This does not reject the candidate: it is preserved for independent staging QA. A later technical GO does not authorize publication; release actions require a separate explicit Product Owner instruction.

## Git and change-control record

- Branch: `feature/sci-hero-patterns-css-compat`
- HEAD: `e078f9f2ab9572c57bb4d9c536267c1ec4f73c12`
- Existing M5/M6 working-tree changes were preserved. This report/checklist and index link are additional documentation changes.
- No commit, push, merge, tag, GitHub release, or change to `main`/`v1.4.0` was made.
- Final `git diff --check` passed after authoring the reports and index update.
