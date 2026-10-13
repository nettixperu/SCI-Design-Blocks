# M6 approved Product Owner decision — PHP 8.3 minimum

**Decision date:** 2026-10-13<br>
**Approver:** Product Owner<br>
**Milestone:** M6<br>
**Applies to:** SCI Design Blocks 1.5.0 and its release candidates only

## Decision

The Product Owner approves raising the minimum PHP requirement for SCI Design Blocks 1.5.0 from PHP 7.4 to **PHP 8.3**. Remove PHP 7.4 as a current M6/1.5.0 certification gate and use PHP 8.3 as the required minimum-version gate.

This decision does not retroactively change stable 1.4.0, its published `v1.4.0` tag or ZIP, its PHP 7.4+ requirement, or the historical evidence and reports for prior releases. Historical reports that mention a PHP 7.4 gate remain unchanged; their PHP statements describe the requirements in force when those reports were written. This decision supersedes the former PHP 7.4 requirement only for 1.5.0.

## Required implementation and certification

- Plugin distribution metadata for the new candidate must identify version `1.5.0-rc.3` and `Requires PHP: 8.3`.
- Current development/release documentation and the minimum-requirement regression test must identify PHP 8.3 for 1.5.0 while clearly retaining PHP 7.4 for stable 1.4.0.
- Test the suite under an actual PHP 8.3 runtime if one is available. A newer PHP runtime is useful regression evidence but is not a substitute for testing the declared minimum.
- Re-run PHP lint, PHP/Python regression, parser/serializer and registration/inventory checks: 22 patterns, 83 icons, seven Block Styles, three CSS assets, zero custom SCI blocks, zero frontend JavaScript, and no added runtime dependencies.
- Keep RC.2 and its manifests intact. Prepare a separately identified RC.3 with new source/package manifests and a newly computed SHA-256. Do not publish it.

## Runtime discovery and current result

Initial discovery found PHP CLI 8.5.7 and no usable PHP 8.3 runtime. The official Homebrew `php@8.3` 8.3.35_1 formula was installed keg-only at `${PHP83_PREFIX}`, without linking it or changing global PHP selection. Its first launch identified missing direct dependency `aspell`; `otool -L` showed the expected `libaspell.15.dylib` and `libpspell.15.dylib` paths under `${HOMEBREW_PREFIX}/opt/aspell/lib`, neither of which existed. Homebrew reported 47/48 recursive PHP runtime dependencies installed. A normal PHP formula installation would have upgraded 22 existing formulas and was cancelled. A dry-run of installing `aspell` alone proposed only `aspell 0.60.8.2`, with no dependency upgrades or replacements; that single formula was installed under the approved A2 scope. No symlink was fabricated and no PHP service/global selection was changed.

PHP 8.3.35 now starts successfully as an arm64 CLI using `${HOMEBREW_PREFIX}/etc/php/8.3/php.ini`; required extensions include `mysqli`, `mbstring`, `json`, `openssl`, `curl`, `zip`, `intl` and `gd`. All 13 repository PHP files linted with the absolute PHP 8.3 binary; all four PHP regression/parser tests and all four Python SVG/CSS checks passed. WP-CLI 2.12.0, itself launched with PHP 8.3.35, loaded WordPress 7.1.3 and active RC.3 in an isolated MariaDB fixture, passed the database check, and reported 22 SCI patterns, 83 icons, seven Block Styles and three CSS assets. The loopback PHP 8.3 web server rendered the site and both Hero fixtures successfully (HTTP 200); a temporary plugin-deactivation test preserved both pages' content hash and the plugin was reactivated.

**PHP 8.3 runtime gate: PASS** for the tested PHP 8.3.35 / WordPress 7.1.3 / RC.3 fixture. This closes the minimum-runtime gate only. Gutenberg editor interaction, responsive coverage and accessibility gates remain open, so M6 remains NO-GO. Full commands, impact checks and limitations are in [M6-FIX-PHP83-REPORT.md](M6-FIX-PHP83-REPORT.md); the earlier [M6-MASTER-CLOSE-REPORT.md](M6-MASTER-CLOSE-REPORT.md) is a dated snapshot before this runtime recovery.

## Artifact identity

- Preserved RC.2: `sci-design-blocks-1.5.0-rc.2-m6.zip`, SHA-256 `f8d5d7c65cb17b1645d407324d73f8c44c8cb1b4b1dbeb8977af23ef007ef477`.
- RC.3: `${SCI_QA_ROOT}/release-candidates/sci-design-blocks-1.5.0-rc.3-m6.zip`.
- Manifests: `RC-FILE-MANIFEST-rc.3.sha256` and `SOURCE-FILE-MANIFEST-rc.3.sha256`, stored beside RC.3.

RC.3 SHA-256: `9b7ffdbeca8f11ca0771bbe0b61b6d6ac6e8471cf011ec611a371a75c743cdd7`.

The copied archive passed `unzip -t`; both manifests contain 105 entries and source/package checksums match for every entry. The copied RC.3 SHA-256 was verified as shown above, and RC.2 retained its original SHA-256.

## Change control

This is a Product Owner-approved requirement change, not a release authorization. No commit, push, merge, tag, or release is authorized. Stable 1.4.0, its published artifact, RC.2, and prior milestone reports must remain untouched. Any further change to scope or minimum versions requires a new explicit PO decision.
