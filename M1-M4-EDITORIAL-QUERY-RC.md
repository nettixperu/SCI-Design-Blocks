# SCI Design Blocks 1.3 — Editorial Query Library RC 04

**Artifact (approved RC):** `sci-design-blocks-1.3.0-rc-04.zip`\
**Artifact SHA-256:** `af6a75cef12854c214351ce488139c619e9f2db253b44b0a5f0952542ec30342`\
**RC metadata:** `1.3.0-dev` (WordPress 7.1+, PHP 7.4+)\
**Scope:** targeted visual-equivalence correction for Compact List and Editorial Sections.\
**Final status:** RC04 visual gate PASS per Product Owner; M5 staging CSS migration PASS per Product Owner; final 1.3.0 certification and release evidence are recorded in `M6-FINAL-RELEASE-1.3.0.md`.

## Pattern inventory

Exactly 15 patterns remain: nine historical and six editorial. The editorial set is Featured Hero, Editorial Lead, Compact List, Editorial Grid, Editorial Stack, and Editorial Sections. Visual Grid remains absent. Hover Zoom remains an opt-in Core Post Featured Image style.

## RC 04 changes

### Compact List

Compact List now follows the proven SCI Core composition:

```text
Query
└── Post Template
    ├── Row (Core Group, flex/horizontal)
    │   ├── Post Featured Image
    │   └── Group (Core Stack)
    │       ├── Post Title
    │       └── Post Terms
    └── Separator
```

The image uses Core attributes `width:160px`, `aspectRatio:16/9`, and `scale:contain`. The Core Row variation uses `flexWrap:nowrap`, centers items vertically, and uses the small spacing preset. Its text Group uses the same compact block gap; the linked title is small and semibold, with category immediately below. Date and excerpt are absent. There is no custom breakpoint or layout CSS. The visual gate must check narrow widths for overflow because this Core Row stays horizontal.

### Editorial Sections

The pattern has three Core Columns with generic editable Section One/Two/Three headings. Every section contains:

1. Primary Core Query: one post (`perPage:1`, `offset:0`); its Post Template contains exactly Featured Image and Post Title.
2. A Core Separator between the primary story and secondary headlines.
3. Secondary Core Query: two posts (`perPage:2`, `offset:1`); its Post Template contains Post Title and Core Separator only. No image, excerpt, date, or category is included.

All six Queries default to the same post type, descending date order, `inherit:false`, `sticky:ignore`, and empty/default filters. Within each section, primary and secondary Query blocks are independent; users must apply equivalent filters/order/sticky settings to both to preserve the sequence. Offsets avoid repeating the primary post within that pair. Sections may overlap with one another when configured with the same filters; no global deduplication is provided. Columns use Core mobile stacking. Spacing uses Core presets.

## Frozen behavior and limits

Featured Hero, Editorial Lead, Editorial Grid, Editorial Stack, the nine historical patterns, Hover Zoom, Tabs, Accordion, and Icon Library retain their approved behavior. There are no custom SCI blocks, editorial layout CSS, frontend JavaScript, custom PHP Query engine, or external runtime dependencies. The stable 1.2.0 package and its published asset remain untouched. The Product Owner reports manually applying the M5 proposed staging CSS: sections 4–5 were removed, sections 1–3 were retained, and the site remained visually correct with no breakage observed. Codex did not modify staging.

## Static validation evidence

Environment: PHP CLI 8.5.4 and WordPress Core parser/source 7.1.2.

- PHP lint: PASS for all seven runtime PHP files and three test scripts in source and extracted package (PHP CLI 8.5.4).
- Registration smoke: PASS; 15 patterns, 83 icons, two Accordion styles, four Tabs styles, and opt-in Hover Zoom.
- Core parser: PASS on WordPress 7.1.2 for all six editorial patterns.
- Compact List contract tests: Query and Post Template; horizontal Core Group Row with centered alignment and compact gap; Featured Image width 160px, 16:9, contain; nested text Group; title then category; Core Separator; no Post Date or Post Excerpt; Core blocks only.
- Editorial Sections contract tests: three columns/section labels; six Queries with paired `perPage`/offset 1/0 and 2/1; matching query settings other than count/offset; exactly three Featured Image block declarations in primary templates; title blocks in every primary and secondary template; secondary templates contain no image/date/terms/excerpt; separators between primary and secondary and within secondary results.
- Static offsets model posts 1, 2 and 3 only. It does not run database queries, verify live category selection, or prove that independently configured Query blocks remain synchronized.
- Regression checks: PASS for the 1.2 icon/pattern/style inventory and Core Tabs round-trip across all four styles.
- Package validation: PASS. `zip -T` succeeds; extracted package passes PHP lint, registration smoke, all six Core parser checks, and Tabs regression. Root is `sci-design-blocks/`; 102 entries; tests, POC, audit input, node_modules, `.git`, and nested ZIPs are excluded. Compared with RC03, only `README.md` and `includes/editorial-query-patterns.php` differ inside the package.
- Authoritative CSS expected SHA-256: `886d4899b99ad59154f9b59efd2f4be6ccbc584fdce767acb34dccb1bd0524fb`.
- No npm build, npm ci, JS lint, or dependency audit was run: there is no package manifest or JS build source, and RC04 adds neither CSS nor JS nor dependencies. The existing `node_modules` was not needed or used.
- Live WordPress editor/browser visual QA is a Product Owner gate; local WordPress database bootstrap previously failed. Static parser checks do not substitute for the editor gate.

## Product Owner visual gate — MUST INSERT NEW PATTERNS

Patterns are insertion templates and do not update existing content. After installing RC04, delete/ignore earlier test instances and insert **new instances** of both **SCI Posts — Compact List** and **SCI Posts — Editorial Sections**. A date appearing in a newly inserted Compact List is a FAIL; an older stored instance may still contain its original markup and is not a valid RC04 test.

Use real SCI content in WordPress 7.1+ / PHP 7.4+, preferably Twenty Twenty-Five and one other Core block theme. Check desktop, tablet, and mobile. Save and reopen both new instances.

1. **Compact List vs current SCI sidebar:** confirm the same visual family and similar information density; image on the left has comparable visual prominence (around 160px at reference desktop width), title/category sit together on the right, no date or excerpt, no large gaps, and a separator per entry. Try a photo, diagram/screenshot with text, long technical title, missing image, and narrow container. The Core Row is non-wrapping; confirm the image and title remain readable and there is no horizontal overflow at the actual sidebar width.
2. **Editorial Sections:** set a different category/filter on each section's primary Query and apply the same settings to that section's secondary Query. Confirm three desktop columns, each with one image-and-title lead followed by two distinct Query-generated title-only posts, separators in the expected positions, and Core stacking on mobile. Confirm there are no images or extra metadata in the secondary results. Identical filters across sections may repeat posts.
3. Confirm normal Core editing, save/reopen validity, and theme typography/colors/spacing. Do not judge RC04 using old RC03 instances.
4. Confirm the historical nine patterns and other four editorial patterns remain present and unchanged. Hover Zoom remains opt-in/off by default and reduced-motion aware.

Record discrepancies with theme, viewport, and whether the pattern was newly inserted. Do not remove or migrate SCI site CSS during this gate.
