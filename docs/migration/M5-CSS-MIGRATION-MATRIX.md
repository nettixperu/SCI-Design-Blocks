# SCI Design Blocks 1.3 — M5 CSS Replacement Audit

**Scope:** CSS audit and proposed staging cleanup for sections 4–5 only. This document does not apply changes to staging or plugin code.

## Evidence and scope

- Authoritative input: `audit-input/sci-staging-custom-m5.css`, extracted byte-for-byte from the Product Owner's staging CSS in the M5 request. SHA-256: `f49044197957857cfecb9a60eb63c27ff767ec153e59827c8f793a1ae43fcced`.
- Proposed copy for the Product Owner to apply: `audit-input/sci-staging-custom-m5-proposed.css`. It preserves sections 1–3 and removes sections 4–5 only. SHA-256: `babda9d0458330affac688781ad1f1325f39a90d675d5c8ab74aa5532d278541`.
- RC04 artifact inspected: `sci-design-blocks-1.3.0-rc-04.zip`, SHA-256 `af6a75cef12854c214351ce488139c619e9f2db253b44b0a5f0952542ec30342`.
- Source inspected: `includes/editorial-query-patterns.php`, especially the `sci-design-blocks/posts-compact-list` registration and markup (lines 72–90).
- Product Owner evidence in the request says the staging homepage's new compositions are visually accepted and Compact List replaces the previous latest-news presentation. This audit did not connect to staging or inspect other staging pages.

The CSS proposed copy is not an edit to the live/staging Additional CSS. It is a candidate for Product Owner review and application. The earlier M2 CSS migration matrix is an ignored, local audit artifact and is not part of the public repository; it was not modified.

The source and proposed CSS copies are local audit evidence under the repository's ignored `audit-input/` path because they contain site-specific staging CSS. They are intentionally not committed or included in the release ZIP.

## Metric method and baseline

Counts are from the captured CSS text. “Selector blocks” counts each qualified rule occurrence, including repeated selectors in media queries; “unique selectors” deduplicates the full selector string. Physical lines include blank lines and comments. Dimension tokens count explicit CSS units and `fr` tracks in declarations; they do not count unitless values such as `1.15` line-height.

| Metric | Full staging CSS before | Sections 4–5 | Proposed CSS after | Change after cleanup |
|---|---:|---:|---:|---:|
| Physical lines | 365 | 211 | 154 | −211 lines |
| Selector blocks / occurrences | 33 | 21 | 12 | −21 occurrences |
| Unique selector strings | 27 | 16 | 11 | −16 unique selectors |
| `!important` declarations | 53 | 24 | 29 | −24 declarations |
| Media queries | 3 | 2 | 1 | −2; header query remains |
| Hardcoded color occurrences | 11 | 8 | 3 | −8 occurrences |
| Unique hardcoded colors | 6 | 5 | 3 | Three editorial colors removed |
| Explicit dimension tokens | 36 | 25 | 11 | −25 tokens |
| Unique dimension tokens | 23 | 16 | 9 | Editorial dimensions removed |

The remaining media query is the 600px header rule in section 2. The 900px and 600px editorial rules in section 5 are removed from the proposed copy. Of the six original unique colors, `#111827`, `#2563eb`, and `#cbd0d6` remain in the preserved header/search sections; `#6b7280`, `#d9dde3`, and `#f5f6f7` disappear with sections 4–5.

### Selector inventory by section

- Section 4: 16 selector blocks, 16 unique selectors, 19 `!important`, no media queries.
- Section 5: 5 selector blocks, 3 unique selectors, 5 `!important`, 2 media queries.
- Across sections 4–5: 21 selector blocks, 16 unique selectors, 24 `!important`, 2 media queries.

The 16 unique editorial selectors are all listed in the migration matrix below. The 21 rule occurrences include the three responsive overrides of `.sci-noticia-compacta`, its featured image, and its title.

## RC04 Compact List evidence

The registered pattern is Core-only and serializes:

`Query → Post Template → Core Group (flex row, nowrap, centered) → Post Featured Image + Core Group (vertical) [Post Title + Post Terms] → Core Separator`.

