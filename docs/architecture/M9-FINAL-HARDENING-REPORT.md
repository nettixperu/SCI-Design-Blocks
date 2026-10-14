# SCI Design Blocks — M9 Final Hardening Report

**Date:** 2026-10-13
**Branch:** `feature/sci-pattern-library-expansion`
**Source baseline:** `bb89a3bfb4138d4b8c5984c508594f0fb0549a6f`
**Candidate at this checkpoint:** `1.5.0-rc.10` (local; the later release authorization and PO QA confirmation are recorded below)
**Disposition at M9 checkpoint:** **TECHNICAL NO-GO — RC.10 installation/upgrade passed; independent browser replay of the CTA edit remained unavailable.**

## 1. Executive summary

M9 confirmed and corrected the nonfunctional CTA example destinations and made stylesheet cache-versioning consistent. The implementation remains Core-first: no feature, pattern, icon, Block Style, CSS selector, JavaScript, custom block, dependency, public slug, saved-content migration, or stable-release change was introduced. RC.10 passed source and extracted-package regression checks. The 27-pattern inventory and 83-icon/7-style/3-CSS contract remains intact.

RC.10 clean installation and upgrade from the official 1.4.0 ZIP pass in isolated WordPress 7.1.3/PHP 8.3.35 fixtures. The Product Owner's subsequent release instruction confirms manual Gutenberg insertion, editing, saving/reopening, and corrected CTA destination testing, and explicitly accepts the documented QA coverage limitations. Codex's separate browser replay remained blocked at normal login (the local Keychain credential passed WP-CLI authentication but its UI clipboard transfer was rejected); no editor was reached and no browser restriction was bypassed. This distinction is retained: the PO's manual evidence is accepted for release, not represented as an independent Codex browser run.

## 2. Exact source and artifact baseline

- Branch and starting HEAD matched the task: `feature/sci-pattern-library-expansion`, `bb89a3bfb4138d4b8c5984c508594f0fb0549a6f`; initial worktree was clean.
- WordPress 7.1.3 Core and PHP 8.3.35 arm64 were available locally.
- RC.8 was verified before work at SHA-256 `d91480a3db2be091f824b42fee9cad81bf8c7f2a041e26035bbc3978767faa81`; it remains byte-identical.
- RC.9 was generated once and preserved unchanged at SHA-256 `536d3fe00ec242dee4da7fb433df34458f6602ef707ad1cc5de89fd83da8d633`. Review then caught stale serialized button `url` attributes after the anchor href removal. RC.9 is superseded; it must not be installed or distributed.
- RC.10: `release-candidates/sci-design-blocks-1.5.0-rc.10.zip`, SHA-256 `6c963a45d53e3eca98c8249754efb052f150c5121ed96211ebac683c1579c8a1`.
- RC.10 ZIP integrity passed; source and package manifests each contain 111 entries and the package manifest verifies against the extracted ZIP.
- At the RC.10 checkpoint, the source header was `1.5.0-rc.3`; `tools/build-rc10.sh` staged it as `1.5.0-rc.10` without changing earlier RC artifacts. The final stable release updates only the source version header to `1.5.0`.
- Stable v1.4.0 remains frozen; its official artifact was not modified. No push, merge, tag, or release was performed.

## 3. Security and SVG review

Reviewed the bootstrap, static include modules, icon manifest/registration, pattern file access, stylesheet registration, and generated HTML. Pattern filenames are fixed in source, not user-controlled; icon names are regex-constrained before building paths; registration checks local SVG readability. No endpoint, arbitrary file write/include, command execution, dynamic evaluation, user-input processing, external frontend asset, or credential was found in the reviewed paths. No critical or high finding was identified. This is a scoped code review and source fixture validation, not a full dynamic security assessment.

All 83 SVGs passed the existing source/manifest safety test, including checks for active content and unexpected external references. Names, provenance, and attribution remain unchanged.

Standalone PHP access was evaluated. Direct requests to the main bootstrap fail outside WordPress due to unavailable WordPress APIs; included modules expose no sensitive content or user-controlled operations. No ABSPATH guard was added: it would not protect each included file without a broader convention, and no concrete exploit or data exposure justified expanding scope. CLI tests continue to load the plugin through WordPress API stubs.

## 4. CTA audit and corrections

