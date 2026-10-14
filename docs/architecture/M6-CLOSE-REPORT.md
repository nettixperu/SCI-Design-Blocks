# SCI Design Blocks — M6-CLOSE Final Certification

**Result:** Certification complete with open gates / **RELEASE NO-GO**.<br>
**Branch:** `feature/sci-hero-patterns-css-compat`<br>
**HEAD:** `e078f9f2ab9572c57bb4d9c536267c1ec4f73c12` (worktree changes are not committed)<br>
**Stable baseline:** 1.4.0, published and frozen<br>
**Candidate:** 1.5.0-rc.2, local QA only<br>
**Scope:** final evidence assessment; no new functionality or architecture work.

## Executive summary

The automated/source certification available on this MacBook passes: registration and static inventory, PHP parser/serializer regressions for 22 registered patterns, the Hero Split empty Image markup regression, PHP lint, Python asset/style checks, and RC.2 archive/manifests. The confirmed `core/image` serialization defect was corrected in Hero Split only. The PO reported a post-fix screenshot without the original warning; that is favorable insertion evidence, not evidence of image selection/change, save, close, and reopen.

**M6 is not closed as PASS.** The current classification is **CERTIFICATION COMPLETE WITH OPEN GATES / RELEASE NO-GO**. Browser/editor save-reopen, full responsive and accessibility QA, PHP 7.4 runtime, and RC.2 install/upgrade remain unverified or pending. No publication or approval is implied.

## Baseline and change control

Read and reconciled the current SDD, M5 report, M6 certification/final reports, M6-FIX-01 report, and PO QA checklist. No historical certification report was rewritten.

| Check | Result |
| --- | --- |
| Current branch | `feature/sci-hero-patterns-css-compat` |
| HEAD | `e078f9f2ab9572c57bb4d9c536267c1ec4f73c12` |
| `git diff --check` | PASS |
| Existing worktree edits | Preserved; no reset, clean, checkout, or commit |
| Commit / push / merge / tag / release | None |
| `main` / stable 1.4.0 | Not modified; source plugin header remains 1.4.0 |

At this close, `git status` shows 10 modified tracked files and 10 untracked files from the accumulated M5/M6 work. The M6-FIX-01 source delta is confined to Hero Split markup plus its regression coverage; this report and the active PO checklist are documentation updates. The RC staging header is changed only in the packaged copy.

## Confirmed inventory

| Component | Count / state | Evidence |
| --- | ---: | --- |
| Registered patterns | 22 = 20 prior + Hero Cover + Hero Split | PHP registration and test inventory |
| Curated icons | 83 | Manifest/source test |
| Core Block Styles | 7 | Registration test and prior WP runtime evidence |
| SCI CSS assets | 3 | CSS checks and prior delivery matrix |
| Custom SCI blocks | 0 | Static registration/source inspection |
| SCI frontend JavaScript | 0 | Runtime/source inventory |
| New external runtime dependencies | 0 | Runtime/source inventory |
| Public Hero slugs | `sci-design-blocks/hero-cover`, `sci-design-blocks/hero-split` | Registration assertions |

## Hero patterns

### Hero Split

The PO's real Gutenberg JavaScript validator confirmed that the old self-closing `core/image` failed because Gutenberg expected a saved `<figure>` and `<img>`. Hero Split now stores the empty editable Image using the PO-confirmed serialized form. The PHP regression checks the exact markup, absence of a URL, round-trip stability, and the reviewed self-closing block allowlist. A PO screenshot after the fix showed no original warning; no post-fix evidence of selecting/changing an image, editing alt text, saving, closing, and reopening was supplied in this close.

**State:** source/markup regression PASS; **post-fix interactive editor gate PENDING PO VALIDATION**.

### Hero Cover

The pattern uses Core Cover and nested editable Core blocks, with no empty Image block or remote image requirement. It was not modified. Prior static/runtime evidence is retained. The current close has no complete interactive insert/edit/save/reopen proof for title, copy, media, overlay, and CTAs.

**State:** source review PASS; **interactive editor gate PENDING PO VALIDATION**.

## Regression, Block Styles, and compatibility

Commands run in the repository:

