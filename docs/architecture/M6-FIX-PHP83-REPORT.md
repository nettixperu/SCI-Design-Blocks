# M6-FIX-PHP83 — Runtime recovery and minimum-version certification

**Date:** 2026-10-13<br>
**Branch / HEAD:** `feature/sci-hero-patterns-css-compat` / `e078f9f2ab9572c57bb4d9c536267c1ec4f73c12`<br>
**Candidate:** SCI Design Blocks 1.5.0-rc.3<br>
**PHP minimum:** 8.3, approved by the Product Owner<br>
**Scope:** recover an isolated PHP 8.3 CLI and execute RC.3 on WordPress 7.1.3; no product-code changes.

## Executive summary

**PHP 8.3 runtime = PASS** for PHP 8.3.35 (arm64), WordPress 7.1.3 and the isolated RC.3 fixture. The failure was the missing direct Homebrew dependency `aspell`, not an ABI mismatch in the other installed Homebrew libraries. A dry-run showed that installing `aspell` alone would add one formula and not upgrade or replace shared packages; only that formula was installed. PHP 8.5.7, Homebrew links/services, existing WordPress sites and the MariaDB QA instance remain unchanged.

All 13 repository PHP files linted using the absolute PHP 8.3.35 binary. All four PHP tests/parser checks, four Python SVG/CSS checks, WP-CLI database/runtime checks, inventory checks and HTTP rendering checks passed. A reversible plugin-off test confirmed the two Hero pages retain identical serialized content. This closes the PHP minimum gate, **not M6**: interactive Gutenberg validation, full responsive coverage and accessibility remain open, so M6 is still **ACTIVE / TECHNICAL NO-GO**.

## Preflight and root cause

| Check | Result |
|---|---|
| macOS / architecture | macOS 15.6; Apple Silicon `arm64` |
| Homebrew | `${HOMEBREW_PREFIX}/bin/brew`, prefix `${HOMEBREW_PREFIX}` |
| Default PHP before/after | PHP 8.5.7; remains `${HOMEBREW_PREFIX}/bin/php` |
| Alternate formula | `php@8.3` 8.3.35_1 at `${PHP83_PREFIX}`; keg-only, not linked |
| Failure before repair | `dyld: Library not loaded: ${HOMEBREW_PREFIX}/opt/aspell/lib/libaspell.15.dylib` |
| Other missing Homebrew dylibs | None after dependency installation; all Homebrew paths listed by `otool -L` resolve |
| PHP required module | `pspell`; PHP's dynamic dependencies also include `libaspell.15.dylib` and `libpspell.15.dylib` |

`brew info php@8.3` reported 47 of 48 recursive runtime dependencies installed. `aspell` is a direct formula dependency and was absent from both `${HOMEBREW_PREFIX}/Cellar` and `${HOMEBREW_PREFIX}/opt`. `file` confirmed the PHP executable is Mach-O arm64. `otool -L` showed the two expected aspell libraries at the missing `opt/aspell` path; these were genuine absent files, not an existing-library ABI or path mismatch. After installation, the Homebrew-linked dynamic-library paths resolved and PHP started without dyld errors.

## Dependency plan and action

The ordinary `brew install php@8.3` plan proposed upgrading 22 already-installed formulas. It was cancelled before those upgrades. No forced linking or dependency-ignoring install flags were used in this recovery.

The read-only `brew install --dry-run aspell` plan reported exactly one formula: `aspell 0.60.8.2`; it proposed no dependency upgrades, replacements or service restarts. Under the PO-authorized A2 scope, that single formula was installed at `${HOMEBREW_PREFIX}/Cellar/aspell/0.60.8.2` (338.4 MB). It provides both `libaspell.15.dylib` and `libpspell.15.dylib`. No unrelated Homebrew formula changed version. Homebrew services report `mariadb`, `php`, and `php@8.3` as `none`; no PHP-FPM service was started.

PHP 8.3 uses `${HOMEBREW_PREFIX}/etc/php/8.3/php.ini`, with no additional `.ini` files loaded. No global PATH, PHP link, PHP 8.5 configuration, SIP setting, or MariaDB service was changed.

## Runtime, lint and PHP regression

Runtime command used the absolute binary:

```sh
${PHP83_BIN} -v
${PHP83_BIN} -m
${PHP83_BIN} --ini
```

Result: PHP 8.3.35 CLI, 64-bit Darwin, arm64 Mach-O. Required modules are loaded, including `mysqli`, `mbstring`, `json`, `openssl`, `curl`, `zip`, `intl`, `gd`, `pdo_mysql` and `pspell`.

The same absolute binary linted all **13** repository PHP files: PASS, zero syntax errors. The following PHP checks passed with PHP 8.3.35:

```sh
${PHP83_BIN} tests/check-icon-library.php
${PHP83_BIN} tests/check-editorial-query-patterns.php ${SCI_QA_SITE_DIR}/fixtures/m6-rc3-clean-2026-10-13
${PHP83_BIN} tests/check-pricing-comparison-patterns.php ${SCI_QA_SITE_DIR}/fixtures/m6-rc3-clean-2026-10-13
${PHP83_BIN} tests/check-tabs-roundtrip.php ${SCI_QA_SITE_DIR}/fixtures/m6-rc3-clean-2026-10-13
```