The audit found 20 example links pointing at fragment identifiers with no matching target. It also confirmed five earlier Core Button placeholders whose anchor has no `href`. These were authoring placeholders, not a security vulnerability, but the fragment links behaved as misleading navigation and all empty destinations needed clearer author intent.

For future pattern insertions only, the 20 broken `href="#…"` values were removed. The visible button labels now say to configure the destination before publishing (Spanish examples use the equivalent instruction). The five historic no-URL buttons in the inline general patterns received the same instruction. Core Button markup and public slugs remain unchanged. This avoids fabricated `#` or external destinations and avoids altering already-saved pages; no database content was edited or migrated.

Regression now checks registered patterns for unconfigured anchors without an editor instruction, unresolved local fragments, and a stale Button `url` attribute when the rendered anchor has no href. Core block parser checks passed. JavaScript `validateBlock` and editor save/reopen were not run against RC.10; see Section 10.

## 5. CSS cache versioning and delivery

Before the correction, Accordion used a fixed string while Tabs and Hover Zoom used `filemtime()`. All three now derive the registered style version from the readable local CSS file's modification time, with a fallback only if the local asset is unreadable. A regression asserts each version matches its local asset. Existing `wp_enqueue_block_style()` associations remain type-specific, so no global stylesheet, duplicate handle, selector change, or altered Core loading mechanism was introduced. The 3-CSS inventory is unchanged.

This favors automatic cache invalidation during local development and normal packaged installs, without an asset pipeline. A timestamp is not a content hash and can be affected by deployment timestamp preservation; package manifests remain the integrity/reproducibility mechanism. Runtime network delivery of RC.9's CSS was not rechecked because the local web/database fixture was offline.

## 6. Maintainability and registration review

The similar pattern registration loops in the pricing, hero, and commercial modules are small and clear; they have distinct ownership and hooks. Consolidating them into a generic framework would add abstraction without a demonstrated benefit. Static pattern filenames and icon manifests keep filesystem paths constrained. Static manifest loading and the fixed count of registration callbacks are straightforward; no persistent/transient caching was added. Existing Core registration hooks and public slugs remain intact.

`README.md`, `CHANGELOG.md`, `CONTRIBUTING.md`, `SECURITY.md`, the preview inventory, and the historical M0–M8/M9-QA reports provide architecture, minimum versions, Core-only principles, tests, release workflow, and limitations. `docs/README.md` now identifies the consolidated current hardening report and classifies milestone material as historical. No redundant AGENTS.md was added.

## 7. Performance observations

Measured the full PHP 8.3 `check-icon-library.php` static registration/inventory smoke (bootstrap, pattern and icon registration stubs, and assertions) ten times on the baseline and ten times on the working source, same Mac/runtime. Wall-clock median was 0.02 s for both; baseline range 0.02–0.04 s, working range 0.02–0.07 s. `/usr/bin/time -p` resolution and process startup dominate this short test, so the result indicates no measurable regression, not a performance improvement or precise registration cost. The regression now makes three local `filemtime()` checks per `init` for cache versioning.

No frontend response benchmark or meaningful registration-only memory measurement was possible: the local MariaDB socket was down and the frontend fixture server was not running. No comparison to Astra/Spectra is claimed. RC.8's recorded ZIP size and prior HTTP 200 results are historical candidate evidence, not RC.9 measurements.

## 8. Regression and inventory

On PHP 8.3.35, the following passed on source and the extracted RC.9 package:

- PHP lint for the plugin bootstrap, all eight include modules, and icon manifest.
- Four PHP test suites: registration/inventory and icon safety; editorial Query; pricing/comparison/heroes and Core parser serialization; Tabs round-trip.
- All four Python checks: Accordion CSS, Tabs CSS, editorial image CSS, and SVG/icon manifest.
- Core parser/serializer checks under WordPress 7.1.3 for all 27 registered patterns; no unreviewed self-closing Image blocks.
- CTA placeholder and unresolved-fragment regressions; CSS cache-version regression.
- `git diff --check`.
- Candidate ZIP integrity and both 111-entry manifests.

Final inventory: **27 patterns, 83 icons, 7 Core Block Styles, 3 CSS files, 0 SCI custom blocks, 0 SCI frontend JavaScript, 0 new runtime dependencies**. Minimum requirements remain PHP 8.3 and WordPress 7.1 for 1.5.0; v1.4.0 requirements remain untouched.

## 9. WordPress installation, upgrade, and no-lock-in

