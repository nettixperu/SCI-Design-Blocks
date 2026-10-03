# SCI Design Blocks 1.3 — Editorial Query Library RC / Visual Gate

**Artifact:** `sci-design-blocks-1.3.0-rc-02.zip`

**Artifact SHA-256:** `e8673b83daf193b5c160ea11eb3aef199acdd9a034e3d5a3c8df43c960f966e2`

**Plugin metadata:** `1.3.0-dev` (WordPress 7.1+, PHP 7.4+)

**Scope:** M1–M4 refinement after Product Owner review of RC 01.

**Status:** static gates pass; awaiting Product Owner visual review. This is not a release or a claim that CSS migration is complete.

## Included Query patterns

The plugin registers exactly 14 insertion patterns in the existing category: the nine historical patterns plus these five editorial Core Query patterns:

- SCI Posts — Featured Hero: one latest post, `inherit:false`, `sticky:ignore`, no pagination.
- SCI Posts — Editorial Lead: primary query (`perPage:1`, `offset:0`) with the only default featured image; secondary query (`perPage:3`, `offset:1`) contains text-first headlines and metadata, no image or excerpt. Both are date-descending, `inherit:false`, `sticky:ignore`; no pagination.
- SCI Posts — Compact List: five posts; Core Columns, linked Featured Image using `aspectRatio:16/9` and `scale:contain`, small Core title/metadata preset and Core Separator.
- SCI Posts — Editorial Grid: three horizontal cards, Core three-column Query layout, linked 4:3 Featured Image, medium Core title preset, concise excerpt and small metadata preset.
- SCI Posts — Editorial Stack: three vertical posts, linked 16:9 image, medium Core title preset, excerpt, small metadata preset and Core Separator.

Visual Grid was removed before 1.3 release and has no compatibility alias; Editorial Stack replaces it. The Grid and Stack differ by orientation and content treatment, not merely image dimensions.

All patterns are unsynced Core block compositions. They contain no SCI branding/colors or custom Query blocks. Query controls remain editable with Gutenberg Core. Editorial Lead does not synchronize its separate queries: to preserve its initial no-duplicate sequence, users changing filters/order/sticky must make equivalent changes to both loops.

## Optional image style

`SCI — Hover Zoom` is registered only for `core/post-featured-image`; it is opt-in per block and absent from all pattern defaults. Its small scoped CSS uses a 1.05 transform with overflow clipping, no `!important`, no image/content changes, no JS, and disables motion under `prefers-reduced-motion: reduce`. Deactivating the plugin can remove this optional presentation, while the image and Core blocks remain.

## Static validation evidence

Environment: PHP CLI 8.5.4; local WordPress Core parser/source 7.1.2. PHP 7.4 is the declared minimum; no separate PHP 7.4 binary was available for execution.

- PHP lint: PASS for plugin/bootstrap, includes, icon manifest and test scripts.
- Registration smoke: PASS; one existing category, all 14 patterns, 83 icons, two Accordion styles, four Tabs styles, and one opt-in Featured Image style.
- WordPress Core block parser: PASS for all five final patterns; recursive block names are Core-only; query defaults, offsets, counts, no-pagination and required blocks match expectations. Lead fixture maps primary to post 1 and secondary to posts 2–4 based on attributes only; it does not test a database query.
- Registration inventory check: exactly 14 unique expected slugs; Visual Grid absent.
- Long headline fixtures cover the three Spanish technical titles from the visual review. Markup has no truncation/line-clamp rules; actual wrapping remains a visual-gate check.
- 1.2 regression: PASS for existing icon/pattern/style registration and Core Tabs parse/serialize fixtures.
- Package validation: root is `sci-design-blocks/`; no tests, source-audit files, docs, prior ZIPs or development directories are included. `zip -T` PASS; extracted PHP lint, pattern registration smoke, Core parser checks and Tabs regression PASS.
- CSS static review: scoped to Core Featured Image Hover Zoom, no `!important`, includes reduced-motion handling.
- No npm build, npm ci, JS lint or dependency audit was run: the project has no package manifest or JavaScript build source, and this change adds no JavaScript or dependencies. `node_modules` was absent. No build tooling was introduced.
- No live WordPress activation/editor/browser test was possible: bootstrapping the local WordPress installation returned “Error establishing a database connection.” The ZIP smoke is static and does not substitute for the manual UI gate below.

The authoritative SCI CSS audit input remains unchanged (SHA-256 `886d4899b99ad59154f9b59efd2f4be6ccbc584fdce767acb34dccb1bd0524fb`). No site CSS or `theme.json` was edited or removed. CSS deletion decisions remain reserved for M5 after visual evidence.

RC 01 remains unchanged (SHA-256 `799270c62a3f66757ec574449dcf2d4dde027ba34833e77a4cff450c1d691ce1`). The immutable 1.2.0 ZIP was not found in the workspace during RC 02 packaging; it was not recreated or modified. Its last recorded SHA-256 is `8e2b7ababbcbb793b7eed82f07f387fa10ea113381a3cd89bb627db0dd19a803`.

## Compact Product Owner visual gate

Install the RC ZIP on WordPress 7.1+ with PHP 7.4+ and a disposable site. Use Twenty Twenty-Five, then one other Core block theme (Twenty Twenty-Four or Twenty Twenty-Three). Check desktop, tablet and mobile widths.

1. In the inserter, confirm exactly these five SCI Posts patterns appear: Featured Hero, Editorial Lead, Compact List, Editorial Grid and Editorial Stack. Confirm Visual Grid is absent. Insert each; save and reopen; verify blocks remain valid and editable.
2. Featured Hero: confirm one latest post, linked image/title, side-by-side desktop composition and Core mobile stacking.
3. Editorial Lead: verify only the primary story has a featured image. Confirm its headline dominates and the three secondary headlines are text-first. Fixture expectation is primary post 1, secondary posts 2–4. If changing a category/order in one loop, update the other equivalently to preserve the sequence.
4. Compact List: inspect a photo, diagram/screenshot with embedded text, portrait image and a post without a featured image. Confirm `contain` does not crop; check small title/metadata and readable row/stack behavior at mobile width.
5. Editorial Grid: verify three horizontal cards with image, compact but prominent title, excerpt and metadata. Check these real title lengths without truncation: “CAPEX vs OPEX en infraestructura TI: comprar servidores o consumir nube”; “Housing, servidor dedicado o nube privada: ¿qué opción conviene para una empresa?”; “VPN propia vs VPN administrada: costos, operación y responsabilidades”.
6. Editorial Stack: verify three vertically repeated entries, each with large image, title, excerpt, metadata and Core separator. Confirm its vertical sequence is visibly distinct from the horizontal Grid.
7. On a Featured Image block, confirm Hover Zoom is not default. Select it from Styles, verify subtle hover/focus zoom; enable reduced motion in the OS/browser and confirm motion is disabled.
8. Deactivate/reactivate the plugin. Confirm inserted Queries and Core layout remain editable. The optional SCI Hover Zoom appearance may disappear while inactive.
9. Regression smoke: confirm the original nine patterns, icon collection, Accordion styles, Tabs styles and Tabs interaction/presentation remain intact.

Record any visual discrepancy and its theme/viewport before starting M5. Do not remove SCI site CSS during this gate.