```sh
find . -path './.git' -prune -o -name '*.php' -type f -print0 | xargs -0 -n1 php -l
php tests/check-icon-library.php
php tests/check-editorial-query-patterns.php ${SCI_QA_ROOT}/site
php tests/check-pricing-comparison-patterns.php ${SCI_QA_ROOT}/site
php tests/check-tabs-roundtrip.php ${SCI_QA_ROOT}/site
python3 tests/check-icon-svg.py
python3 tests/check-accordion-css.py
python3 tests/check-tabs-css.py
python3 tests/check-editorial-image-css.py
git diff --check
```

All pass. The updated Hero regression round-trips all 22 registered patterns and reviews 38 self-closing instances. Those instances are restricted to Core Icon without static save markup and dynamic Query blocks; no self-closing `core/image` or unreviewed self-closing block type remains. PHP parser/serializer checks are not equivalent to Gutenberg editor validation.

Prior M6 runtime evidence covers WordPress 7.1/7.1.3 test paths and the seven unchanged Block Styles on Twenty Twenty-Five and Twenty Twenty-One: type-level stylesheet delivery, one link per relevant type, no relevant CSS on a no-type page, and no duplicate links. This is registration/server-output evidence, not computed editor styling or a complete visual review. The three existing CSS assets remain unchanged: Accordion 1,076 bytes; Tabs 2,202 bytes; Editorial Image 580 bytes.

## PHP, WordPress, and QA laboratory

- Local PHP is 8.5.7. No PHP 7.4 binary/formula, Docker, or Podman runtime was found. PHP 7.4 runtime compatibility is **NOT VERIFIED**; no installation or global PHP change was attempted.
- `wp core version` reads WordPress 7.1.3. Current `wp option get stylesheet` and plugin-version queries fail during SQLite adapter initialization with `Cannot escape data without an active database connection`.
- `http://${QA_HTTP_HOST}:${QA_HTTP_PORT}/` is not listening. A read-only `sqlite3` integrity check could not open the DB file in this sandbox. These failures do not prove database corruption. Historical isolated install/upgrade and database-integrity evidence in the M6 certification report is preserved, but no RC.2 install/upgrade was performed in this close.
- No main QA database repair, WordPress reinstall, engine migration, server restart, or destructive test was performed.

## Security, performance, and no-lock-in

The earlier offline audit reviewed 144 worktree paths and reported zero findings. The incremental M6-FIX-01 delta was reviewed: it is static Core block markup plus test assertions/documentation, with no executable markup, remote asset, new path handling, dependency, or runtime code. This incremental review is not a fresh full audit or a production security assessment.

The architecture retains Core content and type-level stylesheet delivery. Prior M5/M6 QA recorded Hero fixture content persisting with SCI inactive and upgrade persistence for the prior RC.2 predecessor. The changed empty Image has not yet been edited/saved/reopened and deactivated interactively in the new candidate, so that specific end-to-end no-lock-in check remains open. No comparative performance benchmark was run.

## Responsive and accessibility

No complete current-browser run was available for 1440, 768, 390, or 320 px across Heroes, pricing/cards, Accordion, Tabs, and Hover Zoom. Prior Hero Cover spot observations are partial and do not certify this matrix. Keyboard/focus, tab order, contrast, touch targets, screen-reader order, and interaction semantics have not been fully tested. No WCAG conformance claim is made.

**State:** NOT VERIFIED / PENDING PO VALIDATION.

## RC.2 integrity and traceability

RC.2 is stored beside RC.1 in `${SCI_QA_ROOT}/release-candidates/`. ZIP integrity passes; 105 package files and 105 source files are represented in the manifests. Source manifest entries were checked. There are no symlinks. Compared with RC.1, package content changes only in Hero Split markup and the staged plugin-header version.

| Artifact | SHA-256 |
| --- | --- |
| [`sci-design-blocks-1.5.0-rc.2-m6.zip`](<${SCI_QA_ROOT}/release-candidates/sci-design-blocks-1.5.0-rc.2-m6.zip>) | `f8d5d7c65cb17b1645d407324d73f8c44c8cb1b4b1dbeb8977af23ef007ef477` |
| [`RC-FILE-MANIFEST-rc.2.sha256`](<${SCI_QA_ROOT}/release-candidates/RC-FILE-MANIFEST-rc.2.sha256>) | `f906b6d125b4217a8263545495ad5eab5e7046bb91d8cfa5f918fcb6beb0de4d` |
| [`SOURCE-FILE-MANIFEST-rc.2.sha256`](<${SCI_QA_ROOT}/release-candidates/SOURCE-FILE-MANIFEST-rc.2.sha256>) | `a685bb5f75709914b3ff5557084881b52ac4f635f04377a9ed147ad2c9b43368` |
| RC.1 preserved unchanged | `0cd716511c863f0086821128fda592b830ca42614a4e96e0c8244814b4a83ed0` |

