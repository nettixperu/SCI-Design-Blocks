# SCI Design Blocks — Software Design Document: Astra/Spectra Migration

**Document state:** M4 final architecture decision record; M5 accepted PASS WITH LIMITATIONS; M6 authorized for QA and RC preparation only (see [M6 certification report](M6-CERTIFICATION-REPORT.md)). A Product Owner amendment dated 2026-10-13 raises the minimum PHP version for 1.5.0 to 8.3; see [M6-PHP83-PO-DECISION.md](M6-PHP83-PO-DECISION.md). Historical 1.4.0 requirements and reports are retained.

**Baseline:** published and frozen SCI Design Blocks 1.4.0 (`v1.4.0`)

**Working branch at preparation:** `spike/astra-patterns`

**Target work:** M6 QA and release-candidate preparation on `feature/sci-hero-patterns-css-compat`; production release remains unauthorized

**Release:** none authorized by this document

This document consolidates the M2 investigations and M3 proof of concept into a reproducible design contract, incorporating the architecture decisions approved for M4-FINAL. M5 implementation was accepted PASS WITH LIMITATIONS; its results are recorded in the linked implementation report. M6 is authorized only for QA, minor corrections within this SDD, and RC preparation. It does not change the behavior, minimum requirements, public identifiers, or contents of 1.4.0. The explicit PHP minimum amendment applies only to planned 1.5.0. Decisions in §§4–8 remain the contract except where the later PO amendment explicitly supersedes a requirement; verification and QA gates remain open where evidence is still required. Production release, commit, push, merge, and tag operations are not authorized.

## 1. Executive summary

The proposed next SCI expansion has exactly three components:

1. Repair delivery compatibility for the seven existing opt-in Block Styles by retaining their current Core registration and handles and adding early Core `wp_enqueue_block_style()` registration for the same handles. Core delivers the associated sheet when its block type renders. This is **type-level**, not selected-variant-level, loading.
2. Add one Core-only Hero Cover pattern.
3. Add one Core-only Hero Split pattern.

The Hero candidates use ordinary Gutenberg blocks, introduce no SCI layout CSS, JavaScript, custom blocks, runtime dependencies, remote assets, options, or migrations, and leave inserted content editable after SCI deactivation. Card Grid remains deferred. Headers, footers, navigation builders, and all other scope exclusions remain out.

M2-05B and M3 provide runtime/HTTP evidence for the early Core API path in WordPress 7.1.3, including TT5 and TT21; M3 compared the API using isolated test identifiers. This path is the approved architecture, not blanket proof for every supported WordPress version, editor context, or theme. Visual/computed style application, editor appearance, the approved PHP 8.3 minimum runtime, and responsive/accessibility interaction checks remain open gates. The earlier PHP 7.4 gate is superseded for 1.5.0 only.

## 2. Baseline, scope, and evidence vocabulary

### 2.1 Product baseline

The repository identifies release 1.4.0 as the stable download. Stable 1.4.0 requires WordPress 7.1 and PHP 7.4; the planned 1.5.0 candidate requires WordPress 7.1 and PHP 8.3 by the approved M6 PO amendment. The license is GPL-2.0-or-later. The 1.4.0 released product contains 20 patterns, 83 curated icons, seven Block Styles, no custom SCI blocks, no SCI frontend JavaScript, and no external runtime dependencies. Do not change these facts or the published release artifact.

The 20 existing pattern registrations and their public slugs are not to be renamed or edited. The seven existing style names/classes are fixed by §5. Existing source architecture is a plugin entry point plus `includes/` registration modules, static markup under `patterns/`, CSS under `assets/css/`, and PHP/Python checks under `tests/`.

### 2.2 Scope

In scope for this design: Block Styles CSS delivery compatibility, Hero Cover, Hero Split, requirements, implementation guidance, compatibility, security, accessibility, maintainability, test design, and M5/M6 gates.

Out of scope: Core Card Grid; new visual Block Styles or icons; custom blocks; frontend JavaScript; CSS frameworks; Astra/Spectra integrations; header/footer builders; proprietary navigation; sticky headers; mega menus; forms, dynamic pricing, checkout; remote asset fetching; and changes to the stable 1.4.0 package. Deferred ideas are not implicit M5 requirements.

### 2.3 Evidence labels

Use these labels in implementation reports and test records:

| Label | Meaning |
|---|---|
| **VERIFIED BY CODE** | Source or static assertions were inspected. Does not establish runtime behavior. |
| **VERIFIED BY RUNTIME** | WordPress/API/parser/serializer or CLI behavior was exercised. |
| **VERIFIED BY HTTP** | A request/response, status, asset link, or rendered HTML was inspected. |
| **VERIFIED BY BROWSER** | Real browser/editor behavior was manually or automatically observed at a stated version/viewport. |
| **DOCUMENTED** | A stated contract or historical record exists; it is not independent test evidence. |
| **INFERRED** | Reasoned from evidence but not directly exercised; identify the premises. |
| **NOT VERIFIED** | No adequate evidence exists. Keep the gate open. |

### 2.4 Evidence reviewed and limitations

Repository sources reviewed: `README.md`, `CHANGELOG.md`, `CONTRIBUTING.md`, `SECURITY.md`, `docs/README.md`, `docs/architecture/SDD-1.4.md`, `docs/architecture/SDD-1.2.md`, `docs/architecture/SDD-1.1.md`, `docs/architecture/ICON-INVENTORY-1.1.md`, the 1.4 release report, registration modules, CSS, patterns, and tests. The stable 1.4 SDD remains historical and is not rewritten.

The local QA laboratory reports M2-02, M2-03, M2-04, M2-05, M2-ENV-02-M2-05B, and `m3-experiments/M3-FINAL-REPORT.md` were available and reviewed. M2-01 was not available; M2-04 independently notes that absence. Therefore the exact historical mapping of the Hero references SCI-001/009 and SCI-008/010 cannot be certified here. No missing evidence is reconstructed or invented. See the QA laboratory reports for complete page IDs, commands, and execution matrices; this SDD summarizes rather than copies them.

## 3. Architecture and responsibility boundaries

Target model:

```text
WordPress Core / Gutenberg
  blocks, pattern APIs, Block Styles API, semantics, interactions, responsive primitives
                         +
Compatible WordPress theme
  site shell, templates/template parts, typography, Global Styles, branding
                         +
SCI Design Blocks
  reusable Core-block compositions, curated icons, optional scoped Core Block Styles
```

SCI must not take over theme responsibilities or build a parallel page builder. Reuse Core first, then existing SCI mechanisms. Patterns are insertion-time compositions; after insertion, the content tree is Core-owned. A registered style is optional decoration, not the content model.

## 4. Component A — Block Styles compatibility

### 4.1 Current contract and observed issue

Current registration modules (`includes/accordion-styles.php`, `includes/tabs-styles.php`, and `includes/editorial-image-styles.php`) register one external stylesheet handle per Core block type and associate it with each named variation using `register_block_style(..., 'style_handle' => $handle)`. Registration runs at `init` priority 20. The CSS selectors are scoped to their existing `is-style-*` classes.

M2 observed that classes and registrations exist in both tested themes, but normal frontend requests under Twenty Twenty-Five (TT5, block theme) omit the CSS; Twenty Twenty-One (TT21, classic theme) prints it. M2-ENV-02/M2-05B attributes the TT5 behavior on WordPress 7.1.3 to timing: Core's on-demand callback is attached during the head asset phase, while TT5 renders the block template (`do_blocks()`) before `wp_head`. TT21 renders page content later. This is a verified cause for the tested versions/themes, not a guarantee that every theme follows identical timing.

### 4.2 Proposed flow

For each existing block type, after registering the existing stylesheet handle and while registering its current variations, also call Core `wp_enqueue_block_style( $block_name, array( 'handle' => $existing_handle ) )` early enough for block-theme template rendering. Keep the current `register_block_style()` calls, handles, CSS paths, selectors, and versioning behavior. Do not globally enqueue styles or build a custom loader.

| Type | Current handle | Variations |
|---|---|---|
| `core/accordion` | `sci-design-blocks-accordion-styles` | `sci-minimal`, `sci-bordered` |
| `core/tabs` | `sci-design-blocks-tabs-styles` | `sci-underline`, `sci-pills`, `sci-connected`, `sci-filled` |
| `core/post-featured-image` | `sci-design-blocks-editorial-image-styles` | `sci-hover-zoom` |

The expected flow is: register one existing handle → register existing editor variations against it → tell Core to deliver that same handle for the block type → Core conditionally enqueues it when the type renders → scoped selector applies only if its SCI class exists. No serialized post content changes.

### 4.3 Decision and trade-off

| Strategy | Benefit | Cost / limitation | Evidence |
|---|---|---|---|
| Existing `style_handle` only | Variant is opt-in in the editor; in TT21 the style sheet is delivered on rendered type. | Misses TT5's pre-rendered template path in tested WP 7.1.3. | M2 runtime/HTTP; M3 mode A. |
| Existing registration plus early `wp_enqueue_block_style()` | Core delivery works in tested TT5 and TT21; type remains conditional; one shared handle/file per type; no custom loader. | **Loads by block type, not by selected variation.** A Core Accordion/Tabs/Featured Image without an SCI class still causes the corresponding small file to load. | M2-05B used actual SCI identifiers; M3 mode B isolated names and exercised variants, controls, both themes, deduplication. **Approved architecture.** |
| Global enqueue or custom loader | Could force delivery independent of render timing. | Broad/unnecessary requests or proprietary complexity; not selected. | Evaluated and rejected in M2/M3. |

