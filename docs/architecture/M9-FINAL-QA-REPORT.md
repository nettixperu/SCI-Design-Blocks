# SCI Design Blocks — M9 final QA and release preparation

**Date:** 2026-10-13
**Branch:** `feature/sci-pattern-library-expansion`
**Baseline:** `6a65d35051e6d2623d0970819e18daeb3c61103d`
**Candidate:** `1.5.0-rc.8` (local only; not approved for release)
**Decision:** **TECHNICAL NO-GO / PENDING PRODUCT OWNER EDITOR AND ACCESSIBILITY VALIDATION**

## Executive summary

The 27-pattern source passed its PHP, Python, Core parser/serializer, inventory, and static asset regressions. The actual RC.8 ZIP was installed and activated in a fresh WordPress 7.1.3 database namespace under PHP 8.3.35. A separate v1.4.0-to-RC.8 fixture upgraded successfully without changing a representative saved-content hash. The five M8 compositions retained identical saved-content hashes with SCI deactivated. The candidate frontend returned HTTP 200 and Services — Image Cards met the five requested viewport column/overflow checks in Twenty Twenty-Five and Twenty Twenty-One.

No production/runtime source changed after RC.8, so RC.8 remains the release candidate; no RC.9 was built. The Gutenberg editor could not be authenticated for this run. Consequently actual editor insertion, JavaScript `validateBlock`, selecting media, save/reopen, keyboard interaction, and formal accessibility certification remain pending. These are release-blocking gates; this report does not certify 1.5.0 for release.

## Inventory and scope

| Item | Result |
| --- | ---: |
| Gutenberg patterns | 27 |
| Curated icons | 83 |
| Core Block Styles | 7 |
| CSS files | 3 |
| SCI custom blocks | 0 |
| SCI frontend JavaScript | 0 |
| New runtime dependencies | 0 |

The M8 additions are Section — Intro, Benefits — Grid, Services — Image Cards, Pricing — Split Offer, and CTA — Horizontal. Existing patterns and stable 1.4.0 content were preserved. No product code, custom CSS, frontend JavaScript, dependency, or public contract changed during M9. README, preview documentation, changelog, this report, and the local-artifact ignore rule were updated.

## Gutenberg serialization and editor validation

PHP 8.3 tests using WordPress Core 7.1.3 parsed and re-serialized all 27 patterns. Strict block-comment nesting checks found no mismatched or unclosed boundaries in the five M8 patterns. The reviewed self-closing Core blocks are limited to the known dynamic/no-saved-markup cases; no self-closing `core/image` remains. Service Cards contains three complete Core Image comment/figure/image serializations with 4:3 and cover attributes matching the WordPress 7.1.3 save-markup regression fixture.

These checks are structural and serialization evidence only. Opening the editor redirects to the local WordPress login. No authenticated Gutenberg session was available; no JavaScript `validateBlock` invocation or browser editor insertion/edit/save/reopen was performed. Media selection, alt-text editing, and visual comparison with Inserter thumbnails are therefore **NOT VERIFIED**. An Inserter preview is not evidence of the rendered saved pattern.

## Responsive and theme portability

M8 browser evidence covers each of the five new sections at 1440, 1024, 768, 390, and 320 CSS pixels in Twenty Twenty-Five 1.5 and Twenty Twenty-One 2.9: the `alignfull` section root matched the viewport, with no horizontal overflow. This existing evidence is recorded in [M8-FULL-WIDTH-REPORT.md](M8-FULL-WIDTH-REPORT.md).

Additionally, the installed RC.8 ZIP's Services — Image Cards page was measured in Chrome at all five widths in both themes. The grid remained three columns at 1440/1024, two at 768, and one at 390/320; page scroll width equaled viewport width at every size. The three CTA top coordinates aligned within each row (including the two-card row at 768). No image was selected in this run: blank Core Image blocks emit no image element on the public frontend until media is assigned. Image crop, alt text, and the actual in-editor placeholder affordance remain pending editor validation.

Twenty Twenty-One provides a classic-theme comparison; Twenty Twenty-Five provides a block-theme comparison. Theme-native typography, colors, and spacing differ as expected. No CSS or fixed heights were introduced.

## Accessibility

Static and rendered markup review found the page title at H1, the Service Cards section at H2, and its three cards at H3. Native anchors are used for CTAs, and source order is preserved. Image slots are empty and expose no frontend image until the editor user selects media; the pattern documentation instructs authors to provide meaningful alt text.

No keyboard-only, screen-reader, touch-target, contrast sampling across themes, or formal WCAG audit was performed. The editor workflow for choosing media and entering alt text was not exercised. Accessibility is **PARTIAL / NOT CERTIFIED**, not a PASS. Product Owner review should confirm keyboard focus, understandable link names/destinations, image alternatives, readable contrast, and mobile controls in the editor and frontend.

## Installation, upgrade, and no-lock-in

**Clean install:** the actual `sci-design-blocks-1.5.0-rc.8.zip` was installed and activated in a new WordPress 7.1.3 copy with a fresh WordPress table-prefix namespace under PHP 8.3.35 arm64. Runtime registries reported 27 SCI patterns, 83 SCI icons, and seven SCI Block Styles. The three CSS assets were present. The database integrity check passed; each of the five pattern pages returned HTTP 200. No fatal error was observed.

The MariaDB QA account cannot create databases. To keep the test isolated without changing existing fixture tables, the two disposable WordPress copies used separate, previously assigned QA databases and newly namespaced table prefixes. The existing QA main and historical fixture tables were not targeted or overwritten.

