# SCI Design Blocks 1.4 — Pricing + Comparison Pre-SDD Spike

**Status:** Architecture/capability investigation only; production implementation and SDD 1.4 remain unauthorized.

- **Baseline:** SCI Design Blocks 1.3.0, immutable.
- **WordPress source:** 7.1.2 (`/tmp/sci-sources-qa/wp712`).
- **PHP minimum:** 7.4.
- **Branch:** `spike/pricing-comparison-1.4`, created from `v1.3.0` (`7fe5ea16f7dbfeb248b44f92584a7e323aa0a25c`).

## 1. Scope and limits

This document began as the source-level audit. The later Pre-SDD 02 brief was available through section 57, but its attachment ends mid-token at `No` inside “NO PRODUCTION REGISTRATION”; any text after that point is unavailable. Its complete PoC criteria through section 57 have now been addressed in the isolated fixtures and results below. No production files, plugin registration, patterns, PHP, CSS, JavaScript, SDD, version, release, or tag were changed. No browser/database integration test was performed.

## 2. Executive recommendation

Build 1.4, if separately authorized, as **five unsynced block patterns made only from WordPress Core blocks**. Treat price, currency, billing terms, setup fees, features, badges, and notes as directly editable text. Do not create a pricing data model, custom block, checkout, custom frontend JS, dependency, or plugin-specific CSS for the initial scope.

Recommended pattern set:

1. **SCI Pricing — Cards** — three editable plans with ordinary editable price/period text.
2. **SCI Pricing — Featured** — same plan model, with one selected card visually emphasized using its own Core background, border, spacing, and shadow attributes plus editable badge text.
3. **SCI Pricing — Compact** — a denser, lower-chrome 3–4 plan grid for side-by-side B2B scanning.
4. **SCI Comparison — 2 Options** — paired, non-price-led editorial comparison.
5. **SCI Comparison — Feature Table** — semantic Core Table for row/column comparisons.

The two comparison-card variants share the same Core card anatomy with pricing, but their labels, feature-vs-price hierarchy, and insertion intent differ enough for an explicit paired-comparison pattern. Avoid publishing a separate 3-option Comparison pattern initially; users can duplicate a card in the editable grid.

## 3. Repository and compatibility evidence