Do not describe type-level and variant-level loading as equivalent. **The PO/CTO-approved strategy is type-level delivery.** M3 measured one file per rendered relevant type, none on a page without such types, and one of each on a mixed page; multiple variants of one type deduplicated. M3's no-style controls intentionally loaded the type's file. M5 must retain this contract, avoid duplicate links, and make no claim of variant-only delivery. The measured source-byte cost is in §4.4. This decision does not waive compatibility or visual verification gates.

### 4.4 Current CSS byte counts

Measured from the repository's current CSS files with `wc -c`; these are uncompressed source bytes, not transfer-compressed or browser-timing measurements:

| File | Raw bytes |
|---|---:|
| `assets/css/accordion-styles.css` | 1,076 |
| `assets/css/tabs-styles.css` | 2,202 |
| `assets/css/editorial-image-styles.css` | 580 |
| **Total if all three types occur** | **3,858** |

For a single page, expected extra raw CSS is only the sum for the corresponding rendered types. A block type with no SCI variation still incurs its type's file under the proposed contract. No total-page performance or network transfer benchmark was performed; do not infer one from source bytes.

## 5. Immutable public contracts

Names below are the existing suffixes passed to Core and resulting `is-style-*` classes. They are serialized-content compatibility contracts. Preserve spelling, handles, block types, selectors, and the shared one-sheet-per-type association. Do not migrate or rewrite saved posts.

| Core block | Existing style name | Persisted CSS class |
|---|---|---|
| `core/accordion` | `sci-minimal` | `is-style-sci-minimal` |
| `core/accordion` | `sci-bordered` | `is-style-sci-bordered` |
| `core/tabs` | `sci-underline` | `is-style-sci-underline` |
| `core/tabs` | `sci-pills` | `is-style-sci-pills` |
| `core/tabs` | `sci-connected` | `is-style-sci-connected` |
| `core/tabs` | `sci-filled` | `is-style-sci-filled` |
| `core/post-featured-image` | `sci-hover-zoom` | `is-style-sci-hover-zoom` |

No new variants, renamed classes, serialized attributes, global selectors, or side effects on standard Core markup are proposed.

## 6. Component B — Hero Cover

### 6.1 Approved insertion contract

One unsynced pattern, approved slug `sci-design-blocks/hero-cover`, in the existing SCI pattern category. Use only `core/cover`, `core/group`, `core/heading`, `core/paragraph`, `core/buttons`, and `core/button`. This slug follows the repository's existing `sci-design-blocks/<name>` convention; inspection found no conflict. The source request identifies references SCI-001 and SCI-009; the missing M2-01 archive means their historical mapping cannot be independently checked, but this does not reopen the PO-approved scope. The pattern is one composition; one or two CTAs are edited by adding/removing a Core Button, not separate patterns.

The Cover must have a replaceable editable background image, editable overlay color/opacity, full-width alignment, Core minimum-height setting, and editable left/center content alignment. Content comprises an optional eyebrow, heading, optional description, and one/two CTAs. Use a Core Cover with no initial remote image source so the author can select a licensed image with the native control. Do not bundle proprietary Astra/Spectra imagery, the M3 QA image, or an image with unverified provenance. No external image URL or dependency is permitted.

### 6.2 Markup and semantics

Use generic, clear, editable sample copy without real company names or commercial claims: eyebrow “A flexible approach”; heading “Build a stronger digital experience”; description “Explore an approach that can adapt to your team's needs.” All copy remains real editable text. The initial heading is Core H2 to reduce accidental duplicate H1 on common page templates; its level remains editable in Gutenberg when the Hero is the page's primary heading. No PHP dynamically changes heading level. Validate actual template hierarchy during QA; H2 is a content default, not proof about every theme template.

Treat a Cover background as decorative: do not claim it supplies meaningful image alternative text. If the image conveys content, prefer a content-bearing Core Image composition (or provide that information in adjacent text). Overlay legibility must be checked against actual heading, description, link, hover, and focus colors; an opacity value alone is not contrast evidence.

### 6.3 CTA content

Use Core Buttons/Button only. Insert two generic examples by default; the second is removable for a single-CTA composition. Use clearly marked labels such as “Explore details (example)” and “Get started (example)” with local fragment destinations `#example-details` and `#example-start`. These are editable placeholders, not promised page sections or commercial endpoints; authors must replace the label/target for their page. Do not ship real business names, sales claims, tracking, external URLs, or fabricated contact details. M5 verifies the sample URLs remain local and editable.