**Upgrade:** the local official v1.4.0 ZIP (`1d8e81f2da031413a10ae5551152c1597bfda8f8a90a9d5ace2e27ef3ed68d93`) was installed and activated in a separate WordPress 7.1.3 copy. A representative page with headings, paragraph/link, list, Core Image, button, and columns was saved; its content SHA-256 before the upgrade was `88fdf029ab6a414d0c72aef4cc385d31f7953ebb89475e380e27c474d28140c0`. Installing and activating RC.8 preserved the exact hash. The active plugin then reported `1.5.0-rc.8`; the DB check passed.

**No-lock-in:** the five M8 patterns were saved as Core content in the disposable RC.8 clean-install site. Each page's content SHA-256 was recorded before and after deactivating SCI; all five values were identical. SCI was reactivated, and the content remained intact. This verifies persistence of stored Core markup, not editor editing while SCI is absent.

## PHP, automated regression, performance, and security

PHP 8.3.35 (arm64) actually ran WordPress 7.1.3 and the installed RC.8 fixtures. PHP lint passed for all 14 PHP files. Four PHP test suites passed: icon/runtime inventory; editorial Query; pricing, pattern registration, strict boundaries, and serialization; and Tabs round-trip. All four Python checks passed, covering Accordion, editorial image CSS, Tabs CSS, and 83 SVG/icon sources. The tests confirmed the 27-pattern inventory, Core-only block types, seven styles, and no new dependencies. `git diff --check` passed.

The RC.8 ZIP is 80,233 bytes and contains the existing three CSS files, with no SCI frontend JavaScript. No comparative performance benchmark was run. Incremental source review covered the new pattern registration and Core markup, fixed repository paths, external URLs/assets, PHP escaping/input handling, endpoints/permissions, and dependency changes. No new endpoint, user-input processing, remote asset, secret, custom block, or runtime dependency was introduced; no critical/high security issue was identified. This was a scoped source review, not a full dynamic security scan or performance certification.

## Candidate identity and versioning

RC.8 remains byte-identical to its prior artifact:

- ZIP: `release-candidates/sci-design-blocks-1.5.0-rc.8.zip`
- SHA-256: `d91480a3db2be091f824b42fee9cad81bf8c7f2a041e26035bbc3978767faa81`
- Source manifest: `release-candidates/SOURCE-FILE-MANIFEST-rc.8.sha256` (111 entries, all verified)
- Package manifest: `release-candidates/RC-FILE-MANIFEST-rc.8.sha256` (111 entries, all verified)
- ZIP integrity: PASS

The packaging convention is unchanged: source metadata remains `1.5.0-rc.3`, and the build script applies candidate-specific `1.5.0-rc.8` metadata to a staging copy. Stable v1.4.0 was not modified; its ZIP hash remained `1d8e81f2da031413a10ae5551152c1597bfda8f8a90a9d5ace2e27ef3ed68d93`. RC.6 and RC.7 remain preserved. Because M9 made no runtime/product change, rebuilding a candidate would be unnecessary and RC.8 was not modified.

## PO validation handoff and remaining gates

A single local fixture page containing all five M8 patterns is available at `http://127.0.0.1:8093/?page_id=10`; open its Edit link in the disposable site's admin. Please complete one focused round:

1. Confirm all five patterns open without invalid-block notices; insert each from the SCI inserter into a test page if insertion itself needs confirmation.
2. Edit representative headings, paragraphs, columns, and CTAs. Select a local image in each Service Card and set suitable alternative text.
3. Save, close, and reopen. Confirm no invalidation, content loss, or changed image/CTA values; inspect console validation output.
4. Confirm the frontend at 1440, 1024, 768, 390, and 320 px in Twenty Twenty-Five and Twenty Twenty-One; check keyboard focus, contrast, CTA names/destinations, and image alternatives.

The local `m9qa` editor account uses a randomly generated credential stored in the macOS login Keychain under service `SCI Design Blocks M9 QA (localhost)`; no password is included in repository files or this report. The PO can retrieve it locally through Keychain Access. The editor gate remains **PENDING PO VALIDATION** until an authenticated interactive session supplies this evidence.

| Gate | Result | Remaining action |
| --- | --- | --- |
| Inventory, PHP/Python, parser/serializer, asset regression | **PASS** | None for this candidate |
| PHP 8.3 / WordPress 7.1 runtime | **PASS** | None for this candidate |
| Clean installation and v1.4.0 upgrade | **PASS WITH LIMITATIONS** | Physical database creation is restricted; isolated new table-prefix namespaces were used |
| No-lock-in content persistence | **PASS WITH LIMITATIONS** | Interactive post-toggle editing not exercised |
| Responsive layout / two themes | **PASS** for tested frontend widths | Interactive editor canvas remains unverified |
| Gutenberg insertion, selection, save/reopen, JS validation | **PENDING PO VALIDATION** | Complete the one focused editor round above |
| Accessibility | **PARTIAL / NOT CERTIFIED** | Keyboard, screen reader, contrast, touch target, and editable alt workflow checks |
| Security/performance | **PASS WITH LIMITATIONS** | No full dynamic security scan or comparative performance benchmark |
| Release authorization | **NO-GO** | Requires completion/review of open technical gates and explicit PO release approval |

## Git and publication state

This report is included with the M7/M8 work and regression tests in one selective, local-only consolidation on `feature/sci-pattern-library-expansion`. The commit SHA is returned in the final handoff (`git log -1`). RC ZIPs and manifests remain local and are ignored/not staged. No push, merge, tag, GitHub release, or stable publication is authorized or performed. `main` and tag `v1.4.0` remain untouched.

**Final disposition:** M9 technical certification is **NO-GO** until the Gutenberg interactive and accessibility gates are reviewed. `1.5.0` is **NOT RELEASED**. After the focused PO round, Engineering can assess the remaining evidence for a separate release decision; publication remains exclusively a Product Owner decision.