The earlier M9 report records actual RC.8 installation/activation, upgrade from the official 1.4.0 artifact, content hash preservation, responsive checks, and no-lock-in tests in isolated fixtures. These results are preserved as RC.8 evidence and are not repeated or relabelled for RC.10.

RC.10 was installed from its exact ZIP into two new, isolated table-prefix fixtures on the recovered dedicated MariaDB instance; no shared service configuration or historical fixture was changed. Both run WordPress 7.1.3 under PHP 8.3.35, have RC.10 active, and pass `wp db check`. The clean install registers 27 SCI patterns. The upgrade fixture began with the official v1.4.0 ZIP (SHA-256 `1d8e81f2da031413a10ae5551152c1597bfda8f8a90a9d5ace2e27ef3ed68d93`) and 20 seeded pages. Their sorted aggregate post-content SHA-256 was `a9a37b8d56e0e70241707601e5fb54fc7e7eb976212a40c0f4c4f7cd5cdf3906` before and after RC.10 installation; all 20 remained. Both candidate sites and both login endpoints returned HTTP 200 on loopback. This verifies installation/activation, DB integrity, content persistence, and basic HTTP response, not visual or editor behavior.

## 10. Gutenberg and accessibility

The candidate's PHP parser checks are not equivalent to Gutenberg JavaScript `validateBlock`. The Product Owner's release instruction confirms manual Gutenberg insertion/editing/saving/reopening for the tested compositions, including assigning corrected CTA destinations and reporting no invalid blocks; the PO accepts the recorded limits of broader accessibility coverage. This is recorded as PO-supplied evidence, not as an independent Codex browser validation. Codex's local login attempt using the temporary fixture-only administrator did not reach the editor because the browser rejected the OS/browser clipboard value; the available authenticated editor belonged to RC.5 and was not reused. No password reset, existing-account modification, fabricated cookie, or browser/sandbox bypass was attempted. No formal WCAG audit, exhaustive screen-reader run, or comprehensive theme-by-theme keyboard certification was performed.

CTA labels now identify that the author must configure a real destination before publishing. Core provides the button/link editing and keyboard behavior once a destination is configured; no custom interaction was introduced. Existing reduced-motion CSS remains tested. No formal WCAG audit, screen-reader test, or comprehensive contrast/touch-target certification was performed; this product does not claim WCAG conformance.

## 11. Corrections, deferred work, and residual risk

**Applied:** remove 20 broken example fragment destinations from both Core Button comment attributes and rendered HTML; make all future-insertion button placeholders explicit; normalize three stylesheet cache versions; add focused CTA/cache regressions; add reproducible RC.10 builder, release notes, changelog and current documentation index. RC.9 remains preserved and marked superseded.

**Deferred / not justified:** generic registration abstraction, persistent cache, direct-access guard framework, CSS/JS runtime expansion, and performance optimization without evidence. No code change was made to stable v1.4.0 or already-published content.

**Accepted QA limitations:** Codex did not independently replay the RC.10 Gutenberg JavaScript/editor workflow. The Product Owner reports successful insertion, editing, URL assignment, save/reopen, and no invalid blocks for the tested compositions. Accessibility has not been formally certified. The HTTP checks covered the site and login endpoints only; they do not certify CSS asset delivery or editor rendering.

## 12. Release notes and conditional release procedure

Prepared [RC.10 release notes](../releases/1.5.0/RC.10-RELEASE-NOTES.md). RC.10 is not stable and not approved for publication. [RC.9 notes](../releases/1.5.0/RC.9-RELEASE-NOTES.md) record why that earlier local artifact is superseded.

After explicit PO approval for the exact source and ZIP only: verify local/remote branch state; push the approved feature branch; prepare/review merge to `main`; update stable source metadata to 1.5.0; rerun tests; build the stable ZIP from the approved source; verify its checksum; create tag `v1.5.0`; publish the GitHub Release with that ZIP; verify asset checksum and main/tag relationship; confirm v1.4.0 remains intact. No step in this procedure was executed.

## 13. Git and final decision

This report was prepared from baseline `bb89a3bfb4138d4b8c5984c508594f0fb0549a6f` on the authorized feature branch. The hardening source, tests, release notes, and candidate builders are consolidated in local commit `fb35e914a61d855a46968cd3ef3e66a31da0c45b` (`fix: finalize SCI Design Blocks 1.5.0 hardening`). This report is a separate documentation-only local change. RC ZIPs and manifests remain local and ignored. No remote operations, tag, merge, or release were performed.