- Before isolation, `main`, `HEAD`, and `v1.3.0` were at the same commit: `7fe5ea16f7dbfeb248b44f92584a7e323aa0a25c`. The isolated branch was created from that commit. Existing unrelated untracked files and `poc/` content were left untouched.
- Plugin metadata in `sci-design-blocks.php` declares version `1.3.0`, WordPress `7.1`, and PHP `7.4`. The inspected WordPress 7.1.2 `wp-includes/version.php` sets `$required_php_version` to `7.4`. No proposed capability requires raising the PHP floor.
- The existing block-pattern category slug is `sci-design-blocks` (`sci-design-blocks.php`, `register_block_pattern_category`). Reuse it; there is no case for new pricing/comparison subcategories at this size.
- Existing patterns are registered through `register_block_pattern()` and serialize Core blocks. This is the proven project-level route for any later pattern-only implementation.
- Core-only inserted patterns become editable ordinary blocks, according to the [WordPress Block Patterns documentation](https://wordpress.org/documentation/article/block-pattern/). Avoid registered icon markup whose rendering needs plugin icon registration if durable no-lock-in content is a priority.

## 4. WordPress Core 7.1.2 capability map

The following were inspected in the local 7.1.2 source. `core/grid` is **not** a separate block directory. Grid is a supported layout type of a container such as `core/group`; `core/icon` does exist.

| Core primitive | Verified capability useful here | Evidence in local 7.1.2 source |
|---|---|---|
| `core/group` | Editable nested container; background/text/link/button color; padding and block gap; border; shadow; `dimensions.minHeight/minWidth`; flex/grid layout, justification/orientation/wrap controls. | `wp-includes/blocks/group/block.json`; `wp-includes/block-supports/layout.php` |
| `core/columns`, `core/column` | Responsive columns; columns stack at widths up to 781px unless “do not stack” is selected. Column children stretch by the default flex cross-axis behavior. Column/group background, padding, gap, and border supports are available. | `wp-includes/blocks/columns/block.json`, `column/block.json`, `columns/style.css` |
| Group Grid layout | Core generates grid CSS from layout attributes. Grid `minimumColumnWidth` can produce auto-fill/auto-fit responsive tracks; explicit `columnCount` creates a fixed track count. Grid items stretch by default, useful for equal card heights. | `wp-includes/block-supports/layout.php` (`grid` layout definition and grid-template generation) |
| `core/heading`, `core/paragraph` | Editable rich text, typography, color, margin/padding, and block supports suitable for plan name, price, period, description, badge, setup note, terms, and CTA-adjacent notes. | `wp-includes/blocks/heading/block.json`, `paragraph/block.json` |
| `core/buttons`, `core/button` | Editable CTA text and URL; Core fill/outline styles; color, typography, sizing, border, padding, alignment/layout. | `wp-includes/blocks/buttons/block.json`, `button/block.json` |
| `core/list`, `core/list-item` | Semantic editable `ul`/`ol` and nested list content; textual statuses can be explicit and accessible without custom feature-state logic. | `wp-includes/blocks/list/block.json`, `list-item/block.json` |
| `core/icon` | Core SVG icon block exists and supports icon markup, color, dimension, alignment, and aria-label support. The project's 83 registered icons include check-related assets such as `patch-check.svg` and `clipboard-check.svg`; there is no obvious dedicated X/dash pair. | Core `wp-includes/blocks/icon/block.json`; project `includes/icon-library.php`, `icons/svg/` |
| `core/table` | Static semantic table block. Metadata supports `head`, `body`, `foot`, caption, fixed layout; cell data can store `th`/`td`, scope, alignment, row/column span. Root block supports color, typography, spacing, alignment, and border. Default/Stripes styles exist. | `wp-includes/blocks/table/block.json`, `table/style.css` |
| `core/separator` | Standard separator with Core styles and color; optional divider within card content. | `wp-includes/blocks/separator/block.json` |
| `core/details` | Native disclosure; not required for pricing/comparison candidates and would hide content behind interaction. | `wp-includes/blocks/details/block.json` |

For high-level references, see the official [Block Supports reference](https://developer.wordpress.org/block-editor/reference-guides/block-api/block-supports/), [Core Table reference](https://developer.wordpress.org/block-editor/reference-guides/core-blocks/core-blocks-text/core-block-table/), and [Core layout styles explanation](https://developer.wordpress.org/block-editor/explanations/architecture/styles/).

### Pricing as editable content

The information model maps directly to ordinary text blocks:

- Badge → optional Group + Paragraph.
- Plan, short description, price text, currency/prefix, billing period, setup price, footnote/conditions → Paragraph or Heading, with rich-text inline emphasis as needed.
- Feature rows → Core List / List Item.
- CTA → Core Buttons / Button.

There is no need to parse or normalize currency, billing frequency, tax, or price. The author can enter `S/ 1,500 / mes`, `US$ 49 / usuario / mes`, `Desde S/ 350`, `US$ 800 pago único`, or `Consultar` as editorial text. Secondary pricing and terms are optional paragraphs. This keeps all values editable and portable without CPT, metadata, API, options, or PHP logic.

For included/excluded items, use explicit text such as “✓ Incluido” and “— No incluido” or the words alone. Do not communicate status solely with green/red or an icon. The existing SCI Icon List pattern uses registered SCI icons; using that icon source introduces a runtime plugin registration dependency. Prefer ordinary list text in pricing cards for the most portable output. Do not expand the 83-icon library for this scope.

## 5. Equal-height cards and CTA position

**Core feasibility: YES at the layout level; verify the composed pattern in an editor/frontend gate.**

Suggested card composition:

```text
Group (Grid layout; responsive minimum column width; gap from theme preset)
├── Group (card; flex, vertical, justify-content: space-between; min-height)
│   ├── Group (badge/name/description/price/period)
│   ├── List (features; variable count)
│   └── Buttons (CTA)
└── … sibling cards with the same anatomy
```

Core Grid stretches items to their grid row; Group supports min-height and a vertical flex layout with justification. With a shared card structure and the CTA as the final child, Core layout can place CTAs at the bottom of equal-height cards. A Columns-based alternative uses Core's responsive stack at 781px and flex stretching; Grid is better suited to automatic 3–4-to-1 column changes using minimum column width. No custom CSS is warranted before validating this composed arrangement.

This is source-based feasibility, not proof of identical visual height for arbitrary user-edited text, translations, or links. The SDD and later manual QA should require short/long feature-list fixtures, long plan names, and narrow viewport checks. If authors add structurally different blocks or unset the card layout, alignment can naturally diverge.

## 6. Pattern candidate disposition

| Candidate | Classification | Recommendation |
|---|---|---|
| Pricing — Cards | DISTINCT | Include. Three-plan pricing baseline with price, period, features, CTA, optional secondary price/terms. |
| Pricing — Featured | CONFIGURATION VARIANT, useful insertion preset | Include as one optional pattern. Highlight one plan using editable Core attributes and an editable badge; no fixed brand color or “winner” text. Do not promise a raised/offset card unless plain Core spacing can achieve the visual. |
| Pricing — Header | REDUNDANT | Exclude initially. Moving price above plan name and adding a separator is a small arrangement change that the inserted editable Cards pattern can support. |
| Pricing — Compact | DISTINCT | Include. Lower card chrome, denser horizontal scan, likely 4 plans; use the same text model and Core Grid. |
| Pricing — Single | REDUNDANT | Exclude. One card is a normal use of the same Core composition; no new structure or data is added. |
| Comparison — 2 Options | DISTINCT by semantic/insertion intent | Include. Paired editorial contrast with descriptions, features, and optional CTAs, without price hierarchy. Uses the same Core card primitives as Pricing. |
| Comparison — 3 Options | CONFIGURATION VARIANT / REDUNDANT | Exclude initially. It is the same grid anatomy with one extra option; an author can duplicate a card. |
| Comparison — Feature Table | DISTINCT | Include. Row/column relationships are genuinely tabular and should use semantic `core/table`. |

Recommended initial count: **5**. No separate pattern category is required; use existing category “SCI Design Blocks”.

## 7. Featured plan and theme integration

Core Group/Column supports can make the featured card editable via background/text colors, border color/width/radius, shadow, padding/block gap, typography, and nested badge Paragraph. Group and Button supports propagate relevant colors to nested buttons/elements in Core 7.1.2. The theme's presets/Global Styles remain in control. Do not bake Nettix/SCI/Lumedia colors, fonts, or winner labels into markup.

Core can express background, border, shadow and padding differences. A reliably raised/offset card is not necessary to meet the feature emphasis; avoid negative-margin/transform CSS requirements. Core's native block settings let the author edit title, price and feature typography. The feature is visual emphasis only, never pricing logic or state.

For a feature-table column highlight, the inspected Table block supports color/border at the table/root level, while its cell data schema does not declare per-cell style/background attributes. Therefore a reusable colored winner column is **not proven Core-native** in this baseline. Do not hardcode a winner; use clear text or emphasis in cell content, or omit column highlighting in the initial pattern. If per-column fill becomes a must-have, document a scoped styling/structure decision separately before SDD approval.

## 8. Comparison table and mobile behavior

**Pattern around `core/table` is sufficient for structure and baseline semantics.** Use a caption where it aids context; include a header row and make option headers actual `th` cells with column scope, rather than bold `td` cells. Optional footer rows are supported. The source schema retains `scope`, alignment, spans and rich text. Core's default/stripes style and block color, border, spacing, alignment, typography controls are available.

WordPress 7.1.2 Core frontend CSS sets `.wp-block-table { overflow-x: auto; }`, tables to `width:100%`, and fixed-layout cells to wrap long content. Thus Core already provides horizontal overflow containment when the table actually exceeds its container; it does not automatically transform a table into stacked cards. With fixed layout, a table may instead compress/wrap at mobile sizes. Recommendation: use ordinary semantic table, test 3-option/long-cell content on narrow widths, and set fixed layout off if intrinsic sizing plus Core overflow better serves the content. No custom responsive CSS or JavaScript is proposed. If tests show unacceptable compression and no Core attribute solves it, escalate the exact case before adding a small scoped `overflow-x` rule.

For a highlighted option column, the table block does not expose per-cell background/column style in its serialized cell schema. Keep the generic pattern neutral; avoid a baked-in winner. The author may emphasize wording with rich text, but per-column color is not an approved native guarantee.

## 9. Monthly / annual toggle

**Recommendation: DEFER as a data-driven price toggle.** Core 7.1 has native Tabs, but using it would mean duplicating the complete pricing content in separate monthly and annual panels. Those are two independent editable states, not one set of plans whose prices are synchronized or computed. It adds repeated content and accessibility/interaction QA while offering no pricing data model. A later requirement could opt into a `core/tabs` composition, but no custom JS, custom state, pricing engine, or toggle pattern belongs in this initial 1.4 proposal.

Checkout, payment, taxes, discounts, conversion, calculators, and dynamic quote logic remain out of scope.

## 10. CSS, JavaScript, custom blocks, dependencies

| Capability | Initial recommendation |
|---|---|
| Custom block types | 0 |
| SCI frontend JavaScript | 0 |
| SCI custom CSS | 0 initially; re-evaluate only if an evidenced Core Table mobile failure remains after Core attributes |
| External runtime dependencies | 0 |
| Runtime PHP beyond pattern registration | None needed |
| Pattern implementation | Core Group/Grid/Columns, headings, paragraphs, list, buttons, and table in standard block pattern markup |

Use block spacing, supports, theme presets, and Global Styles. A named Block Style is unnecessary for the initial pricing/comparison patterns because the different appearances can be encoded as directly editable pattern attributes. If a later shared card treatment repeats across many patterns and needs reusable styling, that is a separate decision, not a reason to introduce it now.

## 11. Proposed SDD scope if separately authorized

1. Define five static editable patterns listed in section 6.
2. Keep Pricing strictly as presentation content; accept arbitrary author-provided currency and billing text.
3. Use Core Grid or Columns layouts with responsive behavior; author card internals as Group + editable text + List + Buttons.
4. Use Core Group/Column attributes for the featured plan; no fixed color and no automatic “winner”.
5. Use Core Table for Feature Table with proper column headers, optional caption, Core striping/borders/colors, and mobile fixture validation.
6. Defer automatic Monthly/Annual pricing switching and all checkout/commerce logic.
7. Reuse existing `sci-design-blocks` pattern category; PHP remains >=7.4; no new dependency, custom block, custom JS, or CSS unless a Core limitation is reproduced and reviewed.
8. Protect 1.3.0 release/tag/ZIP/source. No 1.4 implementation begins until Product Owner authorizes SDD and implementation separately.

## 12. Validation still required after authorization

Static source inspection confirms declared Core support and markup behavior, but cannot prove editor usability or appearance. After SDD approval, validate in WordPress 7.1+ with a real editor and at least one block theme:

- 3 and 4 pricing cards with varied feature counts, long descriptions, currency strings, setup fees, optional badge/footnote, and CTA alignment.
- Featured treatment editing through Core color/border/spacing controls; no inherited hard-coded palette.
- Mobile Grid/Columns stacking, no horizontal card overflow.
- Comparison 2-option cards and feature tables with header semantics, caption and 3 options.
- Narrow mobile table with long cell text; confirm wrap versus horizontal scroll is usable.
- Keyboard and screen-reader semantics for CTA links, lists, table headers/caption, and any status text.
- Inserted pattern remains ordinary Core blocks after plugin deactivation (registered icon glyphs are avoided in these proposed patterns).

## 13. Decision

**Pre-SDD recommendation:** proceed to a Product Owner review of the five-pattern scope; architecture is Core-pattern-only. No condition found in the visible brief forces an SCI custom block or SCI frontend JavaScript. Grid equal-height/CTA positioning and mobile table usability are Core-feasible but require the later editor/browser acceptance gate. The original Pre-SDD 01 attachment had ended mid-point 43; Pre-SDD 02 subsequently supplied the PoC criteria through section 57, except for the truncated text after the final `No`.

## 14. Pre-SDD 02 PoC closure (sections 7–57)

Detailed isolated artifacts were local PoC files under `poc/pricing-comparison-1.4/` and are not included in the public repository. Their filenames included `README.md`, `pricing-featured.html`, and `comparison-feature-table.html`.

- Pricing fixture uses three cards with unequal feature counts (4/7/5), Core text badge, optional setup-price copy, Core Grid, and vertical Flex Groups with `space-between`; it uses no Spacer hack. The CTA alignment mechanism is statically present, while actual rendered equality remains pending browser/editor QA.
- Comparison fixture is one Core Table with caption, column/row headers, and long content. WordPress 7.1.2 Core CSS confirms an `overflow-x:auto` wrapper; it does not ensure this copy overflows or guarantee mobile legibility. Browser validation remains required.
- Core 7.1.2 `parse_blocks()` → `serialize_blocks()` → `parse_blocks()` ran on each fixture; serializing the second parse reproduced the same markup. This is parser round-trip evidence, not full block-editor validation.
- Updated candidate recommendation: KEEP Pricing Cards, Pricing Featured, Pricing Compact, Comparison 2 Options, and Comparison Feature Table; remove separate Pricing Header, Pricing Single, and Comparison 3 Options as structurally redundant; defer Monthly/Annual toggle.
- Runtime baseline was rechecked statically: 15 registered patterns, 83 icon assets, two Accordion styles, four Tabs styles, one Hover Zoom style, zero custom SCI blocks. No production registration was added. WordPress Core bootstrap could not reach the disposable local database, but the actual Core parser/serializer functions ran directly from checked-out 7.1.2 source.
- CSS/JS/custom blocks/external dependencies/custom runtime PHP for this PoC: none. Production remains untouched. The complete brief attachment stops after section 57 at `No`; no instructions after that cutoff could be assessed.

## 15. Pre-SDD 03 manual visual package

An isolated installable package was prepared locally at `poc/pricing-comparison-1.4/sci-design-blocks-pricing-comparison-poc-0.1.0.zip`. It registered only the three experimental patterns in a unique `SCI PoC — Pricing & Comparison` category. It was not a 1.4 release and was not published; the local PoC files are intentionally excluded from this repository. See [PRE-SDD-1.4-MANUAL-VISUAL-GATE.md](PRE-SDD-1.4-MANUAL-VISUAL-GATE.md) for the Product Owner's checklist.

The extracted ZIP passed PHP lint, isolated pattern registration smoke (one category, exactly three expected unique pattern slugs), Core 7.1.2 parser round-trip for all three fixtures, `zip -T`, one-root/content checks, and static absence checks for CSS, JS, inline script/style elements, remote media, and remote CTA URLs. The ZIP contains seven files: bootstrap, registration PHP, three pattern fixtures, README, and GPL license. It has no collision with the 1.3.0 plugin slug/namespace/pattern category/pattern slugs. Stable production inventory remains 15 patterns, 83 icons, two Accordion styles, four Tabs styles, one Hover Zoom style, and zero custom SCI blocks.

No real WordPress activation, editor insertion, or browser/device visual test was run: the available Core installation's database connection is unavailable, and the brief expressly reserves visual PASS/FAIL evidence for the Product Owner. All manual gate fields remain NOT RUN. The supplied brief ends partway through section 55 after `If Product Owner later reports FAIL:`; no later instructions could be assessed.

## 17. Fix 02 — Core Pricing reuse and scalable Grid PoC (0.3.0-poc)

This section supersedes the earlier three-pattern/0.1.0 PoC packaging recommendation and its manual checklist. The earlier artifact remains preserved for comparison. Production 1.3.0 remains untouched; SDD 1.4 and production implementation remain unauthorized.

### Core source evidence and layout choice

The inspected environment is WordPress Core 7.1.2 at `/tmp/sci-sources-qa/wp712` with Twenty Twenty-Five and Twenty Twenty-Four.

- Twenty Twenty-Five `patterns/pricing-2-col.php` and `patterns/pricing-3-col.php` show the Core pricing card anatomy: Core Columns/Column, editable plan headings and descriptions, price text, feature content, buttons, spacing, backgrounds, and stacking. Twenty Twenty-Four `patterns/cta-pricing.php` is additional theme-level precedent. These are theme patterns, not runtime dependencies; the PoC copies block architecture, not a registered pattern reference.
- Core Columns source (`wp-includes/blocks/columns/style.css`) uses flex layout, does not wrap its desktop columns, and stacks columns at widths up to 781px unless configured not to. This is a good native choice for fixed 2/3-card compositions, but it does not naturally distribute arbitrary 4–6 sibling columns onto multiple desktop rows. At desktop, extra columns compress and can become impractically narrow.
- Core Grid is a `layout.type: grid` option on `core/group`, not a separate `core/grid` block. Twenty Twenty-Five patterns `grid-with-categories.php`, `grid-videos.php`, and `cta-grid-products-link.php` demonstrate Grid Group attributes and ordinary saved Group markup. Core `wp-includes/block-supports/layout.php` generates a responsive `grid-template-columns` from `minimumColumnWidth` and `autoFit`/auto-fill behavior. The Group metadata supports layout and spacing. Editor source in `wp-includes/js/dist/block-editor.js` exposes the minimum-column-width control and auto-fit behavior in the inspected Core version.
- **Decision: CORE GRID BETTER** for variable Pricing Cards and Compact. Its minimum track width permits wrapping/reflow when plan Groups are duplicated or removed, without SCI breakpoints or layout CSS. Use Core Columns as the native reference for fixed two/three-column patterns, not as the scalable variable-count primitive.
- Core-generated row counts and practical fit depend on the active theme, content width, user spacing, and viewport. Source inspection is not a browser measurement; the manual gate records actual behavior.

### Scalability inference and limits

The PoC uses a Core Group Grid with 18rem minimum tracks for Cards/Featured, and 14rem for Compact. The Grid can fit additional columns only where the available content width permits; otherwise it creates another row. For illustration, TT5 source defines wideSize 1340px and spacing preset 30 as 20px; four 18rem tracks plus three 20px gaps total about 1212px and can fit at that wide size, while five or six naturally reflow. This is a source-based width calculation, not observed runtime behavior. At smaller widths tracks reflow again; exact breakpoints are owned by Core/theme.

The reasonable target for Cards is 2–6 plans with wrapping, not six plans on one row. The manual gate must test each count. Seven or more plans are not prohibited, but product guidance should favor a semantic Comparison Feature Table or a different information architecture when many plans/features are difficult to scan. Compact uses shorter sample content and 14rem tracks; its 3–6 behavior also remains NOT RUN until browser testing.

### Rebuilt fixture anatomy and content

The new package registers exactly five isolated patterns:

1. SCI PoC — Pricing Cards — three default Core Grid cards; Starter/Business/Enterprise content and editable prices, features, CTAs.
2. SCI PoC — Pricing Featured — same Core architecture, three cards, base-2 theme preset on Business, editable badge, unequal lists of 4/7/5, and `Implementación: S/ 800 pago único`.
3. SCI PoC — Pricing Compact — three short-content cards; duplicate/remove Core card Groups for 3–6 tests.
4. SCI PoC — Comparison 2 Options — two Core card Groups for Nube Privada and Housing; no pricing model.
5. SCI PoC — Comparison Feature Table — semantic `core/table`, caption, column/row headers and a long cell.

Pricing card items are Core Groups with vertical flex layout and `space-between`; content is Core Heading/Paragraph, List/List Item, and Buttons/Button. No Spacer block, proprietary price field, SCI color, custom control, or named-pattern runtime dependency is used. The featured plan uses a Core theme preset rather than a hard-coded brand color. Table mobile containment relies on Core Table behavior and remains a mandatory manual gate.

Previous visual reports identified a too-narrow fourth card in the old Compact architecture and one invalid block in old Featured. The exact invalid block/root cause was not recorded in the supplied evidence, so this report does not claim to have diagnosed it. The new Fixtures are rebuilt from the verified Core Group Grid/Table patterns and saved-block conventions. The new Featured fixture must still be checked in the actual editor for zero invalid warnings/recovery prompts; static parser acceptance cannot establish that.

### Static validation and artifact

The package is isolated under `poc/pricing-comparison-1.4/package-v0.3.0/` and identifies itself as `0.3.0-poc`; its ZIP is `poc/pricing-comparison-1.4/sci-design-blocks-pricing-comparison-poc-0.3.0.zip`. The package contains its own bootstrap, one registration include, exactly five `.html` fixtures, README, and GPL license. It uses a unique plugin slug, PHP namespace, category, and pattern names; it can coexist with SCI Design Blocks 1.3.0. It has no CSS, JS, dependencies, or build tooling.

Static validation checks PHP syntax, one category/five exact registrations, Core 7.1.2 parse/serialize/reparse stability, Core block names, default card/list counts, table caption/header semantics, ZIP integrity, root layout and exclusions. These checks are recorded with the generated artifact. They do not simulate editor block save validation or replace the Product Owner's real WordPress editor test.

### Candidate disposition

| Candidate | Current Pre-SDD recommendation | Reason |
|---|---|---|
| Pricing Cards | KEEP; primary scalable family | Default three, Core Grid intended to reflow with 2–6 ordinary card Groups. |
| Pricing Featured | KEEP as an insertion variant, pending no-invalid-block visual gate | Same anatomy with editable Core emphasis; optimized for three, not arbitrary centered highlighting. |
| Pricing Compact | KEEP pending 3–6 readability/reflow gate | Distinct short-content/dense treatment; no four-plan-only default. |
| Comparison 2 Options | KEEP pending visual gate | Different non-price editorial intent; shares ordinary card anatomy. |
| Comparison Feature Table | KEEP pending mobile gate | Tabular feature relationships; Core Table semantics. |
| Pricing Header | REDUNDANT | Same blocks rearranged by the author. |
| Pricing Single | REDUNDANT | One card is a normal instance of the card composition. |
| Comparison 3 Options | REDUNDANT | Duplicate an ordinary Core Grid card. |
| Monthly/annual toggle | DEFERRED | Requires duplicated independent tab content or synchronization logic; no implementation here. |

### Manual gate

`PRE-SDD-1.4-MANUAL-VISUAL-GATE.md` is reset to the five new 0.3.0 patterns. Old PoC instances must be deleted/ignored. Every browser/editor checkbox starts NOT RUN; the explicit hard gate is zero invalid-block warnings and zero recovery prompts for newly inserted patterns, especially Featured. Pricing Cards counts 2, 3, 4, 5, 6 and Compact counts 3, 4, 5, 6 are tested by ordinary duplicate/delete. No visual pass is claimed without Product Owner evidence.

Static checks performed for the 0.3.0 package from the extracted ZIP:

- PHP 8.5.4 `php -l`: bootstrap PASS; registration include PASS.
- Registration smoke: exactly one category and these five names/titles: `pricing-cards`, `pricing-featured`, `pricing-compact`, `comparison-2-options`, `comparison-feature-table`.
- WordPress Core 7.1.2 parser source: all five parse → serialize → parse → serialize cycles stable; all non-null block names are Core blocks. Cards contain 3 cards and list item counts 4/5/5; Featured contains 3 cards and 4/7/5; Compact contains 3 cards and 4/6/7; Comparison 2 contains 2 cards and 4/4; Table contains one Core Table with caption, column headers, row headers, and long-cell text.
- ZIP: `zip -T` PASS; one plugin root; nine files (bootstrap, include, five fixtures, README, LICENSE); no CSS/JS, node_modules, source reference, or nested ZIP. LICENSE includes its standard upstream URL; no pattern/runtime asset fetches are present.
- SHA-256: `cb2454bffc7df07774e5f92e59fd570b54851f791027544aaed879de40b1e02b`.
- Prior 0.1.0 PoC ZIP was preserved unchanged (current SHA-256 `e3a706014c8866be516e9b0881795a1cf2cf5e13f8e37c2fbdbf40211e1c284e`).
- No WordPress database/editor/browser run occurred. All visual criteria and the zero-invalid-block gate remain NOT RUN.

## 18. Pre-SDD closeout and Product Owner visual gate

The Product Owner subsequently reported completion of the real Gutenberg visual gate. This section and `PRE-SDD-1.4-MANUAL-VISUAL-GATE.md` supersede the earlier NOT RUN state from the time before that report. Results are attributed to the Product Owner; Codex did not perform browser QA.

Reported PASS: Pricing Cards at 3 and 6 plans; automatic Core Grid reflow; mobile stacking; Pricing Featured; unequal 4/7/5 content; new Featured insertion; Pricing Compact; Comparison 2 Options desktop/mobile; Comparison Feature Table desktop/mobile; and internal horizontal table scrolling. The previous invalid-block issue was NOT REPRODUCED in the corrected PoC. CTA placement with unequal content was ACCEPTABLE. Global destructive page overflow was NOT OBSERVED. Product Owner reported zero custom SCI CSS, frontend JS, custom SCI blocks, and external runtime dependencies.

The exact earlier invalid block/root cause was not recorded. The corrected PoC was rebuilt using Core Group/Grid source conventions and passed static parsing; the manual gate found the reported old failure was not reproduced. This is a non-reproduction result, not a diagnosis of the original uncaptured failure.

**Pre-SDD 1.4: PASS. Recommendation: READY FOR SDD / IMPLEMENTATION.** SDD and implementation authorization are addressed by the later task; stable release 1.4.0 remains separately gated.


## 19. Final certification evidence update

The Product Owner's M5 message reconfirmed the RC visual gate PASS, Cards scalability through six plans, Core Grid reflow, Featured and its invalid-block regression result, Compact, both Comparison patterns, Tabs regression, Tabs mobile horizontal scrolling, and no reported visual regression in tested editorial behavior. This is Product Owner evidence. Codex browser QA remains NOT PERFORMED. Final static/artifact certification and stable release details are recorded in `../releases/1.4/M5-RELEASE-1.4.0.md`.