Verified attributes in `includes/editorial-query-patterns.php`:

- Row uses Core Group `layout.type: flex`, `flexWrap: nowrap`, vertical alignment `center`, and preset `blockGap`.
- Post Featured Image is linked, `width: 160px`, `aspectRatio: 16/9`, `scale: contain`.
- Post Title uses Core `fontSize: small` and typography `fontWeight: 600`.
- Post Terms uses Core `fontSize: small`.
- A Core Separator is serialized after each item.
- The Compact List markup contains neither `sci-ultimas` nor `sci-noticia-compacta`; it also contains no Post Date or excerpt.

The pattern does not declare 140px/120px responsive image widths. Its layout relies on Core Group and active theme styles. The PO's staging visual acceptance is evidence for the approved result, but this audit has not independently tested every viewport or every page still using the legacy classes.

## Rule-by-rule migration matrix

“Manual verification: YES” marks rules covered by the Product Owner's staging migration/visual check. The audit itself inspected the supplied stylesheet and approved pattern source; it did not connect to staging or enumerate every page/content instance.

| Selector | Purpose / old behavior | Core or pattern replacement | Classification | Evidence | Removal risk | Manual verification |
|---|---|---|---|---|---|---|
| `.sci-ultimas .wp-block-post` | Adds 14px vertical padding and a hardcoded bottom border to each item. | Core Post Template item layout plus the pattern's explicit Core Separator. | DELETE / CORE | Supplied section 4; RC04 markup includes a Core Separator and no `.sci-ultimas`. | Low for migrated Compact List; old queries still wrapped in `.sci-ultimas` would lose row padding/border. | YES |
| `.sci-ultimas .wp-block-post:first-child` | Removes top padding from first item. | Core pattern spacing and separator structure; no first-child special case in the pattern. | DELETE / CORE | Supplied section 4; new pattern has no wrapper class or matching rule. | Low on the migrated homepage; check any legacy list retaining that class. | YES |
| `.sci-noticia-compacta` | Builds a two-column CSS Grid, 14px gap, vertical centering, and resets margin/padding. Responsive overrides change columns/gap at 900px and 600px. | Core Group Row with nowrap/center alignment and Core `blockGap`. | DELETE / CORE | RC04 serialized Core Group flex row; no custom class. | Medium at narrow widths if another old instance relied on 120px/140px breakpoints. The accepted staging result is PO evidence, not independent responsive QA. | YES |
| `.sci-noticia-compacta .wp-block-post-featured-image` | Forces 160×90 with reset spacing, crop clipping, and gray background; responsive rules force 140×80 and 120×72. | Core Post Featured Image width 160px, 16:9 ratio, `contain`. | DELETE / CORE | Pattern markup has `width:160px`, `aspectRatio:16/9`, `scale:contain`; no legacy class. | Low for new pattern; legacy image sizing/background disappears elsewhere. | YES |
| `.sci-noticia-compacta .wp-block-post-featured-image a` | Makes image link fill its box. | Core Post Featured Image `isLink:true`. | DELETE / CORE | Registered pattern serializes `isLink:true`; no custom class. | Low; only legacy custom markup might depend on the forced dimensions. | YES |
| `.sci-noticia-compacta .wp-block-post-featured-image img` | Forces image display/size, removes max-width, sets `object-fit:contain`. | Core Post Featured Image width, ratio, and scale attributes. | DELETE / CORE | RC04 markup directly encodes the corresponding image behavior. | Low for pattern; check old classes before removal. | YES |
| `.sci-noticia-compacta > .wp-block-group` | Resets margin and padding on the content group. | Nested Core Group owns title/category composition and Core layout. | DELETE / CORE | Pattern includes nested Core Group with vertical layout and blockGap; no CSS class. | Low; any separate legacy group loses reset. | YES |
| `.sci-noticia-compacta .wp-block-post-title` | Forces 16px/15px mobile, 1.15 line-height, 700 weight, and margin/padding. | Core Post Title uses `fontSize:small`, weight 600; Core Group blockGap provides composition spacing. | DELETE / CORE | Explicit RC04 attributes differ from old hardcoded values; PO accepted visual output. Responsive 15px override only matched old class. | Low for approved pattern; a legacy use would revert to theme typography/spacing. | YES |
| `.sci-noticia-compacta .wp-block-post-title a` | Hardcodes title-link color and removes underline. | Core link and theme/Global Styles. | DELETE / CORE | Pattern uses Core Post Title; PO accepted theme-native presentation; no class in markup. | Low; visual link treatment may follow site theme for any remaining legacy instance. | YES |
| `.sci-noticia-compacta .wp-block-post-title a:hover` | Hardcodes blue hover color. | Theme/Core link interaction styling. | DELETE / CORE | No class in new pattern; staging visual gate accepted the new result without this selector. | Low; hover color will follow theme styles. | YES |
| `.sci-noticia-compacta .wp-block-post-terms` | Resets spacing and hardcodes 11px typography/gray text. | Core Post Terms uses Core `fontSize:small` and theme styles. | DELETE / CORE | Pattern serializes `fontSize:small`; no legacy class. | Low; old instances will use active theme styles. | YES |
| `.sci-noticia-compacta .wp-block-post-terms a` | Hardcodes category-link color and underline details. | Core link and theme/Global Styles. | DELETE / CORE | Core Post Terms is used in the pattern; no custom class; visually accepted result. | Low; theme link appearance becomes authoritative. | YES |
| `.sci-noticia-compacta .wp-block-post-terms a:hover` | Hardcodes blue hover color. | Theme/Core link interaction styling. | DELETE / CORE | No legacy class in pattern; PO accepted visual output. | Low. | YES |
| `.sci-noticia-compacta .wp-block-post-date` | Styles date spacing, size, line-height, and color. | No replacement needed: Compact List intentionally has no Post Date. | OBSOLETE | Exact RC04 pattern children are Post Title and Post Terms only; supplied M5 contract states no date. | None for the migrated pattern; date styling disappears if a legacy instance remains. | YES |
| `.sci-ultimas .wp-block-separator` | Hides Gutenberg separators in the old list. | Core Separator is intentionally present and visible in Compact List. | DELETE / CORE | Pattern serializes Core Separator; no `.sci-ultimas` ancestor. | Low for migrated pattern; a leftover old wrapper would continue hiding separators only while this rule exists. | YES |
| `.sci-ultimas .wp-block-post-template` | Resets top/bottom margins on the old Query Loop template. | Core Query/Post Template layout and theme spacing. | OBSOLETE | RC04 Query markup has no `.sci-ultimas` class; spacing is Core/theme-controlled. | Low; any legacy Query Loop with the class loses the margin reset. | YES |