## 7. Component C — Hero Split

### 7.1 Approved insertion contract

One unsynced pattern, approved slug `sci-design-blocks/hero-split`, in the existing SCI category. It follows the repository slug convention and no conflict was found. Use `core/group`, `core/columns`, `core/column`, `core/image`, `core/heading`, `core/paragraph`, `core/buttons`, and `core/button`. The source request identifies references SCI-008 and SCI-010; the historical mapping limitation for missing M2-01 is recorded above. Use one fixed asymmetric split, text left and image right; M3 exercised 52/48 with vertical centering and Core mobile stacking. Use Core Columns for this fixed relationship, not auto-fit Grid intended for repeated items.

Use generic editable sample copy: eyebrow “A flexible approach”; heading “Bring your work together”; description “Start with a clear plan and shape the experience around your goals.” The heading starts at Core H2 and remains user-editable. Use two clearly labeled example CTAs with local fragment targets as specified in §6.3; authors may remove one and replace both targets. The image is a normal editable Core Image with user-editable alt, aspect ratio/crop, border radius, and alignment controls where supported by the minimum Core version/theme. Start with an empty Core Image block/native editor placeholder. Do not use an external URL, theme asset, M3 QA image, or image with uncertain license/provenance.

### 7.2 Responsive contract

Preserve logical DOM order: text and actions first, image second; Core Columns stacks on narrow screens through Core behavior. Do not hide content, force theme-specific breakpoints, or add CSS in this design. The desired QA viewport set is 1440, 768, 390, and 320 CSS pixels. Verify no horizontal overflow, clipping/truncated headings, deformed image, inaccessible CTA, or unacceptable button wrapping. Do not demand pixel identity with Astra/Spectra.

If Core Columns and theme defaults fail a requirement, record evidence and request approval for the smallest CSS/API alternative; do not silently add CSS. M3 verified serialized stacking attributes and server output, not actual viewport behavior.

## 8. Pattern registration and proposed file placement

Reuse the current architecture; no new framework or generic loader. Subject to implementation review, the minimal proposed files are:

| Proposed path | Responsibility |
|---|---|
| `includes/hero-patterns.php` | Register two static Core patterns using the existing category and Core pattern API. |
| `patterns/sci-hero-cover.html` | Static serialized Core markup for Cover. |
| `patterns/sci-hero-split.html` | Static serialized Core markup for Split. |

Load the include from `sci-design-blocks.php` and register on `init`, reusing the static-file approach of `includes/pricing-comparison-patterns.php`; do not alter existing registrations or slugs. Use titles “SCI Hero — Cover” and “SCI Hero — Split”. Descriptions: “Full-width Cover composition with editable image, overlay, heading, copy, and Core buttons.” and “Two-column text and image composition with editable Core content and buttons.” Use the existing SCI category. No registration-time network access or execution of pattern markup as PHP. Slugs, titles, and category are specified in this SDD; no conflict exists with current registrations. No production files are created by this SDD.

For styles, make the smallest change in each of the three existing registration modules (or another equally minimal reviewed location): register the same current handle with Core's early API after the handle exists, without changing variation registration. Do not combine the different CSS modules into a new subsystem.

## 9. Requirements and traceability matrix

Priority: **MUST** = release acceptance; **SHOULD** = expected unless evidence/PO-approved exception; **OPEN** = decision or proof required. Evidence class names refer to §2.3. A future implementation report must add actual command/build/theme/version and result, not just “pass”.