RC.2 is a QA candidate, not an approved release. No GitHub Release or publication was made.

## Final gates

| Gate | Evidence | Result | Blocking / owner | Next action |
| --- | --- | --- | --- | --- |
| Pattern/inventory registration | 22 patterns, 83 icons, 7 styles, 3 CSS; static checks | PASS | No | None |
| PHP lint + PHP/Python regression | Full command set above | PASS | No | None |
| Hero Split confirmed root cause/fix | PO validator evidence before fix; exact markup regression after fix | PASS WITH LIMITATIONS | Yes / PO | Insert, choose/change image, edit alt/content, save, close, reopen, check console |
| Hero Cover editor workflow | Historical static/runtime evidence only | PENDING PO VALIDATION | Yes / PO | Insert/edit media/overlay/text/CTAs; save and reopen |
| Gutenberg JS validation after fix | Not supplied/executed post-fix | PENDING PO VALIDATION | Yes / PO | Complete Hero Split workflow; capture validation/console result |
| Block Style CSS delivery | Prior WP 7.1.3 TT5/TT21 HTTP/link matrix | PASS WITH LIMITATIONS | Visual gate / PO | Check editor/computed styles and theme appearance |
| WordPress minimum | Historical tests on WP 7.1/7.1.3; current CLI reads 7.1.3 | PASS WITH LIMITATIONS | No, within tested paths | Record exact staging version; no claim beyond evidence |
| PHP 7.4 runtime | No runtime available | NOT VERIFIED | Yes / Engineering | Arrange isolated trusted 7.4 runtime; do not lower requirement |
| RC.2 archive/manifests | Hashes, 105 entries, `unzip -t`, source checks | PASS | No | Install and exercise on disposable staging |
| RC.2 install/activate/upgrade | Not performed in this close | NOT VERIFIED | Yes / PO/Engineering | Test clean install and 1.4.0 upgrade on isolated staging |
| Theme/plugin/SQLite current lab status | HTTP down; WP-CLI DB queries fail; read-only sqlite inaccessible | BLOCKED | Yes / Engineering | Restore authorized local lab access; do not infer corruption |
| Responsive matrix | No complete browser run | NOT VERIFIED | Yes / PO | Check 1440/768/390/320 and record screenshots |
| Accessibility basics | No complete keyboard/contrast/assistive-tech run | NOT VERIFIED | Yes / PO | Complete checklist; no WCAG claim |
| No-lock-in for prior fixtures | Prior plugin-off/persistence evidence; new Image workflow pending | PASS WITH LIMITATIONS | Yes / PO | Verify corrected Image persists and remains Core-editable with SCI disabled on disposable QA |
| Security | Prior 144-path offline scan, zero findings; incremental static review | PASS WITH LIMITATIONS | No production assurance | No full rescan absent broader code change |
| PO approval / publication | No release approval | NOT AUTHORIZED | Yes / PO | Separate explicit decision required; never publish automatically |

## Release readiness and next steps

**Technical decision: NO-GO for stable release.** Mandatory editor, PHP 7.4, installation/upgrade, responsive, and accessibility gates remain open. The appropriate milestone description is **CERTIFICATION COMPLETE WITH OPEN GATES / RELEASE NO-GO**; M6 is not `CLOSED / PASS`.

1. PO completes and returns the updated [RC.2 QA checklist](PO-QA-CHECKLIST.md), including Hero Split selection/change/save/reopen and Hero Cover save/reopen.
2. Engineering restores a non-destructive local lab path and runs RC.2 clean install and upgrade on isolated copies.
3. Obtain isolated PHP 7.4 runtime evidence without changing the Mac's global PHP.
4. PO records responsive and accessibility checks with versions, viewports, methods, and evidence.
5. Reassess the gate matrix. Stable release still requires explicit separate PO authorization.