The test result included 22 patterns; the empty Hero Split `core/image` serialization regression passed; 38 reviewed self-closing blocks had no `core/image` instance; all Tabs variants round-tripped. All four existing Python SVG/CSS checks also passed (83 safe SVG sources, approved three stylesheets only, no frontend JS or dependency manifest).

## WordPress 7.1.3 and RC.3 runtime

Fixture: `${SCI_QA_SITE_DIR}/fixtures/m6-rc3-clean-2026-10-13`. This was created separately with its own database/account on the dedicated QA MariaDB at `${QA_DB_HOST}:${QA_DB_PORT}`; existing sites and data were not copied. RC.3 was installed from its existing ZIP and activated. No candidate files or manifests were modified.

WP-CLI 2.12.0 was launched through PHP 8.3.35 using:

```sh
${PHP83_BIN} ${HOMEBREW_PREFIX}/bin/wp core version --path=${SCI_QA_SITE_DIR}/fixtures/m6-rc3-clean-2026-10-13
```

Observed runtime: WordPress 7.1.3, PHP 8.3.35, RC.3 active; database check passed. Runtime registry/file counts:

| Inventory | Result |
|---|---:|
| SCI patterns | 22 |
| Icons | 83 |
| Core Block Styles | 7 |
| CSS assets | 3 |
| Custom SCI blocks | 0 |
| SCI frontend JavaScript | 0 |
| New runtime dependencies | 0 |

The fixture's active theme is Twenty Twenty-Five 1.5. Hero Cover and Hero Split QA pages rendered. The WP-CLI PHP development server bound only to `${QA_HTTP_HOST}:${QA_HTTP_PORT}`, then was stopped after these checks:

| HTTP endpoint | Result |
|---|---:|
| `/` | 200 |
| `/?page_id=4` (Hero Cover fixture) | 200 |
| `/?page_id=5` (Hero Split fixture) | 200 |
| `/wp-login.php` | 200 |
| Each of the three SCI CSS asset URLs | 200 |

HTTP success proves server rendering/asset availability, not browser layout, editor behavior or accessibility.

## No-lock-in and service impact

With PHP 8.3.35/WP-CLI, the two Hero pages had aggregate content SHA-256 `330a1e5873eaa6cd0ea2e64bf14beac1986624e7bbca75156293aae3bfa7db4c`. SCI was temporarily deactivated in this new fixture: both pages remained present, the same content hash remained, and Core parsing found four top-level block nodes. SCI was then reactivated and the DB check passed. The fixture is left with RC.3 active.

Post-checks confirmed:

- PHP 8.5.7 still runs as the default PHP.
- The original `wordpress/` and `fixtures/upgrade/wordpress/` sites remain on WordPress 7.1.3 and RC.2; both databases pass `wp db check`.
- The dedicated MariaDB responds to ping. Existing localhost sites `8087`, `8088`, and `8089` return HTTP 200.
- The PHP 8.3 QA server on `8092` is stopped. `brew services list` shows no Homebrew-managed MariaDB/PHP/PHP 8.3 service.
- Only the missing `aspell` formula was added; the previously installed shared formula versions were unchanged.
- No change to another project, the SQLite lab, production, the main PHP link, or a global service was made.

## Candidate integrity and Git

RC.3 identity remains ZIP SHA-256 `9b7ffdbeca8f11ca0771bbe0b61b6d6ac6e8471cf011ec611a371a75c743cdd7`; its 105-entry source and package manifests and `unzip -t` were verified in the prior close and rechecked for this task. RC.2 remains `f8d5d7c65cb17b1645d407324d73f8c44c8cb1b4b1dbeb8977af23ef007ef477`; stable 1.4.0 remains `1d8e81f2da031413a10ae5551152c1597bfda8f8a90a9d5ace2e27ef3ed68d93`.

Branch and HEAD remain as stated at the top. `git diff --check`: PASS. This fix changed no product code, runtime metadata, RC ZIP, or manifests. No commit, push, merge, tag or release was made.

## Gate result and remaining work

| Gate | State | Note |
|---|---|---|
| PHP 8.3 runtime (minimum-version gate) | **PASS** | PHP 8.3.35 actually ran WordPress 7.1.3 and active RC.3. |
| PHP lint and PHP tests on PHP 8.3 | **PASS** | 13 PHP files; four PHP test/parser commands. |
| PHP 8.3 inventory, load and rendering | **PASS** | Counts, DB check, Hero responses and CSS URLs passed. |
| No-lock-in in fixture | **PASS WITH LIMITATIONS** | Serialized content survived plugin off/on; no interactive editor image selection was tested. |
| Homebrew/dependency impact | **PASS WITH LIMITATIONS** | Only `aspell` installed; no shared formula upgrades. |
| Gutenberg interactive validation | **NOT VERIFIED** | Manual editor insert/edit/image/CTA/save/reopen remains open. |
| Responsive matrix | **NOT VERIFIED** | 1440/768/390/320 visual checks remain open. |
| Accessibility | **NOT VERIFIED** | Manual keyboard/focus/contrast/alt/tap-target checks remain open. |

**PHP 8.3 = PASS. M6 = ACTIVE / TECHNICAL NO-GO.** Do not treat this runtime recovery as M6 closure or release approval. No commit, push, tag or publication is authorized.