| ID | Requirement | Component | Priority | Verification / expected result | Evidence at M4 |
|---|---|---|---|---|---|
| REQ-CSS-001 | Preserve all seven names/classes, Core block types, handles and selectors. | CSS | MUST | Static inventory and runtime registry match §5; old serialized fixture unchanged. | VERIFIED BY CODE; current tests. |
| REQ-CSS-002 | Register the existing handle early with Core per type, retaining `register_block_style`. | CSS | MUST | WP 7.1.0 and current WP runtime register expected callback/handle; no custom/global loader. | Architecture APPROVED; M2/M3 runtime at WP 7.1.3; minimum 7.1.0 NOT VERIFIED. |
| REQ-CSS-003 | Deliver at most one copy of each relevant stylesheet per request. | CSS | MUST | Response contains one URL for each rendered type, none for absent types. | M3 runtime/HTTP, test namespace. |
| REQ-CSS-004 | Deliver by block type, not exclusively by selected SCI variation. | CSS | MUST | No-style controls load only their type's sheet; no SCI CSS loads if that type is absent; one file per type. | Architecture APPROVED; runtime verified in M3 at WP 7.1.3. |
| REQ-CSS-005 | Core blocks without SCI style class retain standard appearance. | CSS | MUST | Control pages have no SCI classes; CSS selectors do not match/apply; browser computed style confirms no visible effect. | Static/runtime controls verified; computed style NOT VERIFIED. |
| REQ-CSS-006 | Style delivery works in TT5 and TT21 frontend and editor. | CSS | MUST | All 7 variants + controls in both themes; assets printed once; browser confirms front/editor appearance. | Frontend registration/delivery runtime/HTTP partial; browser/editor NOT VERIFIED. |
| REQ-HC-001 | Register `sci-design-blocks/hero-cover` as editable Core-only Cover in existing category. | Cover | MUST | Exact slug/category, parse/serialize and only allowed Core block names. | Slug APPROVED; product registration NOT IMPLEMENTED. |
| REQ-HC-002 | Cover image, overlay, alignment, width and minimum height use editable Core settings. | Cover | MUST | Editor/browser tests change and save each control; no SCI CSS/JS. | M3 structure only; browser NOT VERIFIED. |
| REQ-HC-003 | Heading starts at editable H2 and can be changed in Core. | Cover | MUST | Default fixture H2; editor saves alternate level; verify actual template hierarchy and no unintended duplicate H1. | Contract APPROVED; generic theme behavior NOT VERIFIED. |
| REQ-HC-004 | CTAs are Core Buttons with generic labels and local example fragment targets. | Cover | MUST | One/two CTA save/render; labels identify examples; targets are local/editable, no commercial or external URL. | Contract APPROVED; M3 only exercised a local QA anchor; product pattern NOT IMPLEMENTED. |
| REQ-HS-001 | Register `sci-design-blocks/hero-split` as editable Core-only Split with text before image. | Split | MUST | Exact slug/category, allowed block list, parse/serialize and DOM order. | Slug APPROVED; product registration NOT IMPLEMENTED. |
| REQ-HS-002 | Image alt, crop/ratio and presentation remain editable in Core. | Split | MUST | Editor save/render tests; meaningful alt fixture and decorative alternative scenario. | M3 code/runtime; editor NOT VERIFIED. |
| REQ-HS-003 | Core Columns stacks without loss/overflow at target widths. | Split | MUST | Browser checks 1440/768/390/320; no overflow, clipping or content loss. | NOT VERIFIED. |
| REQ-HS-004 | One/two generic example CTAs remain readable, focusable and ordered. | Split | MUST | Browser/editor, keyboard and narrow viewport test; local editable example fragment targets. | Contract APPROVED; M3 HTTP only; interactive NOT VERIFIED. |
| REQ-ACC-001 | Heading hierarchy, image alternatives, link names, contrast and focus meet review criteria. | Both | MUST | Automated checks plus manual keyboard/contrast/screen-reader review; no unsupported WCAG claim. | Static semantics partial; interactive/contrast NOT VERIFIED. |
| REQ-COMP-001 | Maintain WordPress 7.1+, PHP 8.3+ for 1.5.0, block and supported classic theme compatibility. | All | MUST | Run tests on WordPress 7.1.0 and PHP 8.3, plus current environments; no unsupported syntax/API. | PO amendment 2026-10-13 supersedes the prior PHP 7.4 minimum for 1.5.0. PHP 8.3 runtime NOT VERIFIED; WP minimum evidence remains as reported elsewhere. |
| REQ-NL-001 | Inserted Hero content remains Core and renders after SCI deactivation. | Heroes | MUST | Plugin-off parse/render and editor content check; preserve images/text/links. | M3 representative QA runtime/HTTP. |
| REQ-BC-001 | Preserve 20 existing patterns, 83 icons, seven style contracts, and 1.4.0 artifact. | All | MUST | Regression confirms prior 20 unchanged; final inventory 22; release hashes unchanged. | Current repo and M3 inventory; future implementation not run. |
| REQ-SEC-001 | No endpoints, options, tables, remote processing, external dependencies or code execution. | All | MUST | Static review and dependency/file inventory. | Design/code baseline; implementation pending. |

## 10. CSS delivery test specification

Run every case under TT5 and TT21, frontend and editor where feasible. Each style case must assert registration, expected style name/class, handle, request enqueue/print count, URL/response, selector scoping, and absence of duplicate files. Browser checks must establish actual appearance; HTTP presence alone is not visual proof.

