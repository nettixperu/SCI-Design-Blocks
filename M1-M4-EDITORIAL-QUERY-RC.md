# SCI Design Blocks 1.3 — Editorial Query Library RC / Visual Gate

**Artifact:** `sci-design-blocks-1.3.0-rc-01.zip`

**Plugin metadata:** `1.3.0-dev` (WordPress 7.1+, PHP 7.4+)

**Scope:** M1 Featured Hero; M2 Editorial Lead and Compact List; M3 Editorial Grid and Visual Grid; M4 optional Hover Zoom.

**Status:** static gates pass; awaiting Product Owner visual review. This is not a release or a claim that CSS migration is complete.

## Included Query patterns

The plugin now registers 14 insertion patterns in the existing category: the nine original 1.2 patterns plus five editorial Core Query patterns:

- SCI Posts — Featured Hero: one latest post, `inherit:false`, `sticky:ignore`, no pagination.
- SCI Posts — Editorial Lead: primary query (`perPage:1`, `offset:0`) and secondary query (`perPage:3`, `offset:1`), both date-descending, `inherit:false`, `sticky:ignore`; no pagination.
- SCI Posts — Compact List: five posts; Core Columns, linked Featured Image using `aspectRatio:16/9` and `scale:contain`, Post Title, Post Terms, Post Date and Core Separator.
- SCI Posts — Editorial Grid: six posts, Core three-column Query layout, linked 4:3 Featured Image, title and category/date metadata.
- SCI Posts — Visual Grid: six posts, Core three-column Query layout, linked 16:9 image and title only. Its image-led hierarchy is intentionally distinct from Editorial Grid.

All patterns are unsynced Core block compositions. They contain no SCI branding/colors or custom Query blocks. Query controls remain editable with Gutenberg Core. Editorial Lead does not synchronize its separate queries: to preserve its initial no-duplicate sequence, users changing filters/order/sticky must make equivalent changes to both loops.

## Optional image style

`SCI — Hover Zoom` is registered only for `core/post-featured-image`; it is opt-in per block and absent from all pattern defaults. Its small scoped CSS uses a 1.05 transform with overflow clipping, no `!important`, no image/content changes, no JS, and disables motion under `prefers-reduced-motion: reduce`. Deactivating the plugin can remove this optional presentation, while the image and Core blocks remain.

## Static validation evidence

Environment: PHP CLI 8.5.4; local WordPress Core parser/source 7.1.2. PHP 7.4 is the declared minimum; no separate PHP 7.4 binary was available for execution.

- PHP lint: PASS for plugin/bootstrap, includes, icon manifest and test scripts.
- Registration smoke: PASS; one existing category, all 14 patterns, 83 icons, two Accordion styles, four Tabs styles, and one opt-in Featured Image style.
- WordPress Core block parser: PASS for all five patterns; recursive block names are Core-only; query defaults, offsets, counts, no-pagination and required blocks match expectations.
- 1.2 regression: PASS for existing icon/pattern/style registration and Core Tabs parse/serialize fixtures.
- Package validation: `zip -T` PASS; extracted PHP lint, pattern registration smoke, Core parser checks and Tabs regression PASS.
- CSS static review: scoped to Core Featured Image Hover Zoom, no `!important`, includes reduced-motion handling.
- No npm build, npm ci, JS lint or dependency audit was run: the project has no package manifest or JavaScript build source, and this change adds no JavaScript or dependencies. `node_modules` was absent. No build tooling was introduced.
- No live WordPress activation/editor/browser test was possible: bootstrapping the local WordPress installation returned “Error establishing a database connection.” The ZIP smoke is static and does not substitute for the manual UI gate below.

The authoritative SCI CSS audit input remains unchanged (SHA-256 `886d4899b99ad59154f9b59efd2f4be6ccbc584fdce767acb34dccb1bd0524fb`). No site CSS or `theme.json` was edited or removed. CSS deletion decisions remain reserved for M5 after visual evidence.

The immutable 1.2.0 ZIP also remains unchanged (SHA-256 `8e2b7ababbcbb793b7eed82f07f387fa10ea113381a3cd89bb627db0dd19a803`).

## Compact Product Owner visual gate

Install the RC ZIP on WordPress 7.1+ with PHP 7.4+ and a disposable site. Use Twenty Twenty-Five, then one other Core block theme (Twenty Twenty-Four or Twenty Twenty-Three). Check desktop, tablet and mobile widths.

1. In the inserter, confirm each of the five SCI Posts patterns appears. Insert each; save and reopen; verify blocks remain valid and editable.
2. Featured Hero: confirm one latest post, linked image/title, side-by-side desktop composition and Core mobile stacking.
3. Editorial Lead: confirm a different primary and secondary post under defaults. Change a category/order in one loop and verify the documented user contract: update the other loop equivalently if no-duplicate sequencing is desired.
4. Compact List: inspect a photo, diagram/screenshot with embedded text, portrait image and a post without a featured image. Confirm `contain` does not crop; check readable row/stack behavior at mobile width.
5. Editorial Grid and Visual Grid: confirm three-column desktop layout adapts at narrow widths; compare the metadata-rich balanced card against the image-dominant composition.
6. On a Featured Image block, confirm Hover Zoom is not default. Select it from Styles, verify subtle hover/focus zoom; enable reduced motion in the OS/browser and confirm motion is disabled.
7. Deactivate/reactivate the plugin. Confirm inserted Queries and Core layout remain editable. The optional SCI Hover Zoom appearance may disappear while inactive.
8. Regression smoke: confirm the original nine patterns, icon collection, Accordion styles, Tabs styles and Tabs interaction/presentation remain intact.

Record any visual discrepancy and its theme/viewport before starting M5. Do not remove SCI site CSS during this gate.