No rule is classified KEEP: every property is replaced by the accepted Core composition, intentionally absent (date), or scoped to old classes. No rule is classified VERIFY as its final category because RC04 markup provides sufficient evidence for the new composition; site-wide absence of residual legacy markup remains a pre-application manual check for all rows.

## Site shell boundary

Sections 1–3 are preserved byte-for-byte in the proposed CSS. They cover page-title visibility, site header and subscription behavior, and compact header search. None is moved into SCI Design Blocks. The remaining 600px media query is specifically the header rule, not editorial styling.

## M5 staging application result

- **Staging application:** manually applied by Product Owner.
- **Result:** PASS; Product Owner reports the site remained visually correct and no breakage was observed.
- **Before:** 365 lines, 27 unique selectors, 33 selector-rule appearances, 53 `!important`, 3 media queries, 6 unique hardcoded colors, 36 explicit dimension tokens.
- **After:** 154 lines, 11 unique selectors, 12 selector-rule appearances, 29 `!important`, 1 media query, 3 unique hardcoded colors, 11 explicit dimension tokens.
- **Removed:** 211 lines (about 58% of the supplied staging CSS).
- **Editorial layout CSS moved into SCI Design Blocks:** 0 bytes.
- **Site breakage:** none reported by Product Owner.

The Product Owner applied the proposed copy to staging. Codex did not connect to or modify staging. The CSS audit inputs are development evidence and are not runtime/plugin assets.