| Fixture | Expected style class | Expected file |
|---|---|---|
| No Accordion block | none | No Accordion CSS |
| Standard Core Accordion | none | Accordion CSS under proposed type-level contract; no SCI selector should match |
| Accordion Minimal | `is-style-sci-minimal` | One Accordion CSS |
| Accordion Bordered | `is-style-sci-bordered` | One Accordion CSS |
| No Tabs block | none | No Tabs CSS |
| Standard Core Tabs | none | Tabs CSS under proposed type-level contract; no SCI selector should match |
| Tabs Underline / Pills / Connected / Filled | corresponding class from §5 | One Tabs CSS for all variants, not four copies |
| Featured Image without Hover Zoom | none | Editorial image CSS under proposed type-level contract; no SCI selector should match |
| Featured Image with Hover Zoom | `is-style-sci-hover-zoom` | One editorial image CSS |
| Mixed page; multiple variants of one type | multiple classes | At most one file/URL per type |
| Page with none of the three types | none | Zero SCI style files |

For each, inspect frontend HTTP response and editor assets/appearance. Check stylesheet dependency order and stale browser caches after version changes. Confirm TT5 (block theme) and TT21 (classic theme). For no-lock-in, deactivate SCI and assert block content/classes remain while SCI registry/assets disappear. Verify standard Core controls do not acquire unintended visible styling in a real browser; selector static analysis alone is insufficient.

## 11. Hero test specification

### 11.1 Static and WordPress runtime

- PHP lint all changed PHP on PHP 8.3 and a current supported PHP; parser syntax must be compatible with 8.3.
- Assert exact two pattern slugs, category, titles/descriptions, and Core-only block names. No `uagb/*`, theme-specific classes/variables, external URLs, proprietary block types, or SCI-owned layout attributes.
- Parse → serialize → parse both patterns on WP 7.1 baseline and current WP; assert no invalid/unregistered blocks and stable essential structure.
- Render both patterns; inspect semantic HTML, image source/alt, headings, buttons/anchors, and full-width behavior.
- Insert one/two CTA states; edit text, image, alt, color, overlay, spacing, heading level, crop, radius, and alignment through Gutenberg; save/reopen without recovery warnings or unexpected attribute loss.
- Deactivate SCI; previously inserted markup, copy, image, link, and editable Core block structure remain. Pattern discovery may disappear; content must not.

### 11.2 Visual and interactive

At 1440, 768, 390, and 320 CSS px, inspect both patterns under TT5 and TT21: wrapping/stacking, no horizontal overflow, no hidden/truncated content, image crop/aspect ratio, overlay/contrast, buttons, visible focus, tap target usability, and logical reading order. Exercise editor controls and keyboard navigation; test with a screen reader where available. Record browser, OS, WP, theme, viewport, screenshots or test artifact, and observed outcome. A screenshot is evidence only for the captured state, not all states.

No M3 visual conclusions may be promoted: the QA runtime proved registration, serialization, HTTP, and plugin-off persistence only. Actual layout, contrast, editor, recovery warnings, keyboard, focus, reader behavior, and target viewports remain NOT VERIFIED.

## 12. Accessibility and content semantics

- Keep one logical heading hierarchy. The pattern default should not assume the theme's document H1; use an editable heading block and test on both template types.
- Cover backgrounds are decorative; convey meaningful image information in text. Split's Core Image requires context-appropriate editable alt (descriptive for informative images, empty only when decorative).
- CTA text must describe its destination/action; link targets are actual anchors after configuration. Avoid ambiguous “click here”.
- Test foreground/background contrast for all actual states, including focus and hover. Do not treat an overlay dim percentage as proof.
- Preserve DOM order as reading order. Do not use visual reorder to contradict it.
- Confirm keyboard access and visible focus in editor and frontend. Core semantics are a starting point, not proof of theme-level accessibility.
- Automated checks and manual checks must be reported separately. No full WCAG conformance claim without a defined, completed audit.

## 13. Security, privacy, and dependency boundary

Patterns are static local markup registered by WordPress APIs. Do not add REST/AJAX endpoints, DB tables, options, custom post types, remote processing, runtime downloads, shell execution, proprietary copied code, or unsanitized user-configurable PHP. Do not turn pattern markup into evaluated PHP. Use translation/escaping conventions already present for any PHP labels/descriptions. No user data is collected or transmitted. No new Composer/npm/runtime dependency is proposed. Any deviation needs separate review/authorization.

## 14. Compatibility and backward compatibility