### Gate summary

| Gate | Status | Evidence / limitation |
| --- | --- | --- |
| Pattern/icon/style/CSS inventory | PASS | 27 / 83 / 7 / 3 |
| PHP 8.3 syntax and PHP tests | PASS | Source and extracted RC.10 |
| Python CSS/SVG tests | PASS | Four checks on source and extracted RC.10 |
| WordPress Core parser/serializer | PASS WITH LIMITATIONS | 7.1.3 parser; not JavaScript validation |
| Security/SVG source review | PASS WITH LIMITATIONS | Scoped review; no critical/high finding; not a dynamic scan |
| CTA audit and regression | PASS | Broken fragments removed; author-configured destination required |
| CSS cache versioning | PASS | All three source versions asserted against local filemtime |
| Performance | PASS WITH LIMITATIONS | Equal 0.02 s medians at coarse timing resolution; no HTTP/RSS benchmark |
| PO-reported manual Gutenberg and CTA QA | PASS — PO CONFIRMED | PO confirms insertion, editing, URL, save/reopen and no invalid blocks for tested compositions; Codex replay unavailable |
| Fresh RC.10 clean install / v1.4.0 upgrade | **PASS** | WordPress 7.1.3/PHP 8.3.35; active RC.10; DB checks pass |
| Upgrade content preservation | **PASS** | 20 pages; SHA-256 before/after identical (`a9a37b…cdf3906`) |
| RC.10 site/login HTTP response | **PASS** | Clean and upgrade fixtures returned HTTP 200 on loopback |
| Gutenberg/CTA interactive gate | PASS — PO CONFIRMED / NOT INDEPENDENTLY REPLAYED | Latest PO release instruction confirms tested behaviors; broader JS/editor reproduction remains an evidence limitation |
| Accessibility | ACCEPTED LIMITATION / NOT CERTIFIED | No formal WCAG or exhaustive screen-reader/theme audit; no known confirmed essential-interaction defect |
| v1.4.0 integrity / remote publication | PASS | Stable artifact untouched; no remote operation |

**Disposition at the M9 QA checkpoint:** technical no-go pending PO decision. **Subsequent release disposition:** the Product Owner explicitly confirms interactive Gutenberg/CTA checks and accepts documented QA coverage limitations. The independent Codex browser replay limitation remains disclosed; it is not presented as PO test failure or as a test Codex performed. Stable ZIP-specific install/upgrade and integrity gates must still pass before publication. See `docs/releases/1.5.0/FINAL-RELEASE-REPORT.md` for the final disposition.

## 14. Focused CTA gate — 2026-10-13 follow-up

Confirmed that the clean-install fixture is the isolated WordPress 7.1.3 / PHP 8.3.35 QA site at `127.0.0.1:8095`, with RC.10 active and its database on the dedicated local MariaDB instance. Created a separate temporary administrator only in this fixture and stored its random credential in macOS Keychain. The credential authenticates against the fixture through WP-CLI, but the browser's ordinary login rejected the value copied/pasted through the available UI clipboard path. The editor was not reached. No users other than this new fixture-local account were changed; no password or token is present in output or documentation. RC.10's ZIP and product source were not changed.

**Interactive steps A–J: NOT RUN.** No pattern was inserted; no Core Button was selected or assigned a URL; there was no save/reopen, JavaScript block validation, keyboard/focus test, or link activation test. Therefore no conclusion is claimed about whether the initial no-`href` placeholder is non-focusable/non-announced, or whether its visible instruction prevents a misleading CTA in published content. This specific accessibility/usability risk remains open.

**Single manual PO test:** On the isolated RC.10 clean fixture (`http://127.0.0.1:8095/`), use the temporary QA account's Keychain credential to log in normally. In Gutenberg, insert **SCI CTA — Horizontal**, select its no-URL Core Button, assign `https://example.com/cta-qa-check`, save, exit, and reopen. Confirm no invalid-block warning and that the URL persists. Use only Tab/Shift+Tab/Enter to confirm visible focus and that Enter follows the link. Before assigning the URL, check that the placeholder is not exposed as a working keyboard/screen-reader link and its label clearly tells editors to configure the destination before publishing. Report this as one focused pass/fail check; do not alter the ZIP or run broader audits.