- Preserve WordPress 7.1+ and PHP 8.3+ for 1.5.0, as explicitly approved by the PO on 2026-10-13; this supersedes the earlier M4 PHP 7.4 requirement for 1.5.0. Stable 1.4.0 remains unchanged at PHP 7.4+.
- M2/M3 directly exercised WP 7.1.3, PHP 8.5.7, TT5 1.5 and TT21 2.9. They did **not** certify WP 7.1.0 or the newly approved PHP 8.3 minimum. M3's API result must be repeated on the minimum WP version before adopting it.
- Support block themes and classic themes that use Gutenberg, subject to theme layout/branding differences. A classic theme being able to render inserted Core content does not mean SCI controls that theme's global header/footer.
- Do not depend on `uagb/*`, Astra custom properties/hooks, Spectra CSS/JS, theme files, or private theme APIs.
- Preserve the 20 existing pattern slugs/content contracts, 83 icon identifiers/assets, all seven Block Style names/classes, and existing registered feature behavior. No migrations or edits to user content.
- Do not edit tag `v1.4.0`, the released ZIP, or historical release reports.

## 15. No-lock-in contract

After a Hero is inserted, its essential layout and content are native Core blocks. On SCI deactivation, text, block tree, image/media reference, alt, link target, and Core editability remain. SCI pattern registration and optional Block Styles may disappear; loss of optional decoration is acceptable, loss of content/layout structure is not. No Hero essential layout may require an SCI class, PHP render callback, custom block, script, external resource, or database state.

Test both an existing saved page and fresh pattern insertions. A successful HTTP response alone is insufficient: inspect parsed Core block tree and rendered content with SCI inactive.

## 16. Maintainability and agent governance

Repository inspection found no `AGENTS.md` or equivalent root-level agent governance file. `CONTRIBUTING.md` already expresses Core-first, no-lock-in, compatibility, and test principles. Do not create `AGENTS.md` as part of M4. Recommend a future separately authorized, focused guide defining PO/CTO/Codex roles, A1/A2/A3 limits, reuse/core-first, security/accessibility, compatibility, test evidence, Git safety, documentation, and release control. Keep it consistent with CONTRIBUTING and avoid duplicating long policies.

Future implementation should keep registration close to the existing modules, static patterns in `patterns/`, and CSS changes only in the three existing CSS files if separately justified. Document why each changed file exists, how to run its check, the API contract, and unresolved visual gates. Avoid abstraction layers that make two registrations harder to understand.

## 17. M5 implementation readiness and acceptance

M5 implementation was authorized separately by the Product Owner and is complete with limitations documented in [M5-IMPLEMENTATION-REPORT.md](M5-IMPLEMENTATION-REPORT.md). The acceptance criteria below remain the contract; criteria requiring minimum-version, browser/editor, responsive, visual or accessibility evidence are not closed merely by static/runtime checks:

1. Add exactly two approved Core-only patterns; the final inventory is **22 patterns** (20 existing unchanged + two Heroes). No other new features.
2. Preserve all seven existing Block Style identifiers/classes and handles; deliver CSS in TT5 and TT21 without duplicate links. Load conditionally by block type: no SCI CSS on pages without the three types; one corresponding sheet per rendered type, including a standard Core block without SCI variation. This approved policy is not variant-only loading.
3. No invalid blocks, parse/serialize failures, editor recovery warnings, or unexpected serialized-content changes.
4. No custom block, frontend JS, external dependency, remote/default image, site-specific commercial URL, global loader, or unnecessary CSS.
5. Preserve all 20 prior pattern slugs/behavior, 83 icon identifiers/assets, and seven style contracts. Update current inventory assertions expecting 20 to expect 22 without weakening explicit regression coverage for the original 20.
6. Keep WP 7.1+/PHP 8.3+ compatibility for 1.5.0; run on WordPress 7.1.0 and PHP 8.3 as well as current targets. Verify the enqueue API at the minimum WP version; PHP 8.5 lint alone is not PHP 8.3 certification. Stable 1.4.0 remains frozen at PHP 7.4+.
7. Complete static and runtime tests, TT5/TT21 frontend and editor checks, plugin-off/no-lock-in tests, and document results with evidence labels.
8. Complete PO visual review at 1440/768/390/320, including responsive, contrast, image crop, button wrapping, editor behavior, and invalid-block/recovery review. Complete keyboard/focus and accessibility review. M2/M3 visual gaps remain open until then.
9. Keep limitations explicit. A server-render or static check cannot be represented as browser QA.
10. No release is implied by M5 completion. M6 QA and RC preparation are authorized separately; production release still requires explicit Product Owner approval.

Card Grid stays deferred. Headers/Footers remain outside product scope. If a requirement cannot pass without a newly excluded capability, stop and request scope/architecture approval rather than expanding M5.

## 18. M6 release gate (design only)

Production release operations are not authorized. M6 QA and RC preparation are authorized separately; the certification report records the current run. Before any release, require:

- Clean install and upgrade from the published 1.4.0 ZIP; activation/deactivation; failure recovery.
- Exact inventory: 22 patterns (20 unchanged + two approved), 83 unchanged icons, seven unchanged Block Styles; no custom SCI block or frontend JS. Update the current inventory test's expected 20 to 22 while retaining assertions for all original registrations.
- Full static test suite; runtime/parser/serializer/render checks; TT5 and TT21 front/editor coverage; CSS duplicate/condition checks; no-lock-in and backward-compatibility checks.
- PHP 8.3 and current supported PHP plus WordPress minimum and current target checks; record exact versions. PHP 7.4 is not a current 1.5.0 certification gate.
- Responsive and accessibility gates completed and documented; no unsupported WCAG claim.
- Package contents reviewed against repository scope; ZIP integrity, clean extraction, plugin metadata/version, GPL notices and localized docs checked.
- README/CHANGELOG/release documentation updated only for approved release; version increment authorized and consistent.
- Compute SHA-256 from the final ZIP and verify it after upload/download.
- Tag and GitHub Release only after PO approval; verify tag points to intended commit, asset/checksum match, release notes and package are correct.
- Preserve 1.4.0 tag and release unchanged. Record operator, date, commit, artifact filename, checksum, test evidence, and any accepted limitations.

## 19. Risks and verification gates

| ID | Risk / gate | Owner | Status and handling |
|---|---|---|---|
| DEC-001 | Verify `wp_enqueue_block_style()` at exact WordPress 7.1.0 minimum and in target editor/frontend. | Implementer evidence; CTO review; PO release gate | Architecture APPROVED; compatibility gate OPEN until exercised. |
| DEC-002 | Complete runtime/lint verification on the approved PHP 8.3 minimum. | Implementer evidence; CTO review | M6 PO amendment 2026-10-13 supersedes the 1.5.0 PHP 7.4 requirement; PHP 8.3 runtime verification OPEN. |
| DEC-003 | Complete contrast, responsive, editor and accessibility QA for both Heroes and all styles. | PO / independent QA | Browser gate at 1440/768/390/320 and TT5/TT21 remains OPEN. |
| DEC-004 | M2-01 historical report is missing, preventing independent confirmation of the original SCI-001/009 and SCI-008/010 mapping. | Records owner (non-blocking to approved scope) | References are included as specified by PO; do not invent additional historical detail. |
| DEC-005 | Permanent `AGENTS.md` guidance may help future agents but was not authorized. | PO | Recommendation only; no file created in M4. |

DEC-001 through DEC-003 are evidence/QA gates, not unresolved architecture choices. The type-level CSS policy, both pattern slugs, Core image placeholders, editable H2 default, generic sample copy, and local example CTA targets are approved for implementation. Record test outcomes and any proposed deviation before accepting M5.

## 20. Test commands and handoff

Existing repository checks are invoked from repository root. PHP checks require a PHP CLI; SVG/CSS Python checks require Python 3. The parser checks require the local WordPress QA path as an argument. These commands are existing tests, not evidence that proposed behavior passes:

```sh
php tests/check-icon-library.php
python3 tests/check-icon-svg.py
php tests/check-editorial-query-patterns.php /path/to/wordpress-qa/site
php tests/check-pricing-comparison-patterns.php /path/to/wordpress-qa/site
php tests/check-tabs-roundtrip.php /path/to/wordpress-qa/site
python3 tests/check-accordion-css.py
python3 tests/check-tabs-css.py
python3 tests/check-editorial-image-css.py
```

During M4, existing checks passed on the available macOS environment. M5 reran repository PHP and Python checks against QA WordPress 7.1.3; M6 subsequently exercised the official WordPress 7.1 archive, clean install, and upgrade path. Updated inventory assertions cover 22 patterns, with focused checks for stylesheet associations, Core-only Hero trees, and serialization. Runtime evidence includes TT5/TT21 stylesheet URL delivery, conditional controls, mixed-page deduplication, and plugin-off content persistence. See the M5 and M6 reports for exact commands, fixtures, and outcomes. Those historical reports record the prior PHP 7.4 gate; the current 1.5.0 minimum PHP 8.3 runtime, browser/editor, responsive visual, and accessibility checks remain open.

## 21. M4 status

M4-FINAL established the architecture contract. M5 implementation on `feature/sci-hero-patterns-css-compat` adds two Core-only patterns and standard block-type CSS associations; the Product Owner accepted M5 PASS WITH LIMITATIONS and transferred its open gates to M6. M6 QA/RC work and its current disposition are in [M6-CERTIFICATION-REPORT.md](M6-CERTIFICATION-REPORT.md). The stable 1.4.0 release remains frozen. Commit, push, merge, tag, and production release operations remain unauthorized.
